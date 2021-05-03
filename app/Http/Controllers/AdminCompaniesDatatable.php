<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Shift;
use App\Notifications\VerifyEmailNotification;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdminCompaniesDatatable extends Controller {
     /* Nazev souboru:  AdminCompaniesDatatable.php */
     /* Autor: Pavel Sklenář (xsklen12) */
     /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy firem v uctu s roli admina. Slouzi take k ovladani datove tabulky.
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

        Pro nauceni prace s datovymi tabulkami yajra slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
        Knihovna Yajra pro datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
        Licence k Yajra datovym tabulkam:
        (The MIT License)
        Copyright (c) 2013-2020 Arjay Angeles aqangeles@gmail.com
        Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction,
        including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
        subject to the following conditions:

        The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

        THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
            IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
             WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
     */

    /* Nazev funkce: index
      Argumenty: zadne
      Ucel: Zobrazeni prislusneho pohledu pro seznam firem */
    public function index(){
        return view('admin_actions.companies_list');
    }

    /* Nazev funkce: getCompanies
      Argumenty: zadne
      Ucel: Vyrenderovani datove tabulky, ktera bude obsahovat jednotlive udaje firem */
    public function getCompanies(){
        date_default_timezone_set('Europe/Prague');

        /* Ziskani udaju vsech firem */
        $firmy = Company::all();

        /* Usek kodu zabyvajici se samotnym renderovanim tabulky */
        return Datatables::of($firmy)
            ->addIndexColumn()
            ->editColumn('company_picture', function($firmy){
                if($firmy->company_picture == NULL){
                    /* Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4#  */
                    return "<img src=/images/ikona_profil.png alt='Profilový obrázek' width='60'/>";
                }
                return "<img src=/storage/company_images/".$firmy->company_picture." width='60' height='50' style='height:auto;'/>";
            })
            ->addColumn('company_address', function($firmy){
                if($firmy->company_street == NULL){
                    return 'Ulice nezadána, '.$firmy->company_city;
                }
               return $firmy->company_street.', '.$firmy->company_city;
            })
            ->addColumn('action', function($firmy){ // sekce pro vyrenderovani ovladacich tlacitek datove tabulky
                return '<button type="button" data-id="'.$firmy->company_id.'" data-toggle="modal" data-target="#CompanyEditForm" class="btn btn-primary btn-sm tlacitkoZobrazit" id="obtainCompanyDataEdit"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                        <button type="button" data-id="'.$firmy->company_id.'" data-toggle="modal" data-target="#CompanyDeleteForm" class="btn btn-danger btn-sm tlacitkoSmazat" id="obtainDeleteIdCompany">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
            })
            ->rawColumns(['action', 'company_address', 'company_picture']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: store
        Argumenty: request - udaje nove firmy zadane adminem
        Ucel: Ulozeni udaju firmy do databaze */
    public function store(Request $request){
        /* Definice pravidel pro validaci a jeji provedeni */
        $validator = Validator::make($request->all(), ['nazev_firmy' => ['required', 'string', 'max:255'], 'krestni_jmeno' =>  ['required', 'string', 'max:255'], 'prijmeni' =>  ['required', 'string', 'max:255'], 'emailova_adresa' => ['required','string','email','unique:table_companies,email','max:255'], 'telefon' => 'required|regex:/^[\+]?([0-9\s\-]*)$/|min:9|max:16', 'prihlasovaci_jmeno' => ['required', 'string','unique:table_companies,company_login', 'max:255'], 'heslo' => ['min:8','required_with:potvrzeni_hesla','same:potvrzeni_hesla'], 'ico' => ['nullable','digits:8'], 'mesto_sidla' => ['required','string', 'max:255'], 'ulice_sidla' => ['nullable','max:255']]);

        /* Pokud je libovolný údaj inkorektní, je uložení firmy zrušeno a uživateli je zobrazena odpovidajici chyba ci chyby */
        if ($validator->fails()) {
            return response()->json(['fail' => $validator->errors()->all()]);
        }

        /* Ulozeni noveho uctu firmy do databaze*/
        $new_company = Company::create(['company_name' => $request->nazev_firmy, 'company_user_name' => $request->krestni_jmeno, 'company_user_surname' => $request->prijmeni, 'email' => $request->emailova_adresa, 'company_phone' => $request->telefon, 'company_login' => $request->prihlasovaci_jmeno, 'company_url' => "", 'password' => Hash::make($request->heslo), 'company_ico' => $request->ico, 'company_city' => $request->mesto_sidla, 'company_street' => $request->ulice_sidla]);

        $fileId = "";

        /* Pokud admin zvolil moznost vytvorit firme Google Drive */
        if($request->googleDriveRequest == "true"){
            /* Usek kodu zabyvajici se nazvem slozky v Google Drive firmy (v tomto systému nazev firmy spolecne s emailem) */
            $soubor = $request->nazev_firmy . " " . $request->emailova_adresa;
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
            $folder->setParents(array($destinationID));

            /* Odeslani dat */
            $createdFile = $googleServ->files->create($folder, ['mimeType' => 'application/vnd.google-apps.folder', 'uploadType' => "multipart"]);

            /* Ulozeni identifikatoru nove vytvorene slozky */
            $fileId = $createdFile->id;
            Company::where('company_login', $request->prihlasovaci_jmeno)->update(['company_url' => $fileId]);
            /* Usek kodu slouzici ke nasdileni Google Drive slozky s firmou (pomoci jeji zadane emailove adresy) */
            $userPermission = new Google_Service_Drive_Permission([ //nadefinovani sdileni
                'type' => 'user',
                'role' => 'writer',
                'emailAddress' => $request->emailova_adresa
            ]);

            /* Aplikace sdileni */
            $googleServ->permissions->create($createdFile->id, $userPermission,['emailMessage' => "Dobrý den, registrace do informačního systému Tozondo proběhla úspěšně. Tento email slouží k nasdílení Vaší Google Drive složky v informačním systému Tozondo s Vaší emailovou adresou. Nyní by jste měl mít přístup k Google Drive složce přes svůj Google Drive účet."]);
        }

        /* Poslani verifikacniho emailu firme */
        $new_company->notify(new VerifyEmailNotification());
        /* Odeslani odpovedi */
        return response()->json(['success'=>'Firma '.$request->nazev_firmy.' byla úspešně vytvořena.']);
    }

    /* Nazev funkce: edit
    Argumenty: id - jednoznacny identifikator firmy
    Ucel: Zobrazeni formulare pro naslednou editaci udaju konkretni firmy */
    public function edit($id){
        /* Ziskani firmy podle ID, nasledna extrakce datumu vytvoreni a datumu posledni aktualizace uctu firmy */
        $firma = Company::find($id);
        $created_at = date('d.m.Y H:i:s', strtotime($firma->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($firma->updated_at));
        /* Sekce statistik */
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($firma->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($firma->company_id);
        $pocetSmenFuture = Shift::getUpcomingCompanyShiftsCount($firma->company_id);

        /* Tato promenna slouzi k ulozeni HTML, ktere se pote posle klientovi do prohlizece (Front-end)*/
        $out = '';

        /* Sekce pro zobrazeni profiloveho obrazku firmy */
        if($firma->company_picture === NULL){
            /* Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4# */
            $out = '<center><img src=/images/default_profile.png width="300" alt="Profilová fotka" style="margin-bottom: 25px;" /></center>';
        }else{
            $out = '<center><img src=/storage/company_images/'.$firma->company_picture.' width="300" alt="Profilová fotka" class="img-thumbnail" style="margin-bottom: 25px;" /></center>';
        }

        /* Nadefinovani obsahu modalniho okna ve forme formulare */
        $out .= ' <ul class="nav nav-stacked nav-pills d-flex justify-content-center" style="margin-bottom:25px;font-size: 15px;">
                      <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a></li>
                      <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#zmenaHesla">Změna hesla</a></li>
                      <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#statistiky">Statistiky</a></li>
                  </ul>
                  <div style="margin-top:20px;" class="tab-content">
                         <div class="tab-pane active" id="obecneUdaje">
                            <div class="form-group">
                                <div class="row">
                                    <label for="company_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Společnost (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-address-book " aria-hidden="true"></i></div>
                                            </div>
                                            <input id="company_edit" placeholder="Zadejte název společnosti..." type="text" class="form-control" name="company_edit" value="'.$firma->company_name.'" autocomplete="on" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="company_city_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Město (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                            </div>
                                            <input id="company_city_edit" placeholder="Zadejte město, kde se firma nachází..." type="text" class="form-control" name="company_city_edit" value="'.$firma->company_city.'" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="company_street_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Ulice </label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                            </div>
                                            <input id="company_street_edit" placeholder="Zadejte ulici, kde se firma nachází (včetně čísla popisného)..." type="text" class="form-control" name="company_street_edit" value="'.$firma->company_street.'" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="company_ico_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> IČO </label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                                            </div>
                                            <input id="company_ico_edit" placeholder="Zadejte IČO firmy..." type="text" class="form-control" name="company_ico_edit" value="'.$firma->company_ico.'" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="first_name_edit" class="col-form-label col-md-2 text-center" style="font-size: 13px;"> Jméno zástupce (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                            </div>
                                            <input id="first_name_edit" placeholder="Zadejte křestní jméno zástupce firmy..." type="text" class="form-control" name="first_name_edit" value="'.$firma->company_user_name.'" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="surname_edit" class="col-form-label col-md-2 text-center" style="font-size: 12px;">Příjmení zástupce (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                            </div>
                                            <input id="surname_edit" placeholder="Zadejte příjmení zástupce firmy..." type="text" class="form-control" name="surname_edit"  value="'.$firma->company_user_surname.'" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="company_email_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> E-mail (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                            </div>
                                            <input id="company_email_edit" placeholder="Zadejte e-mailovou adresu firmy..." type="email" class="form-control" name="company_email_edit" value="'.$firma->email.'" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="phone_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Telefon (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                            </div>
                                            <input id="phone_edit" placeholder="Zadejte telefonní číslo firmy [Preferovaný formát je +420 XXX XXX XXX]..." type="text" class="form-control" name="phone_edit" value="'.$firma->company_phone.'" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="company_login_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Login (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                            </div>
                                            <input id="company_login_edit" placeholder="Zadejte uživatelské jméno k systému..." type="text" value="'.$firma->company_login.'" class="form-control" name="company_login_edit"  autocomplete="company_login_edit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                              <p class="d-flex justify-content-center">Účet vytvořen '.$created_at.', naposledy aktualizován '.$updated_at.'.</p>
                         </div>
                         <div class="tab-pane" id="zmenaHesla">
                             <div class="form-group">
                                <div class="row">
                                    <label for="password" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Heslo (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                            </div>
                                            <input id="password_edit" placeholder="Zadejte heslo ..." type="password" class="form-control" name="password_edit" autocomplete="on">
                                        </div>
                                        <span toggle="#password_edit" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHesloFirmaEdit"></span>
                                       <script>
                                            /* Skryti/odkryti hesla */
                                            $(".zobrazHesloFirmaEdit").click(function() {
                                                $(this).toggleClass("fa-eye fa-eye-slash");
                                                var input = $($(this).attr("toggle"));
                                                if (input.attr("type") == "password") {
                                                    input.attr("type", "text");
                                                } else { input.attr("type", "password");}});
                                            /* Tento jquery kod spolecne s elementem span (ktery byl upraven) byl prevzat dle licencnich podminek, viz nize.
                                            Copyright (c) 2021 - Sohail Aj. - www.codepen.io/Sohail05/pen/yOpeBm

                                            Permission is hereby granted, free of charge, to any person
                                            obtaining a copy of this software and associated documentation
                                            files (the "Software"), to deal in the Software without restriction,
                                             including without limitation the rights to use, copy, modify,
                                            merge, publish, distribute, sublicense, and/or sell copies of
                                            the Software, and to permit persons to whom the Software is
                                            furnished to do so, subject to the following conditions:

                                            The above copyright notice and this permission notice shall
                                            be included in all copies or substantial portions of the Software.

                                            THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
                                            EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
                                            OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
                                            NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
                                            HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
                                            WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
                                            OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
                                            DEALINGS IN THE SOFTWARE.
                                            */
                                        </script>
                                    </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                    <label for="password_confirmation_edit" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Heslo znovu (<span style="color:red;">*</span>)</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                            </div>
                                            <input id="password_confirmation_edit" placeholder="Znovu zadejte heslo ..." type="password" class="form-control" name="password_confirmation_edit"  autocomplete="on">
                                        </div>
                                            <span toggle="#password_confirmation_edit" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHesloOvereniFirmaEdit"></span>
                                       <script>
                                            /* Skryti/odkryti hesla */
                                            $(".zobrazHesloOvereniFirmaEdit").click(function() {
                                                $(this).toggleClass("fa-eye fa-eye-slash");
                                                var input = $($(this).attr("toggle"));
                                                if (input.attr("type") == "password") {
                                                    input.attr("type", "text");
                                                } else { input.attr("type", "password");}});
                                            /* Tento jquery kod spolecne s elementem span (ktery byl upraven) byl prevzat dle licencnich podminek, viz nize.
                                            Copyright (c) 2021 - Sohail Aj. - www.codepen.io/Sohail05/pen/yOpeBm

                                            Permission is hereby granted, free of charge, to any person
                                            obtaining a copy of this software and associated documentation
                                            files (the "Software"), to deal in the Software without restriction,
                                             including without limitation the rights to use, copy, modify,
                                            merge, publish, distribute, sublicense, and/or sell copies of
                                            the Software, and to permit persons to whom the Software is
                                            furnished to do so, subject to the following conditions:

                                            The above copyright notice and this permission notice shall
                                            be included in all copies or substantial portions of the Software.

                                            THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
                                            EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
                                            OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
                                            NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
                                            HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
                                            WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
                                            OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
                                            DEALINGS IN THE SOFTWARE.
                                            */
                                        </script>
                                    </div>
                                </div>
                              </div>
                         </div>
                         <div class="tab-pane" id="statistiky">
                             <center><ul class="list-group col-md-5" style="margin-top:20px;margin-bottom: 15px;">
                                <li class="list-group-item list-group-item-primary text-right" style="color:black;"><span class="pull-left"><strong>Počet zaměstnanců</strong></span> '.$pocetZamestnancu.'</li>
                                <li class="list-group-item list-group-item-primary text-right" style="color:black;"><span class="pull-left"><strong>Počet směn celkově</strong></span> '.$pocetSmen.'</li>
                                <li class="list-group-item list-group-item-primary text-right" style="color:black;"><span class="pull-left"><strong>Počet budoucích směn</strong></span> '.$pocetSmenFuture.'</li>
                             </ul></center>
                        </div>
                 </div>';
        /* Zaslani formulare do modalniho okna */
        return response()->json(['out'=> $out]);
    }

    /* Nazev funkce: update
        Argumenty: request - udaje zadane adminem, id - jednoznacny identifikator firmy
        Ucel: Provedeni aktualizace udaju firmy. */
    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        /* Ziskani firmy */
        $firma = Company::find($id);
        $id_firmy = $firma->company_id;
        /* Nadefinovani pravidel pro validaci */
        if(($firma->email == $request->emailova_adresa) && ($firma->company_login == $request->prihlasovaci_jmeno)){
            $validator = Validator::make($request->all(), [
                'nazev_firmy' => ['required', 'string', 'max:255'],
                'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'telefon' => 'required|regex:/^[\+]?([0-9\s\-]*)$/|min:9|max:16',
                'ico' => ['nullable','digits:8'],
                'mesto_sidla' => ['required','string', 'max:255'],
                'ulice_sidla' => ['nullable','max:255']
            ]);
        }else if(($firma->email != $request->emailova_adresa) && ($firma->company_login == $request->prihlasovaci_jmeno)){
            $validator = Validator::make($request->all(), [
                'nazev_firmy' => ['required', 'string', 'max:255'],
                'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'emailova_adresa' => ['required','unique:table_companies,email','string','email','max:255'],
                'telefon' => 'required|regex:/^[\+]?([0-9\s\-]*)$/|min:9|max:16',
                'ico' => ['nullable','digits:8'],
                'mesto_sidla' => ['required','string', 'max:255'],
                'ulice_sidla' => ['nullable','max:255']
            ]);
        }else if(($firma->email == $request->emailova_adresa) && ($firma->company_login != $request->prihlasovaci_jmeno)){
            $validator = Validator::make($request->all(), [
                'nazev_firmy' => ['required', 'string', 'max:255'],
                'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'telefon' => 'required|regex:/^[\+]?([0-9\s\-]*)$/|min:9|max:16',
                'prihlasovaci_jmeno' => ['required', 'unique:table_companies,company_login', 'string', 'max:255'],
                'ico' => ['nullable','digits:8'],
                'mesto_sidla' => ['required','string', 'max:255'],
                'ulice_sidla' => ['nullable','max:255']
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'nazev_firmy' => ['required', 'string', 'max:255'],
                'krestni_jmeno' =>  ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'telefon' => 'required|regex:/^[\+]?([0-9\s\-]*)$/|min:9|max:16',
                'emailova_adresa' => ['required','unique:table_companies,email','string','email','max:255'],
                'prihlasovaci_jmeno' => ['required', 'unique:table_companies,company_login', 'string', 'max:255'],
                'ico' => ['nullable','digits:8'],
                'mesto_sidla' => ['required','string', 'max:255'],
                'ulice_sidla' => ['nullable','max:255']
            ]);
        }

        /* Pokud je libovolný údaj inkorektní, je uložení firmy zrušeno a uživateli je zobrazena odpovidajici chyba ci chyby */
        if ($validator->fails()) {
            return response()->json(['fail' => $validator->errors()->all()]);
        }

        /* Pokud ma firma Google Drive a zmenila si jmeno nebo email, tak je email, pripadne jmeno synchronizovano s nazvem Google Drive slozky firmy */
        if($firma->company_url != ""){
            if($firma->company_name == $request->nazev_firmy && $firma->email == $request->emailova_adresa){ // pokud je nazev firmy i email firmy stejny, tak neni spustena aktualizace nazvu Google Drive slozky firmy
            }else{
                /* Usek kodu zabyvajici se nazvem slozky v Google Drive firmy (v tomto systému nazev firmy spolecne s emailem) */
                $soubor = $request->nazev_firmy . " " . $request->emailova_adresa;
                /*Cesta k autorizačnímu klíči*/
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
                $GoogleServ = new Google_Service_Drive($GoogleClient);
                /* Zjisteni, zdali slozka vubec existuje */
                $existujeSlozka = $GoogleServ->files->get($firma->company_url);
                /* Realizace zmeny nazvu slozky */
                if($existujeSlozka != NULL){
                    $novy_nazev = new Google_Service_Drive_DriveFile();
                    $novy_nazev->setName($soubor);
                    $GoogleServ->files->update($firma->company_url, $novy_nazev, ['uploadType' => 'multipart']);
                }
            }
        }

        /* Indikacni promenne slouzici ke zjisteni zdali doslo ke zmene udaju (jeZmenaUdaju), ci zmene hesla (jeZmenaHesla) */
        $jeZmenaUdaju = 0;
        $jeZmenaHesla = 0;

        /* Pokud jsou udaje stejne, indikator zustava nastaveny na nule */
        if(($firma->company_name == $request->nazev_firmy) && ($firma->company_user_name == $request->krestni_jmeno)
            && ($firma->company_user_surname == $request->prijmeni) && ($firma->company_phone == $request->telefon) && ($firma->email == $request->emailova_adresa)
            && ($firma->company_login == $request->prihlasovaci_jmeno) && ($firma->company_ico == $request->ico)
            && ($firma->company_city == $request->mesto_sidla) && ($firma->company_street == $request->ulice_sidla)){
            $jeZmenaUdaju = 0;
        }else{ // pokud se nejaky udaj zmeni, tak je indikator nastaven na 1
            $jeZmenaUdaju = 1;
        }

        /* Provedeni aktualizace v databazi */
        Company::where(['company_id' => $firma->company_id])->update(['company_name' => $request->nazev_firmy, 'company_user_name' => $request->krestni_jmeno, 'company_user_surname' => $request->prijmeni, 'company_phone' => $request->telefon, 'email' => $request->emailova_adresa, 'company_login' => $request->prihlasovaci_jmeno, 'company_ico' => $request->ico, 'company_city' => $request->mesto_sidla, 'company_street' => $request->ulice_sidla]);

        /* Zjisteni, zdali uzivatel zmenil heslo a pripadna aktualizace hesla v databazi*/
        if(isset($request->heslo)){
            /* Definice pravidel pro validaci udaju a jeji provedeni */
            $validator = Validator::make($request->all(), ['password' => ['string', 'min:8','required_with:potvrzeni_hesla','same:potvrzeni_hesla']]);
            /* Pokud selze validace (inkorektni udaje) */
            if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]);}

            /* Zjisteni, zdali uzivatel opravdu chce zmenit heslo */
            if($request->heslo != ""){
                /* Provedeni aktualizace hesla v databazi*/
                Company::where(['company_id' => $firma->company_id])->update(['password' => Hash::make($request->heslo)]);
                $jeZmenaHesla = 1;
            }
        }

        /* Aktualizace udaju firmy v analyticke sekci systemu OLAP */
        OlapETL::updateCompanyDimension($id_firmy, $request->nazev_firmy, $request->mesto_sidla, $request->ulice_sidla, $request->krestni_jmeno, $request->prijmeni);

        /* Na zaklade zmenenych udaju je realizovano poslani oznameni uzivatelovi, ze se ulozeni zmen zdarilo */
        if($jeZmenaUdaju == 0 && $jeZmenaHesla == 0){
            return response()->json(['success'=>'0']);
        }else if($jeZmenaUdaju == 1 && $jeZmenaHesla == 0){
            return response()->json(['success'=>'Údaje firmy '.$request->nazev_firmy.' byly úspěšně změněny.']);
        }else if($jeZmenaUdaju == 0 && $jeZmenaHesla == 1){
            return response()->json(['success'=>'Heslo firmy '.$request->nazev_firmy.' bylo úspěšně změněno.']);
        }else if($jeZmenaUdaju == 1 && $jeZmenaHesla == 1){
            return response()->json(['success'=>'Údaje firmy a heslo firmy '.$request->nazev_firmy.' byly úspěšně změněny.']);
        }
    }

    /* Nazev funkce: destroy
        Argumenty: id - jednoznacny identifikator firmy
        Ucel: Smazani konkretni firmy na zaklade jejiho identifikatoru. */
    public function destroy($id){
        /* Ziskani konkretni firmy na zaklade jejiho identifikatoru */
        $company = Company::find($id);
        $nazev_firmy = $company->company_name;
        /* Pokud firma nema Google Drive složku, tak je cast smazani Google Drive slozky preskocena */
        if($company->company_url != ""){
            /*Cesta k autorizačnímu klíči*/
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
            $GoogleServ = new Google_Service_Drive($GoogleClient);
            /* Zjisteni, zdali slozka opravdu existuje */
            $existujeSlozka = $GoogleServ->files->get($company->company_url);
            if ($existujeSlozka != NULL) { // pokud existuje, tak je smazana
                $GoogleServ->files->delete($company->company_url);
            }
        }
        /* Smazani firmy z analyticke sekce systemu OLAP */
        OlapETL::deleteRecordFromCompanyDimension($company->company_id);
        /* Aplikace smazani firmy z databaze (OLTP)*/
        Company::find($id)->delete();
        /* Odeslani odpovedi */
        return response()->json(['success' => 'Smazání firmy '.$nazev_firmy.' proběhlo úspěšně']);
    }
}
