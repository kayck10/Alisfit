<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagensProdutos extends Model
{
    use HasFactory;

    protected $fillable = ['imagem', 'produto_id'];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id');
    }
}

