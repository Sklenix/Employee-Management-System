<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\Disease;
use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Injury;
use App\Models\Languages;
use App\Models\Report;
use App\Models\Shift;
use App\Models\Vacation;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller {
    /* Nazev souboru:  StatisticsController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k ziskani statistik a naslednemu poslani techto statistik do pohledu, ktery se zobrazi uzivateli s roli firmy.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni statistik v ramci uctu s roli firmy */
    public function index(){
       // $shift_info_id = OlapETL::getShiftInfoId(8, 1, "2021-03-29 11:00:00", "2021-03-29 18:00:00");
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        /* Usek kodu zabyvajici se ziskanim jednotlivvch statistik skrze modely */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($user->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($user->company_id);
        $pocetNadchazejicich = Shift::getUpcomingCompanyShiftsCount($user->company_id);
        $pocetHistorie = Shift::getHistoricalCompanyShiftsCount($user->company_id);
        $datumVytvoreni = new DateTime($user->created_at);
        $datumZobrazeniVytvoreni = $datumVytvoreni->format('d.m.Y');

        $data_employees = json_encode(Company::getNewEmployeesCountByMonths($user->company_id));
        $data_shifts = json_encode(Company::getNewShiftsCountByMonths($user->company_id));
        $average_overall_score = Company::getAverageEmployeeScore($user->company_id);
        $average_reliability_score = Company::getAverageEmployeeReliabilityScore($user->company_id);
        $average_absence_score = Company::getAverageEmployeeAbsenceScore($user->company_id);
        $average_work_score = Company::getAverageEmployeeWorkScore($user->company_id);

        $average_shift_hour = Company::getAverageShiftHour($user->company_id);
        $max_shift_hour = Company::getMaxShiftHour($user->company_id);
        $min_shift_hour = Company::getMinShiftHour($user->company_id);

        $shifts_assigned_count = OlapAnalyzator::getCountOfShiftFacts($user->company_id);
        $shifts_assigned_count_future_count = OlapAnalyzator::getCountUpcomingShiftFacts($user->company_id);
        $shifts_assigned_count_historical_count = OlapAnalyzator::getCountHistoricalShiftFacts($user->company_id);
        $shifts_assigned_count_by_months = json_encode(OlapAnalyzator::getCountOfShiftFactsByMonths($user->company_id));

        $total_shifts_hours = OlapAnalyzator::getTotalShiftsHours($user->company_id);
        $shift_total_hours_by_months = json_encode(OlapAnalyzator::getTotalShiftsHoursByMonths($user->company_id));

        $shift_total_worked_hours = OlapAnalyzator::getTotalShiftsWorkedHours($user->company_id);
        $shift_total_worked_hours_by_months = json_encode(OlapAnalyzator::getTotalWorkedShiftsHoursByMonths($user->company_id));

        $shift_total_late_hours = OlapAnalyzator::getTotalLateShiftHours($user->company_id);
        $shift_total_late_hours_by_months = json_encode(OlapAnalyzator::getTotalLateShiftsHoursByMonths($user->company_id));

        $total_late_flags_count = OlapAnalyzator::getTotalLateFlagsCount($user->company_id);
        $total_late_flags_count_by_months = json_encode(OlapAnalyzator::getTotalLateFlagsCountByMonths($user->company_id));

        $total_injury_flags_count = OlapAnalyzator::getTotalInjuryFlagsCount($user->company_id);
        $total_injury_flags_count_by_months = json_encode(OlapAnalyzator::getTotalInjuryFlagsCountByMonths($user->company_id));

        $average_employees_scores = OlapAnalyzator::getAverageEmployeesScores($user->company_id);
        $average_employees_scores_by_months = json_encode(OlapAnalyzator::getAverageEmployeesScoresByMonths($user->company_id));

        $company_diseases_count = Disease::getCompanyDiseasesCount($user->company_id);
        $company_diseases_by_months = json_encode(Disease::getCompanyDiseasesByMonths($user->company_id));
        $company_injuries_count = Injury::getCompanyInjuriesCount($user->company_id);
        $company_injuries_count_by_months = json_encode(Injury::getCompanyInjuriesByMonths($user->company_id));
        $company_reports_count = Report::getCompanyReportsCount($user->company_id);
        $company_reports_count_by_months = json_encode(Report::getCompanyReportsByMonths($user->company_id));
        $company_vacations_count = Vacation::getCompanyVacationsCount($user->company_id);
        $company_vacations_count_by_months = json_encode(Vacation::getCompanyVacationsByMonths($user->company_id));

        $pocet_absenci_firmy = Attendance::getCompanyAbsenceCount($user->company_id);
        $pocet_absenci_zpozdeni_firmy = Attendance::getCompanyAbsenceLateCount($user->company_id);
        $pocet_absenci_nemoc_firmy = Attendance::getCompanyAbsenceDiseaseCount($user->company_id);
        $pocet_neprichodu_firmy = Attendance::getCompanyAbsenceNotCameCount($user->company_id);
        $pocet_odmitnuti_smen = Attendance::getCompanyAbsenceDeniedCount($user->company_id);
        $pocet_ok_smen = Attendance::getCompanyAbsenceOKCount($user->company_id);
        $pocet_nezapsanych = $shifts_assigned_count - ($pocet_absenci_firmy + $pocet_absenci_zpozdeni_firmy + $pocet_ok_smen);
        $dochazka_absence_neprichod = json_encode(Attendance::getAttendanceAbsenceNotComeByMonths($user->company_id, Carbon::now()->year));
        $dochazka_absence_nemoc = json_encode(Attendance::getAttendanceAbsenceDiseaseByMonths($user->company_id, Carbon::now()->year));
        $dochazka_absence_odmitl = json_encode(Attendance::getAttendanceAbsenceDeniedByMonths($user->company_id, Carbon::now()->year));
        $dochazka_ok = json_encode(Attendance::getAttendanceOkByMonths($user->company_id, Carbon::now()->year));
        $dochazka_zpozdeni = json_encode(Attendance::getAttendanceAbsenceDelayByMonths($user->company_id, Carbon::now()->year));

        /* Odeslani statistik spolecne s danym pohledem pro zobrazeni statistik */
        return view('company_actions.statistics')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('pocetZamestnancu',$pocetZamestnancu)
            ->with('pocetSmen',$pocetSmen)
            ->with('pocetNadchazejicich',$pocetNadchazejicich)
            ->with('pocetHistorie',$pocetHistorie)
            ->with('vytvorenUcet',$datumZobrazeniVytvoreni)
            ->with('data_employees',$data_employees)
            ->with('data_shifts',$data_shifts)
            ->with('company_absences_count',$pocet_absenci_firmy)
            ->with('company_late_count',$pocet_absenci_zpozdeni_firmy)
            ->with('company_disease_count',$pocet_absenci_nemoc_firmy)
            ->with('company_not_came_count',$pocet_neprichodu_firmy)
            ->with('company_denied_count',$pocet_odmitnuti_smen)
            ->with('company_ok_count',$pocet_ok_smen)
            ->with('data_attendances_absence_disease',$dochazka_absence_nemoc)
            ->with('data_attendances_absence_not_come',$dochazka_absence_neprichod)
            ->with('data_attendances_absence_denied',$dochazka_absence_odmitl)
            ->with('data_attendances_delay',$dochazka_zpozdeni)
            ->with('data_attendances_ok',$dochazka_ok)
            ->with('data_assigned_shifts_count', $shifts_assigned_count)
            ->with('data_unregistered_absence_shifts', $pocet_nezapsanych)
            ->with('data_average_overall_score', $average_overall_score)
            ->with('data_average_reliability_score', $average_reliability_score)
            ->with('data_average_absence_score', $average_absence_score)
            ->with('data_average_work_score', $average_work_score)
            ->with('data_average_shift_hour', $average_shift_hour)
            ->with('data_max_shift_hour', $max_shift_hour)
            ->with('data_min_shift_hour', $min_shift_hour)
            ->with('data_shifts_assigned_by_months', $shifts_assigned_count_by_months)
            ->with('data_shifts_total_hours_by_months', $shift_total_hours_by_months)
            ->with('data_shifts_total_hours', $total_shifts_hours)
            ->with('data_shifts_total_worked_hours', $shift_total_worked_hours)
            ->with('data_shifts_total_late_hours', $shift_total_late_hours)
            ->with('data_shifts_total_late_count', $total_late_flags_count)
            ->with('data_shifts_total_injury_count', $total_injury_flags_count)
            ->with('data_employees_average_employee_score_by_time', $average_employees_scores)
            ->with('data_shifts_total_worked_hours_by_months', $shift_total_worked_hours_by_months)
            ->with('data_shifts_total_late_hours_by_months', $shift_total_late_hours_by_months)
            ->with('data_shifts_total_late_flags_count_by_months', $total_late_flags_count_by_months)
            ->with('data_shifts_total_injury_flags_count_by_months', $total_injury_flags_count_by_months)
            ->with('data_vacations_total_count', $company_vacations_count)
            ->with('data_injuries_total_count', $company_injuries_count)
            ->with('data_reports_total_count', $company_reports_count)
            ->with('data_diseases_total_count', $company_diseases_count)
            ->with('data_diseases_count_by_month', $company_diseases_by_months)
            ->with('data_injuries_count_by_month', $company_injuries_count_by_months)
            ->with('data_reports_count_by_month', $company_reports_count_by_months)
            ->with('data_vacations_count_by_month', $company_vacations_count_by_months)
            ->with('data_average_employees_scores_by_months',$average_employees_scores_by_months);
    }

    /* Nazev funkce: changeEmployeeGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu novych zamestnancu dle mesicu */
    public function changeEmployeeGraphYear($rok){
        $user = Auth::user();
        /* ziskani dat na zaklade roku */
        $data_employees = Company::changeEmployeesYear($user->company_id, $rok);
        /* odeslani techto dat do pohledu */
        return response()->json(['data_employees'=> $data_employees]);
    }

    /* Nazev funkce: changeShiftGraphYear
      Argumenty: rok - rok, na ktery chceme graf zmenit
      Ucel: Zmena roku u grafu poctu vypsanych smen dle mesicu */
    public function changeShiftGraphYear($rok){
        $user = Auth::user();
        $data_shifts = Company::changeShiftsYear($user->company_id, $rok);
        return response()->json(['data_shifts'=> $data_shifts]);
    }

    /* Nazev funkce: changeAttendanceGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu analyzy dochazky dle mesicu */
    public function changeAttendanceGraphYear($rok){
        $user=Auth::user();
        $dochazka_absence_neprichod = Attendance::getAttendanceAbsenceNotComeByMonths($user->company_id,$rok);
        $dochazka_absence_nemoc = Attendance::getAttendanceAbsenceDiseaseByMonths($user->company_id,$rok);
        $dochazka_absence_odmitl = Attendance::getAttendanceAbsenceDeniedByMonths($user->company_id,$rok);
        $dochazka_ok = Attendance::getAttendanceOkByMonths($user->company_id,$rok);
        $dochazka_zpozdeni = Attendance::getAttendanceAbsenceDelayByMonths($user->company_id,$rok);
        return response()->json(['absence_not_come'=> $dochazka_absence_neprichod, 'absence_disease' => $dochazka_absence_nemoc,
        'absence_denied' => $dochazka_absence_odmitl, 'absence_ok' => $dochazka_ok, 'absence_delay' => $dochazka_zpozdeni]);
    }

    /* Nazev funkce: changeShiftsAssignedGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu smen zamestnancu dle mesicu */
    public function changeShiftsAssignedGraphYear($rok){
        $user=Auth::user();
        $shifts_assigned_count_by_months = Company::changeShiftsAssignedYear($user->company_id, $rok);
        return response()->json(['data_shifts_assigned' => $shifts_assigned_count_by_months]);
    }

    /* Nazev funkce: changeShiftsTotalHoursGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu celkoveho poctu hodin smen dle mesicu */
    public function changeShiftsTotalHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_hours_by_months = Company::changeShiftsTotalHoursYear($user->company_id, $rok);
        return response()->json(['data_shifts_total_hours' => $shift_total_hours_by_months]);
    }

    /* Nazev funkce: changeShiftsTotalWorkedHoursGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu celkoveho poctu odpracovanych hodin na smenach dle mesicu */
    public function changeShiftsTotalWorkedHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_worked_hours_by_months = Company::changeShiftsTotalWorkedHoursYear($user->company_id, $rok);
        return response()->json(['data_shifts_total_worked_hours' => $shift_total_worked_hours_by_months]);
    }

    /* Nazev funkce: changeShiftsTotalLateHoursGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu celkoveho poctu hodin zpozdeni dle mesicu */
    public function changeShiftsTotalLateHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_late_hours_by_months = Company::changeShiftsTotalLateHoursYear($user->company_id, $rok);
        return response()->json(['data_shifts_total_late_hours' => $shift_total_late_hours_by_months]);
    }

    /* Nazev funkce: changeShiftsLateFlagsCountGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu zpozdeni dle mesicu */
    public function changeShiftsLateFlagsCountGraphYear($rok){
        $user=Auth::user();
        $total_late_flags_count_by_months = Company::changeShiftsTotalLateFlagsCountYear($user->company_id, $rok);
        return response()->json(['data_shifts_late_flags_count' => $total_late_flags_count_by_months]);
    }

    /* Nazev funkce: changeShiftsInjuriesFlagsCountGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu zraneni dle mesicu */
    public function changeShiftsInjuriesFlagsCountGraphYear($rok){
        $user=Auth::user();
        $total_injury_flags_count_by_months = Company::changeShiftsTotalInjuriesFlagsCountYear($user->company_id, $rok);
        return response()->json(['data_shifts_injuries_flags_count' => $total_injury_flags_count_by_months]);
    }

    /* Nazev funkce: changeVacationsGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu dovolenych dle mesicu */
    public function changeVacationsGraphYear($rok){
        $user=Auth::user();
        $company_vacations_count_by_months = Company::changeVacationsYear($user->company_id, $rok);
        return response()->json(['data_vacations' => $company_vacations_count_by_months]);
    }

    /* Nazev funkce: changeDiseasesGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu nemocenskych dle mesicu */
    public function changeDiseasesGraphYear($rok){
        $user=Auth::user();
        $company_diseases_by_months = Company::changeDiseasesYear($user->company_id, $rok);
        return response()->json(['data_diseases' => $company_diseases_by_months]);
    }

    /* Nazev funkce: changeReportsGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu nahlaseni dle mesicu */
    public function changeReportsGraphYear($rok){
        $user=Auth::user();
        $company_reports_count_by_months = Company::changeReportsYear($user->company_id, $rok);
        return response()->json(['data_reports' => $company_reports_count_by_months]);
    }

    /* Nazev funkce: changeAverageScoreYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu prumerneho skore zamestnance dle mesicu */
    public function changeAverageScoreYear($rok){
        $user=Auth::user();
        $average_employees_scores_by_months = Company::changeAverageEmployeesScoresYear($user->company_id, $rok);
        return response()->json(['data_score' => $average_employees_scores_by_months]);
    }

}
