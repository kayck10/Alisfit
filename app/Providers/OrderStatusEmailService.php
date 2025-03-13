<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\StatusUpdatedMail;
use App\Models\Order;
use App\Models\Pedidos;
use App\Models\StatusPedidos;

class OrderStatusEmailService
{
    public function enviarEmailStatusAlterado(Pedidos $pedido)
    {
        $statusAtual = $pedido->status;

        if ($statusAtual) {
            $this->enviarEmail($pedido, $statusAtual);
        }
    }

    protected function enviarEmail(Pedidos $pedido, StatusPedidos $status)
    {
        $user = $pedido->user;
        $statusDesc = $status->desc;

        Mail::to($user->email)->send(new StatusUpdatedMail($pedido, $statusDesc));
    }
}

