<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("ai_analyses", function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId("inbound_email_id")
                ->constrained()
                ->cascadeOnDelete();

            $table->string("title")->nullable();
            $table->text("summary")->nullable();
            $table->string("intent")->nullable();

            $table->unsignedTinyInteger("confidence")->default(0);
            $table->json("missing_info")->nullable();

            $table->json("raw_response")->nullable();

            $table->timestamps();

            $table->unique("inbound_email_id");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("ai_analyses");
    }
};
