<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\AbsenceReason
 *
 * @property int $reason_id
 * @property int $reason_value
 * @property string|null $reason_description
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason query()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason whereReasonDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason whereReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason whereReasonValue($value)
 * @mixin \Eloquent
 */
class AbsenceReason extends Model
{
    use HasFactory;

    protected $primaryKey = 'reason_id';
    protected $table = 'table_absence_reasons';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason_description','reason_value'
    ];

    public static function getAllReasons(){
        return DB::table('table_absence_reasons')
            ->select('table_absence_reasons.reason_description','table_absence_reasons.reason_value')
            ->get();
    }

    public static function getEmployeeCurrentShiftAbsenceReason($zamestnanec_id, $shift_id){
       return DB::table('table_attendances')
            ->join('table_absence_reasons', 'table_attendances.absence_reason_id', '=', 'table_absence_reasons.reason_id')
            ->select('table_absence_reasons.reason_description')
            ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();
    }

}
