<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Injury;
use App\Models\Languages;
use App\Models\Shift;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InjuriesDatatableController extends Controller {
    /* Nazev souboru:  InjuriesDatatableController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy zraneni v uctu s roli firmy.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni spravy zraneni konkretni firmy */
    public function index(){
        $user = Auth::user();
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
         Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        /* Ziskani vsech zamestnancu firmy pro selectbox*/
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        /* Zaslani pohledu spolecne se ziskanymi daty uzivateli */
        return view('company_actions.injuries_list')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    /* Nazev funkce: getInjuries
       Argumenty: zadne
       Ucel: Zobrazeni datove tabulky reprezentujici seznam zraneni zamestnancu firmy */
    public function getInjuries(){
        $user = Auth::user();
        /* Ziskani vsech zraneni */
        $injury = Injury::getInjuries($user->company_id);
        /* Sekce kodu pro vyrenderovani datove tabulky */
        return Datatables::of($injury)
            ->addIndexColumn()
            ->addColumn('action', function($injury){ // definice ovladacich tlacitek datove tabulky
                return '<button type="button" data-id="'.$injury->injury_id.'" class="btn btn-primary btn-sm" id="obtainEditInjury" data-toggle="modal"  data-target="#EditInjuryForm"><i class="fa fa-pencil-square-o"></i> Editovat</button>
                        <button type="button" data-id="'.$injury->injury_id.'" data-toggle="modal" id="obtainDeleteInjury" data-target="#DeleteInjuryForm" class="btn btn-danger btn-sm">&nbsp;<i class="fa fa-trash-o"></i> Smazat&nbsp;&nbsp;</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /* Nazev funkce: getEmployeeShiftsSelect
       Argumenty: zadne
       Ucel: ziskani zamestnancovych smen a vykresleni jich do selectboxu */
    public function getEmployeeShiftsSelect(Request $request){
        /* Ziskani smen pro selectbox */
        $smeny = Shift::getEmployeeShifts($request->employee_id);
        $out = '<option value="">Vyberte směnu</option>';
        /* Iterace skrze smeny a vkladani datumu zacatku a konce jednotlivych smen do moznosti selectboxu */
        foreach($smeny as $smena){
            /* Zmena formatu datumu */
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $out .= '<option value="'.$smena->shift_id.'">'.$smena->shift_start.' - '.$smena->shift_end.'</option>';
        }
        /* Odeslani html kodu uzivateli */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: getShiftStart
       Argumenty: zadne
       Ucel: Ziskani zacatku smeny, tento udaj se automaticky vyplni jako datum zraneni ve formulari */
    public function getShiftStart($shift_id){
        /* Ziskani smeny */
        $smena = Shift::find($shift_id);
        /* Zmena formatu pro ucely vlozeni datumu do datetime-local inputu */
        $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
        /* Odeslani do uzivatelova prohlizece */
        return response()->json(['shift_start' => $datumStart]);
    }

    /* Nazev funkce: store
      Argumenty: request - udaje zadane firmou
      Ucel: Ulozeni zraneni do databaze */
    public function store(Request $request){
        $user = Auth::user();
        /* Definice pravidel pro validaci a nasledne jeji provedeni */
        $validator = Validator::make($request->all(), ['zamestnanec' => ['required'], 'smena' =>  ['required'], 'datum_zraneni' =>  ['required'], 'popis_zraneni' => ['max:180']]);
        /* Pokud byl zadan inkorektni udaj, je uzivatelovi zaslana chybova hlaska */
        if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]); }
        /* Ziskani smeny, na ktere vzniklo zraneni */
        $smenaUdaje = Shift::getConcreteShift($request->smena);
        /* Ziskani datumu zraneni a nasledna validace, zdali nedoslo ke zraneni drive nez na zacatku smeny */
        $injury_date = new DateTime($request->datum_zraneni);
        $shift_start = new DateTime($smenaUdaje[0]->shift_start);
        $sekundy = 1200; // 20 minut - maximalni doba pro vytvoreni zraneni pred zacatkem smeny
        $difference_start = $injury_date->format('U') - ($shift_start->format('U') - $sekundy);
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0){
            array_push($chybaDatumy,'Datum zranění dříve než začátek směny o více než 20 minut!');
            $bool_datumy = 1;
        }
        /* Naplneni chybovych hlasek do pole */
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        /* Odeslani chyb uzivateli */
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }
        /* Vytvoreni zraneni v databazi */
        Injury::create(['injury_description' => $request->popis_zraneni, 'injury_date' => $request->datum_zraneni, 'employee_id' => $request->zamestnanec, 'shift_id' => $request->smena]);
        /* Ziskani ID smeny z analyticke sekce OLAP */
        $shift_info_id = OlapETL::getShiftInfoId($request->zamestnanec, $user->company_id, $smenaUdaje[0]->shift_start, $smenaUdaje[0]->shift_end);
        /* Oznaceni, ze doslo k zraneni v ramci OLAP sekce systemu */
        OlapETL::aggregateEmployeeInjuryFlag($shift_info_id, $request->zamestnanec, $user->company_id, 1);
        /* Odeslani odpovedi */
        return response()->json(['success'=>'Zranění bylo úspešně vytvořeno.']);
    }

    /* Nazev funkce: edit
      Argumenty: id - jednoznacny identifikator zraneni
      Ucel: Vytvoreni obsahu, ktery bude nasledne poslan do modalniho okna */
    public function edit($id){
        /* Ziskani konkretniho zraneni */
        $injury = Injury::find($id);
        /* Zmena formatu zraneni na format datetime-local inputu */
        $datumZraneni = date('Y-m-d\TH:i', strtotime($injury->injury_date));
        /* Definice obsahu modalniho okna */
        $out = ' <div class="form-group">
                        <div class="row">
                            <label for="injury_date_edit" class="col-md-2 text-left" style="font-size: 16px;">Datum zranění(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="injury_date_edit" id="injury_date_edit" value="'.$datumZraneni.'" autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                   </div>
                   <div class="form-group">
                        <div class="row">
                            <label for="injury_description_edit" class="col-md-2 text-left" style="font-size: 16px;">Popis zranění</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                    </div>
                                    <textarea name="injury_description_edit" placeholder="Zadejte popis zranění... [maximálně 180 znaků]" id="injury_description_edit" class="form-control" autocomplete="on">'.$injury->injury_description.'</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="d-flex justify-content-center">Zranění vytvořeno '.$injury->created_at.', naposledy aktualizováno '.$injury->updated_at.'.</p>';
        /* Odeslani obsahu do modalniho okna */
        return response()->json(['out'=>$out]);
    }

    /* Nazev funkce: edit
       Argumenty: request - udaje zadane firmou,id - jednoznacny identifikator zraneni
       Ucel: Aktualizace zraneni v databazi */
    public function update(Request $request, $id){
        $user = Auth::user();
        /* Ziskani zraneni */
        $zraneni = Injury::find($id);
        /* Definice pravidel validace */
        $validator = Validator::make($request->all(), ['datum_zraneni' => ['required'], 'popis_zraneni' => ['max:180']]);
        /* Ziskani smeny, kde doslo ke zraneni */
        $smenaUdaje = Injury::getEmployeeInjuries($user->company_id, $zraneni->employee_id, $zraneni->shift_id);
        /* Zmena formatu datumu */
        $injury_date = new DateTime($request->datum_zraneni);
        $puvodniInjuryDate = new DateTime($zraneni->injury_date);
        $shift_start = new DateTime($smenaUdaje[0]->shift_start);
        /* Usek kodu, ktery slouzi pro zjisteni, zdali firma nevytvorila zraneni o vice nez 20 minut pred zacatkem smeny */
        $sekundy = 1200; // 20 minut - maximalni doba pro vytvoreni zraneni pred zacatkem smeny
        $difference_start = $injury_date->format('U') - ($shift_start->format('U') - $sekundy);
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0){
            array_push($chybaDatumy,'Datum zranění dříve než začátek směny o více než 20 minut!');
            $bool_datumy = 1;
        }
        /* Naplneni hlasek do pole */
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        /* Odeslani chybovych hlasek uzivateli */
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }
        $jeZmena = 0;
        /* Usek kodu slouzici pro zjisteni, zdali doslo k nejake zmene udaju */
        $diffValidDatum = $injury_date->format('U') - $puvodniInjuryDate->format('U');
        if(($diffValidDatum == 0) && ($request->popis_zraneni == $zraneni->injury_description)){
            $jeZmena = 0;
        }else{
            $jeZmena = 1;
        }
        /* Aktualizace zraneni v databazi */
        Injury::where(['injury_id' => $zraneni->injury_id])->update(['injury_date' => $request->datum_zraneni, 'injury_description' => $request->popis_zraneni]);
        /* Pokud uzivatel nic neupravil, nic se mu nezobrazi, pokud neco upravil je mu zaslana hlaska o uspechu akce */
        if($jeZmena != 1 ){
            return response()->json(['success' => '0']);
        }else{
            return response()->json(['success' => 'Zranění bylo úspěšně zeditováno.']);
        }
    }

    /* Nazev funkce: destroy
       Argumenty: id - jednoznacny identifikator zraneni
       Ucel: Smazani zraneni z databaze */
    public function destroy($id){
        $user = Auth::user();
        /* Ziskani zraneni */
        $zraneni = Injury::find($id);
        /* Ziskani smeny, kde nastalo zraneni */
        $smena = Shift::find($zraneni->shift_id);
        /* Ziskani ID smeny z OLAP sekce systemu */
        $shift_info_id = OlapETL::getShiftInfoId($zraneni->employee_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        /* Aktualizace OLAP sekce systemu */
        OlapETL::aggregateEmployeeInjuryFlag($shift_info_id, $zraneni->employee_id, $user->company_id, 0);
        /* Smazani zraneni z databaze */
        Injury::findOrFail($id)->delete();
        /* Odeslani hlasky o uspechu uzivateli */
        return response()->json(['success'=>'Zranění bylo úspěšně smazáno.']);
    }

}
