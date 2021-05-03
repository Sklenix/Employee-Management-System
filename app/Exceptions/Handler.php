<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
    /* Nazev souboru: Handler.php */
    /* Autor upravy (metoda unauthenticated): Pavel Sklenář (xsklen12) */
    /* Tato trida je soucasti frameworku Laravel, byla upravena metoda unauthenticated */

    /**
     * A list of the exception types that are not reported.
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(){

    }

     /* Pokud jsou uzivatele dve a vice hodin neaktivni (lifetime session), tak jsou automaticky odhlaseni a nasledne presmerovani na prihlasovaci formular (na zaklade jejich roli)
        viz https://laravel.com/docs/5.6/authentication */
    protected function unauthenticated($request, AuthenticationException $exception) {
        if($request->expectsJson()){ return response()->json(['message' => $exception->getMessage()], 401); }
        $guard = $exception->guards(); // ziskani role uzivatele
        if($guard[0] == "admin"){
            return redirect()->guest(route('renderAdminLogin'))->with(['success' => 'Byl jste automaticky odhlášen ze systému [vypršel časový limit neaktivity].']);
        }else if($guard[0] == "company"){
            return redirect()->guest(route('renderCompanyLogin'))->with(['success' => 'Byl jste automaticky odhlášen ze systému [vypršel časový limit neaktivity].']);
        }else if($guard[0] == "employee"){
            return redirect()->guest(route('renderEmployeeLogin'))->with(['success' => 'Byl jste automaticky odhlášen ze systému [vypršel časový limit neaktivity].']);
        }
    }
}
