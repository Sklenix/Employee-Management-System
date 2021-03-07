<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Languages;
use App\Models\Report;
use App\Models\Report_Importance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->whereIn('table_importances_shifts.importance_id',[1,2,3,4,5])
            ->get();
        $importances_options = Report_Importance::getAllReportsImportancesOptions();

        return view('company_actions.reports_list')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('dulezitosti',$importances_options)
            ->with('zamestnanci',$zamestnanci);
    }

    public function getEmployeeReports(Request $request){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        if ($request->ajax()) {
            $reports = Report::getCompanyEmployeesReports($user->company_id);
            return Datatables::of($reports)
                ->addIndexColumn()
                ->editColumn('report_state', function($reports){
                    if($reports->report_state == 0){
                        return '<center><p class="col-md-10" style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nenahlášeno</p></center>';
                    }else if($reports->report_state == 1){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p></center>';
                    }else if($reports->report_state == 2){
                        return '<center><p class="col-md-10" style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p></center>';
                    }else if($reports->report_state == 3){
                        return '<center><p class="col-md-10" style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p></center>';
                    }else if($reports->report_state == 4){
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p></center>';
                    }
                })
                ->addColumn('action', function($reports){
                    return '<button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#AgreementReportModal" class="btn btn-dark btn-sm" id="getReportAgreement"><i class="fa fa-check" aria-hidden="true"></i> Schválit</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DisagreementReportModal" class="btn btn-dark btn-sm" id="getReportDisagreement"><i class="fa fa-times" aria-hidden="true"></i> Neschválit</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#SeenReportModal" class="btn btn-dark btn-sm" style="margin-top:6px;" id="getReportSeen"><i class="fa fa-book" aria-hidden="true"></i> Přečteno</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#SentReportModal" class="btn btn-dark btn-sm" style="margin-top:6px;" id="getReportSent"><i class="fa fa-paper-plane" aria-hidden="true"></i> Odesláno</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#EditReportModal" class="btn btn-primary btn-sm" style="margin-top:6px;" id="getEditReport"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DeleteReportModal" class="btn btn-danger btn-sm" style="margin-top:6px;" id="getReportDelete">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
                })
                ->rawColumns(['action', 'report_state'])
                ->make(true);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nazev_nahlaseni' => ['required'],
            'popis_nahlaseni' =>  ['required'],
            'zamestnanec_vyber' =>  ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $zamestnanec = Employee::findOrFail($request->zamestnanec_vyber);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if($request->dulezitost_nahlaseni == NULL){
            $request->dulezitost_nahlaseni = 6;
        }

        $new_report = new Report();
        $new_report->report_title = $request->nazev_nahlaseni;
        $new_report->report_description = $request->popis_nahlaseni;
        $new_report->report_importance_id = $request->dulezitost_nahlaseni;
        $new_report->report_state = 1;
        $new_report->employee_id = $request->zamestnanec_vyber;
        $new_report->save();

        return response()->json(['success'=>'Nahlášení zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' bylo úspešně vytvořeno.']);
    }

    public function edit($id){
        $report = Report::findOrFail($id);
        $importances_options = Report_Importance::getAllReportsImportancesOptionsWithUnspecified();
        $importance_actual = Report_Importance::getConcreteImportance($report->report_importance_id);
        $moznosti_html = '';
        $moznosti_html .= '<option value = "'.$importance_actual->importance_report_id.'">
                                    '.$importance_actual->importance_report_description.'
                                </option >';
        foreach($importances_options as $dulezitost) {
            if($importance_actual->importance_report_id != $dulezitost->importance_report_id){
                $moznosti_html .= '<option value = "'.$dulezitost->importance_report_id.'">
                                    '.$dulezitost->importance_report_description.'
                                </option >';
            }
        }
        $created_at = date('d.m.Y H:i:s', strtotime($report->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($report->updated_at));
        $stav = '';
        if($report->report_state == 0){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:whitesmoke;">Nezažádáno</p></div></center>';
        }else if($report->report_state == 1){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Odesláno</p></div></center>';
        }else if($report->report_state == 2){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellowgreen;">Schváleno</p></div></center>';
        }else if($report->report_state == 3){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:orangered;">Neschváleno</p></div></center>';
        }else if($report->report_state == 4){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Přečteno</p></div></center>';
        }
        $html = ''.$stav.'
                          <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left">Nadpis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input placeholder="Zadejte, čeho se nahlášení týká..." type="text" class="form-control" id="nazev_nahlaseni_edit" name="nazev_nahlaseni_edit" value="'.$report->report_title.'"  autocomplete="nazev_nahlaseni_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left">Popis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                    </div>
                                    <textarea placeholder="Zadejte popis nahlášení..." name="popis_nahlaseni_edit" id="popis_nahlaseni_edit" class="form-control" autocomplete="popis_nahlaseni_edit">'.$report->report_description.'</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left">Důležitost</label>
                            <div class="col-md-10">
                                <select name="dulezitost_nahlaseni_edit" id="dulezitost_nahlaseni_edit" style="color:black;text-align-last: center;" class="form-control">
                                    '.$moznosti_html.'
                                </select>
                            </div>
                        </div>
                    </div>
                    <p class="d-flex justify-content-center">Nahlášení vytvořeno '.$created_at.', naposledy aktualizováno '.$updated_at.'.</p>
                    </div>';
        return response()->json(['html'=>$html]);
    }

    public function update(Request $request, $id){
        $report = Report::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nazev_nahlaseni_edit' => ['required'],
            'popis_nahlaseni_edit' =>  ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $bool = 0;
        if(($report->report_title == $request->nazev_nahlaseni_edit) && ($report->report_description == $request->popis_nahlaseni_edit)
            && ($report->report_importance_id == $request->dulezitost_nahlaseni_edit)){
            $bool = 0;
        }else{
            $bool = 1;
        }

        $report->report_title = $request->nazev_nahlaseni_edit;
        $report->report_description = $request->popis_nahlaseni_edit;
        $report->report_importance_id = $request->dulezitost_nahlaseni_edit;
        $report->save();

        if($bool != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Nahlášení bylo úspěšně zaktualizováno.']);
        }
    }

    public function reportAgree($id){
        $report = Report::findOrFail($id);
        if($report->report_state == 1 || $report->report_state == 3 || $report->report_state == 4){
            $report->report_state = 2;
            $report->save();
            return response()->json(['success'=>'Schválení nahlášení proběhlo úspěšně.','fail' => '']);
        }else if($report->report_state == 2){
            return response()->json(['success'=>'','fail' => 'Už jste nahlášení schválil!']);
        }
    }

    public function reportDisagree($id){
        $report = Report::findOrFail($id);
        if($report->report_state == 3){
            return response()->json(['success'=>'','fail' => 'Nelze neschválit nahlášení, která již neschváleno bylo!']);
        }else if($report->report_state == 1 || $report->report_state == 2 || $report->report_state == 4){
            $report->report_state = 3;
            $report->save();
            return response()->json(['success'=>'Neschválení nahlášení proběhlo úspěšně.','fail' => '']);
        }
    }

    public function reportSeen($id){
        $report = Report::findOrFail($id);
        if($report->report_state == 1 || $report->report_state == 2 || $report->report_state == 3){
            $report->report_state = 4;
            $report->save();
            return response()->json(['success'=>'Nahlášení je nyní ve stavu přečteno.','fail' => '']);
        }else if($report->report_state == 4){
            return response()->json(['success'=>'','fail' => 'Nahlášení se už ve stavu přečtení nachází!']);
        }
    }

    public function reportSent($id){
        $report = Report::findOrFail($id);
        if($report->report_state == 2 || $report->report_state == 3 || $report->report_state == 4){
            $report->report_state = 1;
            $report->save();
            return response()->json(['success'=>'Nahlášení je nyní ve výchozím stavu "Odesláno".','fail' => '']);
        }else if($report->report_state == 1){
            return response()->json(['success'=>'','fail' => 'Nahlášení už se ve stavu odeslání nachází!']);
        }
    }

    public function destroy($id){
        Report::findOrFail($id)->delete();
        return response()->json(['success'=>'Nahlášení bylo úspěšně smazáno.','fail' => '']);
    }

}
