<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EmployeeDiseaseController extends Controller {
    /* Nazev souboru:  EmployeeDiseaseController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy nemocenskych v uctu s roli zamestnance. Ovladani datove tabulky je tu taktez naprogramovano.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni nemocenskych konkretniho zamestnance */
    public function index(){
        $user = Auth::user();
        return view('employee_actions.diseases_list')
            ->with('profilovka',$user->employee_picture)->with('employee_url', $user->employee_url);
    }

    /* Nazev funkce: getEmployeeDiseases
       Argumenty: zadne
       Ucel: Zobrazeni datove tabulky reprezentujici seznam nemocenskych konkretniho zamestnance */
    public function getEmployeeDiseases(){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        /* Ziskani nemocenskych konkretniho zamestnance */
        $diseases = Disease::getEmployeeDiseases($user->employee_id);
        return Datatables::of($diseases)
            ->addIndexColumn()
            ->editColumn('disease_state', function($diseases){ // uprava sloupce pro zobrazeni statusu nemocenske
                if($diseases->disease_state == 0){
                    return '<center><p style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezažádáno</p></center>';
                }else if($diseases->disease_state == 1){
                    return '<center><p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p></center>';
                }else if($diseases->disease_state == 2){
                    return '<center><p style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p></center>';
                }else if($diseases->disease_state == 3){
                    return '<center><p style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p></center>';
                }else if($diseases->disease_state == 4){
                    return '<center><p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p></center>';
               }
            })
            ->addColumn('disease_actuality', function($diseases){ // pridani sloupce pro zobrazeni aktualnosti nemocenske
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $diseases->disease_from);
                $end = Carbon::createFromFormat('Y-m-d H:i:s', $diseases->disease_to);
                $now = Carbon::now();
                $rozhod_start = $now->gte($start);
                $rozhod_end = $now->lte($end);
                $rozhod_end2 = $now->gte($end);
                if($rozhod_start == 1 && $rozhod_end == 1){
                    return '<center><p style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Probíhá</p></center>';
                }else if($rozhod_end2 == 1){
                    return '<center><p style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Proběhla</p></center>';
                }else{
                    return '<center><p style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Proběhne</p></center>';
                }
            })
            ->addColumn('action', function($diseases){ // definice ovladacich tlacitek datove tabulky
                return '<button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#ApplyDiseaseForm" id="obtainDiseaseApply" class="btn btn-dark btn-sm"><i class="fa fa-bullhorn" aria-hidden="true"></i> Zažádat</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#EditDiseaseForm" id="obtainDiseaseEdit" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editovat</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#DeleteApplyDiseaseForm" id="obtainDiseaseDeleteApply" class="btn btn-dark btn-sm tlacitkoZrusitZadostNemoc"><i class="fa fa-times" aria-hidden="true"></i> Zrušit žádost</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#DeleteDiseaseForm" class="btn btn-danger btn-sm" id="obtainDiseaseDelete" style="margin-top:6px;">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>';
            })
            ->rawColumns(['action', 'disease_state', 'disease_actuality'])  // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: store
       Argumenty: request - zadane udaje uzivatelem
       Ucel: Ulozeni nemocenske do databaze */
    public function store(Request $request){
        $user = Auth::user();
        /* Definice pravidel pro validaci a provedeni samotne validace */
        $validator = Validator::make($request->all(), ['nazev_nemoc' => ['required'], 'nemoc_zacatek' =>  ['required'], 'nemoc_konec' =>  ['required'], 'poznamka' => ['max:180']]);

        /* Ziskani udaju pro vlastni validaci */
        $disease_from = new DateTime($request->nemoc_zacatek);
        $disease_to = new DateTime($request->nemoc_konec);
        $difference_disease = $disease_to->format('U') - $disease_from->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;

        /* Zjisteni, zdali datum konce nemocenske je dříve než datum začátku nemocenske nebo zdali neni stejne */
        if($difference_disease < 0){
            array_push($chybaDatumy,'Datum konce nemocenské dříve než datum začátku nemocenské!');
            $bool_datumy = 1;
        }

        if($difference_disease == 0){
            array_push($chybaDatumy,'Datum konce nemocenské stejné jako datum začátku nemocenské!');
            $bool_datumy = 1;
        }

        /* Iterace skrze chybove hlasky a pridavani jich do pole chybaDatumy*/
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        /* Poslani pripadnych chyb uzivateli */
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['fail' => $chybaDatumy]); }
        /* Vytvoreni nemocenske v databazi */
        Disease::create(['disease_name' => $request->nazev_nemoc, 'disease_from' => $request->nemoc_zacatek, 'disease_to' => $request->nemoc_konec, 'disease_note' => $request->poznamka, 'disease_state' => 0, 'employee_id' => $user->employee_id, ]);
        /* Odeslani odpovedi uzivateli */
        return response()->json(['success' => 'Nemocenská byla úspešně vytvořena.']);
    }

    /* Nazev funkce: edit
       Argumenty: id - jednoznacny identifikator nemocenske
       Ucel: Zobrazeni obsahu (formulare) do modalniho okna */
    public function edit($id){
        /* Ziskani konkretni nemocenske */
        $disease = Disease::find($id);
        /* Ziskani udaju o nemocenske */
        $disease_from = date('Y-m-d\TH:i', strtotime($disease->disease_from));
        $disease_to = date('Y-m-d\TH:i', strtotime($disease->disease_to));
        $created_at = date('d.m.Y H:i:s', strtotime($disease->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($disease->updated_at));
        $stav = '';
        /* Definice stavu nemocenske */
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
        /* Definice obsahu modalniho okna */
        $out = ''.$stav.'
                  <div class="form-group">
                        <div class="row">
                            <label for="disease_name_edit" class="col-md-2 text-left" style="font-size: 16px;">Název(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-medkit" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Zadejte název nemoci..." autocomplete="on" name="disease_name_edit" id="disease_name_edit" value="'.$disease->disease_name.'" autofocus>
                                </div>
                            </div>
                         </div>
                   </div>
                   <div class="form-group">
                        <div class="row">
                            <label for="disease_from_edit" class="col-md-2 text-left" style="font-size: 16px;">Datum od(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="disease_from_edit" id="disease_from_edit" value="'.$disease_from.'" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="disease_to_edit" class="col-md-2 text-left" style="font-size: 16px;">Datum od(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="disease_to_edit" id="disease_to_edit" value="'.$disease_to.'" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="disease_note_edit" class="col-md-2 text-left" style="font-size: 16px;">Poznámka</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                    </div>
                                    <textarea name="disease_note_edit" placeholder="Zadejte poznámku k nemocenské [maximálně 180 znaků]..." id="disease_note_edit" class="form-control" autocomplete="on">'.$disease->disease_note.'</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="d-flex justify-content-center">Nemocenská vytvořena '.$created_at.', naposledy aktualizována '.$updated_at.'.</p>';
        /* Odeslani odpovedi uzivateli */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator nemocenske, request - udaje zadane uzivatelem
       Ucel: Aktualizace nemocenske v databazi */
    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        /* Ziskani konkretni nemocenske */
        $disease = Disease::find($id);
        if($disease->disease_state == 0) {
                /* Pravidla pro validaci */
                $validator = Validator::make($request->all(), ['nazev_nemoci' => ['required'], 'nemoc_zacatek' => ['required'], 'nemoc_konec' => ['required'], 'poznamka' => ['max:180']]);
                /* Ziskani udaju o smene */
                $disease_from = new DateTime($request->nemoc_zacatek);
                $disease_to = new DateTime($request->nemoc_konec);
                $puvodniDisease_from = new DateTime($disease->disease_from);
                $puvodniDisease_to = new DateTime($disease->disease_to);
                $difference_disease = $disease_to->format('U') - $disease_from->format('U');
                /* Pole pro ulozeni chybovych hlasek */
                $chybaDatumy = array();
                /* Indikator chyby s datumy */
                $bool_datumy = 0;

                /* Osetreni moznych chybovych stavu */
                if ($difference_disease < 0) {
                    array_push($chybaDatumy, 'Datum konce nemocenské dříve než datum začátku nemocenské!');
                    $bool_datumy = 1;
                }

                if ($difference_disease == 0) {
                    array_push($chybaDatumy,'Datum konce nemocenské stejné jako datum začátku nemocenské!');
                    $bool_datumy = 1;
                }

                /* Ulozeni chybovych hlasek do pole chybaDatumy */
                foreach ($validator->errors()->all() as $valid) { array_push($chybaDatumy, $valid); }
                /* Poslani chybovych hlasek uzivateli */
                if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }

                /* Promenna indikujici zmenu udaju */
                $jeZmena = 0;

                /* Usek kodu, diky kteremu se zjisti, zdali doslo ke zmene udaju */
                $diffValidDatumStart = $puvodniDisease_from->format('U') - $disease_from->format('U');
                $diffValidDatumEnd = $puvodniDisease_to->format('U') - $disease_to->format('U');
                if (($diffValidDatumStart == 0) && ($diffValidDatumEnd == 0) && ($request->poznamka == $disease->disease_note)
                    && ($request->nazev_nemoci == $disease->disease_name)) {
                    $jeZmena = 0;
                } else {
                    $jeZmena = 1;
                }

                /* Aktualizace udaju konkretni nemocenske v databazi */
                Disease::where(['disease_id' => $disease->disease_id])->update(['disease_name' => $request->nazev_nemoci, 'disease_from' => $request->nemoc_zacatek, 'disease_to' => $request->nemoc_konec, 'disease_note' => $request->poznamka]);

                /* Pokud uzivatel nezmenil zadny udaj, nic mu neni zobrazeno, pokud zmenil libovolny udaj je mu zobrazena zprava o uspechu aktualizace udaju */
                if ($jeZmena != 1) {
                    return response()->json(['success' => '0']);
                } else {
                    return response()->json(['success' => 'Nemocenská byla úspěšně zaktualizována.']);
                }
         }
        /* Odeslani odpovedi, pokud nastala chyba */
        return response()->json(['fail'=>'Upravovat lze pouze ty nemocenské, o které nebylo zažádáno, pokud chcete nemocenskou upravit při stavu "Odesláno" (při ostatních to nelze) klikněte na tlačítko "Zrušit žádost", pak lze nemocenskou dále upravovat.', 'success' => '']);
    }

    /* Nazev funkce: diseaseApply
       Argumenty: id - jednoznacny identifikator nemocenske
       Ucel: Poslani zadosti o nemocenskou */
    public function diseaseApply($id){
        /* Ziskani konkretni nemocenske */
        $disease = Disease::find($id);
        /* Pokud o nemocenskou nebylo zazadano drive, tak se provede zazadani, tedy zmena stavu nemocenske */
        if($disease->disease_state == 0){
            $disease->disease_state = 1;
            $disease->save();
            return response()->json(['success'=>'Žádost o nemocenskou proběhla úspěšně.','fail' => '']);
        }else if($disease->disease_state == 1){
            return response()->json(['success'=>'','fail' => 'Už jste o nemocenskou zažádal!']);
        }
        /* Odeslani chyboveho stavu uzivateli */
        return response()->json(['success'=>'','fail' => 'Nelze zažádat o už schválenou, či neschválenou nemocenskou!']);
    }

    /* Nazev funkce: diseaseDeleteApply
       Argumenty: id - jednoznacny identifikator nemocenske
       Ucel: Zruseni zadosti o nemocenskou */
    public function diseaseDeleteApply($id){
        /* Ziskani konkretni nemocenske */
        $disease = Disease::findOrFail($id);
        /* Pokud o nemocenskou nebylo zazadano nebo uz byla schvalena ci neschvalena, tak dojde k chybovemu stavu, jinak se nemocenske zmeni stav na "Nezažádáno" */
        if($disease->disease_state == 0){
            return response()->json(['success'=>'','fail' => 'Nelze zrušit žádost, která nebyla podána!']);
        }else if($disease->disease_state == 1){
            $disease->disease_state = 0;
            $disease->save();
            return response()->json(['success'=>'Zrušení žádosti o nemocenskou proběhla úspěšně.','fail' => '']);
        }
        /* Odeslani zpravy, pokud nastala chyba */
        return response()->json(['success'=>'','fail' => 'Nelze zrušit žádost o už schválenou, či neschválenou nemocenskou!']);
    }

    /* Nazev funkce: destroy
       Argumenty: id - jednoznacny identifikator nemocenske
       Ucel: Smazani nemocenske z databaze */
    public function destroy($id){
        /* Ziskani konkretni nemocenske */
        $disease = Disease::find($id);
        if($disease->disease_state == 0){ // pokud se nemocenska nachazi ve stavu "Nezažádáno" je možné ji smazat, v ostatních stavech to není možné
            Disease::find($id)->delete();
            return response()->json(['success'=>'Nemocenská byla úspěšně smazána.','fail' => '']);
        }
        /* Odeslani chybove zpravy */
        return response()->json(['fail'=>'Smazat lze pouze ty nemocenské, o které nebylo zažádáno, pokud chcete nemocenskou smazat při stavu "Vyřizuje se" (při ostatních to nelze) klikněte na tlačítko "Zrušit žádost", pak lze nemocenskou smazat.', 'success' => '']);
    }
}
