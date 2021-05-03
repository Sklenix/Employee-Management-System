<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Employee_Shift;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use App\Models\Shift;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;


class EmployeeAttendanceController extends Controller {
    /* Nazev souboru:  EmployeeAttendanceController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy dochazky v uctu s roli zamestnance. Ovladani datove tabulky (Tlacitko "Docházka") je tu taktez naprogramovano.

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
       Ucel: Zobrazeni prislusneho pohledu pro spravu dochazky */
    public function index(){
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
        Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        /* Odeslani pohledu spolecne se ziskanymi daty do uzivatelova prohlizece */
        return view('company_actions.attendances')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci);
    }

    /* Nazev funkce: getAttendance
       Argumenty: id - jednoznacny identifikator zamestnance
       Ucel: Zobrazeni seznamu dochazek konkretniho zamestnance v datove tabulce */
    public function getAttendance($id){
        /* Ziskani zamestnancovych smen */
        $zamestnancovySmeny = Employee_Shift::getEmployeeAllShifts($id);
        /* Usek kodu slouzici k vyrenderovani datove tabulky */
        return Datatables::of($zamestnancovySmeny)
            ->addIndexColumn()
            ->addColumn('shift_importance_id', function($zamestnancovySmeny){ // pridani sloupce pro zobrazeni dulezitosti smeny
                $aktualniDulezitost = Shift::getCurrentImportanceShift($zamestnancovySmeny->shift_importance_id);
                return $aktualniDulezitost[0]->importance_description;
            })
            ->addColumn('attendance_came', function($zamestnancovySmeny){ // pridani sloupce pro zobrazeni indikatoru, zdali zamestnanec na smenu prisel
                $came = Attendance::getAttendanceCame($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($came->isEmpty()){
                    return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezapsáno</p></center>';
                }else {
                    if ($came[0]->attendance_came === NULL) {
                        return '<center><p class="col-md-10" style="color:yellow;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Nezapsáno</p></center>';
                    } else if ($came[0]->attendance_came == 1) {
                        return '<center><p class="col-md-10" style="color:lightgreen;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Ano</p></center>';
                    } else {
                        return '<center><p class="col-md-10" style="color:orangered;display:block;background-color: #333333;padding-bottom: 5px;margin-top:15px;padding-top: 5px;border-radius: 10px;">Ne</p></center>';
                    }
                }
            })
            ->addColumn('attendance_check_in_company', function($zamestnancovySmeny){ // pridani sloupce pro zobrazeni zapisu prichodu na smenu firmou
                $checkin_company = Attendance::getCompanyCheckIn($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($checkin_company->isEmpty()){
                    return 'Invalid date';
                }else{
                    if($checkin_company[0]->attendance_check_in_company === NULL){
                        return 'Invalid date';
                    }else{
                        return $checkin_company[0]->attendance_check_in_company;
                    }
                }
            })
            ->addColumn('attendance_check_out_company', function($zamestnancovySmeny){ // pridani sloupce pro zobrazeni zapisu odchodu ze smeny firmou
                $checkout_company = Attendance::getCompanyCheckOut($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($checkout_company->isEmpty()){
                    return 'Invalid date';
                }else{
                    if($checkout_company[0]->attendance_check_out_company === NULL){
                        return 'Invalid date';
                    }else{
                        return $checkout_company[0]->attendance_check_out_company;
                    }
                }
            })
            ->addColumn('attendance_check_in', function($zamestnancovySmeny){ // pridani sloupce pro zobrazeni zapisu prichodu na smenu zamestnancem
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
            ->addColumn('attendance_check_out', function($zamestnancovySmeny){ // pridani sloupce pro zobrazeni zapisu odchodu ze smeny zamestnancem
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
            ->addColumn('reason_description', function($zamestnancovySmeny){ // pridani sloupce pro zobrazeni statusu dochazky
                $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($aktualniAbsence->isEmpty()){
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
            ->addColumn('hours_total', function($zamestnancovySmeny){ // vypocitani celkove odpracovanych hodin
                $udaje = Attendance::getAllCheckInCheckOutForShift($zamestnancovySmeny->shift_id, $zamestnancovySmeny->employee_id);
                if($udaje->isEmpty()){
                    return '<p style="color:black;">Nezapsaný příchod/odchod</p>';
                }else{ //pokud je prichod, nebo odchod nezadany(od zamestnance nebo firmy), tak se zobrazi hlaska "Nezapsany prichod/odchod"
                    if ($udaje[0]->attendance_check_in_company == NULL || $udaje[0]->attendance_check_out_company == NULL){
                        if($udaje[0]->attendance_check_in == NULL || $udaje[0]->attendance_check_out == NULL){
                            return '<p style="color:black;">Nezapsaný příchod/odchod</p>';
                        }else if($udaje[0]->attendance_check_in != NULL && $udaje[0]->attendance_check_out != NULL){
                            $checkin = new DateTime($udaje[0]->attendance_check_in);
                            $checkout = new DateTime($udaje[0]->attendance_check_out);
                            $hodinyRozdilCheck =$checkout->diff($checkin);
                            return '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                        }
                        return '<p style="color:black;">Nezapsaný příchod/odchod</p>';
                    }else if($udaje[0]->attendance_check_in_company != NULL && $udaje[0]->attendance_check_out_company != NULL){
                        $checkin = new DateTime($udaje[0]->attendance_check_in_company);
                        $checkout = new DateTime($udaje[0]->attendance_check_out_company);
                        $hodinyRozdilCheck =$checkout->diff($checkin);
                        return '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                    }
                }
            })
            ->addColumn('action', function($zamestnancovySmeny){ //definice ovladaciho tlacitka "Docházka"
                return '<button type="button" data-id="'.$zamestnancovySmeny->shift_id.'" data-toggle="modal" style="margin-top:5px;"  id="obtainEmployeeOptions" data-target="#ShowAttendanceOptionsForm" class="btn btn-success btn-sm" ><i class="fa fa-calendar-check-o"></i> Docházka</button>';
            })
            ->rawColumns(['action','attendance_came','attendance_check_out','attendance_check_in','reason_description','hours_total']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);

    }

    /* Nazev funkce: getAttendanceOptions
       Argumenty: id - jednoznacny identifikator dochazky, zamestnanec_id - jednoznacny identifikator zamestnance,
       Ucel: Zobrazeni moznosti dochazky */
    public function getAttendanceOptions($id,$zamestnanec_id){
        $out  = '';
        /* Ziskani konkretniho zamestnance */
        $zamestnanec = Employee::find($zamestnanec_id);
        $out .='<p style="text-align: center;font-size: 17px;">'.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</p>';
        /* Definice jednotlivych moznosti dochazky */
        $out .= '<center><button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckinForm" class="btn btn-primary" id="obtainCheckInShift" "><i class="fa fa-check-square-o"></i> Příchod</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckoutForm" class="btn btn-primary" id="obtainCheckOutShift" "><i class="fa fa-check-square-o"></i> Odchod</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceAbsenceForm" class="btn btn-primary" id="obtainAbsenceReasonAttendance" "><i class="fa fa-lightbulb-o"></i> Status</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceNoteForm" class="btn btn-primary" id="obtainNoteAttendance" "><i class="fa fa-sticky-note-o"></i> Poznámka</button>';
        /* Poslani moznosti do modalniho okna */
        return response()->json(['out' => $out]);
    }

}
