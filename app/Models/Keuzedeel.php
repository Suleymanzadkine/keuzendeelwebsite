<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuzedeel extends Model
{
    use HasFactory;

    protected $table = 'keuzedelen';

    protected $fillable = [
        'naam',
        'beschrijving',
        'min_deelnemers',
        'max_deelnemers',
        'periode',
        'is_active',
        'allow_multiple',
        'low_notified_at',
    ];

    protected $casts = [
        'low_notified_at' => 'datetime',
    ];

    public function inschrijvingen()
    {
        return $this->hasMany(Inschrijving::class);
    }

    public function isBelowMinimum()
    {
        return $this->aantalIngeschreven() < $this->min_deelnemers;
    }

    public function actieveInschrijvingen()
    {
        return $this->hasMany(Inschrijving::class)->where('status', 'ingeschreven');
    }

    public function isVol()
    {
        return $this->actieveInschrijvingen()->count() >= $this->max_deelnemers;
    }

    public function aantalIngeschreven()
    {
        return $this->actieveInschrijvingen()->count();
    }
}
