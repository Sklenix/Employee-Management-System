<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdminCompaniesDatatable extends Controller
{
    public function index(){
        return view('admin_actions.companies_list');
    }

    public function getCompanies(Request $request){
        date_default_timezone_set('Europe/Prague');
        if ($request->ajax()) {
            $firmy = Company::all();
            return Datatables::of($firmy)
                ->addIndexColumn()
                ->addColumn('company_address', function($firmy){
                    if($firmy->company_street == NULL){
                        return 'Ulice nezadána, '.$firmy->company_city;
                    }
                   return $firmy->company_street.', '.$firmy->company_city;
                })
                ->addColumn('action', function($firmy){
                    return '<button type="button" data-id="'.$firmy->company_id.'" data-toggle="modal" data-target="#EditCompanyModal" class="btn btn-primary btn-sm" id="getEditCompanyData"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                            <button type="button" data-id="'.$firmy->company_id.'" data-toggle="modal" data-target="#DeleteCompanyModal" class="btn btn-danger btn-sm" style="margin-top:4px;" id="getCompanyDelete">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
                })
                ->rawColumns(['action', 'company_address'])
                ->make(true);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'company' => ['required', 'string', 'max:255'],
            'first_name' =>  ['required', 'string', 'max:255'],
            'surname' =>  ['required', 'string', 'max:255'],
            'company_email' => ['required','string','email','max:255'],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'company_login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_ico' => ['nullable','digits:8'],
            'company_city' => ['required','string', 'max:255'],
            'company_street' => ['nullable','max:255']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $login = $request->company_login;
        $jmeno =  $request->company;
        $new_company = new Company();
        $new_company->company_name = $request->company;
        $new_company->company_user_name = $request->first_name;
        $new_company->company_user_surname = $request->surname;
        $new_company->email = $request->company_email;
        $new_company->company_phone = $request->phone;
        $new_company->company_login = $request->company_login;
        $new_company->company_url = "";
        $new_company->password = Hash::make($request->password);
        $new_company->company_ico = $request->company_ico;
        $new_company->company_city = $request->company_city;
        $new_company->company_street = $request->company_street;
        $new_company->save();

        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $cele_jmeno = $request->company;
        $email = $request->company_email;
        $soubor = $cele_jmeno . " " . $email;
        /*Service účet pro pripojeni ke Google Drive*/
        $emailAddress = 'tozondoservices@tozondo-drive.iam.gserviceaccount.com';
        /*Cesta k autorizačnímu klíči*/
        $keyFileLocation =storage_path('app/credentials.json');
        /*ID složky, do které chceme soubory nahrávat*/
        $folderId = '1KsP-NAdwBpFaONID4CxTdY4jeKuWJFX4';
        $client = new Google_Client();
        $httpClient = $client->getHttpClient();
        $config = $httpClient->getConfig();
        $config['verify'] = false;
        $client->setHttpClient(new Client($config));
        $client->setApplicationName("BackupDrive");
        try {
            /*Inicializace klienta*/
            $client->setAuthConfig($keyFileLocation);
            $client->useApplicationDefaultCredentials();
            $client->addScope([
                \Google_Service_Drive::DRIVE,
                \Google_Service_Drive::DRIVE_METADATA
            ]);
            $service = new \Google_Service_Drive($client);
            /*Vytvoření složky*/
            $file = new Google_Service_Drive_DriveFile();
            $file->setName($soubor);
            $mimeType = 'application/vnd.google-apps.folder';
            $file->setMimeType($mimeType);
            /*Nasměrování do zvolené složky*/
            $file->setParents(array($folderId));
            /*Odeslání dat*/
            $createdFile = $service->files->create($file, array(
                'mimeType' => $mimeType,
                'uploadType' => "multipart"
            ));

            $role = 'writer';
            $userEmail = $request->company_email;
            $fileId = $createdFile->id;
            Company::where('company_login', $login)->update(array('company_url' => $fileId));
            $userPermission = new Google_Service_Drive_Permission(array(
                'type' => 'user',
                'role' => $role,
                'emailAddress' => $userEmail
            ));

            $request = $service->permissions->create(
                $fileId, $userPermission, array('fields' => 'id')
            );

        } catch (Exception $e) {
            file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
            die();
        }

        return response()->json(['success'=>'Firma '.$jmeno.' byla úspešně vytvořena.']);
    }

    public function edit($id){
        $firma = Company::findOrFail($id);
        $created_at = date('d.m.Y H:i:s', strtotime($firma->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($firma->updated_at));
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($firma->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($firma->company_id);
        $pocetSmenFuture = Shift::getUpcomingCompanyShiftsCount($firma->company_id);
        $html = '';
        if($firma->company_picture === NULL){
            $html = '<center><img src=/images/ikona_profil.png width="250" style="margin-bottom: 25px;" /></center>';
        }else{
            $html = '<center><img src=/storage/company_images/'.$firma->company_picture.' width="250" class="img-thumbnail" style="margin-bottom: 25px;" /></center>';
        }

        $html .= ' <ul class="nav nav-stacked nav-pills d-flex justify-content-center" style="margin-bottom:25px;font-size: 15px;" id="menuTabu">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#zmenaHesla" >Změna hesla</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#statistiky" >Statistiky</a>
                        </li>

                    </ul>
                      <div id="my-tab-content" style="margin-top:20px;" class="tab-content">
                    <div class="tab-pane active" id="obecneUdaje">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Společnost (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-address-book " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_edit" placeholder="Zadejte název společnosti..." type="text" class="form-control" name="company_edit" value="'.$firma->company_name.'"  autocomplete="company_edit" autofocus>
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
                                    <input id="company_city_edit" placeholder="Zadejte město, kde se firma nachází..." type="text" class="form-control" name="company_city_edit" value="'.$firma->company_city.'"  autocomplete="company_city_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_street" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Ulice </label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_street_edit" placeholder="Zadejte ulici, kde se firma nachází (včetně čísla popisného)..." type="text" class="form-control" name="company_street_edit" value="'.$firma->company_street.'"  autocomplete="company_street_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_ico" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> IČO</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_ico_edit" placeholder="Zadejte IČO firmy..." type="text" class="form-control" name="company_ico_edit" value="'.$firma->company_ico.'"  autocomplete="company_ico_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="first_name" class="col-form-label col-md-2 text-center" style="font-size: 13px;"> Jméno zástupce (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="first_name_edit" placeholder="Zadejte křestní jméno zástupce firmy..." type="text" class="form-control" name="first_name_edit" value="'.$firma->company_user_name.'"  autocomplete="first_name_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="surname" class="col-form-label col-md-2 text-center" style="font-size: 12px;">Příjmení zástupce (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="surname_edit" placeholder="Zadejte příjmení zástupce firmy..." type="text" class="form-control" name="surname_edit"  value="'.$firma->company_user_surname.'" autocomplete="surname_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="email" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> E-mail (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_email_edit" placeholder="Zadejte e-mailovou adresu firmy..." type="email" class="form-control" name="company_email_edit" value="'.$firma->email.'"  autocomplete="email_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="phone" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Telefon (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="phone_edit" placeholder="Zadejte telefonní číslo firmy..." type="text" class="form-control" name="phone_edit" value="'.$firma->company_phone.'" autocomplete="phone_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="login" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Login (<span style="color:red;">*</span>)</label>
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
                                    <input id="password_edit" placeholder="Zadejte heslo ..." type="password" class="form-control" name="password_edit"  autocomplete="password_edit">
                                </div>
                                <span toggle="#password_edit" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpassword"></span>
                               <script>
                                    $(".showpassword").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="password-confirm" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Heslo znovu (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="password-confirm_edit" placeholder="Znovu zadejte heslo ..." type="password" class="form-control" name="password_confirmation_edit"  autocomplete="password_confirmation_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                      <div class="tab-pane" id="statistiky">
                        <center>
                             <ul class="list-group col-md-5" style="margin-top:20px;margin-bottom: 15px;">
                                <li class="list-group-item list-group-item-primary text-right" style="color:black;"><span class="pull-left"><strong>Počet zaměstnanců</strong></span> '.$pocetZamestnancu.'</li>
                                <li class="list-group-item list-group-item-primary text-right" style="color:black;"><span class="pull-left"><strong>Počet směn celkově</strong></span> '.$pocetSmen.'</li>
                                <li class="list-group-item list-group-item-primary text-right" style="color:black;"><span class="pull-left"><strong>Počet budoucích směn</strong></span> '.$pocetSmenFuture.'</li>
                             </ul>
                       </center>
                      </div>
                    </div>

                  ';
        return response()->json(['html'=>$html]);
    }

    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        $firma = Company::findOrFail($id);
        if(($firma->email == $request->company_email) && ($firma->company_login == $request->company_login)){
            $validator = Validator::make($request->all(), [
                'company' => ['required', 'string', 'max:255'],
                'first_name' =>  ['required', 'string', 'max:255'],
                'surname' =>  ['required', 'string', 'max:255'],
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'company_ico' => ['nullable','digits:8'],
                'company_city' => ['required','string', 'max:255'],
                'company_street' => ['nullable','max:255']
            ]);
        }else if(($firma->email != $request->company_email) && ($firma->company_login == $request->company_login)){
            $validator = Validator::make($request->all(), [
                'company' => ['required', 'string', 'max:255'],
                'first_name' =>  ['required', 'string', 'max:255'],
                'surname' =>  ['required', 'string', 'max:255'],
                'company_email' => ['required','unique:table_companies,email','string','email','max:255'],
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'company_ico' => ['nullable','digits:8'],
                'company_city' => ['required','string', 'max:255'],
                'company_street' => ['nullable','max:255']
            ]);
        }else if(($firma->email == $request->company_email) && ($firma->company_login != $request->company_login)){
            $validator = Validator::make($request->all(), [
                'company' => ['required', 'string', 'max:255'],
                'first_name' =>  ['required', 'string', 'max:255'],
                'surname' =>  ['required', 'string', 'max:255'],
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'company_login' => ['required', 'unique:table_companies,company_login', 'string', 'max:255'],
                'company_ico' => ['nullable','digits:8'],
                'company_city' => ['required','string', 'max:255'],
                'company_street' => ['nullable','max:255']
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'company' => ['required', 'string', 'max:255'],
                'first_name' =>  ['required', 'string', 'max:255'],
                'surname' =>  ['required', 'string', 'max:255'],
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'company_email' => ['required','unique:table_companies,email','string','email','max:255'],
                'company_login' => ['required', 'unique:table_companies,company_login', 'string', 'max:255'],
                'company_ico' => ['nullable','digits:8'],
                'company_city' => ['required','string', 'max:255'],
                'company_street' => ['nullable','max:255']
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if($firma->company_url != NULL){
            /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
            $cele_jmeno = $request->company;
            $email = $request->company_email;
            $soubor = $cele_jmeno . " " . $email;
            /*Cesta k autorizačnímu klíči*/
            $keyFileLocation =storage_path('app/credentials.json');
            $client = new Google_Client();
            $httpClient = $client->getHttpClient();
            $config = $httpClient->getConfig();
            $config['verify'] = false;
            $client->setHttpClient(new Client($config));
            $client->setApplicationName("BackupDrive");
            try {
                /*Inicializace klienta*/
                $client->setAuthConfig($keyFileLocation);
                $client->useApplicationDefaultCredentials();
                $client->addScope([
                    \Google_Service_Drive::DRIVE,
                    \Google_Service_Drive::DRIVE_METADATA
                ]);
                $service = new \Google_Service_Drive($client);
                $results = $service->files->get($firma->company_url);
                if($results != NULL){
                    $zmena_jmena = new Google_Service_Drive_DriveFile();
                    $zmena_jmena->setName($soubor);
                    $service->files->update($firma->company_url, $zmena_jmena, array(
                        'mimeType' => 'text/csv',
                        'uploadType' => 'multipart'
                    ));
                }
            } catch (Exception $e) {
                print "Nastala chyba: " . $e->getMessage();
            }
        }

        $bool = 0;
        $bool2 = 0;

        if(($firma->company_name == $request->company) && ($firma->company_user_name == $request->first_name)
            && ($firma->company_user_surname == $request->surname) && ($firma->company_phone == $request->phone) && ($firma->email == $request->company_email)
            && ($firma->company_login == $request->company_login) && ($firma->company_ico == $request->company_ico)
            && ($firma->company_city == $request->company_city) && ($firma->company_street == $request->company_street)){
            $bool = 0;
        }else{
            $bool = 1;
        }

        $firma->company_name = $request->company;
        $firma->company_user_name = $request->first_name;
        $firma->company_user_surname = $request->surname;
        $firma->company_phone = $request->phone;
        $firma->email = $request->company_email;
        $firma->company_login = $request->company_login;
        $firma->company_ico = $request->company_ico;
        $firma->company_city = $request->company_city;
        $firma->company_street = $request->company_street;

        if(isset($request->password)){
            $firma->password = Hash::make($request->password);
            $validator = Validator::make($request->all(), [
                'password' => ['string', 'min:8','required_with:password_confirmation','same:password_confirmation'],
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
            if($request->password != ""){
                $bool2 = 1;
            }

        }

        $firma->save();

        if($bool == 0 && $bool2 == 0){
            return response()->json(['success'=>'0']);
        }
        else if($bool == 1 && $bool2 == 0){
            return response()->json(['success'=>'Údaje firmy '.$request->company.' byly úspěšně změněny.']);
        }
        else if($bool == 0 && $bool2 == 1){
            return response()->json(['success'=>'Heslo firmy '.$request->company.' bylo úspěšně změněno.']);
        }
        else if($bool == 1 && $bool2 == 1){
            return response()->json(['success'=>'Údaje firmy a heslo firmy '.$request->company.' byly úspěšně změněny.']);
        }
        return response()->json(['success' => 'Profil firmy '.$firma->company_name.' byl úspěšně zaktualizován.']);
    }

    public function destroy($id){
        $company = Company::findOrFail($id);
        $jmeno = $company->company_name;
        Company::findOrFail($id)->delete();
        return response()->json(['success' => 'Smazání firmy '.$jmeno.' proběhlo úspěšně']);
    }
}
