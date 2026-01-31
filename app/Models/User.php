<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Inschrijving;
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'opleiding_id',
    ];

    // Roles relationship and helpers
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('name', $role)->exists();
        }
        return $this->roles()->where('name', $role)->exists();
    }

    public function assignRole($role)
    {
        if ($role instanceof Role) {
            $this->roles()->syncWithoutDetaching($role);
        } else {
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel) {
                $this->roles()->syncWithoutDetaching($roleModel);
            }
        }
    }

    public function removeRole($role)
    {
        if ($role instanceof Role) {
            $this->roles()->detach($role);
        } else {
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel) {
                $this->roles()->detach($roleModel);
            }
        }
    }

    public function isAdmin()
    {
        return $this->is_admin || $this->hasRole('admin');
    }

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

    public function opleiding()
    {
        return $this->belongsTo(Opleiding::class);
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
