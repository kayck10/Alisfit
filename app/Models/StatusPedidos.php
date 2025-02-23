<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPedidos extends Model
{
    use HasFactory;

    protected $table = 'status_pedidos';

    protected $fillable = [
        'desc',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedidos::class, 'status_pedido_id');
    }
}
