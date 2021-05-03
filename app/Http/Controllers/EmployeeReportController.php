<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Report_Importance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EmployeeReportController extends Controller {
    /* Nazev souboru:  EmployeeReportController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy nahlaseni v uctu s roli zamestnance. Ovladani datove tabulky je tu taktez naprogramovano.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni spravy nahlaseni konkretniho zamestnance */
    public function index(){
        $user = Auth::user();
        /* Ziskani vsech moznych dulezitosti nahlaseni (pro formular vytvareni nahlaseni)*/
        $importances_options = Report_Importance::getAllReportsImportancesOptions();
        return view('employee_actions.reports_list')
            ->with('profilovka',$user->employee_picture)
            ->with('employee_url', $user->employee_url)
            ->with('dulezitosti',$importances_options);
    }

    /* Nazev funkce: getEmployeeReports
       Argumenty: zadne
       Ucel: Zobrazeni datove tabulky pro spravu nahlaseni */
    public function getEmployeeReports(){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        /* Ziskani nahlaseni */
        $reports = Report::getEmployeeReports($user->employee_id);
        /* Vyrenderovani datove tabulky */
        return Datatables::of($reports)
            ->addIndexColumn()
            ->editColumn('report_state', function($reports){ // uprava sloupce pro zobrazeni stavu nahlaseni
                if($reports->report_state == 0){
                    return '<p style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nenahlášeno</p>';
                }else if($reports->report_state == 1){
                    return '<p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p>';
                }else if($reports->report_state == 2){
                    return '<p style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p>';
                }else if($reports->report_state == 3){
                    return '<p style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p>';
                }else if($reports->report_state == 4){
                    return '<center><p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p></center>';
                }
            })
            ->addColumn('action', function($reports){ // definice ovladacich tlacitek datove tabulky
                return '<button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#ApplyReportForm" id="obtainReportApply" class="btn btn-dark btn-sm"><i class="fa fa-bullhorn" aria-hidden="true"></i> Odeslat</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#EditReportForm" id="obtainEditReport" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editovat</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DeleteApplyReportForm" id="obtainReportDeleteApply" class="btn btn-dark btn-sm"><i class="fa fa-times" aria-hidden="true"></i> Zrušit odeslání</button>
                        <button type="button" data-id="'.$reports->report_id.'" data-toggle="modal" data-target="#DeleteReportForm" id="obtainReportDelete" class="btn btn-danger btn-sm tlacitkoSmazatNahlaseni">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
            })
            ->rawColumns(['action', 'report_state']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: store
       Argumenty: request - udaje zapsane zamestnancem
       Ucel: Ulozeni nahlaseni zamestnance do databaze */
    public function store(Request $request){
        $user = Auth::user();
        /* Definice pravidel pro validaci a jeji provedeni */
        $validator = Validator::make($request->all(), ['nazev_nahlaseni' => ['required', 'string'], 'popis_nahlaseni' =>  ['required', 'string', 'max:180']]);
        /* Pri zadani inkorektnich udaju zaslani chybove zpravy uzivateli */
        if ($validator->fails()) { return response()->json(['fail' => $validator->errors()->all()]); }
        /* Pokud neni vybrana dulezitost je automaticky nespecifikovana */
        if($request->dulezitost_nahlaseni == NULL){$request->dulezitost_nahlaseni = 6;}
        /* Vytvoreni nahlaseni v databazi */
        Report::create(['report_title' =>$request->nazev_nahlaseni, 'report_description' => $request->popis_nahlaseni, 'report_importance_id' => $request->dulezitost_nahlaseni, 'report_state' => 0, 'employee_id' => $user->employee_id]);
        /* Odeslani zpravy o uspechu uzivateli */
        return response()->json(['success' => 'Nahlášení bylo úspešně vytvořeno.']);
    }

    /* Nazev funkce: edit
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Zobrazeni obsahu (formulare) modalniho okna */
    public function edit($id){
        /* Ziskani konkretniho nahlaseni */
        $report = Report::find($id);
        /* Ziskani moznosti dulezitosti nahlaseni */
        $importances_options = Report_Importance::getAllReportsImportancesOptionsWithUnspecified();
        /* Ziskani aktualni dulezitosti nahlaseni */
        $importance_actual = Report_Importance::getConcreteImportance($report->report_importance_id);
        /* Ulozeni moznosti do HTML promenne */
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
        /* Datum vytvoreni a posledni aktualizace nahlasen */
        $created_at = date('d.m.Y H:i:s', strtotime($report->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($report->updated_at));
        $stav = '';
        /* Definice stavu nahlaseni a nasledne ulozeni do promenne stav */
        if($report->report_state == 0){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:whitesmoke;">Nezažádáno</p></div></center>';
        }else if($report->report_state == 1){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Odesláno</p></div></center>';
        }else if($report->report_state == 2){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Schváleno</p></div></center>';
        }else if($report->report_state == 3){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Neschváleno</p></div></center>';
        }else if($report->report_state == 4){
            $stav = '<center><p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p></center>';
        }
        /* Definice obsahu modalniho okna */
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
                                <textarea placeholder="Zadejte popis nahlášení [maximálně 180 znaků]..." name="popis_nahlaseni_edit" id="popis_nahlaseni_edit" class="form-control" autocomplete="on">'.$report->report_description.'</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="dulezitost_nahlaseni_edit" class="col-md-2 text-left">Důležitost</label>
                        <div class="col-md-10">
                            <select name="dulezitost_nahlaseni_edit" id="dulezitost_nahlaseni_edit" style="color:black;text-align-last: center;" class="form-control">
                                '.$moznosti_html.'
                            </select>
                        </div>
                    </div>
                 </div>
                 <p class="d-flex justify-content-center">Nahlášení vytvořeno '.$created_at.', naposledy aktualizováno '.$updated_at.'.</p>';
        /* Odeslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator nahlaseni, request - udaje zadane zamestnancem
       Ucel: Aktualizace nahlaseni v databazi */
    public function update(Request $request, $id){
        /* Ziskani konkretniho nahlaseni */
        $report = Report::find($id);
        if($report->report_state == 0) { // nahlaseni se da upravovat jen pokud nebylo odeslano
            /* Definice pravidel pro validaci a jeji provedeni */
            $validator = Validator::make($request->all(), ['nazev_nahlaseni' => ['required', 'string'], 'popis_nahlaseni' =>  ['required', 'string', 'max:180']]);
            /* V pripade libovolneho inkorektniho udaje je uzivatelovi zaslana chybova zprava */
            if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]); }
            $jeZmena = 0;
            if (($report->report_title == $request->nazev_nahlaseni) && ($report->report_description == $request->popis_nahlaseni)
                && ($report->report_importance_id == $request->dulezitost_nahlaseni)) {
                $jeZmena = 0;
            } else {
                $jeZmena = 1;
            }
            /* Aktualizace udaju konkretni nemocenske v databazi */
            Report::where(['report_id' => $report->report_id])->update(['report_title' => $request->nazev_nahlaseni, 'report_description' => $request->popis_nahlaseni, 'report_importance_id' => $request->dulezitost_nahlaseni]);
            /* Paklize se neodehrala zadna zmena, tak se uzivateli nic nezobrazi*/
            if ($jeZmena != 1) {
                return response()->json(['success' => '0']);
            } else {
                return response()->json(['success' => 'Nahlášení bylo úspěšně zaktualizováno.']);
            }
        }
        /* Odeslani chybove zpravy v pripade pokusu editovani nahlaseni, ktere neni ve stavu "Nenahlášeno" */
        return response()->json(['fail'=>'Upravovat lze pouze ty nahlášení, které nebyly poslány, pokud chcete nahlášení upravit při stavu "Odesláno" (při ostatních to nelze) klikněte na tlačítko "Zrušit odeslání", pak lze nahlášení dále upravovat.', 'success' => '']);
    }

    /* Nazev funkce: reportApply
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Odeslani nahlaseni firme */
    public function reportApply($id){
        /* Ziskani konkretniho nahlaseni */
        $report = Report::find($id);
        /* Pokud je nahlaseni ve stavu "Nenahlášeno", lze ho nahlasit, v jinych stavech to mozne neni */
        if($report->report_state == 0){
            $report->report_state = 1;
            $report->save();
            return response()->json(['success'=>'Zaslání nahlášení proběhlo úspěšně.','fail' => '']);
        }else if($report->report_state == 1){
            return response()->json(['success'=>'','fail' => 'Toto nahlášení už jste poslal!']);
        }
        return response()->json(['success'=>'','fail' => 'Nelze znovu poslat nahlášení, které bylo již schváleno, či neschváleno!']);
    }

    /* Nazev funkce: reportDeleteApply
       Argumenty: id - jednoznacny identifikator nahlaseni
       Ucel: Zruseni odeslani nahlaseni firme */
    public function reportDeleteApply($id){
        /* Ziskani konkretniho nahlaseni */
        $report = Report::find($id);
        /* Pokud je nahlaseni ve stavu "Nenahlášeno", tak odeslani nelze zrusit stejne to plati u schvalenych ci neschvalenych nahlaseni. */
        if($report->report_state == 0){
            return response()->json(['success'=>'','fail' => 'Nelze zrušit odeslání, pokud nahlášení nebylo odeslané!']);
        }else if($report->report_state == 1){
            $report->report_state = 0;
            $report->save();
            return response()->json(['success'=>'Zrušení odeslání nahlášení proběhlo úspěšně.','fail' => '']);
        }
        return response()->json(['success'=>'','fail' => 'Nelze zrušit odeslání nahlášení, které bylo již schváleno, či neschváleno!']);
    }

    /* Nazev funkce: destroy
      Argumenty: id - jednoznacny identifikator nahlaseni
      Ucel: Odstraneni nahlaseni zamestnance */
    public function destroy($id){
        /* Ziskani konkretniho nahlaseni */
        $report = Report::findOrFail($id);
        if($report->report_state == 0){ //smazat lze jen ty nahlaseni ve stavu "Nenahlášeno"
            Report::find($id)->delete();
            return response()->json(['success'=>'Nahlášení bylo úspěšně smazáno.','fail' => '']);
        }
        return response()->json(['fail'=>'Smazat lze pouze ty nahlášení, které nebyly poslány, pokud chcete nahlášení smazat při stavu "Odesláno" (při ostatních to nelze) klikněte na tlačítko "Zrušit odeslání", pak lze nahlášení smazat.', 'success' => '']);
    }

}
