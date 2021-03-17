<?php

namespace App\Http\Controllers;

use App\Http\Controllers\OlapETL;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Employee_Shift;
use App\Models\Shift;
use App\Models\ShiftInfoDimension;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EmployeeCurrentShiftsController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('employee_actions.current_shifts_list')
           ->with('profilovka',$user->employee_picture);
    }

    public function getEmployeeCurrentShifts(Request $request){
        $user = Auth::user();
        if ($request->ajax()) {
            $data = Employee_Shift::getEmployeeCurrentShifts($user->employee_id);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('shift_importance_id', function($data){
                    $aktualniDulezitost = Shift::getCurrentImportanceShift($data->shift_importance_id);
                    return $aktualniDulezitost[0]->importance_description;
                })
                ->addColumn('attendance_check_in', function($data){
                    $checkin =Attendance::getEmployeeCheckIn($data->shift_id, $data->employee_id);
                    if($checkin->isEmpty()){
                        return 'Invalid date';
                    }else{
                        if($checkin[0]->attendance_check_in === NULL){
                            return 'Invalid date';
                        }else{
                            return $checkin[0]->attendance_check_in;
                        }
                    }
                })
                ->addColumn('attendance_check_out', function($data){
                    $checkout = Attendance::getEmployeeCheckOut($data->shift_id, $data->employee_id);
                    if($checkout->isEmpty()){
                        return 'Invalid date';
                    }else{
                        if($checkout[0]->attendance_check_out === NULL){
                            return 'Invalid date';
                        }else{
                           return $checkout[0]->attendance_check_out;
                        }
                    }
                })
                ->addColumn('reason_description', function($data){
                    $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($data->shift_id, $data->employee_id);
                    if($aktualniAbsence->isEmpty()){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Čekající</p></center>';
                    }else{
                        if($aktualniAbsence[0]->reason_description === NULL){
                            return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Čekající</p></center>';
                        }else{
                            if($aktualniAbsence[0]->absence_reason_id == 5){
                                return '<center><p class="col-md-10" style="color:lightgreen;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">'.$aktualniAbsence[0]->reason_description.'</p></center>';
                            }else{
                                return '<center><p class="col-md-10" style="color:orangered;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">'.$aktualniAbsence[0]->reason_description.'</p></center>';
                            }
                        }
                    }
                })
                ->addColumn('action', function($data){
                    return '<button type="button" data-id="'.$data->shift_id.'" data-toggle="modal" data-target="#confirmCheckinModal" class="btn btn-dark btn-sm" id="updateCheckinEmployee"><i class="fa fa-check-square-o" aria-hidden="true"></i> Příchod</button>
                            <button type="button" data-id="'.$data->shift_id.'" data-toggle="modal" data-target="#confirmCheckoutModal" class="btn btn-dark btn-sm" id="updateCheckoutEmployee"><i class="fa fa-check-square-o" aria-hidden="true"></i> Odchod</button>
                            <button type="button" class="btn btn-primary btn-sm" style="margin-top:5px;" id="getDetailsCurrentShift" data-toggle="modal"  data-target="#CurrentShiftDetailModal" data-id="'.$data->shift_id.'">&nbsp;&nbsp;<i class="fa fa-eye" aria-hidden="true"></i> Detail&nbsp;&nbsp;</button>';
                })
                ->rawColumns(['action','reason_description','attendance_check_out','attendance_check_in'])
                ->make(true);
        }
    }

    public function showCurrentShiftDetail($shift_id){
        $smena_info = Shift::findOrFail($shift_id);
        $shift_start = new DateTime($smena_info->shift_start);
        $smena_info->shift_start = $shift_start->format('d.m.Y H:i');
        $shift_end = new DateTime($smena_info->shift_end);
        $smena_info->shift_end = $shift_end->format('d.m.Y H:i');
        $aktualniDulezitost = Shift::getCurrentImportanceShift($smena_info->shift_importance_id);
        $html = '<div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:16px;padding:5px 10px;border-radius: 10px;background-color: #2d995b;">'.$aktualniDulezitost[0]->importance_description.'</div>
                    <center>
                        <div style="font-size: 16px;margin-bottom: 10px;">Začátek směny: '.$smena_info->shift_start.'</div>
                        <div style="font-size: 16px;margin-bottom: 10px;">Konec směny: '.$smena_info->shift_end.'</div>
                        <div style="font-size: 16px;">Lokace směny: '.$smena_info->shift_place.'</div>
                    </center>
                 </div>';
        return response()->json(['html'=>$html]);
    }

    public function updateEmployeeCheckin($shift_id){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($shift_id, $user->employee_id);
        $smena = Shift::find($shift_id);
        $now = Carbon::now();
        $now->second(0);
        $now->microsecond(0);
        $shift_start = new DateTime($smena->shift_start);
        $shift_end = new DateTime($smena->shift_end);
        $sekundy = 1200; // 20 minut
        $difference_start = $now->format('U') - ($shift_start->format('U') - $sekundy);
        $difference_end = $shift_end->format('U') - $now->format('U');
        if($difference_start < 0 || $difference_end < 0){
            return response()->json(['fail'=>'Příchod je možný nejdříve 15 minut před startem směny, nebo po konec směny.']);
        }
        $shift_info_id = OlapETL::getShiftInfoId($user->employee_id, NULL, $smena->shift_start, $smena->shift_end);
        $shift_start_date = Carbon::parse($smena->shift_start);
        if($dochazka->isEmpty()){
            if($now->gt($shift_start_date)){
                Attendance::create(['employee_id' => $user->employee_id, 'shift_id' => $shift_id, 'attendance_check_in' => $now, 'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::create(['employee_id' => $user->employee_id, 'shift_id' => $shift_id, 'attendance_check_in' => $now, 'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }else{
            if($now->gt($shift_start_date)){
                Attendance::where(['employee_id' => $user->employee_id, 'shift_id' => $shift_id])->update(['attendance_check_in' => $now,'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::where(['employee_id' => $user->employee_id, 'shift_id' => $shift_id])->update(['attendance_check_in' => $now,'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }
        OlapETL::extractAttendanceCheckInToShiftInfoDimension($shift_info_id, $now);
        OlapETL::aggregateEmployeeAbsenceTotalHoursAndLateFlag($shift_info_id, $user->employee_id, $user->employee_company, $smena->shift_start, NULL, $now);
        return response()->json(['success'=>'Váš příchod byl úspěšně zaznamenán.']);
    }

    public function updateEmployeeCheckOut($shift_id){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($shift_id, $user->employee_id);
        $smena = Shift::find($shift_id);
        $now = Carbon::now();
        $now->second(0);
        $now->microsecond(0);
        $shift_start = new DateTime($smena->shift_start);
        $difference_start = $now->format('U') - $shift_start->format('U');
        if($difference_start < 0){
            return response()->json(['fail'=>'Zapsat odchod před startem směny není možné.']);
        }
        $shift_info_id = OlapETL::getShiftInfoId($user->employee_id, NULL,  $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){
            Attendance::create(['employee_id' => $user->employee_id, 'shift_id' => $shift_id, 'attendance_check_out' => $now, 'attendance_came' => 1]);
        }else{
            Attendance::where(['employee_id' => $user->employee_id, 'shift_id' => $shift_id])->update(['attendance_check_out' => $now,'attendance_came' => 1]);
        }
        OlapETL::extractAttendanceCheckOutToShiftInfoDimension($shift_info_id, $now);
        return response()->json(['success'=>'Váš odchod byl úspěšně zaznamenán.']);
    }

}
