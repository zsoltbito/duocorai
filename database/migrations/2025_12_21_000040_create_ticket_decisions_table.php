<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_decisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inbound_email_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ai_analysis_id')->constrained()->cascadeOnDelete();

            $table->boolean('created_ticket')->default(false);
            $table->string('reason')->nullable();

            $table->string('redmine_issue_id')->nullable();
            $table->string('redmine_project')->nullable();

            $table->timestamps();

            $table->unique('inbound_email_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_decisions');
    }
};
