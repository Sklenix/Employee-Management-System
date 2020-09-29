<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
        return Validator::make($data, [
            'company' => ['required', 'string', 'max:255'],
            'first_name' =>  ['required', 'string', 'max:255'],
            'surname' =>  ['required', 'string', 'max:255'],
            'email' => ['required','string','email','max:255'],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'login' => ['required', 'string', 'max:255'],
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
        return \App\Models\Company::create([
            'company_name' => $data['company'],
            'company_first_name' => $data['first_name'],
            'company_surname' => $data['surname'],
            'company_email' => $data['email'],
            'company_phone' => $data['phone'],
            'company_login' => $data['login'],
            'company_password' => Hash::make($data['password']),
        ]);
    }
}
