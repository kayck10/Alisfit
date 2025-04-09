<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutosRelacionados extends Model
{
    protected $table = 'produtos_relacionados';

    protected $fillable = [
        'produto_id',
        'relacionado_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id');
    }

    public function relacionado()
    {
        return $this->belongsTo(Produtos::class, 'relacionado_id');
    }
}

