<?php

namespace App\Http\Controllers;

use App\Models\AbsenceReason;
use App\Models\Attendance;
use App\Models\Employee_Shift;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Employee;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShiftDatatableController extends Controller {
    /* Nazev souboru:  ShiftDatatableController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy nemocenskych v uctu s roli firmy. Slouzi take k ovladani datove tabulky.

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
       Ucel: Zobrazeni prislusneho pohledu pro seznam smen */
    public function index() {
        $user = Auth::user();
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
         Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        return view('company_actions.shift_list')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance);
    }

    /* Nazev funkce: getShifts
       Argumenty: zadne
       Ucel: Zobrazeni seznamu smen v datove tabulce */
    public function getShifts() {
        $user = Auth::user();
        /* Ziskani smen firmy */
        $smeny = Shift::where('company_id',$user->company_id)->get();
        /* Usek kodu slouzici k vyrenderovani datove tabulky */
        return Datatables::of($smeny)
            ->addIndexColumn()
            ->addColumn('shift_taken', function($smeny){ // pridani indikatoru, zdali je smena obsazena nejakym zamestnancem
                $jeObsazena = Employee_Shift::isShiftTaken($smeny->shift_id);
                if($jeObsazena->isEmpty()){
                    return '<input type="checkbox" name="shift_taken" value="0" onclick="return false;">';
                }else{
                    return '<input type="checkbox" name="shift_taken" value="1" onclick="return false;" checked>';
                }
            })
            ->addColumn('shift_importance_id', function($smeny){ // pridani sloupce pro zobrazeni dulezitosti smeny
                $aktualniDulezitost = ImportancesShifts::getParticularImportance($smeny->shift_importance_id);
                return $aktualniDulezitost[0]->importance_description;
            })
            ->addColumn('action', function($smeny){ // Definice ovladacich tlacitek datove tabulky
                return '<button type="button" data-id="'.$smeny->shift_id.'" data-toggle="modal" data-target="#EditShiftForm" id="obtainEditShiftData" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Zobrazit</button>
                        <button type="button" data-id="'.$smeny->shift_id.'" data-toggle="modal" data-target="#DeleteShiftForm" id="obtainShiftDelete" class="btn btn-danger btn-sm">&nbsp;&nbsp;<i class="fa fa-trash-o"></i> Smazat &nbsp;</button>
                        <button type="button" data-id="'.$smeny->shift_id.'" data-toggle="modal" id="obtainShiftAssigned" data-target="#AssignEmployeeForm" class="btn btn-dark btn-sm tlacitkoPriraditSeznamSmen"><i class="fa fa-exchange"></i> Přiřadit &nbsp;</button>
                        <button type="button" data-id="'.$smeny->shift_id.'" data-toggle="modal" style="margin-top:6px;" id="obtainEmployeeOptions" data-target="#ShowAttendanceOptionsForm" class="btn btn-success btn-sm"><i class="fa fa-calendar-check-o"></i> Docházka</button>';
            })
            ->rawColumns(['action','shift_taken'])
            ->make(true);
    }


    /* Nazev funkce: store
       Argumenty: request - udaje zadane firmou pro tvorbu nove smeny
       Ucel: Vytvoreni nove smeny */
    public function store(Request $request){
        $user = Auth::user();
        /* Definice pravidel pro validaci a nasledne jeji provedeni */
        $validator = Validator::make($request->all(), ['zacatek_smeny' => ['required'], 'konec_smeny' =>  ['required'], 'lokace_smeny' =>  ['required', 'string', 'max:255'], 'poznamka' => ['max:180']]);
        /* Ziskani udaju */
        $shift_start = new DateTime($request->zacatek_smeny);
        $shift_end = new DateTime($request->konec_smeny);
        $difference_shifts = $shift_end->format('U') - $shift_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetDnu = $hodinyRozdil->d;
        $pocetHodin = $hodinyRozdil->h;
        $pocetMinut = $hodinyRozdil->i;
        /* Overeni, zdali zadal uzivatel korektni datumy */
        if($request->zacatek_smeny != NULL){
            if($difference_shifts <= 0){
                array_push($chybaDatumy,'Konec směny je stejný jako její začátek, nebo je dříve než začátek!');
                $bool_datumy = 1;
            }
            if(($pocetHodin == 12 && $pocetMinut > 0) || $pocetHodin > 12 || $pocetDnu > 0){
                array_push($chybaDatumy,'Maximální délka jedné směny je 12 hodin!');
                $bool_datumy = 1;
            }
        }
        /* Naplneni pripadnych chybovych hlasek do pole a jejich pripadne odeslani uzivateli */
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }
        /* Vytvoreni nemocenske v databazi */
        Shift::create(['shift_start' => $request->zacatek_smeny, 'shift_end' => $request->konec_smeny, 'shift_place' => $request->lokace_smeny, 'shift_importance_id' => $request->dulezitost_smeny,
            'shift_note' => $request->poznamka, 'company_id' => $user->company_id]);
        /* Odeslani odpovedi uzivateli */
        return response()->json(['success'=>'Směna byla úspešně vytvořena.']);
    }

    /* Nazev funkce: edit
       Argumenty: id - jednoznacny identifikator smeny
       Ucel: Zobrazeni obsahu pro upravu konkretni smeny */
    public function edit($id){
        /* Ziskani smeny */
        $smena = Shift::find($id);
        /* Zmena formatu datumu, aby sly zobrazit v datetime local */
        $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
        $datumEnd = date('Y-m-d\TH:i', strtotime($smena->shift_end));
        $shift_start = new DateTime($smena->shift_start);
        $shift_end = new DateTime($smena->shift_end);
        /* Zjisteni rozdilu konce a zacatku smeny */
        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetHodin = $hodinyRozdil->h;
        $pocetMinut = $hodinyRozdil->i;
        /* Ziskani vsech moznych dulezitosti a aktualni dulezitosti*/
        $moznostiDulezitosti = ImportancesShifts::getAllImportances();
        $aktualniDulezitost = ImportancesShifts::getParticularImportance($smena->shift_importance_id);
        $vypisDulezitosti = "";
        $tabulka = '<table class="table table-dark" id="shift_employees_table" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th style="width:30%;text-align:center;">Jméno</th>
                                <th style="width:25%;text-align:center;">Pozice</th>
                                <th style="width:25%;text-align:center;">Skóre</th>
                                <th style="width:10%;text-align:center;">Přišel/Přišla</th>
                                <th style="width:10%;text-align:center;">Status</th>
                                <th style="width:10%;text-align:center;">Odpracováno</th>
                            </tr>
                        </thead>
                        <tbody>';
        /* Ziskani zamestnancu na konkretni smene */
        $zamestnanci = Shift::getAllEmployeesAtShift($id);
        /* Iterace skrze zamestnance */
        foreach ($zamestnanci as $zamestnanec){
            /* Ziskani konkretni dochazky zamestnance */
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($id, $zamestnanec->employee_id);
            /* Ziskani celkoveho skore zamestnance */
            $skore = ($zamestnanec->employee_reliability + $zamestnanec->employee_absence + $zamestnanec->employee_workindex) / 3;
            if($dochazka->isEmpty()){ // pokud dochazka nebyla vytvorena
                $tabulka .= '<tr>
                                <td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td>
                                <td class="text-center"> '.$zamestnanec->employee_position.'</td> <td class="text-center"> '.round($skore,2).'</td>
                                <td class="text-center"><p style="color:yellow;">Nezapsáno</p></td>
                                <td class="text-center"><p style="color:yellow;">Neznámý</p></td>
                                <td class="text-center"><p style="color:yellow;">Nezapsaný příchod/odchod</p></td>
                              </tr>';
            }else{ // dochazka existuje
                /* Ziskani statusu dochazky */
                $status = AbsenceReason::getParticularReason( $dochazka[0]->absence_reason_id);
                $statView = "";
                /* Vyplneni statusu dochazky do promenne statView, ktera se potom vyrenderuje */
                if($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL){
                    $statView = '<p style="color:yellow;">Neznámý</p>';
                }else{
                    if($dochazka[0]->absence_reason_id == 5){
                        $statView = '<p style="color:lightgreen;">'.$status[0]->reason_description.'</p>';
                    }else{
                        $statView = '<p style="color:orangered;">'.$status[0]->reason_description.'</p>';
                    }
                }
                /* Usek kodu slouzici k vypoctu odpracovanych hodin na smene */
                $odpracovano = '';
                if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                    if($dochazka[0]->attendance_check_in == NULL || $dochazka[0]->attendance_check_out == NULL){
                        $odpracovano = '<p style="color:yellow;">Nezapsaný příchod/odchod</p>';
                    }else if($dochazka[0]->attendance_check_in != NULL && $dochazka[0]->attendance_check_out != NULL){
                        $checkin = new DateTime($dochazka[0]->attendance_check_in);
                        $checkout = new DateTime($dochazka[0]->attendance_check_out);
                        $hodinyRozdilCheck =$checkout->diff($checkin);
                        $odpracovano = '<p style="color:white;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                    }
                }else if($dochazka[0]->attendance_check_in_company != NULL && $dochazka[0]->attendance_check_out_company != NULL){
                    $checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                    $checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                    $hodinyRozdilCheck =$checkout->diff($checkin);
                    $odpracovano = '<p style="color:white;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                }
                if($dochazka[0]->attendance_came == NULL || $dochazka[0]->attendance_came == 0){
                    $tabulka .= '<tr>
                                    <td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td>
                                    <td class="text-center"> '.$zamestnanec->employee_position.'</td>
                                    <td class="text-center"> '.round($skore,2).'</td>
                                    <td class="text-center"> <p style="color:orangered;">Ne</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>
                                  </tr>';
                }else{
                    $tabulka .= '<tr>
                                    <td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td>
                                    <td class="text-center"> '.$zamestnanec->employee_position.'</td>
                                    <td class="text-center"> '.round($skore,2).'</td>
                                    <td class="text-center"><p style="color:lightgreen;">Ano</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>
                                  </tr>';
                }
            }
        }
        $tabulka .= '</tbody></table>'; // ukonceni tabulky
        /* Usek kodu slouzici pro definici obsahu selectboxu pro dulezitost smeny */
        foreach ($aktualniDulezitost as $dulezitost) {
            $vypisDulezitosti .= ' <option value="'.$dulezitost->importance_id.'">'.$dulezitost->importance_description.'</option>';
        }
        for ($i = 0; $i < count($moznostiDulezitosti); $i++) {
                if($aktualniDulezitost[0]->importance_id != $moznostiDulezitosti[$i]->importance_id){
                    $vypisDulezitosti .= ' <option value="'.$moznostiDulezitosti[$i]->importance_id.'">'.$moznostiDulezitosti[$i]->importance_description.'</option>';
                }
        }
        /* Definice obsahu modalniho okna pro editaci smeny */
        $out = '<ul class="nav nav-stacked nav-pills d-flex justify-content-center" style="font-size: 15px;">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#zamestnanci" >Zaměstnanci</a>
                    </li>
                 </ul>
                <div style="margin-top:20px;" class="tab-content">
                    <div class="tab-pane active" id="obecneUdaje">
                        <div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:16px;padding:10px;border-radius: 10px;background-color: #2d995b;">'.$aktualniDulezitost[0]->importance_description.'</div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shift_start_edit" class="col-md-2 text-left">Začátek směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="shift_start_edit" id="shift_start_edit" value="'.$datumStart.'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shift_end_edit" class="col-md-2 text-left">Konec směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="shift_end_edit" id="shift_end_edit" value="'.$datumEnd.'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shift_place_edit" class="col-md-2 text-left">Místo(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building"></i></div>
                                        </div>
                                        <input id="shift_place_edit" placeholder="Zadejte lokaci směny..." type="text" class="form-control" name="shift_place_edit" value="'.$smena->shift_place.'" autocomplete="on">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shiftImportance_edit" class="col-md-2 text-left">Důležitost</label>
                                <div class="col-md-10">
                                    <select name="shiftImportance_edit" id="shiftImportance_edit" style="color:black;text-align-last: center;" class="form-control">'.$vypisDulezitosti.'</select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shift_note_edit" class="col-md-2 text-left">Poznámka</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                        </div>
                                        <textarea name="shift_note_edit" placeholder="Zadejte poznámku ke směně... [maximálně 180 znaků]" id="shift_note_edit" class="form-control" autocomplete="on">'.$smena->shift_note.'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="d-flex justify-content-center" style="background-color: #333333;color:white;border-radius: 15px;padding:10px;font-size: 15px;">Délka směny: '.$pocetHodin.'h'.$pocetMinut.'m.</p>
                        <p class="d-flex justify-content-center">Směna vytvořena '.$smena->created_at.', naposledy aktualizována '.$smena->updated_at.'.</p>
                    </div>
                    <div class="tab-pane" id="zamestnanci">
                        <p style="font-size: 17px;text-align: center;">Tato směna je obsazena následujícími zaměstnanci (zaměstnancem):</p>
                         <input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavaniZamestnancuSeznamSmen" placeholder="Hledat zaměstnance na základě jména, pozice, nebo skóre ...">
                        '.$tabulka.'
                    </div>
                  </div>
              </div>';
        $out .= '<script>
              /* Implementace vyhledavace v ramci hledani zamestnancu */
              $(document).ready(function(){
                  $("#vyhledavaniZamestnancuSeznamSmen").on("keyup", function() { // po zapsani znaku ve vyhledavani
                    var retezec = $("#vyhledavaniZamestnancuSeznamSmen").val(); // ziskani hodnoty ve vyhledavaci
                    var vysledek = retezec.toUpperCase(); // transformace hodnoty na velka pismena
                    var radkyTabulky = $("#shift_employees_table tr"); // ziskani radku tabulek
                    radkyTabulky.each(function () { // iterace skrze radky tabulky
                        var bunka = $(this).find("td"); // ziskani hodnoty bunky
                        bunka.each(function () { // iterace skrz bunky
                            var obsahBunky = $(this).text(); // ulozeni hodnoty bunky
                            if((obsahBunky.toUpperCase().includes(vysledek) == false) == false){ // kontrola zdali hledany retezec je podmnozinou nejake hodnoty v tabulce
                                  $(this).closest("tr").toggle(true); // radek je ponechan
                                  return false; // pokracovani dalsim radkem
                            }else{
                                  $(this).closest("tr").toggle(false); // schovani radku tabulky, v ktere se nachazi aktualni bunka
                                  return true; // pokracovani dalsi bunkou radku
                            }
                        });
                     });
                  });
                });
        </script>';
        /* Odeslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator smeny, request - zadane udaje
       Ucel: Aktualizace zadanych udaju v ramci upravy smeny */
    public function update(Request $request, $id){
        $user = Auth::user();
        /* Ziskani konkretni smeny*/
        $smena = Shift::find($id);
        /* Definice pravidel pro validaci a nasledne jeji provedeni */
        $validator = Validator::make($request->all(), ['zacatek_smeny' => ['required'], 'konec_smeny' =>  ['required'], 'lokace_smeny' =>  ['required', 'string', 'max:255'], 'poznamka' => ['max:180']]);
        /* Ziskani udaju o smene */
        $shift_start = new DateTime($request->zacatek_smeny);
        $shift_end = new DateTime($request->konec_smeny);
        /* Validace korektnosti datumu */
        $difference_shifts = $shift_end->format('U') - $shift_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_shifts < 0){
            array_push($chybaDatumy,'Konec směny je stejný, nebo je dříve než její začátek!');
            $bool_datumy = 1;
        }
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }
        /* Ziskani zamestnancu na smene */
        $zamestnanci = Shift::getAllEmployeesAtShift($id);
        $employee_ids = array();
        foreach ($zamestnanci as $zamestnanec) { array_push($employee_ids, $zamestnanec->employee_id); }
        /* Usek kodu zjistujici zdali uzivatel zmenil nejake udaje */
        $jeZmena = 0;
        $shift_db_start = new DateTime($smena->shift_start);
        $shift_db_end = new DateTime($smena->shift_end);
        $diffValidStart = $shift_db_start->format('U') - $shift_start->format('U');
        $diffValidEnd = $shift_db_end->format('U') - $shift_end->format('U');
        if(($diffValidStart == 0) && ($diffValidEnd == 0)
            && ($smena->shift_place == $request->lokace_smeny) && ($smena->shift_importance_id == $request->dulezitost_smeny)
            && ($smena->shift_note == $request->poznamka)){
            $jeZmena = 0;
        }else{
            $jeZmena = 1;
        }
        /* Aktualizace udaju smeny v OLAP sekci systemu */
        OlapETL::updateShiftInfoDimension($employee_ids, $user->company_id, $smena->shift_start, $smena->shift_end, $request->zacatek_smeny, $request->konec_smeny);

        /* Aktualizace udaju konkretni smeny v databazi */
        Shift::where(['shift_id' => $smena->shift_id])->update(['shift_start' => $request->zacatek_smeny, 'shift_end' => $request->konec_smeny, 'shift_place' => $request->lokace_smeny,
            'shift_importance_id' => $request->dulezitost_smeny, 'shift_note' => $request->poznamka, 'company_id' => $user->company_id]);

        /* Aplikace transformace delky smeny v OLAP sekci systemu */
        OlapETL::updateShiftTotalHoursField($request->zacatek_smeny, $request->konec_smeny, $employee_ids, $user->company_id);
        if($jeZmena != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Směna byla úspěšně zeditována.']);
        }
    }

    /* Nazev funkce: assignEmployee
       Argumenty: id - jednoznacny identifikator smeny
       Ucel: obsah modalniho okna pro prirazeni smeny zamestnanci */
    public function assignEmployee($id){
        $user = Auth::user();
        /* Ziskani smeny */
        $aktualniSmena = Shift::find($id);
        /* Ziskani udaju o smene*/
        $shift_start = new DateTime($aktualniSmena->shift_start);
        $shift_end = new DateTime($aktualniSmena->shift_end);
        /* Vypocet delky smeny */
        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetZamestnancuNaSmene = Shift::getEmployeesCountAtShift($id);
        $hodiny = $hodinyRozdil->h * $pocetZamestnancuNaSmene;
        $minuty = $hodinyRozdil->i * $pocetZamestnancuNaSmene;
        /* Ziskani zamestnancu firmy*/
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $out = '<div class="alert alert-warning" role="alert">
                     <button type="button" class="close" data-dismiss="alert">x</button>
                     <center><strong>Celkový počet hodin na této směně: <br>'.$hodiny.'h'.$minuty.'m </strong><center>
                 </div>';
        if (count($zamestnanci) == 0){
            $out .=  '<div class="alert alert-danger" role="alert">
                     <button type="button" class="close" data-dismiss="alert">x</button>
                     <center><strong>Nemáte vytvořené žádné zaměstnance.</strong><center>
                 </div>';
        }
        $out .= '<input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavaniPrirazeniZamestnance" placeholder="Hledat zaměstnance na základě jména, pozice ...">
                 <table class="table table-dark" id="tableAssignEmployees" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th style="width:5%;text-align: center;">ID</th>
                                <th style="width:35%;text-align: center;">Jméno</th>
                                <th style="width:25%;text-align: center;">Pozice</th>
                                <th style="width:25%;text-align: center;">Skóre</th>
                                <th style="width:6%;text-align: center;">Obsazeno</th>
                            </tr>
                        </thead>
                        <tbody>';
        /* Iterace skrze zamestnance a ulozeni udaju do formatu HTML pro nasledne vyrenderovani */
        foreach ($zamestnanci as $zamestnanec){
            $aktualniZamestnanec = Employee_Shift::getEmployeeParticularShift($zamestnanec->employee_id, $id);
            $skore = ($zamestnanec->employee_reliability + $zamestnanec->employee_absence + $zamestnanec->employee_workindex) / 3;
            $out .= '<tr><td class="text-center">'.$zamestnanec->employee_id.'</td><td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td><td class="text-center"> '.$zamestnanec->employee_position.'</td> <td class="text-center"> '.round($skore,2).'</td>';
            if($aktualniZamestnanec->isEmpty()){
                $out .= '<td><center><input type="checkbox" name="shift_employee_assign_id" class="form-check-input shift_employee_assign_id" id="shift_employee_assign_id" name="shift_employee_assign_id[]" value="'.$zamestnanec->employee_id.'"></center></td> </tr>';
            }else{
                $out .= '<td><center><input type="checkbox" name="shift_employee_assign_id" class="form-check-input shift_employee_assign_id" id="shift_employee_assign_id" name="shift_employee_assign_id[]" value="'.$zamestnanec->employee_id.'" checked></center></td> </tr>';
            }
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        $out .= '<script>
                       /* Implementace vyhledavace v ramci hledani smen */
                      $(document).ready(function(){
                          $("#vyhledavaniPrirazeniZamestnance").on("keyup", function() { // po zapsani znaku ve vyhledavani
                            var retezec = $("#vyhledavaniPrirazeniZamestnance").val(); // ziskani hodnoty ve vyhledavaci
                            var vysledek = retezec.toUpperCase(); // transformace hodnoty na velka pismena
                            var radkyTabulky = $("#tableAssignEmployees tr"); // ziskani radku tabulek
                            radkyTabulky.each(function () { // iterace skrze radky tabulky
                                var bunka = $(this).find("td"); // ziskani hodnoty bunky
                                bunka.each(function () { // iterace skrz bunky
                                    var obsahBunky = $(this).text(); // ulozeni hodnoty bunky
                                    if((obsahBunky.toUpperCase().includes(vysledek) == false) == false){ // kontrola zdali hledany retezec je podmnozinou nejake hodnoty v tabulce
                                          $(this).closest("tr").toggle(true); // radek je ponechan
                                          return false; // pokracovani dalsim radkem
                                    }else{
                                          $(this).closest("tr").toggle(false); // schovani radku tabulky, v ktere se nachazi aktualni bunka
                                          return true; // pokracovani dalsi bunkou radku
                                    }
                                });
                             });
                          });
                        });
                 </script>';
        /* Odeslani odpovedi uzivateli */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateassignEmployee
       Argumenty: id - jednoznacny identifikator smeny, request - udaje zapsane firmou
       Ucel: aktualizace prirazeni smeny */
    public function updateassignEmployee(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        $shift = Shift::find($id);
        $jmena = '';
        $count = 0;
        /* Zjisteni zdali uzivatel vybral vubec nejake zamestnance */
        if($request->employees_ids != "") {
            $employee_id_arr = explode('&', $request->employees_ids);
            $delka = count($employee_id_arr);
            /* Sekce kodu slouzici pro sber identikatoru zamestnancu */
            $employee_ids_collector = array();
            foreach ($employee_id_arr as $employee_id) {
                $employee_id_value = explode('=', $employee_id);
                array_push($employee_ids_collector,$employee_id_value[1]);
            }
            /* Odstraneni predchozich smen */
            OlapETL::deleteCancelledPreviouslyAssignedEmployee($shift->shift_start, $shift->shift_end, $employee_ids_collector);
            /* Pripadne odstraneni dochazky */
            Employee_Shift::deleteAssignedEmployeesShiftWithAttendance($shift->shift_id, $employee_ids_collector);
            /* Iterace skrze zamestnance */
            foreach ($employee_id_arr as $employee_id) {
                $employee_id_value = explode('=', $employee_id);
                $aktualniZamestnanec = Employee_Shift::getEmployeeParticularShift($employee_id_value[1], $id);
                /* Ziskani konkretniho zamestnance */
                $employee = Employee::find($employee_id_value[1]);
                if ($count == $delka - 1) { // posledni jmeno zamestnance bude koncit teckou
                    $jmena .= $employee->employee_name.' '.$employee->employee_surname.'.';
                }else{
                    $jmena .= $employee->employee_name.' '.$employee->employee_surname.', ';
                }
                if($aktualniZamestnanec->isEmpty()){ // pokud zamestnanec smenu jeste nemel
                    $user = Auth::user();
                    /* Prirazeni smeny */
                    Employee_Shift::create(['shift_id' => $shift->shift_id, 'employee_id' => $employee_id_value[1]]);
                    /* Vytvoreni zaznamu v dimenzi smen, casove dimenzi, dimenzi firem a zamestnancu */
                    $shift_info_id = OlapETL::extractDataToShiftInfoDimension($shift);
                    $time_id = OlapETL::extractDataToTimeDimension($shift_info_id, $shift);
                    $employee_id = OlapETL::extractDataToEmployeeDimension($employee);
                    $company_id = OlapETL::extractDataToCompanyDimension($user);
                    /* Extrakce dat do tabulky faktu */
                    OlapETL::extractDataToShiftFact($shift, $employee, $shift_info_id, $time_id, $employee_id, $company_id);
                    //return response()->json(['success' => 'Firma je: '.$company_id.', ID času je: '.$time_id.', ID zaměstnance je: '.$employee_id.', ID směny je: '.$shift_info_id]);
                }
                $count++;
            }
        }
        if($count > 0){ // pokud uzivatel odstranil i posledni prirazenou smenu
        }else{
            /* Smazani vsech prirazenych smen a dochazky */
            OlapETL::deleteAllCancelledPreviouslyAssignedEmployee($shift->shift_start, $shift->shift_end);
            Employee_Shift::deleteAllAssignedEmployeesShiftWithAttendance($shift->shift_id);
        }
        /* Priprava vypisu hlasky uzivateli */
        substr_replace($jmena, ".", -1);
        if($jmena == ""){
            return response()->json(['success'=>'Ze směny byly úspešně odebráni všichni zaměstnanci.']);
        }
        if($count == 1){
            return response()->json(['success'=>'Směna v lokaci: '.$shift->shift_place.', v čase od: '.$shift->shift_start.' do '.$shift->shift_end.' byla úspěšně přiřazena tomuto zaměstnanci: '.$jmena]);
        }else{
            return response()->json(['success'=>'Směna v lokaci: '.$shift->shift_place.', v čase od: '.$shift->shift_start.' do '.$shift->shift_end.' byla úspěšně přiřazena těmto zaměstnancům: '.$jmena]);
        }
    }

    /* Nazev funkce: destroy
       Argumenty: id - jednoznacny identifikator smeny
       Ucel: odstraneni smeny z databaze */
    public function destroy($id){
        $user = Auth::user();
        $smena = Shift::find($id);
        /* Ziskani zamestnancu na smene */
        $zamestnanci = Shift::getAllEmployeesAtShift($id);
        $employee_ids = array();
        /* Naplneni identifikatoru zamestnancu do pole */
        foreach ($zamestnanci as $zamestnanec) { array_push($employee_ids, $zamestnanec->employee_id); }
        /* Realizace smazani z OLAP sekce systemu i z databaze */
        OlapETL::deleteRecordFromShiftInfoDimension($employee_ids, $user->company_id, $smena->shift_start, $smena->shift_end);
        Shift::find($id)->delete();
        return response()->json(['success'=>'Směna byla úspěšně smazána.']);
    }

    /* Nazev funkce: getAttendanceOptions
       Argumenty: id - jednoznacny identifikator smeny
       Ucel: zobrazeni obsahu moznosti dochazky do modalniho okna */
    public function getAttendanceOptions($id){
        $user = Auth::user();
        $out  = '';
        /* Ziskani zamestnancu a postupne vyplnovani jejich udaju do options */
        $zamestnanci = Employee_Shift::getAttendanceOptionsShifts($id, $user->company_id);
        if(count($zamestnanci) == 0){
            $out .= '<div class="alert alert-danger alert-block"><strong>Ke směně nejsou přiřazeni žádní zaměstnanci</strong></div>';
        }else{
            $out .='<div class="form-group">
                            <select name="vybrany_zamestnanec" required id="vybrany_zamestnanec" style="color:black" class="form-control input-lg dynamic vybrany_zamestnanec">
                                 <option value="">Vyberte zaměstnance</option>';
            foreach ($zamestnanci as $zamestnanec){
                $out .= '<option id="'.$zamestnanec->employee_id.'" value="'.$zamestnanec->employee_id.'">'.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</option>';
            }
            $out .= ' </select></div>';
        }
        /* Definice tlacitek pro ovladani moznosti dochazky */
        $out .= '<center><button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckinForm" id="obtainCheckInShift" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Příchod</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckoutForm" id="obtainCheckOutShift" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Odchod</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceAbsenceForm" id="obtainAbsenceReasonAttendance" class="btn btn-primary"><i class="fa fa-lightbulb-o"></i> Status</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceNoteForm" id="obtainNoteAttendance" class="btn btn-primary"><i class="fa fa-sticky-note-o"></i> Poznámka</button>';
        /* Zaslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: showCheckinshowCheckin
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu prichodu (moznosti) do modalniho okna */
    public function showCheckin($zamestnanec_id,$smena_id){
         $out = '';
         date_default_timezone_set('Europe/Prague');
         /* Pokud uzivatel nevybral zamestnance */
         if($zamestnanec_id == "undefined"){
             $out .= '<div class="alert alert-danger alert-block text-center"><strong>Nevybral jste žádného zaměstnance.</strong></div>';
             return response()->json(['out' => $out]);
         }
         /* Ziskani dochazky */
          $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
          /* Ziskani smeny */
          $smena = Shift::findOrFail($smena_id);

          if($dochazka->isEmpty()){ // pokud dochazka neexistuje vypise se nedefinovano jako hodnota prichodu
              $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
              $out .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
              $out .= '<div class="input-group">
                          <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>';
              $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'">';
              $out .= '</div>';
          }else{
              if($dochazka[0]->attendance_check_in_company == NULL){ // pokud dochazka existuje, ale neni zapsan prichod
                  $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
                  $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
                  $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                           </div>';
                  $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'">';
                  $out .= '</div>';
              }else{ // pokud je zapsan prichod, tak se vpise do datetime local inputu
                  $date_start = new DateTime($dochazka[0]->attendance_check_in_company);
                  $datumZobrazeni = $date_start->format('d.m.Y H:i');
                  $datumStart = date('Y-m-d\TH:i', strtotime($dochazka[0]->attendance_check_in_company));
                  $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: '.$datumZobrazeni.'</strong></div>';
                  $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                           </div>';
                  $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'">';
                  $out .= '</div>';
              }
          }
         /* Odeslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateCheckIn
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadany prichod
       Ucel: aktualizace prichodu */
    public function updateCheckIn(Request $request,$zamestnanec_id,$smena_id){
        $user = Auth::user();
        $smena = Shift::findOrFail($smena_id);
        $shift_start = new DateTime($smena->shift_start);
        $shift_end = new DateTime($smena->shift_end);
        $shift_checkin = new DateTime($request->attendance_check_in_company);
        $sekundy = 0; // 0 minut
        $difference_start = $shift_checkin->format('U') - ($shift_start->format('U') - $sekundy);
        $difference_end = $shift_end->format('U') - $shift_checkin->format('U');
        /* Usek kodu urceny k validaci datumu */
        $chybaDatumy = array();
        $bool_datumy = 0;
       /* if($difference_start < 0){
            array_push($chybaDatumy,'Zapsaný příchod je dříve než začátek směny o více než 20 minut!');
            $bool_datumy = 1;
        }*/
        if($difference_end < 0){
            array_push($chybaDatumy,'Zapsaný příchod je později než konec směny samotné!');
            $bool_datumy = 1;
        }
        if ($bool_datumy == 1) { return response()->json(['fail' => $chybaDatumy]); }
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        $company_check_in_date = new DateTime($request->attendance_check_in_company);
        $shift_start_date = new DateTime($smena->shift_start);
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje, tak se vytvori bud se zpozdenim (4) ci v poradku (5)
            if($company_check_in_date > $shift_start_date){
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_check_in_company' => $request->attendance_check_in_company, 'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_check_in_company' => $request->attendance_check_in_company, 'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }else{
            if($dochazka[0]->attendance_check_out_company != NULL){
                $shift_checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                $difference_checkins = $shift_checkout->format('U') - $shift_checkin->format('U');
                if($difference_checkins < 0){
                    array_push($chybaDatumy,'Zapsaný příchod je později než zapsaný odchod ze směny!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
                /* Provedeni transformace odpracovanych hodin v OLAP sekci systemu */
                OlapETL::aggregateEmployeeTotalWorkedHours($shift_info_id, $zamestnanec->employee_id, $user->company_id, $request->attendance_check_in_company, $dochazka[0]->attendance_check_out_company);
            }
            if($company_check_in_date > $shift_start_date){
                /* Aktualizace dochazky */
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => $request->attendance_check_in_company,'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => $request->attendance_check_in_company,'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }
        /* Extrahovani do dimenze smen, provedeni transformace ohledne celkove zpozdeni a priznaku zpozdeni a extrakce indikatoru prichodu do dimenze smen */
        OlapETL::extractAttendanceCameToShiftFacts($shift_info_id, $zamestnanec->employee_id, $user->company_id);
        OlapETL::aggregateEmployeeAbsenceTotalHoursAndLateFlag($shift_info_id, $zamestnanec->employee_id, $user->company_id, $smena->shift_start, $request->attendance_check_in_company);
        OlapETL::extractAttendanceCheckInCompanyToShiftInfoDimension($shift_info_id, $request->attendance_check_in_company);
        return response()->json(['success'=>'Docházka příchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

    /* Nazev funkce: showCheckOut
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu odchodu (moznosti) do modalniho okna */
    public function showCheckOut($zamestnanec_id,$smena_id){
        $out = '';
        date_default_timezone_set('Europe/Prague');
        if($zamestnanec_id == "undefined"){
            $out .= '<div class="alert alert-danger alert-block text-center"><strong>Nevybral jste žádného zaměstnance.</strong></div>';
            return response()->json(['out' => $out]);
        }
        /* Ziskani dochazky a smeny  a nasledne vyplneni datetime local hodnotou odchodu ci vypsani chybove hlasky */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $smena = Shift::find($smena_id);
        if($dochazka->isEmpty()){
            $datumEnd = date('Y-m-d\TH:i', strtotime($smena->shift_end));
            $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
            $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                           </div>';
            $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumEnd.'">';
            $out .= '</div>';
        }else{
            if($dochazka[0]->attendance_check_out_company == NULL){
                $datumEnd = date('Y-m-d\TH:i', strtotime($smena->shift_end));
                $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
                $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                           </div>';
                $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumEnd.'">';
                $out .= '</div>';
            }else{
                $date_start = new DateTime($dochazka[0]->attendance_check_out_company);
                $datumZobrazeni = $date_start->format('d.m.Y H:i');
                $datumStart = date('Y-m-d\TH:i', strtotime($dochazka[0]->attendance_check_out_company));
                $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: '.$datumZobrazeni.'</strong></div>';
                $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                           </div>';
                $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumStart.'">';
                $out .= '</div>';
            }
        }
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateCheckOut
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadany odchod
       Ucel: aktualizace odchodu */
    public function updateCheckOut(Request $request,$zamestnanec_id,$smena_id){
        $user = Auth::user();
        /* Ziskani smeny a udaju o ni */
        $smena = Shift::find($smena_id);
        $shift_start = new DateTime($smena->shift_start);
        $shift_checkout = new DateTime($request->attendance_check_out_company);
        $sekundy = 0; // 0 minut
        /* Usek urceny pro validaci datumu */
        $difference_start = $shift_checkout->format('U') - ($shift_start->format('U') - $sekundy);
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0){
            array_push($chybaDatumy,'Zapsaný odchod je dříve než začátek směny samotné!');
            $bool_datumy = 1;
        }
        if ($bool_datumy == 1) { return response()->json(['fail' => $chybaDatumy]); }
        /* Ziskani dochazky a zamestnance */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        /* Ziskani ID smeny v ramci OLAP sekce systemu */
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje, tak se vytvori
            Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_check_out_company' => $request->attendance_check_out_company, 'attendance_came' => 1]);
        }else{
            if($dochazka[0]->attendance_check_in_company != NULL){ // pokud dochazka existuje a odchod v ni neni NULL tak nastane pokud o transformaci odpracovanych hodin v OLAP sekci systemu
                $shift_checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                $difference_checkins = $shift_checkout->format('U') - $shift_checkin->format('U');
                if($difference_checkins < 0){ // validace korektne zapsaneho checkoutu
                    array_push($chybaDatumy,'Zapsaný odchod ze směny je dříve než zapsaný příchod na směnu!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
                /* Realizace transformace odpracovanych hodin v OLAP sekci systemu */
                OlapETL::aggregateEmployeeTotalWorkedHours($shift_info_id, $zamestnanec->employee_id, $user->company_id, $dochazka[0]->attendance_check_in_company, $request->attendance_check_out_company);
            }
            /* Aktualizace dochazky */
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_out_company' => $request->attendance_check_out_company,'attendance_came' => 1]);
        }
        /* Extrahovani odchodu a indikatoru prichodu do dimenze smen */
        OlapETL::extractAttendanceCameToShiftFacts($shift_info_id, $zamestnanec->employee_id, $user->company_id);
        OlapETL::extractAttendanceCheckOutCompanyToShiftInfoDimension($shift_info_id, $request->attendance_check_out_company);
        /* Odeslani odpovedi */
        return response()->json(['success'=>'Docházka odchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

    /* Nazev funkce: showAbsence
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu statusu (moznosti) do modalniho okna */
    public function showAbsence($zamestnanec_id,$smena_id){
        $out = '';
        /* Pokud uzivatel nevybral zadneho zamestnance */
        if($zamestnanec_id == "undefined"){
            $out .= '<div class="alert alert-danger alert-block text-center"><strong>Nevybral jste žádného zaměstnance.</strong></div>';
            return response()->json(['html'=>$out]);
        }
        /* Ziskani dochazky a vsech duvodu absence */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $duvody = AbsenceReason::getAllReasons();
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje
            $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
        }else{
            if($dochazka[0]->absence_reason_id == NULL){ // pokud je status dochazky nastaven na NULL
                $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
            }else{ // pokud je status vyplneny
                $duvod_absence = AbsenceReason::getEmployeeCurrentShiftAbsenceReason($zamestnanec_id, $smena_id);
                $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: '.$duvod_absence[0]->reason_description.'</strong></div>';
            }
        }
        /* Vlozeni duvodu do moznosti vyberu */
        $out .= '<div class="form-group"><select name="duvody_absence" required id="duvody_absence" style="color:black" class="form-control input-lg duvody_absence">';

        if($dochazka->isEmpty()){
            foreach ($duvody as $duvod){
                $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
            }
        }else{
            if($dochazka[0]->absence_reason_id == NULL){
                foreach ($duvody as $duvod){
                    $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
                }
            }else{
                foreach ($duvody as $duvod){
                    if($duvod->reason_id == $dochazka[0]->absence_reason_id){
                        $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
                    }
                }
                foreach ($duvody as $duvod){
                    if($duvod->reason_id != $dochazka[0]->absence_reason_id){
                        $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
                    }
                }
            }
        }
        $out .= '</select></div>';
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateAbsence
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadany status
       Ucel: aktualizace statusu dochazky */
    public function updateAbsence(Request $request,$zamestnanec_id,$smena_id){
        $user = Auth::user();
        /* Ziskani dochazky, nasledne zamestnance a smeny */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $rozhod = 0;
        $zamestnanec = Employee::find($zamestnanec_id);
        $smena = Shift::find($smena_id);
        if($request->attendance_absence_reason_id == 4 || $request->attendance_absence_reason_id == 5){ $rozhod = 1; }
        /* Ziskani ID smeny z dimenze smen */
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje
            if($rozhod == 1){ // pokud je statusem zpozdeni ci ok, tak se indikator prichodu nastavi na 1
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 1]);
            }else{
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 0]);
            }
        }else{ // pokud dochazka existuje, tak se jednotliva pole pouze aktualizuji
            if($request->attendance_absence_reason_id == 4 || $request->attendance_absence_reason_id == 5){
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 1]);
            }else{
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => NULL, 'attendance_check_out_company' => NULL,'absence_reason_id' => $request->attendance_absence_reason_id,'attendance_came' => 0]);
            }
        }
        /* Extrakce statusu dochazky do tabulky faktu */
        OlapETL::extractAbsenceReasonToShiftFacts($shift_info_id, $zamestnanec_id, $user->company_id, $request->attendance_absence_reason_id);
        return response()->json(['success'=>'Status docházky zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byl úspěšně zapsán.']);
    }

    /* Nazev funkce: showAttendanceNote
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu poznamky (moznosti) do modalniho okna */
    public function showAttendanceNote($zamestnanec_id,$smena_id){
        $out = '';
        /* Pokud uzivatel nevybral zamestnance */
        if($zamestnanec_id == "undefined"){
            $out .= '<div class="alert alert-danger alert-block text-center"><strong>Nevybral jste žádného zaměstnance.</strong></div>';
            return response()->json(['out' => $out]);
        }
        /* Usek kodu starajici se o definici zobrazeni poznamky */
        $zamestnanec = Employee::find($zamestnanec_id);
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        if($dochazka->isEmpty()){
            $out .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' ... [maximálně 180 znaků]" id="attendance_note" class="form-control"></textarea>';
        }else{
            if($dochazka[0]->attendance_note == NULL){
                $out .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' ... [maximálně 180 znaků]" id="attendance_note" class="form-control"></textarea>';
            }else{
                $out .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' ... [maximálně 180 znaků]" id="attendance_note" class="form-control">'.$dochazka[0]->attendance_note.'</textarea>';
            }
        }
        /* Poslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateAttendanceNote
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadana poznamka
       Ucel: aktualizace poznamky */
    public function updateAttendanceNote(Request $request,$zamestnanec_id,$smena_id){
        /* Overeni, zdali poznamka nema vice nez 180 znaku*/
        $validator = Validator::make($request->all(), ['poznamka' => ['max:180']]);
        if($validator->fails()){
            return response()->json(['fail' => $validator->errors()->all()]);
        }
        /* Ziskani dochazky a zamestnance */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        /* Pokud dochazka neexistuje, tak se vytvori a rovnou se do ni vlozi poznamka, jinak se dochazka aktualizuje pouze v ramci pole poznamky */
        if($dochazka->isEmpty()){
            Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_note' => $request->poznamka]);
        }else{
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_note' => $request->poznamka]);
        }
        return response()->json(['success'=>'Poznámka k docházce zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

}
