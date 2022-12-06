<?php

namespace App\Http\Controllers;

use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        $mailData = [
            "title" => "Boldog Mikulást!",
            "body" => "Szia Anya legyen szép napod! Szeretlek!"
        ];

        Mail::to("leonaweinberger@gmail.com")->send(new DemoMail($mailData));

        dd("Sikeresen elküldve az email.");
    }

    public function mailKinezet()
    {
        $mailData = [
            "title" => "Boldog Mikulást!",
            "body" => "Szia Anya legyen szép napod! Szeretlek!"
        ];
        return view("email.test");
    }
}
