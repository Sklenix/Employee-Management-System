<?php

namespace App\Http\Controllers;

use App\Models\Injury;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EmployeeInjuriesController extends Controller {
    /* Nazev souboru:  EmployeeInjuriesController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci zobrazeni historie zraneni v uctu s roli zamestnance.
    Nazvy jednotlivych metod jsou konvenci frameworku laravel, viz https://laravel.com/docs/8.x/controllers
    Pro nauceni prace s datovymi tabulkami yajra slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
    Knihovna Yajra pro datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
    Licence k Yajra datovym tabulkam:
    (The MIT License)
    Copyright (c) 2013-2020 Arjay Angeles aqangeles@gmail.com
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction,
    including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
    subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
        IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
         WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
    */

    /* Nazev funkce: index
       Argumenty: zadne
       Ucel: Zobrazeni prislusneho pohledu pro zobrazeni historie zraneni konkretniho zamestnance */
    public function index(){
        $user = Auth::user();
        return view('employee_actions.injuries_history')
                ->with('profilovka', $user->employee_picture)->with('employee_url', $user->employee_url);
    }

    /* Nazev funkce: getEmployeeInjuries
       Argumenty: zadne
       Ucel: Zobrazeni datove tabulky reprezentujici historii zraneni zamestnance */
    public function getEmployeeInjuries(){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        /* Ziskani vsech zraneni zamestnance */
        $zraneni = Injury::getEmployeeInjuriesInjuryCentre($user->employee_id);
        /* Vyrenderovani tabulky */
        return Datatables::of($zraneni)
            ->addIndexColumn()
            ->make(true);
    }

}
