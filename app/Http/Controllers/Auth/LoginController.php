<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{
    /* Autor uprav: Pavel Sklenář (xsklen12) */
    /* Nazev souboru: LoginController.php */
    /* Tato trida slouzi pro prihlasovani uzivatelu do systemu a je soucasti autentizacniho a autorizacniho balicku frameworku Laravel a byla znacne upravena pro ucely tohoto informacniho systemu. */

    use AuthenticatesUsers;

    /* Tato promenna reprezentuje cestu kam budou presmerovani uzivatele po prihlaseni */
    protected $redirectTo = RouteServiceProvider::HOME;

    /* Konstruktor, diky kteremu je umozneno odhlasovani ze systemu */
    public function __construct()
    {
        $this->middleware('guest')->except('companyLogout');
        $this->middleware('guest:admin')->except('adminLogout');
        $this->middleware('guest:employee')->except('employeeLogout');
    }

    /* Nazev funkce: renderAdminLogin
       Argumenty: zadne
       Ucel: Zobrazeni prihlasovaciho formulare pro uzivatele s roli admina */
    public function renderAdminLogin(){
        return view('auth.login', ['role' => 'admin']);
    }

    /* Nazev funkce: adminLogin
      Argumenty: request - udaje zadane uzivatelem (v tomto pripade email ci login a heslo)
      Ucel: Po zadani udaju do prihlasovaciho formulare admina a zmacknutim tlacitka "Prihlasit se" se zavola tato metoda, ktera overi, zdali uzivatel zadal korektni udaje, pokud ano, tak je prihlasen do systemu */
    public function adminLogin(Request $request){
        // definice pravidel pro validaci.
        $pravidla = [
            'email' => 'required',
            'heslo' => 'required'
        ];
        // definice vlastnich hlasek na vyse definovana pravidla.
        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'max:100' => 'U položky :attribute je povoleno maximálně 100 znaků.'
        ];
        $this->validate($request, $pravidla, $vlastniHlasky); // realizace validace

        /* Sekce pro zjisteni, zdali uzivatel zmackl moznost "Zapamovat"*/
        $remember = '';
        if($request->has('remember')){
            $remember = 1;
        }else{
            $remember = 0;
        }

        /* Overeni, zdali uzivatel zadal korektni udaje a pripadne nastaveni remember tokenu pokud uzivatel zmackl tlacitko "Zapamatovat" */
        if (Auth::guard('admin')->attempt(['admin_email' => $request->email, 'password' => $request->heslo], $remember) ||  Auth::guard('admin')->attempt(['admin_login' => $request->email, 'password' => $request->heslo], $remember)) {
            return redirect()->route('homeAdmin'); // pokud ano, je presmerovan do jeho dashboardu
        }else{ // pokud zadal inkorektni udaje, tak je znovu presmerovan na prihlasovaci formular s upozornenim, ze zadal inkorektni udaje.
            session()->flash('fail', 'Špatně zadané přihlašovací údaje!');
            session()->flash('alert-class', 'alert-danger');
            return redirect()->route('renderAdminLogin');
        }
    }

    /* Nazev funkce: adminLogout
    Argumenty: request - zde slouzi primarne k vyresetovani session
    Ucel: Odhlaseni uzivatele s roli admina z jeho uctu */
    public function adminLogout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('renderAdminLogin');
    }

    /* Nazev funkce: renderEmployeeLogin
       Argumenty: zadne
       Ucel: Zobrazeni prihlasovaciho formulare pro uzivatele s roli zamestnance */
    public function renderEmployeeLogin(){
        return view('auth.login', ['role' => 'employee']);
    }

    /* Nazev funkce: employeeLogin
      Argumenty: request - udaje zadane uzivatelem (v tomto pripade email ci login a heslo)
      Ucel: Po zadani udaju do prihlasovaciho formulare zamestnance a zmacknutim tlacitka "Prihlasit se" se zavola tato metoda, ktera overi, zdali uzivatel zadal korektni udaje, pokud ano, tak je prihlasen do systemu */
    public function employeeLogin(Request $request){
        // definice pravidel pro validaci.
        $pravidla = [
            'email' => 'required',
            'heslo' => 'required'
        ];
        // definice vlastnich hlasek na vyse definovana pravidla.
        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'max:100' => 'U položky :attribute je povoleno maximálně 100 znaků.'
        ];
        $this->validate($request, $pravidla, $vlastniHlasky); // samotna realizace validace

        /* Sekce pro zjisteni, zdali uzivatel zmackl moznost "Zapamovat"*/
        $remember = '';
        if($request->has('remember')){
            $remember = 1;
        }else{
            $remember = 0;
        }

        /* Overeni, zdali uzivatel zadal korektni udaje a pripadne nastaveni remember tokenu pokud uzivatel zmackl tlacitko "Zapamatovat" */
        if (Auth::guard('employee')->attempt(['email' => $request->email, 'password' => $request->heslo], $remember) || Auth::guard('employee')->attempt(['employee_login' => $request->email, 'password' => $request->heslo],$remember)) {
            return redirect()->route('homeEmployee'); // pokud ano, je presmerovan do jeho dashboardu
        }else{ // pokud zadal inkorektni udaje, tak je znovu presmerovan na prihlasovaci formular s upozornenim, ze zadal inkorektni udaje.
            session()->flash('fail', 'Špatně zadané přihlašovací údaje!');
            session()->flash('alert-class', 'alert-danger');
            return redirect()->route('renderEmployeeLogin');
        }
    }

    /* Nazev funkce: employeeLogout
     Argumenty: request - zde slouzi primarne k vyresetovani session
     Ucel: Odhlaseni uzivatele s roli zamestnance z jeho uctu */
    public function employeeLogout(Request $request){
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('renderEmployeeLogin');
    }

    /* Nazev funkce: renderCompanyLogin
     Argumenty: zadne
     Ucel: Zobrazeni prihlasovaciho formulare pro uzivatele s roli firmy */
    public function renderCompanyLogin(){
        return view('auth.login', ['role' => 'company']);
    }

    /* Nazev funkce: companyLogin
     Argumenty: request - udaje zadane uzivatelem (v tomto pripade email ci login a heslo)
     Ucel: Po zadani udaju do prihlasovaciho formulare firmy a zmacknutim tlacitka "Prihlasit se" se zavola tato metoda, ktera overi, zdali uzivatel zadal korektni udaje, pokud ano, tak je prihlasen do systemu */
    public function companyLogin(Request $request){
        // definice pravidel pro validaci.
       $pravidla = [
           'email' => 'required',
           'heslo' => 'required'
       ];
        // definice vlastnich hlasek na vyse definovana pravidla.
       $vlastniHlasky = [
           'required' => 'Položka :attribute je povinná.',
           'email' => 'U položky :attribute nebyl dodržen formát emailu.',
           'max:100' => 'U položky :attribute je povoleno maximálně 100 znaků.'
       ];
       $this->validate($request, $pravidla, $vlastniHlasky); // samotna realizace validace

       /* Sekce pro zjisteni, zdali uzivatel zmackl moznost "Zapamovat"*/
       $remember = '';
       if($request->has('remember')){
           $remember = 1;
       }else{
           $remember = 0;
       }

       /* Overeni, zdali uzivatel zadal korektni udaje a pripadne nastaveni remember tokenu pokud uzivatel zmackl tlacitko "Zapamatovat" */
       if (Auth::attempt(['email' => $request->email, 'password' => $request->heslo], $remember)
           || Auth::attempt(['company_login' => $request->email, 'password' => $request->heslo], $remember)) {
           return redirect()->route('home');  // pokud ano, je presmerovan do jeho dashboardu
       }else{ // pokud zadal inkorektni udaje, tak je znovu presmerovan na prihlasovaci formular s upozornenim, ze zadal inkorektni udaje.
           session()->flash('fail', 'Špatně zadané přihlašovací údaje!');
           session()->flash('alert-class', 'alert-danger');
           return redirect()->route('renderCompanyLogin');
       }
   }

    /* Nazev funkce: companyLogout
     Argumenty: request - zde slouzi primarne k vyresetovani session
     Ucel: Odhlaseni uzivatele s roli firmy z jeho uctu */
    public function companyLogout(Request $request){
        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('renderCompanyLogin');
    }
}
