<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'preco', 'colecao_id', 'imagem_id', 'genero_id', 'tipo_produto_id', 'destaque', 'quantidade', 'lancamento', 'oferta'];

    public function colecao()
    {
        return $this->belongsTo(Colecoes::class);
    }

    public function medidas()
    {
        return $this->hasMany(TabelaMedidas::class, 'produto_id');
    }

    public function carrinhos()
    {
        return $this->belongsToMany(Carrinhos::class, 'carrinho_produtos')
            ->withPivot('quantidade')
            ->withTimestamps();
    }

    public function tamanhos()
    {
        return $this->belongsToMany(Tamanhos::class, 'produtos_tamanhos', 'produto_id', 'tamanho_id')
            ->withPivot('quantidade', 'cor')
            ->withTimestamps();
    }

    public function imagens()
    {
        return $this->hasMany(ImagensProdutos::class, 'produto_id', 'id');
    }

    public function genero()
    {
        return $this->belongsTo(Generos::class, 'genero_id');
    }

    public function tipoProduto()
    {
        return $this->belongsTo(TiposProdutos::class, 'tipo_produto_id');
    }
}
