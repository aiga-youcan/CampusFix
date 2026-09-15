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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('salle_id')->nullable()->constrained('salles')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->enum('category', ['plomberie', 'electricite', 'mobilier', 'reseau', 'autre'])->default('autre');
            $table->enum('severity', ['faible', 'moyen', 'critique'])->default('moyen');
            $table->enum('status', ['signale', 'pris_en_charge', 'resolu'])->default('signale');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};
