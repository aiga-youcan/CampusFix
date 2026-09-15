<?php

namespace Database\Factories;

use App\Models\Signalement;
use App\Models\User;
use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignalementFactory extends Factory
{
    protected $model = Signalement::class;

    public function definition(): array
    {
        $incidents = [
            ['title' => 'Vidéoprojecteur ne s\'allume plus', 'category' => 'electricite', 'severity' => 'moyen'],
            ['title' => 'Fuite d\'eau sous le lavabo', 'category' => 'plomberie', 'severity' => 'critique'],
            ['title' => 'Prise électrique murale arrachée', 'category' => 'electricite', 'severity' => 'critique'],
            ['title' => 'Chaise cassée et table bancale', 'category' => 'mobilier', 'severity' => 'faible'],
            ['title' => 'Câble RJ45 réseau endommagé', 'category' => 'reseau', 'severity' => 'moyen'],
            ['title' => 'Serrure de porte d\'amphi bloquée', 'category' => 'autre', 'severity' => 'moyen'],
            ['title' => 'Climatisation en panne - surchauffe', 'category' => 'electricite', 'severity' => 'critique'],
            ['title' => 'Éclairage néon clignotant', 'category' => 'electricite', 'severity' => 'faible'],
            ['title' => 'Robinet de radiateur qui fuit', 'category' => 'plomberie', 'severity' => 'moyen'],
            ['title' => 'Tableau blanc décroché du mur', 'category' => 'mobilier', 'severity' => 'faible'],
        ];

        $incident = fake()->randomElement($incidents);
        $salle = Salle::inRandomOrder()->first() ?? Salle::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'title' => $incident['title'],
            'description' => fake()->paragraph(2),
            'location' => $salle->name . ' (' . $salle->building . ')',
            'category' => $incident['category'],
            'severity' => $incident['severity'],
            'status' => fake()->randomElement(['signale', 'pris_en_charge', 'resolu']),
        ];
    }
}
