<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelaMedidas extends Model
{
    use HasFactory;

    protected $table = 'tabela_medidas';


    protected $fillable = [
        'produto_id',
        'tamanho',
        'torax',
        'cintura',
        'quadril',
        'comprimento',
        'altura',
        'observacoes'
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class,  'produto_id');
    }
}
