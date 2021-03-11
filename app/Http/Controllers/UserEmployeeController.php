<?php

namespace App\Http\Controllers;


use App\Models\Attendance;
use App\Models\Disease;
use App\Models\Employee;
use App\Models\Injury;
use App\Models\Report;
use App\Models\Shift;
use App\Models\Vacation;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

class UserEmployeeController extends Controller
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
        return view('homes.employee_home')->with('profilovka',$user->employee_picture);
    }

    public function showEmployeeProfileData(){
        $user = Auth::user();
        $pocetSmen = Shift::getEmployeeShiftsCount($user->employee_id);
        $pocetAbsenci = Attendance::getEmployeeAbsenceCount($user->employee_id);
        $pocetDovolenych = Vacation::getEmployeeVacationsCount($user->employee_id);
        $pocetNemoci = Disease::getEmployeeDiseasesCount($user->employee_id);
        $pocetZraneni = Injury::getEmployeeInjuriesInjuryCentreCount($user->employee_id);
        return view('profiles.employee_profile')
            ->with('profilovka',$user->employee_picture)
            ->with('pocetSmen',$pocetSmen)
            ->with('pocetAbsenci',$pocetAbsenci)
            ->with('pocetDovolenych',$pocetDovolenych);
    }

    public function deleteEmployeeProfile(){
        $user = Auth::user();
        DB::table('table_employees')
            ->where(['table_employees.employee_id' => $user->employee_id])
            ->delete();

        if($user->employee_drive_url != NULL){
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
                $results = $service->files->get($user->employee_drive_url);
                if($results != NULL) {
                    $service->files->delete($user->employee_drive_url);
                }
            }catch (Exception $e){
            }
        }
        session()->flash('success', 'Váš účet byl úspěšně smazán!');
        return redirect()->route('employee');
    }

    protected function validator(array $data,$emailDuplicate,$verze){
        if($verze == 1){
            if($emailDuplicate == 1){
                $pravidla = [
                    'employee_name' => ['required', 'string', 'max:255'],
                    'employee_surname' =>  ['required', 'string', 'max:255'],
                    'employee_city' =>  ['required', 'string', 'max:255'],
                    'employee_email' => ['required','string','email','max:255'],
                    'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                    'employee_street' =>  ['nullable', 'string', 'max:255']
                ];
            }else if($emailDuplicate == 0){
                $pravidla = [
                    'employee_name' => ['required', 'string', 'max:255'],
                    'employee_surname' =>  ['required', 'string', 'max:255'],
                    'employee_city' =>  ['required', 'string', 'max:255'],
                    'employee_email' => ['required','unique:table_employees,email','string','email','max:255'],
                    'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                    'employee_street' =>  ['nullable', 'string', 'max:255']
                ];
            }

            Validator::make($data, [
                'employee_name' => ['required', 'string', 'max:255'],
                'employee_surname' =>  ['required', 'string', 'max:255'],
                'employee_city' =>  ['required', 'string', 'max:255'],
                'employee_email' => ['required','string','email','max:255'],
                'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'employee_street' =>  ['nullable', 'string', 'max:255']
            ]);
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

    public function updateEmployeeProfileData(Request $request){
        $user = Auth::user();
        $emailDuplicate = 0;
        if($user->email == $request->employee_email){
            $emailDuplicate = 1;
        }
        $this->validator($request->all(),$emailDuplicate,1);

        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $jmeno = $request->employee_name;
        $prijmeni = $request->employee_surname;
        $souborZmena = $jmeno." ".$prijmeni;
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
            $service->files->update($user->employee_drive_url, $zmena_jmena, array(
                'mimeType' => 'text/csv',
                'uploadType' => 'multipart'
            ));
        } catch (Exception $e) {
            print "Nastala chyba: " . $e->getMessage();
        }

        $user->employee_name=$request->employee_name;
        $user->employee_surname = $request->employee_surname;
        $user->employee_phone = $request->employee_phone;
        $user->email = $request->employee_email;
        $user->employee_city = $request->employee_city;
        $user->employee_street = $request->employee_street;
        $user->save();
        session()->flash('message', 'Vaše údaje byly úspěšně změněny!');
        return redirect()->route('showEmployeeProfileData');
    }

    public function updateEmployeeProfilePassword(Request $request){
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

    public function deleteEmployeeOldImage(){
        $user = Auth::user();
        if($user->employee_picture){
            Storage::delete('/public/employee_images/'.$user->employee_picture);
            $user->update(['employee_picture' => NULL]);
        }
        return redirect()->back();
    }

    public function uploadEmployeeImage(Request $request){
        if($request->hasFile('obrazek')){
            $validator = Validator::make($request->all(),[
                'obrazek' => 'required|mimes:jpg,jpeg,png|max:8096',
            ]);
            if($validator->fails()){
                session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg], maximální velikost obrázku je 8MB!');
                return redirect()->back();
            }
            $user = Auth::user();
            if($user->employee_picture){
                Storage::delete('/public/employee_images/'.$user->employee_picture);
            }
            $tokenUnique = Str::random(20);
            $tokenUnique2 = Str::random(5);
            $tokenUnique3 = Str::random(10);
            $request->obrazek->storeAs('employee_images',$tokenUnique.$tokenUnique2.$tokenUnique3,'public');
            $user->update(['employee_picture' => $tokenUnique.$tokenUnique2.$tokenUnique3]);
            session()->flash('obrazekZpravaSuccess', 'Profilová fotka úspěšně nahrána.');
        }
        return redirect()->back();
    }


    public function getAllGoogleDriveFilesCheckboxes(){
        $html = '';
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
            $service = new \Google_Service_Drive($client);
            $user = Auth::user();

            $optParams = array(
                'pageSize' => 10,
                'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
                'q' => "'" . $user->employee_drive_url . "' in parents"
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
            $optParams = array(
                'pageSize' => 10,
                'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
                'q' => "trashed = false AND mimeType='application/vnd.google-apps.folder' AND '" . $user->employee_drive_url . "' in parents"
            );
            $slozky = $service->files->listFiles($optParams);

            $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Vyberte, do které složky chcete nahrát soubor.</strong>
                    </div>';
            $html .='<div class="form-group">
                        <select name="slozky" required id="slozky" style="color:black" class="form-control input-lg dynamic" data-dependent="state">
                             <option value="">Vyber složku</option>
                             <option value="'.$user->employee_drive_url.'">/</option>';
            foreach ($slozky as $slozka){
                $html .= '<option value="'.$slozka->id.'">'.$slozka->name.'</option>';
            }
            $html .= ' </select></div>';

        }catch (Exception $e){
        }

        return response()->json(['html'=>$html]);
    }

    public function createFolderGoogleDrive(Request $request){
        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $slozka=$request->nazev;
        /*Service účet pro pripojeni ke Google Drive*/
        $emailAddress = 'tozondoservices@tozondo-drive.iam.gserviceaccount.com';
        /*Cesta k autorizačnímu klíči*/
        $user = Auth::user();
        $keyFileLocation =storage_path('app/credentials.json');
        /*ID složky, do které chceme soubory nahrávat*/
        $folderId = $user->employee_drive_url;
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

    public function uploadGoogleDrive(Request $request){
        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $jmenoSouboru = $request->fileInput;

        /*Service účet pro pripojeni ke Google Drive*/
        $emailAddress = 'tozondoservices@tozondo-drive.iam.gserviceaccount.com';
        /*Cesta k autorizačnímu klíči*/
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


    public function deleteFileGoogleDrive(Request $request){
        if($request->google_drive_delete_listFile != NULL){
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


}
