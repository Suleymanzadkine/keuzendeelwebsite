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

    public function opleidingen()
    {
        return $this->belongsToMany(\App\Models\Opleiding::class, 'keuzedeel_opleiding');
    }

    public function isVol()
    {
        return $this->actieveInschrijvingen()->count() >= $this->max_deelnemers;
    }

    public function aantalIngeschreven()
    {
        return $this->actieveInschrijvingen()->count();
    }

    public function isActief()
    {
        return $this->is_active === true;
    }

    public function isBeschikbaar()
    {
        return $this->isActief() && !$this->isVol();
    }

    public function scopeActief($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBeschikbaar($query)
    {
        return $query->where('is_active', true)
            ->whereRaw('(SELECT COUNT(*) FROM inschrijvingen WHERE keuzedeel_id = keuzedelen.id AND status = "ingeschreven") < max_deelnemers');
    }
}
