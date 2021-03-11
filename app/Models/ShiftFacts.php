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
 */
class ShiftFacts extends Model
{
    use HasFactory;
    protected $table = 'shift_info_dimension';
    public $timestamps = false;

    protected $fillable = [
        'shift_total_hours','absence_total_count','absence_total_hours','average_employee_score','company_id','time_id',
        'employee_id','shift_id'
    ];
}
