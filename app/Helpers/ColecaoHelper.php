<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ColecaoHelper
{
    public static function getImageUrl($collection, $default = 'images/default.jpg')
    {
        if (empty($collection->imagem)) {
            return asset($default);
        }

        // Use a rota nomeada em vez de asset('storage/...')
        return route('colecoes.imagem', [
            'caminho' => dirname($collection->imagem),
            'filename' => basename($collection->imagem)
        ]);
    }
}
