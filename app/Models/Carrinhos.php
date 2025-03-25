<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinhos extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cupom_id',
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

    public function cupom()
    {
        return $this->belongsTo(Cupons::class, 'cupom_id');
    }

    public function cupons()
    {
        return $this->belongsToMany(Cupons::class, 'carrinho_cupons', 'carrinho_id', 'cupom_id')
            ->withPivot('desconto_aplicado')
            ->withTimestamps();
    }

    public function aplicarCupom(Cupons $cupom)
    {
        if ($this->cupons()->where('cupom_id', $cupom->id)->exists()) {
            return false;
        }

        if (!$cupom->isValido()) {
            return false;
        }

        $desconto = $cupom->valor;

        $this->cupons()->attach($cupom->id, [
            'desconto_aplicado' => $desconto,
        ]);

        return true;
    }

    public function getTotalComDescontoAttribute()
    {
        $total = $this->produtos->sum(function ($produto) {
            return $produto->pivot->quantidade * $produto->preco;
        });

        $descontoTotal = $this->cupons->sum('pivot.desconto_aplicado');

        return max($total - $descontoTotal, 0);
    }
}
