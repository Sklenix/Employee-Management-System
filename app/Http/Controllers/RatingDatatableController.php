<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Languages;
use Google_Client;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RatingDatatableController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();

        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->get();

        return view('company_actions.rate_list')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance);
    }

    public function getRatings(Request $request){
        $user = Auth::user();
        if ($request->ajax()) {
            $data = Employee::where('employee_company',$user->company_id);
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_reliability', function($data){
                    $odpoved = '';
                    if($data->employee_reliability == NULL){
                        return 'Nehodnoceno';
                    }else{
                        if($data->employee_reliability == 0){
                            return 0;
                        }else {
                            for ($i = 0; $i < $data->employee_reliability; $i++) {
                                $odpoved .= '<i class="fa fa-star" aria-hidden="true"></i>';
                            }
                            return $odpoved;
                        }
                    }
                })
                ->editColumn('employee_absence', function($data){
                    $odpoved = '';
                    if($data->employee_absence == NULL){
                        return 'Nehodnoceno';
                    }else{
                        if($data->employee_absence == 0){
                            return 0;
                        }else{
                            for ($i = 0; $i < $data->employee_absence; $i++){
                                $odpoved .= '<i class="fa fa-star" aria-hidden="true"></i>';
                            }
                            return $odpoved;
                        }
                    }
                })
                ->editColumn('employee_workindex', function($data){
                    $odpoved = '';
                    if($data->employee_workindex == NULL){
                        return 'Nehodnoceno';
                    }else{
                        if($data->employee_workindex == 0){
                            return 0;
                        }else {
                            for ($i = 0; $i < $data->employee_workindex; $i++) {
                                $odpoved .= '<i class="fa fa-star" aria-hidden="true"></i>';
                            }
                            return $odpoved;
                        }
                    }
                })
                ->editColumn('employee_overall', function($data){
                    if($data->employee_overall == NULL){
                        return 'Nedefinováno';
                    }else{
                        return round(($data->employee_reliability + $data->employee_absence + $data->employee_workindex) / 3,2);
                    }
                })
                ->addColumn('action', function($data){
                    return '<button type="button" data-id="'.$data->employee_id.'" data-toggle="modal" data-target="#RateEmployeeModal" class="btn btn-dark btn-sm" id="getEmployeeRate"><i class="fa fa-check-square" aria-hidden="true"></i> Přehodnotit</button>';
                })
                ->rawColumns(['action','employee_overall', 'employee_reliability','employee_absence','employee_workindex'])
                ->make(true);
        }
    }


    public function editRate($id){
        $employee = new Employee;
        $data = $employee->findData($id);
        $html = '<div class="form-group text-center">
                    <label for="realibitySlider" style="font-size: 17px;">Spolehlivost:</label>
                   <input type="range" min="0" name="edit_realibility" max="5" value="'.$data->employee_reliability.'" class="slider" id="realibitySlider">
                    <div style="margin-top:8px;background-color: #2d995b;padding:1px 3px;border-radius: 10px;">
                        <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewRealibility"></span>b</p>
                    </div>
                </div>
                <div class="form-group text-center">
                   <label for="absenceSlider" style="font-size: 17px;">Dochvilnost:</label>
                   <input type="range" min="0" max="5" name="edit_absence" value="'.$data->employee_absence.'" class="slider" id="absenceSlider">
                   <div style="margin-top:8px;background-color: #2d995b;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewAbsence"></span>b</p>
                   </div>
                </div>

                <div class="form-group text-center">
                   <label for="workSlider" style="font-size: 17px;">Pracovitost:</label>
                   <input type="range" min="0" max="5" name="edit_workindex" value="'.$data->employee_workindex.'" class="slider" id="workSlider">
                   <div style="margin-top:8px;background-color: #2d995b;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewWork"></span>b</p>
                   </div>
                </div>';

        return response()->json(['html'=>$html]);
    }

    public function updateRate(Request $request, $id){
        $employee = new Employee;
        $vysledek = Employee::find($id);
        $jmeno = $vysledek->employee_name;
        $prijmeni = $vysledek->employee_surname;
        $skore = ($request->employee_reliability + $request->employee_absence + $request->employee_workindex) / 3;
        Employee::where('employee_id', $id)->update(array('employee_overall' => round($skore,2)));
        $employee->updateData($id, $request->all());
        OlapETL::updateEmployeeScoreOverall($vysledek->employee_id, round($skore,2));
        return response()->json(['success'=>'Hodnocení zaměstnance '.$jmeno.' '.$prijmeni.' bylo úspěšně dokončeno.']);
    }
}
