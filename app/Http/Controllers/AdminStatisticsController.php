<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminStatisticsController extends Controller
{
    public function index(){
        $assigned_shifts_count = Admin::getCountOfAssignedShifts();
        $assigned_shifts_future_count = Admin::getCountUpcomingShiftsAssigned();
        $employees_total_count = Admin::getCompaniesEmployeesCount();
        $shifts_total_count = Admin::getCompanyTotalShiftCount();
        $companies_total_count = Admin::getCompaniesCount();

        $companies_count_by_months = Admin::getNewCompaniesCountByMonths();
        $companies_employees_count_by_months = Admin::getNewCompaniesEmployeesCountByMonths();
        $companies_shifts_count_by_months = Admin::getNewCompaniesShiftsCountByMonths();
        $company_assigned_shifts_count_by_months = Admin::getCountOfShiftsAssignedByMonths();

        return view('admin_actions.statistics')
             ->with('assigned_shifts_count', $assigned_shifts_count)
             ->with('assigned_shifts_future_count', $assigned_shifts_future_count)
             ->with('employees_total_count', $employees_total_count)
             ->with('shifts_total_count', $shifts_total_count)
             ->with('companies_total_count', $companies_total_count)
             ->with('companies_count_by_months', $companies_count_by_months)
             ->with('companies_employees_count_by_months', $companies_employees_count_by_months)
             ->with('companies_shifts_count_by_months', $companies_shifts_count_by_months)
             ->with('company_assigned_shifts_count_by_months', $company_assigned_shifts_count_by_months);
    }

    public function changeCompanyGraphYear($rok){
        $companies_count_by_months = Admin::changeCompaniesGraphYear($rok);
        return response()->json(['data_companies'=> $companies_count_by_months]);
    }

    public function changeAdminEmployeesGraphYear($rok){
        $companies_employees_count_by_months = Admin::changeEmployeesGraphYear($rok);
        return response()->json(['data_employees'=> $companies_employees_count_by_months]);
    }

    public function changeAdminShiftsGraphYear($rok){
        $companies_shifts_count_by_months = Admin::changeShiftsGraphYear($rok);
        return response()->json(['data_shifts'=> $companies_shifts_count_by_months]);

    }

    public function changeAdminShiftsAssignedGraphYear($rok){
        $company_assigned_shifts_count_by_months = Admin::changeShiftsAssignedYear($rok);
        return response()->json(['data_shifts_assigned'=> $company_assigned_shifts_count_by_months]);

    }

}
