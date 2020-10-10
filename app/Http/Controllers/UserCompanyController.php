<?php

namespace App\Http\Controllers;
use http\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
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
        return view('home')->with('slozky',$slozky)->with('slozkyDelete',$slozkyDelete);

    }

    public function showVerifySuccess()
    {

        return view('email_verified_login', [

        ]);

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



}
