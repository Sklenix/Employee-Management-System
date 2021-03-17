<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Injury;
use App\Models\Languages;
use App\Models\Shift;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InjuriesDatatableController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        return view('company_actions.injuries_list')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    public function getInjuries(Request $request){
        $user = Auth::user();
        if ($request->ajax()) {
            $data = Injury::getInjuries($user->company_id);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    return '<button type="button" class="btn btn-primary btn-sm" id="getEditInjuryData" data-toggle="modal"  data-target="#EditInjuryModal" data-id="'.$data->injury_id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editovat</button>
                    <button type="button" data-id="'.$data->injury_id.'" data-toggle="modal" data-target="#DeleteInjuryModal" class="btn btn-danger btn-sm" id="getInjuryDelete">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getEmployeeShiftsSelect(Request $request){
        $smeny = DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $request->employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->orderBy('table_shifts.shift_end', 'asc')
            ->get();
        $html = '<option value="">Vyberte směnu</option>';
        foreach($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $html .= '<option value="'.$smena->shift_id.'">'.$smena->shift_start.' - '.$smena->shift_end.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getShiftStart($shift_id){
        $smena = Shift::findOrFail($shift_id);
        $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
        return response()->json(['shift_start'=> $datumStart]);
    }

    public function store(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'employee_id_add' => ['required'],
            'shift_id_add' =>  ['required'],
            'injury_date_add' =>  ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $smenaUdaje = Shift::getConcreteShift($request->shift_id_add);
        $injury_date = new DateTime($request->injury_date_add);
        $shift_start = new DateTime($smenaUdaje[0]->shift_start);
        $shift_end = new DateTime($smenaUdaje[0]->shift_end);
        $difference_start = $injury_date->format('U') - $shift_start->format('U');
        $difference_end = $shift_end->format('U') - $injury_date->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0 || $difference_end < 0){
            array_push($chybaDatumy,'Datum zranění dříve než začátek směny, nebo datum zranění je po konci směny!');
            $bool_datumy = 1;
        }
        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }
        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }
        Injury::create(['injury_description' => $request->injury_note_add, 'injury_date' => $request->injury_date_add, 'employee_id' => $request->employee_id_add, 'shift_id' => $request->shift_id_add]);
        $shift_info_id = OlapETL::getShiftInfoId($request->employee_id_add, $user->company_id, $smenaUdaje[0]->shift_start, $smenaUdaje[0]->shift_end);
        OlapETL::aggregateEmployeeInjuryFlag($shift_info_id, $request->employee_id_add, $user->company_id, 1);
        return response()->json(['success'=>'Zranění bylo úspešně vytvořeno.']);
    }

    public function edit($id){
        $data = Injury::findOrFail($id);
        $datumZraneni = date('Y-m-d\TH:i', strtotime($data->injury_date));
        $html = '   <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left" style="font-size: 16px;">Datum zranění(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="injury_date_edit" id="injury_date_edit" value="'.$datumZraneni.'" autocomplete="injury_date_edit" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left" style="font-size: 16px;">Popis zranění</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                        </div>
                                        <textarea name="injury_description_edit" placeholder="Zadejte popis zranění..." id="injury_description_edit" class="form-control" autocomplete="injury_description_edit">'.$data->injury_description.'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <p class="d-flex justify-content-center">Zranění vytvořeno '.$data->created_at.', naposledy aktualizováno '.$data->updated_at.'.</p>
                    </div>';

        return response()->json(['html'=>$html]);
    }

    public function update(Request $request, $id){
        $user = Auth::user();
        $zraneni = Injury::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'injury_date' => ['required'],
        ]);
        $smenaUdaje = Injury::getEmployeeInjuries($user->company_id, $zraneni->employee_id, $zraneni->shift_id);
        $injury_date = new DateTime($request->injury_date);
        $puvodniInjuryDate = new DateTime($zraneni->injury_date);
        $shift_start = new DateTime($smenaUdaje[0]->shift_start);
        $shift_end = new DateTime($smenaUdaje[0]->shift_end);
        $difference_start = $injury_date->format('U') - $shift_start->format('U');
        $difference_end = $shift_end->format('U') - $injury_date->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0 || $difference_end < 0){
            array_push($chybaDatumy,'Datum zranění dříve než začátek směny, nebo datum zranění je po konci směny!');
            $bool_datumy = 1;
        }
        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }
        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }
        $bool = 0;
        $diffValidDatum = $injury_date->format('U') - $puvodniInjuryDate->format('U');
        if(($diffValidDatum == 0) && ($request->injury_description == $zraneni->injury_description)){
            $bool = 0;
        }else{
            $bool = 1;
        }
        Injury::where(['injury_id' => $zraneni->injury_id])->update(['injury_date' => $request->injury_date, 'injury_description' => $request->injury_description]);
        if($bool != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Zranění bylo úspěšně zeditováno.']);
        }
    }

    public function destroy($id){
        $user = Auth::user();
        $zraneni = Injury::findOrFail($id);
        $smena = Shift::findOrFail($zraneni->shift_id);
        $shift_info_id = OlapETL::getShiftInfoId($zraneni->employee_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        OlapETL::aggregateEmployeeInjuryFlag($shift_info_id, $zraneni->employee_id, $user->company_id, 0);
        Injury::findOrFail($id)->delete();
        return response()->json(['success'=>'Zranění bylo úspěšně smazáno.']);
    }
}
