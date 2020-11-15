<?php

namespace App\Models;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Str;

class Company extends Authenticatable implements  MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'company_id';
    protected $table = 'table_companies';
    protected $guard = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name', 'company_user_name', 'company_user_surname','email','company_phone','company_login','password','company_url','company_picture','company_city','company_street','company_ico','company_dic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'company_password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }


}
