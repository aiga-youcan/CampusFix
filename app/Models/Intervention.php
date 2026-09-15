<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'signalement_id',
        'technicien_id',
        'notes',
        'duration_minutes',
        'status',
    ];

    public function signalement()
    {
        return $this->belongsTo(Signalement::class);
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }
}
