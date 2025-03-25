<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrinhoCupom extends Model
{
    use HasFactory;

    protected $table = 'carrinho_cupons';

    protected $fillable = [
        'carrinho_id',
        'cupom_id',
        'desconto_aplicado',
    ];

    public function carrinho()
    {
        return $this->belongsTo(Carrinhos::class, 'carrinho_id');
    }

    public function cupom()
    {
        return $this->belongsTo(Cupons::class, 'cupom_id');
    }
}
