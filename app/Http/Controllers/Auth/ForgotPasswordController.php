<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller{
    /* Nazev souboru: ForgotPasswordController.php */
    /* Tato trida slouzi k zasilani emailu k resetovani hesla a je soucasti autentizacniho a autorizacniho balicku frameworku Laravel. */
    use SendsPasswordResetEmails;
}
