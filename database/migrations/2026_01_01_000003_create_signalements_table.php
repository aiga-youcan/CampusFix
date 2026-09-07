<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->enum('category', ['plomberie', 'electricite', 'mobilier', 'autre'])->default('autre');
            $table->enum('severity', ['faible', 'moyen', 'critique'])->default('faible');
            $table->enum('status', ['signale', 'pris_en_charge', 'resolu'])->default('signale');

            // Métadonnées de l'Agent IA Triage
            $table->integer('ai_score')->default(10);
            $table->text('ai_diagnostic')->nullable();
            $table->text('ai_recommended_action')->nullable();
            $table->decimal('ai_estimated_hours', 4, 1)->default(2.0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};
