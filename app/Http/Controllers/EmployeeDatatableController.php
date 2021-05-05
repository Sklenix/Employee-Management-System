<?php

namespace App\Http\Controllers;

use App\Models\AbsenceReason;
use App\Models\Attendance;
use App\Models\Disease;
use App\Models\Employee_Language;
use App\Models\Employee_Shift;
use App\Models\ImportancesShifts;
use App\Models\Injury;
use App\Models\Languages;
use App\Models\Shift;
use App\Models\Vacation;
use DateTime;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Employee;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class EmployeeDatatableController extends Controller {
    /* Nazev souboru:  EmployeeDatatableController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k zobrazeni pohledu a take k zobrazeni datove tabulky v ramci spravy zamestnancu v uctu s roli firmy. Slouzi take k ovladani datove tabulky.
    Nazvy jednotlivych metod jsou konvenci frameworku laravel, viz https://laravel.com/docs/8.x/controllers
    Inspiraci k napojeni na Google Drive a prace s nim byl clanek https://www.kutac.cz/weby-a-vse-okolo/google-drive-api-nahravani-souboru-v-php, ktery napsal Pavel Kutac v roce 2019
    Odkaz na Google Drive API: https://developers.google.com/drive/api/v3/quickstart/php a jeji git: https://github.com/googleapis/google-api-php-client. Google Drive API knihovna je poskytovana pod licenci Apache License 2.0.
    Copyright pro Google Drive API:
        Copyright 2021 Google Drive API

        Licensed under the Apache License, Version 2.0 (the "License");
        you may not use this file except in compliance with the License.
        You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

        Unless required by applicable law or agreed to in writing, software
        distributed under the License is distributed on an "AS IS" BASIS,
        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
        See the License for the specific language governing permissions and
        limitations under the License.

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
      Ucel: zobrazeni prislusneho pohledu pro seznam zamestnancu */
    public function index(){
        $user = Auth::user();
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
         Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        return view('company_actions.employee_list')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance);
    }

    /* Nazev funkce: getEmployees
       Argumenty: zadne
       Ucel: zobrazeni seznamu zamestnancu v datove tabulce */
    public function getEmployees(){
        $user = Auth::user();
        $zamestnanci = Employee::where('employee_company',$user->company_id);
        /* Usek kodu slouzici k vyrenderovani datove tabulky */
        return Datatables::of($zamestnanci)
            ->addIndexColumn()
            ->addColumn('shift_taken', function($zamestnanci){ /* Pridani indikatoru, zdali zamestnanec obsadil smenu libovolnou smenu, ktera je bud dnes nebo kdykoliv v budoucnu */
                $obsadil = Employee_Shift::isShiftTakenFuture($zamestnanci->employee_id);
                if($obsadil->isEmpty()){
                    return '<input type="checkbox" name="shift_taken" value="0" onclick="return false;">';
                }else{
                    return '<input type="checkbox" name="shift_taken" value="1" onclick="return false;" checked>';
                }
            })
            ->addColumn('action', function($zamestnanci){
                return '<button type="button" data-id="'.$zamestnanci->employee_id.'" data-toggle="modal" data-target="#EmployeeEditForm" id="obtainEditEmployee" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Zobrazit</button>
                        <button type="button" data-id="'.$zamestnanci->employee_id.'" data-toggle="modal" data-target="#EmployeeRatingForm" id="obtainEmployeeRate" class="btn btn-dark btn-sm"><i class="fa fa-check-square" aria-hidden="true"></i> Hodnotit</button>
                        <button type="button" data-id="'.$zamestnanci->employee_id.'" data-toggle="modal" style="color:white;" data-target="#AssignShiftForm" id="obtainEmployeeAssign" class="btn btn-info btn-sm tlacitkoPriraditSeznamZamestnancu"><i class="fa fa-exchange" aria-hidden="true"></i> &nbsp;Přiřadit</button>
                        <button type="button" data-id="'.$zamestnanci->employee_id.'" data-toggle="modal" style="margin-top:5px;" data-target="#DeleteEmployeeForm" id="obtainDeleteEmployee" class="btn btn-danger btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i> &nbsp;Smazat&nbsp;&nbsp;</button>
                        <button type="button" data-id="'.$zamestnanci->employee_id.'" data-toggle="modal" style="margin-top:5px;" data-target="#AttendanceOptionsForm" id="obtainAttendanceOptions" class="btn btn-success btn-sm"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Docházka</button>';
            })
            ->rawColumns(['action','shift_taken'])
            ->make(true);
    }


    /* Nazev funkce: store
       Argumenty: request - udaje zadane firmou pro tvorbu noveho zamestnance
       Ucel: vytvoreni noveho zamestnance */
    public function store(Request $request) {
        $user = Auth::user();
        /* Definice pravidel pro validaci a jeji provedeni */
        $validator = Validator::make($request->all(), ['jmeno' => ['required', 'string', 'max:255'], 'prijmeni' =>  ['required', 'string', 'max:255'], 'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
            'pozice' =>  ['required', 'string', 'max:255'], 'email' => ['required','unique:table_employees,email','string','email','max:255'], 'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
            'prihlasovaci_jmeno' => ['required','unique:table_employees,employee_login', 'string', 'max:255'], 'poznamka' => ['nullable','max:180'], 'mesto_bydliste' => ['required','string', 'max:255'], 'ulice_bydliste' => ['nullable','max:255'],
            'heslo' => ['required', 'string', 'min:8','required_with:heslo_overeni','same:heslo_overeni'],
        ]);
        /* Overeni, zdali doslo k chybe */
        if ($validator->fails()) { return response()->json(['fail' => $validator->errors()->all()]); }

        /* Vytvoreni noveho zamestnance */
        Employee::create(['employee_name' => $request->jmeno, 'employee_surname' => $request->prijmeni, 'employee_birthday' => $request->narozeniny, 'employee_phone' => $request->telefon, 'email' => $request->email,
            'employee_note' => $request->poznamka, 'employee_position' => $request->pozice, 'employee_city' => $request->mesto_bydliste, 'employee_street' => $request->ulice_bydliste, 'employee_login' => $request->prihlasovaci_jmeno,
            'password' => Hash::make($request->heslo), 'employee_company' => $user->company_id, 'employee_url' => ""]);

        /* Nalezeni zamestnance podle jeho prihlasovaciho jmena */
        $employeeSearch = Employee::where('employee_login', '=',$request->prihlasovaci_jmeno)->first();

        /* priprava na parsovani jazyku z pozadavku */
        if($request->jazyky != ""){
            $id_jazyku = explode('&', $request->jazyky);
            $name = "";
        }

        /* Ziskani jmen jazyku a nasledne ulozeni do databaze */
        if($request->jazyky != "") {
            foreach ($id_jazyku as $id_jazyk) {
                $id_jaz = explode('=', $id_jazyk);
                $employeeLanguage = new Employee_Language();
                $employeeLanguage->language_id = $id_jaz[1];
                $employeeLanguage->employee_id = $employeeSearch->employee_id;
                $employeeLanguage->save();
            }
        }
        if($user->company_url != ""){ // pokud si firma aktivovala Google Drive
            /* Usek kodu zabyvajici se nazvem slozky v Google Drive zamestnance (v tomto systému jmeno a prijmeni zamestnance) */
            $soubor = $request->jmeno.' '.$request->prijmeni;
            /*Cesta k autorizacnimu klici*/
            $authKey = storage_path('app/credentials.json');
            /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
            $httpClient = new Client(['verify' => false]);
            /* Inicializace Google Drive klienta */
            $GoogleClient = new Google_Client();
            /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
            $GoogleClient->setHttpClient($httpClient);
            /* Nastaveni prihlaseni ke Google Drive API */
            $GoogleClient->setAuthConfig($authKey);
            /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
            $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
            $googleServ = new Google_Service_Drive($GoogleClient);
            /* Vytvoreni slozky */
            $nova_slozka = new Google_Service_Drive_DriveFile();
            $nova_slozka->setName($soubor);
            $nova_slozka->setMimeType('application/vnd.google-apps.folder');
            /*Nasměrování do zvolené složky*/
            $nova_slozka->setParents([$user->company_url]);
            /* Vytvoreni slozky na Google Drive */
            $createdFolder = $googleServ->files->create($nova_slozka, ['mimeType' => 'application/vnd.google-apps.folder', 'uploadType' => "multipart"]);
            $folderId = $createdFolder->id;
            /* Pokud chce firma nasdilet slozku zamestnanci */
            if($request->googleDriveRequest == "true"){
                $userPermission = new Google_Service_Drive_Permission(['type' => 'user', 'emailAddress' => $request->email, 'role' => 'writer']);
                $googleServ->permissions->create($folderId, $userPermission, ['emailMessage' => "Dobrý den, Vaše firma Vám nasdílela Vaši Google Drive složku."]);
                $employeeSearch->update(['employee_url' => $folderId]);
            }
        }

        /* Odeslani odpovedi uzivateli */
        return response()->json(['success'=>'Zaměstnanec '.$request->jmeno.' '.$request->prijmeni.' byl úspešně vytvořen.']);
    }

    /* Nazev funkce: uploadImageEmployeeProfile
       Argumenty: request - profilovy obrazek
       Ucel: nahrani profiloveho obrazku zamestnance */
    public function uploadImageEmployeeProfile(Request $request){
        if($request->hasFile('obrazek')){
            $validator = Validator::make($request->all(),['obrazek' => ['required','mimes:jpg,jpeg,png','max:8096']]);
            /* Pokud validace selze */
            if($validator->fails()){
                session()->flash('obrazekZpravaFail', 'Zadejte platný formát obrázku! [png, jpg, jpeg], maximální velikost obrázku je 8MB!');
                return redirect()->back();
            }
            /* Najiti zamestnance a nasledne smazani jeho minuleho profiloveho obrazku */
            $zamestnanec = Employee::find($request->employee_id);
            if($zamestnanec->employee_picture != NULL){
                Storage::delete('/public/employee_images/'.$zamestnanec->employee_picture);
            }
            /* Tvorba jmena obrazku */
            $tokenUnique = Str::random(20);
            $tokenUnique2 = Str::random(5);
            $tokenUnique3 = Str::random(10);
            /* Ulozeni obrazku */
            $request->obrazek->storeAs('employee_images',$tokenUnique.$tokenUnique2.$tokenUnique3, 'public');
            /* Aktualizace v databazi */
            $zamestnanec->update(['employee_picture' => $tokenUnique.$tokenUnique2.$tokenUnique3]);
            session()->flash('obrazekZpravaSuccess', 'Profilová fotka zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' úspěšně nahrána.');
        }
        return redirect()->back();
    }

    /* Nazev funkce: deleteOldImageEmployeeProfile
       Argumenty: request - profilovy obrazek
       Ucel: smazani profiloveho obrazku zamestnance */
    public function deleteOldImageEmployeeProfile(Request $request){
        /* Nalezeni zamestnance a nasledne smazani jeho profiloveho obrazku */
        $zamestnanec = Employee::find($request->employee_id);
        if($zamestnanec->employee_picture != NULL){
            Storage::delete('/public/employee_images/'.$zamestnanec->company_picture);
            $zamestnanec->update(['employee_picture' => NULL]);
        }
        session()->flash('obrazekZpravaSuccess', 'Profilová fotka zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně smazána.');
        return redirect()->back();
    }

    /* Nazev funkce: edit
       Argumenty: request - udaje zapsane firmou
       Ucel: editace profilu zamestnance */
    public function edit($id){
        date_default_timezone_set('Europe/Prague');
        $userFirma = Auth::user();
        $zamestnanec = Employee::find($id);
        $out = '';
        /* Zjisteni, zdali ma zamestnanec svou profilovou fotku, kdyz ne je pouzita defaultni */
        if($zamestnanec->employee_picture === NULL){
            /* Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4# */
            $out = '<center><img src=/images/default_profile.png width="300" /></center>';
        }else{
            $out = '<center><img src=/storage/employee_images/'.$zamestnanec->employee_picture.' width="300" class="img-thumbnail"  /></center>';
        }
        /* Ziskani statistik skrze modely */
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
        $shift_total_employee_late_hours = OlapAnalyzator::getTotalEmployeeLateShiftHours($id);
        $average_employee_score_by_time = OlapAnalyzator::getAverageEmployeeScore($id);
        $total_late_employee_flags_count = OlapAnalyzator::getTotalEmployeeLateFlagsCount($id);
        $shifts_employee_assigned_count_by_months = OlapAnalyzator::getCountOfEmployeeShiftFactsByMonths($id);
        $shift_total_employee_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsHoursByMonths($id);
        $shift_employee_total_worked_hours_by_months = OlapAnalyzator::getTotalEmployeeShiftsWorkedHoursByMonths($id);
        $shift_total_employee_late_hours_by_months = OlapAnalyzator::getTotalEmployeeLateShiftsHoursByMonths($id);
        $total_late_flags_count_employee_by_months = OlapAnalyzator::getTotalEmployeeLateFlagsCountByMonths($id);
        $employee_injuries_count_by_months = Injury::getEmployeeInjuriesByMonths($id);
        $average_employee_score_by_months = OlapAnalyzator::getAverageEmployeeScoreByMonths($id);
        /* Vypocitani celkoveho skore zamestnance*/
        $skore = ($zamestnanec->employee_reliability + $zamestnanec->employee_absence + $zamestnanec->employee_workindex) / 3;
        /* Vypocet pravdepodobnosti prichodu */
        if($pocetSmenDochazka == 0){
            $pstPrichod = "zaměstnanec zatím nemá zaevidované žádné docházky";
        }else{
            $pstPrichod = (1 - ($pocetAbsenci)/($pocetSmenDochazka))*100;
            $pstPrichod = round($pstPrichod,2).'%';
        }
        /* Ziskani jazyku zamestnance a ulozeni do promenne v HTML */
        $seznam_jazyku_zamestnance = Employee_Language::getEmployeeLanguages($zamestnanec->employee_id);
        $jazykySeznamView = "";
        $text_jazyk = '';
        if (count($seznam_jazyku_zamestnance) == 0){ $text_jazyk = 'žádné'; }
        for($i = 0;$i < count($seznam_jazyku_zamestnance);$i++){
            if ($i == count($seznam_jazyku_zamestnance) - 1) {
                $jazykySeznamView .= $seznam_jazyku_zamestnance[$i]->language_name.'.';
            }else{
                $jazykySeznamView .= $seznam_jazyku_zamestnance[$i]->language_name.', ';
            }
        }
        /* Ziskani jazyku nadefinovane firmou */
        $moznostiJazyk = Languages::getCompanyLanguages($userFirma->company_id);
        /* Promenna pro ulozeni firemnich jazyku */
        $vypisJazyku = "";
        $tabulka = '<table class="table table-dark" id="zamestnancovySmenySeznamZamestnancu" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th style="width:14%;text-align: center;">Začátek</th>
                                <th style="width:14%;text-align: center;">Konec</th>
                                <th style="width:14%;text-align: center;">Hodin</th>
                                <th style="width:14%;text-align: center;">Lokace</th>
                                <th style="width:14%;text-align: center;">Přišel/Přišla</th>
                                <th style="width:14%;text-align: center;">Status</th>
                                <th style="width:14%;text-align: center;">Odpracováno</th>
                            </tr>
                        </thead>
                        <tbody>';
        /* Ziskani smen zamestnance*/
        $smeny = Shift::getEmployeeShiftsWithEmployeeInformation($id);
        foreach ($smeny as $smena){
            /* Ziskani dochazky ke smene a preformatovani datumu a nasledny vypocet delky smeny */
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $id);
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $hodinyRozdil = $shift_end->diff($shift_start);
            $pocetHodin = $hodinyRozdil->h;
            $pocetMinut = $hodinyRozdil->i;
            if($dochazka->isEmpty()){ // pokud ke smene neexistuje dochazka
                $tabulka .= '<tr>
                                <td class="text-center">'.$smena->shift_start.'</td>
                                <td class="text-center"> '.$smena->shift_end.'</td>
                                <td class="text-center"> '.$pocetHodin.'h'.$pocetMinut.'m</td>
                                <td class="text-center"> '.$smena->shift_place.'</td>
                                <td class="text-center"><p style="color:yellow;">Nezapsáno</p></td>
                                <td class="text-center"><p style="color:yellow;">Neznámý</p></td>
                                <td class="text-center"><p style="color:yellow;">Nezapsaný příchod/odchod</p></td>
                              </tr>';
            }else { // pokud dochazka existuje
                $status = AbsenceReason::getParticularReason($dochazka[0]->absence_reason_id);
                $statView = "";
                /* Definice statusu dochazky */
                if ($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL) {
                    $statView = '<p style="color:yellow;">Neznámý</p>';
                } else {
                    if ($dochazka[0]->absence_reason_id == 5) {
                        $statView = '<p style="color:lightgreen;">' .$status[0]->reason_description. '</p>';
                    } else {
                        $statView = '<p style="color:orangered;">' .$status[0]->reason_description. '</p>';
                    }
                }
                /* Usek kodu slouzici k vypoctu odpracovynch hodin */
                $odpracovano = '';
                if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                        if($dochazka[0]->attendance_check_in == NULL || $dochazka[0]->attendance_check_out == NULL){
                            $odpracovano = '<p style="color:yellow;">Nezapsaný příchod/odchod</p>';
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
                /* Pokud zamestnanec neprisel na danou smenu */
                if($dochazka[0]->attendance_came == NULL || $dochazka[0]->attendance_came == 0){
                    $tabulka .= '<tr>
                                    <td class="text-center">'.$smena->shift_start.'</td>
                                    <td class="text-center"> '.$smena->shift_end.'</td>
                                    <td class="text-center"> '.$pocetHodin.'h'.$pocetMinut.'m</td>
                                    <td class="text-center"> '.$smena->shift_place.'</td>
                                    <td class="text-center"> <p style="color:orangered;">Ne</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>
                                  </tr>';
                }else{ // pokud prisel
                    $tabulka .= '<tr>
                                    <td class="text-center">'.$smena->shift_start.'</td>
                                    <td class="text-center"> '.$smena->shift_end.'</td>
                                    <td class="text-center"> '.$pocetHodin.'h'.$pocetMinut.'m</td>
                                    <td class="text-center"> '.$smena->shift_place.'</td>
                                    <td class="text-center"><p style="color:lightgreen;">Ano</p></td>
                                    <td class="text-center">'.$statView.'</td>
                                    <td class="text-center">'.$odpracovano.'</td>
                                 </tr>';
                }
            }
        }

        $tabulka .= '</tbody></table>';
        $tabulka .= '<script>
                           /* Implementace vyhledavace v ramci hledani smen */
                          $(document).ready(function(){
                              $("#vyhledavacSeznamZamestnancuSmeny").on("keyup", function() { // po zapsani znaku ve vyhledavani
                                var retezec = $("#vyhledavacSeznamZamestnancuSmeny").val(); // ziskani hodnoty ve vyhledavaci
                                var vysledek = retezec.toUpperCase(); // transformace hodnoty na velka pismena
                                var radkyTabulky = $("#zamestnancovySmenySeznamZamestnancu tr"); // ziskani radku tabulek
                                radkyTabulky.each(function () { // iterace skrze radky tabulky
                                    var bunka = $(this).find("td"); // ziskani hodnoty bunky
                                    bunka.each(function () { // iterace skrz bunky
                                        var obsahBunky = $(this).text(); // ulozeni hodnoty bunky
                                        if((obsahBunky.toUpperCase().includes(vysledek) == false) == false){ // kontrola zdali hledany retezec je podmnozinou nejake hodnoty v tabulce
                                              $(this).closest("tr").toggle(true); // radek je ponechan
                                              return false; // pokracovani dalsim radkem
                                        }else{
                                              $(this).closest("tr").toggle(false); // schovani radku tabulky, v ktere se nachazi aktualni bunka
                                              return true; // pokracovani dalsi bunkou radku
                                        }
                                    });
                                 });
                              });
                            });
                     </script>';
        /* Usek kodu starajici se o vypis vsech moznosti jazyku */
        $pocet_jazyku = count($moznostiJazyk);
        $text_seznam_jazyk = '';
        if ($pocet_jazyku == 0) {
            $text_seznam_jazyk = '<div class="alert alert-danger" style="margin-top:15px;" role="alert">Žádné jazyky nebyly definovány, přidejte je v dashboardu, pomocí tlačítka vytvořit jazyk</div>';
        }

        /* Iterace skrze firemni jazyky a naplnovani jednotlivych jazyku do checkboxu (pokud zamestnanec jazyk ovlada je checkbox automaticky vyplnen)*/
        for ($i = 0; $i < count($moznostiJazyk); $i++) {
            $jeZmena = 0;
            if(count($seznam_jazyku_zamestnance) == 0){
                $vypisJazyku .= '<input type="checkbox" class="form-check-input jazyky_edit" id="jazyky_edit" name="jazyky[]" value="'.$moznostiJazyk[$i]->language_id.'" >';
                $vypisJazyku.= '<label class="form-check-label" style="font-size: 17px;" for="jazyky_edit"> '.$moznostiJazyk[$i]->language_name.'</label><br>';
            }else{
                for ($j = 0; $j < count($seznam_jazyku_zamestnance); $j++) {
                    // $vypisJazyku .= ' '.$zamestnanec[$j]->language_name.' '.$moznostiJazyk[$i]->language_name.'';
                    if($seznam_jazyku_zamestnance[$j]->language_name == $moznostiJazyk[$i]->language_name){
                        $jeZmena = 1;
                        $vypisJazyku .=  '<input type="checkbox" class="form-check-input jazyky_edit" id="jazyky_edit" name="jazyky[]" value="'.$moznostiJazyk[$i]->language_id.'" checked>';
                        $vypisJazyku.= '<label class="form-check-label" style="font-size: 17px;" for="jazyky_edit"> '.$moznostiJazyk[$i]->language_name.'</label><br>';
                    }
                }
                if($jeZmena != 1){
                    $vypisJazyku .=  '<input type="checkbox" class="form-check-input jazyky_edit" id="jazyky_edit" name="jazyky[]" value="'.$moznostiJazyk[$i]->language_id.'" >';
                    $vypisJazyku.= '<label class="form-check-label" style="font-size: 17px;" for="jazyky_edit"> '.$moznostiJazyk[$i]->language_name.'</label><br>';
                }
            }
        }

        /* Usek kodu zabyvajici se ukladanim hodnoceni do promennych */
        $spolehlivost = '';
        if($zamestnanec->employee_reliability == NULL){
            $spolehlivost = 'nehodnoceno';
        }else{
            $spolehlivost = $zamestnanec->employee_reliability.'b';
        }
        $absence_zamestnanec = '';
        if($zamestnanec->employee_absence == NULL){
            $absence_zamestnanec = 'nehodnoceno';
        }else{
            $absence_zamestnanec = $zamestnanec->employee_absence.'b';
        }
        $pracovitost = '';
        if($zamestnanec->employee_workindex == NULL){
            $pracovitost = 'nehodnoceno';
        }else{
            $pracovitost = $zamestnanec->employee_workindex.'b';
        }
        $celkove = '';
        if($zamestnanec->employee_overall == NULL){
            $celkove = 'nedefinováno';
        }else{
            $celkove = round($zamestnanec->employee_overall,2).'b';
        }

        /* Definice obsahu, ktery bude odeslan do modalniho okna */
        $out .= '<center><div class="col-md-4"> <!-- Formular pro nahravani profiloveho obrazku --->
                  <form method="post" class="text-center" style="margin-top: 15px;" action="/company/profile/uploadImageProfileEmployee" enctype="multipart/form-data">
                        <input type="hidden" name="employee_id" value="'.$zamestnanec->employee_id.'">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <div class="form-group nahratTlacitko">
                            <input type="file" onchange="ziskatNazevSouboru()" name="obrazek" required id="souborProNahrani" hidden/>
                            <label for="souborProNahrani" style="max-width: 550px;padding: 11px 13px;font-size:12px;background-color:#4aa0e6;border-radius: 30px;text-transform: uppercase;letter-spacing: 2px;font-weight: bold;color:white;" id="zobrazeniNazvu">&nbsp;&nbsp;Vyberte soubor&nbsp;&nbsp;</label>
                            <script>
                                /* Funkce pro ziskani nazvu vybraneho souboru */
                                function ziskatNazevSouboru(){
                                    /* Po zmene vstupu pro soubory se ziska nazev souboru, diky tomu, ze lze nahravat pouze jeden soubor naraz staci ziskat nazev souboru na nultem indexu */
                                    document.getElementById("zobrazeniNazvu").innerHTML = "Vybrán soubor: " + event.target.files.item(0).name;
                                }
                            </script>
                        </div>
                        <input class="btn btn-primary btn-block btn-md" style="margin-top: 8px;font-size:16px;" type="submit" value="Nahrát">
                    </form> <!-- Formular pro odstraneni profiloveho obrazku --->
                    <form method="post"  style="margin-top: 15px;" action="/company/profile/deleteOldImageProfileEmployee" enctype="multipart/form-data">
                        <div class="form-group nahratTlacitko">
                             <input type="hidden" name="employee_id" value="'.$zamestnanec->employee_id.'">
                             <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input class="btn btn-danger btn-block btn-md" type="submit" style="font-size:16px;" value="Smazat">
                        </div>
                    </form>
                    </div></center> <!-- Definice tabu --->
                    <ul class="nav nav-stacked nav-pills d-flex justify-content-center" style="margin-top:30px;font-size: 15px;">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#zmenaHesla">Změna hesla</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#hodnoceni">Hodnocení a statistiky</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#jazykyTab">Jazyky</a>
                        </li>
                          <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#smeny"> Směny</a>
                        </li>
                    </ul>
                   <div style="margin-top:20px;" class="tab-content">
                        <div class="tab-pane active" id="obecneUdaje"> <!-- Obsah tabu obecne udaje --->
                            <div class="form-group">
                                <label for="edit_first_name" class="formularLabels">Křestní jméno (<span class="text-danger">*</span>):</label>
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control formularInputs" name="edit_first_name" placeholder="Zadejte křestní jméno zaměstnance..." autocomplete="on" id="edit_first_name" value="'.$zamestnanec->employee_name.'">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_surname" class="formularLabels">Příjmení (<span class="text-danger">*</span>):</label>
                                 <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control formularInputs" placeholder="Zadejte příjmení zaměstnance..." autocomplete="on" name="edit_surname" id="edit_surname" value="'.$zamestnanec->employee_surname.'">
                                </div>
                            </div>
                            <div class="form-group">
                                    <label for="employee_birthday_edit" class="formularLabels">Datum narození</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-birthday-cake" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="date" class="form-control formularInputs" name="employee_birthday_edit" value="'.$zamestnanec->employee_birthday.'" id="employee_birthday_edit">
                                    </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_phone_number" class="formularLabels">Telefonní číslo (<span class="text-danger">*</span>):</label>
                                 <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control formularInputs" placeholder="Zadejte telefonní číslo zaměstnance ve tvaru +420 XXX XXX XXX či XXX XXX XXX ..." autocomplete="on" name="edit_phone_number" id="edit_phone_number" value="'.$zamestnanec->employee_phone.'">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_email" class="formularLabels">Emailová adresa (<span class="text-danger">*</span>):</label>
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                        </div>
                                <input type="text" class="form-control formularInputs" placeholder="Zadejte emailovou adresu zaměstnance..." autocomplete="on" name="edit_email" id="edit_email" value="'.$zamestnanec->email.'">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_position" class="formularLabels">Pozice (<span class="text-danger">*</span>):</label>
                                 <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-child" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control formularInputs" name="edit_position" placeholder="Zadejte pozici zaměstnance..." autocomplete="on" id="edit_position" value="'.$zamestnanec->employee_position.'">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_city" class="formularLabels">Město bydliště (<span class="text-danger">*</span>):</label>
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control formularInputs" placeholder="Zadejte město bydliště zaměstnance..." autocomplete="on" name="edit_city" id="edit_city" value="'.$zamestnanec->employee_city.'">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_street" class="formularLabels">Ulice bydliště</label>
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control formularInputs" placeholder="Zadejte ulici bydliště zaměstnance..." autocomplete="on" name="edit_street" id="edit_street" value="'.$zamestnanec->employee_street.'">
                                </div>
                            </div>
                             <div class="form-group">
                                <label for="edit_login" class="formularLabels">Uživatelské jméno (<span class="text-danger">*</span>)</label>
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="text" class="form-control formularInputs" placeholder="Zadejte uživatelské jméno zaměstnance..." autocomplete="on" name="edit_login" id="edit_login" value="'.$zamestnanec->employee_login.'">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_note" class="formularLabels">Poznámka</label>
                                 <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                        </div>
                                        <textarea name="edit_note" placeholder="Zadejte poznámku k zaměstnanci [maximálně 180 znaků]..." id="edit_note" class="form-control formularInputs" autocomplete="on">'.$zamestnanec->employee_note.'</textarea>
                                </div>
                            </div>
                            <p class="d-flex justify-content-center">Účet vytvořen '.$zamestnanec->created_at.', naposledy aktualizován '.$zamestnanec->updated_at.'.</p>
                        </div>
                        <div class="tab-pane" id="zmenaHesla"> <!-- Obsah tabu pro zmenu hesla --->
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
                            <input type="password" class="form-control formularInputs" placeholder="Zadejte nové heslo zaměstnance..." name="password_edit" id="password_edit">
                            </div>
                             <span toggle="#password_edit" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHesloEdit"></span>
                               <script>
                                    /* Skryti/odkryti hesla */
                                    $(".zobrazHesloEdit").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                   /* Tento jquery kod spolecne s elementem span (ktery byl upraven) byl prevzat dle licencnich podminek, viz nize.
                                        Copyright (c) 2021 - Sohail Aj. - www.codepen.io/Sohail05/pen/yOpeBm

                                        Permission is hereby granted, free of charge, to any person
                                        obtaining a copy of this software and associated documentation
                                        files (the "Software"), to deal in the Software without restriction,
                                         including without limitation the rights to use, copy, modify,
                                        merge, publish, distribute, sublicense, and/or sell copies of
                                        the Software, and to permit persons to whom the Software is
                                        furnished to do so, subject to the following conditions:

                                        The above copyright notice and this permission notice shall
                                        be included in all copies or substantial portions of the Software.

                                        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
                                        EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
                                        OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
                                        NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
                                        HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
                                        WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
                                        OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
                                        DEALINGS IN THE SOFTWARE.
                                        */
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="password_edit_confirm" class="formularLabels">Heslo znovu:</label>
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                <input type="password" class="form-control formularInputs" name="password_edit_confirm" placeholder="Zopakujte nové heslo zaměstnance..." id="password_edit_confirm">
                                </div>
                                  <span toggle="#password_edit_confirm" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHesloOvereniEdit"></span>
                                    <script>
                                        /* Funkce pro vygenerovani hesla pro zmenu hesla zamestnance */
                                        function generator_edit() {
                                              var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                              var heslo = "";
                                              var i = 0;
                                              while(i < 10){
                                                    heslo += znaky.charAt(Math.floor(Math.random()*znaky.length));
                                                    i++;
                                              }
                                              document.getElementById("password_edit").value = heslo;
                                              document.getElementById("password_edit_confirm").value = heslo;
                                         }

                                         /* Skryti/odkryti hesla */
                                        $(".zobrazHesloOvereniEdit").click(function() {
                                            $(this).toggleClass("fa-eye fa-eye-slash");
                                            var input = $($(this).attr("toggle"));
                                            if (input.attr("type") == "password") {
                                                input.attr("type", "text");
                                            } else {
                                                input.attr("type", "password");
                                            }
                                        });
                                       /* Tento jquery kod spolecne s elementem span (ktery byl upraven) byl prevzat dle licencnich podminek, viz nize.
                                        Copyright (c) 2021 - Sohail Aj. - www.codepen.io/Sohail05/pen/yOpeBm

                                        Permission is hereby granted, free of charge, to any person
                                        obtaining a copy of this software and associated documentation
                                        files (the "Software"), to deal in the Software without restriction,
                                         including without limitation the rights to use, copy, modify,
                                        merge, publish, distribute, sublicense, and/or sell copies of
                                        the Software, and to permit persons to whom the Software is
                                        furnished to do so, subject to the following conditions:

                                        The above copyright notice and this permission notice shall
                                        be included in all copies or substantial portions of the Software.

                                        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
                                        EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
                                        OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
                                        NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
                                        HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
                                        WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
                                        OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
                                        DEALINGS IN THE SOFTWARE.
                                        */
                                    </script>
                            </div>
                        </div> <!-- Obsah tabu pro hodnoceni --->
                        <div class="tab-pane" id="hodnoceni">
                             <center>
                             <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                                 <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Spolehlivost: '.$spolehlivost.'</span>
                                 <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Dochvilnost: '.$absence_zamestnanec.'</span>
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
                                <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                                 <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Celkové zpoždění ze všech směn: '.$shift_total_employee_late_hours.'h</span>
                                 <span style="background-color: #d9534f;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Počet zpoždění ze všech směn : '.$total_late_employee_flags_count.'x</span>
                              </div>
                               <div class="col-sm-12" style="margin-top:35px;margin-bottom: 25px;">
                                 <span style="background-color: #333333;margin-top:15px;padding:14px 20px;font-size: 16px;border-radius: 10px;">Průměrné skóre zaměstnance ze všech jeho směn: '.$average_employee_score_by_time.'b</span>
                              </div>
                               <div class="col-sm-12"></div>
                             <ul class="list-group col-md-5" style="margin-top:12px;margin-bottom: 15px;">
                                <li class="list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet směn celkově</span> '.$pocetSmen.'</li>
                                <li class="list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet směn s vyplněnou docházkou</span> '.$pocetSmenDochazka.'</li>
                                <li class="list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet nadcházejících směn</span> '.$pocetBudoucichSmen.'</li>
                                <li class="list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Počet nepříchodů celkově</span> '.$pocetAbsenci.'</li>
                                <li class="list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Celkový počet zranění</span> '.$pocetZraneni .'</li>
                                <li class="list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Celkový počet dovolených</span> '.$pocetDovolenych  .'</li>
                                <li class="list-group-item text-right" style="color:white;background-color: #333;font-size: 16px;"><span class="pull-left">Celkový počet nemocenských</span> '.$pocetNemocenskych  .'</li>
                            </ul> <!-- Sekce pro vykreslovani grafu --->
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-doughnutlabel/2.0.3/chartjs-plugin-doughnutlabel.js"></script>
                            <div class="row justify-content-center" style="margin-bottom: 60px;">
                                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                                    &nbsp;<canvas id="barChartShiftsAssigned"></canvas>
                                </div>
                                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                                    &nbsp;<canvas id="barChartShiftsTotalHours"></canvas>
                                </div>
                            </div>
                             <div class="row justify-content-center" style="margin-bottom: 60px;">
                                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                                    &nbsp;<canvas id="barChartShiftsTotalWorkedHours"></canvas>
                                </div>
                                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                                    &nbsp;<canvas id="barChartShiftsTotalLateHours"></canvas>
                                </div>
                             </div>
                             <div class="row justify-content-center" style="margin-bottom: 60px;">
                                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                                    &nbsp;<canvas id="barChartShiftsTotalLateFlagsCount"></canvas>
                                </div>

                                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                                    &nbsp;<canvas id="barChartShiftsTotalInjuriesFlagsCount"></canvas>
                                </div>
                             </div>
                             <div class="row justify-content-center" style="margin-bottom: 60px;">
                                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                                    &nbsp;<canvas id="barChartAverageEmployeesScoreByTime"></canvas>
                                </div>
                            </div>
                            <script>
                            /* Deklarace promennych pro jednotlive grafy */
                            var barChartShiftsAssigned;
                            var barChartShiftsAssignedCanvas;
                            var barChartShiftsTotalHours;
                            var barChartShiftsTotalHoursCanvas;
                            var barChartShiftsTotalWorkedHours;
                            var barChartShiftsTotalWorkedHoursCanvas;
                            var barChartShiftsTotalLateHours;
                            var barChartShiftsTotalLateHoursCanvas;

                            var barChartShiftsTotalLateFlagsCount;
                            var barChartShiftsTotalLateFlagsCountCanvas;
                            var barChartShiftsTotalInjuriesFlagsCount;
                            var barChartShiftsTotalInjuriesFlagsCountCanvas;
                            var barChartAverageEmployeesScoreByTime;
                            var barChartAverageEmployeesScoreByTimeCanvas;
                            /* Funkce pro renderovani sloupcoveho grafu */
                            function renderBarGraph(data_values, title, label_value, element, canvas_element, element_id){
                                                   canvas_element = $(element_id);
                                                   element = new Chart(canvas_element, {
                                                       type:"bar",
                                                       data:{
                                                           labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                                                           datasets:[
                                                               {
                                                                   label: label_value,
                                                                   data: data_values,
                                                                   backgroundColor: ["#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f"]
                                                               }
                                                           ]
                                                       },
                                                       options: {
                                                           responsive: true,
                                                           maintainAspectRatio: false,
                                                           title: {
                                                               display: true,
                                                               fontColor: "white",
                                                               text: title,
                                                               fontSize: 20,
                                                               position: "top",
                                                               padding: 25,
                                                               fontStyle:"normal"
                                                           },
                                                           legend: {
                                                               display: false,
                                                           },
                                                           scales: {
                                                               xAxes: [{
                                                                   ticks: {
                                                                       fontColor: "white",
                                                                   },
                                                                   gridLines: {
                                                                       display:false,
                                                                   },
                                                               }],
                                                               yAxes: [{
                                                                   ticks: {
                                                                       display:false,
                                                                       beginAtZero: true,
                                                                       precision: 0,
                                                                   },
                                                                     gridLines: {
                                                                       display:false,
                                                                       color: "white",
                                                                       zeroLineColor: "white"
                                                                   },
                                                               }]
                                                           },
                                                           plugins: {
                                                               datalabels: {
                                                                   color: "white",
                                                                   align: "top",
                                                                   font: {
                                                                       weight: "bold",
                                                                       size:16
                                                                   },
                                                               }
                                                           }
                                                       }
                                                   })
                                               }
                                           /* Ulozeni dat do promennych a zavolani funkce pro vykresleni */
                                           var data_assigned_shifts_by_months = '.json_encode($shifts_employee_assigned_count_by_months).';
                                           renderBarGraph(data_assigned_shifts_by_months,"Počet směn dle měsíců","Počet směn dle měsíců",barChartShiftsAssigned, barChartShiftsAssignedCanvas, "#barChartShiftsAssigned");
                                           var data_total_hours_shifts_by_months = '.json_encode($shift_total_employee_hours_by_months).';
                                           renderBarGraph(data_total_hours_shifts_by_months,"Celkový počet hodin směn dle měsíců", "Celkový počet hodin směn dle měsíců", barChartShiftsTotalHours, barChartShiftsTotalHoursCanvas, "#barChartShiftsTotalHours");
                                           var data_total_worked_hours_by_months = '.json_encode($shift_employee_total_worked_hours_by_months).';
                                           renderBarGraph(data_total_worked_hours_by_months,"Počet celkově odpracovaných hodin na směnách", "Počet celkově odpracovaných hodin na směnách", barChartShiftsTotalWorkedHours, barChartShiftsTotalWorkedHoursCanvas, "#barChartShiftsTotalWorkedHours");
                                           var data_total_late_hours_by_months = '.json_encode($shift_total_employee_late_hours_by_months).';
                                           renderBarGraph(data_total_late_hours_by_months,"Počet celkových hodin zpoždění", "Počet celkových hodin zpoždění", barChartShiftsTotalLateHours, barChartShiftsTotalLateHoursCanvas, "#barChartShiftsTotalLateHours");
                                           var data_total_late_flags_count_by_months = '.json_encode($total_late_flags_count_employee_by_months).';
                                           renderBarGraph(data_total_late_flags_count_by_months, "Počet zpoždění dle měsíců", "Počet zpoždění dle měsíců", barChartShiftsTotalLateFlagsCount, barChartShiftsTotalLateFlagsCountCanvas, "#barChartShiftsTotalLateFlagsCount");
                                           var data_total_injury_flags_count_by_months = '.json_encode($employee_injuries_count_by_months).';
                                           renderBarGraph(data_total_injury_flags_count_by_months,"Počet zranění na směnách dle měsíců", "Počet zranění na směnách dle měsíců", barChartShiftsTotalInjuriesFlagsCount, barChartShiftsTotalInjuriesFlagsCountCanvas, "#barChartShiftsTotalInjuriesFlagsCount");
                                           var data_average_employee_score_by_months = '.json_encode($average_employee_score_by_months).';
                                           renderBarGraph(data_average_employee_score_by_months,"Vývoj průměrného skóre zaměstnance v čase", "Vývoj průměrného skóre zaměstnance v čase", barChartAverageEmployeesScoreByTime, barChartAverageEmployeesScoreByTimeCanvas, "#barChartAverageEmployeesScoreByTime");
                             </script>
                             </center>
                         </div> <!-- Obsah tabu pro jazyky --->
                         <div class="tab-pane" id="jazykyTab">
                               <div style="margin-top:15px;background-color: #2d995b;padding:10px 15px;border-radius: 10px; font-size: 16px;text-align: center;">Zaměstnanec ovládá tyto jazyky: '.$jazykySeznamView.''.$text_jazyk.'</div>
                               <center>
                               <div style="margin-top:15px;font-size: 17px;">Změnit zaměstnanci jazyky:</div>
                               '.$text_seznam_jazyk.'
                               <div class="form-check text-center" style="color:white;margin-top:5px;padding-bottom:15px;">
                               '.$vypisJazyku.'
                               </div>
                               </center>
                             </div> <!-- Obsah tabu pro historii smen --->
                              <div class="tab-pane" id="smeny">
                                <input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavacSeznamZamestnancuSmeny" placeholder="Hledat směnu na základě jejího začátku, konce či lokace ...">
                                '.$tabulka.'
                            </div>
                      </div>
              </div>';
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: editRate
       Argumenty: id - jednoznacny identifikator zamestnance
       Ucel: Definice zobrazeni hodnoceni zamestnance */
    public function editRate($id){
        $zamestnanec = Employee::find($id);
        /* Definice obsahu */
        $out = '<div class="form-group text-center">
                    <label for="realibitySlider" style="font-size: 17px;">Spolehlivost:</label>
                    <input type="range" min="0" name="set_realibility" max="5" value="'.$zamestnanec->employee_reliability.'" style="height: 1.5vh;width:100% !important;-webkit-appearance: none;" class="posuvnik" id="realibitySlider">
                    <div style="margin-top:8px;background-color: #4682B4;padding:1px 3px;border-radius: 10px;">
                        <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewRealibility"></span>b</p>
                    </div>
                </div>
                <div class="form-group text-center">
                   <label for="absenceSlider" style="font-size: 17px;">Dochvilnost:</label>
                   <input type="range" min="0" max="5" name="set_absence" value="'.$zamestnanec->employee_absence.'" style="height: 1.5vh;width:100% !important;-webkit-appearance: none;" class="posuvnik" id="absenceSlider">
                   <div style="margin-top:8px;background-color: #4682B4;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewAbsence"></span>b</p>
                   </div>
                </div>
                <div class="form-group text-center">
                   <label for="workSlider" style="font-size: 17px;">Pracovitost:</label>
                   <input type="range" min="0" max="5" name="set_workindex" value="'.$zamestnanec->employee_workindex.'" style="height: 1.5vh;width:100% !important;-webkit-appearance: none;" class="posuvnik" id="workSlider">
                   <div style="margin-top:8px;background-color: #4682B4;padding:1px 3px;border-radius: 10px;">
                       <p style="margin-top:15px;font-size: 16px;">Hodnota: <span id="viewWork"></span>b</p>
                   </div>
                </div>';
        return response()->json(['out'=>$out]);
    }

    /* Nazev funkce: updateRate
       Argumenty: id - jednoznacny identifikator zamestnance, request - zadane hodnoty hodnoceni
       Ucel: Aktualizace hodnoceni zamestnance */
    public function updateRate(Request $request, $id){
        $zamestnanec = Employee::find($id);
        $jmeno = $zamestnanec->employee_name;
        $prijmeni = $zamestnanec->employee_surname;
        $skore = ($request->employee_reliability + $request->employee_absence + $request->employee_workindex) / 3;
        Employee::where('employee_id', $id)->update(['employee_overall' => round($skore,2), 'employee_reliability' => $request->employee_reliability, 'employee_absence' => $request->employee_absence, 'employee_workindex' => $request->employee_workindex]);
        return response()->json(['success'=>'Hodnocení zaměstnance '.$jmeno.' '.$prijmeni.' bylo úspěšně dokončeno.']);
    }

    /* Nazev funkce: update
       Argumenty: id - jednoznacny identifikator zamestnance, request - zadane hodnoty ve formulari profilu zamestnance
       Ucel: Aktualizace udaju zamestnance */
    public function update(Request $request, $id){
        $user = Auth::user();
        $zamestnanec = Employee::find($id);
        $idZamestnance = $zamestnanec->employee_id;
        /* Definice pravidel pro validaci */
        if(($zamestnanec->email == $request->email) && ($zamestnanec->employee_login == $request->prihlasovaci_jmeno)){
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
                'pozice' =>  ['required', 'string', 'max:255'],
                'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                'poznamka' => ['nullable','max:180'],
                'mesto_bydliste' => ['required','string', 'max:255'],
                'ulice_bydliste' => ['nullable','max:255'],
            ];
        }else if(($zamestnanec->email != $request->email) && ($zamestnanec->employee_login == $request->prihlasovaci_jmeno)){
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
                'pozice' =>  ['required', 'string', 'max:255'],
                'email' => ['required','unique:table_employees,email','string','email','max:255'],
                'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                'poznamka' => ['nullable','max:180'],
                'mesto_bydliste' => ['required','string', 'max:255'],
                'ulice_bydliste' => ['nullable','max:255'],
            ];
        }else if(($zamestnanec->email == $request->email) && ($zamestnanec->employee_login != $request->prihlasovaci_jmeno)){
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
                'pozice' =>  ['required', 'string', 'max:255'],
                'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                'prihlasovaci_jmeno' => ['required','unique:table_employees,employee_login', 'string', 'max:255'],
                'poznamka' => ['nullable','max:180'],
                'mesto_bydliste' => ['required','string', 'max:255'],
                'ulice_bydliste' => ['nullable','max:255'],
            ];
        }else{
            $pravidla = [
                'jmeno' => ['required', 'string', 'max:255'],
                'prijmeni' =>  ['required', 'string', 'max:255'],
                'narozeniny' => ['nullable','before: 2006-04-21 00:00:00'],
                'pozice' =>  ['required', 'string', 'max:255'],
                'email' => ['required','unique:table_employees,email','string','email','max:255'],
                'telefon' => ['required', 'regex:/^[\+]?([0-9\s\-]*)$/', 'min:9', 'max:16'],
                'prihlasovaci_jmeno' => ['required','unique:table_employees,employee_login', 'string', 'max:255'],
                'poznamka' => ['nullable','max:180'],
                'mesto_bydliste' => ['required','string', 'max:255'],
                'ulice_bydliste' => ['nullable','max:255'],
            ];
        }
        /* Definice vlastnich hlasek */
        $vlastniHlasky = [
            'required' => 'Položka :attribute je povinná.',
            'email' => 'U položky :attribute nebyl dodržen formát emailu.',
            'before' => 'Datum narození musí být nejméně před 15 lety.',
            'regex' => 'Formát :attribute není validní.',
            'max:255' => 'U položky :attribute je povoleno maximálně 255 znaků.',
            'unique' => 'Váš e-mail, nebo Váš login už v databázi evidujeme.',
            'digits' => 'Číslo musí mít 8 cifer'
        ];
        /* Realizace validace */
        $validator = Validator::make($request->all(), $pravidla, $vlastniHlasky);
        if ($validator->fails()) { return response()->json(['fail' => $validator->errors()->all()]); }

        if($user->company_url != "") { // pokud si firma aktivovala Google Drive
            /* Zjisteni zdali ma zamestnanec Google Drive slozku */
            if($zamestnanec->employee_url != ""){
                if($zamestnanec->employee_name == $request->jmeno && $zamestnanec->employee_surname == $request->prijmeni){ // pokud ma zamestnanec jmeno a prijmeni stejne, neni spustena aktualizace nazvu Google Drive slozky zamestnance
                }else{
                    $nazev = $request->jmeno . " " . $request->prijmeni;
                    /*Cesta k autorizacnimu klici*/
                    $authKey = storage_path('app/credentials.json');
                    /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
                    $httpClient = new Client(['verify' => false]);
                    /* Inicializace Google Drive klienta */
                    $GoogleClient = new Google_Client();
                    /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
                    $GoogleClient->setHttpClient($httpClient);
                    /* Nastaveni prihlaseni ke Google Drive API */
                    $GoogleClient->setAuthConfig($authKey);
                    /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
                    $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
                    $googleServ = new Google_Service_Drive($GoogleClient);
                    /* Zjisteni, zdali existuje Google Drive slozka zamestnance, pokud ano a novy nazev je jiny nez stavajici, tak dojde k prejmenovani */
                    $existujeSlozka = $googleServ->files->get($zamestnanec->employee_url);
                    if($existujeSlozka->name == $nazev){
                    }else if($existujeSlozka != NULL){
                        $novyNazevSlozky = new Google_Service_Drive_DriveFile();
                        $novyNazevSlozky->setName($nazev);
                        $googleServ->files->update($zamestnanec->employee_url, $novyNazevSlozky, ['uploadType' => 'multipart']);
                    }
                }
            }
        }

        $jeZmenaUdaj = 0;
        $jeZmenaHeslo = 0;
        $jeZmenaJazyk = 0;
        /* Pokud firma zadala i jazyky, tak se zacne s parsovanim */
        if($request->jazyky_edit != ""){ $id_jazyku = explode('&', $request->jazyky_edit); }
        $pocetJazykuZamestnanec = Employee_Language::getEmployeeLanguagesCount($zamestnanec->employee_id);

        $count = 0; // nastaveni pocitadla
        /* Pokud uzivatel zadal jazyky */
        if($request->jazyky_edit != "") {
            $poleJazyku = array();
            foreach ($id_jazyku as $jazyk_id) { // rozparsovani retezce na jednotlive identifikatory jazyku
                $jazyk_identif = explode('=', $jazyk_id);
                array_push($poleJazyku, $jazyk_identif[1]); // naplneni nazvu do pole
            }
            /* Provedeni aktualizace jazyku */
            DB::table('table_employee_table_languages')
                ->whereNotIn('language_id',$poleJazyku)
                ->where(['table_employee_table_languages.employee_id' => $zamestnanec->employee_id])
                ->delete();
            foreach ($id_jazyku as $jazyk_id) {
                $jazyk_identif = explode('=', $jazyk_id);
                $pocet = Employee_Language::getEmployeeParticularLanguageCount($zamestnanec->employee_id, $jazyk_identif[1]);
                if ($pocet == 0) { // pokud zamestnanec jeste dany jazyk nemel nadefinovany, tak se provede aktualizace
                    $employeeLanguage = new Employee_Language();
                    $employeeLanguage->language_id = $jazyk_identif[1];
                    $employeeLanguage->employee_id = $zamestnanec->employee_id;
                    $employeeLanguage->save();
                }
                $count++; // zvyseni pocitadla
            }
        }
        /* Pokud se maji odstranit vsechny jazyky */
        if($count == 0){
            DB::table('table_employee_table_languages')
                ->where(['table_employee_table_languages.employee_id' => $zamestnanec->employee_id])
                ->delete();
        }
        /* Zjisteni, zdali nastala libovolna zmena udaju */
        if(($zamestnanec->employee_name == $request->jmeno) && ($zamestnanec->employee_surname == $request->prijmeni) && ($zamestnanec->employee_birthday == $request->narozeniny)
            && ($zamestnanec->employee_phone == $request->telefon) && ($zamestnanec->email == $request->email) && ($zamestnanec->employee_note == $request->poznamka)
            && ($zamestnanec->employee_position == $request->pozice) && ($zamestnanec->employee_city == $request->mesto_bydliste)
            && ($zamestnanec->employee_street == $request->ulice_bydliste) && ($zamestnanec->employee_login == $request->prihlasovaci_jmeno)){
            $jeZmenaUdaj = 0;
        }else{
            $jeZmenaUdaj = 1;
        }

        /* Aktualizace udaju konkretniho zamestnance v databazi */
        Employee::where(['employee_id' => $zamestnanec->employee_id])->update(['employee_name' => $request->jmeno, 'employee_surname' => $request->prijmeni, 'employee_phone' => $request->telefon,
            'employee_birthday' => $request->narozeniny, 'email' => $request->email, 'employee_note' => $request->poznamka, 'employee_position' => $request->pozice, 'employee_city' => $request->mesto_bydliste,
            'employee_login' => $request->prihlasovaci_jmeno, 'employee_street' => $request->ulice_bydliste]);
        /* Aktualizace udaju zamestnance v OLAP sekci systemu */
        OlapETL::updateEmployeeDimension($idZamestnance, $request->jmeno, $request->prijmeni, $request->pozice, $zamestnanec->employee_overall);

        /* Zjisteni, zdali doslo ke zmene hesla */
        if(isset($request->heslo)){
            $zamestnanec->password = Hash::make($request->heslo);
            /* Definice pravidla */
            $pravidlo = ['heslo' => ['string', 'min:8','required_with:heslo_overeni','same:heslo_overeni']];
            /* Definice vlastnich hlasek */
            $vlastniHlasky = [
                'string' => 'Heslo musí být řetězcem.',
                'min' => 'Heslo musí obsahovat minimálně 8 znaků.',
                'required_with' => 'Vyplňte heslo pro ověření Vašeho hesla.',
                'same' => 'Hesla nejsou stejná.',
            ];
            /* Realizace validace*/
            $validator = Validator::make($request->all(), $pravidlo, $vlastniHlasky);
            /* Pri selhani poslani zpravy uzivateli */
            if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]); }
            if($request->heslo != ""){ // zjisteni, zdali doslo ke zmene hesla
                $jeZmenaHeslo = 1;
            }
        }
        $zamestnanec->save(); // pripadne ulozeni hesla
        if ($request->jazyky_edit != "") { // zjisteni, zdali doslo ke zmene jazyku
            if (count($id_jazyku) != $pocetJazykuZamestnanec) {
                $jeZmenaJazyk = 1;
            }
        }
        /* Odeslani odpovedi uzivateli */
        if($jeZmenaUdaj == 1 && $jeZmenaHeslo == 1 && $jeZmenaJazyk == 1){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->jmeno.' '.$request->prijmeni.', byly úspěšně změněny, včetně hesla a zaměstnancova nastavení jazyků.']);
        }
        else if($jeZmenaUdaj == 1 && $jeZmenaHeslo == 0 && $jeZmenaJazyk == 0){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->jmeno.' '.$request->prijmeni.' byly úspěšně změněny.']);
        }
        else if($jeZmenaUdaj == 0 && $jeZmenaHeslo == 1 && $jeZmenaJazyk == 0){
            return response()->json(['success'=>'Heslo zaměstnance '.$request->jmeno.' '.$request->prijmeni.' bylo úspěšně změněno.']);
        }
        else if($jeZmenaUdaj == 0 && $jeZmenaHeslo == 0 && $jeZmenaJazyk == 1){
            return response()->json(['success'=>'Jazykové dovednosti zaměstnance '.$request->jmeno.' '.$request->prijmeni.' byly úspěšně změněny.']);
        }
        else if($jeZmenaUdaj == 1 && $jeZmenaHeslo == 1 && $jeZmenaJazyk == 0){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->jmeno.' '.$request->prijmeni.', byly úspěšně změněny, včetně jeho hesla.']);
        }
        else if($jeZmenaUdaj == 0 && $jeZmenaHeslo == 1 && $jeZmenaJazyk == 1){
            return response()->json(['success'=>'Heslo zaměstnance '.$request->jmeno.' '.$request->prijmeni.', bylo úspěšně změněno, včetně nastavení jazyků zaměstnance.']);
        }
        else if($jeZmenaUdaj == 1 && $jeZmenaHeslo == 0 && $jeZmenaJazyk == 1){
            return response()->json(['success'=>'Údaje zaměstnance '.$request->jmeno.' '.$request->prijmeni.', byly úspěšně změněny, včetně nastavení jazyků zaměstnance.']);
        }else{
            return response()->json(['success'=>'0']);
        }
    }

    /* Nazev funkce: assignShift
       Argumenty: id - jednoznacny identifikator zamestnance
       Ucel: obsah modalniho okna pro prirazeni zamestnance ke smenam */
    public function assignShift($id){
        $user = Auth::user();
        /* Ziskani zamestnance a jeho hodiny odpracovane za tyden a mesic */
        $zamestnanec = Employee::find($id);
        $tydenniPocetHodin = Employee::getEmployeeWeekShiftsHour($id);
        $mesicniPocetHodin = Employee::getEmployeeMonthShiftsHour($id);
        /* Ziskani budoucich smen */
        $smeny = Shift::getUpcomingCompanyShifts($user->company_id);
        /* Definice prostoru pro ukazani odpracovanych hodin */
        $out = '<div class="alert alert-warning" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span>x</span></button>
                    <center><strong>Aktuální počet hodin na směnách zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.': <br>'.$tydenniPocetHodin.' tento týden<br>'.$mesicniPocetHodin.' tento měsíc</strong><center></div>';
        if(count($smeny) == 0){
            $out .= '<div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span>x</span></button>
                    <center><strong>Neexistují žádné budoucí směny, které by zaměstnanci mohly být přiřazeny (vytvořte libovolnou směnu v budoucnosti, poté tu bude zobrazena).</strong><center></div>';
        }
        /* Vlozeni smen do tabulky */
        $out .= '<input type="text" class="form-control" style="margin-bottom:15px;" id="vyhledavacSeznamZamestnancuPrirazeniSmeny" onkeyup="Search()" placeholder="Hledat směnu na základě lokace, důležitosti, nebo data začátku, či konce ...">
                 <table class="table table-dark" id="tableShiftsAssignEmployee" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th style="width:19%;text-align: center;">Začátek</th>
                                <th style="width:19%;text-align: center;">Konec</th>
                                <th style="width:19%;text-align: center;">Lokace</th>
                                <th style="width:19%;text-align: center;">Důležitost</th>
                                <th style="width:19%;text-align: center;">Poznámka</th>
                                <th style="width:5%;text-align: center;">Přiřazeno</th>
                            </tr>
                        </thead>
                        <tbody>';
        /* Iterace skrze smeny a postupne vkladani smen jako radky tabulek, pokud uz ji zamestnanec obsadil, tak bude automaticky vyplnena */
        foreach ($smeny as $smena){
            $aktualniSmena = Employee_Shift::getEmployeeParticularShift($id, $smena->shift_id);
            $aktualniDulezitost = ImportancesShifts::getParticularImportance($smena->shift_importance_id);
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $out .= '<tr><td class="text-center">'.$smena->shift_start.'</td><td class="text-center"> '.$smena->shift_end.'</td><td class="text-center"> '.$smena->shift_place.'</td> <td class="text-center"> '.$aktualniDulezitost[0]->importance_description.'</td> <td class="text-center"> '.$smena->shift_note.'</td>';
            if($aktualniSmena->isEmpty()){
                $out .= '<td><center><input type="checkbox" name="shift_shift_assign_id" class="form-check-input shift_shift_assign_id" id="shift_shift_assign_id" name="shift_shift_assign_id[]" value="'.$smena->shift_id.'"></center></td> </tr>';
            }else{
                $out .= '<td><center><input type="checkbox" name="shift_shift_assign_id" class="form-check-input shift_shift_assign_id" id="shift_shift_assign_id" name="shift_shift_assign_id[]" value="'.$smena->shift_id.'" checked></center></td> </tr>';
            }
        }
        $out .= '</tbody></table>';
        $out .= '<script>
                    /* Implementace vyhledavace v ramci hledani smen */
                    $(document).ready(function(){
                        $("#vyhledavacSeznamZamestnancuPrirazeniSmeny").on("keyup", function() { // po zapsani znaku ve vyhledavani
                            var retezec = $("#vyhledavacSeznamZamestnancuPrirazeniSmeny").val(); // ziskani hodnoty ve vyhledavaci
                            var vysledek = retezec.toUpperCase(); // transformace hodnoty na velka pismena
                            var radkyTabulky = $("#tableShiftsAssignEmployee tr"); // ziskani radku tabulek
                            radkyTabulky.each(function () { // iterace skrze radky tabulky
                                var bunka = $(this).find("td"); // ziskani hodnoty bunky
                                bunka.each(function () { // iterace skrz bunky
                                    var obsahBunky = $(this).text(); // ulozeni hodnoty bunky
                                    if((obsahBunky.toUpperCase().includes(vysledek) == false) == false){ // kontrola zdali hledany retezec je podmnozinou nejake hodnoty v tabulce
                                          $(this).closest("tr").toggle(true); // radek je ponechan
                                          return false; // pokracovani dalsim radkem
                                    }else{
                                          $(this).closest("tr").toggle(false); // schovani radku tabulky, v ktere se nachazi aktualni bunka
                                          return true; // pokracovani dalsi bunkou radku
                                    }
                                });
                             });
                         });
                      });
                 </script>';
        /* Odeslani obsahu */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateassignShift
       Argumenty: id - jednoznacny identifikator zamestnance, request - udaje zapsane firmou
       Ucel: aktualizace prirazeni zamestnance */
    public function updateassignShift(Request $request, $id){
        date_default_timezone_set('Europe/Prague');
        $employee = Employee::find($id);
        $smena_info = '';
        $count = 0;
        /* Pokud uzivatel nezadal smeny */
        if($request->shifts_ids != "") {
            /* Parsovani jednotlivych smen a ulozeni jejich udaju do poli */
            $shift_id_arr = explode('&', $request->shifts_ids);
            $delka = count($shift_id_arr);
            $shift_ids_collector = array();
            $shift_starts_collector = array();
            $shift_ends_collector = array();
            foreach ($shift_id_arr as $shift_id) {
                $shift_id_value = explode('=', $shift_id);
                array_push($shift_ids_collector,$shift_id_value[1]);
                $shift_tmp = Shift::find($shift_id_value[1]);
                array_push($shift_starts_collector,$shift_tmp->shift_start);
                array_push($shift_ends_collector,$shift_tmp->shift_end);
            }
            /* Usek kodu pro aktualizaci smen v OLAP sekci systemu i OLTP sekci systemu */
            OlapETL::deleteCancelledPreviouslyAssignedShift($employee->employee_id, $shift_starts_collector, $shift_ends_collector);
            Employee_Shift::deleteEmployeeAssignedShiftsWithAttendance($id, $shift_ids_collector);
            /* Iterace skrze id smen */
            foreach ($shift_id_arr as $shift_id) {
                $shift_id_value = explode('=', $shift_id);
                $concreteShift = Employee_Shift::getEmployeeParticularShift($id,$shift_id_value[1]);
                $shift = Shift::find($shift_id_value[1]);
                /* Shromazdovani informaci o smene */
                if ($count == $delka - 1) {
                    $smena_info .= "<br>".$shift->shift_start.' '.$shift->shift_end.', lokace: '.$shift->shift_place.".";
                }else{
                    $smena_info .= "<br>".$shift->shift_start.' '.$shift->shift_end.', lokace: '.$shift->shift_place.", ";
                }
                if($concreteShift->isEmpty()){ /* Pokud zamestnanec smenu nema, tak se mu vytvori, nasledne se smena vytvori vytvori i v OLAP sekci systemu */
                    $user = Auth::user();
                    /* Prirazeni smeny */
                    Employee_Shift::create(['shift_id' => $shift_id_value[1], 'employee_id' => $employee->employee_id]);
                    /* Vytvoreni zaznamu v dimenzi smen, casove dimenzi, dimenzi firem a zamestnancu */
                    $shift_info_id = OlapETL::extractDataToShiftInfoDimension($shift);
                    $time_id = OlapETL::extractDataToTimeDimension($shift_info_id, $shift);
                    $employee_id = OlapETL::extractDataToEmployeeDimension($employee);
                    $company_id = OlapETL::extractDataToCompanyDimension($user);
                    /* Extrakce dat do tabulky faktu */
                    OlapETL::extractDataToShiftFact($shift, $employee, $shift_info_id, $time_id, $employee_id, $company_id);
                    //return response()->json(['success' => 'Firma je: '.$company_id.', ID času je: '.$time_id.', ID zaměstnance je: '.$employee_id.', ID směny je: '.$shift_info_id]);
                }else{}
                $count++;
            }
        }
        if($count > 0){ // pokud uzivatel odstranil i posledni prirazenou smenu
        }else{
            /* Smazani vsech prirazenych smen a dochazky */
            OlapETL::deleteAllCancelledPreviouslyAssignedShift($employee->employee_id);
            Employee_Shift::deleteEmployeeAllUpcomingShiftsWithAttendance($id);
        }
        /* Priprava vypisu hlasky uzivateli */
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

    /* Nazev funkce: destroy
       Argumenty: id - jednoznacny identifikator zamestnance
       Ucel: odstraneni zamestnance z databaze */
    public function destroy($id){
        $zamestnanec = Employee::find($id);
        /* Smazani jazyku */
        DB::table('table_employee_table_languages')
            ->join('table_company_languages','table_company_languages.language_id','=','table_employee_table_languages.language_id')
            ->where(['table_employee_table_languages.employee_id' => $zamestnanec->employee_id])
            ->delete();

        if($zamestnanec->employee_url != ""){
            /*Cesta k autorizacnimu klici*/
            $authKey = storage_path('app/credentials.json');
            /* Inicializace klienta a nastaveni atributu verify na false (pote neni pozadovan certifikat SSL, nebo TLS) */
            $httpClient = new Client(['verify' => false]);
            /* Inicializace Google Drive klienta */
            $GoogleClient = new Google_Client();
            /* Diky tomuto nastaveni (setHttpClient) bude mozno pracovat s Google Drive API i bez SSL nebo TLS certifikatu, nebot httpClient ma nastaven atribut verify na false */
            $GoogleClient->setHttpClient($httpClient);
            /* Nastaveni prihlaseni ke Google Drive API */
            $GoogleClient->setAuthConfig($authKey);
            /* Pro moznost nahravani, vytvareni souboru, ... je potreba nastavit scope na Google_Service_Drive::DRIVE */
            $GoogleClient->addScope([Google_Service_Drive::DRIVE]);
            $googleServ = new Google_Service_Drive($GoogleClient);
            /* Pokud zamestnanec ma Google Drive slozku, tak je mu smazana */
            $existujeSlozka = $googleServ->files->get($zamestnanec->employee_url);
            if ($existujeSlozka != NULL) {
                $googleServ->files->delete($zamestnanec->employee_url);
            }
        }
        $jmeno = $zamestnanec->employee_name;
        $prijmeni = $zamestnanec->employee_surname;
        /* Realizace smazani z OLAP sekce systemu i z databaze */
        OlapETL::deleteRecordFromEmployeeDimension($id);
        Employee::find($id)->delete();
        return response()->json(['success'=>'Zaměstnanec '.$jmeno.' '.$prijmeni.' byl úspěšně smazán.']);
    }

    /* Nazev funkce: getAttendanceOptions
       Argumenty: id - jednoznacny identifikator zamestnance
       Ucel: zobrazeni obsahu moznosti dochazky do modalniho okna */
    public function getAttendanceOptions($id){
        $user = Auth::user();
        $out  = '';
        /* Ziskani smen a postupne vyplnovani jejich udaju do options */
        $smeny = Employee_Shift::getAttendanceOptionsEmployees($id, $user->company_id);
        if(count($smeny) == 0){
            $out .= '<div class="alert alert-danger alert-block"><strong>Zaměstnanec nemá, v rámci aktuálního měsíce, zatím žádné směny.</strong></div>';
        }else{
            $out .='<div class="form-group"><select name="vybrana_smena" required id="vybrana_smena" style="color:black" class="form-control input-lg dynamic vybrana_smena"><option value="">Vyberte směnu</option>';
            foreach ($smeny as $smena){
                /* Zmena formatu datumu smen a jejich nasledne vlozeni do options */
                $date_start = new DateTime($smena->shift_start);
                $date_end = new DateTime($smena->shift_end);
                $datumZobrazeniStart = $date_start->format('d.m.Y H:i');
                $datumZobrazeniEnd = $date_end->format('d.m.Y H:i');
                $out .= '<option id="'.$smena->shift_id.'" value="'.$smena->shift_id.'">'.$datumZobrazeniStart.' - '.$datumZobrazeniEnd.'</option>';
            }
            $out .= '</select></div>';
        }
        /* Definice tlacitek pro ovladani moznosti dochazky */
        $out .= '<center><button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckinForm" id="obtainCheckInShift" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Příchod</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceCheckoutForm" id="obtainCheckOutShift" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Odchod</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceAbsenceForm" id="obtainAbsenceReasonAttendance" class="btn btn-primary"><i class="fa fa-lightbulb-o"></i> Status</button>
                  <button type="button" data-id="'.$id.'" data-toggle="modal" data-target="#ShowAttendanceNoteForm" id="obtainNoteAttendance" class="btn btn-primary"><i class="fa fa-sticky-note-o"></i> Poznámka</button>';
        /* Zaslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: showCheckinshowCheckin
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu prichodu (moznosti) do modalniho okna */
    public function showCheckin($zamestnanec_id, $smena_id){
        $out = '';
        date_default_timezone_set('Europe/Prague');
        if($smena_id == "undefined"){
            $out .= '<div class="alert alert-danger alert-block text-center"><strong>Nevybral jste žádnou směnu.</strong></div>';
            return response()->json(['out' => $out]);
        }
        /* Ziskani dochazky */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        /* Ziskani smeny */
        $smena = Shift::find($smena_id);
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje vypise se nedefinovano jako hodnota prichodu
            $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
            $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
            $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>';
            $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'">';
            $out .= '</div>';
        }else{
            if($dochazka[0]->attendance_check_in_company == NULL){ // pokud dochazka existuje, ale neni zapsan prichod
                $datumStart = date('Y-m-d\TH:i', strtotime($smena->shift_start));
                $out .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
                $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>';
                $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'">';
                $out .= '</div>';
            }else{ // pokud je zapsan prichod, tak se vpise do datetime local inputu
                $date_start = new DateTime($dochazka[0]->attendance_check_in_company);
                $datumZobrazeni = $date_start->format('d.m.Y H:i');
                $datumStart = date('Y-m-d\TH:i', strtotime($dochazka[0]->attendance_check_in_company));
                $out .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: '.$datumZobrazeni.'</strong>
                    </div>';
                $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>';
                $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkin" id="attendance_create_checkin" value="'.$datumStart.'">';
                $out .= '</div>';
            }
        }
        /* Odeslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateCheckIn
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadany prichod
       Ucel: aktualizace prichodu */
    public function updateCheckIn(Request $request,$zamestnanec_id,$smena_id){
        date_default_timezone_set('Europe/Prague');
        $smena = Shift::find($smena_id);
        $user = Auth::user();
        $shift_start = new DateTime($smena->shift_start);
        $shift_end = new DateTime($smena->shift_end);
        $shift_checkin = new DateTime($request->attendance_check_in_company);
        $sekundy = 0; // 0 minut
        $difference_start = $shift_checkin->format('U') - ($shift_start->format('U') - $sekundy);
        $difference_end = $shift_end->format('U') - $shift_checkin->format('U');
        /* Usek kodu urceny k validaci datumu */
        $chybaDatumy = array();
        $bool_datumy = 0;
       /* if($difference_start < 0){
            array_push($chybaDatumy,'Zapsaný prichod je dříve než začátek směny o více než 20 minut!');
            $bool_datumy = 1;
        } */
        if($difference_end < 0){
            array_push($chybaDatumy,'Zapsaný příchod je později než konec směny samotné!');
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
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje, tak se vytvori bud se zpozdenim (4) ci v poradku (5)
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
                    array_push($chybaDatumy,'Zapsaný příchod je později než zapsaný odchod směny!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
                /* Provedeni transformace odpracovanych hodin v OLAP sekci systemu */
                OlapETL::aggregateEmployeeTotalWorkedHours($shift_info_id, $zamestnanec->employee_id, $user->company_id, $dochazka[0]->attendance_check_out_company, $request->attendance_check_in_company);
            }
            if($company_check_in_date > $shift_start_date){
                /* Aktualizace dochazky */
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => $request->attendance_check_in_company,'attendance_came' => 1, 'absence_reason_id' => 4]);
            }else{
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => $request->attendance_check_in_company,'attendance_came' => 1, 'absence_reason_id' => 5]);
            }
        }
        /* Extrahovani do dimenze smen, provedeni transformace ohledne celkove zpozdeni a priznaku zpozdeni a extrakce indikatoru prichodu do dimenze smen */
        OlapETL::extractAttendanceCameToShiftFacts($shift_info_id, $zamestnanec->employee_id, $user->company_id);
        OlapETL::aggregateEmployeeAbsenceTotalHoursAndLateFlag($shift_info_id, $zamestnanec->employee_id, $user->company_id, $smena->shift_start, $request->attendance_check_in_company);
        OlapETL::extractAttendanceCheckInCompanyToShiftInfoDimension($shift_info_id, $request->attendance_check_in_company);
        return response()->json(['success'=>'Docházka příchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

    /* Nazev funkce: showCheckOut
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu odchodu (moznosti) do modalniho okna */
    public function showCheckOut($zamestnanec_id,$smena_id){
        $out = '';
        date_default_timezone_set('Europe/Prague');
        if($smena_id == "undefined"){
            $out .= '<div class="alert alert-danger alert-block text-center">
                            <strong>Nevybral jste žádnou směnu.</strong>
                        </div>';
            return response()->json(['out' => $out]);
        }
        /* Ziskani dochazky a smeny  a nasledne vyplneni datetime local hodnotou odchodu ci vypsani chybove hlasky */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $smena = Shift::find($smena_id);
        if($dochazka->isEmpty()){
            $datumEnd = date('Y-m-d\TH:i', strtotime($smena->shift_end));
            $out .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
            $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>';
            $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumEnd.'">';
            $out .= '</div>';
        }else{
            if($dochazka[0]->attendance_check_out_company == NULL){
                $datumEnd = date('Y-m-d\TH:i', strtotime($smena->shift_end));
                $out .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: nedefinováno</strong>
                    </div>';
                $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>';
                $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumEnd.'">';
                $out .= '</div>';
            }else{
                $date_start = new DateTime($dochazka[0]->attendance_check_out_company);
                $datumZobrazeni = $date_start->format('d.m.Y H:i');
                $datumStart = date('Y-m-d\TH:i', strtotime($dochazka[0]->attendance_check_out_company));
                $out .= '<div class="alert alert-info alert-block text-center">
                        <strong>Aktuálně nastaveno na: '.$datumZobrazeni.'</strong>
                    </div>';
                $out .= '<div class="input-group">
                           <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>';
                $out .= '<input type="datetime-local" class="form-control" name="attendance_create_checkout" id="attendance_create_checkout" value="'.$datumStart.'">';
                $out .= '</div>';
            }
        }
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateCheckOut
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadany odchod
       Ucel: aktualizace odchodu */
    public function updateCheckOut(Request $request,$zamestnanec_id,$smena_id){
        date_default_timezone_set('Europe/Prague');
        $user = Auth::user();
        /* Ziskani smeny a udaju o ni */
        $smena = Shift::find($smena_id);
        $shift_start = new DateTime($smena->shift_start);
        $shift_checkout = new DateTime($request->attendance_check_out_company);
        $sekundy = 0; // 0 minut
        /* Usek urceny pro validaci datumu */
        $difference_start = $shift_checkout->format('U') - ($shift_start->format('U') - $sekundy);
        $chybaDatumy = array();
        $bool_datumy = 0;
        if($difference_start < 0){
            array_push($chybaDatumy,'Zapsaný odchod je dříve než začátek směny samotné!');
            $bool_datumy = 1;
        }
        if ($bool_datumy == 1) {
            return response()->json(['fail' => $chybaDatumy]);
        }
        /* Ziskani dochazky a zamestnance */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        /* Ziskani ID smeny v ramci OLAP sekce systemu */
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje, tak se vytvori
            Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_check_out_company' => $request->attendance_check_out_company, 'attendance_came' => 1]);
        }else{
            if($dochazka[0]->attendance_check_in_company != NULL){ // pokud dochazka existuje a odchod v ni neni NULL tak nastane pokud o transformaci odpracovanych hodin v OLAP sekci systemu
                $shift_checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                $difference_checkins = $shift_checkout->format('U') - $shift_checkin->format('U');
                if($difference_checkins < 0){ // validace korektne zapsaneho checkoutu
                    array_push($chybaDatumy,'Zapsaný odchod je dřívě než zapsaný příchod směny!');
                    return response()->json(['fail' => $chybaDatumy]);
                }
                /* Realizace transformace odpracovanych hodin v OLAP sekci systemu */
                OlapETL::aggregateEmployeeTotalWorkedHours($shift_info_id, $zamestnanec->employee_id, $user->company_id, $dochazka[0]->attendance_check_in_company, $request->attendance_check_out_company);
            }
            /* Aktualizace dochazky */
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_out_company' => $request->attendance_check_out_company,'attendance_came' => 1]);
        }
        /* Extrahovani odchodu a indikatoru prichodu do dimenze smen */
        OlapETL::extractAttendanceCameToShiftFacts($shift_info_id, $zamestnanec->employee_id, $user->company_id);
        OlapETL::extractAttendanceCheckOutCompanyToShiftInfoDimension($shift_info_id, $request->attendance_check_out_company);
        /* Odeslani odpovedi */
        return response()->json(['success'=>'Docházka odchodu zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

    /* Nazev funkce: showAbsence
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu statusu (moznosti) do modalniho okna */
    public function showAbsence($zamestnanec_id,$smena_id){
        $out = '';
        /* Pokud uzivatel nevybral zadnou smenu */
        if($smena_id == "undefined"){
            $out .= '<div class="alert alert-danger alert-block text-center"><strong>Nevybral jste žádnou směnu.</strong></div>';
            return response()->json(['out' => $out]);
        }
        /* Ziskani dochazky a vsech duvodu absence */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $duvody = AbsenceReason::getAllReasons();
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje
            $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
        }else{
            if($dochazka[0]->absence_reason_id == NULL){ // pokud je status dochazky nastaven na NULL
                $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: nedefinováno</strong></div>';
            }else{ // pokud je status vyplneny
                $duvod_absence = AbsenceReason::getEmployeeCurrentShiftAbsenceReason($zamestnanec_id, $smena_id);
                $out .= '<div class="alert alert-info alert-block text-center"><strong>Aktuálně nastaveno na: '.$duvod_absence[0]->reason_description.'</strong></div>';
            }
        }
        /* Vlozeni duvodu do moznosti vyberu */
        $out .='<div class="form-group"><select name="duvody_absence" required id="duvody_absence" style="color:black" class="form-control input-lg dynamic duvody_absence">';
        if($dochazka->isEmpty()){
            foreach ($duvody as $duvod){
                $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
            }
        }else{
            if($dochazka[0]->absence_reason_id == NULL){
                foreach ($duvody as $duvod){
                    $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
                }
            }else{
                foreach ($duvody as $duvod){
                    if($duvod->reason_id == $dochazka[0]->absence_reason_id){
                        $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
                    }
                }
                foreach ($duvody as $duvod){
                    if($duvod->reason_id != $dochazka[0]->absence_reason_id){
                        $out .= '<option id="'.$duvod->reason_id.'" value="'.$duvod->reason_id.'">'.$duvod->reason_description.'</option>';
                    }
                }
            }
        }
        $out .= '</select></div>';
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateAbsence
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadany status
       Ucel: aktualizace statusu dochazky */
    public function updateAbsence(Request $request,$zamestnanec_id,$smena_id){
        $user = Auth::user();
        /* Ziskani dochazky, nasledne zamestnance a smeny */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $rozhod = 0;
        $smena = Shift::find($smena_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        if($request->attendance_absence_reason_id == 4 || $request->attendance_absence_reason_id == 5){ $rozhod = 1; }
        /* Ziskani ID smeny z dimenze smen */
        $shift_info_id = OlapETL::getShiftInfoId($zamestnanec_id, $user->company_id, $smena->shift_start, $smena->shift_end);
        if($dochazka->isEmpty()){ // pokud dochazka neexistuje
            if($rozhod == 1){ // pokud je statusem zpozdeni ci ok, tak se indikator prichodu nastavi na 1
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 1]);
            }else{
                Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'absence_reason_id' => $request->attendance_absence_reason_id, 'attendance_came' => 0]);
            }
        }else{ // pokud dochazka existuje, tak se jednotliva pole pouze aktualizuji
            if($request->attendance_absence_reason_id == 4 || $request->attendance_absence_reason_id == 5){
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['absence_reason_id' => $request->attendance_absence_reason_id,'attendance_came' => 1]);
            }else{
                Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_check_in_company' => NULL, 'attendance_check_out_company' => NULL,'absence_reason_id' => $request->attendance_absence_reason_id,'attendance_came' => 0]);
            }
        }
        /* Extrakce statusu dochazky do tabulky faktu */
        OlapETL::extractAbsenceReasonToShiftFacts($shift_info_id, $zamestnanec_id, $user->company_id, $request->attendance_absence_reason_id);
        return response()->json(['success'=>'Status docházky zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byl úspěšně zapsán.']);
    }

    /* Nazev funkce: showAttendanceNote
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny
       Ucel: zobrazeni obsahu poznamky (moznosti) do modalniho okna */
    public function showAttendanceNote($zamestnanec_id,$smena_id){
        $out = '';
        /* Pokud uzivatel nevybral smenu */
        if($smena_id == "undefined"){
            $out .= '<div class="alert alert-danger alert-block text-center"><strong>Nevybral jste žádnou směnu.</strong></div>';
            return response()->json(['out' => $out]);
        }
        /* Usek kodu starajici se o definici zobrazeni poznamky */
        $zamestnanec = Employee::find($zamestnanec_id);
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        if($dochazka->isEmpty()){
            $out .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' [maximálně 180 znaků] ..." id="attendance_note" class="form-control" autocomplete="attendance_note"></textarea>';
        }else{
            if($dochazka[0]->attendance_note == NULL){
                $out .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' [maximálně 180 znaků] ..." id="attendance_note" class="form-control" autocomplete="attendance_note"></textarea>';
            }else{
                $out .= ' <textarea name="attendance_note" placeholder="Zadejte poznámku k docházce zaměstnance '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' [maximálně 180 znaků] ..." id="attendance_note" class="form-control" autocomplete="attendance_note">'.$dochazka[0]->attendance_note.'</textarea>';
            }
        }
        /* Poslani obsahu do modalniho okna */
        return response()->json(['out' => $out]);
    }

    /* Nazev funkce: updateAttendanceNote
       Argumenty: zamestnanec_id - identifikator zamestnance, smena_id - identifikator smeny, request - zadana poznamka
       Ucel: aktualizace poznamky */
    public function updateAttendanceNote(Request $request,$zamestnanec_id,$smena_id){
        /* Overeni, zdali poznamka nema vice nez 180 znaku*/
        $validator = Validator::make($request->all(), ['poznamka' => ['max:180']]);
        if($validator->fails()){
            return response()->json(['fail' => $validator->errors()->all()]);
        }
        /* Ziskani dochazky a zamestnance */
        $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena_id, $zamestnanec_id);
        $zamestnanec = Employee::find($zamestnanec_id);
        /* Pokud dochazka neexistuje, tak se vytvori a rovnou se do ni vlozi poznamka, jinak se dochazka aktualizuje pouze v ramci pole poznamky */
        if($dochazka->isEmpty()){
            Attendance::create(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id, 'attendance_note' => $request->poznamka]);
        }else{
            Attendance::where(['employee_id' => $zamestnanec_id, 'shift_id' => $smena_id])->update(['attendance_note' => $request->poznamka]);
        }
        return response()->json(['success'=>'Poznámka docházky zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.' byla úspěšně zapsána.']);
    }

}
