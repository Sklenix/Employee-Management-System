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
        $celkovyPocetHodinSmeny = OlapAnalyzator::getTotalEmployeeShiftsHours($user->employee_id);
        $tydenniPocetHodin = Employee::getEmployeeWeekShiftsHourWithoutMinutesExtension($user->employee_id);
        $mesicniPocetHodin = Employee::getEmployeeMonthShiftsHourWithoutMinutesExtension($user->employee_id);

        $mesicniPocetOdpracovanychHodin = Employee::getEmployeeWorkedMonthShiftsHourWithoutMinutesExtension($user->employee_id);
        $tydenniPocetOdpracovanychHodin = Employee::getEmployeeWorkedWeekShiftsHourWithoutMinutesExtension($user->employee_id);
        $celkovyPocetOdpracovanychHodin = Employee::getEmployeeWorkedTotalShiftsHourWithoutMinutesExtension($user->employee_id);

        $shift_total_employee_late_hours = OlapAnalyzator::getTotalEmployeeLateShiftHours($user->employee_id);
        $total_late_employee_flags_count = OlapAnalyzator::getTotalEmployeeLateFlagsCount($user->employee_id);
        $shifts_employee_assigned_count_by_months = OlapAnalyzator::getCountOfEmployeeShiftFactsByMonths($user->employee_id);
        $shift_total_employee_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsHoursByMonths($user->employee_id);
        $shift_employee_total_worked_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsWorkedHoursByMonths($user->employee_id);
        $shift_total_employee_late_hours_by_months = OlapAnalyzator::getTotalEmployeeLateShiftsHoursByMonths($user->employee_id);
        $total_late_flags_count_employee_by_months = OlapAnalyzator::getTotalEmployeeLateFlagsCountByMonths($user->employee_id);
        $employee_injuries_count = Injury::getEmployeeInjuriesCount($user->employee_id);
        $employee_injuries_count_by_months = Injury::getEmployeeInjuriesByMonths($user->employee_id);
        $datumVytvoreni = new DateTime($user->created_at);
        $datumZobrazeniVytvoreni = $datumVytvoreni->format('d.m.Y');

        return view('employee_actions.statistics')
            ->with('profilovka',$user->employee_picture)
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

    public function changeEmployeeShiftsAssignedGraphYear($rok){
        $user=Auth::user();
        $shifts_assigned_count_by_months = Employee::changeShiftsAssignedYear($user->employee_id, $rok);
        return response()->json(['data_shifts_assigned' => $shifts_assigned_count_by_months]);
    }

    public function changeEmployeeShiftsTotalHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_hours_by_months = Employee::changeShiftsTotalHoursYear($user->employee_id, $rok);
        return response()->json(['data_shifts_total_hours' => $shift_total_hours_by_months]);
    }

    public function changeEmployeeShiftsTotalWorkedHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_worked_hours_by_months = Employee::changeShiftsTotalWorkedHoursYear($user->employee_id, $rok);
        return response()->json(['data_shifts_total_worked_hours' => $shift_total_worked_hours_by_months]);
    }

    public function changeEmployeeShiftsTotalLateHoursGraphYear($rok){
        $user=Auth::user();
        $shift_total_late_hours_by_months = Employee::changeShiftsTotalLateHoursYear($user->employee_id, $rok);
        return response()->json(['data_shifts_total_late_hours' => $shift_total_late_hours_by_months]);
    }

    public function changeEmployeeShiftsLateFlagsCountGraphYear($rok){
        $user=Auth::user();
        $total_late_flags_count_by_months = Employee::changeShiftsTotalLateFlagsCountYear($user->employee_id, $rok);
        return response()->json(['data_shifts_late_flags_count' => $total_late_flags_count_by_months]);
    }

    public function changeEmployeeShiftsInjuriesFlagsCountGraphYear($rok){
        $user=Auth::user();
        $total_injury_flags_count_by_months = Employee::changeShiftsTotalInjuriesFlagsCountYear($user->employee_id, $rok);
        return response()->json(['data_shifts_injuries_flags_count' => $total_injury_flags_count_by_months]);
    }

    public function changeEmployeeVacationsGraphYear($rok){
        $user=Auth::user();
        $company_vacations_count_by_months = Employee::changeVacationsYear($user->employee_id, $rok);
        return response()->json(['data_vacations' => $company_vacations_count_by_months]);
    }

    public function changeEmployeeDiseasesGraphYear($rok){
        $user=Auth::user();
        $company_diseases_by_months = Employee::changeDiseasesYear($user->employee_id, $rok);
        return response()->json(['data_diseases' => $company_diseases_by_months]);
    }

    public function changeEmployeeReportsGraphYear($rok){
        $user=Auth::user();
        $company_reports_count_by_months = Employee::changeReportsYear($user->employee_id, $rok);
        return response()->json(['data_reports' => $company_reports_count_by_months]);
    }

}
