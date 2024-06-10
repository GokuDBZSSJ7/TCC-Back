<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_comentario'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    
}