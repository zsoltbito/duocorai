<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("monitoring_events", function (Blueprint $table) {
            $table->id();

            $table->string("source")->index(); // nagios|librenms
            $table->string("external_id")->index(); // host/service id, alert id, etc
            $table->string("entity")->nullable()->index(); // host/device
            $table->string("check")->nullable()->index(); // service/metric
            $table->string("state_from")->nullable();
            $table->string("state_to")->nullable();
            $table->timestamp("happened_at")->nullable()->index();

            $table->string("fingerprint")->unique(); // dedupe
            $table->json("payload")->nullable();

            $table->unsignedBigInteger("redmine_issue_id")->nullable()->index();
            $table->string("status")->default("new")->index(); // new|ticketed|commented|failed
            $table->text("error")->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("monitoring_events");
    }
};
