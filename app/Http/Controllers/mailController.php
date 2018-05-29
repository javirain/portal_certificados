<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\mail\sendMail;

class mailController extends Controller
{
    public function send(){

    	
       	Mail::send( new sendMail());
       	echo "correo enviado";
    }

    public function email(){

    	return view('correo.email');
    }

}
