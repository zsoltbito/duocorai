<?php

namespace App\Services\Mail;

use App\Models\InboundEmail;
use App\Models\TicketDecision;
use App\Services\Ai\AiClientInterface;

class ReplyBuilder
{
    public function __construct(private readonly AiClientInterface $ai) {}

    public function build(InboundEmail $email, TicketDecision $decision): array
    {
        if ($decision->created_ticket) {
            return $this->ticketCreated($email, $decision);
        }

        return $this->missingInfo($email, $decision);
    }

    private function ticketCreated(
        InboundEmail $email,
        TicketDecision $decision,
    ): array {
        $prompt = <<<PROMPT
        Write a polite, professional reply email in Hungarian.

        Context:
        - The user's email resulted in a support ticket.
        - Reassure them the issue is being handled.
        - Mention ticket ID if available.
        - Keep it friendly and concise.

        Ticket ID: {$decision->redmine_issue_id}
        Original issue:
        {$email->getCleanBody()}
        PROMPT;

        $text =
            $this->ai->analyze($prompt)["text"] ??
            "Köszönjük a megkeresést, a hibajegyet rögzítettük. Hamarosan jelentkezünk.";

        return [
            "subject" => "Hibajegy rögzítve",
            "body" => $text,
            "type" => "ticket_created",
        ];
    }

    private function missingInfo(
        InboundEmail $email,
        TicketDecision $decision,
    ): array {
        $analysis = $email->aiAnalysis;

        $missing = implode(", ", $analysis->missing_info ?? []);

        $prompt = <<<PROMPT
        Write a polite Hungarian email asking for missing information.

        Missing information:
        {$missing}

        Original message:
        {$email->getCleanBody()}
        PROMPT;

        $text =
            $this->ai->analyze($prompt)["text"] ??
            "A kérés feldolgozásához további információra van szükség: {$missing}";

        return [
            "subject" => "További információ szükséges",
            "body" => $text,
            "type" => "missing_info",
        ];
    }
}
