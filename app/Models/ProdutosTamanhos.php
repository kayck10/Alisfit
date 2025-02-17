<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosTamanhos extends Model
{
    use HasFactory;

    protected $table = 'produtos_tamanhos';

    protected $fillable = ['produto_id', 'tamanho_id', 'quantidade', 'cor'];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id');
    }

    public function tamanho()
    {
        return $this->belongsTo(Tamanhos::class, 'tamanho_id');
    }

}
