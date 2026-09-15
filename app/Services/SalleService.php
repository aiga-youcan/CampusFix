<?php

namespace App\Services;

use App\Models\Salle;
use Illuminate\Database\Eloquent\Collection;

class SalleService
{
    public function getGroupedByBuilding()
    {
        return Salle::orderBy('building')->orderBy('name')->get()->groupBy('building');
    }

    public function getAll(): Collection
    {
        return Salle::orderBy('name')->get();
    }

    public function findById(int $id): ?Salle
    {
        return Salle::find($id);
    }
}