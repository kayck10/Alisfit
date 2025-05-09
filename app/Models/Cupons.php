<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupons extends Model
{
    use HasFactory;
    protected $table = 'cupons';

    protected $fillable = [
        'codigo',
        'tipo',
        'valor',
        'quantidade',
        'expiracao',
        'ativo',
    ];

    protected $casts = [
        'expiracao' => 'date',
        'ativo' => 'boolean',
    ];

    public function isValido(): bool
    {
        return $this->ativo && ($this->quantidade > 0) && (!$this->expiracao || $this->expiracao->isFuture());
    }

    public function carrinhos()
    {
        return $this->belongsToMany(Carrinhos::class, 'carrinho_cupons')
            ->withPivot('desconto_aplicado')
            ->withTimestamps();
    }
}
