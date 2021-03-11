<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\ImportancesShifts
 *
 * @property int $importance_id
 * @property int|null $importance_value
 * @property string|null $importance_description
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts whereImportanceDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts whereImportanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts whereImportanceValue($value)
 * @mixin \Eloquent
 */
class ImportancesShifts extends Model
{
    use HasFactory;

    protected $primaryKey = 'importance_id';
    protected $table = 'table_importances_shifts';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importance_value', 'importance_description'
    ];

    public static function getParticularImportance($importance_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->join('table_shifts','table_shifts.shift_importance_id','=','table_importances_shifts.importance_id')
            ->where(['table_shifts.shift_importance_id' => $importance_id])
            ->get();
    }
}
