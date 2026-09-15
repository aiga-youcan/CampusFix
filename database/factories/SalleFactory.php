<?php

namespace Database\Factories;

use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalleFactory extends Factory
{
    protected $model = Salle::class;

    public function definition(): array
    {
        $buildings = [
            'Bâtiment Informatique',
            'Bâtiment Génie Électrique',
            'Bâtiment Économie & Gestion',
            'Bloc Amphis',
            'Ateliers Maintenance'
        ];
        $types = ['cours', 'tp_informatique', 'tp_electronique', 'amphitheatre', 'reunion'];
        $floors = ['RDC', '1er Étage', '2ème Étage'];

        $building = fake()->randomElement($buildings);
        $code = strtoupper(fake()->lexify('???')) . '-' . fake()->numberBetween(101, 305);

        return [
            'code' => $code,
            'name' => 'Salle ' . $code,
            'building' => $building,
            'floor' => fake()->randomElement($floors),
            'capacity' => fake()->randomElement([25, 30, 40, 60, 150]),
            'type' => fake()->randomElement($types),
        ];
    }
}
