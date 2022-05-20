<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id','numero_inscripcion','partido_id','tipo_id',
    ];
    public function personas()
    {
        return $this->belongsTo(Persona::class);
    }

    public function tipos()
    {
        return $this->belongsTo(Tipo::class);
    }

    public function partidos()
    {
        return $this->belongsTo(Partido::class);
    }

    protected $table = 'candidatos';

}
