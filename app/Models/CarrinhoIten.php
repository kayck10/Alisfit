<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrinhoIten extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrinho_id',
        'produto_id',
        'quantidade'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
