<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salle_id',
        'title',
        'description',
        'location',
        'category',
        'severity',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'resolu' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'pris_en_charge' => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'resolu' => 'Résolu',
            'pris_en_charge' => 'En cours',
            default => 'Signalé',
        };
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critique' => 'bg-rose-50 text-rose-700 border-rose-200',
            'moyen' => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }

    public function getSeverityLabelAttribute(): string
    {
        return match($this->severity) {
            'critique' => 'Critique',
            'moyen' => 'Moyen',
            default => 'Faible',
        };
    }
}
