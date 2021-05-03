<?php

namespace App\Http\Controllers;

use App\Models\Admin;

class AdminStatisticsController extends Controller {
    /* Nazev souboru:  AdminStatisticsController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k ziskani statistik a naslednemu poslani techto statistik do pohledu, ktery se zobrazi uzivateli s roli admina.
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
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni statistik v ramci uctu s roli admina */
    public function index(){
        /* Usek kodu zabyvajici se ziskanim jednotlivvch statistik skrze modely */
        $assigned_shifts_count = Admin::getCountOfAssignedShifts();
        $assigned_shifts_future_count = Admin::getCountUpcomingShiftsAssigned();
        $employees_total_count = Admin::getCompaniesEmployeesCount();
        $shifts_total_count = Admin::getCompanyTotalShiftCount();
        $companies_total_count = Admin::getCompaniesCount();
        /* Grafy v case potrebuji data ve formatu json, proto je zde pouzita metoda json_encode() */
        $companies_count_by_months = json_encode(Admin::getNewCompaniesCountByMonths());
        $companies_employees_count_by_months = json_encode(Admin::getNewCompaniesEmployeesCountByMonths());
        $companies_shifts_count_by_months = json_encode(Admin::getNewCompaniesShiftsCountByMonths());
        $company_assigned_shifts_count_by_months = json_encode(Admin::getCountOfShiftsAssignedByMonths());

        /* Odeslani statistik spolecne s danym pohledem pro zobrazeni statistik */
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

    /* Nazev funkce: changeCompanyGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu nove zaregistrovanych firem dle mesicu */
    public function changeCompanyGraphYear($rok){
        /* ziskani dat na zaklade roku */
        $companies_count_by_months = Admin::changeCompaniesGraphYear($rok);
        /* odeslani techto dat do pohledu */
        return response()->json(['data_companies'=> $companies_count_by_months]);
    }

    /* Nazev funkce: changeAdminEmployeesGraphYear
      Argumenty: rok - rok, na ktery chceme graf zmenit
      Ucel: Zmena roku u grafu poctu novych zamestnancu firem dle mesicu */
    public function changeAdminEmployeesGraphYear($rok){
        $companies_employees_count_by_months = Admin::changeEmployeesGraphYear($rok);
        return response()->json(['data_employees'=> $companies_employees_count_by_months]);
    }

    /* Nazev funkce: changeAdminShiftsGraphYear
       Argumenty: rok - rok, na ktery chceme graf zmenit
       Ucel: Zmena roku u grafu poctu vypsanych smen firem dle mesicu */
    public function changeAdminShiftsGraphYear($rok){
        $companies_shifts_count_by_months = Admin::changeShiftsGraphYear($rok);
        return response()->json(['data_shifts'=> $companies_shifts_count_by_months]);

    }

    /* Nazev funkce: changeAdminShiftsAssignedGraphYear
        Argumenty: rok - rok, na ktery chceme graf zmenit
        Ucel: Zmena roku u grafu poctu obsazenych smen firem dle mesicu */
    public function changeAdminShiftsAssignedGraphYear($rok){
        $company_assigned_shifts_count_by_months = Admin::changeShiftsAssignedYear($rok);
        return response()->json(['data_shifts_assigned'=> $company_assigned_shifts_count_by_months]);
    }

}
