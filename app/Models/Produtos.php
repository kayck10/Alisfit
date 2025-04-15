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

    public function scopeDaColecaoPublicada($query)
    {
        return $query->whereHas('colecao', function ($query) {
            $query->where('status', 'publicado');
        });
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

    // Produtos relacionados a este produto
    public function relacionados()
    {
        return $this->belongsToMany(Produtos::class, 'produto_relacionados', 'produto_id', 'relacionado_id')->withTimestamps();
    }

    // Produtos que têm este como relacionado (opcional, mas útil)
    public function relacionadosPor()
    {
        return $this->belongsToMany(Produtos::class, 'produto_relacionados', 'relacionado_id', 'produto_id')->withTimestamps();
    }
}
