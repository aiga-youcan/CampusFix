<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Signalement;
use App\Services\AiTriageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignalementAiTriageTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_service_calculates_critical_score_for_dangerous_incident()
    {
        $service = new AiTriageService();
        $result = $service->analyze(
            'Court-circuit majeur',
            'Forte odeur de brûlé et étincelles au niveau du disjoncteur',
            'Laboratoire de physique',
            'electricite'
        );

        $this->assertGreaterThanOrEqual(70, $result['score']);
        $this->assertEquals('critique', $result['severity']);
        $this->assertStringContainsString("Intervention d'urgence", $result['diagnostic']);
    }

    public function test_ai_service_calculates_low_score_for_minor_furniture_issue()
    {
        $service = new AiTriageService();
        $result = $service->analyze(
            'Chaise bancale',
            'Une vis est légèrement desserrée',
            'Couloir Bâtiment B',
            'mobilier'
        );

        $this->assertLessThan(50, $result['score']);
        $this->assertNotEquals('critique', $result['severity']);
    }
}
