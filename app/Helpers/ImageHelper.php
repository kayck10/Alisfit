<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getProdutoImagemUrl($produto)
    {
        if (!$produto->imagens->isEmpty()) {
            $path = $produto->imagens->first()->imagem;
            $parts = explode('/', $path);
            return route('produtos.imagem', ['caminho' => $parts[0], 'filename' => $parts[1]]);
        }

        return asset('images/default.png'); // Imagem padrão se não houver
    }
}
