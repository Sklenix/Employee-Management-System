<?php

namespace App\Http\Controllers;
use App\Models\Employee_Language;
use App\Models\Employee_Shift;
use App\Models\ImportancesShifts;
use App\Models\Shift;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Languages;
use App\Models\Employee;
use Google_Service_Drive_Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Google_Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class UserCompanyController extends Controller {
    /* Nazev souboru:  UserCompanyController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazovani dashboardu a profilu uctu s roli firmy. Dale slouzi ke zmene udaju (vcetne hesla a profiloveho obrazku) v profilu firmy a take k praci s Google Drive v ramci firmy.
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
       Ucel: Zobrazeni firemni domovske stranky */
    public function index() {
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        return view('homes.company_home')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('company_url', $user->company_url);
    }

    /* Nazev funkce: validator
       Argumenty: udaje - udaje zadane uzivatelem, emailDuplicate - indikacni promenna znacici stejne emailove adresy, loginDuplicate - indikacni promenna znacici stejne prihlasovaci jmena, verze - volba validatoru
       Ucel: Validace udaju zadane uzivatelem */
    protected function validator(array $udaje, $emailDuplicate, $loginDuplicate, $verze) {
        if($verze == 1){
            if($emailDuplicate == 1 && $loginDuplicate == 0){ // emailove adresy jsou stejne, loginy nikoliv
                if($udaje['ico'] == NULL){ // ico neni zadano
                    /* Definice pravidel pro validaci */
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'prihlasovaci_jmeno' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'prihlasovaci_jmeno' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'ico' => ['digits:8'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }
            }else if($loginDuplicate == 1 && $emailDuplicate == 0){ // loginy jsou stejne, emailove adresy nikoliv
                if($udaje['ico'] == NULL){ // ico neni zadano
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'ico' => ['digits:8'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }
            }else if($loginDuplicate == 1 && $emailDuplicate == 1){ // loginy jsou stejne a emailove adresy taktez
                if($udaje['ico'] == NULL){ // ico neni zadano
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'ico' => ['digits:8'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }
            }else if($loginDuplicate == 0 && $emailDuplicate == 0){ // emailove adresy ani loginy nejsou stejne
                if($udaje['ico'] == NULL){
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'prihlasovaci_jmeno' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'nazev_spolecnosti' => ['required', 'string', 'max:255'],
                        'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                        'prijmeni' =>  ['required', 'string', 'max:255'],
                        'email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                        'prihlasovaci_jmeno' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'ico' => ['digits:8'],
                        'mesto_sidla' => ['string', 'max:255'],
                        'ulice_sidla' => ['max:255']
                    ];
                }
            }
        }else if($verze == 2){ // verze pro zamestnance
            $pravidla = [
                'krestni_jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
                'pozice' =>  ['required', 'string', 'max:255'],
                'email' => ['required','unique:table_employees,email','string','email','max:255'],
                'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                'prihlasovaci_jmeno' => ['required','unique:table_employees,employee_login', 'string', 'max:255'],
                'poznamka' => ['nullable','max:180'],
                'mesto_bydliste' => ['required','string', 'max:255'],
                'ulice_bydliste' => ['nullable','max:255'],
                'heslo' => ['required', 'string', 'min:8','required_with:overeni_hesla','same:overeni_hesla'],
                'profilovy_obrazek' => ['mimes:jpeg,jpg,png,gif','max:20000']
            ];
        }
        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'before' => 'Datum narození musí být nejméně před 15 lety.',
            'regex' => 'Formát :attribute není validní.',
            'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.',
            'unique' => 'Váš e-mail, nebo Váš login už v databázi evidujeme.',
            'digits' => 'Číslo musí mít 8 cifer.'
        ];
        /* Realizace zvalidovani */
        Validator::validate($udaje, $pravidla, $vlastniHlasky);
    }

    /* Nazev funkce: updateProfileData
       Argumenty: request - udaje zadane firmou
       Ucel: Validace udaju zadane uzivatelem */
    public function updateProfileData(Request $request) {
        $user = Auth::user();
        /* Usek kodu ke zjisteni duplicit */
        $emailDuplicate = 0;
        $loginDuplicate = 0;
        if($user->email == $request->email){ $emailDuplicate = 1; }
        if($user->company_login == $request->prihlasovaci_jmeno){ $loginDuplicate = 1; }
        /* Zavolani validatoru */
        $this->validator($request->all(),$emailDuplicate,$loginDuplicate,1);
        if($user->company_url != ""){
            if($user->company_name == $request->nazev_spolecnosti && $user->email == $request->email){ // pokud je nazev firmy i email firmy stejny, tak neni spustena aktualizace nazvu Google Drive slozky firmy
            }else{
                /* Usek kodu zabyvajici se nazvem slozky v Google Drive firmy */
                $souborZmena = $request->nazev_spolecnosti . " " . $request->email;
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
                $googleServ->files->update($user->company_url, $zmena_jmena, ['uploadType' => 'multipart']);
            }
        }
        /* Aktualizace udaju v databazi */
        Company::where(['company_id' => $user->company_id])->update(['company_name' => $request->nazev_spolecnosti, 'company_user_name' => $request->krestni_jmeno, 'company_user_surname' => $request->prijmeni, 'email' => $request->email, 'company_city' => $request->mesto_sidla, 'company_street' => $request->ulice_sidla,
                        'company_ico' => $request->ico, 'company_phone' => $request->telefon, 'company_login' => $request->prihlasovaci_jmeno]);
        /* Aktualizace udaju v OLAP sekci systemu */
        OlapETL::updateCompanyDimension($user->company_id, $request->nazev_spolecnosti, $request->mesto_sidla, $request->ulice_sidla, $request->krestni_jmeno, $request->prijmeni);
        session()->flash('message', 'Vaše údaje byly úspěšně změněny!');
        return redirect()->route('showCompanyProfileData');
    }

    /* Nazev funkce: updateProfilePassword
       Argumenty: request - hesla zadane firmou
       Ucel: Validace hesla zadaneho firmou */
    public function updateProfilePassword(Request $request) {
        $user = Auth::user();
        if($request->heslo == $request->overeni_heslo){
            if($request->heslo == "" || $request->overeni_heslo == ""){
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
                return redirect()->back();
            }else if(strlen($request->heslo) < 8){
                session()->flash('errorZprava', 'Heslo musí mít alespoň 8 znaků!');
                return redirect()->back();
            }else{
                $user->password= Hash::make($request->heslo);
            }
        }else{
            if($request->heslo == "" || $request->overeni_heslo == ""){
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
            }
            session()->flash('errorZprava', 'Hesla se neshodují!');
            return redirect()->back();
        }
        $user->save();
        session()->flash('message', 'Vaše heslo bylo úspešně změněno!');
        return redirect()->back();
    }

    /* Nazev funkce: deleteOldImage
       Argumenty: zadne
       Ucel: Smazani profiloveho obrazku */
    public function deleteOldImage() {
        $user = Auth::user();
        if($user->company_picture != NULL){
            Storage::delete('/public/company_images/'.$user->company_picture);
            $user->update(['company_picture' => NULL]);
        }
        return redirect()->back();
    }

    /* Nazev funkce: uploadImage
       Argumenty: request - profilovy obrazek
       Ucel: Nahrani profiloveho obrazku */
    public function uploadImage(Request $request) {
        if($request->hasFile('obrazek')){
            /* Definice pravidel */
           $validator = Validator::make($request->all(),['obrazek' => ['required','mimes:jpg,jpeg,png','max:8096']]);
           /* Odeslani chybove hlasky */
           if($validator->fails()){
                session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg], maximální velikost obrázku je 8MB!');
                return redirect()->back();
           }
           /* Smazani stareho profiloveho obrazku */
           $user = Auth::user();
           if($user->company_picture){
               Storage::delete('/public/company_images/'.$user->company_picture);
           }
           /* Vytvoreni nazvu profiloveho obrazku a nasledne jeho ulozeni na server a aktualizace nazvu profiloveho obrazku v databazi */
           $tokenUnique = Str::random(20);
           $tokenUnique2 = Str::random(5);
           $tokenUnique3 = Str::random(10);
           $request->obrazek->storeAs('company_images',$tokenUnique.$tokenUnique2.$tokenUnique3,'public');
           $user->update(['company_picture' => $tokenUnique.$tokenUnique2.$tokenUnique3]);
           /* Odeslani odpovedi */
           session()->flash('obrazekZpravaSuccess', 'Profilová fotka úspěšně nahrána.');
        }
        return redirect()->back();
    }

    /* Nazev funkce: showCompanyProfileData
       Argumenty: zadne
       Ucel: Zobrazeni profilu firmy */
    public function showCompanyProfileData() {
        $user = Auth::user();
        return view('profiles.company_profile')
            ->with('profilovka',$user->company_picture);
    }

    /* Nazev funkce: deleteCompanyProfile
       Argumenty: zadne
       Ucel: Smazani uctu firmy */
    public function deleteCompanyProfile() {
        $user = Auth::user();
        /* Smazani z OLAP sekce systemu */
        OlapETL::deleteRecordFromCompanyDimension($user->company_id);
        /* Smazani z databaze */
        DB::table('table_companies')
            ->where(['table_companies.company_id' => $user->company_id])
            ->delete();
        if($user->company_url != ""){
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
            $results = $googleServ->files->get($user->company_url);
            /* Usek kodu, ktery zjisti, zdali existuje Google Drive slozka firmy, pokud ano, tak se odstrani */
            if($results != NULL) {
                $googleServ->files->delete($user->company_url);
            }
        }
        /* Odeslani zpravy uzivateli a presmerovani na prihlasovaci formular firmy */
        session()->flash('success', 'Váš účet byl úspěšně smazán!');
        return redirect()->route('renderCompanyLogin');
    }

    /* Nazev funkce: createFolderGoogleDrive
       Argumenty: request - nazev slozky
       Ucel: vytvoreni nove slozky na Google Drive  */
    public function createFolderGoogleDrive(Request $request) {
        $user = Auth::user();
        /* Pozadovany nazev slozky v Google Drive */
        $slozka = $request->nazev;
        /* Cesta k autorizacnimu klici */
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
        $nova_slozka->setParents([$user->company_url]);
        /* Vytvoreni slozky v Google Drive */
        $googleServ->files->create($nova_slozka, ['mimeType' => "application/vnd.google-apps.folder", 'uploadType' => "multipart"]);
        /* Odeslani hlasky o uspechu uzivateli */
        session()->flash('success', 'Složka '.$request->nazev.' byla úspešně vytvořena na Vašem Google Drive!');
        return redirect()->back();
    }

    /* Nazev funkce: addLanguage
       Argumenty: request - nazev jazyka
       Ucel: vytvoreni noveho jazyka v ramci firmy */
    public function addLanguage(Request $request) {
        $user = Auth::user();
        /* Definice pravidel pro validaci nasledne jeji provedeni */
        $validator = Validator::make($request->all(), [ 'jazyk' => ['required','min:2','string', 'max:30']]);
        /* Pokud validace selze */
        if ($validator->fails()) {
            $chyby = implode($validator->errors()->all());
            session()->flash('errory', $chyby);
            return redirect()->back();
        }
        /* Vytvoreni jazyka v databazi */
        Languages::create(['language_name' => $request->jazyk, 'company_id' =>  $user->company_id]);
        /* Odeslani odpovedi uzivateli */
        session()->flash('success', 'Jazyk '.$request->jazyk.' byl úspešně přidán do výběru!');
        return redirect()->back();
    }


    /* Nazev funkce: removeLanguage
       Argumenty: request - jazyky urcene ke smazani
       Ucel: odstraneni vybranych jazyku firmy */
    public function removeLanguage(Request $request){
        /* Postupne mazani jazyku v cyklu a nasledovne poslani odpovedi uzivateli */
        for ($i = 0;$i < count($request->jazyky);$i++){ Languages::where('language_id', $request->jazyky[$i])->delete(); }
        session()->flash('success', 'Jazyk/y '.$request->jazyk.' byl úspešně smazán z výběru!');
        return redirect()->back();
    }

    /* Nazev funkce: uploadGoogleDrive
       Argumenty: request - soubor k nahrani na Google Drive
       Ucel: nahrani souboru do Google Drive slozky firmy */
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
        /* Nasmerovani do slozky firmy */
        $novy_soubor->setParents([$request->slozky]);
        /* Vytvoreni souboru na Google Drive */
        $googleServ->files->create($novy_soubor, ['data' => file_get_contents($request->soubor), 'mimeType' => $request->file('soubor')->getMimeType(), 'uploadType' => "resumable"]);
        /* Odeslani hlasky o uspechu uzivateli */
        session()->flash('success', 'Soubor '.$request->file('soubor')->getClientOriginalName().' byl úspešně nahrán na Váš Google Drive!');
        return redirect()->back();
    }

    /* Nazev funkce: getAllGoogleDriveFoldersOptions
       Argumenty: zadne
       Ucel: ziskani vsech slozek v ramci Google Drive slozky firmy */
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
        $optParams = ["fields" => "nextPageToken, files(id, name, fileExtension)", "q" => "mimeType='application/vnd.google-apps.folder' AND '" . $user->company_url . "' in parents"];
        /* Nalezeni vsech slozek slozky */
        $slozky = $googleServ->files->listFiles($optParams);
        /* Definice obsahu modalniho okna */
        $out .= '<div class="alert alert-info alert-block text-center"><strong>Vyberte, do které složky chcete nahrát soubor.</strong></div>';
        /* Ulozeni nazvu slozek jako moznosti pro selectbox */
        $out .='<div class="form-group">
                    <select name="slozky" id="slozky" style="color:black" class="form-control input-lg" required>
                         <option value="">Vyberte složku</option>
                         <option value="'.$user->company_url.'">/</option>';
        foreach ($slozky as $slozka){
            $out .= '<option value="'.$slozka->id.'">'.$slozka->name.'</option>';
        }
        $out .= '</select></div>'; // ukonceni moznosti
        /* Zaslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: getAllGoogleDriveFilesCheckboxes
     Argumenty: zadne
     Ucel: ziskani vsech souboru a slozek v ramci Google Drive slozky firmy */
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
        $optParams = ["fields" => "nextPageToken, files(id, name, fileExtension)", "q" => "'" . $user->company_url . "' in parents"];
        /* Nalezeni vsech souboru slozky */
        $slozkyDelete = $googleServ->files->listFiles($optParams);
        /* Zjisteni, zdali ma firma vubec nejake soubory/slozky na Google Drive */
        if(count($slozkyDelete) == 0){
            $out .= '<div class="alert alert-danger alert-block" style="font-size: 16px;">
                        <strong>Na Google Drive nemáte žadné soubory/složky.</strong>
                    </div>';
        }else{ // pokud ano, tak je generovan checkbox pro kazdou slozku/soubor
            $out .= '<div class="alert alert-info alert-block text-center" style="font-size: 16px;"><strong>Seznam souborů na Vašem Google Drive. Vyberte, které soubory chcete smazat.</strong></div>';
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

    /* Nazev funkce: deleteFileGoogleDrive
      Argumenty: request - soubory ci slozky ke smazani na Google Drive
      Ucel: odstraneni souboru ci slozek z Google Drive slozky firmy */
    public function deleteFileGoogleDrive(Request $request){
        /* Pokud uzivatel vybral nejake soubory ci slozky */
        if ($request->google_drive_delete_listFile != NULL) {
            /* Cesta k autorizacnimu klici */
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

    /* Nazev funkce: addEmployee
      Argumenty: request - udaje zadane firmou o novem zamestnanci
      Ucel: vytvoreni noveho zamestnance */
    public function addEmployee(Request $request){
        $user = Auth::user();
        /* Zvalidovani udaju */
        $this->validator($request->all(),-1,-1,2);

        /* Ziskani prihlasovaciho jmena zamestnance do promenne */
        $uzivatel = $request->prihlasovaci_jmeno;

        /* Vytvoreni zamestnance */
        Employee::create(['employee_name' => $request->krestni_jmeno, 'employee_surname' => $request->prijmeni, 'employee_birthday' => $request->narozeniny, 'employee_phone' => $request->telefon, 'email' => $request->email, 'employee_note' => $request->poznamka, 'employee_position' => $request->pozice, 'employee_city' => $request->mesto_bydliste,
            'employee_street' => $request->ulice_bydliste, 'employee_login' => $request->prihlasovaci_jmeno, 'password' => Hash::make($request->heslo), 'employee_company' => $user->company_id, 'employee_url' => ""]);

        /* Ziskani zamestnance */
        $employeeSearch = Employee::where('employee_login', '=',$uzivatel)->first();
        /* Vytvoreni jazyku zamestnance podle vyberu firmy */
        if($request->jazyky != ""){
            for($i = 0; $i < count($request->jazyky); $i++){
                Employee_Language::create(['language_id' => $request->jazyky[$i], 'employee_id' => $employeeSearch->employee_id,]);
            }
        }
        /* Usek kodu zabyvajici se nahranim profiloveho obrazku zamestnanci */
        if($request->hasFile('profilovy_obrazek')){
            $validator = Validator::make($request->all(),['profilovy_obrazek' => 'required|mimes:jpg,jpeg,png|max:8096',]);
            if($validator->fails()){
                session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg], maximální velikost obrázku je 8MB!');
                return redirect()->back();
            }
            if($employeeSearch->employee_picture != NULL){ Storage::delete('/public/employee_images/'.$employeeSearch->employee_picture); }
            $tokenUnique = Str::random(20);
            $tokenUnique2 = Str::random(5);
            $tokenUnique3 = Str::random(10);
            $request->profilovy_obrazek->storeAs('employee_images',$tokenUnique.$tokenUnique2.$tokenUnique3,'public');
            $employeeSearch->update(['employee_picture' => $tokenUnique.$tokenUnique2.$tokenUnique3]);
        }

        /*Pozadovany nazev slozky v Google Drive */
        $nazev_slozky = $request->krestni_jmeno.' '.$request->prijmeni;
        /* Cesta k autorizacnimu klici */
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
        /* Vytvoreni slozky */
        $nova_slozka = new Google_Service_Drive_DriveFile();
        /* Nastaveni jmena slozky */
        $nova_slozka->setName($nazev_slozky);
        /* Nastaveni typu MIME */
        $nova_slozka->setMimeType('application/vnd.google-apps.folder');
        /*Nasmerování do slozky firmy */
        $nova_slozka->setParents([$user->company_url]);
        /* Vytvoreni slozky na Google Drive */
        $createdFolder = $googleServ->files->create($nova_slozka, ['mimeType' => 'application/vnd.google-apps.folder', 'uploadType' => "multipart"]);
        /* Ulozeni identifikatoru nove slozky do promenne */
        $folderId = $createdFolder->id;
        /* Pokud firma zvolila, ze chce nasdilet zamestnancovu slozku */
        if(isset($request->googleDriveRequest)) {
            $userPermission = new Google_Service_Drive_Permission(['type' => 'user', 'role' => 'writer', 'emailAddress' => $request->email]);
            $googleServ->permissions->create($folderId, $userPermission, ['emailMessage' => "Dobrý den, Vaše firma Vám nasdílela Vaši Google Drive složku."]);
            /* Aktualizace identifikatoru slozky v databazi u konkretniho zamestnance */
            $employeeSearch->update(['employee_url' => $folderId]);
        }
        session()->flash('success', 'Zaměstnanec '.$request->krestni_jmeno.' '.$request->prijmeni.' byl úspešně vytvořen!');
        return redirect()->back();
    }

    /* Nazev funkce: addShift
      Argumenty: request - udaje zadane firmou o nove smene
      Ucel: vytvoreni nove smeny */
    public function addShift(Request $request){
        $user = Auth::user();
        /* Definice pravidel pro validaci a nasledne jeji provedeni*/
        $validator = Validator::make($request->all(), ['zacatek_smeny' => ['required'], 'konec_smeny' =>  ['required'], 'lokace_smeny' =>  ['required', 'string', 'max:255'], 'poznamka' => ['max:180']]);
        /* Ziskani udaju o smene */
        $shift_start = new DateTime($request->zacatek_smeny);
        $shift_end = new DateTime($request->konec_smeny);
        $chybaDatumy = array();
        $bool_datumy = 0;

        /* Usek kodu, ktery slouzi k dodatecne validaci */
        $difference_shifts = $shift_end->format('U') - $shift_start->format('U');
        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetDnu = $hodinyRozdil->d;
        $pocetHodin = $hodinyRozdil->h;
        $pocetMinut = $hodinyRozdil->i;
        if($request->zacatek_smeny != NULL){
            if($difference_shifts <= 0){
                array_push($chybaDatumy,'Konec směny je stejný buďto stejný jako její začátek, nebo je dříve než samotný začátek!');
                $bool_datumy = 1;
            }
            if(($pocetHodin == 12 && $pocetMinut > 0) || $pocetHodin > 12 || $pocetDnu > 0){
                array_push($chybaDatumy,'Maximální délka jedné směny je 12 hodin!');
                $bool_datumy = 1;
            }
        }
        /* Naplneni hlasek do pole */
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        /* Pripadne odeslani chyb */
        if ($validator->fails() || $bool_datumy == 1) {
            session()->flash('erroryShift', $chybaDatumy);
            return redirect()->back();
        }
        /* Vytvoreni smeny v databazi */
        Shift::create(['shift_start' => $request->zacatek_smeny, 'shift_end' => $request->konec_smeny, 'shift_place' =>  $request->lokace_smeny, 'shift_importance_id' => $request->dulezitost_smeny, 'shift_note' => $request->poznamka, 'company_id' => $user->company_id]);
        /* Odeslani odpovedi uzivateli */
        session()->flash('success', 'Směna byla úspešně vytvořena!');
        return redirect()->back();
    }

    /* Nazev funkce: getAllShifts
      Argumenty: zadne
      Ucel: Ziskani seznamu smen firmy */
    public function getAllShifts(){
        $user = Auth::user();
        /* Definice zahlavi tabulky a vyhledavace */
        $out = '<input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavacSmenySmazani" placeholder="Hledat směnu dle začátku, lokace, nebo konce směny ...">
                    <table class="table table-dark" id="show_table_employee_delete" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th style="width:20%;text-align: center;">Začátek</th>
                                <th style="width:20%;text-align: center;">Konec</th>
                                <th style="width:20%;text-align: center;">Lokace</i></th>
                                <th style="width:22%;text-align: center;">Počet zaměstnanců</i></th>
                                <th style="width:18%;text-align: center;">Odstranit</th>
                            </tr>
                        </thead>
                        <tbody>';
        /* Ziskani vsech smen */
        $smeny = Shift::getCompanyShiftsDesc($user->company_id);
        /* Iterace skrze smeny */
        foreach ($smeny as $smena){
            /* Zmena formatu datumu */
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            /* Ziskani poctu zamestnancu na konkretni smene */
            $pocet_zamestnancu = Employee_Shift::getEmployeesShiftCounts($smena->shift_id);
            /* Zapis udaju ve formatu jazyka HTML do promenne out */
            $out .= '<tr><td class="text-center">'.$smena->shift_start.'</td><td class="text-center"> '.$smena->shift_end.'</td>
                      <td class="text-center"> '.$smena->shift_place.'</td>
                      <td class="text-center"> '.$pocet_zamestnancu.'</td>
                      <td class="text-center"><input type="checkbox" class="form-check-input"  id="smenyDeleteDashboard" name="smenyDeleteDashboard[]" value="'.$smena->shift_id.'"></td>
                     </tr>';
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        $out .= '<script>
              /* Implementace vyhledavace v ramci hledani smen */
              $(document).ready(function(){
                  $("#vyhledavacSmenySmazani").on("keyup", function() { // po zapsani znaku ve vyhledavani
                    var retezec = $("#vyhledavacSmenySmazani").val(); // ziskani hodnoty ve vyhledavaci
                    var vysledek = retezec.toUpperCase(); // transformace hodnoty na velka pismena
                    var radkyTabulky = $("#show_table_employee_delete tr"); // ziskani radku tabulek
                    radkyTabulky.each(function () { // iterace skrze radky tabulky
                        var bunka = $(this).find("td"); // ziskani hodnoty bunky
                        bunka.each(function () { // iterace skrz bunky
                            var obsahBunky = $(this).text(); // ulozeni hodnoty bunky
                            if((obsahBunky.toUpperCase().includes(vysledek) == false) == false){ // kontrola zdali hledany retezec je podmnozinou nejake hodnoty v tabulce
                                  $(this).closest("tr").toggle(true); // radek je ponechan
                                  return false; // pokracovani dalsim radkem
                            }else{
                                  $(this).closest("tr").toggle(false); // schovani radku tabulky, v ktere se nachazi aktualni bunka
                                  return true; // pokracovani dalsi bunkou radku
                            }
                        });
                     });
                  });
                });
        </script>';
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: deleteShift
      Argumenty: request - vybrane smeny
      Ucel: smazani vybranych smen */
    public function deleteShift(Request  $request){
        $user = Auth::user();
        /* Pokud uzivatel vybral nejakou smenu */
        if($request->smenyDeleteDashboard != NULL){
            /* Iterace skrze tyto smeny, pri kazde iterace se provede smazani dane smeny */
            foreach ($request->smenyDeleteDashboard as $id_smeny){
                /* Ziskani smeny */
                $smena = Shift::find($id_smeny);
                /* Ziskani zamestnancu na smene */
                $zamestnanci = Shift::getAllEmployeesAtShift($id_smeny);
                $employee_ids = array();
                /* Naplneni identifikatoru zamestnancu do pole */
                foreach ($zamestnanci as $zamestnanec) { array_push($employee_ids, $zamestnanec->employee_id); }
                /* Realizace smazani z OLAP sekce systemu */
                OlapETL::deleteRecordFromShiftInfoDimension($employee_ids, $user->company_id, $smena->shift_start, $smena->shift_end);
                /* Smazani smeny z databaze*/
                DB::table('table_shifts')->where(['table_shifts.shift_id' => $id_smeny])->delete();
            }
            /* Nastaveni hlasky, ktera se odesle uzivateli */
            if(count($request->smenyDeleteDashboard) == 1){
                session()->flash('success', 'Směna byla úspešně smazána!');
            }else{
                session()->flash('success', 'Směny byly úspešně smazány!');
            }

        }else{
            session()->flash('fail', 'Vyberte nějakou směnu!');
        }
        return redirect()->back();
    }

    /* Nazev funkce: getAllEmployees
       Argumenty: zadne
       Ucel: Ziskani seznamu zamestnancu firmy */
    public function getAllEmployees(){
        $user = Auth::user();
        $out = '<input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavacZamestnanciSmazani" onkeyup="Search()" placeholder="Hledat zaměstnance na základě jeho jména, příjmení, pozice, nebo počtu směn ..." title="Zadejte údaje o směně">
                    <table class="table table-dark" id="show_table" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th style="width:25%;text-align: center;">Jméno</th>
                                <th style="width:25%;text-align: center;">Příjmení</th>
                                <th style="width:25%;text-align: center;">Pozice</th>
                                <th style="width:25%;text-align: center;">Počet směn</th>
                                <th style="width:25%;text-align: center;">Odstranit</th>
                            </tr>
                        </thead>
                        <tbody>';
        /* Ziskani zamestnancu */
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        /* Iterace skrze zamestnance */
        foreach ($zamestnanci as $zamestnanec){
            /* Ziskani poctu smen */
            $pocet_smen = Employee_Shift::getEmployeeShiftsCounts($zamestnanec->employee_id);
            /* Vyplneni udaju do promenne out ve formatu HTML */
            $out .= '<tr><td class="text-center">'.$zamestnanec->employee_name.'</td><td class="text-center"> '.$zamestnanec->employee_surname.'</td>
                      <td class="text-center"> '.$zamestnanec->employee_position.'</td>
                      <td class="text-center"> '.$pocet_smen.'</td>
                      <td class="text-center"><center><input type="checkbox" class="form-check-input"  id="zamestnanciDeleteDashboard" name="zamestnanciDeleteDashboard[]" value="'.$zamestnanec->employee_id.'"></center></td>';
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        $out .= '<script>
              /* Implementace vyhledavace v ramci hledani smen */
              $(document).ready(function(){
                  $("#vyhledavacZamestnanciSmazani").on("keyup", function() { // po zapsani znaku ve vyhledavani
                    var retezec = $("#vyhledavacZamestnanciSmazani").val(); // ziskani hodnoty ve vyhledavaci
                    var vysledek = retezec.toUpperCase(); // transformace hodnoty na velka pismena
                    var radkyTabulky = $("#show_table_employee_delete tr"); // ziskani radku tabulek
                    radkyTabulky.each(function () { // iterace skrze radky tabulky
                        var bunka = $(this).find("td"); // ziskani hodnoty bunky
                        bunka.each(function () { // iterace skrz bunky
                            var obsahBunky = $(this).text(); // ulozeni hodnoty bunky
                            if((obsahBunky.toUpperCase().includes(vysledek) == false) == false){ // kontrola zdali hledany retezec je podmnozinou nejake hodnoty v tabulce
                                  $(this).closest("tr").toggle(true); // radek je ponechan
                                  return false; // pokracovani dalsim radkem
                            }else{
                                  $(this).closest("tr").toggle(false); // schovani radku tabulky, v ktere se nachazi aktualni bunka
                                  return true; // pokracovani dalsi bunkou radku
                            }
                        });
                     });
                  });
                });
        </script>';
        /* Odeslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: deleteEmployee
       Argumenty: request - vybrani zamestnanci
       Ucel: smazani vybranych zamestnancu */
    public function deleteEmployee(Request $request){
        /* Pokud uzivatel vybral nejake zamestnance */
        if($request->zamestnanciDeleteDashboard != NULL){
            /* Iterace skrz vybrane zamestnance a jejich postupne odstranovani z databaze a OLAP sekce systemu */
            foreach ($request->zamestnanciDeleteDashboard as $zamestnanec){
                /* Odstraneni zamestnance z OLAP sekce systemu a databaze */
                OlapETL::deleteRecordFromEmployeeDimension($zamestnanec);
                DB::table('table_employees')->where(['table_employees.employee_id' => $zamestnanec])->delete();
            }
            /* Nastaveni hlasky pro uzivatele */
            if(count($request->zamestnanciDeleteDashboard) == 1){
                session()->flash('success', 'Zaměstnanec byl úspešně smazán!');
            }else{
                session()->flash('success', 'Zaměstnanci byli úspešně smazáni!');
            }
        }else{
            session()->flash('fail', 'Vyberte nějakého zaměstnance k smazání!');
        }
        return redirect()->back();
    }

}
