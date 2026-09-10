<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SignalementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'location' => 'Bâtiment ' . $this->faker->randomElement(['A', 'B', 'C']) . ', Salle ' . $this->faker->numberBetween(101, 305),
            'category' => $this->faker->randomElement(['electricite', 'plomberie', 'mobilier']), // 💡 Ḥyd l-values li kay-dirou conflict
            'severity' => $this->faker->randomElement(['faible', 'moyen', 'critique']),
            'status' => $this->faker->randomElement(['signale', 'pris_en_charge', 'resolu']),
            'ai_score' => $this->faker->numberBetween(50, 99),
            'ai_diagnostic' => $this->faker->sentence(),
            'ai_recommended_action' => $this->faker->sentence(),
            'ai_estimated_hours' => $this->faker->randomFloat(1, 1, 5),
        ];
    }
}