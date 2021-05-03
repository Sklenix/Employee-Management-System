<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Disease;
use App\Models\Employee_Language;
use App\Models\Injury;
use App\Models\Report;
use App\Models\Report_Importance;
use App\Models\Shift;
use App\Models\Vacation;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;

class EmployeeFileGeneratorController extends Controller {
    /* Nazev souboru:  EmployeeFileGeneratorController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi ke generovani souboru ve formatu PDF pro ucty s roli zamestnance.
    Pro generovani souboru ve formatu PDF byla pouzita knihovna DOMPDF Wrapper for Laravel: https://github.com/barryvdh/laravel-dompdf, ktera je poskytovana s MIT licenci, ktera je zapsana nize

    Copyright 2021 DOMPDF Wrapper for Laravel

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
    and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
    OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
    */

    /* Nazev funkce: index
        Argumenty: zadne
        Ucel: Zobrazeni prislusneho pohledu pro generovani souboru v ramci uctu s roli zamestnance */
    public function index(){
        $user = Auth::user();
        return view('employee_actions.file_generator')
            ->with('profilovka',$user->employee_picture)->with('employee_url', $user->employee_url);
    }

    /* Nazev funkce: generateVacationsList
       Argumenty: zadne
       Ucel: Vygenerovani seznamu dovolenych zamestnance */
    public function generateVacationsList(){
        $user = Auth::user();
        /* Promenna, do ktere se ulozi generovany obsah stranky */
        $out = '';
        /* Ziskani dovolenych zamestnance*/
        $dovolene = Vacation::getEmployeeVacations($user->employee_id);
        /* Priprava zahlavi tabulky a nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Dovolené zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
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
        /* Iterace skrz dovolene */
        foreach ($dovolene as $dovolena){
            $out .= '<tr>';
            /* Ziskani udaju o dovolene */
            $vacation_start_tmp = $dovolena->vacation_start;
            $vacation_end_tmp = $dovolena->vacation_end;
            $vacation_start = new DateTime($dovolena->vacation_start);
            $dovolena->vacation_start = $vacation_start->format('d.m.Y H:i');
            $vacation_end = new DateTime($dovolena->vacation_end);
            $dovolena->vacation_end = $vacation_end->format('d.m.Y H:i');

            /* Zapsani udaju o dovolene do HTML jako radky tabulky */
            $out .= '  <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$dovolena->vacation_start.'</td>
                        <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$dovolena->vacation_end.'</td>';
            if($dovolena->vacation_state == 0){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Nezažádáno</td>';
            }else if($dovolena->vacation_state == 1){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Odesláno</td>';
            }else if($dovolena->vacation_state == 2){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Schváleno</td>';
            }else if($dovolena->vacation_state == 3){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Neschváleno</td>';
            }
            /* Usek kodu slouzici pro zapsani aktualnosti dovolene do promenne ve formatu HTML */
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $vacation_start_tmp);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $vacation_end_tmp);
            $now = Carbon::now();
            $rozhod_start = $now->gte($start);
            $rozhod_end = $now->lte($end);
            $rozhod_end2 = $now->gte($end);
            if($rozhod_start == 1 && $rozhod_end == 1){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Probíhá</td>';
            }else if($rozhod_end2 == 1){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhla</td>';
            }else{
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhne</td>';
            }
            $out .='</tr>'; // ukonceni radku tabulky
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_dovolene.pdf','UTF-8');
    }

    /* Nazev funkce: generateDiseasesList
     Argumenty: zadne
     Ucel: Vygenerovani seznamu nemocenskych zamestnance */
    public function generateDiseasesList(){
        $user = Auth::user();
        /* Promenna, do ktere se ulozi generovany obsah stranky */
        $out = '';
        /* Ziskani nemocenskych zamestnance */
        $nemocenske = Disease::getEmployeeDiseases($user->employee_id);
        /* Definice zahlavi tabulky a nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Nemocenské zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
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
        /* Iterace skrze nemocenske zamestnance */
        foreach ($nemocenske as $nemocenska){
            $out .= '<tr>';
            /* Ziskani udaju o nemocenske */
            $nemocenska_from_tmp = $nemocenska->disease_from;
            $nemocenska_to_tmp = $nemocenska->disease_to;
            $nemocenska_from = new DateTime($nemocenska->disease_from);
            $nemocenska->disease_from = $nemocenska_from->format('d.m.Y H:i');
            $nemocenska_to = new DateTime($nemocenska->disease_to);
            $nemocenska->disease_to = $nemocenska_to->format('d.m.Y H:i');
            /* Ulozeni udaju o nemocenske do tabulky v podobe radku */
            $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nemocenska->disease_name.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nemocenska->disease_from.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nemocenska->disease_to.'</td>';
            if($nemocenska->disease_state == 0){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Nezažádáno</td>';
            }else if($nemocenska->disease_state == 1){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Odesláno</td>';
            }else if($nemocenska->disease_state == 2){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Schváleno</td>';
            }else if($nemocenska->disease_state == 3){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Neschváleno</td>';
            }
            /* Ulozeni aktualnosti nemocenske do promenne out */
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $nemocenska_from_tmp);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $nemocenska_to_tmp);
            $now = Carbon::now();
            $rozhod_start = $now->gte($start);
            $rozhod_end = $now->lte($end);
            $rozhod_end2 = $now->gte($end);

            if($rozhod_start == 1 && $rozhod_end == 1){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Probíhá</td>';
            }else if($rozhod_end2 == 1){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhla</td>';
            }else{
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 12px;padding-right:50px;padding-top: 12px;">Proběhne</td>';
            }
            $out .='</tr>'; // ukonceni radku tabulky
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_nemocenske.pdf','UTF-8');

    }

    /* Nazev funkce: generateInjuriesList
       Argumenty: zadne
       Ucel: Vygenerovani seznamu zraneni zamestnance */
    public function generateInjuriesList(){
        $user = Auth::user();
        $out = '';
        /* Ziskani zraneni zamestnance */
        $zraneni = Injury::getEmployeeInjuriesInjuryCentre($user->employee_id);
        /* Definice zahlavi tabulky a nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Nahlášení zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 12px;padding-right:50px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:13px;">Popis zranění</th>
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:13px;">Datum zranění</th>
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:13px;">Začátek směny</th>
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:13px;">Konec směny</th>
                            <th width="15%" style="border-bottom: 1px solid black;padding-bottom: 7px;padding-right:50px;font-size:13px;">Lokace</th>
                           </tr>
                      </thead>
                      <tbody>';
        /* Iterace skrze zraneni */
        foreach ($zraneni as $zran){
            /* Ulozeni udaju o zraneni jako radek tabulky*/
            $out .= '<tr>';
            $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$zran->injury_description.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$zran->injury_date.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$zran->shift_start.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$zran->shift_end.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$zran->shift_place.'</td>';
            $out .='</tr>'; // ukonceni radku tabulky
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_zraneni.pdf','UTF-8');
    }

    /* Nazev funkce: generateReportsList
       Argumenty: zadne
       Ucel: Vygenerovani seznamu nahlaseni zamestnance */
    public function generateReportsList(){
        $user = Auth::user();
        $out = '';
        /* Ziskani konkretnich nahlaseni */
        $nahlaseni = Report::getEmployeeReports($user->employee_id);
        /* Definice zahlavi tabulky a nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Nahlášení zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
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
        /* Iterace skrze nahlaseni */
        foreach ($nahlaseni as $nahlas){
            $out .= '<tr>';
            /* Zapis udaju o nahlaseni do promenne out */
            $dulezitost = Report_Importance::getConcreteImportance($nahlas->report_importance_id);
            $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nahlas->report_title.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$nahlas->report_description.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">'.$dulezitost->importance_report_description.'</td>';
            if($nahlas->report_state == 0){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Nezažádáno</td>';
            }else if($nahlas->report_state == 1){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Odesláno</td>';
            }else if($nahlas->report_state == 2){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Schváleno</td>';
            }else if($nahlas->report_state == 3){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:50px;padding-bottom: 12px;padding-top: 12px;">Neschváleno</td>';
            }
            $out .='</tr>'; // ukonceni radku tabulky
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'nahlaseni.pdf','UTF-8');
    }

    /* Nazev funkce: generateEmployeeProfile
       Argumenty: zadne
       Ucel: Vygenerovani udaju z profilu zamestnance */
    public function generateEmployeeProfile(){
        $user = Auth::user();
        $out = '';
        /* Ziskani jednotlivych udaju */
        $vytvoren = new DateTime($user->created_at);
        $vytvoren_spravny_format = $vytvoren->format('d.m.Y H:i'); // zmena formatu datumu
        $pocetSmen = Shift::getEmployeeShiftsCount($user->employee_id);
        $pocetAbsenci = Attendance::getEmployeeAbsenceCount($user->employee_id);
        $pocetDovolenych = Vacation::getEmployeeVacationsCount($user->employee_id);
        $pocetNemoci = Disease::getEmployeeDiseasesCount($user->employee_id);
        $pocetNahlaseni = Report::getEmployeeReportsCount($user->employee_id);
        $pocetZraneni = Injury::getEmployeeInjuriesInjuryCentreCount($user->employee_id);
        /* Ziskani jazyku, ktere zamestnanec ovlada */
        $jazyky = Employee_Language::getEmployeeLanguages($user->employee_id);
        if($jazyky->isEmpty()){
            $jazyky_html = '<p>Ovládané jazyky: žádné</p>';
        }else{
            $jazyky_html = '<p>Ovládané jazyky: ';
            $i = 0;
            foreach ($jazyky as $jazyk){ // iterace skrze jazyky
                if($i == count($jazyky) - 1){
                    $jazyky_html .= $jazyk->language_name.'.';
                }else{
                    $jazyky_html .= $jazyk->language_name.', ';
                }
                $i++;
            }
            $jazyky_html .= '</p>';
        }
        /* Ulozeni udaju zamestnance do promenne out */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Údaje zaměstnance: '.$user->employee_name.' '.$user->employee_surname.'</h3>
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
                       <p>Profil vytvořen: '.$vytvoren_spravny_format.'</p>
                   </div></center>';
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$user->employee_name.'_'.$user->employee_surname.'_profil.pdf','UTF-8');
    }

    /* Nazev funkce: generateCurrentShiftsList
       Argumenty: zadne
       Ucel: Vygenerovani aktualnich smen zamestnance */
    public function generateCurrentShiftsList(){
        $user = Auth::user();
        $out = '';
        /* Ziskani aktualnich smen zamestnance */
        $smeny = Shift::getEmployeeCurrentShifts($user->employee_id);
        /* Definice zahlavi tabulky a nadpisu v souboru */
        $out = '<html style="margin:0;padding:0;">
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
                  <tbody>';
        /* Iterace skrze smeny */
        foreach ($smeny as $smena){
            /* Ziskani udaju o smene */
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
            /* Zapis udaju smeny do promenne out */
            $out .= '<tr>';
            $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_start.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_end.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_place.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$dulezitost[0]->importance_description.'</td>';
            $checkin = Attendance::getEmployeeCheckIn($smena->shift_id,$user->employee_id);
            if($checkin->isEmpty()){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkin[0]->attendance_check_in === NULL){
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkin = new DateTime($checkin[0]->attendance_check_in);
                    $checkin = $checkin->format('d.m.Y H:i');
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkin.'</td>';
                }
            }
            $checkout = Attendance::getEmployeeCheckOut($smena->shift_id,$user->employee_id);
            if($checkout->isEmpty()){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkout[0]->attendance_check_out === NULL){
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkout = new DateTime($checkout[0]->attendance_check_out);
                    $checkout = $checkout->format('d.m.Y H:i');
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkout.'</td>';
                }
            }
            $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($smena->shift_id, $user->employee_id);
            if($aktualniAbsence->isEmpty()){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
            }else{
                if($aktualniAbsence[0]->reason_description === NULL){
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
                }else{
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$aktualniAbsence[0]->reason_description.'</td>';
                }
            }
            $out .= '</tr>'; // ukonceni radku tabulky
        }
        $out .= '</tbody></table></body></html>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$user->employee_name.' '.$user->employee_surname.'_rozvrh_aktualni.pdf','UTF-8');
    }

    /* Nazev funkce: generateShiftHistoryList
      Argumenty: zadne
      Ucel: Vygenerovani vsech smen zamestnance */
    public function generateShiftHistoryList(){
        $user = Auth::user();
        $out = '';
        /* Ziskani smen zamestnance */
        $smeny =  Shift::getEmployeeShifts($user->employee_id);
        /* Definice zahlavi tabulky a nadpisu v souboru */
        $out = '<html style="margin:0;padding:0;">
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
                  <tbody>';
        /* Iterace skrze smeny */
        foreach ($smeny as $smena){
            /* Ziskani udaju o smene */
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
            /* Zapsani udaju o smene do promenne out */
            $out .= '<tr>';
            $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_start.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_end.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$smena->shift_place.'</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$dulezitost[0]->importance_description.'</td>';
            $checkin = Attendance::getEmployeeCheckIn($smena->shift_id,$user->employee_id);
            if($checkin->isEmpty()){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkin[0]->attendance_check_in === NULL){
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkin = new DateTime($checkin[0]->attendance_check_in);
                    $checkin = $checkin->format('d.m.Y H:i');
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkin.'</td>';
                }
            }
            $checkout = Attendance::getEmployeeCheckOut($smena->shift_id,$user->employee_id);
            if($checkout->isEmpty()){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
            }else{
                if($checkout[0]->attendance_check_out === NULL){
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Nezapsáno</td>';
                }else{
                    $checkout = new DateTime($checkout[0]->attendance_check_out);
                    $checkout = $checkout->format('d.m.Y H:i');
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$checkout.'</td>';
                }
            }
            $aktualniAbsence = Attendance::getEmployeeCurrentShiftAbsenceStatus($smena->shift_id, $user->employee_id);
            if($aktualniAbsence->isEmpty()){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
            }else{
                if($aktualniAbsence[0]->reason_description === NULL){
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">Čekající</td>';
                }else{
                    $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-right:25px;padding-bottom: 12px;padding-top: 12px;">'.$aktualniAbsence[0]->reason_description.'</td>';
                }
            }
            $out .= '</tr>'; // ukonceni radku tabulky
        }
        $out .= '</tbody></table></body></html>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$user->employee_name.' '.$user->employee_surname.'_historie_smen.pdf','UTF-8');
    }

}
