<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeStatisticsController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('employee_actions.statistics')
            ->with('profilovka',$user->employee_picture);
    }
}
