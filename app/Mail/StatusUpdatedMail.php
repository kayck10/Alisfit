<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

namespace App\Mail;

use App\Models\Order;
use App\Models\Pedidos;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

namespace App\Mail;

use App\Models\Pedidos;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $statusDesc;

    public function __construct(Pedidos $pedido, string $statusDesc)
    {
        $this->pedido = $pedido;
        $this->statusDesc = $statusDesc;
    }

    public function build()
    {
        return $this->from('alisoriginalfitness@gmail.com')
                    ->subject('Status do Pedido Atualizado')
                    ->view('emails.status_atualizado');
    }
}


