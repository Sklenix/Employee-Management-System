<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Languages;
use App\Models\Vacation;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class VacationsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->whereIn('table_importances_shifts.importance_id',[1,2,3,4,5])
            ->get();

        return view('company_actions.vacations_list')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    public function getEmployeeVacations(Request $request){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        if ($request->ajax()) {
            $vacations = Vacation::getCompanyEmployeesVacations($user->company_id);
            return Datatables::of($vacations)
                ->addIndexColumn()
                ->editColumn('vacation_state', function($vacations){
                    if($vacations->vacation_state == 0){
                        return '<center><p class="col-md-10" style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezažádáno</p></center>';
                    }else if($vacations->vacation_state == 1){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p></center>';
                    }else if($vacations->vacation_state == 2){
                        return '<center><p class="col-md-10" style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p></center>';
                    }else if($vacations->vacation_state == 3){
                        return '<center><p class="col-md-10" style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p></center>';
                    }else if($vacations->vacation_state == 4){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p></center>';
                    }
                })
                ->addColumn('vacation_actuality', function($vacations){
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $vacations->vacation_start);
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', $vacations->vacation_end);
                    $now = Carbon::now();
                    $rozhod_start = $now->gte($start);
                    $rozhod_end = $now->lte($end);
                    $rozhod_end2 = $now->gte($end);
                    if($rozhod_start == 1 && $rozhod_end == 1){
                        return '<center><p class="col-md-10" style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Probíhá</p></center>';
                    }else if($rozhod_end2 == 1){
                        return '<center><p class="col-md-10" style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Proběhla</p></center>';
                    }else{
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Proběhne</p></center>';
                    }
                })
                ->addColumn('action', function($vacations){
                    return '<button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#AgreementVacationModal" class="btn btn-dark btn-sm" id="getVacationAgreement"><i class="fa fa-check" aria-hidden="true"></i> Schválit</button>
                            <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#EditVacationModal" class="btn btn-primary btn-sm" id="getEditVacationData"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                            <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#DisagreementVacationModal" class="btn btn-dark btn-sm" id="getVacationDisagreement"><i class="fa fa-times" aria-hidden="true"></i> Neschválit</button>
                            <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#SeenVacationModal" class="btn btn-dark btn-sm" style="margin-top:6px;" id="getVacationSeen"><i class="fa fa-book" aria-hidden="true"></i> Přečteno</button>
                            <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#DeleteVacationModal" class="btn btn-danger btn-sm" style="margin-top:6px;" id="getVacationDelete">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>
                            <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#SentVacationModal" class="btn btn-dark btn-sm" style="margin-top:6px;" id="getVacationSent"><i class="fa fa-paper-plane" aria-hidden="true"></i> Odesláno</button>';
                })
                ->rawColumns(['action', 'vacation_state', 'vacation_actuality'])
                ->make(true);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'zacatek_dovolene' => ['required'],
            'konec_dovolene' =>  ['required'],
            'zamestnanec_vyber' =>  ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $zamestnanec = Employee::findOrFail($request->zamestnanec_vyber);
        $vacation_start = new DateTime($request->zacatek_dovolene);
        $vacation_end = new DateTime($request->konec_dovolene);

        $difference_vacation = $vacation_end->format('U') - $vacation_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;

        if($difference_vacation < 0){
            array_push($chybaDatumy,'Datum konce dovolené dříve než datum začátku dovolené!');
            $bool_datumy = 1;
        }

        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }
        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }

        $new_vacation = new Vacation();
        $new_vacation->vacation_start = $request->zacatek_dovolene;
        $new_vacation->vacation_end = $request->konec_dovolene;
        $new_vacation->vacation_note = $request->poznamka;
        $new_vacation->vacation_state = 1;
        $new_vacation->employee_id = $request->zamestnanec_vyber;
        $new_vacation->save();

        return response()->json(['success'=>'Dovolená pro zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspešně vytvořena.']);
    }

    public function edit($id){
        $data = Vacation::findOrFail($id);
        $vacation_start = date('Y-m-d\TH:i', strtotime($data->vacation_start));
        $vacation_end = date('Y-m-d\TH:i', strtotime($data->vacation_end));
        $created_at = date('d.m.Y H:i:s', strtotime($data->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($data->updated_at));
        $stav = '';
        if($data->vacation_state == 0){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:whitesmoke;">Nezažádáno</p></div></center>';
        }else if($data->vacation_state == 1){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Odesláno</p></div></center>';
        }else if($data->vacation_state == 2){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellowgreen;">Schváleno</p></div></center>';
        }else if($data->vacation_state == 3){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:orangered;">Neschváleno</p></div></center>';
        }else if($data->vacation_state == 4){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Přečteno</p></div></center>';
        }
        $html = ''.$stav.'
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left" style="font-size: 16px;">Datum od(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="vacation_start_edit" id="vacation_start_edit" value="'.$vacation_start.'" autocomplete="vacation_start_edit" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left" style="font-size: 16px;">Datum od(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="vacation_end_edit" id="vacation_end_edit" value="'.$vacation_end.'" autocomplete="vacation_end_edit" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left" style="font-size: 16px;">Poznámka</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                        </div>
                                        <textarea name="vacation_note_edit" placeholder="Zadejte poznámku k dovolené..." id="vacation_note_edit" class="form-control" autocomplete="vacation_note_edit">'.$data->vacation_note.'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <p class="d-flex justify-content-center">Dovolená vytvořena '.$created_at.', naposledy aktualizována '.$updated_at.'.</p>
                    </div>';

        return response()->json(['html'=>$html]);
    }

    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        $data = Vacation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vacation_start_edit' => ['required'],
            'vacation_end_edit' =>  ['required'],
        ]);

        $vacation_start = new DateTime($request->vacation_start_edit);
        $vacation_end = new DateTime($request->vacation_end_edit);
        $puvodniVacation_start = new DateTime($data->vacation_start);
        $puvodniVacation_end = new DateTime($data->vacation_end);

        $difference_vacation = $vacation_end->format('U') - $vacation_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;

        if($difference_vacation < 0){
            array_push($chybaDatumy,'Datum konce dovolené dříve, než datum začátku dovolené!');
            $bool_datumy = 1;
        }

        if($difference_vacation == 0){
            array_push($chybaDatumy,'Datum konce dovolené stejné jako datum začátku dovolené!');
            $bool_datumy = 1;
        }

        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }

        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }

        $bool = 0;

        $diffValidDatumStart = $puvodniVacation_start->format('U') - $vacation_start->format('U');
        $diffValidDatumEnd = $puvodniVacation_end->format('U') - $vacation_end->format('U');

        if(($diffValidDatumStart == 0) && ($diffValidDatumEnd == 0) && ($request->vacation_note_edit == $data->vacation_note)){
            $bool = 0;
        }else{
            $bool = 1;
        }

        $data->vacation_start = $request->vacation_start_edit;
        $data->vacation_end = $request->vacation_end_edit;
        $data->vacation_note = $request->vacation_note_edit;
        $data->save();

        if($bool != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Dovolená byla úspěšně zaktualizována.']);
        }
    }

    public function vacationAgree($id){
        $vacation = Vacation::findOrFail($id);
        if($vacation->vacation_state == 1 || $vacation->vacation_state == 3 || $vacation->vacation_state == 4){
            $vacation->vacation_state = 2;
            $vacation->save();
            return response()->json(['success'=>'Schválení dovolené proběhlo úspěšně.','fail' => '']);
        }else if($vacation->vacation_state == 2){
            return response()->json(['success'=>'','fail' => 'Už jste dovolenou schválil!']);
        }
    }

    public function vacationDisagree($id){
        $vacation = Vacation::findOrFail($id);
        if($vacation->vacation_state == 3){
            return response()->json(['success'=>'','fail' => 'Nelze neschválit žádost o dovolenou, která již neschválena byla!']);
        }else if($vacation->vacation_state == 1 || $vacation->vacation_state == 2 || $vacation->vacation_state == 4){
            $vacation->vacation_state = 3;
            $vacation->save();
            return response()->json(['success'=>'Neschválení žádosti o dovolenou proběhlo úspěšně.','fail' => '']);
        }
    }

    public function vacationSeen($id){
        $vacation = Vacation::findOrFail($id);
        if($vacation->vacation_state == 1 || $vacation->vacation_state == 2 || $vacation->vacation_state == 3){
            $vacation->vacation_state = 4;
            $vacation->save();
            return response()->json(['success'=>'Žádost je nyní ve stavu přečteno.','fail' => '']);
        }else if($vacation->vacation_state == 4){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu přečtení nachází!']);
        }
    }

    public function vacationSent($id){
        $vacation = Vacation::findOrFail($id);
        if($vacation->vacation_state == 2 || $vacation->vacation_state == 3 || $vacation->vacation_state == 4){
            $vacation->vacation_state = 1;
            $vacation->save();
            return response()->json(['success'=>'Žádost je nyní ve výchozím stavu "Odesláno".','fail' => '']);
        }else if($vacation->vacation_state == 1){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu odeslání nachází!']);
        }
    }

    public function destroy($id){
        Vacation::findOrFail($id)->delete();
        return response()->json(['success'=>'Dovolená byla úspěšně smazána.','fail' => '']);
    }
}
