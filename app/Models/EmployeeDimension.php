<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmployeeDimension
 * @property int $employee_id
 * @property string $employee_name
 * @property string $employee_surname
 * @property string $employee_position
 * @property string|null $employee_overall
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeOverall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeSurname($value)
 * @mixin \Eloquent
 */
class EmployeeDimension extends Model {
    /* Nazev souboru: EmployeeDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce employee_dimension (soucast OLAP sekce systemu) */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $table = 'employee_dimension';
    protected $primaryKey = 'employee_id';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'employee_id','employee_name','employee_surname','employee_position','employee_overall'
    ];

}
