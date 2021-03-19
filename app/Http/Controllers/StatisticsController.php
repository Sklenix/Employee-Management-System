<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\Disease;
use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use App\Models\Shift;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($user->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($user->company_id);
        $pocetNadchazejicich = Shift::getUpcomingCompanyShiftsCount($user->company_id);
        $pocetHistorie = Shift::getHistoricalCompanyShiftsCount($user->company_id);
        $datumVytvoreni = new DateTime($user->created_at);
        $datumZobrazeniVytvoreni = $datumVytvoreni->format('d.m.Y');

        $data_employees = Company::getNewEmployeesCountByMonths($user->company_id);
        $data_shifts = Company::getNewShiftsCountByMonths($user->company_id);
        $average_overall_score = Company::getAverageEmployeeScore($user->company_id);
        $average_reliability_score = Company::getAverageEmployeeReliabilityScore($user->company_id);
        $average_absence_score = Company::getAverageEmployeeAbsenceScore($user->company_id);
        $average_work_score = Company::getAverageEmployeeWorkScore($user->company_id);

        $average_shift_hour = Company::getAverageShiftHour($user->company_id);
        $max_shift_hour = Company::getMaxShiftHour($user->company_id);
        $min_shift_hour = Company::getMinShiftHour($user->company_id);

        $shifts_assigned_count = OlapAnalyzator::getCountOfShiftFacts($user->company_id);
        $shifts_employee_assigned = OlapAnalyzator::getCountOfEmployeeShiftFacts(8);
        $shifts_assigned_count_future = OlapAnalyzator::getCountUpcomingShiftFacts($user->company_id);
        $shifts_assigned_count_historical = OlapAnalyzator::getCountHistoricalShiftFacts($user->company_id);

        $shifts_assigned_count_by_months = OlapAnalyzator::getCountOfShiftFactsByMonths($user->company_id);
        $shifts_employee_assigned_count_by_months = OlapAnalyzator::getCountOfEmployeeShiftFactsByMonths(8);

        $total_shifts_hours = OlapAnalyzator::getTotalShiftsHours($user->company_id);
        $total_employee_shifts_hours = OlapAnalyzator::getTotalEmployeeShiftsHours(8);
        $shift_total_hours_by_months = OlapAnalyzator::getTotalShiftsHoursByMonths($user->company_id);
        $shift_total_employee_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsHoursByMonths(8);

        $shift_total_worked_hours = OlapAnalyzator::getTotalShiftsWorkedHours($user->company_id);
        $shift_total_employee_worked_hours = OlapAnalyzator::getTotalEmployeeShiftsWorkedHours(8);
        $shift_total_worked_hours_by_months = OlapAnalyzator::getTotalWorkedShiftsHoursByMonths($user->company_id);
        $shift_employee_total_worked_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsWorkedHoursByMonths(5);

        $shift_total_late_hours = OlapAnalyzator::getTotalLateShiftHours($user->company_id);
        $shift_total_employee_late_hours = OlapAnalyzator::getTotalEmployeeLateShiftHours(6);
        $shift_total_late_hours_by_months = OlapAnalyzator::getTotalLateShiftsHoursByMonths($user->company_id);
        $shift_total_employee_late_hours_by_months = OlapAnalyzator::getTotalEmployeeLateShiftsHoursByMonths(5);

        $total_late_flags_count = OlapAnalyzator::getTotalLateFlagsCount($user->company_id);
        $total_late_employee_flags_count = OlapAnalyzator::getTotalEmployeeLateFlagsCount(4);
        $total_late_flags_count_by_months = OlapAnalyzator::getTotalLateFlagsCountByMonths($user->company_id);
        $total_late_flags_count_employee_by_months = OlapAnalyzator::getTotalEmployeeLateFlagsCountByMonths(4);

        $total_injury_flags_count = OlapAnalyzator::getTotalInjuryFlagsCount($user->company_id);
        $total_injury_employee_flags_count = OlapAnalyzator::getTotalEmployeeInjuryFlagsCount(8);
        $total_injury_flags_count_by_months = OlapAnalyzator::getTotalInjuryFlagsCountByMonths($user->company_id);
        $total_injury_flags_count_employee_by_months = OlapAnalyzator::getTotalEmployeeInjuryFlagsCountByMonths(8);

        $average_employees_scores = OlapAnalyzator::getAverageEmployeesScores($user->company_id);
        $average_employee_score = OlapAnalyzator::getAverageEmployeeScore(20);
        $average_employees_scores_by_months = OlapAnalyzator::getAverageEmployeesScoresByMonths($user->company_id);
        $average_employee_score_by_months = OlapAnalyzator::getAverageEmployeeScoreByMonths(4);

        $company_disease_count = Disease::getCompanyDiseasesCount($user->company_id);
        $employee_disease_count = Disease::getEmployeeDiseasesCount(8);
        $company_diseases_by_months = Disease::getCompanyDiseasesByMonths($user->company_id);
        $employee_diseases_by_months = Disease::getEmployeeDiseasesByMonths(2);



        $pocet_absenci_firmy = Attendance::getCompanyAbsenceCount($user->company_id);
        $pocet_absenci_zpozdeni_firmy = Attendance::getCompanyAbsenceLateCount($user->company_id);
        $pocet_absenci_nemoc_firmy = Attendance::getCompanyAbsenceDiseaseCount($user->company_id);
        $pocet_neprichodu_firmy = Attendance::getCompanyAbsenceNotCameCount($user->company_id);
        $pocet_odmitnuti_smen = Attendance::getCompanyAbsenceDeniedCount($user->company_id);
        $pocet_ok_smen = Attendance::getCompanyAbsenceOKCount($user->company_id);

        $dochazka_absence_neprichod = Attendance::getAttendanceAbsenceNotComeByMonths($user->company_id, Carbon::now()->year);
        $dochazka_absence_nemoc = Attendance::getAttendanceAbsenceDiseaseByMonths($user->company_id, Carbon::now()->year);
        $dochazka_absence_odmitl = Attendance::getAttendanceAbsenceDeniedByMonths($user->company_id, Carbon::now()->year);
        $dochazka_ok = Attendance::getAttendanceOkByMonths($user->company_id, Carbon::now()->year);
        $dochazka_zpozdeni = Attendance::getAttendanceAbsenceDelayByMonths($user->company_id, Carbon::now()->year);

        return view('company_actions.statistics')
            ->with('profilovka',$user->company_picture)
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
            ->with('data_attendances_ok',$dochazka_ok);

    }

    public function changeEmployeeGraphYear($rok){
        $user = Auth::user();
        $data_employees = Company::changeEmployeesYear($user->company_id, $rok);
        return response()->json(['data_employees'=> $data_employees]);
    }

    public function changeShiftGraphYear($rok){
        $user = Auth::user();
        $data_shifts = Company::changeShiftsYear($user->company_id, $rok);
        return response()->json(['data_shifts'=> $data_shifts]);
    }

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

}
