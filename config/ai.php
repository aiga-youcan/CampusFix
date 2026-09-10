<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CampusAiAgent Configuration & Logique de Triage Local
    |--------------------------------------------------------------------------
    | Spécialisé pour l'École Supérieure de Technologie de Fquih Ben Salah (EST FBS - USMS)
    */

    'model' => env('AI_MODEL', 'Local-Heuristic-Engine'),

    // Callback dyal l-analyse li kan f Service
    'evaluate' => function (string $title, string $description, string $location, string $category, string $occupancy = 'today'): array {
        
        $text = mb_strtolower($title . ' ' . $description);
        $locationLower = mb_strtolower($location);

        // 1. Mots-clés critiques et modérés
        $criticalKeywords = [
            'inondation' => 45,
            'fuite d\'eau' => 35,
            'court-circuit' => 50,
            'feu' => 50,
            'flamme' => 50,
            'fumée' => 45,
            'odeur de brûlé' => 40,
            'gaz' => 50,
            'ascenseur' => 45,
            'danger' => 35,
            'électrocution' => 50,
            'plafond' => 40,
            'verre brisé' => 25,
            'porte bloquée' => 20,
            'panne totale' => 30,
        ];

        $moderateKeywords = [
            'fuite' => 20,
            'ampoule' => 10,
            'clim' => 15,
            'climatisation' => 15,
            'chauffage' => 20,
            'bruit anormal' => 15,
            'projecteur' => 15,
            'vidéoprojecteur' => 15,
            'prise cassée' => 18,
            'prise déboîtée' => 18,
            'serrure' => 15,
            'robinet' => 15,
            'chaise cassée' => 8,
            'fenêtre' => 12,
        ];

        // 2. Facteur d'impact des lieux de l'EST FBS
        $locationWeights = [
            'serveur' => 30,
            'datacenter' => 30,
            'agro-alimentaire' => 25,
            'chimie' => 25,
            'informatique' => 25,
            'génie électrique' => 25,
            'labo' => 25,
            'laboratoire' => 25,
            'amphi' => 20,
            'amphithéâtre' => 20,
            'bibliothèque' => 15,
            'bloc administratif' => 15,
            'sanitaires' => 15,
            'cafétéria' => 10,
            'salle' => 12,
        ];

        // 3. Score de base par catégorie technique
        $baseScore = match(strtolower($category)) {
            'electricite' => 30,
            'plomberie' => 25,
            'mobilier' => 10,
            default => 15,
        };

        $matchedSignals = [];
        $keywordScore = 0;

        foreach ($criticalKeywords as $word => $points) {
            if (str_contains($text, $word)) {
                $keywordScore += $points;
                $matchedSignals[] = "Risque critique détecté : '{$word}' (+{$points} pts)";
            }
        }

        foreach ($moderateKeywords as $word => $points) {
            if (str_contains($text, $word)) {
                $keywordScore += $points;
                $matchedSignals[] = "Anomalie technique : '{$word}' (+{$points} pts)";
            }
        }

        $locationBonus = 0;
        foreach ($locationWeights as $locKey => $points) {
            if (str_contains($locationLower, $locKey)) {
                $locationBonus = max($locationBonus, $points);
                $matchedSignals[] = "Lieu prioritaire EST FBS : '{$locKey}' (+{$points} pts)";
            }
        }

        $timeBonus = 0;
        if ($occupancy === 'imminent') {
            $timeBonus = 30;
            $matchedSignals[] = "🚨 Urgence horaire EST FBS : Séance de cours dans moins d'une heure (+30 pts)";
        }

        $finalScore = min(98, max(10, $baseScore + $keywordScore + $locationBonus + $timeBonus));

        if ($finalScore >= 70) {
            $severity = 'critique';
            $estimatedHours = 1.0;
            $diagnostic = ($occupancy === 'imminent')
                ? "Urgence pédagogique EST-FBS : Séance de cours programmée dans moins d'une heure dans cette salle. Risque immédiat d'interruption des enseignements."
                : "Incident critique menaçant la sécurité des usagers ou les équipements pédagogiques de l'EST FBS.";
            $action = "Déployer immédiatement un technicien sur site avant le début de la séance et sécuriser le périmètre.";
        } elseif ($finalScore >= 40) {
            $severity = 'moyen';
            $estimatedHours = 3.0;
            $diagnostic = "Dysfonctionnement nécessitant une prise en charge dans la journée par l'équipe technique de l'EST FBS.";
            $action = "Planifier l'intervention d'un technicien lors de la prochaine tournée de maintenance.";
        } else {
            $severity = 'faible';
            $estimatedHours = 4.5;
            $diagnostic = "Anomalie mineure sans impact pédagogique immédiat sur les cours de l'école.";
            $action = "Traitement dans le planning d'entretien hebdomadaire régulier.";
        }

        if (empty($matchedSignals)) {
            $matchedSignals[] = "Évaluation standard pour la catégorie " . ucfirst($category);
        }

        return [
            'score' => $finalScore,
            'severity' => $severity,
            'diagnostic' => $diagnostic,
            'recommended_action' => $action,
            'estimated_hours' => $estimatedHours,
            'factors' => $matchedSignals,
        ];
    },

];