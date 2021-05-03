<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee_Shift;
use App\Models\Shift;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EmployeeAllShiftsController extends Controller {
    /* Nazev souboru:  EmployeeAllShiftsController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci zobrazeni vsech smen konkretniho zamestnance v uctu s roli zamestnance.

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
       Ucel: Zobrazeni prislusneho pohledu pro seznam vsech smen */
    public function index(){
        $user = Auth::user();
        return view('employee_actions.shifts_history_list')
            ->with('profilovka',$user->employee_picture)->with('employee_url', $user->employee_url);
    }

    /* Nazev funkce: getAllEmployeeShiftsList
       Argumenty: zadne
       Ucel: Zobrazeni seznamu vsech smen zamestnance v datove tabulce */
    public function getAllEmployeeShiftsList(){
        $user = Auth::user();
        /* ziskani zamestnancovych smen */
        $zamestnancovySmeny = Employee_Shift::getEmployeeAllShifts($user->employee_id);
        /* Usek kodu slouzici k vyrenderovani datove tabulky */
        return Datatables::of($zamestnancovySmeny)
            ->addIndexColumn()
            ->addColumn('shift_importance_id', function($zamestnancovySmeny){ // pridani sloupce pro dulezitost
                $aktualniDulezitost = Shift::getCurrentImportanceShift($zamestnancovySmeny->shift_importance_id);
                return $aktualniDulezitost[0]->importance_description;
            })
            ->addColumn('attendance_check_in', function($zamestnancovySmeny){ // pridani sloupce pro prichod na danou smenu (prichod zapsany zamestnancem)
                $checkin =Attendance::getEmployeeCheckIn($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($checkin->isEmpty()){
                    return 'Invalid date';
                }else{
                    if($checkin[0]->attendance_check_in === NULL){
                        return 'Invalid date';
                    }else{
                        return $checkin[0]->attendance_check_in;
                    }
                }
            })
            ->addColumn('attendance_check_out', function($zamestnancovySmeny){ // pridani sloupce pro odchod z dane smeny (odchod zapsany zamestnancem)
                $checkout = Attendance::getEmployeeCheckOut($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($checkout->isEmpty()){
                    return 'Invalid date';
                }else{
                    if($checkout[0]->attendance_check_out === NULL){
                        return 'Invalid date';
                    }else{
                        return $checkout[0]->attendance_check_out;
                    }
                }
            })
            ->addColumn('hours_total', function($zamestnancovySmeny){ // pridani sloupce pro vypocet odpracovanych hodin na dane smene
                $udaje = Attendance::getAllCheckInCheckOutForShift($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($udaje->isEmpty()){
                    return '<p style="color:black;">Nezapsaný příchod/out</p>';
                }else{ //pokud je prichod, nebo odchod nezadany(od zamestnance nebo firmy), tak se zobrazi hlaska "Nezapsaný příchod/odchod"
                    if ($udaje[0]->attendance_check_in_company == NULL || $udaje[0]->attendance_check_out_company == NULL){
                        if($udaje[0]->attendance_check_in == NULL || $udaje[0]->attendance_check_out == NULL){
                            return '<p style="color:black;">Nezapsaný příchod/odchod</p>';
                        }else if($udaje[0]->attendance_check_in != NULL && $udaje[0]->attendance_check_out != NULL){
                            $checkin = new DateTime($udaje[0]->attendance_check_in);
                            $checkout = new DateTime($udaje[0]->attendance_check_out);
                            $hodinyRozdilCheck =$checkout->diff($checkin); //vypocet rozdilu
                            return '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>'; //vraceni odpracovanych hodin ve formatu napriklad 8h30m
                        }
                        return '<p style="color:black;">Nezapsaný příchod/odchod</p>';
                    }else if($udaje[0]->attendance_check_in_company != NULL && $udaje[0]->attendance_check_out_company != NULL){
                        $checkin = new DateTime($udaje[0]->attendance_check_in_company);
                        $checkout = new DateTime($udaje[0]->attendance_check_out_company);
                        $hodinyRozdilCheck =$checkout->diff($checkin); //vypocet rozdilu
                        return '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>'; //vraceni odpracovanych hodin ve formatu napriklad 8h30m
                    }
                }
            })
            ->addColumn('reason_description', function($zamestnancovySmeny){ // pridani sloupce pro ukazani statusu dochazky
                $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                 if($aktualniAbsence->isEmpty()){ //pokud status dochazky neni vyplnen je zobrazena hlaska "Čekající"
                     return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Čekající</p></center>';
                 }else{
                     if($aktualniAbsence[0]->reason_description === NULL){
                         return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Čekající</p></center>';
                     }else{
                         if($aktualniAbsence[0]->absence_reason_id == 5){
                             return '<center><p class="col-md-10" style="color:lightgreen;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">'.$aktualniAbsence[0]->reason_description.'</p></center>';
                         }else{
                             return '<center><p class="col-md-10" style="color:orangered;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">'.$aktualniAbsence[0]->reason_description.'</p></center>';
                         }
                     }
                 }
            })
            ->rawColumns(['action','reason_description','attendance_check_in','attendance_check_out', 'hours_total']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);
    }

}
