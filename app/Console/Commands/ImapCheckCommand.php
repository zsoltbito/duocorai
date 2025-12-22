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

        $this->line("<info>[IMAP]</info> Kapcsolódás indítása…");

        try {
            $client = Client::account("default");
            $client->connect();

            $this->line("<info>[IMAP]</info> Kapcsolódás sikeres");
            $runtime?->step("IMAP connected");

            $folder = $client->getFolder("INBOX");

            $messages = $folder
                ->query()
                ->unseen()
                ->since(CarbonImmutable::now()->subDays(2))
                ->get();

            $count = 0;

            foreach ($messages as $message) {
                $messageId = (string) $message->getMessageId();

                if (
                    !$messageId ||
                    InboundEmail::where("message_id", $messageId)->exists()
                ) {
                    continue;
                }

                InboundEmail::create([
                    "message_id" => $messageId,
                    "from_email" => optional($message->getFrom()[0])->mail,
                    "from_name" => optional($message->getFrom()[0])->personal,
                    "subject" => $message->getSubject(),
                    "body_text" => $message->getTextBody(),
                    "body_html" => $message->getHTMLBody(),
                    "received_at" => $message->getDate()?->toDateTime(),
                    "imap_uid" => $message->getUid(),
                    "imap_folder" => "INBOX",
                ]);

                $count++;
            }

            $this->line(
                "<info>[IMAP]</info> Új levelek feldolgozva: <comment>{$count}</comment>",
            );
            $runtime?->step("IMAP check finished", ["new_emails" => $count]);

            return self::SUCCESS;
        } catch (ImapServerErrorException $e) {
            // Tipikus: AUTHENTICATIONFAILED, mailbox denied, stb.
            $msg = $this->normalizeImapError($e->getMessage());

            $this->error("[IMAP] Kapcsolódási hiba");
            $this->error("[IMAP] {$msg}");

            $runtime?->step("IMAP error", ["error" => $msg]);

            Log::warning("IMAP authentication/server error", [
                "error" => $e->getMessage(),
            ]);

            return self::FAILURE;
        } catch (Throwable $e) {
            // Egyéb hiba (network, parse, stb.)
            $this->error("[IMAP] Váratlan hiba történt");
            $this->error("[IMAP] " . $e->getMessage());

            $runtime?->step("IMAP unexpected error", [
                "error" => $e->getMessage(),
            ]);

            Log::error("IMAP unexpected error", [
                "exception" => $e,
            ]);

            return self::FAILURE;
        }
    }

    private function normalizeImapError(string $raw): string
    {
        if (str_contains($raw, "AUTHENTICATIONFAILED")) {
            return "Authentication failed – ellenőrizd a felhasználónevet és jelszót";
        }

        if (str_contains($raw, "Connection refused")) {
            return "IMAP kapcsolat elutasítva – host/port hibás";
        }

        return "IMAP szerver hiba – részletek a logban";
    }
}
