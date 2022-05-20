<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre','punto_votacio_id',
    ];

    public function PuntoVotacio()
    {
        return $this->belongsTo(PuntoVotacio::class);
    }
    protected $table = 'mesas';

}
