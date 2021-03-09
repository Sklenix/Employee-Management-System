<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Disease;
use App\Models\Injury;
use App\Models\Shift;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeStatisticsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $pocetSmen = Shift::getEmployeeShiftsCount($user->employee_id);
        $pocetAbsenci = Attendance::getEmployeeAbsenceCount($user->employee_id);
        $pocetDovolenych = Vacation::getEmployeeVacationsCount($user->employee_id);
        $pocetNemoci = Disease::getEmployeeDiseasesCount($user->employee_id);
        $pocetZraneni = Injury::getEmployeeInjuriesInjuryCentreCount($user->employee_id);
        return view('employee_actions.statistics')
            ->with('profilovka',$user->employee_picture)
            ->with('pocetSmen',$pocetSmen)
            ->with('pocetAbsenci',$pocetAbsenci)
            ->with('pocetDovolenych',$pocetDovolenych);
    }
}
