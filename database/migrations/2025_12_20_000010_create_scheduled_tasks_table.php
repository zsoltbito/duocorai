<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("scheduled_tasks", function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->string("task_key")->unique();
            $table->boolean("is_enabled")->default(true);

            // artisan | job (most csak artisan-t fogunk seedelni, de készen van a job is)
            $table->string("handler")->default("artisan");
            $table->string("command")->nullable();
            $table->string("job_class")->nullable();

            // everyMinute,everyFiveMinutes,hourly,daily
            $table->string("schedule_type")->default("everyMinute");
            $table->json("payload")->nullable();

            $table->unsignedSmallInteger("timeout_seconds")->default(120);
            $table->unsignedSmallInteger("overlap_lock_seconds")->default(300);

            $table->timestamp("last_run_at")->nullable();
            $table->timestamp("next_run_at")->nullable();
            $table->string("last_status")->nullable(); // success|failed|skipped
            $table->text("last_error")->nullable();

            $table->timestamps();

            $table->index(["is_enabled", "next_run_at"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("scheduled_tasks");
    }
};
