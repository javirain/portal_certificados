<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\request;


class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(request $request)
    {
        //return $this->view('mail',['msg'=>$request->message])->to($request->to);
                

    return $this->view('correo.mail',['msg'=>$request->message])->to($request->to)

                    ->from('certificadosubb@gmail.com','Universidad del Bío Bío')
                    ->subject('Portal certificados')  
                    ->attach('C:\xampp\htdocs\portal_cert\Prueba2PDF2.pdf');
                        
    }
}
