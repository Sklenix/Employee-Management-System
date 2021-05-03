<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller{
    /* Tato trida je soucasti autentizacniho a autorizacniho balicku frameworku Laravel, v tomto projektu neni vyuzita. Slouzi pro potvrzeni hesel uzivatelu */

    use ConfirmsPasswords;

    /* Tato promenna reprezentuje cestu kam budou presmerovani uzivatele, pokud nebude mozne otevrit stranku s url pro zmenu hesla */
    protected $redirectTo = RouteServiceProvider::HOME;

    /* Konstruktor, pro pouziti teto tridy je potreba byt prihlaseny */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
