<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class WelcomeController extends Controller
{
    function index(){
        return view('welcome');
    }

    function send(Request $request){
        $this->validate($request, [
            'name'   =>  'required|max:100',
            'email' =>    'required|email',
            'message' =>  'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
        ]);
        $data = array(
          'name' => $request->name,
          'message' => $request->message,
          'email' => $request->email,
          'phone' => $request->phone
        );

        Mail::to('tozondoservice@gmail.com')->send(new SendMail($data));
        return back()->with('success','Děkujeme za kontaktování.');
    }
}
