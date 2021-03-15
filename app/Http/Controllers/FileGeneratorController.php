<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Employee_Shift;
use App\Models\Languages;
use App\Models\Shift;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FileGeneratorController extends Controller
{
    public function index(){
        $user = Auth::user();
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->get();
        $zamestnanci = Employee::getCompanyEmployeesAssigned($user->company_id);
        $smeny = Shift::getCompanyShiftsAssigned($user->company_id);
        foreach ($smeny as $smena){
            $shift_start = new DateTime( $smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
        }

        return view('company_actions.file_generator')
            ->with('profilovka',$user->company_picture)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci)
            ->with('smeny',$smeny);
    }

    public function generateEmployeesList(){
        $user = Auth::user();
        $html = '';
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam zaměstnanců firmy: '.$user->company_name.'</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 12px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;font-size:13px;">Jméno</th>
                            <th style="border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;font-size:13px;">Příjmení</th>
                            <th style="border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;font-size:13px;">Telefon</th>
                            <th style="border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;font-size:13px;">Email</th>
                            <th style="border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;font-size:13px;">Pozice</th>
                            <th style="border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;font-size:13px;">Adresa</th>
                           </tr>
                      </thead>
                      <tbody>
                    ';
        foreach ($zamestnanci as $zamestnanec){
            $html .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_name.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_surname.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_phone.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->email.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_position.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_street.', '.$zamestnanec->employee_city.'</td>
                       </tr>';
        }
        $html .= '</tbody></table>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download('zamestnanci.pdf','UTF-8');

    }

    public function generateShiftsList(){
        $user = Auth::user();
        $smeny = Shift::getCompanyShifts($user->company_id);

        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam historicky všech směn firmy: '.$user->company_name.'</h3>
                    <table class="table center" style="font-family: DejaVu Sans;font-size: 13px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 20px;">Začátek</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 20px;">Konec</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 20px;">Lokace</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 20px;">Důležitost</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 20px;">Počet hodin</th>
                           </tr>
                      </thead>
                      <tbody>
                    ';
        foreach ($smeny as $smena){
            $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
            $shift_start = new DateTime( $smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $hodinyRozdil = $shift_end->diff($shift_start);
            $pocetHodin = $hodinyRozdil->h;
            $pocetMinut = $hodinyRozdil->i;
            $html .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_start.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_end.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_place.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$dulezitost[0]->importance_description.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                       </tr>';
        }
        $html .= '</tbody></table>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download('smeny.pdf','UTF-8');

    }

    public function generateCompanyProfile(){
        $user = Auth::user();
        $html = '';
        $shift_start = new DateTime($user->created_at);
        $shift_format = $shift_start->format('d.m.Y H:i');
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($user->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($user->company_id);

        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Údaje firmy: '.$user->company_name.'</h3>
                  <center><div class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">
                       <p>Jméno zástupce: '.$user->company_user_name.'</p>
                       <p>Příjmení: '.$user->company_user_surname.'</p>
                       <p>Email: '.$user->email.'</p>
                       <p>Telefon: '.$user->company_phone.'</p>
                       <p>Login: '.$user->company_login.'</p>
                       <p>IČO: '.$user->company_ico.'</p>
                       <p>Adresa sídla: '.$user->company_street.', '.$user->company_city.'</p>
                       <p>Vytvořeno: '. $shift_format.'</p>
                       <p>Počet zaměstnanců: '.$pocetZamestnancu.'</p>
                       <p>Počet směn celkově: '.$pocetSmen.'</p>
                   </div></center>';

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download('profil.pdf','UTF-8');
    }

    public function generateEmployeeShifts(Request $request){
        $user = Auth::user();
        $html = '';
        if($request->vybrany_zamestnanec == -1){
            session()->flash('fail', 'Vyberte nějakého zaměstnance!');
            return redirect()->back();
        }else{
            $zamestnanec = Employee::find($request->vybrany_zamestnanec);
            $smeny = Shift::getEmployeeShifts($request->vybrany_zamestnanec);
            $html = '<h4 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam všech směn zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</h4>
                    <table class="table center" style="font-family: DejaVu Sans;font-size: 13px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Začátek</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Konec</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Lokace</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Důležitost</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Počet hodin</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Odpracováno</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Přišel</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 20px;">Status</th>
                           </tr>
                      </thead>
                      <tbody>
                    ';
            $celkove = 0;
            $celkove_odpracovano = 0;
            foreach ($smeny as $smena){
                $dochazka = DB::table('table_attendances')
                    ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
                    ->select('table_attendances.attendance_came','table_attendances.absence_reason_id',
                        'table_attendances.attendance_check_in', 'table_attendances.attendance_check_out','table_attendances.attendance_check_in_company',
                        'table_attendances.attendance_check_out_company')
                    ->where(['table_attendances.shift_id' => $smena->shift_id,'table_attendances.employee_id' => $request->vybrany_zamestnanec])
                    ->get();

                $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
                $shift_start = new DateTime( $smena->shift_start);
                $smena->shift_start = $shift_start->format('d.m.Y H:i');
                $shift_end = new DateTime( $smena->shift_end);
                $smena->shift_end = $shift_end->format('d.m.Y H:i');
                $hodinyRozdil = $shift_end->diff($shift_start);
                $pocetHodin = $hodinyRozdil->h;
                $pocetMinut = $hodinyRozdil->i;
                $celkove = $celkove + $pocetHodin + $pocetMinut/60;
                if($dochazka->isEmpty()){
                    $html .= '<tr>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_start.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_end.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_place.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$dulezitost[0]->importance_description.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p style="color:black;">Nezapsaný check-in/out</p></td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Nezapsáno</p></td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Neznámý</p></td>
                               </tr>';
                }else{
                    $status = DB::table('table_absence_reasons')
                        ->select('table_absence_reasons.reason_description')
                        ->where(['table_absence_reasons.reason_id' => $dochazka[0]->absence_reason_id])
                        ->get();
                    $statView = "";
                    if($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL){
                        $statView = '<p>Neznámý</p>';
                    }else{
                        if($dochazka[0]->absence_reason_id == 5){
                            $statView = '<p>'.$status[0]->reason_description.'</p>';
                        }else{
                            $statView = '<p>'.$status[0]->reason_description.'</p>';
                        }
                    }

                    $odpracovano = '';
                    if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                        if($dochazka[0]->attendance_check_in == NULL || $dochazka[0]->attendance_check_out == NULL){
                            $odpracovano = '<p style="color:black;">Nezapsaný check-in/out</p>';
                        }else if($dochazka[0]->attendance_check_in != NULL && $dochazka[0]->attendance_check_out != NULL){
                            $checkin = new DateTime($dochazka[0]->attendance_check_in);
                            $checkout = new DateTime($dochazka[0]->attendance_check_out);
                            $hodinyRozdilCheck =$checkout->diff($checkin);
                            $odpracovano = '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                            $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                        }
                    }else if($dochazka[0]->attendance_check_in_company != NULL && $dochazka[0]->attendance_check_out_company != NULL){
                        $checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                        $checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                        $hodinyRozdilCheck =$checkout->diff($checkin);
                        $odpracovano = '<p style="color:black;">'.$hodinyRozdilCheck->h.'h'.$hodinyRozdilCheck->i.'m</p>';
                        $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                    }

                    if($dochazka[0]->attendance_came == NULL || $dochazka[0]->attendance_came == 0){
                        $html .= '<tr>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_start.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_end.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_place.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$dulezitost[0]->importance_description.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$odpracovano.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"> <p>Ne</p></td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$statView.'</td>
                                  </tr>';
                    }else{
                        $html .= '<tr>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_start.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_end.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_place.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$dulezitost[0]->importance_description.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$odpracovano.'</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Ano</p> </td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$statView.'</td>
                                  </tr>';
                    }
                }
            }
            $html .= '</tbody></table><br>';
            $cas_arr = explode(".", $celkove);
            $cas_odpracovano_arr = explode(".", $celkove_odpracovano);
            if(sizeof($cas_arr) > 1){
                $cas_arr[1] = substr( $cas_arr[1],0,2);
                $cas_arr[1]= round(($cas_arr[1]/100)*60,0);
                $html .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h'.$cas_arr[1].'m</b>.</p></center>';
            }else{
                $html .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h0m</b>.</p></center>';
            }

            if(sizeof($cas_odpracovano_arr) > 1){
                $cas_odpracovano_arr[1] = substr($cas_odpracovano_arr[1],0,2);
                $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/100)*60,0);
                $html .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet odpracovaných hodin: <b>'.$cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m</b>.</p></center>';
            }else{
                $html .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet odpracovaných hodin: <b>'.$cas_odpracovano_arr[0].'h0m</b>.</p></center>';
            }

            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
            return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'_smeny.pdf','UTF-8');
        }
       // $zamestnancovi_smeny = Shift::getEmployeeShifts();
    }

    public function generateEmployeeCurrentShifts(Request $request){
        $user = Auth::user();
        $html = '';
        if($request->vybrany_zamestnanec == -1){
            session()->flash('fail', 'Vyberte nějakého zaměstnance!');
            return redirect()->back();
        }else{
            $zamestnanec = Employee::find($request->vybrany_zamestnanec);
            $smeny = Shift::getEmployeeCurrentShifts($request->vybrany_zamestnanec);
            $html = '<h4 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam aktuálních směn zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</h4>
                    <table class="table center" style="font-family: DejaVu Sans;font-size: 13px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 30px;padding-top: 8px;">Začátek</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 30px;padding-top: 8px;">Konec</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 30px;padding-top: 8px;">Lokace</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 30px;padding-top: 8px;">Důležitost</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:14px;padding-right: 30px;padding-top: 8px;">Počet hodin</th>
                           </tr>
                      </thead>
                      <tbody>
                    ';
            $celkove = 0;
            foreach ($smeny as $smena){
                $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
                $shift_start = new DateTime( $smena->shift_start);
                $smena->shift_start = $shift_start->format('d.m.Y H:i');
                $shift_end = new DateTime( $smena->shift_end);
                $smena->shift_end = $shift_end->format('d.m.Y H:i');
                $hodinyRozdil = $shift_end->diff($shift_start);
                $pocetHodin = $hodinyRozdil->h;
                $pocetMinut = $hodinyRozdil->i;
                $celkove = $celkove + $pocetHodin + $pocetMinut/60;
                $html .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$smena->shift_start.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$smena->shift_end.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$smena->shift_place.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$dulezitost[0]->importance_description.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>';
            }
            $html .= '</tbody></table>';
            $cas_arr = explode(".", $celkove);
            if(sizeof($cas_arr) > 1){
                $cas_arr[1]= ($cas_arr[1]/100)*60;
                $html .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h'.$cas_arr[1].'m</b>.</p></center>';
            }else{
                $html .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h0m</b>.</p></center>';
            }
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
            return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'_aktualni_smeny.pdf','UTF-8');
        }

        // $zamestnancovi_smeny = Shift::getEmployeeShifts();
    }


    public function generateShiftEmployees(Request $request){
        $user = Auth::user();
        $html = '';
        if($request->vybrana_smena == -1){
            session()->flash('fail', 'Vyberte nějakou směnu!');
            return redirect()->back();
        }else {
            $zamestnanci = Employee::getShiftsEmployee($request->vybrana_smena);
            $smena = Shift::find($request->vybrana_smena);
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Směna od ' . $smena->shift_start . ' do: ' . $smena->shift_end . '</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 12px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Jméno</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Příjmení</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Telefon</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Email</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Pozice</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Přišel</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Status</th>
                           </tr>
                      </thead>
                      <tbody>
                    ';
            foreach ($zamestnanci as $zamestnanec) {

                $dochazka = DB::table('table_attendances')
                    ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
                    ->select('table_attendances.attendance_came','table_attendances.absence_reason_id')
                    ->where(['table_attendances.shift_id' => $request->vybrana_smena,'table_attendances.employee_id' => $zamestnanec->employee_id])
                    ->get();

                if($dochazka->isEmpty()){
                    $html .= '<tr>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_name . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_surname . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_phone . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->email . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_position . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Nezapsáno</p></td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Neznámý</p></td>
                             </tr>';

                }else{
                    $status = DB::table('table_absence_reasons')
                        ->select('table_absence_reasons.reason_description')
                        ->where(['table_absence_reasons.reason_id' => $dochazka[0]->absence_reason_id])
                        ->get();
                    $statView = "";
                    if($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL){
                        $statView = '<p>Neznámý</p>';
                    }else{
                        if($dochazka[0]->absence_reason_id == 5){
                            $statView = $status[0]->reason_description;
                        }else{
                            $statView = $status[0]->reason_description;
                        }
                    }
                    if($dochazka[0]->attendance_came == NULL || $dochazka[0]->attendance_came == 0){
                        $html .= '<tr>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_name . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_surname . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_phone . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->email . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_position . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Ne</p></td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>'.$statView.'</p></td>
                                 </tr>';
                    }else{
                        $html .= '<tr>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_name . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_surname . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_phone . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->email . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_position . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Ano</p></td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$statView.'</td>
                                 </tr>';
                    }
                }
            }
            $html .= '</tbody></table>';
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
            return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$smena->shift_start.' - '.$smena->shift_end .'_zamestnanci.pdf', 'UTF-8');
        }
    }

    public function generateEmployeesRatings(Request $request){
        $user = Auth::user();
        $html = '';
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Přehled hodnocení zaměstnanců firmy: ' . $user->company_name . '</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 12px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Jméno</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Příjmení</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Spolehlivost</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Absence</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Pracovitost</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Počet nepříchodů</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Celkově</th>
                           </tr>
                      </thead>
                      <tbody>
                    ';
        foreach ($zamestnanci as $zamestnanec){
            $pocetAbsenci = Attendance::getEmployeeAbsenceCount($zamestnanec->employee_id);
            $html .='<tr>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_name . '</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_surname . '</td>';
             if($zamestnanec->employee_reliability == NULL){
                 $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">Nezapsáno</td>';
             }else{
                 $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">'.$zamestnanec->employee_reliability.'</td>';
             }
            if($zamestnanec->employee_absence == NULL){
                $html .= ' <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">Nezapsáno</td>';
            }else{
                $html .= ' <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">'.$zamestnanec->employee_absence.'</td>';
            }
            if($zamestnanec->employee_workindex == NULL){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">Nezapsáno</td>';
            }else{
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">'.$zamestnanec->employee_workindex.'</td>';
            }
            $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">' . $pocetAbsenci . '</td>';
            if($zamestnanec->employee_overall == NULL){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Nezapsáno</p></td>';
            }else{
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>'.$zamestnanec->employee_overall.'</p></td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download('hodnoceni_zamestnanci.pdf', 'UTF-8');
    }

}
