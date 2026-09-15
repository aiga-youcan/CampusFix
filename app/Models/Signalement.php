<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    public function interventions()
    {
        return $this->hasMany(Intervention::class)->latest();
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'resolu' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'pris_en_charge' => 'bg-amber-100 text-amber-800 border-amber-300',
            default => 'bg-sky-100 text-sky-800 border-sky-300',
        };
    }

    public function getSeverityColorAttribute()
    {
        return match ($this->severity) {
            'critique' => 'bg-rose-100 text-rose-800 border-rose-300',
            'moyen' => 'bg-orange-100 text-orange-800 border-orange-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}