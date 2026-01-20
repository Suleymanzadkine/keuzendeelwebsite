<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Inschrijving;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaties
    public function inschrijvingen()
    {
        return $this->hasMany(Inschrijving::class);
    }

    public function actieveInschrijvingen()
    {
        return $this->hasMany(Inschrijving::class)->where('status', 'ingeschreven');
    }

    // Helper methods
    public function heeftInschrijvingVoorPeriode($periode)
    {
        return $this->actieveInschrijvingen()
            ->whereHas('keuzedeel', function ($query) use ($periode) {
                $query->where('periode', $periode);
            })
            ->exists();
    }

    public function heeftKeuzedeelAfgerond($keuzedeelId)
    {
        return $this->inschrijvingen()
            ->where('keuzedeel_id', $keuzedeelId)
            ->where('status', 'afgerond')
            ->exists();
    }
}
