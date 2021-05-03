<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RatingDatatableController extends Controller {
    /* Nazev souboru:  RatingDatatableController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci zobrazeni prehledu hodnoceni zamestnancu v uctu s roli firmy.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni prehledu hodnoceni zamestnancu konkretni firmy */
    public function index(){
        $user = Auth::user();
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
           Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        /* Zaslani pohledu spolecne se ziskanymi daty uzivateli */
        return view('company_actions.rate_list')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance);
    }

    /* Nazev funkce: getRatings
       Argumenty: zadne
       Ucel: Zobrazeni datove tabulky reprezentujici seznam zraneni zamestnancu firmy */
    public function getRatings(){
        $user = Auth::user();
        /* Ziskani zamestnancu firmy */
        $zamestnanci = Employee::where('employee_company',$user->company_id);
        return Datatables::of($zamestnanci)
            ->addIndexColumn()
            ->editColumn('employee_reliability', function($zamestnanci){ // uprava sloupce pro zobrazeni spolehlivosti
                $odpoved = '';
                if($zamestnanci->employee_reliability == NULL){
                    return 'Nehodnoceno';
                }else{
                    if($zamestnanci->employee_reliability == 0){
                        return 0;
                    }else {
                        for ($i = 0; $i < $zamestnanci->employee_reliability; $i++) { // vykresleni skore jako pocet hvezdicek
                            $odpoved .= '<i class="fa fa-star"></i>';
                        }
                        return $odpoved;
                    }
                }
            })
            ->editColumn('employee_absence', function($zamestnanci){ // uprava sloupce pro zobrazeni dochvilnosti
                $odpoved = '';
                if($zamestnanci->employee_absence == NULL){
                    return 'Nehodnoceno';
                }else{
                    if($zamestnanci->employee_absence == 0){
                        return 0;
                    }else{
                        for ($i = 0; $i < $zamestnanci->employee_absence; $i++){ // vykresleni skore jako pocet hvezdicek
                            $odpoved .= '<i class="fa fa-star"></i>';
                        }
                        return $odpoved;
                    }
                }
            })
            ->editColumn('employee_workindex', function($zamestnanci){ // uprava sloupce pro zobrazeni pracovitosti
                $odpoved = '';
                if($zamestnanci->employee_workindex == NULL){
                    return 'Nehodnoceno';
                }else{
                    if($zamestnanci->employee_workindex == 0){
                        return 0;
                    }else {
                        for ($i = 0; $i < $zamestnanci->employee_workindex; $i++) { // vykresleni skore jako pocet hvezdicek
                            $odpoved .= '<i class="fa fa-star"></i>';
                        }
                        return $odpoved;
                    }
                }
            })
            ->editColumn('employee_overall', function($zamestnanci){  // uprava sloupce pro zobrazeni celkoveho skore
                if($zamestnanci->employee_overall == NULL){
                    return 'Nedefinováno';
                }else{ // vypocet celkoveho skore
                    return round(($zamestnanci->employee_reliability + $zamestnanci->employee_absence + $zamestnanci->employee_workindex) / 3,2);
                }
            })
            ->addColumn('action', function($zamestnanci){
                return '<button type="button" data-id="'.$zamestnanci->employee_id.'" data-toggle="modal" data-target="#RateEmployeeForm" id="obtainEmployeeRate" class="btn btn-dark btn-sm"><i class="fa fa-check-square"></i> Přehodnotit</button>';
            })
            ->rawColumns(['action','employee_overall', 'employee_reliability','employee_absence','employee_workindex']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

    /* Nazev funkce: editRate
       Argumenty: id - jednoznacny identifikator zamestnance
       Ucel: Vytvoreni obsahu, ktery bude nasledne poslan do modalniho okna */
    public function editRate($id){
        /* Ziskani konkretniho zamestnance */
        $zamestnanec = Employee::find($id);
        /* Definice obsahu modalniho okna */
        $out = '<div class="form-group text-center">
                    <label for="realibitySlider" style="font-size: 17px;">Spolehlivost:</label>
                    <input type="range" min="0" name="edit_realibility" max="5" value="'.$zamestnanec->employee_reliability.'" style="height: 1.5vh;width:100% !important;-webkit-appearance: none;" class="posuvnik" id="realibitySlider">
                    <div style="margin-top:8px;background-color: #4682B4;padding:1px 3px;border-radius: 10px;">
                        <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewRealibility"></span>b</p>
                    </div>
                </div>
                <div class="form-group text-center">
                   <label for="absenceSlider" style="font-size: 17px;">Dochvilnost:</label>
                   <input type="range" min="0" max="5" name="edit_absence" value="'.$zamestnanec->employee_absence.'" style="height: 1.5vh;width:100% !important;-webkit-appearance: none;" class="posuvnik" id="absenceSlider">
                   <div style="margin-top:8px;background-color: #4682B4;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewAbsence"></span>b</p>
                   </div>
                </div>
                <div class="form-group text-center">
                   <label for="workSlider" style="font-size: 17px;">Pracovitost:</label>
                   <input type="range" min="0" max="5" name="edit_workindex" value="'.$zamestnanec->employee_workindex.'" style="height: 1.5vh;width:100% !important;-webkit-appearance: none;" class="posuvnik" id="workSlider">
                   <div style="margin-top:8px;background-color: #4682B4;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewWork"></span>b</p>
                   </div>
                </div>';
        /* Odeslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateRate
       Argumenty: request - hodnoceni zadane firmou, id - jednoznacny identifikator zamestnance
       Ucel: Aktualizace hodnoceni zamestnance v databazi */
    public function updateRate(Request $request, $id){
        /* Ziskani zamestnance */
        $zamestnanec = Employee::find($id);
        $jmeno = $zamestnanec->employee_name;
        $prijmeni = $zamestnanec->employee_surname;
        /* Vypocet celkoveho skore */
        $skore = ($request->employee_reliability + $request->employee_absence + $request->employee_workindex) / 3;
        /* Aktualizace hodnoceni v databazi */
        Employee::where('employee_id', $id)->update(['employee_overall' => round($skore,2), 'employee_reliability' => $request->employee_reliability, 'employee_absence' => $request->employee_absence, 'employee_workindex' => $request->employee_workindex]);
        /* Odeslani odpovedi uzivateli */
        return response()->json(['success' => 'Hodnocení zaměstnance '.$jmeno.' '.$prijmeni.' bylo úspěšně dokončeno.']);
    }

}
