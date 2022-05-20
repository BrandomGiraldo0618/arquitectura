<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class PuntoVotacio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre','direccion',
    ];
    public function mesa(){
        return $this->hasMany(Mesa::class);
    }
    protected $table = 'punto_votacio';
}
