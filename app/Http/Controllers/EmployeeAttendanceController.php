<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Employee_Shift;
use App\Models\Languages;
use App\Models\Shift;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class EmployeeAttendanceController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);

        return view('company_actions.attendances')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    public function getAttendance(Request $request, $id){
        if ($request->ajax()) {
            $data = Employee_Shift::getEmployeeAllShifts($id);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('shift_importance_id', function($data){
                    $aktualniDulezitost = Shift::getCurrentImportanceShift($data->shift_importance_id);
                    return $aktualniDulezitost[0]->importance_description;
                })
                ->addColumn('attendance_came', function($data){
                    $came = Attendance::getAttendanceCame($data->shift_id, $data->employee_id);
                    if($came->isEmpty()){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezapsáno</p></center>';
                    }else {
                        if ($came[0]->attendance_came === NULL) {
                            return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezapsáno</p></center>';
                        } else if ($came[0]->attendance_came == 1) {
                            return '<center><p class="col-md-10" style="color:lightgreen;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Ano</p></center>';
                        } else {
                            return '<center><p class="col-md-10" style="color:orangered;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Ne</p></center>';
                        }
                    }
                })
                ->addColumn('attendance_check_in_company', function($data){
                    $checkin_company = Attendance::getCompanyCheckIn($data->shift_id, $data->employee_id);
                    if($checkin_company->isEmpty()){
                        return 'Invalid date';
                    }else{
                        if($checkin_company[0]->attendance_check_in_company === NULL){
                            return 'Invalid date';
                        }else{
                            return $checkin_company[0]->attendance_check_in_company;
                        }
                    }
                })
                ->addColumn('attendance_check_out_company', function($data){
                    $checkout_company = Attendance::getCompanyCheckOut($data->shift_id, $data->employee_id);
                    if($checkout_company->isEmpty()){
                        return 'Invalid date';
                    }else{
                        if($checkout_company[0]->attendance_check_out_company === NULL){
                            return 'Invalid date';
                        }else{
                            return $checkout_company[0]->attendance_check_out_company;
                        }
                    }
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
                ->addColumn('hours_total', function($data){
                    $udaje = Attendance::getAllCheckInCheckOutForShift($data->shift_id, $data->employee_id);
                    if($udaje->isEmpty()){
                        return '<p style="color:black;">Nezapsaný check-in/out</p>';
                    }else{
                        if ($udaje[0]->attendance_check_in_company == NULL || $udaje[0]->attendance_check_out_company == NULL){
                            if($udaje[0]->attendance_check_in == NULL || $udaje[0]->attendance_check_out == NULL){
                                return '<p style="color:black;">Nezapsaný check-in/out</p>';
                            }else if($udaje[0]->attendance_check_in != NULL && $udaje[0]->attendance_check_out != NULL){
                                $checkin = new DateTime($udaje[0]->attendance_check_in);
                                $checkout = new DateTime($udaje[0]->attendance_check_out);
                                $hodinyRozdilCheck =$checkout->diff($checkin);
                                return '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                            }
                            return '<p style="color:black;">Nezapsaný check-in/out</p>';
                        }else if($udaje[0]->attendance_check_in_company != NULL && $udaje[0]->attendance_check_out_company != NULL){
                            $checkin = new DateTime($udaje[0]->attendance_check_in_company);
                            $checkout = new DateTime($udaje[0]->attendance_check_out_company);
                            $hodinyRozdilCheck =$checkout->diff($checkin);
                            return '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                        }
                    }

                })
                ->addColumn('action', function($data){
                    return '<button type="button" data-id="'.$data->shift_id.'" data-toggle="modal" style="margin-top:5px;" data-target="#ShowAttendanceOptionsModal" class="btn btn-success btn-sm" id="getEmployeesOptions"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Docházka</button>';
                })
                ->rawColumns(['action','attendance_came','attendance_check_out','attendance_check_in','reason_description','hours_total'])
                ->make(true);
        }
    }

    public function getAttendanceOptions($id,$zamestnanec_id){
        $user = Auth::user();
        $html  = '';

        $zamestnanec = Employee::find($zamestnanec_id);

        $html .='<p style="text-align: center;font-size: 17px;">'.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</p>';

        $html .= '<center><button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckinModal" class="btn btn-primary" id="getCheckInShift" "><i class="fa fa-check-square-o" aria-hidden="true"></i> Check-in</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckoutModal" class="btn btn-primary" id="getCheckOutShift" "><i class="fa fa-check-square-o" aria-hidden="true"></i> Check-out</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceAbsenceModal" class="btn btn-primary" id="getAbsenceReasonAttendance" "><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Status</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceNoteModal" class="btn btn-primary" id="getNoteAttendance" "><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Poznámka</button>
                  ';
        return response()->json(['html'=>$html]);
    }


}
