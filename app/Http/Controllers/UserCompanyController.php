<?php

namespace App\Http\Controllers;
use http\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
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
                'q' => "trashed = false AND mimeType='application/vnd.google-apps.folder' AND '" . $userSearch->company_url . "' in parents"
            );
            $slozky = $service->files->listFiles($optParams);

            $optParams = array(
                'pageSize' => 10,
                'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
                'q' => "'" . $userSearch->company_url . "' in parents"
            );
            $slozkyDelete = $service->files->listFiles($optParams);


        }catch (Exception $e){

        }
        return view('home')->with('slozky',$slozky)->with('slozkyDelete',$slozkyDelete)->with('profilovka',$user->company_picture);;

    }

    public function showVerifySuccess()
    {
        return view('email_verified_login', [
        ]);

    }


    protected function validator(array $data,$emailDuplicate,$loginDuplicate,$verze){
        if($verze == 1){
            if($emailDuplicate == 1 && $loginDuplicate == 0){
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
            }else if($loginDuplicate == 1 && $emailDuplicate == 0){
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
            }else if($loginDuplicate == 1 && $emailDuplicate == 1){
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
            }else if($loginDuplicate == 0 && $emailDuplicate == 0){
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
        return redirect()->route('showProfileData');
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
               $request->obrazek->storeAs('company_images',$tokenUnique.$nazev,'public');
               $user->update(['company_picture' => $tokenUnique.$nazev]);
               session()->flash('obrazekZpravaSuccess', 'Profilová fotka úspěšně nahrána.');
           }else{
               session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg]');
           }
        }
        return redirect()->back();
    }

    public function showCompanyProfileData(){
        $user = Auth::user();
        return view('profiles.company_profile')->with('profilovka',$user->company_picture);
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
        session()->flash('successCreateFolder', 'Složka '.$request->nazev.' byla úspešně vytvořena na Vašem Google Drive!');
        return redirect()->intended('/company/profile/');
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
        session()->flash('successUpload', 'Soubor '.$request->file('fileInput')->getClientOriginalName().' byl úspešně nahrán na Váš Google Drive!');
        return redirect()->intended('/company/profile/');

    }


    public function deleteFileGoogleDrive(Request $request){

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
            $service->files->delete( $request->slozkyDelete);
        }catch (Exception $e){
            file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
            die();
        }


        session()->flash('successDelete', 'Soubor byl úspešně smazán!');
        return redirect()->intended('/company/profile/');

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

}
