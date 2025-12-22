<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("external_ticket_links", function (Blueprint $table) {
            $table->id();

            $table->string("source")->index(); // email|nagios|librenms
            $table->string("external_ref")->unique(); // e.g. msgid:.. or nagios:host/service
            $table->unsignedBigInteger("redmine_issue_id")->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("external_ticket_links");
    }
};
