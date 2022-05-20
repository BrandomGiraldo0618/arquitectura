<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo_Documento','numero_Documento','nombre','apellido','lugar_nacimiento','fecha_nacimiento',
    ];

    public function candidato()
    {
        return $this->hasMany(Candidato::class);
    }
    public function votante()
    {
        return $this->hasMany(Votante::class);
    }

   /* public function partido()
    {
        return $this->hasMany(Partido::class);
    }*/

    protected $table = 'personas';

}
