<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:employee')->except('logout');
    }



    public function showAdminLoginForm()
    {
        return view('auth.login', ['url' => 'admin']);
    }

    public function adminLogin(Request $request)
    {
        $pravidla = [
            'email' => 'required',
            'password' => 'required'
        ];

        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'max:100' => 'U položky :attribute je povoleno maximálně 100 znaků.'
        ];

        $this->validate($request, $pravidla, $vlastniHlasky);

        if (Auth::guard('admin')->attempt(['admin_email' => $request->email, 'password' => $request->password], $request->get('remember')) ||  Auth::guard('admin')->attempt(['admin_login' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/admin/profile');
        }else{
            session()->flash('message', 'Špatně zadané přihlašovací údaje!');
            session()->flash('alert-class', 'alert-danger');
            return redirect()->route('showAdminLoginForm');
        }
      //  return back()->withInput($request->only('email', 'remember'));
    }

    public function showEmployeeLoginForm()
    {
        return view('auth.login', ['url' => 'employee']);
    }

    public function employeeLogin(Request $request)
    {
        $pravidla = [
            'email' => 'required',
            'password' => 'required'
        ];

        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'max:100' => 'U položky :attribute je povoleno maximálně 100 znaků.'
        ];

        $this->validate($request, $pravidla, $vlastniHlasky);

        if (Auth::guard('employee')->attempt(['email' => $request->email, 'password' => $request->password]) || Auth::guard('employee')->attempt(['employee_login' => $request->email, 'password' => $request->password])) {

            return redirect()->intended('/employee/profile/');
        }else{
            session()->flash('message', 'Špatně zadané přihlašovací údaje!');
            session()->flash('alert-class', 'alert-danger');
            return redirect()->route('employee');
        }
    }

    public function showCompanyLoginForm()
    {
        return view('auth.login', ['url' => 'company']);
    }



    public function companyLogin(Request $request)
   {

       $pravidla = [
           'email' => 'required',
           'password' => 'required'
       ];

       $vlastniHlasky = [
           'required' => 'Položka :attribute je povinná.',
           'email' => 'U položky :attribute nebyl dodržen formát emailu.',
           'max:100' => 'U položky :attribute je povoleno maximálně 100 znaků.'
       ];

       $this->validate($request, $pravidla, $vlastniHlasky);


       if (Auth::attempt(['email' => $request->email, 'password' => $request->password]) || Auth::attempt(['company_login' => $request->email, 'password' => $request->password]))
       {
          // return redirect()->intended('userhome');
           return redirect()->intended('/company/profile/');
       }else{
           session()->flash('message', 'Špatně zadané přihlašovací údaje!');
           session()->flash('alert-class', 'alert-danger');
           return redirect()->route('company');
       }
   }




}
