<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Disease;
use App\Models\Employee;
use App\Models\Employee_Language;
use App\Models\Employee_Shift;
use App\Models\Injury;
use App\Models\Report;
use App\Models\Report_Importance;
use App\Models\Shift;
use App\Models\Vacation;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeFileGeneratorController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('employee_actions.file_generator')
            ->with('profilovka',$user->employee_picture);
    }

    public function generateVacationsList(){
        $user = Auth::user();
        $html = '';
        $dovolene = Vacation::getEmployeeVacations($user->employee_id);
        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Dovolené zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 13px;margin-left:50px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Od</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Do</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Aktuálnost</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Stav</th>
                           </tr>
                      </thead>
                      <tbody>';

        foreach ($dovolene as $dovolena){
            $html .= '<tr>';
            $vacation_start_tmp = $dovolena->vacation_start;
            $vacation_end_tmp = $dovolena->vacation_end;
            $vacation_start = new DateTime($dovolena->vacation_start);
            $dovolena->vacation_start = $vacation_start->format('d.m.Y H:i');
            $vacation_end = new DateTime($dovolena->vacation_end);
            $dovolena->vacation_end = $vacation_end->format('d.m.Y H:i');

            $html .= '  <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$dovolena->vacation_start.'</td>
                        <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$dovolena->vacation_end.'</td>';

            if($dovolena->vacation_state == 0){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Nezažádáno</td>';
            }else if($dovolena->vacation_state == 1){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Odesláno</td>';
            }else if($dovolena->vacation_state == 2){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Schváleno</td>';
            }else if($dovolena->vacation_state == 3){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Neschváleno</td>';
            }

            $start = Carbon::createFromFormat('Y-m-d H:i:s', $vacation_start_tmp);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $vacation_end_tmp);
            $now = Carbon::now();
            $rozhod_start = $now->gte($start);
            $rozhod_end = $now->lte($end);
            $rozhod_end2 = $now->gte($end);

            if($rozhod_start == 1 && $rozhod_end == 1){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Probíhá</td>';
            }else if($rozhod_end2 == 1){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhla</td>';
            }else{
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhne</td>';
            }
            $html .='</tr>';
        }
        $html .= '</tbody></table>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_dovolene.pdf','UTF-8');

    }

    public function generateDiseasesList(){
        $user = Auth::user();
        $html = '';
        $nemocenske = Disease::getEmployeeDiseases($user->employee_id);
        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Nemocenské zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 13px;margin-left:30px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Název</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Od</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Do</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Aktuálnost</th>
                            <th style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Stav</th>
                           </tr>
                      </thead>
                      <tbody>';

        foreach ($nemocenske as $nemocenska){
            $html .= '<tr>';
            $nemocenska_from_tmp = $nemocenska->disease_from;
            $nemocenska_to_tmp = $nemocenska->disease_to;
            $nemocenska_from = new DateTime($nemocenska->disease_from);
            $nemocenska->disease_from = $nemocenska_from->format('d.m.Y H:i');
            $nemocenska_to = new DateTime($nemocenska->disease_to);
            $nemocenska->disease_to = $nemocenska_to->format('d.m.Y H:i');

            $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nemocenska->disease_name.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nemocenska->disease_from.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nemocenska->disease_to.'</td>';

            if($nemocenska->disease_state == 0){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Nezažádáno</td>';
            }else if($nemocenska->disease_state == 1){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Odesláno</td>';
            }else if($nemocenska->disease_state == 2){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Schváleno</td>';
            }else if($nemocenska->disease_state == 3){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Neschváleno</td>';
            }

            $start = Carbon::createFromFormat('Y-m-d H:i:s', $nemocenska_from_tmp);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $nemocenska_to_tmp);
            $now = Carbon::now();
            $rozhod_start = $now->gte($start);
            $rozhod_end = $now->lte($end);
            $rozhod_end2 = $now->gte($end);

            if($rozhod_start == 1 && $rozhod_end == 1){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Probíhá</td>';
            }else if($rozhod_end2 == 1){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhla</td>';
            }else{
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhne</td>';
            }
            $html .='</tr>';
        }
        $html .= '</tbody></table>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_nemocenske.pdf','UTF-8');

    }

    public function generateReportsList(){
        $user = Auth::user();
        $html = '';
        $nahlaseni = Report::getEmployeeReports($user->employee_id);
        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Nahlášení zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 12px;padding-right:50px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Název</th>
                            <th width="55%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Popis</th>
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Důležitost</th>
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:14px;">Stav</th>
                           </tr>
                      </thead>
                      <tbody>';

        foreach ($nahlaseni as $nahlas){
            $html .= '<tr>';
            $dulezitost = Report_Importance::getConcreteImportance($nahlas->report_importance_id);
            $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nahlas->report_title.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nahlas->report_description.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$dulezitost->importance_report_description.'</td>';

            if($nahlas->report_state == 0){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Nezažádáno</td>';
            }else if($nahlas->report_state == 1){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Odesláno</td>';
            }else if($nahlas->report_state == 2){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Schváleno</td>';
            }else if($nahlas->report_state == 3){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Neschváleno</td>';
            }

            $html .='</tr>';
        }
        $html .= '</tbody></table>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_nemocenske.pdf','UTF-8');
    }

    public function generateEmployeeProfile(){
        $user = Auth::user();
        $html = '';
        $vytvoren = new DateTime($user->created_at);
        $shift_format = $vytvoren->format('d.m.Y H:i');
        $pocetSmen = Shift::getEmployeeShiftsCount($user->employee_id);
        $pocetAbsenci = Attendance::getEmployeeAbsenceCount($user->employee_id);
        $pocetDovolenych = Vacation::getEmployeeVacationsCount($user->employee_id);
        $pocetNemoci = Disease::getEmployeeDiseasesCount($user->employee_id);
        $pocetNahlaseni = Report::getEmployeeReportsCount($user->employee_id);
        $pocetZraneni = Injury::getEmployeeInjuriesInjuryCentreCount($user->employee_id);
        $jazyky = Employee_Language::getEmployeeLanguages($user->employee_id);
        if($jazyky->isEmpty()){
            $jazyky_html = '<p>Ovládané jazyky: žádné</p>';
        }else{
            $jazyky_html = '<p>Ovládané jazyky: ';
            $i = 0;
            foreach ($jazyky as $jazyk){
                if($i == count($jazyky) - 1){
                    $jazyky_html .= $jazyk->language_name.'.';
                }else{
                    $jazyky_html .= $jazyk->language_name.', ';
                }
                $i++;
            }
            $jazyky_html .= '</p>';
        }
        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Údaje zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
                  <center><div class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">
                       <p>Email: '.$user->email.'</p>
                       <p>Telefon: '.$user->employee_phone.'</p>
                       <p>Pozice: '.$user->employee_position.'</p>
                       <p>Login: '.$user->employee_login.'</p>
                       <p>Adresa bydliště: '.$user->employee_street.', '.$user->employee_city.'</p>
                       <p>Celkový počet směn: '.$pocetSmen.'</p>
                       <p>Celkový počet absencí: '.$pocetAbsenci.'</p>
                       <p>Počet dovolených: '.$pocetDovolenych.'</p>
                       <p>Počet nemocenských: '.$pocetNemoci.'</p>
                       <p>Počet nahlášení: '.$pocetNahlaseni.'</p>
                       <p>Počet zranění: '.$pocetZraneni.'</p>
                       '.$jazyky_html.'
                       <p>Profil vytvořen: '. $shift_format.'</p>
                   </div></center>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_profil.pdf','UTF-8');
    }

    public function generateCurrentShiftsList(Request $request){
        $user = Auth::user();
        $html = '';
        $smeny = Employee_Shift::getEmployeeCurrentShiftsWithAttendance($user->employee_id);
        $html = '<html style="margin:0;padding:0;">
                <body>
                <h4 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam směn aktuálního týdne zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h4>
                <table class="table center" style="font-family: DejaVu Sans;font-size: 12px;border-collapse: collapse;">
                 <thead>
                      <tr style="text-align: left;">
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right:25px;">Začátek</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right:25px;">Konec</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right:25px;">Lokace</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Důležitost</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Příchod</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Odchod</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Status</th>
                       </tr>
                  </thead>
                  <tbody>
                ';
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
            $html .= '<tr>';
            $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_start.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_end.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_place.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$dulezitost[0]->importance_description.'</td>';

            $checkin = Attendance::getEmployeeCheckIn($smena->shift_id,$user->employee_id);

            if($checkin->isEmpty()){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkin[0]->attendance_check_in === NULL){
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkin = new DateTime($checkin[0]->attendance_check_in);
                    $checkin = $checkin->format('d.m.Y H:i');
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkin.'</td>';
                }
            }

            $checkout = Attendance::getEmployeeCheckOut($smena->shift_id,$user->employee_id);

            if($checkout->isEmpty()){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkout[0]->attendance_check_out === NULL){
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkout = new DateTime($checkout[0]->attendance_check_out);
                    $checkout = $checkout->format('d.m.Y H:i');
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkout.'</td>';
                }
            }

            $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($smena->shift_id, $user->employee_id);

            if($aktualniAbsence->isEmpty()){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
            }else{
                if($aktualniAbsence[0]->reason_description === NULL){
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
                }else{
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$aktualniAbsence[0]->reason_description.'</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></body></html>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$user->employee_name.' '.$user->employee_surname.'_rozvrh_aktualni.pdf','UTF-8');
    }

    public function generateShiftHistoryList(Request $request){
        $user = Auth::user();
        $html = '';
        $smeny = Employee_Shift::getEmployeeAllShiftsWithAttendance($user->employee_id);
        $html = '<html style="margin:0;padding:0;">
                <body>
                <h4 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam směn aktuálního týdne zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h4>
                <table class="table center" style="font-family: DejaVu Sans;font-size: 12px;padding: 0;margin: 0;border-collapse: collapse;">
                 <thead>
                      <tr style="text-align: left;">
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right:25px;">Začátek</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right:25px;">Konec</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right:25px;">Lokace</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Důležitost</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Příchod</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Odchod</th>
                        <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Status</th>
                       </tr>
                  </thead>
                  <tbody>
                ';
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
            $html .= '<tr>';
            $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_start.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_end.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_place.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$dulezitost[0]->importance_description.'</td>';

            $checkin = Attendance::getEmployeeCheckIn($smena->shift_id,$user->employee_id);

            if($checkin->isEmpty()){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkin[0]->attendance_check_in === NULL){
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkin = new DateTime($checkin[0]->attendance_check_in);
                    $checkin = $checkin->format('d.m.Y H:i');
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkin.'</td>';
                }
            }

            $checkout = Attendance::getEmployeeCheckOut($smena->shift_id,$user->employee_id);

            if($checkout->isEmpty()){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkout[0]->attendance_check_out === NULL){
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkout = new DateTime($checkout[0]->attendance_check_out);
                    $checkout = $checkout->format('d.m.Y H:i');
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkout.'</td>';
                }
            }

            $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($smena->shift_id, $user->employee_id);

            if($aktualniAbsence->isEmpty()){
                $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
            }else{
                if($aktualniAbsence[0]->reason_description === NULL){
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
                }else{
                    $html .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$aktualniAbsence[0]->reason_description.'</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></body></html>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download(''.$user->employee_name.' '.$user->employee_surname.'_historie_smen.pdf','UTF-8');
    }


}
