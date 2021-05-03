<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use App\Models\Report;
use App\Models\Report_Importance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller {
    /* Nazev souboru:  ReportsController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy nahlaseni v uctu s roli firmy. Slouzi take k ovladani datove tabulky.

   Nazvy jednotlivych metod jsou konvenci frameworku laravel, viz https://laravel.com/docs/8.x/controllers
   Pro nauceni prace s datovymi tabulkami yajra slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
   Knihovna Yajra pro datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
   Licence k Yajra datovym tabulkam:
    (The MIT License)
    Copyright (c) 2013-2020 Arjay Angeles aqangeles@gmail.com
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction,
    including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
    subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
        IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
         WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

    /* Nazev funkce: index
       Argumenty: zadne
       Ucel: Zobrazeni prislusneho pohledu pro seznam nahlaseni */
    public function index(){
        $user = Auth::user();
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
        Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        /* Ziskani moznych dulezitosti nahlaseni */
        $importances_options = Report_Importance::getAllReportsImportancesOptions();
        /* Odeslani pohledu spolecne se ziskanymi daty do uzivatelova prohlizece */
        return view('company_actions.reports_list')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('dulezitosti',$importances_options)
            ->with('zamestnanci',$zamestnanci);
    }

    /* Nazev funkce: getEmployeeReports
       Argumenty: zadne
       Ucel: Zobrazeni seznamu nahlaseni v datove tabulce */
    public function getEmployeeReports(){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        /* Ziskani nahlaseni */
        $reports = Report::getCompanyEmployeesReports($user->company_id);
        /* Usek kodu slouzici k vyrenderovani datove tabulky */
        return Datatables::of($reports)
            ->addIndexColumn()
            ->editColumn('report_state', function($reports){  // usek kodu slouzici k zobrazeni stavu nahlaseni
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
            ->addColumn('action', function($reports){ // Definice jednotlivych ovladacich tlacitek
                return '<button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#AgreementReportForm" id="obtainReportAgreement" class="btn btn-dark btn-sm" ><i class="fa fa-check" aria-hidden="true"></i> Schválit</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DisagreementReportForm" id="obtainReportDisagreement" class="btn btn-dark btn-sm" ><i class="fa fa-times" aria-hidden="true"></i> Neschválit</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#SeenReportForm" id="obtainReportSeen" class="btn btn-dark btn-sm tlacitkoPrectenoNahlaseni"><i class="fa fa-book" aria-hidden="true"></i> Přečteno</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#SentReportForm" id="obtainReportSent" class="btn btn-dark btn-sm tlacitkoOdeslanoNahlaseni"><i class="fa fa-paper-plane" aria-hidden="true"></i> Odesláno</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#EditReportForm" id="obtainEditReport" class="btn btn-primary btn-sm" style="margin-top:6px;"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DeleteReportForm" id="obtainReportDelete" class="btn btn-danger btn-sm" style="margin-top:6px;">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
            })
            ->rawColumns(['action', 'report_state']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: store
       Argumenty: request - udaje zadane firmou pro tvorbu noveho nahlaseni
       Ucel: Vytvoreni noveho nahlaseni */
    public function store(Request $request){
        /* Definice pravidel pro validaci a nasledne jeji provedeni */
        $validator = Validator::make($request->all(), ['nazev_nahlaseni' => ['required'], 'popis_nahlaseni' =>  ['required', 'max:180'], 'zamestnanec_vyber' =>  ['required']]);
        /* Pokud je libovolny udaj/e inkorektni, jsou uzivatelovi zobrazeny chybove hlasky */
        if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]); }
        /* Ziskani konkretniho zamestnance */
        $zamestnanec = Employee::find($request->zamestnanec_vyber);
        /* Pokud je dulezitost null tak je automaticky nespecifikovana */
        if($request->dulezitost_nahlaseni == NULL){ $request->dulezitost_nahlaseni = 6; }

        /* Vytvoreni nahlaseni v databazi */
        Report::create(['report_title' => $request->nazev_nahlaseni, 'report_description' => $request->popis_nahlaseni, 'report_importance_id' => $request->dulezitost_nahlaseni, 'report_state' => 1, 'employee_id' => $request->zamestnanec_vyber]);

        /* Odeslani odpovedi uzivateli */
        return response()->json(['success'=>'Nahlášení zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' bylo úspešně vytvořeno.']);
    }

    /* Nazev funkce: edit
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Zobrazeni obsahu pro upravu konkretniho nahlaseni */
    public function edit($id){
        /* Ziskani nahlaseni */
        $report = Report::find($id);
        /* Ziskani vsech dulezitosti a nasledne ziskani aktualni dulezitosti nahlaseni */
        $importances_options = Report_Importance::getAllReportsImportancesOptionsWithUnspecified();
        $importance_actual = Report_Importance::getConcreteImportance($report->report_importance_id);
        /* Naplneni dulezitosti do selectboxu */
        $moznosti_html = '';
        /* Ulozeni aktualni dulezitosti nahlaseni do promenne moznosti_html, diky tomu bude aktualni moznost v selectboxu vzdy prvni */
        $moznosti_html .= '<option value = "'.$importance_actual->importance_report_id.'">'.$importance_actual->importance_report_description.'</option >';
        /* Iterace skrze dulezitosti */
        foreach($importances_options as $dulezitost) {
            if($importance_actual->importance_report_id != $dulezitost->importance_report_id){ // pokud se dulezitost nerovna aktualni tak se prida do selectboxu
                $moznosti_html .= '<option value = "'.$dulezitost->importance_report_id.'">'.$dulezitost->importance_report_description.'</option >';
            }
        }
        /* Zmena formatu datumu vytvoreni a aktualizace */
        $created_at = date('d.m.Y H:i:s', strtotime($report->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($report->updated_at));
        $stav = '';
        /* Zobrazeni stavu nahlaseni na zaklade jeho hodnoty */
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
        /* Ulozeni obsahu (formulare) do promenne */
        $out = ''.$stav.'
                 <div class="form-group">
                        <div class="row">
                            <label for="nazev_nahlaseni_edit" class="col-md-2 text-left">Nadpis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input placeholder="Zadejte, čeho se nahlášení týká..." type="text" class="form-control" id="nazev_nahlaseni_edit" name="nazev_nahlaseni_edit" value="'.$report->report_title.'" autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                   </div>
                   <div class="form-group">
                        <div class="row">
                            <label for="popis_nahlaseni_edit" class="col-md-2 text-left">Popis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                    </div>
                                    <textarea placeholder="Zadejte popis nahlášení... [maximálně 180 znaků]" name="popis_nahlaseni_edit" id="popis_nahlaseni_edit" class="form-control" autocomplete="on">'.$report->report_description.'</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="dulezitost_nahlaseni_edit" class="col-md-2 text-left">Důležitost</label>
                            <div class="col-md-10">
                                <select name="dulezitost_nahlaseni_edit" id="dulezitost_nahlaseni_edit" style="color:black;text-align-last: center;" class="form-control">'.$moznosti_html.'</select>
                            </div>
                        </div>
                    </div>
                    <p class="d-flex justify-content-center">Nahlášení vytvořeno '.$created_at.', naposledy aktualizováno '.$updated_at.'.</p>';
        /* Zaslani HTML obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator nahlaseni, request - zadane udaje
       Ucel: Aktualizace zadanych udaju v ramci upravy nahlaseni */
    public function update(Request $request, $id){
        /* Ziskani nahlaseni */
        $report = Report::find($id);
        /* Pravidla pro validaci */
        $validator = Validator::make($request->all(), ['nazev_nahlaseni' => ['required'], 'popis_nahlaseni' =>  ['required','max:180'],]);
        /* Poslani chybovych hlasek uzivateli */
        if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]); }

        /* Usek kodu, diky kteremu se zjisti, zdali doslo ke zmene udaju nahlaseni */
        $jeZmena = 0;
        if(($report->report_title == $request->nazev_nahlaseni) && ($report->report_description == $request->popis_nahlaseni)
            && ($report->report_importance_id == $request->dulezitost_nahlaseni)){
            $jeZmena = 0;
        }else{
            $jeZmena = 1;
        }

        /* Aktualizace udaju konkretniho nahlaseni v databazi */
        Report::where(['report_id' => $report->report_id])->update(['report_title' => $request->nazev_nahlaseni, 'report_description' => $request->popis_nahlaseni, 'report_importance_id' => $request->dulezitost_nahlaseni]);

        /* Pokud uzivatel nezmenil zadny udaj, nic mu neni zobrazeno, pokud zmenil libovolny udaj je mu zobrazena zprava o uspechu aktualizace udaju */
        if($jeZmena != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Nahlášení bylo úspěšně zaktualizováno.']);
        }
    }

    /* Nazev funkce: reportAgree
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Zmena stavu nahlaseni na stav "Schvaleno" */
    public function reportAgree($id){
        $report = Report::find($id);
        if($report->report_state == 1 || $report->report_state == 3 || $report->report_state == 4){
            $report->report_state = 2;
            $report->save();
            return response()->json(['success'=>'Schválení nahlášení proběhlo úspěšně.','fail' => '']);
        }else if($report->report_state == 2){
            return response()->json(['success'=>'','fail' => 'Už jste nahlášení schválil!']);
        }
    }

    /* Nazev funkce: reportDisagree
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Zmena stavu nahlaseni na stav "Neschvaleno" */
    public function reportDisagree($id){
        $report = Report::find($id);
        if($report->report_state == 3){
            return response()->json(['success'=>'','fail' => 'Nelze neschválit nahlášení, která již neschváleno bylo!']);
        }else if($report->report_state == 1 || $report->report_state == 2 || $report->report_state == 4){
            $report->report_state = 3;
            $report->save();
            return response()->json(['success'=>'Neschválení nahlášení proběhlo úspěšně.','fail' => '']);
        }
    }

    /* Nazev funkce: reportSeen
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Zmena stavu nahlaseni na stav "Precteno" */
    public function reportSeen($id){
        $report = Report::find($id);
        if($report->report_state == 1 || $report->report_state == 2 || $report->report_state == 3){
            $report->report_state = 4;
            $report->save();
            return response()->json(['success'=>'Nahlášení je nyní ve stavu přečteno.','fail' => '']);
        }else if($report->report_state == 4){
            return response()->json(['success'=>'','fail' => 'Nahlášení se už ve stavu přečtení nachází!']);
        }
    }

    /* Nazev funkce: reportSent
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Zmena stavu nahlaseni na stav "Odeslano" */
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

    /* Nazev funkce: destroy
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Smazani nahlaseni */
    public function destroy($id){
        Report::find($id)->delete();
        return response()->json(['success'=>'Nahlášení bylo úspěšně smazáno.','fail' => '']);
    }

}
