<?php

namespace Database\Factories;

use App\Models\Intervention;
use App\Models\Signalement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterventionFactory extends Factory
{
    protected $model = Intervention::class;

    public function definition(): array
    {
        $reports = [
            'Remplacement de la pièce défectueuse et test opérationnel réussi.',
            'Diagnostic effectué, câblage réajusté et remise sous tension sécurisée.',
            'Changement du joint de tuyauterie, vérification de l\'étanchéité.',
            'Resserrage des fixations et nettoyage de l\'équipement.',
            'Vérification du tableau électrique et changement du fusible de protection.',
        ];

        return [
            'signalement_id' => Signalement::factory(),
            'technicien_id' => User::factory(),
            'notes' => fake()->randomElement($reports),
            'duration_minutes' => fake()->randomElement([15, 30, 45, 60, 90, 120]),
            'status' => 'termine',
        ];
    }
}
