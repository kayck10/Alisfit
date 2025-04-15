<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colecoes extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'nome',
        'imagem',
        'status',
        'publicado_em'
    ];

    protected $casts = [
        'publicado_em' => 'datetime'
    ];

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'colecao_id');
    }

    public function getImagemUrlAttribute()
    {
        return $this->imagem ? asset('storage/' . $this->imagem) : asset('images/default.jpg');
    }

    // Escopo para pegar apenas coleções publicadas
    public function scopePublicadas($query)
    {
        return $query->where('status', 'publicado');
    }
}
