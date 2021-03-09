<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Report_Importance;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EmployeeReportController extends Controller
{
    public function index(){
        $user = Auth::user();
        $importances_options = Report_Importance::getAllReportsImportancesOptions();
        return view('employee_actions.reports_list')
            ->with('profilovka',$user->employee_picture)
            ->with('dulezitosti',$importances_options);
    }

    public function getEmployeeReports(Request $request){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        if ($request->ajax()) {
            $reports = Report::getEmployeeReports($user->employee_id);
            return Datatables::of($reports)
                ->addIndexColumn()
                ->editColumn('report_state', function($reports){
                    if($reports->report_state == 0){
                        return '<p style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nenahlášeno</p>';
                    }else if($reports->report_state == 1){
                        return '<p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p>';
                    }else if($reports->report_state == 2){
                        return '<p style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p>';
                    }else if($reports->report_state == 3){
                        return '<p style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p>';
                    }
                })
                ->addColumn('action', function($reports){
                    return '<button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#ApplyReportModal" class="btn btn-dark btn-sm" id="getReportApply"><i class="fa fa-bullhorn" aria-hidden="true"></i> Poslat</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#EditReportModal" class="btn btn-primary btn-sm" id="getEditReport"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editovat</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DeleteApplyReportModal" class="btn btn-dark btn-sm" id="getReportDeleteApply"><i class="fa fa-times" aria-hidden="true"></i> Zrušit odeslání</button>
                            <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DeleteReportModal" class="btn btn-danger btn-sm" style="margin-top:6px;" id="getReportDelete">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
                })
                ->rawColumns(['action', 'report_state'])
                ->make(true);
        }
    }

    public function store(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'nazev_nahlaseni' => ['required'],
            'popis_nahlaseni' =>  ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if($request->dulezitost_nahlaseni == NULL){
            $request->dulezitost_nahlaseni = 6;
        }
        $new_report = new Report();
        $new_report->report_title = $request->nazev_nahlaseni;
        $new_report->report_description = $request->popis_nahlaseni;
        $new_report->report_importance_id = $request->dulezitost_nahlaseni;
        $new_report->report_state = 0;
        $new_report->employee_id = $user->employee_id;
        $new_report->save();

        return response()->json(['success'=>'Nahlášení bylo úspešně vytvořeno.']);
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
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Schváleno</p></div></center>';
        }else if($report->report_state == 3){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Neschváleno</p></div></center>';
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
        if($report->report_state == 0) {
            $validator = Validator::make($request->all(), [
                'nazev_nahlaseni_edit' => ['required'],
                'popis_nahlaseni_edit' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            $bool = 0;
            if (($report->report_title == $request->nazev_nahlaseni_edit) && ($report->report_description == $request->popis_nahlaseni_edit)
                && ($report->report_importance_id == $request->dulezitost_nahlaseni_edit)) {
                $bool = 0;
            } else {
                $bool = 1;
            }

            $report->report_title = $request->nazev_nahlaseni_edit;
            $report->report_description = $request->popis_nahlaseni_edit;
            $report->report_importance_id = $request->dulezitost_nahlaseni_edit;
            $report->save();

            if ($bool != 1) {
                return response()->json(['success' => '0']);
            } else {
                return response()->json(['success' => 'Nahlášení bylo úspěšně zaktualizováno.']);
            }
        }
        return response()->json(['fail'=>'Upravovat lze pouze ty nahlášení, které nebyly poslány, pokud chcete nahlášení upravit při stavu "Odesláno" (při ostatních to nelze) klikněte na tlačítko "Zrušit odeslání", pak lze nahlášení dále upravovat.', 'success' => '']);
    }

    public function reportApply($id){
        $report = Report::findOrFail($id);
        if($report->report_state == 0){
            $report->report_state = 1;
            $report->save();
            return response()->json(['success'=>'Zaslání nahlášení proběhlo úspěšně.','fail' => '']);
        }else if($report->report_state == 1){
            return response()->json(['success'=>'','fail' => 'Toto nahlášení už jste poslal!']);
        }
        return response()->json(['success'=>'','fail' => 'Nelze znovu poslat nahlášení, které bylo již schváleno, či neschváleno!']);
    }

    public function reportDeleteApply($id){
        $report = Report::findOrFail($id);
        if($report->report_state == 0){
            return response()->json(['success'=>'','fail' => 'Nelze zrušit odeslání, pokud nahlášení nebylo odeslané!']);
        }else if($report->report_state == 1){
            $report->report_state = 0;
            $report->save();
            return response()->json(['success'=>'Zrušení odeslání nahlášení proběhlo úspěšně.','fail' => '']);
        }
        return response()->json(['success'=>'','fail' => 'Nelze zrušit odeslání nahlášení, které bylo již schváleno, či neschváleno!']);
    }

    public function destroy($id){
        $report = Report::findOrFail($id);
        if($report->report_state == 0){
            $report = Report::findOrFail($id)->delete();
            return response()->json(['success'=>'Nahlášení bylo úspěšně smazáno.','fail' => '']);
        }
        return response()->json(['fail'=>'Smazat lze pouze ty nahlášení, které nebyly poslány, pokud chcete nahlášení smazat při stavu "Odesláno" (při ostatních to nelze) klikněte na tlačítko "Zrušit odeslání", pak lze nahlášení smazat.', 'success' => '']);
    }
}
