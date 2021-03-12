<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\CompanyDimension;
use App\Models\Disease;
use App\Models\Employee_Language;
use App\Models\Employee_Shift;
use App\Models\EmployeeDimension;
use App\Models\ImportancesShifts;
use App\Models\Injury;
use App\Models\Languages;
use App\Models\Shift;
use App\Models\ShiftFacts;
use App\Models\ShiftInfoDimension;
use App\Models\TimeDimension;
use App\Models\Vacation;
use Carbon\Carbon;
use DateTime;
use App\Http\Controllers\OlapETL;
use Google_Client;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Request;
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

class EmployeeDatatableController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->get();

        return view('company_actions.employee_list')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance);
    }


    public function getEmployees(Request $request){
        $user = Auth::user();
        if ($request->ajax()) {
            $data = Employee::where('employee_company',$user->company_id);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('shift_taken', function($data){
                    $res = DB::table('table_employee_shifts')
                        ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                        ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                        ->select('table_employee_shifts.employee_id')
                        ->where([ 'table_employees.employee_id' => $data->employee_id])
                        ->get();
                    if($res->isEmpty()){
                        return '<input type="checkbox" name="shift_taken" value="0" onclick="return false;">';
                    }else{
                        return '<input type="checkbox" name="shift_taken" value="1" onclick="return false;" checked>';
                    }
                })
                ->addColumn('action', function($data){
                    return '<button type="button" class="btn btn-primary btn-sm" id="getEditArticleData" data-toggle="modal"  data-target="#EditArticleModal" data-id="'.$data->employee_id.'"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                    <button type="button" data-id="'.$data->employee_id.'" data-toggle="modal" data-target="#RateEmployeeModal" class="btn btn-dark btn-sm" id="getEmployeeRate"><i class="fa fa-check-square" aria-hidden="true"></i> Hodnotit</button>
                    <button type="button" data-id="'.$data->employee_id.'" data-toggle="modal" style="margin-top:5px;color:white;" data-target="#AssignShiftModal" class="btn btn-info btn-sm" id="getEmployeeAssign"><i class="fa fa-exchange" aria-hidden="true"></i> &nbsp;Přiřadit</button>
                    <button type="button" data-id="'.$data->employee_id.'" data-toggle="modal" style="margin-top:5px;" data-target="#DeleteArticleModal" class="btn btn-danger btn-sm" id="getDeleteId"><i class="fa fa-trash-o" aria-hidden="true"></i> &nbsp;Smazat&nbsp;&nbsp;</button>
                    <button type="button" data-id="'.$data->employee_id.'" data-toggle="modal" style="margin-top:5px;" data-target="#ShowAttendanceOptionsModal" class="btn btn-success btn-sm" id="getShiftsOptions"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Docházka</button>
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_name' => ['required', 'string', 'max:255'],
            'employee_surname' =>  ['required', 'string', 'max:255'],
            'employee_position' =>  ['required', 'string', 'max:255'],
            'email' => ['required','unique:table_employees,email','string','email','max:255'],
            'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'employee_login' => ['required','unique:table_employees,employee_login', 'string', 'max:255'],
            'employee_note' => ['nullable','max:500'],
            'employee_city' => ['required','string', 'max:255'],
            'employee_street' => ['nullable','max:255'],
            'password' => ['required', 'string', 'min:8','required_with:password_confirm','same:password_confirm'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $user = Auth::user();
        $employee = new Employee();
        $employee->employee_name=$request->employee_name;
        $employee->employee_surname = $request->employee_surname;
        $employee->employee_phone = $request->employee_phone;
        $employee->email = $request->email;
        $employee->employee_note = $request->employee_note;
        $employee->employee_position = $request->employee_position;
        $employee->employee_city = $request->employee_city;
        $employee->employee_street = $request->employee_street;
        $employee->employee_login = $request->employee_login;
        $employee->password = Hash::make($request->password);
        $employee->employee_company = $user->company_id;
        $employee->save();

        $employeeSearch = Employee::where('employee_login', '=',$request->employee_login)->first();

        if($request->jazyky != ""){
            $params = explode('&', $request->jazyky);
            $name = "";
        }

        if($request->jazyky != "") {
            foreach ($params as $param) {
                $name_value = explode('=', $param);
                $name = $name_value[1];
                $employeeLanguage = new Employee_Language();
                $employeeLanguage->language_id = $name;
                $employeeLanguage->employee_id = $employeeSearch->employee_id;
                $employeeLanguage->save();
            }
        }

        /*Pozadovany nazev slozky v GoogleDrive, u nás jméno brigádníka*/
        $soubor = $request->employee_name.' '.$request->employee_surname;

        /*Cesta k autorizačnímu klíči*/
        $keyFileLocation =storage_path('app/credentials.json');
        /*ID složky, do které chceme soubory nahrávat*/
        $folderId = $user->company_url;
        $client = new Google_Client();
        $httpClient = $client->getHttpClient();
        $config = $httpClient->getConfig();
        $config['verify'] = false;
        $client->setHttpClient(new Client($config));
        $client->setApplicationName("BackupDrive");
        try {
            /*Inicializace klienta*/
            $client->setAuthConfig($keyFileLocation);
            $client->useApplicationDefaultCredentials();
            $client->addScope([
                \Google_Service_Drive::DRIVE,
                \Google_Service_Drive::DRIVE_METADATA
            ]);
            $service = new \Google_Service_Drive($client);
            /*Vytvoření složky*/
            $file = new Google_Service_Drive_DriveFile();
            $file->setName($soubor);
            $mimeType = 'application/vnd.google-apps.folder';
            $file->setMimeType($mimeType);
            /*Nasměrování do zvolené složky*/

            $file->setParents(array($folderId));

            /*Odeslání dat*/
            $createdFile = $service->files->create($file, array(
                'mimeType' => $mimeType,
                'uploadType' => "multipart"
            ));

            $fileId = $createdFile->id;

        /*  $role = 'writer';
            $userEmail = $request->email;

            $userPermission = new Google_Service_Drive_Permission(array(
               'type' => 'user',
               'role' => $role,
               'emailAddress' => $userEmail
             ));

            $request = $service->permissions->create(
                $fileId, $userPermission, array('fields' => 'id')
             ); */

        } catch (Exception $e) {
            file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
            die();
        }

        $employeeSearch->update(['employee_drive_url' => $fileId]);


        return response()->json(['success'=>'Zaměstnanec '.$request->employee_name.' '.$request->employee_surname.' byl úspešně vytvořen.']);
    }

    public function uploadImageEmployeeProfile(Request $request){
        if($request->hasFile('obrazek')){
            $validator = Validator::make($request->all(),[
                'obrazek' => 'required|mimes:jpg,jpeg,png|max:8096',
            ]);
            if($validator->fails()){
                session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg], maximální velikost obrázku je 8MB!');
                return redirect()->back();
            }
            $user = Employee::find($request->employee_id);
            if($user->employee_picture){
                Storage::delete('/public/employee_images/'.$user->employee_picture);
            }
            $tokenUnique = Str::random(20);
            $tokenUnique2 = Str::random(5);
            $tokenUnique3 = Str::random(10);
            $request->obrazek->storeAs('employee_images',$tokenUnique.$tokenUnique2.$tokenUnique3,'public');
            $user->update(['employee_picture' => $tokenUnique.$tokenUnique2.$tokenUnique3]);
            session()->flash('obrazekZpravaSuccess', 'Profilová fotka zaměstnance '.$user->employee_name.' '.$user->employee_surname.' úspěšně nahrána.');
        }
        return redirect()->back();
    }

    public function deleteOldImageEmployeeProfile(Request $request){
        $user = Employee::find($request->employee_id);
        if($user->employee_picture){
            Storage::delete('/public/employee_images/'.$user->company_picture);
            $user->update(['employee_picture' => NULL]);
        }
        session()->flash('obrazekZpravaSuccess', 'Profilová fotka zaměstnance '.$user->employee_name.' '.$user->employee_surname.' byla úspěšně smazána.');
        return redirect()->back();
    }

    public function edit($id){
        date_default_timezone_set('Europe/Prague');
        $employee = new Employee;
        $data = $employee->findData($id);
        $html = '';
        if($data->employee_picture === NULL){
            $html = '<center><img src=/images/default_profile.png width="300" /></center>';
        }else{
            $html = '<center><img src=/storage/employee_images/'.$data->employee_picture.' width="300" class="img-thumbnail"  /></center>';
        }

        $pocetDovolenych = Vacation::getEmployeeVacationsCount($id);
        $pocetZraneni = Injury::getEmployeeInjuriesInjuryCentreCount($id);
        $pocetNemocenskych = Disease::getEmployeeDiseasesCount($id);
        $pocetSmen = Shift::getEmployeeShiftsCount($id);
        $pocetBudoucichSmen = Shift::getEmployeeUpcomingShiftsCount($id);
        $pocetAbsenci = Attendance::getEmployeeAbsenceCount($id);
        $pocetSmenDochazka = Attendance::getEmployeeShiftsCount($id);
        $celkovyPocetHodinSmeny = Employee::getEmployeeTotalShiftsHour($id);
        $tydenniPocetHodin = Employee::getEmployeeWeekShiftsHour($id);
        $mesicniPocetHodin = Employee::getEmployeeMonthShiftsHour($id);
        $mesicniPocetOdpracovanychHodin = Employee::getEmployeeWorkedMonthShiftsHour($id);
        $tydenniPocetOdpracovanychHodin = Employee::getEmployeeWorkedWeekShiftsHour($id);
        $celkovyPocetOdpracovanychHodin = Employee::getEmployeeWorkedTotalShiftsHour($id);
        $skore = ($data->employee_reliability + $data->employee_absence + $data->employee_workindex) / 3;

        if($pocetSmenDochazka == 0){
            $pstPrichod = "zaměstnanec zatím nemá zaevidované žádné docházky";
        }else{
            $pstPrichod = (1 - ($pocetAbsenci)/($pocetSmenDochazka))*100;
            $pstPrichod = round($pstPrichod,2).'%';
        }

        $vysledek = DB::table('table_employee_languages')
                    ->select('table_employee_languages.language_name')
                    ->join('table_employee_table_languages','table_employee_table_languages.language_id','=','table_employee_languages.language_id')
                    ->where(['table_employee_table_languages.employee_id' => $data->employee_id])
                    ->get();
        $jazykySeznam = "";
        $text_jazyk = '';

        if(count($vysledek) == 0){
            $text_jazyk = 'žádné';
        }

        for($i = 0;$i < count($vysledek);$i++){
            if($i == count($vysledek) - 1){
                $jazykySeznam .= $vysledek[$i]->language_name.'.';
            }else{
                $jazykySeznam .= $vysledek[$i]->language_name.', ';
            }
        }

        $userFirma = Auth::user();

        $moznostiJazyk = DB::table('table_employee_languages')
            ->select('table_employee_languages.language_name', 'table_employee_languages.language_id')
            ->where(['table_employee_languages.company_id' => $userFirma->company_id])
            ->get();

        $vypisJazyku = "";

        $tabulka = '
                    <table class="table table-dark" id="show_table" style="font-size: 16px;">
                    <thead>
                        <tr>
                            <th scope="col" style="width:20%;text-align: center;">Začátek</th>
                            <th scope="col" style="width:20%;text-align: center;">Konec</th>
                            <th scope="col" style="width:10%;text-align: center;">Hodin</th>
                            <th scope="col" style="width:25%;text-align: center;">Lokace <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(2,this);"></i></th>
                            <th scope="col" style="width:10%;text-align: center;">Přišel/Přišla</th>
                            <th scope="col" style="width:10%;text-align: center;">Status</th>
                            <th scope="col" style="width:15%;text-align: center;">Odpracováno</th>
                        </tr>
                    </thead>
                    <tbody>';

        $smeny = Shift::getEmployeeShiftsWithEmployeeInformation($id);
        foreach ($smeny as $smena){
            $dochazka = DB::table('table_attendances')
                ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
                ->select('table_attendances.attendance_came','table_attendances.absence_reason_id','table_attendances.attendance_check_in'
                ,'table_attendances.attendance_check_out','table_attendances.attendance_check_in_company','table_attendances.attendance_check_out_company')
                ->where(['table_attendances.shift_id' => $smena->shift_id,'table_attendances.employee_id' => $id])
                ->get();

            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');

            $hodinyRozdil = $shift_end->diff($shift_start);
            $pocetHodin = $hodinyRozdil->h;
            $pocetMinut = $hodinyRozdil->i;

            if($dochazka->isEmpty()){
                $tabulka .= '<tr>
                                <td class="text-center">'.$smena->shift_start.'</td>
                                <td class="text-center"> '.$smena->shift_end.'</td>
                                <td class="text-center"> '.$pocetHodin.'h'.$pocetMinut.'m</td>
                                <td class="text-center"> '.$smena->shift_place.'</td>
                                <td class="text-center"><p style="color:yellow;">Nezapsáno</p></td>
                                <td class="text-center"><p style="color:yellow;">Neznámý</p></td>
                                <td class="text-center"><p style="color:yellow;">Nezapsaný check-in/out</p></td>';
            }else {
                $status = DB::table('table_absence_reasons')
                    ->select('table_absence_reasons.reason_description')
                    ->where(['table_absence_reasons.reason_id' => $dochazka[0]->absence_reason_id])
                    ->get();
                $statView = "";
                if ($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL) {
                    $statView = '<p style="color:yellow;">Neznámý</p>';
                } else {
                    if ($dochazka[0]->absence_reason_id == 5) {
                        $statView = '<p style="color:lightgreen;">' . $status[0]->reason_description . '</p>';
                    } else {
                        $statView = '<p style="color:orangered;">' . $status[0]->reason_description . '</p>';
                    }
                }
                $odpracovano = '';
                if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                        if($dochazka[0]->attendance_check_in == NULL || $dochazka[0]->attendance_check_out == NULL){
                            $odpracovano = '<p style="color:yellow;">Nezapsaný check-in/out</p>';
                        }else if($dochazka[0]->attendance_check_in != NULL || $dochazka[0]->attendance_check_out != NULL){
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
                                    <td class="text-center">'.$smena->shift_start.'</td>
                                    <td class="text-center"> '.$smena->shift_end.'</td>
                                    <td class="text-center"> '.$pocetHodin.'h'.$pocetMinut.'m</td>
                                    <td class="text-center"> '.$smena->shift_place.'</td>
                                    <td class="text-center"> <p style="color:orangered;">Ne</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>';
                }else{
                    $tabulka .= '<tr>
                                    <td class="text-center">'.$smena->shift_start.'</td>
                                    <td class="text-center"> '.$smena->shift_end.'</td>
                                    <td class="text-center"> '.$pocetHodin.'h'.$pocetMinut.'m</td>
                                    <td class="text-center"> '.$smena->shift_place.'</td>
                                    <td class="text-center"><p style="color:lightgreen;">Ano</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>';
                }
            }
        }

        $tabulka .= '</tbody></table>
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
                          td = tr[i].getElementsByTagName("td")[1];
                          td2 = tr[i].getElementsByTagName("td")[2];
                          td3 = tr[i].getElementsByTagName("td")[0];
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
                 </script>
';

        $delka_jazyk = count($moznostiJazyk);
        $text_seznam_jazyk = '';
        if($delka_jazyk == 0){
            $text_seznam_jazyk = '<div class="alert alert-danger" style="margin-top:15px;" role="alert">Žádné jazyky nebyly definovány, přidejte je v dashboardu, pomocí tlačítka přidat jazyk</div>';
        }

        for($i = 0;$i < count($moznostiJazyk);$i++){
            $bool = 0;
            if(count($vysledek) == 0){
                $vypisJazyku .=  '<input type="checkbox" class="form-check-input jazyky_edit" id="jazyky_edit" name="jazyky[]" value="'.$moznostiJazyk[$i]->language_id.'" >';
                $vypisJazyku.= '<label class="form-check-label" style="font-size: 17px;" for="jazyky_edit"> '.$moznostiJazyk[$i]->language_name.'</label><br>';
            }else{
                for($j = 0;$j < count($vysledek);$j++){
                    // $vypisJazyku .= ' '.$vysledek[$j]->language_name.' '.$moznostiJazyk[$i]->language_name.'';
                    if($vysledek[$j]->language_name == $moznostiJazyk[$i]->language_name){
                        $bool = 1;
                        $vypisJazyku .=  '<input type="checkbox" class="form-check-input jazyky_edit" id="jazyky_edit" name="jazyky[]" value="'.$moznostiJazyk[$i]->language_id.'" checked>';
                        $vypisJazyku.= '<label class="form-check-label" style="font-size: 17px;" for="jazyky_edit"> '.$moznostiJazyk[$i]->language_name.'</label><br>';
                    }

                }
                if($bool != 1){
                    $vypisJazyku .=  '<input type="checkbox" class="form-check-input jazyky_edit" id="jazyky_edit" name="jazyky[]" value="'.$moznostiJazyk[$i]->language_id.'" >';
                    $vypisJazyku.= '<label class="form-check-label" style="font-size: 17px;" for="jazyky_edit"> '.$moznostiJazyk[$i]->language_name.'</label><br>';
                }
            }
        }

        $spolehlivost = '';
        if($data->employee_reliability == NULL){
            $spolehlivost = 'nehodnoceno';
        }else{
            $spolehlivost = $data->employee_reliability.'b';
        }

        $absence_zamestnanec = '';
        if($data->employee_absence == NULL){
            $absence_zamestnanec = 'nehodnoceno';
        }else{
            $absence_zamestnanec = $data->employee_absence.'b';
        }

        $pracovitost = '';
        if($data->employee_workindex == NULL){
            $pracovitost = 'nehodnoceno';
        }else{
            $pracovitost = $data->employee_workindex.'b';
        }

        $celkove = '';
        if($data->employee_overall == NULL){
            $celkove = 'nedefinováno';
        }else{
            $celkove = round($data->employee_overall,2).'b';
        }

        $html .= '<center><div class="col-md-4">
                  <form method="post" class="text-center" style="margin-top: 15px;" action="/company/profile/uploadImageProfileEmployee" id="zamestnanec_form" enctype="multipart/form-data">
                       <input type="hidden" name="employee_id" value="'.$data->employee_id.'">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <div class="form-group nahratTlacitko">
                        <input type="file" name="obrazek" required id="file" hidden />
                        <label for="file" style="max-width: 450px;padding: 10px 12px;border:3px solid #4aa0e6;font-size:12px;background-color:#4aa0e6;border-radius: 48px;text-transform: uppercase;letter-spacing: 2px;font-weight: bold;color:white;" id="selector">Vyberte soubor</label>
                        <script>
                            var loader = function(e){
                                let file = e.target.files;
                                let show="<span> Vybrán soubor: </span>" + file[0].name;
                                let output = document.getElementById("selector");
                                output.innerHTML = show;
                                output.classList.add("active");
                            };
                            let fileInput = document.getElementById("file");
                            fileInput.addEventListener("change",loader);
                        </script>
                        <input class="btn btn-primary btn-block btn-md"  style="margin-top: 8px;font-size:16px;" type="submit" value="Nahrát">
                    </div>
                    </form>

                    <form method="post"  style="margin-top: 15px;" action="/company/profile/deleteOldImageProfileEmployee" id="zamestnanec_form" enctype="multipart/form-data">
                        <div class="form-group nahratTlacitko">
                             <input type="hidden" name="employee_id" value="'.$data->employee_id.'">
                             <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input class="btn btn-danger btn-block btn-md" type="submit" style="font-size:16px;" value="Smazat">
                        </div>
                    </form>
                    </div></center>
                    <ul class="nav nav-stacked nav-pills d-flex justify-content-center" style="margin-top:30px;font-size: 15px;" id="menuTabu">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#zmenaHesla" >Změna hesla</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#hodnoceni" >Hodnocení a statistiky</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#jazykyTab" >Jazyky</a>
                        </li>
                          <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#smeny" > Směny</a>
                        </li>
                    </ul>

                   <div id="my-tab-content" style="margin-top:20px;" class="tab-content">
                    <div class="tab-pane active" id="obecneUdaje">
                    <div class="form-group">
                        <label for="first_name" class="formularLabels"><i class="fa fa-user " aria-hidden="true"></i> Křestní jméno(<span class="text-danger">*</span>):</label>
                        <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" name="first_name" placeholder="Zadejte křestní jméno zaměstnance..." autocomplete="edit_first_name" id="edit_first_name" value="'.$data->employee_name.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="surname" class="formularLabels"><i class="fa fa-user " aria-hidden="true"></i> Příjmení(<span class="text-danger">*</span>):</label>
                         <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" placeholder="Zadejte příjmení zaměstnance..." autocomplete="edit_surname" name="surname" id="edit_surname" value="'.$data->employee_surname.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="formularLabels"><i class="fa fa-phone " aria-hidden="true"></i> Telefon(<span class="text-danger">*</span>):</label>
                         <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" placeholder="Zadejte telefonní číslo zaměstnance..." autocomplete="edit_phone_number" name="phone_number" id="edit_phone_number" value="'.$data->employee_phone.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="formularLabels"><i class="fa fa-envelope " aria-hidden="true"></i> Email(<span class="text-danger">*</span>):</label>
                        <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" placeholder="Zadejte email zaměstnance..." autocomplete="edit_email" name="phone_number" id="edit_email" value="'.$data->email.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="position" class="formularLabels"><i class="fa fa-child" aria-hidden="true"></i> Pozice(<span class="text-danger">*</span>):</label>
                         <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-child" aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" name="position" placeholder="Zadejte pozici zaměstnance..." autocomplete="edit_position" id="edit_position" value="'.$data->employee_position.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="city" class="formularLabels"><i class="fa fa-building-o" aria-hidden="true"></i> Město bydliště(<span class="text-danger">*</span>):</label>
                        <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" placeholder="Zadejte město bydliště zaměstnance..." autocomplete="edit_city" name="city" id="edit_city" value="'.$data->employee_city.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="street" class="formularLabels"><i class="fa fa-building-o" aria-hidden="true"></i> Ulice bydliště</label>
                        <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" placeholder="Zadejte ulici bydliště zaměstnance..." autocomplete="edit_street" name="street" id="edit_street" value="'.$data->employee_street.'">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="edit_login" class="formularLabels"><i class="fa fa-user " aria-hidden="true"></i> Login(<span class="text-danger">*</span>)</label>
                        <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                </div>
                        <input type="text" class="form-control formularInputs" placeholder="Zadejte login zaměstnance..." autocomplete="edit_login" name="login" id="edit_login" value="'.$data->employee_login.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_note" class="formularLabels"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Poznámka</label>
                         <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                </div>
                        <textarea name="note" placeholder="Zadejte poznámku k zaměstnanci..." id="edit_note" class="form-control formularInputs" autocomplete="edit_note">'.$data->employee_note.'</textarea>
                        </div>
                    </div>
                    <p class="d-flex justify-content-center">Účet vytvořen '.$data->created_at.', naposledy aktualizován '.$data->updated_at.'.</p>
                    </div>
                    <div class="tab-pane" id="zmenaHesla">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="button" class="btn btn-sm btn-warning pull-right" value="Generovat heslo" onClick="generator_edit();">
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="password_edit" class="formularLabels">Heslo:</label>
                         <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                </div>
                        <input type="password" class="form-control formularInputs" placeholder="Zadejte heslo zaměstnance..." autocomplete="password_edit" name="password_edit" id="password_edit">
                        </div>
                         <span toggle="#password_edit" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpassword_edit"></span>
                           <script>
                                $(".showpassword_edit").click(function() {
                                    $(this).toggleClass("fa-eye fa-eye-slash");
                                    var input = $($(this).attr("toggle"));
                                    if (input.attr("type") == "password") {
                                        input.attr("type", "text");
                                    } else {
                                        input.attr("type", "password");
                                    }
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="password_edit_confirm" class="formularLabels">Heslo znovu:</label>
                             <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                </div>
                            <input type="password" class="form-control formularInputs" name="password_confirm" placeholder="Zopakujte heslo zaměstnance..." autocomplete="password_edit_confirm" id="password_edit_confirm">
                            </div>
                              <span toggle="#password_edit_confirm" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpasswordverify_edit"></span>
                                <script>
                                    function generator_edit() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var password_tmp = "";
                                        for (var x = 0; x < 10; ++x) { password_tmp += znaky.charAt(Math.floor(Math.random()*znaky.length));}
                                        password_edit.value = password_tmp;
                                        password_edit_confirm.value = password_tmp;
                                    }

                                    $(".showpasswordverify_edit").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                        </div>
                    </div>
                    <div class="tab-pane" id="hodnoceni">
                    <center>
                         <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                             <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Spolehlivost: '.$spolehlivost.'</span>
                             <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Absence: '.$absence_zamestnanec.'</span>
                             <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Pracovitost: '.$pracovitost.'</span>
                         </div>
                          <div class="col-sm-4" style="background-color: #333333;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Celkové skóre: '.$celkove.'</div>
                          <div class="col-sm-4" style="background-color: #333333;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Pravděpodobnost příchodu: '.$pstPrichod.'</div>
                          <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                             <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Počet hodin za aktuální týden: '.$tydenniPocetHodin.'</span>
                             <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Počet hodin za aktuální měsíc  : '.$mesicniPocetHodin.'</span>
                          </div>
                           <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                             <span style="background-color: #333333;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Počet hodin celkově: '.$celkovyPocetHodinSmeny.'</span>
                          </div>
                          <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                             <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Počet odpracovaných hodin za aktuální týden: '.$tydenniPocetOdpracovanychHodin.'</span>
                             <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Počet odpracovaných hodin za aktuální měsíc  : '.$mesicniPocetOdpracovanychHodin.'</span>
                          </div>
                          <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                             <span style="background-color: #333333;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Počet odpracovaných hodin celkově: '.$celkovyPocetOdpracovanychHodin.'</span>
                          </div>
                           <div class="col-sm-12"></div>
                         <ul class="list-group col-md-5" style="margin-top:12px;margin-bottom: 15px;">
                            <li class="list-group-item list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet směn celkově</span> '.$pocetSmen.'</li>
                            <li class="list-group-item list-group-item-primary text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet směn s vyplněnou docházkou</span> '.$pocetSmenDochazka.'</li>
                            <li class="list-group-item list-group-item-primary text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet nadcházejících směn</span> '.$pocetBudoucichSmen.'</li>
                            <li class="list-group-item list-group-item-primary text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet nepříchodů celkově</span> '.$pocetAbsenci.'</li>
                            <li class="list-group-item list-group-item-primary text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Celkový počet zranění</span> '.$pocetZraneni .'</li>
                            <li class="list-group-item list-group-item-primary text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Celkový počet dovolených</span> '.$pocetDovolenych  .'</li>
                            <li class="list-group-item list-group-item-primary text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Celkový počet nemocenských</span> '.$pocetNemocenskych  .'</li>
                        </ul>
                         </center>
                     </div>
                     <div class="tab-pane" id="jazykyTab">
                       <div style="margin-top:15px;background-color: #2d995b;padding:10px 15px;border-radius: 10px; font-size: 16px;text-align: center;">Zaměstnanec ovládá tyto jazyky: '.$jazykySeznam.''.$text_jazyk.'</div>
                       <center>
                       <div style="margin-top:15px;font-size: 17px;">Změnit zaměstnanci jazyky:</div>
                       '.$text_seznam_jazyk.'
                       <div class="form-check text-center" style="color:white;margin-top:5px;padding-bottom:15px;">
                       '.$vypisJazyku.'
                       </div>
                       </center>
                     </div>
                      <div class="tab-pane" id="smeny">
                        <input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavac" onkeyup="Search()" placeholder="Hledat směnu na základě začátku, konce směny, lokace, statusu, nebo podle příchodu ..." title="Zadejte údaje o směně">
                        '.$tabulka.'
                    </div>
                  </div>
              </div>';
        return response()->json(['html'=>$html]);
    }

    public function editRate($id){
        $employee = new Employee;
        $data = $employee->findData($id);
        $html = '<div class="form-group text-center">
                    <label for="realibitySlider" style="font-size: 17px;">Spolehlivost:</label>
                   <input type="range" min="0" name="edit_realibility" max="5" value="'.$data->employee_reliability.'" class="slider" id="realibitySlider">
                    <div style="margin-top:8px;background-color: #2d995b;padding:1px 3px;border-radius: 10px;">
                        <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewRealibility"></span>b</p>
                    </div>
                </div>
                <div class="form-group text-center">
                   <label for="absenceSlider" style="font-size: 17px;">Dochvilnost:</label>
                   <input type="range" min="0" max="5" name="edit_absence" value="'.$data->employee_absence.'" class="slider" id="absenceSlider">
                   <div style="margin-top:8px;background-color: #2d995b;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewAbsence"></span>b</p>
                   </div>
                </div>

                <div class="form-group text-center">
                   <label for="workSlider" style="font-size: 17px;">Pracovitost:</label>
                   <input type="range" min="0" max="5" name="edit_workindex" value="'.$data->employee_workindex.'" class="slider" id="workSlider">
                   <div style="margin-top:8px;background-color: #2d995b;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewWork"></span>b</p>
                   </div>
                </div>';

        return response()->json(['html'=>$html]);
    }


    public function updateRate(Request $request, $id){

        $employee = new Employee;
        $vysledek = Employee::find($id);
        $jmeno = $vysledek->employee_name;
        $prijmeni = $vysledek->employee_surname;
        $skore = ($request->employee_reliability + $request->employee_absence + $request->employee_workindex) / 3;
        Employee::where('employee_id', $id)->update(array('employee_overall' => round($skore,2)));
        $employee->updateData($id, $request->all());

        return response()->json(['success'=>'Hodnocení zaměstnance '.$jmeno.' '.$prijmeni.' bylo úspěšně dokončeno.']);
    }


    public function update(Request $request, $id){
        $vysledek = Employee::find($id);
        if(($vysledek->email == $request->email) && ($vysledek->employee_login == $request->employee_login)){
            $validator = Validator::make($request->all(), [
                'employee_name' => ['required', 'string', 'max:255'],
                'employee_surname' =>  ['required', 'string', 'max:255'],
                'employee_position' =>  ['required', 'string', 'max:255'],
                'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'employee_note' => ['nullable','max:500'],
                'employee_city' => ['required','string', 'max:255'],
                'employee_street' => ['nullable','max:255'],
            ]);
        }else if(($vysledek->email != $request->email) && ($vysledek->employee_login == $request->employee_login)){
            $validator = Validator::make($request->all(), [
                'employee_name' => ['required', 'string', 'max:255'],
                'employee_surname' =>  ['required', 'string', 'max:255'],
                'employee_position' =>  ['required', 'string', 'max:255'],
                'email' => ['required','unique:table_employees,email','string','email','max:255'],
                'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'employee_note' => ['nullable','max:500'],
                'employee_city' => ['required','string', 'max:255'],
                'employee_street' => ['nullable','max:255'],
            ]);
        }else if(($vysledek->email == $request->email) && ($vysledek->employee_login != $request->employee_login)){
            $validator = Validator::make($request->all(), [
                'employee_name' => ['required', 'string', 'max:255'],
                'employee_surname' =>  ['required', 'string', 'max:255'],
                'employee_position' =>  ['required', 'string', 'max:255'],
                'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'employee_login' => ['required','unique:table_employees,employee_login', 'string', 'max:255'],
                'employee_note' => ['nullable','max:500'],
                'employee_city' => ['required','string', 'max:255'],
                'employee_street' => ['nullable','max:255'],
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'employee_name' => ['required', 'string', 'max:255'],
                'employee_surname' =>  ['required', 'string', 'max:255'],
                'employee_position' =>  ['required', 'string', 'max:255'],
                'email' => ['required','unique:table_employees,email','string','email','max:255'],
                'employee_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'employee_login' => ['required','unique:table_employees,employee_login', 'string', 'max:255'],
                'employee_note' => ['nullable','max:500'],
                'employee_city' => ['required','string', 'max:255'],
                'employee_street' => ['nullable','max:255'],
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if($vysledek->employee_drive_url != NULL){
            $jmeno = $request->employee_name;
            $prijmeni = $request->employee_surname;
            $souborZmenaEmployee = $jmeno . " " . $prijmeni;
            /*Cesta k autorizačnímu klíči*/
            $keyFileLocation =storage_path('app/credentials.json');
            $client = new Google_Client();
            $httpClient = $client->getHttpClient();
            $config = $httpClient->getConfig();
            $config['verify'] = false;
            $client->setHttpClient(new Client($config));
            $client->setApplicationName("BackupDrive");
            try {
                /*Inicializace klienta*/
                $client->setAuthConfig($keyFileLocation);
                $client->useApplicationDefaultCredentials();
                $client->addScope([
                    \Google_Service_Drive::DRIVE,
                    \Google_Service_Drive::DRIVE_METADATA
                ]);
                $service = new \Google_Service_Drive($client);
                $results = $service->files->get($vysledek->employee_drive_url);
                if($results != NULL){
                    $zmena_jmena = new Google_Service_Drive_DriveFile();
                    $zmena_jmena->setName($souborZmenaEmployee);
                    $service->files->update($vysledek->employee_drive_url, $zmena_jmena, array(
                        'mimeType' => 'text/csv',
                        'uploadType' => 'multipart'
                    ));
                }
            } catch (Exception $e) {
                print "Nastala chyba: " . $e->getMessage();
            }
        }

        $bool = 0;
        $bool2 = 0;
        $bool3 = 0;

        if($request->jazyky_edit != ""){
            $params = explode('&', $request->jazyky_edit);
        }

        $pocetZaznam = DB::table('table_employee_table_languages')
            ->select('table_employee_table_languages.language_employee_id')
            ->where(['table_employee_table_languages.employee_id' => $vysledek->employee_id])
            ->count();

        $count = 0;

        if($request->jazyky_edit != "") {

            $tmp_arr = array();

            foreach ($params as $param) {
                $name_value = explode('=', $param);
                $name = $name_value[1];
                array_push($tmp_arr,$name);
            }

            DB::table('table_employee_table_languages')
                ->select('table_employee_table_languages.language_employee_id')
                ->whereNotIn('language_id',$tmp_arr)
                ->where(['table_employee_table_languages.employee_id' => $vysledek->employee_id])
                ->delete();

            foreach ($params as $param) {
                $name_value = explode('=', $param);
                $name = $name_value[1];
                $pocet = DB::table('table_employee_table_languages')
                    ->select('table_employee_table_languages.language_employee_id')
                    ->where(['table_employee_table_languages.employee_id' => $vysledek->employee_id, 'table_employee_table_languages.language_id' => $name])
                    ->count();
                if ($pocet == 0) {
                    $employeeLanguage = new Employee_Language();
                    $employeeLanguage->language_id = $name;
                    $employeeLanguage->employee_id = $vysledek->employee_id;
                    $employeeLanguage->save();
                }
                $count++;
            }
        }

        if($count == 0){
            DB::table('table_employee_table_languages')
                ->select('table_employee_table_languages.language_employee_id')
                ->where(['table_employee_table_languages.employee_id' => $vysledek->employee_id])
                ->delete();
        }

        if(($vysledek->employee_name == $request->employee_name) && ($vysledek->employee_surname == $request->employee_surname)
            && ($vysledek->employee_phone == $request->employee_phone) && ($vysledek->email == $request->email) && ($vysledek->employee_note == $request->employee_note)
            && ($vysledek->employee_position == $request->employee_position) && ($vysledek->employee_city == $request->employee_city)
            && ($vysledek->employee_street == $request->employee_street) && ($vysledek->employee_login == $request->employee_login)){
            $bool = 0;
        }else{
            $bool = 1;
        }

        $vysledek->employee_name = $request->employee_name;
        $vysledek->employee_surname = $request->employee_surname;
        $vysledek->employee_phone = $request->employee_phone;
        $vysledek->email = $request->email;
        $vysledek->employee_note = $request->employee_note;
        $vysledek->employee_position = $request->employee_position;
        $vysledek->employee_city = $request->employee_city;
        $vysledek->employee_login = $request->employee_login;
        $vysledek->employee_street = $request->employee_street;

        if(isset($request->password)){
            $vysledek->password = Hash::make($request->password);
            $validator = Validator::make($request->all(), [
                'password' => ['string', 'min:8','required_with:password_repeat','same:password_repeat'],
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
            if($request->password != ""){
                $bool2 = 1;
            }

        }
        $vysledek->save();

        if($request->jazyky_edit != "") {
            if(count($params) != $pocetZaznam){
                $bool3 = 1;
            }
        }

        if($bool == 1 && $bool2 == 1 && $bool3 == 1){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->employee_name.' '.$request->employee_surname.', byly úspěšně změněny, včetně hesla a zaměstnancova nastavení jazyků.']);
        }
        else if($bool == 1 && $bool2 == 0 && $bool3 == 0){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->employee_name.' '.$request->employee_surname.' byly úspěšně změněny.']);
        }
        else if($bool == 0 && $bool2 == 1 && $bool3 == 0){
            return response()->json(['success'=>'Heslo zaměstnance '.$request->employee_name.' '.$request->employee_surname.' bylo úspěšně změněno.']);
        }
        else if($bool == 0 && $bool2 == 0 && $bool3 == 1){
            return response()->json(['success'=>'Jazykové dovednosti zaměstnance '.$request->employee_name.' '.$request->employee_surname.' byly úspěšně změněny.']);
        }
        else if($bool == 1 && $bool2 == 1 && $bool3 == 0){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->employee_name.' '.$request->employee_surname.', byly úspěšně změněny, včetně jeho hesla.']);
        }
        else if($bool == 0 && $bool2 == 1 && $bool3 == 1){
            return response()->json(['success'=>'Heslo zaměstnance '.$request->employee_name.' '.$request->employee_surname.', bylo úspěšně změněno, včetně nastavení jazyků zaměstnance.']);
        }
        else if($bool == 1 && $bool2 == 0 && $bool3 == 1){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->employee_name.' '.$request->employee_surname.', byly úspěšně změněny, včetně nastavení jazyků zaměstnance.']);
        }else{
            return response()->json(['success'=>'0']);
        }
    }

    public function assignShift($id){
        $user = Auth::user();
        $zamestnanec = Employee::findOrFail($id);
        $tydenniPocetHodin = Employee::getEmployeeWeekShiftsHour($id);
        $mesicniPocetHodin = Employee::getEmployeeMonthShiftsHour($id);
        $html = ' <div class="alert alert-warning" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <center>
                           Aktuální počet hodin na směnách zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.': <br>
                           '.$tydenniPocetHodin.' tento týden<br>
                           '.$mesicniPocetHodin.' tento měsíc
                       <center>
                    </div>';
        $html .= '
             <input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavac" onkeyup="Search()" placeholder="Hledat směnu na základě lokace, důležitosti, nebo data začátku, či konce ..." title="Zadejte údaje o směně">
             <table class="table table-dark" id="tableShifts" style="font-size: 16px;">
                    <thead>
                        <tr>
                            <th scope="col" style="width:19%;text-align: center;">Začátek</th>
                            <th scope="col" style="width:19%;text-align: center;">Konec</th>
                            <th scope="col" style="width:19%;text-align: center;">Lokace <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(2,this);"></i></th>
                            <th scope="col" style="width:19%;text-align: center;">Důležitost <i class="fa fa-sort-alpha-asc" style="margin-left: 5px" onclick="zmenaIkonky(this);sortTable(3,this);" ></i></th>
                            <th scope="col" style="width:19%;text-align: center;">Poznámka</th>
                            <th scope="col" style="width:5%;text-align: center;">Přiřazeno</th>
                        </tr>
                    </thead>
                <tbody>';
        $smeny = Shift::getUpcomingCompanyShifts($user->company_id);

        foreach ($smeny as $smena){
            $aktualniSmena = Employee_Shift::getEmployeeParticularShift($id, $smena->shift_id);
            $aktualniDulezitost = ImportancesShifts::getParticularImportance($smena->shift_importance_id);
            $shift_start = new DateTime( $smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $html .= '<tr><td class="text-center">'.$smena->shift_start.'</td><td class="text-center"> '.$smena->shift_end.'</td><td class="text-center"> '.$smena->shift_place.'</td> <td class="text-center"> '.$aktualniDulezitost[0]->importance_description.'</td> <td class="text-center"> '.$smena->shift_note.'</td>';
            if($aktualniSmena->isEmpty()){
                $html .= '<td><center><input type="checkbox" name="shift_shift_assign_id" class="form-check-input shift_shift_assign_id" id="shift_shift_assign_id" name="shift_shift_assign_id[]" value="'.$smena->shift_id.'"></center></td> </tr>';
            }else{
                $html .= '<td><center><input type="checkbox" name="shift_shift_assign_id" class="form-check-input shift_shift_assign_id" id="shift_shift_assign_id" name="shift_shift_assign_id[]" value="'.$smena->shift_id.'" checked></center></td> </tr>';
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
                      var input, filter, table, tr, td, td2, td3, td4, i, txtValue, txtValue2, txtValue3, txtValue4;
                      input = document.getElementById("vyhledavac");
                      filter = input.value.toUpperCase();
                      table = document.getElementById("tableShifts");
                      tr = table.getElementsByTagName("tr");
                      for (i = 0; i < tr.length; i++) {
                          td3 = tr[i].getElementsByTagName("td")[0];
                          td = tr[i].getElementsByTagName("td")[1];
                          td2 = tr[i].getElementsByTagName("td")[2];
                          td4 = tr[i].getElementsByTagName("td")[3];
                          if (td || td2 || td3 || td4) {
                              txtValue = td.textContent || td.innerText;
                              txtValue2 = td2.textContent || td2.innerText;
                              txtValue3 = td3.textContent || td3.innerText;
                              txtValue4 = td4.textContent || td4.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1
                              || txtValue3.toUpperCase().indexOf(filter) > -1 || txtValue4.toUpperCase().indexOf(filter) > -1) {
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

    public function updateassignShift(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        $employee = Employee::findOrFail($id);
        $smena_info = '';
        $count = 0;
        if($request->shifts_ids != "") {
            $shift_id_arr = explode('&', $request->shifts_ids);
            $delka = count($shift_id_arr);
            $shift_ids_collector = array();
            $shift_starts_collector = array();
            $shift_ends_collector = array();
            foreach ($shift_id_arr as $shift_id) {
                $shift_id_value = explode('=', $shift_id);
                array_push($shift_ids_collector,$shift_id_value[1]);
                $shift_tmp = Shift::findOrFail($shift_id_value[1]);
                array_push($shift_starts_collector,$shift_tmp->shift_start);
                array_push($shift_ends_collector,$shift_tmp->shift_end);
            }
            OlapETL::deleteCancelledPreviouslyAssignedShift($employee->employee_id, $shift_starts_collector, $shift_ends_collector);
            Employee_Shift::deleteEmployeeAssignedShiftsWithAttendance($id, $shift_ids_collector);
            foreach ($shift_id_arr as $shift_id) {
                $shift_id_value = explode('=', $shift_id);
                $concreteShift = Employee_Shift::getEmployeeParticularShift($id,$shift_id_value[1]);
                $shift = Shift::findOrFail($shift_id_value[1]);
                if ($count == $delka - 1) {
                    $smena_info .= "<br>".$shift->shift_start.' '.$shift->shift_end.', lokace: '.$shift->shift_place.".";
                }else{
                    $smena_info .= "<br>".$shift->shift_start.' '.$shift->shift_end.', lokace: '.$shift->shift_place.", ";
                }
                if($concreteShift->isEmpty()){
                    $user = Auth::user();
                    Employee_Shift::create(['shift_id' => $shift_id_value[1], 'employee_id' => $employee->employee_id]);
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
            OlapETL::deleteAllCancelledPreviouslyAssignedShift($employee->employee_id);
            Employee_Shift::deleteEmployeeAllUpcomingShiftsWithAttendance($id);
        }
        substr_replace($smena_info, ".", -1);
        if($smena_info == ""){
            return response()->json(['success'=>'Zaměstnancovi směny byly všechny úspěšně smazány.']);
        }
        if($count == 1){
            return response()->json(['success'=>'Zaměstnanci: '.$employee->employee_name.' '.$employee->employee_surname.' byla přidána následující směna: '.$smena_info]);
        }else{
            return response()->json(['success'=>'Zaměstnanci: '.$employee->employee_name.' '.$employee->employee_surname.' byly přidány následující směny: '.$smena_info]);
        }
    }

    public function destroy($id){
        $employee = new Employee;
        $vysledek = Employee::find($id);

        DB::table('table_employee_table_languages')
            ->select('table_employee_table_languages.language_employee_id')
            ->join('table_employee_languages','table_employee_languages.language_id','=','table_employee_table_languages.language_id')
            ->where(['table_employee_table_languages.employee_id' => $vysledek->employee_id])
            ->delete();

        if($vysledek->employee_drive_url != NULL){
            $keyFileLocation =storage_path('app/credentials.json');
            /*ID složky, do které chceme soubory nahrávat*/
            $client = new Google_Client();
            $httpClient = $client->getHttpClient();
            $config = $httpClient->getConfig();
            $config['verify'] = false;
            $client->setHttpClient(new Client($config));
            $client->setApplicationName("BackupDrive");
            try {
                /*Inicializace klienta*/
                $client->setAuthConfig($keyFileLocation);
                $client->useApplicationDefaultCredentials();
                $client->addScope([
                    \Google_Service_Drive::DRIVE,
                    \Google_Service_Drive::DRIVE_METADATA
                ]);
                $service = new \Google_Service_Drive($client);
                $results = $service->files->get($vysledek->employee_drive_url);
                if($results != NULL) {
                    $service->files->delete($vysledek->employee_drive_url);
                }
            }catch (Exception $e){
                file_put_contents("error.log", date("Y-m-d H:i:s") . ": " . $e->getMessage() . "\n\n", FILE_APPEND);
                die();
            }
        }

        $jmeno = $vysledek->employee_name;
        $prijmeni = $vysledek->employee_surname;
        $employee->deleteData($id);

        return response()->json(['success'=>'Zaměstnanec '.$jmeno.' '.$prijmeni.' byl úspěšně smazán.']);
    }

    public function getAttendanceOptions($id){
        $user = Auth::user();
        $html  = '';
        $smeny = DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_id')
            ->where(['table_employees.employee_id' => $id,'table_employees.employee_company' => $user->company_id])
            ->whereMonth('table_shifts.shift_start', Carbon::now()->month)
            ->orderBy('table_shifts.shift_start', 'desc')
            ->get();

        if(count($smeny) == 0){
            $html .= '<div class="alert alert-danger alert-block">
                            <strong>K zaměstnanci nejsou přiřazeny žádné směny</strong>
                        </div>';
        }else{
            $html .='<div class="form-group">
                            <select name="vybrana_smena" required id="vybrana_smena" style="color:black" class="form-control input-lg dynamic vybrana_smena" data-dependent="state">
                                 <option value="">Vyberte směnu</option>';
            foreach ($smeny as $smena){
                $date_start = new DateTime($smena->shift_start);
                $date_end = new DateTime($smena->shift_end);
                $datumZobrazeniStart = $date_start->format('d.m.Y H:i');
                $datumZobrazeniEnd = $date_end->format('d.m.Y H:i');
                $html .= '<option id="'.$smena->shift_id.'" value="'.$smena->shift_id.'">'.$datumZobrazeniStart.' - '.$datumZobrazeniEnd.'</option>';
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
        if($smena_id == "undefined"){
            $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádnou směnu.</strong>
                        </div>';
            return response()->json(['html'=>$html]);
        }
        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.attendance_check_in_company')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();
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
        date_default_timezone_set('Europe/Prague');
        $smena = Shift::findOrFail($smena_id);
        $shift_start = new DateTime($smena->shift_start);
        $shift_end = new DateTime($smena->shift_end);
        $shift_checkin = new DateTime($request->attendance_check_in_company);
        $sekundy = 900; // 15 minut
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

        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.attendance_check_in_company','table_attendances.attendance_check_out_company')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();

        $zamestnanec = Employee::find($zamestnanec_id);

        if($dochazka->isEmpty()){
            Attendance::create([
                'employee_id' => $zamestnanec_id,
                'shift_id' => $smena_id,
                'attendance_check_in_company' => $request->attendance_check_in_company,
                'attendance_came' => 1
            ]);
        }else{
            if($dochazka[0]->attendance_check_out_company != NULL){
                $shift_checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                $difference_checkins = $shift_checkout->format('U') - $shift_checkin->format('U');
                if($difference_checkins < 0){
                    array_push($chybaDatumy,'Zapsaný check-in je později než zapsaný check-out směny!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
            }
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(array('attendance_check_in_company' => $request->attendance_check_in_company,'attendance_came' => 1));
        }
        return response()->json(['success'=>'Docházka příchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }


    public function showCheckOut($zamestnanec_id,$smena_id){
        $html = '';
        date_default_timezone_set('Europe/Prague');
        $now = new DateTime();
        if($smena_id == "undefined"){
            $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádnou směnu.</strong>
                        </div>';
            return response()->json(['html'=>$html]);
        }

        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.attendance_check_out_company')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();

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
        date_default_timezone_set('Europe/Prague');
        $smena = Shift::findOrFail($smena_id);
        $shift_start = new DateTime($smena->shift_start);
        $shift_checkout = new DateTime($request->attendance_check_out_company);
        $sekundy = 900; // 15 minut
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

        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.attendance_check_out_company','table_attendances.attendance_check_in_company')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();

        $zamestnanec = Employee::find($zamestnanec_id);

        if($dochazka->isEmpty()){
            Attendance::create([
                'employee_id' => $zamestnanec_id,
                'shift_id' => $smena_id,
                'attendance_check_out_company' => $request->attendance_check_out_company,
                'attendance_came' => 1
            ]);
        }else{
            if($dochazka[0]->attendance_check_in_company != NULL){
                $shift_checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                $difference_checkins = $shift_checkout->format('U') - $shift_checkin->format('U');
                if($difference_checkins < 0){
                    array_push($chybaDatumy,'Zapsaný check-out je dřívě než zapsaný check-in směny!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
            }
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(array('attendance_check_out_company' => $request->attendance_check_out_company,'attendance_came' => 1));
        }

        return response()->json(['success'=>'Docházka odchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

    public function showAbsence($zamestnanec_id,$smena_id){
        $html = '';
        if($smena_id == "undefined"){
            $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádnou směnu.</strong>
                        </div>';
            return response()->json(['html'=>$html]);
        }
        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.absence_reason_id')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();

        $duvody = DB::table('table_absence_reasons')
            ->select('table_absence_reasons.reason_description','table_absence_reasons.reason_value')
            ->get();

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
                $hodnota = DB::table('table_attendances')
                    ->join('table_absence_reasons', 'table_attendances.absence_reason_id', '=', 'table_absence_reasons.reason_id')
                    ->select('table_absence_reasons.reason_description')
                    ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
                    ->get();
                $html .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: '.$hodnota[0]->reason_description.'</strong>
                    </div>';
            }
        }

        $html .='<div class="form-group">
                            <select name="duvody_absence" required id="duvody_absence" style="color:black" class="form-control input-lg dynamic duvody_absence" data-dependent="state">
                                ';
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
        $html .= ' </select></div>';
        return response()->json(['html'=>$html]);
    }

    public function updateAbsence(Request $request,$zamestnanec_id,$smena_id){
        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.absence_reason_id')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();
        $bool = 0;
        $zamestnanec = Employee::find($zamestnanec_id);
        if($request->attendance_absence_reason_id == 4 || $request->attendance_absence_reason_id == 5){
            $bool = 1;
        }
        if($dochazka->isEmpty()){
            if($bool == 1){
                Attendance::create([
                    'employee_id' => $zamestnanec_id,
                    'shift_id' => $smena_id,
                    'absence_reason_id' => $request->attendance_absence_reason_id,
                    'attendance_came' => 1
                ]);
            }else{
                Attendance::create([
                    'employee_id' => $zamestnanec_id,
                    'shift_id' => $smena_id,
                    'absence_reason_id' => $request->attendance_absence_reason_id,
                    'attendance_came' => 0
                ]);
            }
        }else{
            if($request->attendance_absence_reason_id == 5){
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(array('absence_reason_id' => $request->attendance_absence_reason_id,'attendance_came' => 1));
            }else{
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(array('absence_reason_id' => $request->attendance_absence_reason_id,'attendance_came' => 0));
            }
        }
        return response()->json(['success'=>'Status docházky zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byl úspěšně zapsán.']);
    }

    public function showAttendanceNote($zamestnanec_id,$smena_id){
        $html = '';
        if($smena_id == "undefined"){
            $html .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádnou směnu.</strong>
                        </div>';
            return response()->json(['html'=>$html]);
        }
        $zamestnanec = Employee::find($zamestnanec_id);
        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.attendance_note')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();

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
        $dochazka = DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.attendance_note')
            ->where(['table_attendances.shift_id' => $smena_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();
        $zamestnanec = Employee::find($zamestnanec_id);

        if($dochazka->isEmpty()){
            Attendance::create([
                'employee_id' => $zamestnanec_id,
                'shift_id' => $smena_id,
                'attendance_note' => $request->attendance_note,
            ]);
        }else{
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(array('attendance_note' => $request->attendance_note));
        }
        return response()->json(['success'=>'Poznámka docházky zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

}
