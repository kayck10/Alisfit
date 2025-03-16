<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrinhoIten extends Model
{
    use HasFactory;

    protected $table = 'carrinho_iten';
    protected $fillable = [
        'carrinho_id',
        'produto_id',
        'quantidade',
        'tamanho_id',
        'cor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
