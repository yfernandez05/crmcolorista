<?php

namespace App\Mail;

use App\Models\Cliente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Evento Go Cientifica';
    public $cliente;
    public $qrCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Cliente $cliente, $qrCode)
    {
      $this->cliente = $cliente;
      $this->qrCode = $qrCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->bcc(['marleni.ingarojas@gmail.com','macuna@padin.com.pe','mrodriguezp@padin.com.pe'])->view('messagereceived');
        // return $this->from('marketing@padin.com.pe',$nombrecliente->nombres)
        // // // ->subject('Correo de Contacto')
        // ->view('messagereceived');
        return $this->view('qr.messagereceived')
                    ->with([
                        'cliente' => $this->cliente,
                        'qrCode' => $this->qrCode
                    ]);

    }
}
