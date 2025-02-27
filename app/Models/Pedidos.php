<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'user_id',
        'carrinho_id',
        'total',
        'status_pedido_id',
        'payment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cupons()
    {
        return $this->belongsToMany(Cupons::class, 'pedido_cupons', 'pedido_id', 'cupom_id')
            ->withPivot('desconto_aplicado')
            ->withTimestamps();
    }


    public function aplicarCupom(Cupons $cupom)
    {
        $cupomExistente = $this->cupons()->where('cupom_id', $cupom->id)->exists();

        if ($cupomExistente) {
            return false;
        }

        $desconto = $cupom->valor_desconto;

        $this->cupons()->attach($cupom->id, [
            'desconto_aplicado' => $desconto,
        ]);

        return true;
    }

    public function getTotalComDescontoAttribute()
    {
        $descontoTotal = $this->cupons->sum('pivot.desconto_aplicado');
        return max($this->total - $descontoTotal, 0);
    }

    public function status()
    {
        return $this->belongsTo(StatusPedidos::class, 'status_pedido_id', 'id');
    }




    public function carrinho()
    {
        return $this->belongsTo(Carrinhos::class);
    }
}
