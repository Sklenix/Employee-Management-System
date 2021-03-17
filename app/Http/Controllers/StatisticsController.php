<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee;
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
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->whereIn('table_importances_shifts.importance_id',[1,2,3,4,5])
            ->get();
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($user->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($user->company_id);
        $pocetNadchazejicich = Shift::getUpcomingCompanyShiftsCount($user->company_id);
        $pocetHistorie = Shift::getHistoricalCompanyShiftsCount($user->company_id);
        $datumVytvoreni = new DateTime($user->created_at);
        $datumZobrazeniVytvoreni = $datumVytvoreni->format('d.m.Y');

        $zamestnanci = DB::table('table_employees')
            ->select(DB::raw("COUNT(*) as count"))
            ->where('employee_company', $user->company_id)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_employees')
            ->select(DB::raw("Month(created_at) as month"))
            ->where('employee_company', $user->company_id)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $smeny = DB::table('table_shifts')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->where('company_id', $user->company_id)
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('table_shifts')
            ->select(DB::raw("Month(shift_start) as month_shift"))
            ->where('company_id', $user->company_id)
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('month_shift');

        $data_employees = array(0,0,0,0,0,0,0,0,0,0,0,0);
        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($mesice as $index => $month){
            $data_employees[$month - 1] = $zamestnanci[$index];
        }

        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }

        $average_overall_score = Company::getAverageEmployeeScore($user->company_id);
        $average_reliability_score = Company::getAverageEmployeeReliabilityScore($user->company_id);
        $average_absence_score = Company::getAverageEmployeeAbsenceScore($user->company_id);
        $average_work_score = Company::getAverageEmployeeWorkScore($user->company_id);

        $average_shift_hour = Company::getAverageShiftHour($user->company_id);
        $max_shift_hour = Company::getMaxShiftHour($user->company_id);
        $min_shift_hour = Company::getMinShiftHour($user->company_id);

        $total_worked_hours = OlapAnalyzator::getTotalShiftWorkedHours($user->company_id);
        $shifts_assigned_count = OlapAnalyzator::getCountOfShiftFacts($user->company_id);



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
        $zamestnanci = DB::table('table_employees')
            ->select(DB::raw("COUNT(*) as count"))
            ->where('employee_company', $user->company_id)
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_employees')
            ->select(DB::raw("Month(created_at) as month"))
            ->where('employee_company', $user->company_id)
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $data_employees = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice as $index => $month){
            $data_employees[$month - 1] = $zamestnanci[$index];
        }
        return response()->json(['data_employees'=> $data_employees]);
    }

    public function changeShiftGraphYear($rok){
        $user = Auth::user();
        $smeny = DB::table('table_shifts')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->where('company_id', $user->company_id)
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('table_shifts')
            ->select(DB::raw("Month(created_at) as month_shift"))
            ->where('company_id', $user->company_id)
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
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
