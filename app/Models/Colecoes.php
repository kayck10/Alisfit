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
    ];

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'colecao_id');
    }

    public function getImagemUrlAttribute()
    {
        return $this->imagem ? asset('storage/' . $this->imagem) : asset('images/default.jpg');
    }
}
