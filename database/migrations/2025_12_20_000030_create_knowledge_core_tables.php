<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("knowledge_entities", function (Blueprint $table) {
            $table->id();
            $table->string("type")->index(); // host|device|service|location|user|project
            $table->string("key")->index(); // normalized key
            $table->string("label")->nullable();
            $table->json("meta")->nullable();
            $table->timestamps();

            $table->unique(["type", "key"]);
        });

        Schema::create("knowledge_facts", function (Blueprint $table) {
            $table->id();
            $table->string("source")->index(); // email|nagios|librenms|chat
            $table->string("signal")->index(); // DOWN|CRITICAL|HIGH_LATENCY|...
            $table->string("entity_ref")->nullable()->index(); // e.g. host:sw1
            $table->json("context")->nullable();
            $table->unsignedSmallInteger("weight")->default(1);
            $table->timestamp("observed_at")->nullable()->index();
            $table->timestamps();
        });

        Schema::create("knowledge_patterns", function (Blueprint $table) {
            $table->id();
            $table->string("signature")->unique(); // normalized
            $table->string("title");
            $table->longText("solution")->nullable();
            $table->unsignedSmallInteger("confidence")->default(50);
            $table->unsignedInteger("hits")->default(0);
            $table->unsignedInteger("success_hits")->default(0);
            $table->json("meta")->nullable();
            $table->timestamps();
        });

        Schema::create("decision_logs", function (Blueprint $table) {
            $table->id();
            $table->string("source")->index(); // email|nagios|librenms|chat
            $table->string("subject")->nullable()->index(); // message/alert id
            $table->unsignedSmallInteger("confidence")->nullable();
            $table->string("chosen_project")->nullable()->index();
            $table->json("scores")->nullable();
            $table->json("explanation")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("decision_logs");
        Schema::dropIfExists("knowledge_patterns");
        Schema::dropIfExists("knowledge_facts");
        Schema::dropIfExists("knowledge_entities");
    }
};
