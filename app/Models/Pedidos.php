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
        'total',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cupons()
    {
        return $this->belongsToMany(Cupons::class, 'pedido_cupons')
                    ->withPivot('desconto_aplicado')
                    ->withTimestamps();
    }

    public function aplicarCupom(Cupons $cupom)
    {
        if (!$cupom->isValido()) {
            return false;
        }

        $desconto = ($cupom->tipo === 'percentual')
                    ? ($this->total * ($cupom->valor / 100))
                    : $cupom->valor;

        $desconto = min($desconto, $this->total);

        $this->cupons()->attach($cupom->id, ['desconto_aplicado' => $desconto]);

        $this->total -= $desconto;
        $this->save();

        $cupom->decrement('quantidade');
        return true;
    }

}
