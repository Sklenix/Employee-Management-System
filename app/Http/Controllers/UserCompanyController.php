<?php

namespace App\Http\Controllers;
use App\Models\Shift;
use DateTime;
use http\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Languages;
use App\Models\Employee;
use App\Providers\RouteServiceProvider;
use Google_Service_Drive_Permission;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use Google_Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Illuminate\Validation\ValidationException;

class UserCompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->get();

        return view('homes.company_home')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance);
    }

    public function showVerifySuccess()
    {
        return view('email_verified_login', [
        ]);

    }


    protected function validator(array $data,$emailDuplicate,$loginDuplicate,$verze){
        if($verze == 1){
            if($emailDuplicate == 1 && $loginDuplicate == 0){
                if($data['company_ico'] == NULL){
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'company_ico' => ['digits:8'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }

            }else if($loginDuplicate == 1 && $emailDuplicate == 0){
                if($data['company_ico'] == NULL){
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required', 'string', 'max:255'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required', 'string', 'max:255'],
                        'company_ico' => ['digits:8'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }

            }else if($loginDuplicate == 1 && $emailDuplicate == 1){
                if($data['company_ico'] == NULL){
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required', 'string', 'max:255'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required', 'string', 'max:255'],
                        'company_ico' => ['digits:8'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }
            }else if($loginDuplicate == 0 && $emailDuplicate == 0){
                if($data['company_ico'] == NULL){
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }else{
                    $pravidla = [
                        'company_name' => ['required', 'string', 'max:255'],
                        'company_firstname' =>  ['required', 'string', 'max:255'],
                        'company_surname' =>  ['required', 'string', 'max:255'],
                        'company_email' => ['required','unique:table_companies,email','string','email','max:255'],
                        'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'company_login' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
                        'company_ico' => ['digits:8'],
                        'company_city' => ['string', 'max:255'],
                        'company_street' => ['max:255']
                    ];
                }
            }
            if($data['company_ico'] == NULL){
                Validator::make($data, [
                    'company_name' => ['required', 'string', 'max:255'],
                    'company_firstname' =>  ['required', 'string', 'max:255'],
                    'company_surname' =>  ['required', 'string', 'max:255'],
                    'company_email' => ['required','string','email','max:255'],
                    'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                    'company_login' => ['required', 'string', 'max:255'],
                    'company_city' => ['string', 'max:255'],
                    'company_street' => ['max:255']
                ]);
            }else{
                Validator::make($data, [
                    'company_name' => ['required', 'string', 'max:255'],
                    'company_firstname' =>  ['required', 'string', 'max:255'],
                    'company_surname' =>  ['required', 'string', 'max:255'],
                    'company_email' => ['required','string','email','max:255'],
                    'company_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                    'company_login' => ['required', 'string', 'max:255'],
                    'company_ico' => ['digits:8'],
                    'company_city' => ['string', 'max:255'],
                    'company_street' => ['max:255']
                ]);
            }

        }else if($verze == 2){
            $pravidla = [
                'employee_name' => ['required', 'string', 'max:255'],
                'employee_surname' =>  ['required', 'string', 'max:255'],
                'employee_position' =>  ['required', 'string', 'max:255'],
                'employee_email' => ['required','unique:table_employees,email','string','email','max:255'],
                'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'employee_login' => ['required','unique:table_employees,employee_login', 'string', 'max:255'],
                'employee_note' => ['nullable','max:500'],
                'employee_city' => ['required','string', 'max:255'],
                'employee_street' => ['nullable','max:255'],
                'employee_password' => ['required', 'string', 'min:8','required_with:employee_password_confirm','same:employee_password_confirm'],
                'employee_picture' => ['mimes:jpeg,jpg,png,gif','max:20000']
            ];
        }

    $vlastniHlasky = [
        'required' => 'Položka :attribute je povinná.',
        'email' => 'U položky :attribute nebyl dodržen formát emailu.',
        'regex' => 'Formát :attribute není validní.',
        'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.',
        'unique' => 'Váš e-mail, nebo Váš login už v databázi evidujeme.',
        'digits' => 'Číslo musí mít 8 cifer'
    ];
    Validator::validate($data, $pravidla, $vlastniHlasky);
    }

    public function updateProfileData(Request $request){
        $user = Auth::user();
        $emailDuplicate = 0;
        $loginDuplicate = 0;
        if($user->email == $request->company_email){
            $emailDuplicate = 1;

        }
        if($user->company_login == $request->company_login){
            $loginDuplicate = 1;
        }

        $this->validator($request->all(),$emailDuplicate,$loginDuplicate,1);

        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $firma = $request->company_name;
        $email = $request->company_email;
        $souborZmena = $firma . " " . $email;
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
            $zmena_jmena = new Google_Service_Drive_DriveFile();
            $zmena_jmena->setName($souborZmena);
            $service->files->update($user->company_url, $zmena_jmena, array(
                'mimeType' => 'text/csv',
                'uploadType' => 'multipart'
            ));
        } catch (Exception $e) {
            print "Nastala chyba: " . $e->getMessage();
        }

        $user->company_name=$request->company_name;
        $user->company_user_name = $request->company_firstname;
        $user->company_user_surname = $request->company_surname;
        $user->email = $request->company_email;
        $user->company_city = $request->company_city;
        $user->company_street = $request->company_street;
        $user->company_ico = $request->company_ico;
        $user->company_phone = $request->company_phone;
        $user->company_login = $request->company_login;
        $user->save();
        session()->flash('message', 'Vaše údaje byly úspěšně změněny!');
        return redirect()->route('showCompanyProfileData');
    }

    public function updateProfilePassword(Request $request){
        $user = Auth::user();
        if($request->password == $request->password_verify){
            if($request->password == "" || $request->password_verify == ""){
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
                return back()->withInput(['tab'=>'zmenaHesla']);
            }else if(strlen($request->password) < 8){
                session()->flash('errorZprava', 'Heslo musí mít alespoň 8 znaků!');
                return back()->withInput(['tab'=>'zmenaHesla']);
            }else{
                $user->password= Hash::make($request->password);
            }

        }else{
            if($request->password == "" || $request->password_verify == ""){
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
            }
            session()->flash('errorZprava', 'Hesla se neshodují!');
            return back()->withInput(['tab'=>'zmenaHesla']);
        }
        $user->save();
        session()->flash('message', 'Vaše heslo bylo úspešně změněno!');
        return back()->withInput(['tab'=>'zmenaHesla']);
    }

    public function deleteOldImage(){
        $user = Auth::user();
        if($user->company_picture){
            Storage::delete('/public/company_images/'.$user->company_picture);
            $user->update(['company_picture' => NULL]);
        }
        return redirect()->back();
    }

    public function uploadImage(Request $request){
        if($request->hasFile('obrazek')){
           $nazev = $request->obrazek->getClientOriginalName();
           $pripona = explode(".",$nazev);
           if($pripona[1] == "jpg" || $pripona[1] == "png" || $pripona[1] == "jpeg"){
               $user = Auth::user();
               if($user->company_picture){
                   Storage::delete('/public/company_images/'.$user->company_picture);
               }
               $tokenUnique = Str::random(20);
               $tokenUnique2 = Str::random(5);
               $tokenUnique3 = Str::random(10);
               $request->obrazek->storeAs('company_images',$tokenUnique.$tokenUnique2.$tokenUnique3,'public');
               $user->update(['company_picture' => $tokenUnique.$tokenUnique2.$tokenUnique3]);
               session()->flash('obrazekZpravaSuccess', 'Profilová fotka úspěšně nahrána.');
           }else{
               session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg]');
           }
        }
        return redirect()->back();
    }

    public function showCompanyProfileData(){
        $user = Auth::user();
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($user->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($user->company_id);
        $pocetNadchazejicich = Shift::getUpcomingCompanyShiftsCount($user->company_id);
        $pocetHistorie = Shift::getHistoricalCompanyShiftsCount($user->company_id);
        $datumVytvoreni = new DateTime($user->created_at);
        $datumZobrazeniVytvoreni = $datumVytvoreni->format('d.m.Y');
        return view('profiles.company_profile')
            ->with('profilovka',$user->company_picture)
            ->with('pocetZamestnancu',$pocetZamestnancu)
            ->with('pocetSmen',$pocetSmen)
            ->with('pocetNadchazejicich',$pocetNadchazejicich)
            ->with('pocetHistorie',$pocetHistorie)
            ->with('vytvorenUcet',$datumZobrazeniVytvoreni);
    }

    public function deleteCompanyProfile(){
        $user = Auth::user();
        DB::table('table_companies')
            ->where(['table_companies.company_id' => $user->company_id])
            ->delete();

        if($user->company_url != NULL){
            $keyFileLocation =storage_path('app/credentials.json');
            /*ID složky, do které chceme soubory nahrávat*/
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
                $results = $service->files->get($user->company_url);
                if($results != NULL) {
                    $service->files->delete($user->company_url);
                }
            }catch (Exception $e){
            }
        }

        session()->flash('success', 'Váš účet byl úspěšně smazán!');
        return redirect()->route('company');
    }

    public function createFolderGoogleDrive(Request $request){
        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $slozka=$request->nazev;
        /*Service účet pro pripojeni ke Google Drive*/
        $emailAddress = 'tozondoservices@tozondo-drive.iam.gserviceaccount.com';
        /*Cesta k autorizačnímu klíči*/
        $user = Auth::user();
        $userSearch = Company::where('company_url', '=',$user->company_url )->first();
        $keyFileLocation =storage_path('app/credentials.json');
        /*ID složky, do které chceme soubory nahrávat*/
        $folderId = $userSearch->company_url;
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
            $file->setName($slozka);
            $mimeType = 'application/vnd.google-apps.folder';
            $file->setMimeType($mimeType);
            /*Nasměrování do zvolené složky*/

            $file->setParents(array($folderId));

            /*Odeslání dat*/
            $createdFile = $service->files->create($file, array(
                'mimeType' => $mimeType,
                'uploadType' => "multipart"
            ));


        } catch (Exception $e) {
            file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
            die();
        }
        session()->flash('success', 'Složka '.$request->nazev.' byla úspešně vytvořena na Vašem Google Drive!');
        return redirect()->back();
    }

    public function addLanguage(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'jazyk' => ['required','min:2','string', 'max:30'],
        ]);

        if ($validator->fails()) {
            $chyby = implode($validator->errors()->all());
            session()->flash('errory', $chyby);
            return redirect()->back();
        }

        \App\Models\Languages::create([
            'language_name' => $request->jazyk,
            'company_id' =>  $user->company_id
        ]);
        session()->flash('success', 'Jazyk '.$request->jazyk.' byl úspešně přidán do výběru!');
        return redirect()->back();
    }


    public function removeLanguage(Request $request){
        for ($i = 0;$i < count($request->jazyky);$i++){
            Languages::where('language_id', $request->jazyky[$i])->delete();
        }
        session()->flash('success', 'Jazyk/y '.$request->jazyk.' byl úspešně smazán z výběru!');
        return redirect()->back();
    }

    public function uploadGoogleDrive(Request $request){
        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $jmenoSouboru = $request->fileInput;

        /*Service účet pro pripojeni ke Google Drive*/
        $emailAddress = 'tozondoservices@tozondo-drive.iam.gserviceaccount.com';
        /*Cesta k autorizačnímu klíči*/
        $user = Auth::user();
        $userSearch = Company::where('company_url', '=',$user->company_url )->first();

        $keyFileLocation =storage_path('app/credentials.json');
        /*ID složky, do které chceme soubory nahrávat*/
        $folderId =  $request->slozky;
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
            $file->setName( $request->file('fileInput')->getClientOriginalName());
            $mime = finfo_open(FILEINFO_MIME);

            $mimeType = finfo_file($mime,$jmenoSouboru);
            finfo_close($mime);

            $file->setMimeType($mimeType);
            /*Nasměrování do zvolené složky*/

            $file->setParents(array($folderId));

            /*Odeslání dat*/
            $createdFile = $service->files->create($file, array(
                'data' => file_get_contents($jmenoSouboru),
                'mimeType' => $mimeType,
                'uploadType' => "multipart"
            ));
        }catch (Exception $e){
            file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
            die();
        }
        session()->flash('success', 'Soubor '.$request->file('fileInput')->getClientOriginalName().' byl úspešně nahrán na Váš Google Drive!');
        return redirect()->back();

    }
    public function getAllGoogleDriveFoldersOptions(){
        $html = '';
        $slozky="";
        $keyFileLocation =storage_path('app/credentials.json');
        $client = new Google_Client();
        $httpClient = $client->getHttpClient();
        $config = $httpClient->getConfig();
        $config['verify'] = false;
        $client->setHttpClient(new Client($config));
        $client->setApplicationName("BackupDrive");

        try {
            $client->setAuthConfig($keyFileLocation);
            $client->useApplicationDefaultCredentials();
            $client->addScope([
                \Google_Service_Drive::DRIVE,
                \Google_Service_Drive::DRIVE_METADATA
            ]);
            $mimeType = 'application/vnd.google-apps.folder';
            $service = new \Google_Service_Drive($client);
            $user = Auth::user();
            $userSearch = Company::where('company_url', '=', $user->company_url)->first();
            $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
            $optParams = array(
                'pageSize' => 10,
                'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
                'q' => "trashed = false AND mimeType='application/vnd.google-apps.folder' AND '" . $userSearch->company_url . "' in parents"
            );
            $slozky = $service->files->listFiles($optParams);

            $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Vyberte, do které složky chcete nahrát soubor.</strong>
                    </div>';
            $html .='<div class="form-group">
                        <select name="slozky" required id="slozky" style="color:black" class="form-control input-lg dynamic" data-dependent="state">
                             <option value="">Vyber složku</option>
                             <option value="'.$user->company_url.'">/</option>';
            foreach ($slozky as $slozka){
                $html .= '<option value="'.$slozka->id.'">'.$slozka->name.'</option>';
            }
            $html .= ' </select></div>';

        }catch (Exception $e){
        }

        return response()->json(['html'=>$html]);
    }

    public function getAllGoogleDriveFilesCheckboxes(){
        $html = '';
        $slozky="";
        $keyFileLocation =storage_path('app/credentials.json');
        $client = new Google_Client();
        $httpClient = $client->getHttpClient();
        $config = $httpClient->getConfig();
        $config['verify'] = false;
        $client->setHttpClient(new Client($config));
        $client->setApplicationName("BackupDrive");
        try {
            $client->setAuthConfig($keyFileLocation);
            $client->useApplicationDefaultCredentials();
            $client->addScope([
                \Google_Service_Drive::DRIVE,
                \Google_Service_Drive::DRIVE_METADATA
            ]);
            $mimeType = 'application/vnd.google-apps.folder';
            $service = new \Google_Service_Drive($client);
            $user = Auth::user();
            $userSearch = Company::where('company_url', '=', $user->company_url)->first();

            $optParams = array(
                'pageSize' => 10,
                'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
                'q' => "'" . $userSearch->company_url . "' in parents"
            );
            $slozkyDelete = $service->files->listFiles($optParams);
            if(count($slozkyDelete) == 0){
                $html .= '<div class="alert alert-danger alert-block">
                            <strong>Na Google Drive nemáte žadné soubory/složky.</strong>
                        </div>';
            }else{
                $html .= '<div class="alert alert-info alert-block text-center">
                            <strong>Seznam souborů na Vašem Google Drive, vyberte, které soubory chcete smazat.</strong>
                        </div>';
                foreach ($slozkyDelete as $slozkaDelete){
                    $html .= '<center><div class="custom-control form-control-lg custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="'.$slozkaDelete->name.'" name="google_drive_delete_listFile[]" value="'.$slozkaDelete->id.'">
                             <label class="custom-control-label" style="font-size:16px;" for="'.$slozkaDelete->name.'">
                                    '.$slozkaDelete->name.'
                            </label>
                            </div></center>
                            ';
                }
            }

        }catch (Exception $e){
        }
        return response()->json(['html'=>$html]);
    }

    public function deleteFileGoogleDrive(Request $request){
        if($request->google_drive_delete_listFile != NULL){
           /* $emailAddress = 'tozondoservices@tozondo-drive.iam.gserviceaccount.com';
          $role = 'organizer';

          $userPermission = new Google_Service_Drive_Permission(array(
              'type' => 'user',
              'role' => $role,
              'emailAddress' => $emailAddress
          ));*/

          $keyFileLocation =storage_path('app/credentials.json');
          /*ID složky, do které chceme soubory nahrávat*/
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
                foreach ($request->google_drive_delete_listFile as $slozka){
                    $service->files->delete($slozka);
                }

            }catch (Exception $e){
                file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
                die();
            }
            if(count($request->google_drive_delete_listFile) == 1){
                session()->flash('success', 'Soubor byl úspešně smazán!');
            }else{
                session()->flash('success', 'Soubory byly úspešně smazány!');
            }
        }else{
                session()->flash('fail', 'Nevybral jste žádný soubor!');
        }
        return redirect()->back();
    }

    public function addEmployee(Request $request){
        $user = Auth::user();
        $this->validator($request->all(),-1,-1,2);
        $uzivatel = $request->employee_login;

        \App\Models\Employee::create([
            'employee_name' => $request->employee_name,
            'employee_surname' => $request->employee_surname,
            'employee_phone' => $request->employee_phone,
            'email' => $request->employee_email,
            'employee_note' => $request->employee_note,
            'employee_position' => $request->employee_position,
            'employee_city' => $request->employee_city,
            'employee_street' => $request->employee_street,
            'employee_login' => $request->employee_login,
            'password' => Hash::make($request->employee_password),
            'employee_company' => $user->company_id
        ]);

        $employeeSearch = Employee::where('employee_login', '=',$uzivatel )->first();
        if($request->jazyky != ""){
            for($i = 0;$i < count($request->jazyky);$i++){
                \App\Models\Employee_Language::create([
                    'language_id' => $request->jazyky[$i],
                    'employee_id' => $employeeSearch->employee_id,
                ]);
            }
        }

        if($request->hasFile('employee_picture')){
            $nazev = $request->employee_picture->getClientOriginalName();
            $pripona = explode(".",$nazev);
            if($pripona[1] == "jpg" || $pripona[1] == "png" || $pripona[1] == "jpeg"){
                if($employeeSearch->employee_picture){
                    Storage::delete('/public/employee_images/'.$employeeSearch->employee_picture);
                }
                $tokenUnique = Str::random(20);
                $request->employee_picture->storeAs('employee_images',$tokenUnique.$nazev,'public');
                $employeeSearch->update(['employee_picture' => $tokenUnique.$nazev]);
            }
        }

        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $soubor = $request->employee_name.' '.$request->employee_surname;

        /*Cesta k autorizačnímu klíči*/
        $keyFileLocation =storage_path('app/credentials.json');
        /*ID složky, do které chceme soubory nahrávat*/
        $folderId = $user->company_url;
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

            $fileId = $createdFile->id;
          /*  $role = 'writer';
            $userEmail = $request->employee_email;


            $userPermission = new Google_Service_Drive_Permission(array(
                'type' => 'user',
                'role' => $role,
                'emailAddress' => $userEmail
            ));

            $request = $service->permissions->create(
                $fileId, $userPermission, array('fields' => 'id')
            );*/

        } catch (Exception $e) {
            file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
            die();
        }
        $employeeSearch->update(['employee_drive_url' => $fileId]);
        session()->flash('success', 'Zaměstnanec '.$request->employee_name.' '.$request->employee_surname.' byl úspešně vytvořen!');
        return redirect()->back();
    }

    public function addShift(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'shift_start' => ['required'],
            'shift_end' =>  ['required'],
            'shift_place' =>  ['required', 'string', 'max:255'],
        ]);

        $shift_start = new DateTime($request->shift_start);
        $shift_end = new DateTime($request->shift_end);
        $now = new DateTime();
        $chybaDatumy = array();
        $bool_datumy = 0;
       // $difference_start = $shift_start->format('U') - $now->format('U');
       // $difference_end = $shift_end->format('U') - $now->format('U');
        $difference_shifts = $shift_end->format('U') - $shift_start->format('U');

        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetDnu = $hodinyRozdil->d;
        $pocetHodin = $hodinyRozdil->h;
        $pocetMinut = $hodinyRozdil->i;

        if($request->shift_start != NULL){
            if($difference_shifts <= 0){
                array_push($chybaDatumy,'Konec směny je stejný buďto stejný jako její začátek, nebo je dříve než samotný začátek!');
                $bool_datumy = 1;
            }

            if(($pocetHodin == 12 && $pocetMinut > 0) || $pocetHodin > 12 || $pocetDnu > 0){
                array_push($chybaDatumy,'Maximální délka jedné směny je 12 hodin!');
                $bool_datumy = 1;
            }
        }

        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }

        if ($validator->fails() || $bool_datumy == 1) {
            session()->flash('erroryShift', $chybaDatumy);
            return redirect()->back();
        }

        \App\Models\Shift::create([
            'shift_start' => $request->shift_start,
            'shift_end' => $request->shift_end,
            'shift_place' =>  $request->shift_place,
            'shift_importance_id' => $request->shiftImportance,
            'shift_note' => $request->shift_note,
            'company_id' => $user->company_id
        ]);

        session()->flash('success', 'Směna byla úspešně vytvořena!');
        return redirect()->back();
    }


    public function getAllShifts(){
        $user = Auth::user();
        $html = '
 <input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavac" onkeyup="Search()" placeholder="Hledat směnu na základě ID, začátku, lokace, nebo konce směny ..." title="Zadejte údaje o směně">
                    <table class="table table-dark" id="show_table_employee_delete" style="font-size: 16px;">
                    <thead>
                        <tr>
                            <th scope="col" style="width:25%;text-align: center;">Začátek</th>
                            <th scope="col" style="width:25%;text-align: center;">Konec</th>
                            <th scope="col" style="width:25%;text-align: center;">Lokace <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(2,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Počet zaměstnanců <i class="fa fa-sort-numeric-desc" style="margin-left: 5px" onclick="zmenaIkonkyCisla(this);sortTable(3,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Smazat</th>
                        </tr>
                    </thead>
                    <tbody>';

        $smeny = DB::table('table_shifts')
            ->select('table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $user->company_id])
            ->orderByDesc('table_shifts.shift_start')
            ->get();

        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');

            $pocet_zamestnancu = DB::table('table_employee_shifts')
                ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                ->select('table_shifts.shift_start','table_shifts.shift_end',
                    'table_shifts.shift_place','table_shifts.shift_id')
                ->where(['table_employee_shifts.shift_id' => $smena->shift_id])
                ->orderByDesc('table_shifts.shift_start')
                ->count();

            $html .= '<tr><td class="text-center">'.$smena->shift_start.'</td><td class="text-center"> '.$smena->shift_end.'</td>
                      <td class="text-center"> '.$smena->shift_place.'</td>
                      <td class="text-center"> '.$pocet_zamestnancu.'</td>
                      <td class="text-center"><center><input type="checkbox" class="form-check-input"  id="smenyDeleteDashboard" name="smenyDeleteDashboard[]" value="'.$smena->shift_id.'"></center></td>';
        }
        $html .= '</tbody></table>
           <script>
            function zmenaIkonky(x) {
                x.classList.toggle("fa-sort-alpha-desc");
                x.classList.toggle("fa-sort-alpha-asc");
            }

            function zmenaIkonkyCisla(x) {
                x.classList.toggle("fa-sort-numeric-asc");
                x.classList.toggle("fa-sort-numeric-desc");
            }

            function Search() {
                var input, filter, table, tr, td, td2, td3, td4, i, txtValue, txtValue2, txtValue3, txtValue4;
                input = document.getElementById("vyhledavac");
                filter = input.value.toUpperCase();
                table = document.getElementById("show_table_employee_delete");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td3 = tr[i].getElementsByTagName("td")[0];
                    td = tr[i].getElementsByTagName("td")[1];
                    td2 = tr[i].getElementsByTagName("td")[2];
                    td4 = tr[i].getElementsByTagName("td")[3];
                    if (td || td2 || td3 || td4) {
                        txtValue = td.textContent || td.innerText;
                        txtValue2 = td2.textContent || td2.innerText;
                        txtValue3 = td3.textContent || td3.innerText;
                        txtValue4 = td4.textContent || td4.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1
                            || txtValue3.toUpperCase().indexOf(filter) > -1 || txtValue4.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }

                    }

                }
            }

            function sortTable(n,ikonka) {
                var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                table = document.getElementById("show_table_employee_delete");
                switching = true;
                dir = "asc";
                while (switching) {
                    switching = false;
                    rows = table.rows;

                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("TD")[n];
                        y = rows[i + 1].getElementsByTagName("TD")[n];

                        if (dir == "asc") {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                shouldSwitch= true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        switchcount ++;
                    } else {
                        if (switchcount == 0 && dir == "asc") {
                            dir = "desc";
                            switching = true;
                        }
                    }
                }
            }
        </script>';
        return response()->json(['html'=>$html]);
    }

    public function deleteShift(Request  $request){
        if($request->smenyDeleteDashboard != NULL){
            foreach ($request->smenyDeleteDashboard as $smena){
                DB::table('table_shifts')
                    ->where(['table_shifts.shift_id' => $smena])
                    ->delete();

                DB::table('table_employee_shifts')
                    ->where(['table_employee_shifts.shift_id' => $smena])
                    ->delete();
            }
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

    public function getAllEmployees(){
        $user = Auth::user();
        $html = '<input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavac" onkeyup="Search()" placeholder="Hledat zaměstnance na základě jeho jména, příjmení, pozice, nebo počtu směn ..." title="Zadejte údaje o směně">
                    <table class="table table-dark" id="show_table" style="font-size: 16px;">
                    <thead>
                        <tr>
                            <th scope="col" style="width:25%;text-align: center;">Jméno <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(0,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Příjmení <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(1,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Pozice <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(2,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Počet směn <i class="fa fa-sort-numeric-desc" style="margin-left: 5px" onclick="zmenaIkonkyCisla(this);sortTable(3,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Smazat</th>
                        </tr>
                    </thead>
                    <tbody>';

        $zamestnanci = DB::table('table_employees')
            ->select('table_employees.employee_id','table_employees.employee_name','table_employees.employee_surname',
                'table_employees.employee_position')
            ->where(['table_employees.employee_company' => $user->company_id])
            ->orderByDesc('table_employees.employee_surname')
            ->get();

        foreach ($zamestnanci as $zamestnanec){

            $pocet_smen = DB::table('table_employee_shifts')
                ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                ->where(['table_employee_shifts.employee_id' => $zamestnanec->employee_id])
                ->count();

            $html .= '<tr><td class="text-center">'.$zamestnanec->employee_name.'</td><td class="text-center"> '.$zamestnanec->employee_surname.'</td>
                      <td class="text-center"> '.$zamestnanec->employee_position.'</td>
                      <td class="text-center"> '.$pocet_smen.'</td>
                      <td class="text-center"><center><input type="checkbox" class="form-check-input"  id="zamestnanciDeleteDashboard" name="zamestnanciDeleteDashboard[]" value="'.$zamestnanec->employee_id.'"></center></td>';
        }
        $html .= '</tbody></table>
        <script>
            function zmenaIkonky(x) {
                x.classList.toggle("fa-sort-alpha-desc");
                x.classList.toggle("fa-sort-alpha-asc");
            }

            function zmenaIkonkyCisla(x) {
                x.classList.toggle("fa-sort-numeric-asc");
                x.classList.toggle("fa-sort-numeric-desc");
            }

            function Search() {
                var input, filter, table, tr, td, td2, td3, td4, i, txtValue, txtValue2, txtValue3, txtValue4;
                input = document.getElementById("vyhledavac");
                filter = input.value.toUpperCase();
                table = document.getElementById("show_table");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td3 = tr[i].getElementsByTagName("td")[0];
                    td = tr[i].getElementsByTagName("td")[1];
                    td2 = tr[i].getElementsByTagName("td")[2];
                    td4 = tr[i].getElementsByTagName("td")[3];
                    if (td || td2 || td3 || td4) {
                        txtValue = td.textContent || td.innerText;
                        txtValue2 = td2.textContent || td2.innerText;
                        txtValue3 = td3.textContent || td3.innerText;
                        txtValue4 = td4.textContent || td4.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1
                            || txtValue3.toUpperCase().indexOf(filter) > -1 || txtValue4.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }

                    }

                }
            }

            function sortTable(n,ikonka) {
                var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                table = document.getElementById("show_table");
                switching = true;
                dir = "asc";
                while (switching) {
                    switching = false;
                    rows = table.rows;

                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("TD")[n];
                        y = rows[i + 1].getElementsByTagName("TD")[n];

                        if (dir == "asc") {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                shouldSwitch= true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        switchcount ++;
                    } else {
                        if (switchcount == 0 && dir == "asc") {
                            dir = "desc";
                            switching = true;
                        }
                    }
                }
            }
        </script>';
        return response()->json(['html'=>$html]);
    }

    public function deleteEmployee(Request $request){
        if($request->zamestnanciDeleteDashboard != NULL){
            foreach ($request->zamestnanciDeleteDashboard as $zamestnanec){
                DB::table('table_employees')
                    ->where(['table_employees.employee_id' => $zamestnanec])
                    ->delete();

                DB::table('table_employee_shifts')
                    ->where(['table_employee_shifts.employee_id' => $zamestnanec])
                    ->delete();
            }
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
