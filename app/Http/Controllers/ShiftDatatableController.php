<?php

namespace App\Http\Controllers;

use App\Http\Controllers\OlapETL;
use App\Models\AbsenceReason;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee_Language;
use App\Models\Employee_Shift;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use DateTime;
use Google_Client;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Employee;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Session;
use View;

class ShiftDatatableController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();

        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->whereIn('table_importances_shifts.importance_id',[1,2,3,4,5])
            ->get();

        return view('company_actions.shift_list')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance);
    }

    public function getShifts(Request $request){
        $user = Auth::user();
        if ($request->ajax()) {
            $data = Shift::where('company_id',$user->company_id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('shift_taken', function($data){
                    $res = DB::table('table_employee_shifts')
                        ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                        ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                        ->select('table_employee_shifts.employee_id')
                        ->where([ 'table_shifts.shift_id' => $data->shift_id])
                        ->get();
                    if($res->isEmpty()){
                        return '<input type="checkbox" name="shift_taken" value="0" onclick="return false;">';
                    }else{
                        return '<input type="checkbox" name="shift_taken" value="1" onclick="return false;" checked>';
                    }
                })
                ->addColumn('shift_importance_id', function($data){
                    $dulezitostView = '';
                    $aktualniDulezitost = DB::table('table_importances_shifts')
                        ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
                        ->join('table_shifts','table_shifts.shift_importance_id','=','table_importances_shifts.importance_id')
                        ->where(['table_shifts.shift_importance_id' => $data->shift_importance_id])
                        ->get();
                    return $aktualniDulezitost[0]->importance_description;
                })
                ->addColumn('action', function($data){
                    return '<button type="button" class="btn btn-primary btn-sm" id="getEditShiftData" data-toggle="modal"  data-target="#EditShiftModal" data-id="'.$data->shift_id.'"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                            <button type="button" data-id="'.$data->shift_id.'" data-toggle="modal" data-target="#DeleteArticleModal" class="btn btn-danger btn-sm" id="getShiftID">&nbsp;&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i> Smazat &nbsp;</button>
                            <button type="button" data-id="'.$data->shift_id.'" data-toggle="modal" style="margin-top:5px;" data-target="#AssignEmployeeModal" class="btn btn-dark btn-sm" id="getShiftAssign"><i class="fa fa-exchange" aria-hidden="true"></i> Přiřadit &nbsp;</button>
                            <button type="button" data-id="'.$data->shift_id.'" data-toggle="modal" style="margin-top:5px;" data-target="#ShowAttendanceOptionsModal" class="btn btn-success btn-sm" id="getEmployeesOptions"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Docházka</button>
                    ';
                })
                ->rawColumns(['action','shift_taken'])
                ->make(true);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'shift_start' => ['required'],
            'shift_end' =>  ['required'],
            'shift_place' =>  ['required', 'string', 'max:255'],
        ]);

        $shift_start = new DateTime($request->shift_start);
        $shift_end = new DateTime($request->shift_end);
        $now = new DateTime();

      //  $difference_start = $shift_start->format('U') - $now->format('U');
      //  $difference_end = $shift_end->format('U') - $now->format('U');
        $difference_shifts = $shift_end->format('U') - $shift_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;

        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetDnu = $hodinyRozdil->d;
        $pocetHodin = $hodinyRozdil->h;
        $pocetMinut = $hodinyRozdil->i;

        if($request->shift_start != NULL){
            if($difference_shifts <= 0){
                array_push($chybaDatumy,'Konec směny je stejný jako její začátek, nebo je dříve než začátek!');
                $bool_datumy = 1;
            }

            if(($pocetHodin == 12 && $pocetMinut > 0) || $pocetHodin > 12 || $pocetDnu > 0){
                array_push($chybaDatumy,'Maximální délka jedné směny je 12 hodin!');
                $bool_datumy = 1;
            }
        }

        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }
        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }

        $user = Auth::user();
        $shift = new Shift();
        $shift->shift_start = $request->shift_start;
        $shift->shift_end = $request->shift_end;
        $shift->shift_place = $request->shift_place;
        $shift->shift_importance_id = $request->shift_importance_id;
        $shift->shift_note = $request->shift_note;
        $shift->company_id = $user->company_id;
        $shift->save();

        return response()->json(['success'=>'Směna byla úspešně vytvořena.']);
    }

    public function edit($id){
        $shift = new Shift();
        $user = Auth::user();
        $data = $shift->findData($id);
        $datumStart = date('Y-m-d\TH:i', strtotime($data->shift_start));
        $datumEnd = date('Y-m-d\TH:i', strtotime($data->shift_end));
        $shift_start = new DateTime($data->shift_start);
        $shift_end = new DateTime($data->shift_end);
        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetHodin = $hodinyRozdil->h;
        $pocetMinut = $hodinyRozdil->i;

        $moznostiDulezitosti = ImportancesShifts::getAllImportances();
        $aktualniDulezitost = ImportancesShifts::getParticularImportance($data->shift_importance_id);
        $vypisDulezitosti = "";
        $tabulka = '
                    <table class="table table-dark" id="show_table" style="font-size: 16px;">
                    <thead>
                        <tr>
                            <th scope="col" style="width:30%;text-align: center;">Jméno <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(0,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Pozice <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(1,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Skóre <i class="fa fa-sort-numeric-desc" style="margin-left: 5px" onclick="zmenaIkonkyCisla(this);sortTable(2,this);"></i></th>
                            <th scope="col" style="width:10%;text-align: center;">Přišel/Přišla</th>
                            <th scope="col" style="width:10%;text-align: center;">Status</th>
                              <th scope="col" style="width:10%;text-align: center;">Odpracováno</th>
                        </tr>
                    </thead>
                    <tbody>';
        $zamestnanci = Shift::getAllEmployeesAtShift($id);
        foreach ($zamestnanci as $zamestnanec){
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($id, $zamestnanec->employee_id);
            $skore = ($zamestnanec->employee_reliability + $zamestnanec->employee_absence + $zamestnanec->employee_workindex) / 3;
            if($dochazka->isEmpty()){
                $tabulka .= '<tr>
                                <td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td>
                                <td class="text-center"> '.$zamestnanec->employee_position.'</td> <td class="text-center"> '.round($skore,2).'</td>
                                <td class="text-center"><p style="color:yellow;">Nezapsáno</p></td>
                                <td class="text-center"><p style="color:yellow;">Neznámý</p></td>
                                <td class="text-center"><p style="color:yellow;">Nezapsaný check-in/out</p></td>';
            }else{
                $status = AbsenceReason::getParticularReason( $dochazka[0]->absence_reason_id);
                $statView = "";
                if($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL){
                    $statView = '<p style="color:yellow;">Neznámý</p>';
                }else{
                    if($dochazka[0]->absence_reason_id == 5){
                        $statView = '<p style="color:lightgreen;">'.$status[0]->reason_description.'</p>';
                    }else{
                        $statView = '<p style="color:orangered;">'.$status[0]->reason_description.'</p>';
                    }
                }
                $odpracovano = '';
                if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                    if($dochazka[0]->attendance_check_in == NULL || $dochazka[0]->attendance_check_out == NULL){
                        $odpracovano = '<p style="color:yellow;">Nezapsaný check-in/out</p>';
                    }else if($dochazka[0]->attendance_check_in != NULL && $dochazka[0]->attendance_check_out != NULL){
                        $checkin = new DateTime($dochazka[0]->attendance_check_in);
                        $checkout = new DateTime($dochazka[0]->attendance_check_out);
                        $hodinyRozdilCheck =$checkout->diff($checkin);
                        $odpracovano = '<p style="color:white;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                    }
                }else if($dochazka[0]->attendance_check_in_company != NULL && $dochazka[0]->attendance_check_out_company != NULL){
                    $checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                    $checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                    $hodinyRozdilCheck =$checkout->diff($checkin);
                    $odpracovano = '<p style="color:white;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                }
                if($dochazka[0]->attendance_came == NULL || $dochazka[0]->attendance_came == 0){
                    $tabulka .= '<tr>
                                    <td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td>
                                    <td class="text-center"> '.$zamestnanec->employee_position.'</td>
                                    <td class="text-center"> '.round($skore,2).'</td>
                                    <td class="text-center"> <p style="color:orangered;">Ne</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>';
                }else{
                    $tabulka .= '<tr>
                                    <td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td>
                                    <td class="text-center"> '.$zamestnanec->employee_position.'</td>
                                    <td class="text-center"> '.round($skore,2).'</td>
                                    <td class="text-center"><p style="color:lightgreen;">Ano</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>';
                }
            }
            }
        $tabulka .= "</tbody></table>";
        foreach ($aktualniDulezitost as $dulezitost) {
            $vypisDulezitosti .= ' <option value="'.$dulezitost->importance_id.'">'.$dulezitost->importance_description.'</option>';
        }
        for($i = 0;$i < count($moznostiDulezitosti);$i++){
                if($aktualniDulezitost[0]->importance_id != $moznostiDulezitosti[$i]->importance_id){
                    $vypisDulezitosti .= ' <option value="'.$moznostiDulezitosti[$i]->importance_id.'">'.$moznostiDulezitosti[$i]->importance_description.'</option>';
                }
        }
        $html = '  <ul class="nav nav-stacked nav-pills d-flex justify-content-center" style="font-size: 15px;" id="menuTabu">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#zamestnanci" >Zaměstnanci</a>
                        </li>
                    </ul>
                   <div id="my-tab-content" style="margin-top:20px;" class="tab-content">
                    <div class="tab-pane active" id="obecneUdaje">
                    <div class="text-center" style="margin-top:5px;margin-bottom:15px;font-size:16px;padding:10px;border-radius: 10px;background-color: #2d995b;">'.$aktualniDulezitost[0]->importance_description.'</div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Začátek směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="shift_start_edit" id="shift_start_edit" value="'.$datumStart.'" autocomplete="shift_start_edit" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Konec směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="shift_end_edit" id="shift_end_edit" value="'.$datumEnd.'" autocomplete="shift_end_edit" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Místo(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building" aria-hidden="true"></i></div>
                                        </div>
                                        <input id="shift_place_edit" placeholder="Zadejte lokaci směny..." type="text" class="form-control" name="shift_place_edit" value="'.$data->shift_place.'"  autocomplete="shift_place_edit">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Důležitost</label>
                                <div class="col-md-10">
                                    <select name="shiftImportance_edit" id="shiftImportance_edit" style="color:black;text-align-last: center;" class="form-control" data-dependent="state">
                                        '.$vypisDulezitosti.'
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Poznámka</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                        </div>
                                        <textarea name="shift_note_edit" placeholder="Zadejte poznámku ke směně..." id="shift_note_edit" class="form-control" autocomplete="shift_note_edit">'.$data->shift_note.'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <p class="d-flex justify-content-center" style="background-color: #333333;color:white;border-radius: 15px;padding:10px;font-size: 15px;">Délka směny: '.$pocetHodin.'h'.$pocetMinut.'m.</p>
                    <p class="d-flex justify-content-center">Směna vytvořena '.$data->created_at.', naposledy aktualizována '.$data->updated_at.'.</p>
                    </div>
                    <div class="tab-pane" id="zamestnanci">
                        <p style="font-size: 17px;text-align: center;">Tato směna je obsazena následujícími zaměstnanci (zaměstnancem):</p>
                         <input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavac" onkeyup="Search()" placeholder="Hledat zaměstnance na základě jména, pozice, nebo skóre ..." title="Zadejte údaje o zaměstnanci">
                        '.$tabulka.'
                    </div>
                  </div>
              </div>
               <script>
                    function zmenaIkonky(x) {
                        x.classList.toggle("fa-sort-alpha-desc");
                        x.classList.toggle("fa-sort-alpha-asc");
                    }
                     function zmenaIkonkyCisla(x) {
                         x.classList.toggle("fa-sort-numeric-asc");
                         x.classList.toggle("fa-sort-numeric-desc");
                     }
                    function Search() {
                        var input, filter, table, tr, td, td2, td3, td4, td5, i, txtValue, txtValue2, txtValue3, txtValue4, txtValue5;
                      input = document.getElementById("vyhledavac");
                      filter = input.value.toUpperCase();
                      table = document.getElementById("show_table");
                      tr = table.getElementsByTagName("tr");

                      for (i = 0; i < tr.length; i++) {
                          td3 = tr[i].getElementsByTagName("td")[0];
                          td = tr[i].getElementsByTagName("td")[1];
                          td2 = tr[i].getElementsByTagName("td")[2];
                          td4 = tr[i].getElementsByTagName("td")[3];
                          td5 = tr[i].getElementsByTagName("td")[4];
                          if (td || td2 || td3 || td4) {
                              txtValue = td.textContent || td.innerText;
                              txtValue2 = td2.textContent || td2.innerText;
                              txtValue3 = td3.textContent || td3.innerText;
                              txtValue4 = td4.textContent || td4.innerText;
                              txtValue5 = td5.textContent || td5.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1
                                  || txtValue3.toUpperCase().indexOf(filter) > -1 || txtValue4.toUpperCase().indexOf(filter) > -1
                                  || txtValue5.toUpperCase().indexOf(filter) > -1) {
                                  tr[i].style.display = "";
                              } else {
                                  tr[i].style.display = "none";
                              }
                          }
                      }
                    }

                     function sortTable(n,ikonka) {
                         var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                      table = document.getElementById("show_table");
                      switching = true;
                      dir = "asc";
                      while (switching) {
                          switching = false;
                          rows = table.rows;

                          for (i = 1; i < (rows.length - 1); i++) {
                              shouldSwitch = false;
                              x = rows[i].getElementsByTagName("TD")[n];
                              y = rows[i + 1].getElementsByTagName("TD")[n];

                              if (dir == "asc") {
                                  if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                      shouldSwitch= true;
                                      break;
                                  }
                              } else if (dir == "desc") {
                                  if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                      shouldSwitch = true;
                                      break;
                                  }
                              }
                          }
                          if (shouldSwitch) {
                              rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                              switching = true;
                              switchcount ++;
                          } else {
                              if (switchcount == 0 && dir == "asc") {
                                  dir = "desc";
                                  switching = true;
                              }
                          }
                      }
                    }
                 </script>';
        return response()->json(['html'=>$html]);
    }

    public function update(Request $request, $id){
        $vysledek = Shift::find($id);
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'shift_start' => ['required'],
            'shift_end' =>  ['required'],
            'shift_place' =>  ['required', 'string', 'max:255'],
        ]);
        $shift_start = new DateTime($request->shift_start);
        $shift_end = new DateTime($request->shift_end);
        $difference_shifts = $shift_end->format('U') - $shift_start->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;

        if($difference_shifts < 0){
            array_push($chybaDatumy,'Konec směny je stejný, nebo je dříve než její začátek!');
            $bool_datumy = 1;
        }
        foreach ($validator->errors()->all() as $valid){
            array_push($chybaDatumy,$valid);
        }
        if ($validator->fails() || $bool_datumy == 1) {
            return response()->json(['errors' => $chybaDatumy]);
        }
        $zamestnanci = Shift::getAllEmployeesAtShift($id);
        $employee_ids = array();
        foreach ($zamestnanci as $zamestnanec) {
            array_push($employee_ids, $zamestnanec->employee_id);
        }
        $bool = 0;
        $shift_db_start = new DateTime($vysledek->shift_start);
        $shift_db_end = new DateTime($vysledek->shift_end);
        $diffValidStart = $shift_db_start->format('U') - $shift_start->format('U');
        $diffValidEnd = $shift_db_end->format('U') - $shift_end->format('U');
        if(($diffValidStart == 0) && ($diffValidEnd == 0)
            && ($vysledek->shift_place == $request->shift_place) && ($vysledek->shift_importance_id == $request->shift_importance_id)
            && ($vysledek->shift_note == $request->shift_note)){
            $bool = 0;
        }else{
            $bool = 1;
        }
        OlapETL::updateShiftInfoDimension($employee_ids, $user->company_id,$vysledek->shift_start, $vysledek->shift_end ,$request->shift_start,  $request->shift_end);
        $vysledek->shift_start = $request->shift_start;
        $vysledek->shift_end = $request->shift_end;
        $vysledek->shift_place = $request->shift_place;
        $vysledek->shift_importance_id = $request->shift_importance_id;
        $vysledek->shift_note = $request->shift_note;
        $vysledek->company_id = $user->company_id;
        $vysledek->save();
        OlapETL::updateShiftTotalHoursField($vysledek, $employee_ids, $user->company_id);
        if($bool != 1 ){
            return response()->json(['success'=>'0']);
        }else{
            return response()->json(['success'=>'Směna byla úspěšně zeditována.']);
        }
    }

    public function assignEmployee($id){
        $user = Auth::user();
        $aktualniSmena = Shift::findOrFail($id);
        $shift_start = new DateTime($aktualniSmena->shift_start);
        $shift_end = new DateTime($aktualniSmena->shift_end);
        $hodinyRozdil = $shift_end->diff($shift_start);
        $pocetZamestnancuNaSmene = Shift::getEmployeesCountAtShift($id);
        $hodiny = $hodinyRozdil->h * $pocetZamestnancuNaSmene;
        $minuty = $hodinyRozdil->i * $pocetZamestnancuNaSmene;
        $html = ' <div class="alert alert-warning" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <center>
                           Celkový počet hodin na této směně: <br>
                            '.$hodiny.'h'.$minuty.'m
                       <center>
                    </div>';
        $html .= '
             <input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavac" onkeyup="Search()" placeholder="Hledat zaměstnance na základě jména, pozice, nebo ID ..." title="Zadejte jméno zaměstnance">
             <table class="table table-dark" id="tableShifts" style="font-size: 16px;">
                    <thead>
                        <tr>
                            <th scope="col" style="width:5%;text-align: center;">ID</th>
                            <th scope="col" style="width:35%;text-align: center;">Jméno <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(1,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Pozice <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(2,this);"></i></th>
                            <th scope="col" style="width:25%;text-align: center;">Skóre <i class="fa fa-sort-numeric-desc" style="margin-left: 5px" onclick="zmenaIkonkyCisla(this);sortTable(3,this);"></i></th>
                            <th scope="col" style="width:6%;text-align: center;">Obsazeno</th>
                        </tr>
                    </thead>
                <tbody>';
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        foreach ($zamestnanci as $zamestnanec){
            $aktualniZamestnanec = Employee_Shift::getEmployeeParticularShift($zamestnanec->employee_id, $id);
            $skore = ($zamestnanec->employee_reliability + $zamestnanec->employee_absence + $zamestnanec->employee_workindex) / 3;
            $html .= '<tr><td class="text-center">'.$zamestnanec->employee_id.'</td><td class="text-center"> '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</td><td class="text-center"> '.$zamestnanec->employee_position.'</td> <td class="text-center"> '.round($skore,2).'</td>';
            if($aktualniZamestnanec->isEmpty()){
                $html .= '<td><center><input type="checkbox" name="shift_employee_assign_id" class="form-check-input shift_employee_assign_id" id="shift_employee_assign_id" name="shift_employee_assign_id[]" value="'.$zamestnanec->employee_id.'"></center></td> </tr>';
            }else{
                $html .= '<td><center><input type="checkbox" name="shift_employee_assign_id" class="form-check-input shift_employee_assign_id" id="shift_employee_assign_id" name="shift_employee_assign_id[]" value="'.$zamestnanec->employee_id.'" checked></center></td> </tr>';
            }
        }
        $html .= "</tbody></table>";
        $html .= '<script>
                    function zmenaIkonky(x) {
                      x.classList.toggle("fa-sort-alpha-desc");
                      x.classList.toggle("fa-sort-alpha-asc");
                    }
                     function zmenaIkonkyCisla(x) {
                        x.classList.toggle("fa-sort-numeric-asc");
                        x.classList.toggle("fa-sort-numeric-desc");
                     }
                    function Search() {
                      var input, filter, table, tr, td, td2, td3, i, txtValue, txtValue2, txtValue3;
                      input = document.getElementById("vyhledavac");
                      filter = input.value.toUpperCase();
                      table = document.getElementById("tableShifts");
                      tr = table.getElementsByTagName("tr");
                      for (i = 0; i < tr.length; i++) {
                          td3 = tr[i].getElementsByTagName("td")[0];
                          td = tr[i].getElementsByTagName("td")[1];
                          td2 = tr[i].getElementsByTagName("td")[2];
                          if (td || td2 || td3) {
                              txtValue = td.textContent || td.innerText;
                              txtValue2 = td2.textContent || td2.innerText;
                              txtValue3 = td3.textContent || td3.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1 || txtValue3.toUpperCase().indexOf(filter) > -1) {
                                  tr[i].style.display = "";
                              } else {
                                  tr[i].style.display = "none";
                              }
                          }
                      }
                    }
                     function sortTable(n,ikonka) {
                      var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                      table = document.getElementById("tableShifts");
                      switching = true;
                      dir = "asc";
                      while (switching) {
                        switching = false;
                        rows = table.rows;

                        for (i = 1; i < (rows.length - 1); i++) {
                          shouldSwitch = false;
                          x = rows[i].getElementsByTagName("TD")[n];
                          y = rows[i + 1].getElementsByTagName("TD")[n];

                          if (dir == "asc") {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                              shouldSwitch= true;
                              break;
                            }
                          } else if (dir == "desc") {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                              shouldSwitch = true;
                              break;
                            }
                          }
                        }
                        if (shouldSwitch) {
                          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                          switching = true;
                          switchcount ++;
                        } else {
                          if (switchcount == 0 && dir == "asc") {
                            dir = "desc";
                            switching = true;
                          }
                        }
                      }
                    }
                 </script>';
        return response()->json(['html'=>$html]);
    }

    public function updateassignEmployee(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        $shift = Shift::findOrFail($id);
        $jmena = '';
        $count = 0;
        if($request->employees_ids != "") {
            $employee_id_arr = explode('&', $request->employees_ids);
            $delka = count($employee_id_arr);
            $employee_ids_collector = array();
            foreach ($employee_id_arr as $employee_id) {
                $employee_id_value = explode('=', $employee_id);
                array_push($employee_ids_collector,$employee_id_value[1]);
            }
            OlapETL::deleteCancelledPreviouslyAssignedEmployee($shift->shift_start, $shift->shift_end, $employee_ids_collector);
            Employee_Shift::deleteAssignedEmployeesShiftWithAttendance($shift->shift_id, $employee_ids_collector);
            foreach ($employee_id_arr as $employee_id) {
                $employee_id_value = explode('=', $employee_id);
                $aktualniZamestnanec = Employee_Shift::getEmployeeParticularShift($employee_id_value[1], $id);
                $employee = Employee::findOrFail($employee_id_value[1]);
                if ($count == $delka - 1) {
                    $jmena .= $employee->employee_name.' '.$employee->employee_surname.'.';
                }else{
                    $jmena .= $employee->employee_name.' '.$employee->employee_surname.', ';
                }
                if($aktualniZamestnanec->isEmpty()){
                    $user = Auth::user();
                    Employee_Shift::create(['shift_id' => $shift->shift_id, 'employee_id' => $employee_id_value[1]]);
                    $shift_info_id = OlapETL::extractDataToShiftInfoDimension($shift);
                    $time_id = OlapETL::extractDataToTimeDimension($shift_info_id, $shift);
                    $employee_id = OlapETL::extractDataToEmployeeDimension($employee);
                    $company_id = OlapETL::extractDataToCompanyDimension($user);
                    OlapETL::extractDataToShiftFact($shift, $employee, $shift_info_id, $time_id, $employee_id, $company_id);
                    //return response()->json(['success' => 'Firma je: '.$company_id.', ID času je: '.$time_id.', ID zaměstnance je: '.$employee_id.', ID směny je: '.$shift_info_id]);
                }
                $count++;
            }
        }
        if($count > 0){
        }else{
            OlapETL::deleteAllCancelledPreviouslyAssignedEmployee($shift->shift_start, $shift->shift_end);
            Employee_Shift::deleteAllAssignedEmployeesShiftWithAttendance($shift->shift_id);
        }
        substr_replace($jmena, ".", -1);
        if($jmena == ""){
            return response()->json(['success'=>'Ze směny byly úspešně odebráni všichni zaměstnanci.']);
        }
        if($count == 1){
            return response()->json(['success'=>'Směna v lokaci: '.$shift->shift_place.', v čase od: '.$shift->shift_start.' do '.$shift->shift_end.' byla úspěšně přiřazena tomuto zaměstnanci: '.$jmena]);
        }else{
            return response()->json(['success'=>'Směna v lokaci: '.$shift->shift_place.', v čase od: '.$shift->shift_start.' do '.$shift->shift_end.' byla úspěšně přiřazena těmto zaměstnancům: '.$jmena]);
        }
    }

    public function destroy($id){
        $user = Auth::user();
        $smena = Shift::findOrFail($id);
        $zamestnanci = Shift::getAllEmployeesAtShift($id);
        $employee_ids = array();
        foreach ($zamestnanci as $zamestnanec) {
            array_push($employee_ids, $zamestnanec->employee_id);
        }
        OlapETL::deleteRecordFromShiftInfoDimension($employee_ids, $user->company_id, $smena->shift_start, $smena->shift_end);
        Shift::findOrFail($id)->delete();
        Employee_Shift::deleteShiftFromShiftDatatable($id);

        return response()->json(['success'=>'Směna byla úspěšně smazána.']);
    }

    public function getAttendanceOptions($id){
        $user = Auth::user();
        $html  = '';
        $zamestnanci = Employee_Shift::getAttendanceOptionsShifts($id, $user->company_id);
        if(count($zamestnanci) == 0){
            $html .= '<div class="alert alert-danger alert-block">
                            <strong>Ke směně nejsou přiřazeni žádní zaměstnanci</strong>
                        </div>';
        }else{
            $html .='<div class="form-group">
                            <select name="vybrany_zamestnanec" required id="vybrany_zamestnanec" style="color:black" class="form-control input-lg dynamic vybrany_zamestnanec" data-dependent="state">
                                 <option value="">Vyberte zaměstnance</option>';
            foreach ($zamestnanci as $zamestnanec){
                $html .= '<option id="'.$zamestnanec->employee_id.'" value="'.$zamestnanec->employee_id.'">'.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</option>';
            }
            $html .= ' </select></div>';
        }
        $html .= '<center><button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckinModal" class="btn btn-primary" id="getCheckInShift" "><i class="fa fa-check-square-o" aria-hidden="true"></i> Check-in</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckoutModal" class="btn btn-primary" id="getCheckOutShift" "><i class="fa fa-check-square-o" aria-hidden="true"></i> Check-out</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceAbsenceModal" class="btn btn-primary" id="getAbsenceReasonAttendance" "><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Status</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceNoteModal" class="btn btn-primary" id="getNoteAttendance" "><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Poznámka</button>
                  ';
        return response()->json(['html'=>$html]);
    }

    public function showCheckin($zamestnanec_id,$smena_id){
         $html = '';
         date_default_timezone_set('Europe/Prague');
         if($zamestnanec_id == "undefined"){
             $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádného zaměstnance.</strong>
                        </div>';
             return response()->json(['html'=>$html]);
         }
          $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
          $smena = Shift::findOrFail($smena_id);
          if($dochazka->isEmpty()){
              $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
              $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
              $html .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'"  autocomplete="attendance_create_checkin" autofocus>';
          }else{
              if($dochazka[0]->attendance_check_in_company == NULL){
                  $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
                  $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
                  $html .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'"  autocomplete="attendance_create_checkin" autofocus>';
              }else{
                  $date_start = new DateTime($dochazka[0]->attendance_check_in_company);
                  $datumZobrazeni = $date_start->format('d.m.Y H:i');
                  $datumStart = date('Y-m-d\TH:i', strtotime($dochazka[0]->attendance_check_in_company));
                  $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: '.$datumZobrazeni.'</strong>
                    </div>';
                  $html .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'"  autocomplete="attendance_create_checkin   " autofocus>';
              }
          }
        return response()->json(['html'=>$html]);
    }

    public function updateCheckIn(Request $request,$zamestnanec_id,$smena_id){
        $user = Auth::user();
        $smena = Shift::findOrFail($smena_id);
        $shift_start = new DateTime($smena->shift_start);
        $shift_end = new DateTime($smena->shift_end);
        $shift_checkin = new DateTime($request->attendance_check_in_company);
        $sekundy = 1200; // 20 minut
        $difference_start = $shift_checkin->format('U') - ($shift_start->format('U') - $sekundy);
        $difference_end = $shift_end->format('U') - $shift_checkin->format('U');
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0){
            array_push($chybaDatumy,'Zapsaný check-in je dříve než začátek směny samotné!');
            $bool_datumy = 1;
        }
        if($difference_end < 0){
            array_push($chybaDatumy,'Zapsaný check-in je později než konec směny samotné!');
            $bool_datumy = 1;
        }
        if ($bool_datumy == 1) {
            return response()->json(['fail' => $chybaDatumy]);
        }
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        $company_check_in_date = new DateTime($request->attendance_check_in_company);
        $shift_start_date = new DateTime($smena->shift_start);
        if($dochazka->isEmpty()){
            if($company_check_in_date > $shift_start_date){
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_check_in_company' => $request->attendance_check_in_company, 'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_check_in_company' => $request->attendance_check_in_company, 'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }else{
            if($dochazka[0]->attendance_check_out_company != NULL){
                $shift_checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                $difference_checkins = $shift_checkout->format('U') - $shift_checkin->format('U');
                if($difference_checkins < 0){
                    array_push($chybaDatumy,'Zapsaný check-in je později než zapsaný check-out směny!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
                OlapETL::aggregateEmployeeTotalWorkedHours($shift_info_id, $zamestnanec->employee_id, $user->company_id, $request->attendance_check_in_company, $dochazka[0]->attendance_check_out_company, NULL, NULL);
            }
            if($company_check_in_date > $shift_start_date){
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => $request->attendance_check_in_company,'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => $request->attendance_check_in_company,'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }
        OlapETL::aggregateEmployeeAbsenceTotalHoursAndLateFlag($shift_info_id, $zamestnanec->employee_id, $user->company_id, $smena->shift_start, $request->attendance_check_in_company, NULL);
        OlapETL::extractAttendanceCheckInCompanyToShiftInfoDimension($shift_info_id, $request->attendance_check_in_company);
        return response()->json(['success'=>'Docházka příchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

    public function showCheckOut($zamestnanec_id,$smena_id){
        $html = '';
        date_default_timezone_set('Europe/Prague');
        if($zamestnanec_id == "undefined"){
            $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádného zaměstnance.</strong>
                        </div>';
            return response()->json(['html'=>$html]);
        }
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $smena = Shift::findOrFail($smena_id);
        if($dochazka->isEmpty()){
            $datumEnd = date('Y-m-d\TH:i', strtotime($smena->shift_end));
            $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
            $html .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumEnd.'"  autocomplete="attendance_create_checkout" autofocus>';
        }else{
            if($dochazka[0]->attendance_check_out_company == NULL){
                $datumEnd = date('Y-m-d\TH:i', strtotime($smena->shift_end));
                $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
                $html .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumEnd.'"  autocomplete="attendance_create_checkout" autofocus>';
            }else{
                $date_start = new DateTime($dochazka[0]->attendance_check_out_company);
                $datumZobrazeni = $date_start->format('d.m.Y H:i');
                $datumStart = date('Y-m-d\TH:i', strtotime($dochazka[0]->attendance_check_out_company));
                $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: '.$datumZobrazeni.'</strong>
                    </div>';
                $html .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumStart.'"  autocomplete="attendance_create_checkout   " autofocus>';
            }
        }
        return response()->json(['html'=>$html]);
    }

    public function updateCheckOut(Request $request,$zamestnanec_id,$smena_id){
        $user = Auth::user();
        $smena = Shift::findOrFail($smena_id);
        $shift_start = new DateTime($smena->shift_start);
        $shift_checkout = new DateTime($request->attendance_check_out_company);
        $sekundy = 0; // 0 minut
        $difference_start = $shift_checkout->format('U') - ($shift_start->format('U') - $sekundy);
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0){
            array_push($chybaDatumy,'Zapsaný check-out je dříve než začátek směny samotné!');
            $bool_datumy = 1;
        }
        if ($bool_datumy == 1) {
            return response()->json(['fail' => $chybaDatumy]);
        }
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){
            Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_check_out_company' => $request->attendance_check_out_company, 'attendance_came' => 1]);
        }else{
            if($dochazka[0]->attendance_check_in_company != NULL){
                $shift_checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                $difference_checkins = $shift_checkout->format('U') - $shift_checkin->format('U');
                if($difference_checkins < 0){
                    array_push($chybaDatumy,'Zapsaný check-out je dřívě než zapsaný check-in směny!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
                OlapETL::aggregateEmployeeTotalWorkedHours($shift_info_id, $zamestnanec->employee_id, $user->company_id, $dochazka[0]->attendance_check_in_company, $request->attendance_check_out_company, NULL, NULL);
            }
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_out_company' => $request->attendance_check_out_company,'attendance_came' => 1]);
        }
        OlapETL::extractAttendanceCheckOutCompanyToShiftInfoDimension($shift_info_id, $request->attendance_check_out_company);
        return response()->json(['success'=>'Docházka odchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

    public function showAbsence($zamestnanec_id,$smena_id){
        $html = '';
        if($zamestnanec_id == "undefined"){
            $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádného zaměstnance.</strong>
                        </div>';
            return response()->json(['html'=>$html]);
        }
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $duvody = AbsenceReason::getAllReasons();
        if($dochazka->isEmpty()){
            $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                      </div>';
        }else{
            if($dochazka[0]->absence_reason_id == NULL){
                $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
            }else{
                $duvod_absence = AbsenceReason::getEmployeeCurrentShiftAbsenceReason($zamestnanec_id, $smena_id);
                $html .= '<div class="alert alert-info alert-block text-center">
                             <strong>Aktuálně nastaveno na: '.$duvod_absence[0]->reason_description.'</strong>
                         </div>';
            }
        }
        $html .='<div class="form-group"><select name="duvody_absence" required id="duvody_absence" style="color:black" class="form-control input-lg dynamic duvody_absence" data-dependent="state">';
        if($dochazka->isEmpty()){
            foreach ($duvody as $duvod){
                $html .= '<option id="'.$duvod->reason_value.'" value="'.$duvod->reason_value.'">'.$duvod->reason_description.'</option>';
            }
        }else{
            if($dochazka[0]->absence_reason_id == NULL){
                foreach ($duvody as $duvod){
                    $html .= '<option id="'.$duvod->reason_value.'" value="'.$duvod->reason_value.'">'.$duvod->reason_description.'</option>';
                }
            }else{
                foreach ($duvody as $duvod){
                    if($duvod->reason_value == $dochazka[0]->absence_reason_id){
                        $html .= '<option id="'.$duvod->reason_value.'" value="'.$duvod->reason_value.'">'.$duvod->reason_description.'</option>';
                    }
                }
                foreach ($duvody as $duvod){
                    if($duvod->reason_value != $dochazka[0]->absence_reason_id){
                        $html .= '<option id="'.$duvod->reason_value.'" value="'.$duvod->reason_value.'">'.$duvod->reason_description.'</option>';
                    }
                }
            }
        }
        $html .= '</select></div>';
        return response()->json(['html'=>$html]);
    }

    public function updateAbsence(Request $request,$zamestnanec_id,$smena_id){
        $user = Auth::user();
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $bool = 0;
        $zamestnanec = Employee::findOrFail($zamestnanec_id);
        $smena = Shift::findOrFail($smena_id);
        if($request->attendance_absence_reason_id == 4 || $request->attendance_absence_reason_id == 5){
            $bool = 1;
        }
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){
            if($bool == 1){
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 1]);
            }else{
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 0]);
            }
        }else{
            if($request->attendance_absence_reason_id == 4 || $request->attendance_absence_reason_id == 5){
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 1]);
            }else{
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 0]);
            }
        }
        OlapETL::extractAbsenceReasonToShiftInfoDimension($shift_info_id, $request->attendance_absence_reason_id);
        OlapETL::extractAbsenceReasonToShiftFacts($shift_info_id, $zamestnanec_id, $user->company_id, $request->attendance_absence_reason_id);
        return response()->json(['success'=>'Status docházky zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byl úspěšně zapsán.']);
    }

    public function showAttendanceNote($zamestnanec_id,$smena_id){
        $html = '';
        if($zamestnanec_id == "undefined"){
            $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádného zaměstnance.</strong>
                       </div>';
            return response()->json(['html'=>$html]);
        }
        $zamestnanec = Employee::findOrFail($zamestnanec_id);
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        if($dochazka->isEmpty()){
            $html .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' ..." id="attendance_note" class="form-control" autocomplete="attendance_note"></textarea>';
        }else{
            if($dochazka[0]->attendance_note == NULL){
                $html .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' ..." id="attendance_note" class="form-control" autocomplete="attendance_note"></textarea>';
            }else{
                $html .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' ..." id="attendance_note" class="form-control" autocomplete="attendance_note">'.$dochazka[0]->attendance_note.'</textarea>';
            }
        }
        return response()->json(['html'=>$html]);
    }

    public function updateAttendanceNote(Request $request,$zamestnanec_id,$smena_id){
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        if($dochazka->isEmpty()){
            Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_note' => $request->attendance_note]);
        }else{
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_note' => $request->attendance_note]);
        }
        return response()->json(['success'=>'Poznámka k docházce zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }
}
