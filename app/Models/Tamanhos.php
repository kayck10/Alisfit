<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamanhos extends Model
{
    use HasFactory;

    protected $fillable = ['desc'];

    public function produtos()
    {
        return $this->belongsToMany(Produtos::class, 'produtos_tamanhos', 'tamanho_id', 'produto_id')
                    ->withPivot('quantidade', 'cor')
                    ->withTimestamps();
    }
}

