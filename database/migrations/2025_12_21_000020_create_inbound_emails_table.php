<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("inbound_emails", function (Blueprint $table) {
            $table->id();

            $table->string("message_id")->unique();
            $table->string("from_email")->nullable();
            $table->string("from_name")->nullable();
            $table->string("subject")->nullable();

            $table->longText("body_text")->nullable();
            $table->longText("body_html")->nullable();

            $table->timestamp("received_at")->nullable();

            $table->string("imap_uid")->nullable();
            $table->string("imap_folder")->default("INBOX");

            $table->boolean("processed")->default(false);

            $table->timestamps();

            $table->index(["processed", "received_at"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("inbound_emails");
    }
};
