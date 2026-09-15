<?php

namespace Tests\Feature;

use App\Models\Signalement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignalementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_signalement()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('signalements.store'), [
            'title' => 'Fuite d\'eau',
            'location' => 'Bâtiment A',
            'category' => 'plomberie',
            'severity' => 'moyen',
            'description' => 'Fuite importante sous l\'évier du laboratoire.',
        ]);

        $response->assertRedirect(route('signalements.index'));

        $this->assertDatabaseHas('signalements', [
            'title' => 'Fuite d\'eau',
            'user_id' => $user->id,
            'status' => 'signale',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_signalements()
    {
        $response = $this->get(route('signalements.index'));

        $response->assertRedirect(route('login'));
    }
}