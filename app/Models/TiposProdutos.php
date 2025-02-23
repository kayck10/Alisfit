<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposProdutos extends Model
{
    use HasFactory;

    protected $table = 'tipos_produtos';

    protected $fillable = [
        'desc',
    ];

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'tipo_produto_id');
    }
}
