<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'id_usuario',
        'impacto_esperado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}