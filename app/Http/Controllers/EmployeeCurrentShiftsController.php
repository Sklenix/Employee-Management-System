<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee_Shift;
use App\Models\Shift;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EmployeeCurrentShiftsController extends Controller {
    /* Nazev souboru:  EmployeeCurrentShiftsController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci zobrazeni aktualnich smen v uctu s roli zamestnance. Ovladani datove tabulky je tu taktez naprogramovano.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni aktualnich smen */
    public function index(){
        $user = Auth::user();
        return view('employee_actions.current_shifts_list')
           ->with('profilovka',$user->employee_picture)->with('employee_url', $user->employee_url);
    }

    /* Nazev funkce: getEmployeeCurrentShifts
    Argumenty: id - jednoznacny identifikator zamestnance
    Ucel: Zobrazeni seznamu dochazek konkretniho zamestnance v datove tabulce */
    public function getEmployeeCurrentShifts(){
        $user = Auth::user();
        /* Ziskani smen zamestnance pro aktualni tyden */
        $zamestnancoviSmeny = Employee_Shift::getEmployeeCurrentShifts($user->employee_id);
        return Datatables::of($zamestnancoviSmeny)
            ->addIndexColumn()
            ->addColumn('shift_importance_id', function($zamestnancoviSmeny){ // pridani sloupce pro zobrazeni dulezitosti smeny
                $aktualniDulezitost = Shift::getCurrentImportanceShift($zamestnancoviSmeny->shift_importance_id);
                return $aktualniDulezitost[0]->importance_description;
            })
            ->addColumn('attendance_check_in', function($zamestnancoviSmeny){ // pridani sloupce pro zobrazeni zapisu prichodu na smenu (zamestnancem)
                $checkin =Attendance::getEmployeeCheckIn($zamestnancoviSmeny->shift_id, $zamestnancoviSmeny->employee_id);
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
            ->addColumn('attendance_check_out', function($zamestnancoviSmeny){ // pridani sloupce pro zobrazeni zapisu odchodu ze smeny (zamestnancem)
                $checkout = Attendance::getEmployeeCheckOut($zamestnancoviSmeny->shift_id, $zamestnancoviSmeny->employee_id);
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
            ->addColumn('reason_description', function($zamestnancoviSmeny){  // pridani sloupce pro zobrazeni statusu dochazky
                $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($zamestnancoviSmeny->shift_id, $zamestnancoviSmeny->employee_id);
                if($aktualniAbsence->isEmpty()){ // dokud je docházka nevyplnena tak ma status hodnotu "Čekající"
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
            ->addColumn('action', function($zamestnancoviSmeny){ // definovani ovladacich tlacitek datove tabulky
                return '<button type="button" data-id="'.$zamestnancoviSmeny->shift_id.'" data-toggle="modal" data-target="#confirmCheckinForm" id="updateCheckinEmployee" class="btn btn-dark btn-sm"><i class="fa fa-check-square-o" aria-hidden="true"></i> Příchod</button>
                        <button type="button" data-id="'.$zamestnancoviSmeny->shift_id.'" data-toggle="modal" data-target="#confirmCheckoutForm" id="updateCheckoutEmployee" class="btn btn-dark btn-sm"><i class="fa fa-check-square-o" aria-hidden="true"></i> Odchod</button>
                        <button type="button" data-id="'.$zamestnancoviSmeny->shift_id.'" data-toggle="modal" data-target="#CurrentShiftDetailForm" id="obtainDetailsCurrentShift" class="btn btn-primary btn-sm" style="margin-top:5px;">&nbsp;&nbsp;<i class="fa fa-eye" aria-hidden="true"></i> Detail&nbsp;&nbsp;</button>';
            })
            ->rawColumns(['action','reason_description','attendance_check_out','attendance_check_in']) // oznaceni sloupcu, ktere byly pridany, nebo upraveny za pomoci jazyka HTML
            ->make(true);

    }

    /* Nazev funkce: showCurrentShiftDetail
       Argumenty: shift_id - jednoznacny identifikator smeny
       Ucel: Zobrazeni detailu smeny v modalnim okne */
    public function showCurrentShiftDetail($shift_id){
        /* Ziskani konkretni smeny*/
        $smena_info = Shift::findOrFail($shift_id);
        /* Ziskani udaju do detailu smeny */
        $shift_start = new DateTime($smena_info->shift_start);
        $smena_info->shift_start = $shift_start->format('d.m.Y H:i');
        $shift_end = new DateTime($smena_info->shift_end);
        $smena_info->shift_end = $shift_end->format('d.m.Y H:i');
        $aktualniDulezitost = Shift::getCurrentImportanceShift($smena_info->shift_importance_id);
        /* Prirazeni detailu smeny do promenne */
        $out = '<div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:17px;padding:17px 10px;border-radius: 10px;background-color: #4aa0e6;">'.$aktualniDulezitost[0]->importance_description.'</div>
                       <center><div style="font-size: 16.1px;margin-bottom: 10px;">Začátek směny: '.$smena_info->shift_start.'</div>
                        <div style="font-size: 16.1px;margin-bottom: 10px;">Konec směny: '.$smena_info->shift_end.'</div>
                        <div style="font-size: 16.1px;">Lokace směny: '.$smena_info->shift_place.'</div></center>
                </div>';
        /* Poslani detailu smeny do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateEmployeeCheckin
       Argumenty: shift_id - jednoznacny identifikator smeny
       Ucel: zapis prichodu na smenu */
    public function updateEmployeeCheckin($shift_id){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        /* Ziskani konkretni dochazky (paklize existuje) */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($shift_id, $user->employee_id);
        /* Ziskani konkretni smeny */
        $smena = Shift::find($shift_id);
        /* Ziskani aktualniho casu */
        $now = Carbon::now();
        $now->second(0); // nastaveni sekund aktualniho casu na 0
        $now->microsecond(0); // nastaveni mikrosekund aktualniho casu 0
        /* Ziskani udaju o smene */
        $shift_start = new DateTime($smena->shift_start);
        $shift_end = new DateTime($smena->shift_end);
        $sekundy = 1200; // 20 minut - maximalni doba zapsani prichodu pred zacatkem smeny

        /* Validace, zdali zamestnanec nechtel zapsat prichod moc brzy*/
        $difference_start = $now->format('U') - ($shift_start->format('U') - $sekundy);
        $difference_end = $shift_end->format('U') - $now->format('U');
        if($difference_start < 0 || $difference_end < 0){
            return response()->json(['fail'=>'Příchod je možný nejdříve 20 minut před začátkem směny, nebo po konec směny.']);
        }
        /* Ziskani ID smeny z analyticke sekce systemu OLAP */
        $shift_info_id = OlapETL::getShiftInfoId($user->employee_id, NULL, $smena->shift_start, $smena->shift_end);
        $shift_start_date = Carbon::parse($smena->shift_start);
        if($dochazka->isEmpty()){ //pokud dochazka ke smene neexistuje, tak se vytvori dochazka nova, kdyz zamestnanec prijde se zpozdenim, tak se rovnou zapise status dochazky jako "Zpoždění".
            if($now->gt($shift_start_date)){
                Attendance::create(['employee_id' => $user->employee_id, 'shift_id' => $shift_id, 'attendance_check_in' => $now, 'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::create(['employee_id' => $user->employee_id, 'shift_id' => $shift_id, 'attendance_check_in' => $now, 'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }else{ // pokud dochazka existuje
            if($now->gt($shift_start_date)){ // uprava dochazky, paklize zamestnanec prisel se zpozdenim
                Attendance::where(['employee_id' => $user->employee_id, 'shift_id' => $shift_id])->update(['attendance_check_in' => $now,'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::where(['employee_id' => $user->employee_id, 'shift_id' => $shift_id])->update(['attendance_check_in' => $now,'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }
        OlapETL::extractAttendanceCheckInToShiftInfoDimension($shift_info_id, $now); //extrakce prichodu zamestnance do OLAP sekce systemu
        /* Odeslani odpovedi */
        return response()->json(['success' => 'Váš příchod byl úspěšně zaznamenán.']);
    }

    /* Nazev funkce: updateEmployeeCheckOut
       Argumenty: shift_id - jednoznacny identifikator smeny
       Ucel: zapis odchodu ze smeny */
    public function updateEmployeeCheckOut($shift_id){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        /* Ziskani konkretni dochazky (paklize existuje) */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($shift_id, $user->employee_id);
        /* Ziskani konkretni smeny */
        $smena = Shift::find($shift_id);
        /* Ziskani aktualniho casu */
        $now = Carbon::now();
        $now->second(0);
        $now->microsecond(0);
        $shift_start = new DateTime($smena->shift_start);

        /* Zjisteni, zdali zamestnanec nezapsal odchod drive nez je cas samotneho zacatku smeny */
        $difference_start = $now->format('U') - $shift_start->format('U');
        if($difference_start < 0){
            return response()->json(['fail'=>'Zapsat odchod před začátkem směny není možné.']);
        }

        /* Ziskani id smeny v analyticke sekci systemu OLAP */
        $shift_info_id = OlapETL::getShiftInfoId($user->employee_id, NULL,  $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje, tak se vytvori nova
            Attendance::create(['employee_id' => $user->employee_id, 'shift_id' => $shift_id, 'attendance_check_out' => $now, 'attendance_came' => 1]);
        }else{ // pokud existuje a je zapsany i prichod, tak dojde k provedeni transformace v OLAP sekci systemu, ktera slouzi k vypoctu odpracovanych hodin na smene
            /* nasledne se aktualizuje checkout v databazi (OLTP) */
            Attendance::where(['employee_id' => $user->employee_id, 'shift_id' => $shift_id])->update(['attendance_check_out' => $now,'attendance_came' => 1]);
        }
        OlapETL::extractAttendanceCheckOutToShiftInfoDimension($shift_info_id, $now); // extrakce checkoutu zamestnance v analyticke sekci OLAP
        /* Odeslani odpovedi */
        return response()->json(['success' => 'Váš odchod byl úspěšně zaznamenán.']);
    }

}
