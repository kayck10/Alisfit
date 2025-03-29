<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImagensProdutoController extends Controller
{
    public function getImage($caminho, $filename)
    {
        $path = storage_path('app/public/' . $caminho . '/' . $filename);

        if (!file_exists($path)) {
            abort(404, "Arquivo não encontrado");
        }

        return Response::file($path);
    }
}
