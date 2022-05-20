<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;
    
    public function candidato()
    {
        return $this->hasMany(Candidato::class);
    }
   /* public function personas()
    {
        return $this->belongsTo(Persona::class);
    }*/
}
