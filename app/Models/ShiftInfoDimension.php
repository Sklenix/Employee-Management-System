<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShiftInfoDimension
 * @property int $shift_id
 * @property string|null $shift_start
 * @property string|null $shift_end
 * @property int|null $attendance_came
 * @property string|null $attendance_check_in
 * @property string|null $attendance_check_out
 * @property string|null $attendance_check_in_company
 * @property string|null $attendance_check_out_company
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereAttendanceCame($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereAttendanceCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereAttendanceCheckInCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereAttendanceCheckOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereAttendanceCheckOutCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereShiftEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereShiftStart($value)
 * @mixin \Eloquent
 * @property int $shift_info_id
 * @property int|null $absence_reason_value
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereAbsenceReasonValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShiftInfoDimension whereShiftInfoId($value)
 */
class ShiftInfoDimension extends Model {
    /* Nazev souboru: ShiftInfoDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce shift_info_dimension (soucast OLAP sekce systemu) */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $table = 'shift_info_dimension';
    protected $primaryKey = 'shift_info_id';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'shift_info_id', 'shift_start', 'shift_end','attendance_came', 'attendance_check_in','attendance_check_out',
        'attendance_check_in_company', 'attendance_check_out_company', 'absence_reason'
    ];
}
