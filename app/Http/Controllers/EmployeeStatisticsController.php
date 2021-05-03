<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Disease;
use App\Models\Employee;
use App\Models\Injury;
use App\Models\Report;
use App\Models\Shift;
use App\Models\Vacation;
use DateTime;
use Illuminate\Support\Facades\Auth;

class EmployeeStatisticsController extends Controller {
    /* Nazev souboru:  EmployeeStatisticsController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k ziskani statistik a naslednemu poslani techto statistik do pohledu, ktery se zobrazi uzivateli s roli zamestnance.
    Pro vykresleni techto statistik byla pouzita knihovna chart.js: https://www.chartjs.org/, ktera je distribuovana pod MIT licenci, ktera je zapsana nize
    The MIT License (MIT)

    Copyright (c) 2014-2021 Chart.js Contributors

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction,
    including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
    subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH
    THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

    K zobrazeni hodnot ve grafu byl pouzit plugin Chart.js plugin datalabels, ktery je primo od vyvojaru chart.js a spada taktez pod MIT licenci, odkaz na git pluginu: https://github.com/chartjs/chartjs-plugin-datalabels
    K zobrazeni textu uprostred prstencovych grafu byl pouzit chartjs doughnutlabel plugin, ktery je poskytovan pod licenci MIT, licence k doughnut label plugin:

    MIT License

    Copyright (c) 2018 ciprianciurea

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    SOFTWARE.
    */

    /* Nazev funkce: index
       Argumenty: zadne
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni statistik v ramci uctu s roli zamestnance */
    public function index(){
        $user = Auth::user();
        /* Usek kodu zabyvajici se ziskanim jednotlivych statistik skrze modely */
        $pocetDovolenych = Vacation::getEmployeeVacationsCount($user->employee_id);
        $pocetNemocenskych = Disease::getEmployeeDiseasesCount($user->employee_id);
        $pocetNahlaseni = Report::getEmployeeReportsCount($user->employee_id);
        $pocetSmen = Shift::getEmployeeShiftsCount($user->employee_id);

        $pocetBudoucichSmen = Shift::getEmployeeUpcomingShiftsCount($user->employee_id);
        $pocetAbsenci = Attendance::getEmployeeAbsenceCount($user->employee_id);
        $celkovyPocetHodinSmeny = OlapAnalyzator::getTotalEmployeeShiftsHours($user->employee_id);
        $tydenniPocetHodin = Employee::getEmployeeWeekShiftsHourWithoutMinutesExtension($user->employee_id);
        $mesicniPocetHodin = Employee::getEmployeeMonthShiftsHourWithoutMinutesExtension($user->employee_id);

        $mesicniPocetOdpracovanychHodin = Employee::getEmployeeWorkedMonthShiftsHourWithoutMinutesExtension($user->employee_id);
        $tydenniPocetOdpracovanychHodin = Employee::getEmployeeWorkedWeekShiftsHourWithoutMinutesExtension($user->employee_id);
        $celkovyPocetOdpracovanychHodin = Employee::getEmployeeWorkedTotalShiftsHourWithoutMinutesExtension($user->employee_id);

        $shift_total_employee_late_hours = OlapAnalyzator::getTotalEmployeeLateShiftHours($user->employee_id);
        $total_late_employee_flags_count = OlapAnalyzator::getTotalEmployeeLateFlagsCount($user->employee_id);
        $shifts_employee_assigned_count_by_months = json_encode(OlapAnalyzator::getCountOfEmployeeShiftFactsByMonths($user->employee_id));
        $shift_total_employee_hours_by_months = json_encode(OlapAnalyzator::getTotalEmployeeShiftsHoursByMonths($user->employee_id));
        $shift_employee_total_worked_hours_by_months = json_encode(OlapAnalyzator::getTotalEmployeeShiftsWorkedHoursByMonths($user->employee_id));
        $shift_total_employee_late_hours_by_months = json_encode(OlapAnalyzator::getTotalEmployeeLateShiftsHoursByMonths($user->employee_id));
        $total_late_flags_count_employee_by_months = json_encode(OlapAnalyzator::getTotalEmployeeLateFlagsCountByMonths($user->employee_id));
        $employee_injuries_count = Injury::getEmployeeInjuriesCount($user->employee_id);
        $employee_injuries_count_by_months = json_encode(Injury::getEmployeeInjuriesByMonths($user->employee_id));
        $datumVytvoreni = new DateTime($user->created_at);
        $datumZobrazeniVytvoreni = $datumVytvoreni->format('d.m.Y');

        /* Odeslani statistik spolecne s danym pohledem pro zobrazeni statistik */
        return view('employee_actions.statistics')
            ->with('profilovka',$user->employee_picture)
            ->with('employee_url', $user->employee_url)
            ->with('pocetDovolenych',$pocetDovolenych)
            ->with('pocetZraneni',$employee_injuries_count)
            ->with('pocetNemocenskych',$pocetNemocenskych)
            ->with('pocetNahlaseni',$pocetNahlaseni)
            ->with('pocetSmen',$pocetSmen)
            ->with('pocetBudoucichSmen',$pocetBudoucichSmen)
            ->with('pocetAbsenci',$pocetAbsenci)
            ->with('celkovyPocetHodinSmeny',$celkovyPocetHodinSmeny)
            ->with('tydenniPocetHodin',$tydenniPocetHodin)
            ->with('mesicniPocetHodin',$mesicniPocetHodin)
            ->with('mesicniOdpracovanyPocetHodin',$mesicniPocetOdpracovanychHodin)
            ->with('tydenniOdpracovanyPocetHodin',$tydenniPocetOdpracovanychHodin)
            ->with('celkoveOdpracovanoHodin',$celkovyPocetOdpracovanychHodin)
            ->with('celkovyPocetHodinZpozdeni',$shift_total_employee_late_hours)
            ->with('celkovyPocetZpozdeni',$total_late_employee_flags_count)
            ->with('pocetPrirazenychSmen',$shifts_employee_assigned_count_by_months)
            ->with('pocetHodinSmenDleMesicu',$shift_total_employee_hours_by_months)
            ->with('pocetOdpracovanychHodinSmenDleMesicu',$shift_employee_total_worked_hours_by_months)
            ->with('pocetHodinZpozdeniSmenDleMesicu',$shift_total_employee_late_hours_by_months)
            ->with('pocetZpozdeniSmenDleMesicu',$total_late_flags_count_employee_by_months)
            ->with('pocetZraneniDleMesicu',$employee_injuries_count_by_months)
            ->with('datumVytvoreni',$datumZobrazeniVytvoreni);
    }

    /* Nazev funkce: changeEmployeeShiftsAssignedGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu smen dle mesicu */
    public function changeEmployeeShiftsAssignedGraphYear($rok){
        $user=Auth::user();
        $shifts_assigned_count_by_months = Employee::changeShiftsAssignedYear($user->employee_id, $rok);
        return response()->json(['data_shifts_assigned' => $shifts_assigned_count_by_months]);
    }
    /* Nazev funkce: changeEmployeeShiftsTotalHoursGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu celkovych hodin smen dle mesicu */
    public function changeEmployeeShiftsTotalHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_hours_by_months = Employee::changeShiftsTotalHoursYear($user->employee_id, $rok);
        return response()->json(['data_shifts_total_hours' => $shift_total_hours_by_months]);
    }

    /* Nazev funkce: changeEmployeeShiftsTotalWorkedHoursGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu celkove odpracovanych hodin na smenach dle mesicu */
    public function changeEmployeeShiftsTotalWorkedHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_worked_hours_by_months = Employee::changeShiftsTotalWorkedHoursYear($user->employee_id, $rok);
        return response()->json(['data_shifts_total_worked_hours' => $shift_total_worked_hours_by_months]);
    }

    /* Nazev funkce: changeEmployeeShiftsTotalLateHoursGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu celkovych hodin zpozdeni dle mesicu */
    public function changeEmployeeShiftsTotalLateHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_late_hours_by_months = Employee::changeShiftsTotalLateHoursYear($user->employee_id, $rok);
        return response()->json(['data_shifts_total_late_hours' => $shift_total_late_hours_by_months]);
    }

    /* Nazev funkce: changeEmployeeShiftsLateFlagsCountGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu zpozdeni dle mesicu */
    public function changeEmployeeShiftsLateFlagsCountGraphYear($rok){
        $user=Auth::user();
        $total_late_flags_count_by_months = Employee::changeShiftsTotalLateFlagsCountYear($user->employee_id, $rok);
        return response()->json(['data_shifts_late_flags_count' => $total_late_flags_count_by_months]);
    }

    /* Nazev funkce: changeEmployeeShiftsInjuriesFlagsCountGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu zraneni dle mesicu */
    public function changeEmployeeShiftsInjuriesFlagsCountGraphYear($rok){
        $user=Auth::user();
        $total_injury_flags_count_by_months = Employee::changeShiftsTotalInjuriesFlagsCountYear($user->employee_id, $rok);
        return response()->json(['data_shifts_injuries_flags_count' => $total_injury_flags_count_by_months]);
    }

}
