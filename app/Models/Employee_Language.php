<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Employee_Language
 *
 * @property int $language_employee_id
 * @property int $language_id
 * @property int $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language whereLanguageEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Language whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Employee_Language extends Model
{
    use HasFactory;

    protected $table = 'table_employee_table_languages';
    protected $guard = 'employees';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id','employee_id'
    ];

    public static function getEmployeeLanguages($employee_id){
        return DB::table('table_employee_languages')
            ->select('table_employee_languages.language_name')
            ->join('table_employee_table_languages','table_employee_table_languages.language_id','=','table_employee_languages.language_id')
            ->where(['table_employee_table_languages.employee_id' => $employee_id])
            ->get();
    }

}
