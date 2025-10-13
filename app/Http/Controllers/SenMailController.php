<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SenMailController extends Controller
{
    public function sendMail()
    {
        Mail::send('email',[],function($message) {
            $message->to('tanvir59@outlook.com')->subject('SendinBlue Testing');
        });
        dd('Mail Send Successfully');
    }
}
