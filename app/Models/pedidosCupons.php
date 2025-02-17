<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pedidosCupons extends Model
{
    use HasFactory;
    protected $table = 'pedido_cupons';

    protected $fillable = [
        'pedido_id',
        'cupom_id',
        'desconto_aplicado',
    ];
}
