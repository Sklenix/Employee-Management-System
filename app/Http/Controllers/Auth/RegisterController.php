<?php

namespace App\Http\Controllers\Auth;

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
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/login/company";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $pravidla = [
            'company' => ['required', 'string', 'max:255'],
            'first_name' =>  ['required', 'string', 'max:255'],
            'surname' =>  ['required', 'string', 'max:255'],
            'company_email' => ['required','unique:table_companies,email','string','email','max:255'],
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'company_login' => ['required','unique:table_companies,company_login', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.',
            'unique' => 'Váš e-mail, nebo Váš login už v databázi evidujeme.'
        ];

        Validator::validate($data, $pravidla, $vlastniHlasky);

        return Validator::make($data, [
            'company' => ['required', 'string', 'max:255'],
            'first_name' =>  ['required', 'string', 'max:255'],
            'surname' =>  ['required', 'string', 'max:255'],
            'company_email' => ['required','string','email','max:255'],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'company_login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Company
     */
    protected function create(array $data)
    {
        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $cele_jmeno = $data['company'];
        $id_brigadnik = $data['company_email'];
        $soubor = $cele_jmeno . " " . $id_brigadnik;
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
            $userEmail = $data['company_email'];
            $fileId = $createdFile->id;

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
        return \App\Models\Company::create([
            'company_name' => $data['company'],
            'company_first_name' => $data['first_name'],
            'company_surname' => $data['surname'],
            'email' => $data['company_email'],
            'company_phone' => $data['phone'],
            'company_login' => $data['company_login'],
            'company_url' => $fileId,
            'password' => Hash::make($data['password']),
        ]);


    }

    public function register(Request $request)
    {

        try {
            $this->validator($request->all())->validate();
        } catch (ValidationException $e) {
        }

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('successRegister', 'Registrace proběhla úspěšně, byl vám zaslán e-mail pro ověření e-mailové adresy, před přihlášením ověřte svou e-mailovou adresu.');
    }

}
