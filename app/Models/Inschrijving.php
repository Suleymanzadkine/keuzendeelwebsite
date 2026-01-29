<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Inschrijving extends Model
{
    use HasFactory;

    protected $table = 'inschrijvingen';

    protected $fillable = [
        'user_id',
        'keuzedeel_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function keuzedeel()
    {
        return $this->belongsTo(Keuzedeel::class, 'keuzedeel_id');
    }
}
