<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Employee_Shift;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EmployeeAllShiftsController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('employee_actions.shifts_history_list')
            ->with('profilovka',$user->employee_picture);
    }

    public function getAllEmployeeShiftsList(Request $request){
        $user = Auth::user();
        if ($request->ajax()) {
            $data = Employee_Shift::getEmployeeAllShiftsWithAttendance($user->employee_id);
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
                ->rawColumns(['action','reason_description','attendance_check_in','attendance_check_out'])
                ->make(true);
        }
    }
}
