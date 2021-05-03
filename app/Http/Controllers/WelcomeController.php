<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendQuestionWelcomeMail;
use Illuminate\Support\Facades\Validator;

class WelcomeController extends Controller {
    /* Nazev souboru:  WelcomeController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazovani uvodni strany systemu a take k odeslani emailove zpravy v ramci dotazniku */

    /* Nazev funkce: index
       Argumenty: zadne
       Ucel: Zobrazeni uvodni stranky systemu */
    function index() {
        return view('welcome');
    }

    /* Nazev funkce: send
       Argumenty: request - data z formulare
       Ucel: Odeslani emailove zpravy */
    function send(Request $request){
        /* Definice pravidel pro validaci*/
        $pravidla = [
            'jmeno'  => ['required', 'max:100'], 'email'  => ['required', 'email'], 'zprava' => ['required'], 'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
        ];
        /* Definice vlastnich hlasek */
        $vlastniHlasky = [
        'required' => 'Položka :attribute je povinná.',
        'email' => 'U položky :attribute nebyl dodržen formát emailu.',
        'regex' => 'Formát :attribute není validní.',
        'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.'
        ];

        /* Realizace validace */
        Validator::validate($request->all(), $pravidla, $vlastniHlasky);
        /* Ulozeni udaju do promenne */
        $udaje = ['jmeno' => $request->jmeno, 'email' => $request->email, 'telefon' => $request->telefon, 'zprava' => $request->zprava];
        /* Zaslani emailove zpravy */
        Mail::to('tozondoservice@gmail.com')->send(new SendQuestionWelcomeMail($udaje));
        /* Zobrazeni zpravy o uspechu */
        return back()->with('success','Děkujeme za kontaktování.');
    }

}
