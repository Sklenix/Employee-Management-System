<?php

namespace App\Models;
use App\Notifications\VerifyEmailNotification;
use DateTime;
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

    public static function getAverageEmployeeScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_overall != NULL){
                array_push($skore,$zamestnanec->employee_overall);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageEmployeeReliabilityScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_reliability != NULL){
                array_push($skore,$zamestnanec->employee_reliability);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageEmployeeAbsenceScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_absence != NULL){
                array_push($skore,$zamestnanec->employee_absence);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageEmployeeWorkScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_workindex != NULL){
                array_push($skore,$zamestnanec->employee_workindex);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageShiftHour($company_id){
        $smeny = Shift::getCompanyShifts($company_id);
        $delka = array();
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $celkove = $hodinyRozdil->h + ($hodinyRozdil->i/60);
            array_push($delka,$celkove);
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($delka);$i++){
            $sum += $delka[$i];
        }
        if(sizeof($delka) == 0){
            return 0;
        }
        return round($sum/sizeof($delka),2);
    }

    public static function getMaxShiftHour($company_id){
        $smeny = Shift::getCompanyShifts($company_id);
        $delka = array();
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $celkove = $hodinyRozdil->h + ($hodinyRozdil->i/60);
            array_push($delka,$celkove);
        }
        if(sizeof($delka) == 0){
            return 0;
        }
        return round(max($delka),2);
    }

    public static function getMinShiftHour($company_id){
        $smeny = Shift::getCompanyShifts($company_id);
        $delka = array();
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $celkove = $hodinyRozdil->h + ($hodinyRozdil->i/60);
            array_push($delka,$celkove);
        }
        if(sizeof($delka) == 0){
            return 0;
        }
        return round(min($delka),2);
    }


}
