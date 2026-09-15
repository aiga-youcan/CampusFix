<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Salle;
use App\Models\Signalement;
use App\Models\Intervention;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Initialisation des Rôles RBAC
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrateur',
            'description' => 'Supervision globale du campus et gestion des droits'
        ]);

        $techRole = Role::firstOrCreate(['name' => 'technicien'], [
            'display_name' => 'Technicien de Maintenance',
            'description' => 'Prise en charge et exécution des interventions techniques'
        ]);

        $demandeurRole = Role::firstOrCreate(['name' => 'demandeur'], [
            'display_name' => 'Demandeur',
            'description' => 'Utilisateur déclarant des incidents sur le campus'
        ]);

        // 2. Utilisateurs de test officiels
        $admin = User::firstOrCreate(['email' => 'admin@CCfbs.usms.ac.ma'], [
            'name' => 'Direction CC FBS (Dr. Alami)',
            'password' => Hash::make('password'),
        ]);
        if (!$admin->hasRole('admin')) {
            $admin->addRole('admin');
        }

        $technicien = User::firstOrCreate(['email' => 'technicien@CCfbs.usms.ac.ma'], [
            'name' => 'Karim Alami (Technicien)',
            'password' => Hash::make('password'),
        ]);
        if (!$technicien->hasRole('technicien')) {
            $technicien->addRole('technicien');
        }

        $etudiant = User::firstOrCreate(['email' => 'etudiant@usms.ma'], [
            'name' => 'Rida Sabrar (Étudiant)',
            'password' => Hash::make('password'),
        ]);
        if (!$etudiant->hasRole('demandeur')) {
            $etudiant->addRole('demandeur');
        }

        // 3. Génération des Salles via Factory
        $salles = Salle::factory()->count(12)->create();

        // 4. Génération d'utilisateurs demandeurs supplémentaires via Factory
        $demandeurs = User::factory()->count(6)->create();
        foreach ($demandeurs as $dem) {
            $dem->addRole('demandeur');
        }

        // 5. Génération des Signalements via Factory
        $signalements = Signalement::factory()->count(20)->create();

        // 6. Génération des Interventions réelles via Factory pour les signalements en cours ou résolus
        foreach ($signalements as $sig) {
            if (in_array($sig->status, ['pris_en_charge', 'resolu'])) {
                Intervention::factory()->create([
                    'signalement_id' => $sig->id,
                    'technicien_id' => $technicien->id,
                    'status' => 'termine',
                ]);
            }
        }
    }
}
