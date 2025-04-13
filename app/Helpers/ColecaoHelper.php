<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ColecaoHelper
{
     /**
     * Obtém a URL da imagem de uma coleção
     *
     * @param \App\Models\Colecoes $collection
     * @param string $default Caminho da imagem padrão
     * @return string
     */
    public static function getImageUrl($collection, $default = 'images/default.jpg')
    {
        // Se já existir um accessor (como getImagemUrlAttribute), use-o
        if (method_exists($collection, 'getImagemUrlAttribute')) {
            return $collection->imagem_url;
        }

        // Caso contrário, use a lógica padrão
        return $collection->imagem
            ? asset('storage/' . $collection->imagem)
            : asset($default);
    }

    /**
     * Serve a imagem diretamente do storage
     *
     * @param string $caminho
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public static function serveImage($caminho, $filename)
    {
        $path = storage_path('app/public/' . $caminho . '/' . $filename);

        if (!Storage::exists('public/' . $caminho . '/' . $filename)) {
            abort(404, "Arquivo não encontrado");
        }

        return Response::file($path);
    }
}
