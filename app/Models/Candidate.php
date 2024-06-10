<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'idade',
        'formacao',
        'experiencia',
        'id_cargo',
        'id_partido',
        'filiacao_eleitoral',
        'id_numero_eleitoral'
    ];

    public function cargo()
    {
        return $this->belongsTo(Office::class);
    }

    public function partido()
    {
        return $this->belongsTo(Party::class);
    }

    public function numeroEleitoral()
    {
        return $this->belongsTo(ElectorNumber::class);
    }
}