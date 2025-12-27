<?php

namespace App\Services\Ticketing;

use App\Models\InboundEmail;
use App\Models\TicketDecision;
use App\Services\Redmine\RedmineClient;

class TicketDecisionService
{
    public function __construct(
        private readonly RedmineClient $redmine
    ) {}

    public function handle(InboundEmail $email): TicketDecision
    {
        $analysis = $email->aiAnalysis;

        if (!$analysis) {
            return TicketDecision::create([
                'inbound_email_id' => $email->id,
                'ai_analysis_id' => null,
                'created_ticket' => false,
                'reason' => 'No AI analysis',
            ]);
        }

        if ($analysis->confidence < 60) {
            return $this->reject($email, $analysis->id, 'Low confidence');
        }

        if (!empty($analysis->missing_info)) {
            return $this->reject($email, $analysis->id, 'Missing information');
        }

        $issue = $this->redmine->createIssue(
            config('redmine.default_project'),
            $analysis->title ?? 'New issue',
            $analysis->summary ?? ''
        );

        $email->markProcessed();

        return TicketDecision::create([
            'inbound_email_id' => $email->id,
            'ai_analysis_id' => $analysis->id,
            'created_ticket' => true,
            'redmine_issue_id' => $issue['id'] ?? null,
            'redmine_project' => config('redmine.default_project'),
        ]);
    }

    private function reject(InboundEmail $email, int $analysisId, string $reason): TicketDecision
    {
        return TicketDecision::create([
            'inbound_email_id' => $email->id,
            'ai_analysis_id' => $analysisId,
            'created_ticket' => false,
            'reason' => $reason,
        ]);
    }
}
