<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Google_Service_Drive;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Google_Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Google_Service_Drive_DriveFile;

class UserEmployeeController extends Controller {
    /* Nazev souboru:  UserEmployeeController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazovani dashboardu a profilu uctu s roli zamestnancu. Dale slouzi ke zmene udaju (vcetne hesla a profiloveho obrazku) v profilu zamestnance a take k praci s Google Drive v ramci zamestnancu.
       Nazvy jednotlivych metod jsou konvenci frameworku laravel, viz https://laravel.com/docs/8.x/controllers
       Inspiraci k napojeni na Google Drive a prace s nim byl clanek https://www.kutac.cz/weby-a-vse-okolo/google-drive-api-nahravani-souboru-v-php, ktery napsal Pavel Kutac v roce 2019
       Odkaz na Google Drive API: https://developers.google.com/drive/api/v3/quickstart/php a jeji git: https://github.com/googleapis/google-api-php-client. Google Drive API knihovna je poskytovana pod licenci Apache License 2.0.
       Copyright pro Google Drive API:
           Copyright 2021 Google Drive API

           Licensed under the Apache License, Version 2.0 (the "License");
           you may not use this file except in compliance with the License.
           You may obtain a copy of the License at

           http://www.apache.org/licenses/LICENSE-2.0

           Unless required by applicable law or agreed to in writing, software
           distributed under the License is distributed on an "AS IS" BASIS,
           WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
           See the License for the specific language governing permissions and
           limitations under the License.
    */

    /* Nazev funkce: index
       Argumenty: zadne
       Ucel: Zobrazeni zamestnanecke domovske stranky */
    public function index() {
        $user = Auth::user();
        return view('homes.employee_home')->with('profilovka',$user->employee_picture)->with('employee_url', $user->employee_url);
    }

    /* Nazev funkce: showEmployeeProfileData
       Argumenty: zadne
       Ucel: Zobrazeni profilu uctu s roli zamestnance */
    public function showEmployeeProfileData() {
        $user = Auth::user();
        return view('profiles.employee_profile')
            ->with('profilovka', $user->employee_picture);
    }

    /* Nazev funkce: deleteEmployeeProfile
       Argumenty: zadne
       Ucel: Smazani profilu uctu s roli zamestnance */
    public function deleteEmployeeProfile() {
        $user = Auth::user();
        /* Smazani z OLAP sekce systemu */
        OlapETL::deleteRecordFromEmployeeDimension($user->employee_id);
        /* Smazani zamestnance z databaze */
        DB::table('table_employees')
            ->where(['table_employees.employee_id' => $user->employee_id])
            ->delete();
        /* Smazani zamestnance z Google Drive */
        if($user->employee_url != ""){
            /*Cesta k autorizacnimu klici*/
            $authKey = storage_path('app/credentials.json');
            /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
            $httpClient = new Client(['verify' => false]);
            /* Inicializace Google Drive klienta */
            $GoogleClient = new Google_Client();
            /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
            $GoogleClient->setHttpClient($httpClient);
            /* Nastaveni prihlaseni ke Google Drive API */
            $GoogleClient->setAuthConfig($authKey);
            /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
            $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
            $googleServ = new Google_Service_Drive($GoogleClient);
            /* Usek kodu, ktery zjisti, zdali existuje Google Drive slozka zamestnance, pokud ano, tak se odstrani */
            $results = $googleServ->files->get($user->employee_url);
            if($results != NULL) {
                $googleServ->files->delete($user->employee_url);
            }
        }
        /* Odeslani zpravy uzivateli a presmerovani na prihlasovaci formular zamestnance */
        session()->flash('success', 'Váš účet byl úspěšně smazán!');
        return redirect()->route('renderEmployeeLogin');
    }

    /* Nazev funkce: validator
       Argumenty: zadne
       Ucel: validace udaju zadanymi zamestnancem */
    protected function validator(array $udaje, $emailDuplicate){
        /* Definice pravidel pro validaci */
        if($emailDuplicate == 1){ // pokud je email stejny jako puvodni
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
                'mesto_bydliste' =>  ['required', 'string', 'max:255'],
                'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                'ulice_bydliste' =>  ['nullable', 'string', 'max:255']
            ];
        }else if($emailDuplicate == 0){ // pokud neni email stejny jako puvodni
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
                'mesto_bydliste' =>  ['required', 'string', 'max:255'],
                'email' => ['required','unique:table_employees,email','string','email','max:255'],
                'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                'ulice_bydliste' =>  ['nullable', 'string', 'max:255']
            ];
        }
        /* Definice vlastnich hlasek */
        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'before' => 'Datum narození musí být nejméně před 15 lety.',
            'regex' => 'Formát :attribute není validní.',
            'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.',
            'unique' => 'Váš e-mail, nebo Váš login už v databázi evidujeme.',
            'digits' => 'Číslo musí mít 8 cifer.'
        ];
        /* Realizace validace */
        Validator::validate($udaje, $pravidla, $vlastniHlasky);
    }

    /* Nazev funkce: updateEmployeeProfileData
       Argumenty: request - udaje zadane zamestnancem
       Ucel: Aktualizace profilu uctu s roli zamestnance */
    public function updateEmployeeProfileData(Request $request){
        $user = Auth::user();
        $emailDuplicate = 0;
        /* Zjisteni, zdali uzivatel zmenil emailovou adresu */
        if($user->email == $request->email){ $emailDuplicate = 1; }

        /* Zavolani validatoru */
        $this->validator($request->all(),$emailDuplicate,1);
        /* Pokud zamestnanec nema vubec Google Drive slozku */
        if($user->employee_url != ""){
            if($user->employee_name == $request->jmeno && $user->employee_surname == $request->prijmeni){ // pokud ma zamestnanec jmeno a prijmeni stejne, neni spustena aktualizace nazvu Google Drive slozky zamestnance
            }else{
                /* Usek kodu zabyvajici se nazvem slozky v Google Drive firmy */
                $souborZmena = $request->jmeno." ".$request->prijmeni;
                /*Cesta k autorizacnimu klici*/
                $authKey = storage_path('app/credentials.json');
                /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
                $httpClient = new Client(['verify' => false]);
                /* Inicializace Google Drive klienta */
                $GoogleClient = new Google_Client();
                /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
                $GoogleClient->setHttpClient($httpClient);
                /* Nastaveni prihlaseni ke Google Drive API */
                $GoogleClient->setAuthConfig($authKey);
                /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
                $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
                $googleServ = new Google_Service_Drive($GoogleClient);
                $zmena_jmena = new Google_Service_Drive_DriveFile();
                $zmena_jmena->setName($souborZmena);
                $googleServ->files->update($user->employee_url, $zmena_jmena, ['uploadType' => 'multipart']);
            }

        }

        /* Aktualizace udaju v databazi */
        Employee::where(['employee_id' => $user->employee_id])->update(['employee_name' => $request->jmeno, 'employee_surname' => $request->prijmeni, 'employee_birthday' => $request->narozeniny, 'employee_phone' => $request->telefon, 'email' => $request->email, 'employee_city' => $request->mesto_bydliste, 'employee_street' => $request->ulice_bydliste]);
        /* Aktualizace OLAP sekce systemu */
        OlapETL::updateEmployeeDimension($user->employee_id, $request->jmeno, $request->prijmeni, $request->pozice);
        /* Nastaveni zpravy o uspechu a vraceni se na profil uctu s roli zamestnance */
        session()->flash('message', 'Vaše údaje byly úspěšně změněny!');
        return redirect()->route('showEmployeeProfileData');
    }

    /* Nazev funkce: updateEmployeeProfilePassword
       Argumenty: request - udaje zadane zamestnancem
       Ucel: Aktualizace hesla uctu s roli zamestnance */
    public function updateEmployeeProfilePassword(Request $request){
        $user = Auth::user();
        /* Zjisteni zdali se hesla rovnaji */
        if($request->heslo == $request->heslo_overeni){
            if($request->heslo == "" || $request->heslo_overeni == ""){
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
                return redirect()->back();
            }else if(strlen($request->heslo) < 8){ // zjisteni zdali ma heslo alespon 8 znaku
                session()->flash('errorZprava', 'Heslo musí obsahovat nejméně 8 znaků!');
                return redirect()->back();
            }else{
                $user->password= Hash::make($request->heslo); // vytvoreni noveho hesla
            }
        }else{ // pokud se nerovnaji
            if($request->heslo == "" || $request->heslo_overeni == ""){
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
            }
            session()->flash('errorZprava', 'Hesla se neshodují!');
            return redirect()->back();
        }
        $user->save(); // ulozeni do databaze
        session()->flash('message', 'Vaše heslo bylo úspešně změněno!');
        return redirect()->back();
    }

    /* Nazev funkce: deleteEmployeeOldImage
       Argumenty: zadne
       Ucel: Odstraneni profiloveho obrazku zamestnance */
    public function deleteEmployeeOldImage(){
        $user = Auth::user();
        if($user->employee_picture != NULL){
            /* Odstraneni profilove fotky z uloziste */
            Storage::delete('/public/employee_images/'.$user->employee_picture);
            /* Aktualizace databaze */
            $user->update(['employee_picture' => NULL]);
        }
        return redirect()->back();
    }

    /* Nazev funkce: uploadEmployeeImage
       Argumenty: request - vybrany obrazek
       Ucel: Nahrani profiloveho obrazku zamestnance */
    public function uploadEmployeeImage(Request $request){
        /* Overeni, ze pozadavek obsahuje nejaky soubor */
        if($request->hasFile('obrazek')){
            /* Definice pravidel pro validaci a nasledne jeji provedeni */
            $validator = Validator::make($request->all(),['obrazek' => ['required','mimes:jpg,jpeg,png','max:8096']]);
            /* Pokud nejsou splneny podminky pro profilovy obrazek je uzivateli poslana chybova hlaska */
            if($validator->fails()){
                session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg], maximální velikost obrázku je 8MB!');
                return redirect()->back();
            }
            $user = Auth::user();
            /* Smazani puvodniho profiloveho obrazku zamestnance */
            if($user->employee_picture != NULL){ Storage::delete('/public/employee_images/'.$user->employee_picture); }
            /* Usek kodu pro vytvoreni nazvu obrazku v systemu */
            $tokenUnique = Str::random(20);
            $tokenUnique2 = Str::random(5);
            $tokenUnique3 = Str::random(10);
            /* Ulozeni obrazku do systemu */
            $request->obrazek->storeAs('employee_images',$tokenUnique.$tokenUnique2.$tokenUnique3, 'public');
            /* Aktualizace jmena obrazku v databazi */
            $user->update(['employee_picture' => $tokenUnique.$tokenUnique2.$tokenUnique3]);
            /* Odeslani hlasky o uspechu uzivateli */
            session()->flash('obrazekZpravaSuccess', 'Profilová fotka úspěšně nahrána.');
        }
        return redirect()->back();
    }

    /* Nazev funkce: getAllGoogleDriveFilesCheckboxes
       Argumenty: zadne
       Ucel: ziskani vsech souboru a slozek v ramci Google Drive slozky zamestnance */
    public function getAllGoogleDriveFilesCheckboxes(){
        $user = Auth::user();
        /* Promenna, do ktere se bude ukladat HTML kod */
        $out = '';
        /*Cesta k autorizacnimu klici*/
        $authKey = storage_path('app/credentials.json');
        /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
        $httpClient = new Client(['verify' => false]);
        /* Inicializace Google Drive klienta */
        $GoogleClient = new Google_Client();
        /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
        $GoogleClient->setHttpClient($httpClient);
        /* Nastaveni prihlaseni ke Google Drive API */
        $GoogleClient->setAuthConfig($authKey);
        /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
        $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
        $googleServ = new Google_Service_Drive($GoogleClient);
        /* Definice pro vyhledavani souboru (jake udaje ze souboru potrebujeme a kde se nachazi misto pro prohledavani souboru) */
        $optParams = ["fields" => "nextPageToken, files(id, name, fileExtension)", "q" => "'" . $user->employee_url . "' in parents"];
        /* Nalezeni vsech souboru slozky */
        $slozkyDelete = $googleServ->files->listFiles($optParams);
        /* Zjisteni, zdali zamestnanec vubec ma nejake soubory/slozky na Google Drive */
        if(count($slozkyDelete) == 0){
            $out .= '<div class="alert alert-danger alert-block" style="font-size: 16px;">
                        <strong>Na Google Drive nemáte žadné soubory/složky.</strong>
                    </div>';
        }else{ // pokud ano, tak je generovan checkbox pro kazdou slozku/soubor
            $out .= '<div class="alert alert-info alert-block text-center" style="font-size: 16px;"><strong>Seznam souborů na Vašem Google Drive. Vyberte, které soubory chcete odstranit.</strong></div>';
            foreach ($slozkyDelete as $slozkaDelete){
                $out .= '<center><div class="form-check" style="margin-bottom: 10px;">
                                     <input type="checkbox" style="width: 17px; height: 17px;" class="form-check-input" id="'.$slozkaDelete->name.'" name="google_drive_delete_listFile[]" value="'.$slozkaDelete->id.'">
                                     <label class="form-check-label" style="font-size:16px;margin-top:1px;" for="'.$slozkaDelete->name.'">&nbsp;'.$slozkaDelete->name.'</label>
                                </div></center>';
            }
        }
        /* Zaslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: getAllGoogleDriveFoldersOptions
       Argumenty: zadne
       Ucel: ziskani vsech slozek v ramci Google Drive slozky zamestnance */
    public function getAllGoogleDriveFoldersOptions(){
        $user = Auth::user();
        /* Promenna, do ktere se bude ukladat HTML kod */
        $out = '';
        /* Promenna, do ktere se ulozi jednotlive slozky */
        $slozky="";
        /*Cesta k autorizacnimu klici*/
        $authKey = storage_path('app/credentials.json');
        /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
        $httpClient = new Client(['verify' => false]);
        /* Inicializace Google Drive klienta */
        $GoogleClient = new Google_Client();
        /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
        $GoogleClient->setHttpClient($httpClient);
        /* Nastaveni prihlaseni ke Google Drive API */
        $GoogleClient->setAuthConfig($authKey);
        /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
        $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
        $googleServ = new Google_Service_Drive($GoogleClient);
        /* Definice pro vyhledavani slozek (jake udaje ze slozek potrebujeme a kde se nachazi misto pro prohledavani slozek) */
        $optParams = ["fields" => "nextPageToken, files(id, name, fileExtension)", "q" => "mimeType='application/vnd.google-apps.folder' AND '" . $user->employee_url . "' in parents"];
        /* Nalezeni vsech slozek slozky */
        $slozky = $googleServ->files->listFiles($optParams);
        /* Definice obsahu modalniho okna */
        $out .= '<div class="alert alert-info alert-block text-center"><strong>Vyberte, do které složky chcete soubor nahrát.</strong></div>';
        /* Ulozeni nazvu slozek jako moznosti pro selectbox */
        $out .='<div class="form-group">
                    <select name="slozky" id="slozky" style="color:black" class="form-control input-lg" required>
                         <option value="">Vyberte složku</option>
                         <option value="'.$user->employee_url.'">/</option>';
        foreach ($slozky as $slozka){
            $out .= '<option value="'.$slozka->id.'">'.$slozka->name.'</option>';
        }
        $out .= '</select></div>'; // ukonceni moznosti
        /* Zaslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: createFolderGoogleDrive
       Argumenty: request - nazev slozky
       Ucel: vytvoreni nove slozky na Google Drive */
    public function createFolderGoogleDrive(Request $request){
        $user = Auth::user();
        /* Pozadovany nazev slozky v Google Drive */
        $slozka=$request->nazev;
        /*Cesta k autorizacnimu klici*/
        $authKey = storage_path('app/credentials.json');
        /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
        $httpClient = new Client(['verify' => false]);
        /* Inicializace Google Drive klienta */
        $GoogleClient = new Google_Client();
        /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
        $GoogleClient->setHttpClient($httpClient);
        /* Nastaveni prihlaseni ke Google Drive API */
        $GoogleClient->setAuthConfig($authKey);
        /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
        $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
        $googleServ = new Google_Service_Drive($GoogleClient);
        /*Vytvoření složky*/
        $nova_slozka = new Google_Service_Drive_DriveFile();
        /* Nastaveni nazvu slozky */
        $nova_slozka->setName($slozka);
        /* Nastaveni typu MIME na typ slozky */
        $nova_slozka->setMimeType('application/vnd.google-apps.folder');
        /*Nasměrování do Google Drive slozky zamestnance */
        $nova_slozka->setParents([$user->employee_url]);
        /* Vytvoreni slozky v Google Drive */
        $googleServ->files->create($nova_slozka, ['mimeType' => "application/vnd.google-apps.folder", 'uploadType' => "multipart"]);
        /* Odeslani hlasky o uspechu uzivateli */
        session()->flash('success', 'Složka '.$request->nazev.' byla úspešně vytvořena na Vašem Google Drive!');
        return redirect()->back();
    }

    /* Nazev funkce: uploadGoogleDrive
       Argumenty: request - soubor k nahrani na Google Drive
       Ucel: nahrani souboru do Google Drive slozky zamestnance */
    public function uploadGoogleDrive(Request $request){
        /*Cesta k autorizacnimu klici*/
        $authKey = storage_path('app/credentials.json');
        /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
        $httpClient = new Client(['verify' => false]);
        /* Inicializace Google Drive klienta */
        $GoogleClient = new Google_Client();
        /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
        $GoogleClient->setHttpClient($httpClient);
        /* Nastaveni prihlaseni ke Google Drive API */
        $GoogleClient->setAuthConfig($authKey);
        /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
        $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
        $googleServ = new Google_Service_Drive($GoogleClient);

        /* Vytvoření souboru */
        $novy_soubor = new Google_Service_Drive_DriveFile();
        $novy_soubor->setName($request->file('soubor')->getClientOriginalName());
        /* Nastaveni typu MIME souboru */
        $novy_soubor->setMimeType($request->file('soubor')->getMimeType());
        /* Nasmerovani do slozky zamestnance */
        $novy_soubor->setParents([$request->slozky]);
        /* Vytvoreni souboru na Google Drive */
        $googleServ->files->create($novy_soubor, ['data' => file_get_contents($request->soubor), 'mimeType' => $request->file('soubor')->getMimeType(), 'uploadType' => "resumable"]);
        /* Odeslani hlasky o uspechu uzivateli */
        session()->flash('success', 'Soubor '.$request->file('soubor')->getClientOriginalName().' byl úspešně nahrán na Váš Google Drive!');
        return redirect()->back();
    }

    /* Nazev funkce: deleteFileGoogleDrive
       Argumenty: request - soubory ci slozky ke smazani na Google Drive
       Ucel: odstraneni souboru ci slozek z Google Drive slozky zamestnance */
    public function deleteFileGoogleDrive(Request $request){
        /* Pokud uzivatel vybral nejake soubory ci slozky */
        if ($request->google_drive_delete_listFile != NULL) {
            /*Cesta k autorizacnimu klici*/
            $authKey = storage_path('app/credentials.json');
            /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
            $httpClient = new Client(['verify' => false]);
            /* Inicializace Google Drive klienta */
            $GoogleClient = new Google_Client();
            /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
            $GoogleClient->setHttpClient($httpClient);
            /* Nastaveni prihlaseni ke Google Drive API */
            $GoogleClient->setAuthConfig($authKey);
            /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
            $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
            $googleServ = new Google_Service_Drive($GoogleClient);
            /* Postupne odstranovani souboru */
            foreach ($request->google_drive_delete_listFile as $slozka){ $googleServ->files->delete($slozka); }
            /* Odeslani hlasky o uspechu uzivateli */
            if (count($request->google_drive_delete_listFile) == 1) {
                session()->flash('success', 'Soubor byl úspešně smazán!');
            }else{
                session()->flash('success', 'Soubory byly úspešně smazány!');
            }
        }else { // pokud nevybral zadny soubor ci slozku
            session()->flash('fail', 'Nevybral jste žádný soubor!');
        }
        /* Presmerovani zpet */
        return redirect()->back();
    }

}
