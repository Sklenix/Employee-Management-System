<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Injury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EmployeeInjuriesController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('employee_actions.injuries_history')
            ->with('profilovka',$user->employee_picture);
    }

    public function getEmployeeInjuries(Request $request){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        if ($request->ajax()) {
            $zraneni = Injury::getEmployeeInjuriesInjuryCentre($user->employee_id);
            return Datatables::of($zraneni)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
