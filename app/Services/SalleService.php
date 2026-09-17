<?php

namespace App\Services;

use App\Models\Salle;

class SalleService
{
    /**
     * Récupère les salles regroupées par bâtiment pour alimenter les <optgroup> du formulaire
     */
    public function getGroupedByBuilding()
    {
        return Salle::orderBy('building')
            ->orderBy('name')
            ->get()
            ->groupBy('building');
    }
}