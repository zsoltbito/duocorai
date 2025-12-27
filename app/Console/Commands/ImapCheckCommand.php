<?php

namespace App\Console\Commands;

use App\Models\InboundEmail;
use App\Services\Scheduler\TaskRuntime;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Exceptions\ImapServerErrorException;
use Throwable;

class ImapCheckCommand extends Command
{
    protected $signature = "imap:check {--runId=}";
    protected $description = "Check IMAP inbox and store new emails";

    public function handle(): int
    {
        $runtime = TaskRuntime::fromRunId($this->option("runId"));
        $runtime?->step("IMAP check started");

        $this->info("[IMAP] Kapcsolódás indítása…");

        try {
            $client = Client::account("default");
            $client->connect();

            $this->info("[IMAP] Kapcsolódás sikeres");
            $runtime?->step("IMAP connected");

            $folder = $client->getFolder("INBOX");
            $this->line("[IMAP] Folder: INBOX");

            // statisztika
            $totalCount = $folder->messages()->all()->count();
            $unseenQuery = $folder->query()->unseen();

            $unseenCount = $unseenQuery->count();

            $this->line("[IMAP] Összes levél a fiókban: {$totalCount}");
            $this->line("[IMAP] Új (unseen) levelek: {$unseenCount}");

            $runtime?->step("IMAP stats", [
                "total" => $totalCount,
                "unseen" => $unseenCount,
            ]);

            if ($unseenCount === 0) {
                $this->line("[IMAP] Nincs új levél");
                $runtime?->step("No new emails");
                return self::SUCCESS;
            }

            $messages = $unseenQuery
                ->since(CarbonImmutable::now()->subDays(2))
                ->get();

            $saved = 0;
            $skipped = 0;

            foreach ($messages as $message) {
                $messageId = (string) $message->getMessageId();

                if (!$messageId) {
                    $skipped++;
                    continue;
                }

                if (InboundEmail::where("message_id", $messageId)->exists()) {
                    $skipped++;
                    continue;
                }

                InboundEmail::create([
                    "message_id" => $messageId,
                    "from_email" => optional($message->getFrom()[0])->mail,
                    "from_name" => optional($message->getFrom()[0])->personal,
                    "subject" => $message->getSubject(),
                    "body_text" => $message->getTextBody(),
                    "body_html" => $message->getHTMLBody(),
                    "received_at" => $message->getDate()?->toString(), // 👈 FIX
                    "imap_uid" => $message->getUid(),
                    "imap_folder" => "INBOX",
                ]);

                $saved++;
            }

            $this->info("[IMAP] Mentett új levelek: {$saved}");
            $this->line("[IMAP] Kihagyott (duplikált / hibás): {$skipped}");

            $runtime?->step("IMAP finished", [
                "saved" => $saved,
                "skipped" => $skipped,
            ]);

            return self::SUCCESS;
        } catch (ImapServerErrorException $e) {
            $msg = $this->normalizeImapError($e->getMessage());

            $this->error("[IMAP] Kapcsolódási hiba");
            $this->error("[IMAP] {$msg}");

            $runtime?->step("IMAP error", ["error" => $msg]);

            Log::warning("IMAP server error", ["error" => $e->getMessage()]);

            return self::FAILURE;
        } catch (Throwable $e) {
            $this->error("[IMAP] Váratlan hiba");
            $this->error($e->getMessage());

            $runtime?->step("IMAP unexpected error", [
                "error" => $e->getMessage(),
            ]);

            Log::error("IMAP unexpected error", ["exception" => $e]);

            return self::FAILURE;
        }
    }

    private function normalizeImapError(string $raw): string
    {
        if (str_contains($raw, "AUTHENTICATIONFAILED")) {
            return "Authentication failed – hibás felhasználónév/jelszó";
        }

        if (str_contains($raw, "Connection refused")) {
            return "IMAP kapcsolat elutasítva – host/port hiba";
        }

        return "IMAP szerver hiba – részletek a logban";
    }
}
