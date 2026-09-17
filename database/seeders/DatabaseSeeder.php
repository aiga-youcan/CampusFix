<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Salle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Les Rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrateur',
            'description' => 'Supervision globale du campus'
        ]);

        $techRole = Role::firstOrCreate(['name' => 'technicien'], [
            'display_name' => 'Technicien de Maintenance',
            'description' => 'Prise en charge et réparations'
        ]);

        $demandeurRole = Role::firstOrCreate(['name' => 'demandeur'], [
            'display_name' => 'Demandeur',
            'description' => 'Étudiant ou personnel déclarant des incidents'
        ]);

        // 2. Les Comptes Officiels Fixes
        $admin = User::firstOrCreate(['email' => 'admin@ccfbs.usms.ac.ma'], [
            'name' => 'Direction CC FBS (Dr. Alami)',
            'password' => Hash::make('password'),
        ]);
        if (!$admin->hasRole('admin')) {
            $admin->addRole('admin');
        }

        $technicien = User::firstOrCreate(['email' => 'technicien@ccfbs.usms.ac.ma'], [
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

        // 3. Les Salles Réelles du Campus (Fixes)
        $salles = [
            ['code' => 'INFO-101', 'name' => 'Salle Info 1', 'building' => 'Bâtiment Informatique', 'floor' => '1er Étage', 'capacity' => 30, 'type' => 'tp_informatique'],
            ['code' => 'INFO-102', 'name' => 'Salle Info 2', 'building' => 'Bâtiment Informatique', 'floor' => '1er Étage', 'capacity' => 30, 'type' => 'tp_informatique'],
            ['code' => 'ELEC-201', 'name' => 'Labo Électronique', 'building' => 'Bâtiment Génie Électrique', 'floor' => 'RDC', 'capacity' => 25, 'type' => 'tp_electronique'],
            ['code' => 'AMPHI-A', 'name' => 'Amphi Ibn Khaldoun', 'building' => 'Bloc Amphis', 'floor' => 'RDC', 'capacity' => 150, 'type' => 'amphitheatre'],
            ['code' => 'AMPHI-B', 'name' => 'Amphi Al Khawarizmi', 'building' => 'Bloc Amphis', 'floor' => 'RDC', 'capacity' => 150, 'type' => 'amphitheatre'],
            ['code' => 'COURS-301', 'name' => 'Salle de Cours 301', 'building' => 'Bâtiment Économie & Gestion', 'floor' => '2ème Étage', 'capacity' => 40, 'type' => 'cours'],
        ];

        foreach ($salles as $salle) {
            Salle::firstOrCreate(['code' => $salle['code']], $salle);
        }

        // Zéro génération aléatoire ! Base 100% propre.
    }
}