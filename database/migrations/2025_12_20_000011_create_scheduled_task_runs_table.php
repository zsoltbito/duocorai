<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("scheduled_task_runs", function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId("scheduled_task_id")
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp("started_at");
            $table->timestamp("finished_at")->nullable();

            // running|success|failed|skipped
            $table->string("status")->default("running");

            $table->longText("output")->nullable();
            $table->text("error")->nullable();

            $table->unsignedInteger("duration_ms")->nullable();

            $table->timestamps();

            $table->index(["status", "started_at"]);
            $table->index(["scheduled_task_id", "started_at"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("scheduled_task_runs");
    }
};
