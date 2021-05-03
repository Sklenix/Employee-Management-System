<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller{
    /* Nazev souboru: ResetPasswordController.php */
    /* Tato trida slouzi pro resetovani hesel uctu firem a je soucasti autentizacniho a autorizacniho balicku frameworku Laravel. */

    use ResetsPasswords;

    /* Tato promenna reprezentuje kam budou uzivatele presmerovani po resetovani jejich hesla */
    protected $redirectTo = '/company/dashboard';

}
