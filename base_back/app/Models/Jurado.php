<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurado extends Model
{
    use HasFactory;
    protected $fillable = [
        'persona_id','mesa_id'
    ];

    public function personas()
    {
        return $this->belongsTo(Persona::class);
    }


    protected $table = 'jurados';
}
