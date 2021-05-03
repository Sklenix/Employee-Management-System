<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TimeDimension
 * @property int $time_id
 * @property int $day
 * @property int $month
 * @property int|null $quarter
 * @property int $year
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereTimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereYear($value)
 * @mixin \Eloquent
 */
class TimeDimension extends Model {
    /* Nazev souboru: TimeDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce time_dimension (soucast OLAP sekce systemu) */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $table = 'time_dimension';
    protected $primaryKey = 'time_id';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'time_id','day','month','quarter','year'
    ];
}
