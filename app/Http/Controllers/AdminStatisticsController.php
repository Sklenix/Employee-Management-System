<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminStatisticsController extends Controller
{
    public function index(){
        return view('admin_actions.statistics');
    }
}
