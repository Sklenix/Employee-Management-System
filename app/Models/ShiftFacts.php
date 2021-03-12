<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShiftFacts
 *
 * @property int $shift_id
 * @property string|null $shift_start
 * @property string|null $shift_end
 * @property int|null $attendance_came
 * @property string|null $attendance_check_in
 * @property string|null $attendance_check_out
 * @property string|null $attendance_check_in_company
 * @property string|null $attendance_check_out_company
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereAttendanceCame($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereAttendanceCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereAttendanceCheckInCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereAttendanceCheckOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereAttendanceCheckOutCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereShiftEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereShiftStart($value)
 * @mixin \Eloquent
 * @property int $fact_id
 * @property float $shift_total_hours
 * @property float|null $absence_total_hours
 * @property float|null $employee_overall
 * @property int|null $employee_late_flag
 * @property int|null $employee_injury_flag
 * @property int|null $absence_reason
 * @property int $company_id
 * @property int $time_id
 * @property int $employee_id
 * @property int $shift_info_id
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereAbsenceReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereAbsenceTotalHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereEmployeeInjuryFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereEmployeeLateFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereEmployeeOverall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereFactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereShiftInfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereShiftTotalHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftFacts whereTimeId($value)
 */
class ShiftFacts extends Model
{
    use HasFactory;
    protected $table = 'shift_facts';
    public $timestamps = false;

    protected $fillable = [
        'shift_total_hours','employee_late_flag','employee_injury_flag','absence_reason','absence_total_hours','employee_overall','company_id','time_id',
        'employee_id','shift_info_id'
    ];
}
