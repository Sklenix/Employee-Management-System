<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EmployeeVacationController extends Controller {
    /* Nazev souboru:  EmployeeVacationController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy dovolenych v uctu s roli zamestnance. Ovladani datove tabulky je tu taktez naprogramovano.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni spravy dovolenych konkretniho zamestnance */
    public function index(){
        $user = Auth::user();
        return view('employee_actions.vacations_list')
            ->with('profilovka',$user->employee_picture)->with('employee_url', $user->employee_url);
    }

    /* Nazev funkce: getEmployeeVacations
       Argumenty: zadne
       Ucel: Zobrazeni datove tabulky pro spravu dovolenych */
    public function getEmployeeVacations(){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        /* Ziskani dovolenych zamestnance */
        $vacations = Vacation::getEmployeeVacations($user->employee_id);
        /* Usek kodu zabyvajici se vyrenderovanim tabulky */
        return Datatables::of($vacations)
            ->addIndexColumn()
            ->editColumn('vacation_state', function($vacations){ // uprava sloupce pro zobrazeni statusu dovolene
                if($vacations->vacation_state == 0){
                    return '<p style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezažádáno</p>';
                }else if($vacations->vacation_state == 1){
                    return '<p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p>';
                }else if($vacations->vacation_state == 2){
                    return '<p style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p>';
                }else if($vacations->vacation_state == 3){
                    return '<p style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p>';
                }else if($vacations->vacation_state == 4){
                    return '<p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p>';
                }
            })
            ->addColumn('vacation_actuality', function($vacations){ // pridani sloupce pro zobrazeni aktualnosti dovolene na zaklade toho, zdali je aktualni cas drive, pozdeji, nebo v intervalu dovolene
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
            ->addColumn('action', function($vacations){ // definice ovladacich tlacitek datove tabulky
                return '<button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#ApplyVacationForm" class="btn btn-dark btn-sm" id="obtainVacationApply"><i class="fa fa-bullhorn"></i> Zažádat</button>
                        <button type="button" class="btn btn-primary btn-sm" id="obtainEditVacationData" data-toggle="modal"  data-target="#EditVacationForm" data-id="'.$vacations->vacation_id.'"><i class="fa fa-pencil-square-o"></i> Editovat</button>
                        <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#DeleteApplyVacationForm" class="btn btn-dark btn-sm tlacitkoZrusitZadostDovolena" id="obtainVacationDeleteApply"><i class="fa fa-times"></i> Zrušit žádost</button>
                        <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#DeleteVacationForm" class="btn btn-danger btn-sm" style="margin-top:6px;" id="obtainVacationDelete">&nbsp;<i class="fa fa-trash-o"></i> Smazat&nbsp;&nbsp;</button>';
            })
            ->rawColumns(['action', 'vacation_state', 'vacation_actuality']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: store
       Argumenty: request - udaje zapsane zamestnancem
       Ucel: Ulozeni dovolene zamestnance do databaze */
    public function store(Request $request){
        $user = Auth::user();
        /* Definice pravidel pro validaci a nasledne jeji provedeni */
        $validator = Validator::make($request->all(), ['zacatek_dovolene' => ['required'], 'konec_dovolene' =>  ['required'], 'poznamka' => ['max:180']]);
        /* Pri zadani inkorektnich udaju zaslani chybove zpravy uzivateli */
        if ($validator->fails()) { return response()->json(['fail' => $validator->errors()->all()]); }

        /* Zjisteni, zdali datum konce dovolené je dříve než datum začátku dovolené nebo zdali neni stejne */
        $vacation_start = new DateTime($request->zacatek_dovolene);
        $vacation_end = new DateTime($request->konec_dovolene);
        $difference_vacation = $vacation_end->format('U') - $vacation_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_vacation < 0){
            array_push($chybaDatumy,'Datum konce dovolené dříve než datum začátku dovolené!');
            $bool_datumy = 1;
        }
        if($difference_vacation == 0){
            array_push($chybaDatumy,'Datum konce dovolené stejné jako datum začátku dovolené!');
            $bool_datumy = 1;
        }

        /* Naplneni pole chybovymi hlaskami */
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        /* Zaslani chybove zpravy uzivateli */
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['fail' => $chybaDatumy]); }
        /* Vytvoreni nahlaseni v databazi */
        Vacation::create(['vacation_start' => $request->zacatek_dovolene,'vacation_end' => $request->konec_dovolene, 'vacation_note' => $request->poznamka, 'vacation_state' => 0, 'employee_id' => $user->employee_id]);
        /* Odeslani zpravy o uspechu uzivateli */
        return response()->json(['success'=>'Dovolená byla úspešně vytvořena.']);
    }

    /* Nazev funkce: edit
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Zobrazeni obsahu (formulare) modalniho okna */
    public function edit($id){
        /* Ziskani konkretni dovolene */
        $dovolena = Vacation::find($id);
        /* Ziskani udaju dovolene */
        $vacation_start = date('Y-m-d\TH:i', strtotime($dovolena->vacation_start));
        $vacation_end = date('Y-m-d\TH:i', strtotime($dovolena->vacation_end));
        $created_at = date('d.m.Y H:i:s', strtotime($dovolena->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($dovolena->updated_at));
        $stav = '';
        /* Definice stavu nahlaseni a nasledne ulozeni do promenne stav */
        if($dovolena->vacation_state == 0){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:whitesmoke;">Nezažádáno</p></div></center>';
        }else if($dovolena->vacation_state == 1){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Odesláno</p></div></center>';
        }else if($dovolena->vacation_state == 2){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:greenyellow;">Schváleno</p></div></center>';
        }else if($dovolena->vacation_state == 3){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:orangered;">Neschváleno</p></div></center>';
        }else if($dovolena->vacation_state == 4){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Přečteno</p></div></center>';
        }
        /* Definice obsahu modalniho okna */
        $out = ''.$stav.'
                <div class="form-group">
                    <div class="row">
                        <label for="vacation_start_edit" class="col-md-2 text-left" style="font-size: 16px;">Datum od(<span class="text-danger">*</span>)</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                <input type="datetime-local" class="form-control" name="vacation_start_edit" id="vacation_start_edit" value="'.$vacation_start.'" autocomplete="on" autofocus>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="vacation_end_edit" class="col-md-2 text-left" style="font-size: 16px;">Datum od(<span class="text-danger">*</span>)</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                <input type="datetime-local" class="form-control" name="vacation_end_edit" id="vacation_end_edit" value="'.$vacation_end.'" autocomplete="on">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="vacation_note_edit" class="col-md-2 text-left" style="font-size: 16px;">Poznámka</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                </div>
                                <textarea name="vacation_note_edit" placeholder="Zadejte poznámku k dovolené [maximálně 180 znaků]..." id="vacation_note_edit" class="form-control" autocomplete="on">'.$dovolena->vacation_note.'</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="d-flex justify-content-center">Dovolená vytvořena '.$created_at.', naposledy aktualizována '.$updated_at.'.</p>';
        /* Odeslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator dovolene, request - udaje zadane zamestnancem
       Ucel: Aktualizace dovolene v databazi */
    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        /* Ziskani konkretni dovolene */
        $dovolena = Vacation::findOrFail($id);
        if($dovolena->vacation_state == 0) { // dovolena se da upravovat jen pokud o ni nebylo zazadano
            $validator = Validator::make($request->all(), ['zacatek_dovolene' => ['required'], 'konec_dovolene' => ['required'], 'poznamka' => ['max:180']]);
            /* Ulozeni udaju o dovolene do promennych a nasledna validace udaju */
            $vacation_start = new DateTime($request->zacatek_dovolene);
            $vacation_end = new DateTime($request->konec_dovolene);
            $puvodniVacation_start = new DateTime($dovolena->vacation_start);
            $puvodniVacation_end = new DateTime($dovolena->vacation_end);
            $difference_vacation = $vacation_end->format('U') - $vacation_start->format('U');
            $chybaDatumy = array();
            $bool_datumy = 0;
            if ($difference_vacation < 0) {
                array_push($chybaDatumy, 'Datum konce dovolené dříve než datum začátku dovolené!');
                $bool_datumy = 1;
            }
            if($difference_vacation == 0){
                array_push($chybaDatumy,'Datum konce dovolené stejné jako datum začátku dovolené!');
                $bool_datumy = 1;
            }
            /* Naplneni pole chybovymi hlaskami */
            foreach ($validator->errors()->all() as $valid) { array_push($chybaDatumy, $valid); }
            /* Odeslani pripadne chybove zpravy*/
            if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }
            /* Indikacni promenna slouzici k indikaci zmeny udaju v ramci dovolene */
            $jeZmena = 0;

            /* Zjisteni zmeny */
            $diffValidDatumStart = $puvodniVacation_start->format('U') - $vacation_start->format('U');
            $diffValidDatumEnd = $puvodniVacation_end->format('U') - $vacation_end->format('U');
            if (($diffValidDatumStart == 0) && ($diffValidDatumEnd == 0) && ($request->poznamka == $dovolena->vacation_note)) {
                $jeZmena = 0;
            } else {
                $jeZmena = 1;
            }
            /* Aktualizace udaju konkretni dovolene v databazi */
            Vacation::where(['vacation_id' => $dovolena->vacation_id])->update(['vacation_start' => $request->zacatek_dovolene, 'vacation_end' => $request->konec_dovolene, 'vacation_note' => $request->poznamka]);
            /* Paklize se neodehrala zadna zmena, tak se uzivateli nic nezobrazi*/
            if ($jeZmena != 1) {
                return response()->json(['success' => '0']);
            } else {
                return response()->json(['success' => 'Dovolená byla úspěšně zaktualizována.']);
            }
        }
        /* Odeslani chybove zpravy v pripade pokusu editovani dovolene, ktera neni ve stavu "Nezažádáno" */
        return response()->json(['fail'=>'Upravovat lze pouze ty dovolené, o které nebylo zažádáno, pokud chcete dovolenou upravit při stavu "Odesláno" (při ostatních to nelze) klikněte na tlačítko "Zrušit žádost", pak lze dovolenou dále upravovat.', 'success' => '']);
    }

    /* Nazev funkce: vacationApply
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Odeslani zadosti o dovolenou firme */
    public function vacationApply($id){
        /* Ziskani konkretni dovolene */
        $vacation = Vacation::find($id);
        /* Pokud je dovolena ve stavu "Nezažádáno", lze o ni zažádat, v jinych stavech to mozne neni */
        if($vacation->vacation_state == 0){
            $vacation->vacation_state = 1;
            $vacation->save();
            return response()->json(['success'=>'Žádost o dovolenou proběhla úspěšně.','fail' => '']);
        }else if($vacation->vacation_state == 1){
            return response()->json(['success'=>'','fail' => 'Už jste o dovolenou zažádal!']);
        }
        return response()->json(['success'=>'','fail' => 'Nelze zažádat o už schválenou, přečtenou či neschválenou dovolenou!']);
    }

    /* Nazev funkce: vacationDeleteApply
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Zruseni odeslani zadosti o dovolenou firme */
    public function vacationDeleteApply($id){
        /* Ziskani konkretni dovolene */
        $vacation = Vacation::find($id);
        /* Pokud je dovolena ve stavu "Nezažádáno", tak zadost nelze zrusit stejne to plati u schvalenych ci neschvalenych dovolenych. */
        if($vacation->vacation_state == 0){
            return response()->json(['success'=>'','fail' => 'Nelze zrušit žádost, která nebyla podána!']);
        }else if($vacation->vacation_state == 1){
            $vacation->vacation_state = 0;
            $vacation->save();
            return response()->json(['success'=>'Zrušení žádosti o dovolenou proběhla úspěšně.','fail' => '']);
        }
        return response()->json(['success'=>'','fail' => 'Nelze zrušit žádost o už schválenou, přečtenou či neschválenou dovolenou!']);
    }

    /* Nazev funkce: destroy
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Odstraneni dovolene zamestnance */
    public function destroy($id){
        /* Ziskani konkretni dovolene */
        $vacation = Vacation::findOrFail($id);
        if($vacation->vacation_state == 0){ //smazat lze jen ty dovolene, ktere jsou ve stavu "Nezažádáno"
            Vacation::find($id)->delete();
            return response()->json(['success'=>'Dovolená byla úspěšně smazána.','fail' => '']);
        }
        return response()->json(['fail'=>'Smazat lze pouze ty dovolené, o které nebylo zažádáno, pokud chcete dovolenou smazat při stavu "Odesláno" (při ostatních to nelze) klikněte na tlačítko "Zrušit žádost", pak lze dovolenou smazat.', 'success' => '']);
    }

}
