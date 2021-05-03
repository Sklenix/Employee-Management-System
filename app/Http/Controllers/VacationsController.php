<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use App\Models\Vacation;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class VacationsController extends Controller {
    /* Nazev souboru:  VacationsController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy dovolenych v uctu s roli firmy. Slouzi take k ovladani datove tabulky.

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
       Ucel: Zobrazeni prislusneho pohledu pro seznam dovolenych */
    public function index(){
        $user = Auth::user();
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
         Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        /* Odeslani pohledu spolecne se ziskanymi daty do uzivatelova prohlizece */
        return view('company_actions.vacations_list')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    /* Nazev funkce: getEmployeeVacations
       Argumenty: zadne
       Ucel: Zobrazeni seznamu dovolenych v datove tabulce */
    public function getEmployeeVacations(){
        $user = Auth::user();
        date_default_timezone_set('Europe/Prague');
        /* Ziskani dovolenych */
        $vacations = Vacation::getCompanyEmployeesVacations($user->company_id);
        /* Usek kodu slouzici k vyrenderovani datove tabulky */
        return Datatables::of($vacations)
            ->addIndexColumn()
            ->editColumn('vacation_state', function($vacations){ // usek kodu slouzici k zobrazeni stavu dovolene
                if($vacations->vacation_state == 0){
                    return '<center><p class="col-md-10" style="color:whitesmoke;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezažádáno</p></center>';
                }else if($vacations->vacation_state == 1){
                    return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Odesláno</p></center>';
                }else if($vacations->vacation_state == 2){
                    return '<center><p class="col-md-10" style="color:greenyellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Schváleno</p></center>';
                }else if($vacations->vacation_state == 3){
                    return '<center><p class="col-md-10" style="color:red;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Neschváleno</p></center>';
                }else if($vacations->vacation_state == 4){
                    return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Přečteno</p></center>';
                }
            })
            ->addColumn('vacation_actuality', function($vacations){ // usek kodu slouzici k zobrazeni aktuality dovolene
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
            ->addColumn('action', function($vacations){ // Definice jednotlivych ovladacich tlacitek
                return '<button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#AgreementVacationForm" id="obtainVacationAgreement" class="btn btn-dark btn-sm"><i class="fa fa-check" aria-hidden="true"></i> Schválit</button>
                        <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#EditVacationForm" id="obtainEditVacation" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                        <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#DisagreementVacationForm" id="obtainVacationDisagreement" class="btn btn-dark btn-sm"><i class="fa fa-times" aria-hidden="true"></i> Neschválit</button>
                        <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#SeenVacationForm" id="obtainVacationSeen" class="btn btn-dark btn-sm tlacitkoPrectenoDovolene"><i class="fa fa-book" aria-hidden="true"></i> Přečteno</button>
                        <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#DeleteVacationForm" class="btn btn-danger btn-sm" id="obtainVacationDelete" style="margin-top:6px;">&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat&nbsp;&nbsp;</button>
                        <button type="button" data-id="'.$vacations->vacation_id.'" data-toggle="modal" data-target="#SentVacationForm" class="btn btn-dark btn-sm" id="obtainVacationSent" style="margin-top:6px;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Odesláno</button>';
            })
            ->rawColumns(['action', 'vacation_state', 'vacation_actuality']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: store
       Argumenty: request - udaje zadane firmou pro tvorbu nove dovolene
       Ucel: Vytvoreni nove dovolene */
    public function store(Request $request){
        /* Definice pravidel pro validaci a nasledne jeji provedeni */
        $validator = Validator::make($request->all(), ['zacatek_dovolene' => ['required'], 'konec_dovolene' =>  ['required'], 'zamestnanec_vyber' =>  ['required'], 'poznamka' => ['max:180']]);
        /* Pokud je libovolny udaj/e inkorektni, jsou uzivatelovi zobrazeny chybove hlasky */
        if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]); }
        /* Ziskani konkretniho zamestnance */
        $zamestnanec = Employee::find($request->zamestnanec_vyber);
        /* Ziskani zacatku a konce dovolene */
        $vacation_start = new DateTime($request->zacatek_dovolene);
        $vacation_end = new DateTime($request->konec_dovolene);

        /* Promenna, ktera slouzi k overeni, ze datum konce dovolene neni driv nez datum zacatku dovolene */
        $difference_vacation = $vacation_end->format('U') - $vacation_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        /* Naplneni hlasky do pole */
        if($difference_vacation < 0){
            array_push($chybaDatumy,'Datum konce dovolené dříve než datum začátku dovolené!');
            $bool_datumy = 1;
        }

        if($difference_vacation == 0){
            array_push($chybaDatumy,'Datum konce dovolené stejné jako datum začátku dovolené!');
            $bool_datumy = 1;
        }

        /* Naplneni ostatnich hlasek do pole */
        foreach ($validator->errors()->all() as $valid){ array_push($chybaDatumy,$valid); }
        /* Pokud se objevila chyba je oznamena uzivatelovi */
        if ($validator->fails() || $bool_datumy == 1) { return response()->json(['errors' => $chybaDatumy]); }

        /* Vytvoreni dovolene v databazi */
        Vacation::create(['vacation_start' => $request->zacatek_dovolene, 'vacation_end' => $request->konec_dovolene, 'vacation_note' => $request->poznamka, 'vacation_state' => 1, 'employee_id' => $request->zamestnanec_vyber]);

        /* Odeslani odpovedi uzivateli */
        return response()->json(['success'=>'Dovolená pro zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspešně vytvořena.']);
    }

    /* Nazev funkce: edit
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Zobrazeni obsahu pro upravu konkretni dovolene */
    public function edit($id){
        $dovolena = Vacation::find($id);
        /* Ziskani udaju o dovolene v korektnim formatu */
        $vacation_start = date('Y-m-d\TH:i', strtotime($dovolena->vacation_start));
        $vacation_end = date('Y-m-d\TH:i', strtotime($dovolena->vacation_end));
        $created_at = date('d.m.Y H:i:s', strtotime($dovolena->created_at));
        $updated_at = date('d.m.Y H:i:s', strtotime($dovolena->updated_at));
        $stav = '';
        /* Zobrazeni stavu dovolene na zaklade jeji hodnoty */
        if($dovolena->vacation_state == 0){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:whitesmoke;">Nezažádáno</p></div></center>';
        }else if($dovolena->vacation_state == 1){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Odesláno</p></div></center>';
        }else if($dovolena->vacation_state == 2){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellowgreen;">Schváleno</p></div></center>';
        }else if($dovolena->vacation_state == 3){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:orangered;">Neschváleno</p></div></center>';
        }else if($dovolena->vacation_state == 4){
            $stav = '<center><div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:15px;border-radius: 10px;background-color: #333333;"><p style="padding-top:7px;padding-bottom:7px;font-size:17px;color:yellow;">Přečteno</p></div></center>';
        }
        /* Ulozeni obsahu (formulare) do promenne */
        $out = ''.$stav.'
                 <div class="form-group">
                    <div class="row">
                        <label for="vacation_start_edit" class="col-md-2 text-left" style="font-size: 16px;">Datum od(<span class="text-danger">*</span>)</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                </div>
                                <input type="datetime-local" class="form-control" name="vacation_start_edit" id="vacation_start_edit" value="'.$vacation_start.'" autofocus>
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
                                    <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                </div>
                                <input type="datetime-local" class="form-control" name="vacation_end_edit" id="vacation_end_edit" value="'.$vacation_end.'">
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
                                    <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                </div>
                                <textarea name="vacation_note_edit" placeholder="Zadejte poznámku k dovolené... [maximálně 180 znaků]" id="vacation_note_edit" class="form-control" autocomplete="on">'.$dovolena->vacation_note.'</textarea>
                            </div>
                        </div>
                    </div>
                 </div>
                 <p class="d-flex justify-content-center">Dovolená vytvořena '.$created_at.', naposledy aktualizována '.$updated_at.'.</p>';
        /* Zaslani HTML obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator dovolene, request - zadane udaje
       Ucel: Aktualizace zadanych udaju v ramci upravy dovolene */
    public function update(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        /* Ziskani konkretni dovolene */
        $dovolena = Vacation::find($id);
        /* Pravidla pro validaci */
        $validator = Validator::make($request->all(), ['zacatek_dovolene' => ['required'], 'konec_dovolene' =>  ['required'], 'poznamka' => ['max:180']]);

        /* Ziskani novych a starych datumu do promennych */
        $vacation_start = new DateTime($request->zacatek_dovolene);
        $vacation_end = new DateTime($request->konec_dovolene);
        $puvodniVacation_start = new DateTime($dovolena->vacation_start);
        $puvodniVacation_end = new DateTime($dovolena->vacation_end);

        /* Zjisteni, zdali datum konce dovolené je dříve než datum začátku dovolené nebo zdali neni stejne */
        $difference_vacation = $vacation_end->format('U') - $vacation_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_vacation < 0){
            array_push($chybaDatumy,'Datum konce dovolené dříve, než datum začátku dovolené!');
            $bool_datumy = 1;
        }
        if($difference_vacation == 0){
            array_push($chybaDatumy,'Datum konce dovolené stejné jako datum začátku dovolené!');
            $bool_datumy = 1;
        }

        foreach ($validator->errors()->all() as $valid){array_push($chybaDatumy,$valid);}

        if ($validator->fails() || $bool_datumy == 1) {return response()->json(['errors' => $chybaDatumy]);}

        /* Usek kodu pro zjisteni, zdali uzivatel provedl nejakou zmenu */
        $jeZmena = 0;
        $diffValidDatumStart = $puvodniVacation_start->format('U') - $vacation_start->format('U');
        $diffValidDatumEnd = $puvodniVacation_end->format('U') - $vacation_end->format('U');
        if(($diffValidDatumStart == 0) && ($diffValidDatumEnd == 0) && ($request->poznamka == $dovolena->vacation_note)){
            $jeZmena = 0;
        }else{
            $jeZmena = 1;
        }

        /* Aktualizace udaju konkretni dovolene v databazi */
        Vacation::where(['vacation_id' => $dovolena->vacation_id])->update(['vacation_start' => $request->zacatek_dovolene, 'vacation_end' => $request->konec_dovolene, 'vacation_note' => $request->poznamka]);

        /* Odeslani odpovedi uzivateli. Prazdna odpoved je v pripade, kdy uzivatel nezmeni zadny udaj */
        if($jeZmena != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Dovolená byla úspěšně zaktualizována.']);
        }
    }

    /* Nazev funkce: vacationAgree
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Zmena stavu dovolene na stav "Schvaleno" */
    public function vacationAgree($id){
        $vacation = Vacation::find($id);
        if($vacation->vacation_state == 1 || $vacation->vacation_state == 3 || $vacation->vacation_state == 4){
            $vacation->vacation_state = 2;
            $vacation->save();
            return response()->json(['success'=>'Schválení dovolené proběhlo úspěšně.','fail' => '']);
        }else if($vacation->vacation_state == 2){
            return response()->json(['success'=>'','fail' => 'Už jste dovolenou schválil!']);
        }
    }

    /* Nazev funkce: vacationDisagree
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Zmena stavu dovolene na stav "Neschvaleno" */
    public function vacationDisagree($id){
        $vacation = Vacation::find($id);
        if($vacation->vacation_state == 3){
            return response()->json(['success'=>'','fail' => 'Nelze neschválit žádost o dovolenou, která již neschválena byla!']);
        }else if($vacation->vacation_state == 1 || $vacation->vacation_state == 2 || $vacation->vacation_state == 4){
            $vacation->vacation_state = 3;
            $vacation->save();
            return response()->json(['success'=>'Neschválení žádosti o dovolenou proběhlo úspěšně.','fail' => '']);
        }
    }

    /* Nazev funkce: vacationSeen
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Zmena stavu dovolene na stav "Precteno" */
    public function vacationSeen($id){
        $vacation = Vacation::find($id);
        if($vacation->vacation_state == 1 || $vacation->vacation_state == 2 || $vacation->vacation_state == 3){
            $vacation->vacation_state = 4;
            $vacation->save();
            return response()->json(['success'=>'Žádost je nyní ve stavu přečteno.','fail' => '']);
        }else if($vacation->vacation_state == 4){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu přečtení nachází!']);
        }
    }

    /* Nazev funkce: diseaseSent
       Argumenty: id - jednoznacny identifikator dovolene
       Ucel: Zmena stavu dovolene na stav "Odeslano" */
    public function vacationSent($id){
        $vacation = Vacation::find($id);
        if($vacation->vacation_state == 2 || $vacation->vacation_state == 3 || $vacation->vacation_state == 4){
            $vacation->vacation_state = 1;
            $vacation->save();
            return response()->json(['success'=>'Žádost je nyní ve výchozím stavu "Odesláno".','fail' => '']);
        }else if($vacation->vacation_state == 1){
            return response()->json(['success'=>'','fail' => 'Žádost už se ve stavu odeslání nachází!']);
        }
    }

    /* Nazev funkce: destroy
    Argumenty: id - jednoznacny identifikator dovolene
    Ucel: Smazani dovolene */
    public function destroy($id){
        Vacation::find($id)->delete();
        return response()->json(['success'=>'Dovolená byla úspěšně smazána.','fail' => '']);
    }
}
