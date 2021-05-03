<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DiseasesController extends Controller {
    /* Nazev souboru:  DiseasesController.php */
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
       Ucel: Zobrazeni prislusneho pohledu pro seznam nemocenskych */
    public function index(){
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
           Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        /* Odeslani pohledu spolecne se ziskanymi daty do uzivatelova prohlizece */
        return view('company_actions.diseases_list')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    /* Nazev funkce: getEmployeeDiseases
       Argumenty: zadne
       Ucel: Zobrazeni seznamu nemocenskych v datove tabulce */
    public function getEmployeeDiseases(){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        /* Ziskani nemocenskych zamestnancu firmy */
        $diseases = Disease::getCompanyEmployeesDiseases($user->company_id);
        /* Usek kodu slouzici k vyrenderovani datove tabulky */
        return Datatables::of($diseases)
            ->addIndexColumn()
            ->editColumn('disease_state', function($diseases){ // usek kodu slouzici k zobrazeni stavu nemocenske
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
            ->addColumn('disease_actuality', function($diseases){ // usek kodu slouzici k zobrazeni aktuality nemocenske
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
            ->addColumn('action', function($diseases){ // Vyrenderovani jednotlivych ovladacich tlacitek
                return '<button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#AgreementDiseaseForm" class="btn btn-dark btn-sm" id="obtainDiseaseAgreement"><i class="fa fa-check" aria-hidden="true"></i> Schválit</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#EditDiseaseForm" class="btn btn-primary btn-sm" id="obtainDiseaseEdit"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#DisagreementDiseaseForm" class="btn btn-dark btn-sm" id="obtainDiseaseDisagreement"><i class="fa fa-times" aria-hidden="true"></i> Neschválit</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#SeenDiseaseForm" class="btn btn-dark btn-sm tlacitkoPrecteno" id="obtainDiseaseSeen"><i class="fa fa-book" aria-hidden="true"></i> Přečteno</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#DeleteDiseaseForm" class="btn btn-danger btn-sm" style="margin-top:6px;" id="obtainDiseaseDelete"><i class="fa fa-trash-o" aria-hidden="true"></i> Smazat</button>
                        <button type="button" data-id="'.$diseases->disease_id.'" data-toggle="modal" data-target="#SentDiseaseForm" class="btn btn-dark btn-sm" style="margin-top:6px;" id="obtainDiseaseSent"><i class="fa fa-paper-plane" aria-hidden="true"></i> Odesláno</button>';
            })
            ->rawColumns(['action', 'disease_state', 'disease_actuality']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: store
       Argumenty: request - udaje zadane firmou pro tvorbu nove nemocenske
       Ucel: Vytvoreni nove nemocenske */
    public function store(Request $request){
        /* Definice pravidel pro validaci a jeji provedeni */
        $validator = Validator::make($request->all(), ['nazev_nemoc' => ['required'], 'nemoc_zacatek' =>  ['required'], 'nemoc_konec' =>  ['required'], 'zamestnanec_vyber' =>  ['required'], 'poznamka' => ['max:180']]);

        /* Pokud je libovolny udaj/e inkorektni, jsou uzivatelovi zobrazeny chybove hlasky */
        if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]); }

        /* Ziskani konkretniho zamestnance */
        $zamestnanec = Employee::find($request->zamestnanec_vyber);
        /* Ziskani zacatku a konce nemocenske */
        $disease_from = new DateTime($request->nemoc_zacatek);
        $disease_to = new DateTime($request->nemoc_konec);

        /* Promenna, ktera slouzi k overeni, ze datum konce nemocenske neni driv nez datum zacatku nemocenske */
        $difference_disease = $disease_to->format('U') - $disease_from->format('U');
        /* Pole slouzici pro ulozeni chybovych hlasek */
        $chybaDatumy = array();
        /* Zjisteni, zdali nastala vyse zmineny typ chyby */
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

        /* Naplneni ostatnich hlasek do pole */
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }

        /* Pokud se objevila chyba je oznamena uzivatelovi */
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }

        /* Vytvoreni nemocenske v databazi */
        Disease::create(['disease_name' => $request->nazev_nemoc, 'disease_from' => $request->nemoc_zacatek, 'disease_to' => $request->nemoc_konec, 'disease_note' => $request->poznamka, 'disease_state' => 1, 'employee_id' => $request->zamestnanec_vyber]);

        /* Odeslani odpovedi uzivateli */
        return response()->json(['success'=>'Nemocenská zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspešně vytvořena.']);
    }

    /* Nazev funkce: edit
       Argumenty: id - jednoznacny identifikator nemocenske
       Ucel: Zobrazeni obsahu pro upravu konkretni nemocenske */
    public function edit($id){
        $disease = Disease::find($id);
        /* Ziskani udaju */
        $disease_from = date('Y-m-d\TH:i', strtotime($disease->disease_from));
        $disease_to = date('Y-m-d\TH:i', strtotime($disease->disease_to));
        $created_at = date('d.m.Y H:i:s', strtotime($disease->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($disease->updated_at));
        $stav = '';
        /* Zobrazeni stavu nemocenske na zaklade jeho hodnoty */
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
        /* Ulozeni obsahu (formulare) do promenne */
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
                                <textarea name="disease_note_edit" placeholder="Zadejte poznámku k nemocenské..." id="disease_note_edit" class="form-control" autocomplete="on">'.$disease->disease_note.'</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="d-flex justify-content-center">Nemocenská vytvořena '.$created_at.', naposledy aktualizována '.$updated_at.'.</p>';
        /* Zaslani HTML obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator nemocenske, request - zadane udaje
       Ucel: Aktualizace zadanych udaju v ramci upravy nemocenske */
    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        /* Ziskani konkretni nemocenske */
        $disease = Disease::find($id);
        /* Pravidla pro validaci */
        $validator = Validator::make($request->all(), ['nazev_nemoci' => ['required'], 'nemoc_zacatek' =>  ['required'], 'nemoc_konec' =>  ['required'], 'poznamka' => ['max:180']]);

        /* Ziskani novych a starych datumu do promennych */
        $disease_from = new DateTime($request->nemoc_zacatek);
        $disease_to = new DateTime($request->nemoc_konec);
        $puvodniDisease_from = new DateTime($disease->disease_from);
        $puvodniDisease_to = new DateTime($disease->disease_to);

        /* Tato sekce kodu slouzi k realizaci validace udaju, princip je stejny jako u metody store */
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

        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }

        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }

        /* Usek kodu pro zjisteni, zdali uzivatel provedl nejakou zmenu */
        $zmenaUdaje = 0;
        $diffValidDatumStart = $puvodniDisease_from->format('U') - $disease_from->format('U');
        $diffValidDatumEnd = $puvodniDisease_to->format('U') - $disease_to->format('U');
        if(($diffValidDatumStart == 0) && ($diffValidDatumEnd == 0) && ($request->poznamka == $disease->disease_note)
            && ($request->nazev_nemoci == $disease->disease_name)){
            $zmenaUdaje = 0;
        }else{
            $zmenaUdaje = 1;
        }

        /* Aktualizace udaju konkretni nemocenske v databazi */
        Disease::where(['disease_id' => $disease->disease_id])->update(['disease_name' => $request->nazev_nemoci, 'disease_from' => $request->nemoc_zacatek, 'disease_to' => $request->nemoc_konec, 'disease_note' => $request->poznamka]);

        /* Odeslani odpovedi uzivateli. Prazdna odpoved je v pripade, kdy uzivatel nezmeni zadny udaj */
        if($zmenaUdaje != 1){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Nemocenská byla úspěšně zaktualizována.']);
        }
    }

    /* Nazev funkce: diseaseAgree
       Argumenty: id - jednoznacny identifikator nemocenske
       Ucel: Zmena stavu nemocenske na stav "Schvaleno" */
    public function diseaseAgree($id){
        $disease = Disease::find($id);
        if($disease->disease_state == 1 || $disease->disease_state == 3 || $disease->disease_state == 4){
            $disease->disease_state = 2;
            $disease->save();
            return response()->json(['success'=>'Schválení nemocenské proběhlo úspěšně.','fail' => '']);
        }else if($disease->disease_state == 2){
            return response()->json(['success'=>'','fail' => 'Už jste nemocenskou schválil!']);
        }
    }

    /* Nazev funkce: diseaseDisagree
       Argumenty: id - jednoznacny identifikator nemocenske
       Ucel: Zmena stavu nemocenske na stav "Neschvaleno" */
    public function diseaseDisagree($id){
        $disease = Disease::find($id);
        if($disease->disease_state == 3){
            return response()->json(['success'=>'','fail' => 'Nelze neschválit žádost o dovolenou, která již neschválena byla!']);
        }else if($disease->disease_state == 1 || $disease->disease_state == 2 || $disease->disease_state == 4){
            $disease->disease_state = 3;
            $disease->save();
            return response()->json(['success'=>'Neschválení žádosti o dovolenou proběhlo úspěšně.','fail' => '']);
        }
    }

    /* Nazev funkce: diseaseSeen
        Argumenty: id - jednoznacny identifikator nemocenske
        Ucel: Zmena stavu nemocenske na stav "Precteno" */
    public function diseaseSeen($id){
        $disease = Disease::find($id);
        if($disease->disease_state == 1 || $disease->disease_state == 2 || $disease->disease_state == 3){
            $disease->disease_state = 4;
            $disease->save();
            return response()->json(['success'=>'Žádost je nyní ve stavu přečteno.','fail' => '']);
        }else if($disease->disease_state == 4){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu přečtení nachází!']);
        }
    }

    /* Nazev funkce: diseaseSent
     Argumenty: id - jednoznacny identifikator nemocenske
     Ucel: Zmena stavu nemocenske na stav "Odeslano" */
    public function diseaseSent($id){
        $disease = Disease::find($id);
        if($disease->disease_state == 2 || $disease->disease_state == 3 || $disease->disease_state == 4){
            $disease->disease_state = 1;
            $disease->save();
            return response()->json(['success'=>'Žádost je nyní ve výchozím stavu "Odesláno".','fail' => '']);
        }else if($disease->disease_state == 1){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu odeslání nachází!']);
        }
    }

    /* Nazev funkce: destroy
    Argumenty: id - jednoznacny identifikator nemocenske
    Ucel: Smazani nemocenske */
    public function destroy($id){
        Disease::find($id)->delete();
        return response()->json(['success'=>'Nemocenská byla úspěšně smazána.','fail' => '']);
    }
}
