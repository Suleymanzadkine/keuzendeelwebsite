<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opleiding extends Model
{
    use HasFactory;

    protected $table = 'opleidingen';

    protected $fillable = [
        'naam',
        'slug',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function keuzedelen()
    {
        return $this->belongsToMany(Keuzedeel::class, 'keuzedeel_opleiding');
    }
}
