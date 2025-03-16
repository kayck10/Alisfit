<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinhos extends Model

{

    protected $fillable = [
        'user_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produtos::class, 'carrinho_iten', 'carrinho_id', 'produto_id')
            ->withPivot('quantidade', 'tamanho_id', 'cor')
            ->withTimestamps();
    }

}
