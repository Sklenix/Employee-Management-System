<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyDimension
 *
 * @property int $company_id
 * @property string $company_name
 * @property string|null $company_city
 * @property string|null $company_street
 * @property string $company_user_name
 * @property string $company_user_surname
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension whereCompanyCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension whereCompanyStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension whereCompanyUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyDimension whereCompanyUserSurname($value)
 * @mixin \Eloquent
 */
class CompanyDimension extends Model
{
    use HasFactory;
    protected $table = 'company_dimension';
    protected $primaryKey = 'company_id';
    public $timestamps = false;

    protected $fillable = [
        'company_id','company_name','company_city','company_street','company_user_name','company_user_surname'
    ];
}
