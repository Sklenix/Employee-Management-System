<?php

namespace App\Models;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Str;

/**
 * App\Models\Company
 *
 * @property int $company_id
 * @property string $company_name
 * @property string $company_user_name
 * @property string $company_user_surname
 * @property string $email
 * @property string|null $company_phone
 * @property string $company_login
 * @property string $company_url
 * @property string|null $company_picture
 * @property string $password
 * @property string|null $company_ico
 * @property string|null $company_city
 * @property string|null $company_street
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyIco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyUserSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
