<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Google_Service_Drive_Permission;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;


class RegisterController extends Controller{
    /* Autor uprav: Pavel Sklenář (xsklen12) */
    /* Nazev souboru: RegisterController.php */
    /* Tato trida slouzi k registraci novych uctu s roli firmy a je soucasti autentizacniho
       a autorizacniho balicku frameworku Laravel a byla znacne upravena pro ucely tohoto informacniho systemu.
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

    use RegistersUsers;

    /* Cesta, kam jsou uzivatele presmerovani po cerstve registraci. Jedna se o branu do informacniho systemu. */
    protected $redirectTo = "/email/verify";

    /* Nazev funkce: validator
       Argumenty: data - uzivatelovi zadane udaje
       Ucel: Validace udaju zadanymi uzivatelem pri jeho registraci */
    protected function validator(array $data){
        /* Nadefinovani validacnich pravidel */
        $pravidla = [
            'nazev_firmy' => ['required', 'string', 'max:255'],
            'krestni_jmeno' =>  ['required', 'string', 'max:255'],
            'prijmeni' =>  ['required', 'string', 'max:255'],
            'emailova_adresa' => ['required','unique:table_companies,email','string','email','max:255'],
            'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
            'prihlasovaci_jmeno' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
            'heslo' => ['min:8','required_with:potvrzeni_hesla','same:potvrzeni_hesla'],
            'ico' => ['nullable','digits:8'],
            'mesto_sidla' => ['required','string', 'max:255'],
            'ulice_sidla' => ['nullable','max:255']
        ];
        /* Nadefinovani vlastnich hlasek */
        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'regex' => 'Formát :attribute není validní.',
            'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.',
            'unique' => 'Váš e-mail, nebo Váš login už v databázi evidujeme.',
            'digits' => 'Číslo musí mít 8 cifer'
        ];
        /* usek kodu pro realizaci samotne validace */
        Validator::validate($data, $pravidla, $vlastniHlasky);
        return Validator::make($data, [
            'nazev_firmy' => ['required', 'string', 'max:255'],
            'krestni_jmeno' =>  ['required', 'string', 'max:255'],
            'prijmeni' =>  ['required', 'string', 'max:255'],
            'emailova_adresa' => ['required','unique:table_companies,email','string','email','max:255'],
            'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
            'prihlasovaci_jmeno' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
            'heslo' => ['min:8','required_with:potvrzeni_hesla','same:potvrzeni_hesla'],
            'ico' => ['nullable','digits:8'],
            'mesto_sidla' => ['required','string', 'max:255'],
            'ulice_sidla' => ['nullable','max:255']
        ]);
    }

    /* Nazev funkce: create
       Argumenty: data - uzivatelovi zadane udaje
       Ucel: Vytvoreni uctu uzivatele v databazi a pripadne vytvoreni Google Drive slozky spolecne s nasdilenim one slozky
    */
    protected function create(array $data){
        $fileId = "";
        if(isset($data['googleDriveRequest'])){
            /* Usek kodu zabyvajici se nazvem slozky v Google Drive firmy (v tomto systému nazev firmy spolecne s emailem) */
            $soubor = $data['nazev_firmy'] . " " . $data['emailova_adresa'];
            /*Cesta k autorizacnimu klici*/
            $authKey = storage_path('app/credentials.json');
            /* ID slozky, do ktere chceme soubory nahravat */
            $destinationID = '1KsP-NAdwBpFaONID4CxTdY4jeKuWJFX4';
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

            /*Vytvoření slozky */
            $folder = new Google_Service_Drive_DriveFile();
            /* Nastaveni jmena slozky */
            $folder->setName($soubor);
            /* Nastaveni typu mime */
            $folder->setMimeType('application/vnd.google-apps.folder');
            /*Nasmerovani do zvolene slozky*/
            $folder->setParents([$destinationID]);

            /* Odeslani dat */
            $createdFile = $googleServ->files->create($folder, ['mimeType' => 'application/vnd.google-apps.folder', 'uploadType' => "multipart"]);

            /* Ulozeni identifikatoru nove vytvorene slozky */
            $fileId = $createdFile->id;

            /* Usek kodu slouzici ke nasdileni Google Drive slozky s firmou (pomoci jeji zadane emailove adresy) */
            $userPermission = new Google_Service_Drive_Permission([ //nadefinovani sdileni
                'type' => 'user',
                'role' => 'writer',
                'emailAddress' => $data['emailova_adresa']
            ]);

            /* Aplikace sdileni */
            $googleServ->permissions->create($createdFile->id, $userPermission,['emailMessage' => "Dobrý den, registrace do informačního systému Tozondo proběhla úspěšně. Tento email slouží k nasdílení Vaší Google Drive složky v informačním systému Tozondo s Vaší emailovou adresou. Nyní by jste měl mít přístup k Google Drive složce přes svůj Google Drive účet."]);
        }

        /* Presmerovani na stranku, ktera slouzi jako brana do informacniho systemu, ona stranka se bude pri prihlaseni zobrazovat dokud si firma neoveri emailovou adresu */
        redirect($this->redirectPath())->with('successRegister', 'Registrace proběhla úspěšně, byl Vám zaslán e-mail pro ověření e-mailové adresy.');

        /* Zapis udaju uzivatele do databaze */
        return Company::create([
            'company_name' => $data['nazev_firmy'],
            'company_user_name' => $data['krestni_jmeno'],
            'company_user_surname' => $data['prijmeni'],
            'email' => $data['emailova_adresa'],
            'company_phone' => $data['telefon'],
            'company_login' => $data['prihlasovaci_jmeno'],
            'company_url' => $fileId,
            'password' => Hash::make($data['heslo']),
            'company_ico' => $data['ico'],
            'company_city' => $data['mesto_sidla'],
            'company_street' => $data['ulice_sidla']
        ]);
    }

}
