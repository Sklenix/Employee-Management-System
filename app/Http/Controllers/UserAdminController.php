<?php


namespace App\Http\Controllers;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAdminController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('homes.admin_home');
    }

    public function showAdminProfileData(){
        return view('profiles.admin_profile');
    }

    public function deleteAdminProfile(){
        $user = Auth::user();
        DB::table('table_admin')
            ->where(['table_admin.admin_id' => $user->admin_id])
            ->delete();
        session()->flash('success', 'Váš účet byl úspěšně smazán!');
        return redirect()->route('showAdminLoginForm');
    }

    protected function validator(array $data,$emailDuplicate,$loginDuplicate,$verze){
        if($verze == 1){
            if($emailDuplicate == 1 && $loginDuplicate == 0){
                $pravidla = [
                    'admin_name' => ['required', 'string', 'max:255'],
                    'admin_surname' =>  ['required', 'string', 'max:255'],
                    'admin_login' =>  ['required','unique:table_admin,admin_login','string', 'max:255'],
                    'admin_email' => ['required','string','email','max:255']
                ];
            }else if($loginDuplicate == 1 && $emailDuplicate == 0){
                $pravidla = [
                    'admin_name' => ['required', 'string', 'max:255'],
                    'admin_surname' =>  ['required', 'string', 'max:255'],
                    'admin_login' =>  ['required','string', 'max:255'],
                    'admin_email' => ['required','unique:table_admin,admin_email','string','email','max:255']
                ];
            }else if($loginDuplicate == 1 && $emailDuplicate == 1){
                $pravidla = [
                    'admin_name' => ['required', 'string', 'max:255'],
                    'admin_surname' =>  ['required', 'string', 'max:255'],
                    'admin_login' =>  ['required','string', 'max:255'],
                    'admin_email' => ['required','string','email','max:255']
                ];
            }else if($loginDuplicate == 0 && $emailDuplicate == 0){
                $pravidla = [
                    'admin_name' => ['required', 'string', 'max:255'],
                    'admin_surname' =>  ['required', 'string', 'max:255'],
                    'admin_login' =>  ['required','unique:table_admin,admin_login','string', 'max:255'],
                    'admin_email' => ['required','unique:table_admin,admin_email','string','email','max:255']
                ];
            }

            Validator::make($data, [
                'admin_name' => ['required', 'string', 'max:255'],
                'admin_surname' =>  ['required', 'string', 'max:255'],
                'admin_login' =>  ['required','unique:table_admin,admin_login','string', 'max:255'],
                'admin_email' => ['required','string','email','max:255']
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

    public function updateAdminProfileData(Request $request){
        $user = Auth::user();
        $emailDuplicate = 0;
        $loginDuplicate = 0;
        if($user->email == $request->employee_email){
            $emailDuplicate = 1;
        }
        if($user->company_login == $request->company_login){
            $loginDuplicate = 1;
        }
        $this->validator($request->all(),$emailDuplicate,$loginDuplicate,1);
        $user->admin_name=$request->admin_name;
        $user->admin_surname = $request->admin_surname;
        $user->admin_email = $request->admin_email;
        $user->admin_login = $request->admin_login;
        $user->save();
        session()->flash('message', 'Vaše údaje byly úspěšně změněny!');
        return redirect()->route('showAdminProfileData');
    }

    public function updateAdminProfilePassword(Request $request){
        $user = Auth::user();
        if($request->password == $request->password_verify){
            if($request->password == "" || $request->password_verify == ""){
                session()->flash('errorZprava', 'Položky password, password_verify jsou povinné!');
                return back()->withInput(['tab'=>'zmenaHesla']);
            }else if(strlen($request->password) < 5){
                session()->flash('errorZprava', 'Heslo musí mít alespoň 5 znaků!');
                return back()->withInput(['tab'=>'zmenaHesla']);
            }else{
                $user->admin_password= Hash::make($request->password);
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
}
