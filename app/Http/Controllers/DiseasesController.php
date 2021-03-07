<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\Employee;
use App\Models\Languages;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DiseasesController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->whereIn('table_importances_shifts.importance_id',[1,2,3,4,5])
            ->get();

        return view('company_actions.diseases_list')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    public function getEmployeeDiseases(Request $request){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        if ($request->ajax()) {
            $diseases = Disease::getCompanyEmployeesDiseases($user->company_id);
            return Datatables::of($diseases)
                ->addIndexColumn()
                ->editColumn('disease_state', function($diseases){
                    if($diseases->disease_state == 0){
                        return '<center><p class="col-md-10" style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezažádáno</p></center>';
                    }else if($diseases->disease_state == 1){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p></center>';
                    }else if($diseases->disease_state == 2){
                        return '<center><p class="col-md-10" style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p></center>';
                    }else if($diseases->disease_state == 3){
                        return '<center><p class="col-md-10" style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p></center>';
                    }else if($diseases->disease_state == 4){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p></center>';
                    }
                })
                ->addColumn('disease_actuality', function($diseases){
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $diseases->disease_from);
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', $diseases->disease_to);
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
                ->addColumn('action', function($diseases){
                    return '<button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#AgreementDiseaseModal" class="btn btn-dark btn-sm" id="getDiseaseAgreement"><i class="fa fa-check" aria-hidden="true"></i> Schválit</button>
                            <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#EditDiseaseModal" class="btn btn-primary btn-sm" id="getEditDisease"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                            <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#DisagreementDiseaseModal" class="btn btn-dark btn-sm" id="getDiseaseDisagreement"><i class="fa fa-times" aria-hidden="true"></i> Neschválit</button>
                            <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#SeenDiseaseModal" class="btn btn-dark btn-sm" style="margin-top:6px;" id="getDiseaseSeen"><i class="fa fa-book" aria-hidden="true"></i> Přečteno</button>
                            <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#DeleteDiseaseModal" class="btn btn-danger btn-sm" style="margin-top:6px;" id="getDiseaseDelete"><i class="fa fa-trash-o" aria-hidden="true"></i> Smazat</button>
                            <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#SentDiseaseModal" class="btn btn-dark btn-sm" style="margin-top:6px;" id="getDiseaseSent"><i class="fa fa-paper-plane" aria-hidden="true"></i> Odesláno</button>';
                })
                ->rawColumns(['action', 'disease_state', 'disease_actuality'])
                ->make(true);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nazev_nemoc' => ['required'],
            'nemoc_zacatek' =>  ['required'],
            'nemoc_konec' =>  ['required'],
            'zamestnanec_vyber' =>  ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $zamestnanec = Employee::findOrFail($request->zamestnanec_vyber);

        $disease_from = new DateTime($request->nemoc_zacatek);
        $disease_to = new DateTime($request->nemoc_konec);

        $difference_vacation = $disease_to->format('U') - $disease_from->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;

        if($difference_vacation < 0){
            array_push($chybaDatumy,'Datum konce nemocenské dříve než datum začátku nemocenské!');
            $bool_datumy = 1;
        }

        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }
        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }

        $new_disease = new Disease();
        $new_disease->disease_name = $request->nazev_nemoc;
        $new_disease->disease_from = $request->nemoc_zacatek;
        $new_disease->disease_to = $request->nemoc_konec;
        $new_disease->disease_note = $request->poznamka;
        $new_disease->disease_state = 1;
        $new_disease->employee_id = $request->zamestnanec_vyber;
        $new_disease->save();

        return response()->json(['success'=>'Nemocenská zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspešně vytvořena.']);
    }

    public function edit($id){
        $disease = Disease::findOrFail($id);
        $disease_from = date('Y-m-d\TH:i', strtotime($disease->disease_from));
        $disease_to = date('Y-m-d\TH:i', strtotime($disease->disease_to));
        $created_at = date('d.m.Y H:i:s', strtotime($disease->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($disease->updated_at));
        $stav = '';
        if($disease->disease_state == 0){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:whitesmoke;">Nezažádáno</p></div></center>';
        }else if($disease->disease_state == 1){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Odesláno</p></div></center>';
        }else if($disease->disease_state == 2){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:greenyellow;">Schváleno</p></div></center>';
        }else if($disease->disease_state == 3){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:orangered;">Neschváleno</p></div></center>';
        }else if($disease->disease_state == 4){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Přečteno</p></div></center>';
        }
        $html = ''.$stav.'
                          <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left" style="font-size: 16px;">Název(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-medkit" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Zadejte název nemoci..." autocomplete="disease_name_edit" name="disease_name_edit" id="disease_name_edit" value="'.$disease->disease_name.'">
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
                                        <input type="datetime-local" class="form-control" name="disease_from_edit" id="disease_from_edit" value="'.$disease_from.'" autocomplete="disease_from_edit" autofocus>
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
                                        <input type="datetime-local" class="form-control" name="disease_to_edit" id="disease_to_edit" value="'.$disease_to.'" autocomplete="disease_to_edit" autofocus>
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
                                        <textarea name="disease_note_edit" placeholder="Zadejte poznámku k nemocenské..." id="disease_note_edit" class="form-control" autocomplete="disease_note_edit">'.$disease->disease_note.'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <p class="d-flex justify-content-center">Nemocenská vytvořena '.$created_at.', naposledy aktualizována '.$updated_at.'.</p>
                    </div>';
        return response()->json(['html'=>$html]);
    }

    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        $disease = Disease::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'disease_name_edit' => ['required'],
            'disease_from_edit' =>  ['required'],
            'disease_to_edit' =>  ['required']
        ]);

        $disease_from = new DateTime($request->disease_from_edit);
        $disease_to = new DateTime($request->disease_to_edit);
        $puvodniDisease_from = new DateTime($disease->disease_from);
        $puvodniDisease_to = new DateTime($disease->disease_to);

        $difference_disease = $disease_to->format('U') - $disease_from->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;

        if($difference_disease < 0){
            array_push($chybaDatumy,'Datum konce nemocenské dříve než datum začátku nemocenské!');
            $bool_datumy = 1;
        }

        if($difference_disease == 0){
            array_push($chybaDatumy,'Datum konce nemocenské stejné jako datum začátku nemocenské!');
            $bool_datumy = 1;
        }

        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }

        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }

        $bool = 0;

        $diffValidDatumStart = $puvodniDisease_from->format('U') - $disease_from->format('U');
        $diffValidDatumEnd = $puvodniDisease_to->format('U') - $disease_to->format('U');

        if(($diffValidDatumStart == 0) && ($diffValidDatumEnd == 0) && ($request->disease_note_edit == $disease->disease_note)
            && ($request->disease_name_edit == $disease->disease_name)){
            $bool = 0;
        }else{
            $bool = 1;
        }

        $disease->disease_name = $request->disease_name_edit;
        $disease->disease_from = $request->disease_from_edit;
        $disease->disease_to = $request->disease_to_edit;
        $disease->disease_note = $request->disease_note_edit;
        $disease->save();

        if($bool != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Nemocenská byla úspěšně zaktualizována.']);
        }
    }

    public function diseaseAgree($id){
        $disease = Disease::findOrFail($id);
        if($disease->disease_state == 1 || $disease->disease_state == 3 || $disease->disease_state == 4){
            $disease->disease_state = 2;
            $disease->save();
            return response()->json(['success'=>'Schválení nemocenské proběhlo úspěšně.','fail' => '']);
        }else if($disease->disease_state == 2){
            return response()->json(['success'=>'','fail' => 'Už jste nemocenskou schválil!']);
        }
    }

    public function diseaseDisagree($id){
        $disease = Disease::findOrFail($id);
        if($disease->disease_state == 3){
            return response()->json(['success'=>'','fail' => 'Nelze neschválit žádost o dovolenou, která již neschválena byla!']);
        }else if($disease->disease_state == 1 || $disease->disease_state == 2 || $disease->disease_state == 4){
            $disease->disease_state = 3;
            $disease->save();
            return response()->json(['success'=>'Neschválení žádosti o dovolenou proběhlo úspěšně.','fail' => '']);
        }
    }

    public function diseaseSeen($id){
        $disease = Disease::findOrFail($id);
        if($disease->disease_state == 1 || $disease->disease_state == 2 || $disease->disease_state == 3){
            $disease->disease_state = 4;
            $disease->save();
            return response()->json(['success'=>'Žádost je nyní ve stavu přečteno.','fail' => '']);
        }else if($disease->disease_state == 4){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu přečtení nachází!']);
        }
    }

    public function diseaseSent($id){
        $disease = Disease::findOrFail($id);
        if($disease->disease_state == 2 || $disease->disease_state == 3 || $disease->disease_state == 4){
            $disease->disease_state = 1;
            $disease->save();
            return response()->json(['success'=>'Žádost je nyní ve výchozím stavu "Odesláno".','fail' => '']);
        }else if($disease->disease_state == 1){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu odeslání nachází!']);
        }
    }

    public function destroy($id){
        $disease = Disease::findOrFail($id)->delete();
        return response()->json(['success'=>'Nemocenská byla úspěšně smazána.','fail' => '']);
    }
}
