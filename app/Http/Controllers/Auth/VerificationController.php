<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller{
    /* Nazev souboru: VerificationController.php */
    /* Tato trida slouzi k rozesilani overovacich emailu a take k samotnemu overeni emailovych adres. Je soucasti autentizacniho a autorizacniho balicku frameworku Laravel. Metoda verify byla upravena.
    Autor upravy: Pavel Sklenář (xsklen12) */
    use VerifiesEmails;

    /* Tato promenna reprezentuje kam budou uzivatele presmerovani po overeni jejich emailove adresy */
    protected $redirectTo = '/company/dashboard';

    public function __construct(){
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /* Nazev funkce: verify
       Argumenty: request
       Ucel: Prepsani metody verify z VerifiesEmails.php kvuli notifikaci nove registrovane firmy a take k jejimu automatickemu prihlaseni */
    public function verify(Request $request){
        $company = Company::find($request->route('id'));
        $user = Auth::user();
        if($user == NULL){
            Auth::login($company);
        }
        if ($company->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }
        if ($company->markEmailAsVerified()) {
            event(new Verified($company));
        }
        return redirect($this->redirectPath())->with('verified', true)->with('success', 'Ověření emailové adresy proběhlo v pořádku, nyní můžete svůj účet používat.');
    }

}
