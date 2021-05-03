<?php


namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAdminController {
    /* Nazev souboru:  UserAdminController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazovani dashboardu a profilu uctu s roli admina. Dale slouzi ke zmene udaju v profilu admina */

    /* Nazev funkce: index
       Argumenty: zadne
       Ucel: Zobrazeni dashboardu uctu s roli admina */
    public function index() {
        return view('homes.admin_home');
    }

    /* Nazev funkce: showAdminProfileData
       Argumenty: zadne
       Ucel: Zobrazeni profilu uctu s roli admina */
    public function showAdminProfileData() {
        return view('profiles.admin_profile');
    }

    /* Nazev funkce: deleteAdminProfile
       Argumenty: zadne
       Ucel: Smazani uctu s roli admina ze systemu */
    public function deleteAdminProfile(){
        $user = Auth::user();
        DB::table('table_admins')
            ->where(['table_admins.admin_id' => $user->admin_id])
            ->delete();
        session()->flash('success', 'Váš účet byl úspěšně smazán!');
        return redirect()->route('renderAdminLogin');
    }

    /* Nazev funkce: updateAdminProfileData
       Argumenty: request - udaje zadane ve formulari
       Ucel: Aktualizace udaju uctu s roli admina */
    public function updateAdminProfileData(Request $request){
        $user = Auth::user();
        /* Usek kodu slouzici pro zjisteni duplicit prihlasovaciho jmena ci emailove adresy */
        $emailDuplicate = 0;
        $loginDuplicate = 0;
        if($user->admin_email == $request->email){ $emailDuplicate = 1; }
        if($user->admin_login == $request->prihlasovaci_jmeno){ $loginDuplicate = 1; }

        /* Definice pravidel pro validaci */
        if($emailDuplicate == 1 && $loginDuplicate == 0){ // pokud jsou emailove adresy stejne a prihlasovaci jmena rozdilne
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'prihlasovaci_jmeno' =>  ['required','unique:table_admins,admin_login','string', 'max:255'],
            ];
        }else if($loginDuplicate == 1 && $emailDuplicate == 0){ // pokud jsou prihlasovaci jmena stejne a emailove adresy rozdilne
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'email' => ['required','unique:table_admins,admin_email','string','email','max:255']
            ];
        }else if($loginDuplicate == 1 && $emailDuplicate == 1){ // pokud jsou prihlasovaci jmena stejne a emailove adresy take
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
            ];
        }else if($loginDuplicate == 0 && $emailDuplicate == 0){ // pokud prihlasovaci jmena nejsou stejna a emailove adresy take nejsou stejne
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'prihlasovaci_jmeno' =>  ['required','unique:table_admins,admin_login','string', 'max:255'],
                'email' => ['required','unique:table_admins,admin_email','string','email','max:255']
            ];
        }
        /* Definice vlastnich hlasek */
        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'regex' => 'Formát :attribute není validní.',
            'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.',
            'unique' => 'Váš e-mail, nebo Váš login už v databázi evidujeme.',
            'digits' => 'Číslo musí mít 8 cifer'
        ];
        /* Realizace validace */
        Validator::validate($request->all(), $pravidla, $vlastniHlasky);
        /* Aktualizace udaju v databazi */
        Admin::where(['admin_id' => $user->admin_id])->update(['admin_name' => $request->jmeno, 'admin_surname' => $request->prijmeni, 'admin_email' => $request->email, 'admin_login' => $request->prihlasovaci_jmeno]);

        /* Nastaveni zpravy o uspechu a vraceni se na profil uctu s roli admina */
        session()->flash('success', 'Vaše údaje byly úspěšně změněny!');
        return redirect()->route('showAdminProfileData');
    }

    /* Nazev funkce: updateAdminProfilePassword
       Argumenty: request - heslo zadane ve formulari
       Ucel: Aktualizace hesla uctu s roli admina */
    public function updateAdminProfilePassword(Request $request){
        $user = Auth::user();
        /* Kontrola zdali se hesla rovnaji a zdali byly vubec zadane a pokud byly tak zdali byly zadany korektne */
        if($request->password == $request->password_verify){
            if ($request->password == "" || $request->password_verify == "") {
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
                return redirect()->back();
            }else if (strlen($request->password) < 5) {
                session()->flash('errorZprava', 'Heslo musí obsahovat nejméně 5 znaků!');
                return redirect()->back();
            }else {
                $user->password= Hash::make($request->password); // nastaveni noveho hesla
            }
        }else {
            if($request->password == "" || $request->password_verify == "") {
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
            }
            session()->flash('errorZprava', 'Hesla se neshodují!');
            return redirect()->back();
        }
        $user->save(); // ulozeni hesla
        /* Nastaveni zpravy o uspechu a vraceni se na profil uctu s roli admina */
        session()->flash('success', 'Vaše heslo bylo úspešně změněno!');
        return redirect()->back();
    }

}
