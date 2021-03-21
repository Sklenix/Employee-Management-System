<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Disease;
use App\Models\Employee;
use App\Models\Injury;
use App\Models\Report;
use App\Models\Shift;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeStatisticsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $pocetDovolenych = Vacation::getEmployeeVacationsCount($user->employee_id);
        $pocetNemocenskych = Disease::getEmployeeDiseasesCount($user->employee_id);
        $pocetNahlaseni = Report::getEmployeeReportsCount($user->employee_id);
        $pocetSmen = Shift::getEmployeeShiftsCount($user->employee_id);
        $pocetBudoucichSmen = Shift::getEmployeeUpcomingShiftsCount($user->employee_id);
        $pocetAbsenci = Attendance::getEmployeeAbsenceCount($user->employee_id);
        $pocetSmenDochazka = Attendance::getEmployeeShiftsCount($user->employee_id);
        $celkovyPocetHodinSmeny = Employee::getEmployeeTotalShiftsHour($user->employee_id);
        $tydenniPocetHodin = Employee::getEmployeeWeekShiftsHour($user->employee_id);
        $mesicniPocetHodin = Employee::getEmployeeMonthShiftsHour($user->employee_id);
        $mesicniPocetOdpracovanychHodin = Employee::getEmployeeWorkedMonthShiftsHour($user->employee_id);
        $tydenniPocetOdpracovanychHodin = Employee::getEmployeeWorkedWeekShiftsHour($user->employee_id);
        $celkovyPocetOdpracovanychHodin = Employee::getEmployeeWorkedTotalShiftsHour($user->employee_id);

        $shift_total_employee_late_hours = OlapAnalyzator::getTotalEmployeeLateShiftHours($user->employee_id);
        $total_late_employee_flags_count = OlapAnalyzator::getTotalEmployeeLateFlagsCount($user->employee_id);
        $shifts_employee_assigned_count_by_months = OlapAnalyzator::getCountOfEmployeeShiftFactsByMonths($user->employee_id);
        $shift_total_employee_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsHoursByMonths($user->employee_id);
        $shift_employee_total_worked_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsWorkedHoursByMonths($user->employee_id);
        $shift_total_employee_late_hours_by_months = OlapAnalyzator::getTotalEmployeeLateShiftsHoursByMonths($user->employee_id);
        $total_late_flags_count_employee_by_months = OlapAnalyzator::getTotalEmployeeLateFlagsCountByMonths($user->employee_id);
        $employee_injuries_count = Injury::getEmployeeInjuriesCount($user->employee_id);
        $employee_vacations_count_by_months = Vacation::getEmployeeVacationsByMonths($user->employee_id);
        $employee_reports_count_by_months = Report::getEmployeeReportsByMonths($user->employee_id);
        $employee_injuries_count_by_months = Injury::getEmployeeInjuriesByMonths($user->employee_id);
        $employee_diseases_by_months = Disease::getEmployeeDiseasesByMonths($user->employee_id);

        return view('employee_actions.statistics')
            ->with('profilovka',$user->employee_picture)
            ->with('pocetDovolenych',$pocetDovolenych)
            ->with('pocetZraneni',$employee_injuries_count)
            ->with('pocetNemocenskych',$pocetNemocenskych)
            ->with('pocetNahlaseni',$pocetNahlaseni)
            ->with('pocetSmen',$pocetSmen)
            ->with('pocetBudoucichSmen',$pocetBudoucichSmen)
            ->with('pocetAbsenci',$pocetAbsenci)
            ->with('pocetSmenDochazka',$pocetSmenDochazka)
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
            ->with('pocetDovolenychDleMesicu',$employee_vacations_count_by_months)
            ->with('pocetNahlaseniDleMesicu',$employee_reports_count_by_months)
            ->with('pocetNemocenskychDleMesicu',$employee_diseases_by_months);
    }
}
