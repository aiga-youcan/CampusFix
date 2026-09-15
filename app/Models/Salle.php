<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'building',
        'floor',
        'capacity',
        'type',
    ];

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }
}
