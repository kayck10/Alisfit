<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generos extends Model
{
    use HasFactory;
    protected $table = 'generos';

    protected $fillable = [
        'desc',
    ];

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'genero_id');
    }
}
