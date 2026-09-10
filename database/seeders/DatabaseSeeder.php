<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Signalement;
use App\Models\Intervention;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des Rôles Laratrust
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrateur',
            'description' => 'Gestion globale du système et supervision'
        ]);

        $techRole = Role::firstOrCreate(['name' => 'technicien'], [
            'display_name' => 'Technicien de Maintenance',
            'description' => 'Interventions sur le terrain et résolution des pannes'
        ]);

        $demandeurRole = Role::firstOrCreate(['name' => 'demandeur'], [
            'display_name' => 'Demandeur',
            'description' => 'Étudiant ou employé signalant des pannes'
        ]);

        // 2. Création des Utilisateurs types
        $admin = User::firstOrCreate(['email' => 'admin@campusfix.test'], [
            'name' => 'Directeur Administratif',
            'password' => Hash::make('password'),
        ]);
        if (!$admin->hasRole('admin')) {
            $admin->addRole($adminRole);
        }

        $technicien = User::firstOrCreate(['email' => 'technicien@campusfix.test'], [
            'name' => 'Karim Alami (Technicien)',
            'password' => Hash::make('password'),
        ]);
        if (!$technicien->hasRole('technicien')) {
            $technicien->addRole($techRole);
        }

        $etudiant = User::firstOrCreate(['email' => 'etudiant@campusfix.test'], [
            'name' => 'Youssef Bennani (Étudiant)',
            'password' => Hash::make('password'),
        ]);
        if (!$etudiant->hasRole('demandeur')) {
            $etudiant->addRole($demandeurRole);
        }

        // 💡 3. Creyi Users 3wadiyin bash l-factory t-stakhdmhum
        $otherUsers = User::factory(5)->create()->each(function ($user) use ($demandeurRole) {
            $user->addRole($demandeurRole);
        });

        // 💡 4. Creyi Signalements b factory b t-rtib s-sḥiḥ
        Signalement::factory(15)->create([
            'user_id' => $otherUsers->random()->id,
        ]);

        // 5. Exemples de Signalements fixes avec Scores IA
        $s1 = Signalement::create([
            'user_id' => $etudiant->id,
            'title' => 'Court-circuit et étincelles au tableau électrique',
            'description' => 'Des étincelles et une forte odeur de brûlé proviennent du tableau dans le laboratoire de chimie.',
            'location' => 'Laboratoire de Chimie - Bâtiment C',
            'category' => 'electricite',
            'severity' => 'critique',
            'status' => 'pris_en_charge',
            'ai_score' => 95,
            'ai_diagnostic' => 'Intervention d\'urgence prioritaire requise. Risque élevé sur la sécurité des usagers ou la continuité de service.',
            'ai_recommended_action' => 'Couper l\'alimentation générale du laboratoire et déployer immédiatement un électricien agréé.',
            'ai_estimated_hours' => 1.5,
        ]);

        Intervention::create([
            'signalement_id' => $s1->id,
            'technicien_id' => $technicien->id,
            'notes' => 'Disjoncteur différentiel coupé à titre préventif. Remplacement des fusibles haute tension en cours.',
            'duration_minutes' => 45,
        ]);

        $s2 = Signalement::create([
            'user_id' => $etudiant->id,
            'title' => 'Fuite d\'eau sous l\'évier des sanitaires',
            'description' => 'Un écoulement d\'eau constant sous le robinet provoque une flaque au sol près des toilettes.',
            'location' => 'Sanitaires 1er étage - Bâtiment A',
            'category' => 'plomberie',
            'severity' => 'moyen',
            'status' => 'signale', // 💡 Bddlha men 'en_attente' l 'signale'
            'ai_score' => 55,
            'ai_diagnostic' => 'Incident modéré nécessitant une prise en charge dans la journée pour éviter une aggravation.',
            'ai_recommended_action' => 'Planifier l\'intervention d\'un plombier lors de la prochaine tournée.',
            'ai_estimated_hours' => 3.0,
        ]);

        $s3 = Signalement::create([
            'user_id' => $etudiant->id,
            'title' => 'Pied de table métallique desserré',
            'description' => 'Une table bancale dans la rangée 4 de l\'amphithéâtre.',
            'location' => 'Amphithéâtre 1',
            'category' => 'mobilier',
            'severity' => 'faible',
            'status' => 'resolu',
            'ai_score' => 38,
            'ai_diagnostic' => 'Dysfonctionnement mineur sans risque sécuritaire direct.',
            'ai_recommended_action' => 'Resserrage et stabilisation du piétement.',
            'ai_estimated_hours' => 0.5,
        ]);

        Intervention::create([
            'signalement_id' => $s3->id,
            'technicien_id' => $technicien->id,
            'notes' => 'Vis de serrage resserrées et embout plastique remis en place. Table de nouveau opérationnelle.',
            'duration_minutes' => 15,
        ]);
    }
}