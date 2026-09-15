<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Salle;
use App\Models\Signalement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignalementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_signalement(): void
    {
        $user = User::factory()->create();
        $user->addRole('demandeur');
        $salle = Salle::factory()->create();

        $response = $this->actingAs($user)->post('/signalements', [
            'title' => 'Panne électrique amphi',
            'salle_id' => $salle->id,
            'category' => 'electricite',
            'severity' => 'critique',
            'description' => 'Disjoncteur général qui a sauté dans l\'amphi',
        ]);

        $this->assertDatabaseHas('signalements', [
            'title' => 'Panne électrique amphi',
            'severity' => 'critique',
            'status' => 'signale',
        ]);
    }
}
