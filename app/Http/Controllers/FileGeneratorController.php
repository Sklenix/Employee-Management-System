<?php

namespace App\Http\Controllers;

use App\Models\AbsenceReason;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\ImportancesShifts;
use App\Models\Languages;
use App\Models\Shift;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FileGeneratorController extends Controller {
    /* Nazev souboru:  FileGeneratorController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi ke generovani souboru ve formatu PDF pro ucty s roli firmy.
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
        Ucel: Zobrazeni prislusneho pohledu pro generovani souboru v ramci uctu s roli firmy */
    public function index(){
        $user = Auth::user();
        /* Usek kodu, ktery slouzi k ziskani profilove fotky firmy, moznosti jazyku a moznosti dulezitosti smen.
         Bez tohoto useku kodu by nebylo mozne pridavat smeny a jazyky pres postranni panel a nebyla by zobrazena profilova fotka */
        $userJazyky = Languages::where('company_id', '=', $user->company_id)->get();
        $moznostiImportance = ImportancesShifts::getAllImportancesExceptUnspecified();
        /* Ziskani vsech zamestnancu, kteri obsadili minimalne jednu smenu */
        $zamestnanci = Employee::getCompanyEmployeesAssigned($user->company_id);
        /* Ziskani prirazenych smen */
        $smeny = Shift::getCompanyShiftsAssigned($user->company_id);
        /* Zmena formatu datumu a casu u smen */
        foreach ($smeny as $smena){
            $shift_start = new DateTime( $smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
        }
        /* Zaslani pohledu spolecne se ziskanymi daty uzivateli */
        return view('company_actions.file_generator')
            ->with('profilovka',$user->company_picture)
            ->with('company_url', $user->company_url)
            ->with('jazyky',$userJazyky)
            ->with('importances',$moznostiImportance)
            ->with('zamestnanci',$zamestnanci)
            ->with('smeny',$smeny);
    }

    /* Nazev funkce: generateEmployeesList
       Argumenty: zadne
       Ucel: Vygenerovani seznamu zamestnancu firmy */
    public function generateEmployeesList(){
        $user = Auth::user();
        /* Promenna, do ktere se ulozi generovany obsah stranky */
        $out = '';
        /* Ziskani zamestnancu firmy */
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        /* Priprava zahlavi tabulky a nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam zaměstnanců firmy: '.$user->company_name.'</h3>
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
                      <tbody>';
        /* Iterace skrze zamestnance */
        foreach ($zamestnanci as $zamestnanec){
            /* V kazde iteraci vytvoreni radku tabulky, kazdy radek reprezentuje udaje jednoho zamestnance */
            $out .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_name.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_surname.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_phone.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->email.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_position.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-right:5px;padding-bottom: 8px;">'.$zamestnanec->employee_street.', '.$zamestnanec->employee_city.'</td>
                      </tr>';
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download('zamestnanci.pdf','UTF-8');
    }

    /* Nazev funkce: generateShiftsList
       Argumenty: zadne
       Ucel: Vygenerovani seznamu smen firem */
    public function generateShiftsList(){
        $user = Auth::user();
        /* Ziskani smen firmy */
        $smeny = Shift::getCompanyShifts($user->company_id);
        /* Definice zahlavi tabulky a nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam historicky všech směn firmy: '.$user->company_name.'</h3>
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
                       <tbody>';
        /* Iterace skrze smeny */
        foreach ($smeny as $smena){
            /* Ziskani udaju ze smeny */
            $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
            $shift_start = new DateTime( $smena->shift_start);
            /* Zmena formatu datumu */
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime( $smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            /* Ziskani delky smeny v poctu hodin a minut */
            $hodinyRozdil = $shift_end->diff($shift_start);
            $pocetHodin = $hodinyRozdil->h;
            $pocetMinut = $hodinyRozdil->i;
            /* Zapsani ziskanych udaju do promenne out */
            $out .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_start.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_end.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_place.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$dulezitost[0]->importance_description.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                       </tr>';
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download('smeny.pdf','UTF-8');
    }

    /* Nazev funkce: generateCompanyProfile
       Argumenty: zadne
       Ucel: Vygenerovani udaju firmy */
    public function generateCompanyProfile(){
        $user = Auth::user();
        /* Promenna, do ktere se ulozi generovany obsah stranky */
        $out = '';
        /* Zmena formatu datumu */
        $created_at = new DateTime($user->created_at);
        $created_format = $created_at->format('d.m.Y H:i');
        $pocetZamestnancu = Employee::getCompanyEmployeesCount($user->company_id);
        $pocetSmen = Shift::getCompanyTotalShiftCount($user->company_id);
        /* Definice obsahu souboru a take nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Údaje firmy: '.$user->company_name.'</h3>
                  <center><div class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">
                               <p>Jméno zástupce: '.$user->company_user_name.'</p>
                               <p>Příjmení: '.$user->company_user_surname.'</p>
                               <p>Email: '.$user->email.'</p>
                               <p>Telefon: '.$user->company_phone.'</p>
                               <p>Login: '.$user->company_login.'</p>
                               <p>IČO: '.$user->company_ico.'</p>
                               <p>Adresa sídla: '.$user->company_street.', '.$user->company_city.'</p>
                               <p>Vytvořeno: '. $created_format.'</p>
                               <p>Počet zaměstnanců: '.$pocetZamestnancu.'</p>
                               <p>Počet směn celkově: '.$pocetSmen.'</p>
                            </div></center>';
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download('profil.pdf','UTF-8');
    }

    /* Nazev funkce: generateEmployeeShifts
       Argumenty: request - v teto promenne se nachazi vybrany zamestnanec pro realizaci generovani
       Ucel: Vygenerovani smen zamestnance */
    public function generateEmployeeShifts(Request $request){
        /* Promenna, do ktere se ulozi generovany obsah stranky */
        $out = '';
        /* Pokud uzivatel s roli firmy nevybral zadneho zamestnance */
        if($request->vybrany_zamestnanec == -1){
            session()->flash('fail', 'Vyberte nějakého zaměstnance!');
            return redirect()->back();
        }else{ // pokud vybral nejakeho zamestnance
            /* Ziskani konkretniho zamestnance */
            $zamestnanec = Employee::find($request->vybrany_zamestnanec);
            /* Ziskani smen zamestnance */
            $smeny = Shift::getEmployeeShifts($request->vybrany_zamestnanec);
            /* Definice zahlavi tabulky a nadpisu v souboru */
            $out = '<h4 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam všech směn zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</h4>
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
                      <tbody>';
            $celkove = 0;
            $celkove_odpracovano = 0;
            foreach ($smeny as $smena){
                /* Ziskani dochazky dane smeny zamestnance*/
                $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $request->vybrany_zamestnanec);
                /* Preformatovani datumu a ziskani dulezitosti smeny a nasledny vypocet delky smeny */
                $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
                $shift_start = new DateTime($smena->shift_start);
                $smena->shift_start = $shift_start->format('d.m.Y H:i');
                $shift_end = new DateTime($smena->shift_end);
                $smena->shift_end = $shift_end->format('d.m.Y H:i');
                $hodinyRozdil = $shift_end->diff($shift_start);
                $pocetHodin = $hodinyRozdil->h;
                $pocetMinut = $hodinyRozdil->i;
                $celkove = $celkove + $pocetHodin + $pocetMinut/60;
                /* Pokud dochazka neexistuje */
                if($dochazka->isEmpty()){
                    /* Vyplnovani tabulky */
                    $out .= '<tr>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_start.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_end.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_place.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$dulezitost[0]->importance_description.'</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p style="color:black;">Nezapsaný příchod/odchod</p></td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Neznámo</p></td>
                                <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Nezapsáno</p></td>
                             </tr>';
                }else{
                    $status = AbsenceReason::getParticularReason($dochazka[0]->absence_reason_id);
                    $statView = "";
                    /* Definice statusu */
                    if($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL){
                        $statView = '<p>Nezapsáno</p>';
                    }else{
                        if($dochazka[0]->absence_reason_id == 5){
                            $statView = '<p>'.$status[0]->reason_description.'</p>';
                        }else{
                            $statView = '<p>'.$status[0]->reason_description.'</p>';
                        }
                    }
                    /* Usek kodu zabyvajici se vypoctem odpracovanych hodin (primarne se pocita zapis prichodu a odchodu firmy)*/
                    $odpracovano = '';
                    if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                        if($dochazka[0]->attendance_check_in == NULL || $dochazka[0]->attendance_check_out == NULL){
                            $odpracovano = '<p style="color:black;">Nezapsaný příchod/odchod</p>';
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
                   /* Pokud zamestnanec neprisel */
                    if($dochazka[0]->attendance_came == NULL || $dochazka[0]->attendance_came == 0){
                        $out .= '<tr>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_start.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_end.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$smena->shift_place.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$dulezitost[0]->importance_description.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$odpracovano.'</td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"> <p>Ne</p></td>
                                    <td style="text-align: center;font-size:12px;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">'.$statView.'</td>
                                  </tr>';
                    }else{ // pokud zamestnanec prisel
                        $out .= '<tr>
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
            $out .= '</tbody></table><br>'; // ukonceni tabulky
            /* Sekce kodu tykajici se prevodem na hodiny a minuty oddelene (napr 8h20m)*/
            $cas_arr = explode(".", $celkove);
            /* Oddeleni retezce na zaklade desetinne tecky do pole */
            $cas_odpracovano_arr = explode(".", $celkove_odpracovano);
            if(sizeof($cas_arr) > 1){
                /* Pokud existuji minuty, tak jsou podeleny poctem desetinnych mist a nasledne vynasobeny 60 pro ziskani minut v celych cislech */
                $cas_arr[1] = substr( $cas_arr[1],0,2);
                $scale = 1;
                for($i = 0; $i < strlen($cas_arr[1]); $i++){
                    $scale *= 10;
                }
                $cas_arr[1]= round(($cas_arr[1]/$scale)*60,0);
                /* Zapsani poctu hodin do promenne out */
                $out .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h'.$cas_arr[1].'m</b>.</p></center>';
            }else{
                /* Zapsani poctu hodin do promenne out */
                $out .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h0m</b>.</p></center>';
            }
            /* Tento usek kodu funguje analogicky jako vyse napsany kod, akorat se tyka hodin odpracovanych */
            if(sizeof($cas_odpracovano_arr) > 1){
                $cas_odpracovano_arr[1] = substr($cas_odpracovano_arr[1],0,2);
                $scale = 1;
                for($i = 0; $i < strlen($cas_odpracovano_arr[1]); $i++){
                    $scale *= 10;
                }
                $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/$scale)*60,0);
                $out .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet odpracovaných hodin: <b>'.$cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m</b>.</p></center>';
            }else{
                $out .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet odpracovaných hodin: <b>'.$cas_odpracovano_arr[0].'h0m</b>.</p></center>';
            }
            /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
            return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'_smeny.pdf','UTF-8');
        }
    }

    /* Nazev funkce: generateEmployeeCurrentShifts
       Argumenty: request - v teto promenne se nachazi vybrany zamestnanec pro realizaci generovani
       Ucel: Vygenerovani aktualnich smen zamestnance */
    public function generateEmployeeCurrentShifts(Request $request){
        /* Promenna, do ktere se ulozi generovany obsah stranky */
        $out = '';
        /* Pokud uzivatel s roli firmy nevybral zadneho zamestnance */
        if($request->vybrany_zamestnanec == -1){
            session()->flash('fail', 'Vyberte nějakého zaměstnance!');
            return redirect()->back();
        }else{ // pokud vybral nejakeho zamestnance
            /* Ziskani konkretniho zamestnance */
            $zamestnanec = Employee::find($request->vybrany_zamestnanec);
            /* Ziskani aktualnich smen zamestnance */
            $smeny = Shift::getEmployeeCurrentShifts($request->vybrany_zamestnanec);
            /* Definice zahlavi tabulky a nadpisu v souboru */
            $out = '<h4 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam aktuálních směn zaměstnance: '.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'</h4>
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
                     <tbody>';
            $celkove = 0;
            /* Iterace skrze smeny */
            foreach ($smeny as $smena){
                /* Ziskani udaju o smene */
                $dulezitost = Shift::getCurrentImportanceShift($smena->shift_importance_id);
                $shift_start = new DateTime( $smena->shift_start);
                $smena->shift_start = $shift_start->format('d.m.Y H:i');
                $shift_end = new DateTime( $smena->shift_end);
                $smena->shift_end = $shift_end->format('d.m.Y H:i');
                $hodinyRozdil = $shift_end->diff($shift_start);
                $pocetHodin = $hodinyRozdil->h;
                $pocetMinut = $hodinyRozdil->i;
                $celkove = $celkove + $pocetHodin + $pocetMinut/60;
                /* Ulozeni udaju do promenne out */
                $out .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$smena->shift_start.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$smena->shift_end.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$smena->shift_place.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$dulezitost[0]->importance_description.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 30px;padding-top: 8px;">'.$pocetHodin.'h'.$pocetMinut.'m</td>
                          </tr>';
            }
            $out .= '</tbody></table>'; // ukonceni tabulky
            /* Oddeleni poctu hodin na zaklade desetinne carky */
            $cas_arr = explode(".", $celkove);
            /* Pokud existuji minuty */
            if(sizeof($cas_arr) > 1){
                $cas_arr[1] = substr($cas_arr[1],0,2);
                $scale = 1;
                for($i = 0; $i < strlen($cas_arr[1]); $i++){
                    $scale *= 10;
                }
                /* Vypocet minut */
                $cas_arr[1]= round(($cas_arr[1]/$scale)*60,0);
                $out .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h'.$cas_arr[1].'m</b>.</p></center>';
            }else{
                $out .= '<center><p class="text-center" style="font-family: DejaVu Sans;font-size: 13px;">Celkový počet hodin: <b>'.$cas_arr[0].'h0m</b>.</p></center>';
            }
            /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
            return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$zamestnanec->employee_name.' '.$zamestnanec->employee_surname.'_aktualni_smeny.pdf','UTF-8');
        }
    }

    /* Nazev funkce: generateShiftEmployees
       Argumenty: request - v teto promenne se nachazi vybrana smena
       Ucel: Vygenerovani zamestnancu na smene */
    public function generateShiftEmployees(Request $request){
        $out = '';
        /* Pokud nebyla vybrana zadna smena */
        if($request->vybrana_smena == -1){
            session()->flash('fail', 'Vyberte nějakou směnu!');
            return redirect()->back();
        }else { // pokud byla vybrana
            /* Ziskani zamestnancu na smene */
            $zamestnanci = Employee::getShiftsEmployee($request->vybrana_smena);
            /* Ziskani smeny */
            $smena = Shift::find($request->vybrana_smena);
            /* Zmena formatu datumu */
            $shift_start = new DateTime($smena->shift_start);
            $smena->shift_start = $shift_start->format('d.m.Y H:i');
            $shift_end = new DateTime($smena->shift_end);
            $smena->shift_end = $shift_end->format('d.m.Y H:i');
            /* Definice zahlavi tabulky a nadpisu v souboru */
            $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Směna od ' . $smena->shift_start . ' do: ' . $smena->shift_end . '</h3>
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
                       <tbody>';
            /* Iterace skrze zamestnance */
            foreach ($zamestnanci as $zamestnanec) {
                /* Ziskani dochazky konkretniho zamestnance na konkretni smene */
                $dochazka = Attendance::getEmployeeShiftParticularAttendance($request->vybrana_smena, $zamestnanec->employee_id);
                /* Pokud dochazka neexistuje */
                if($dochazka->isEmpty()){
                    $out .= '<tr>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_name . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_surname . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_phone . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->email . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_position . '</td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Neznámo</p></td>
                                <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Nezapsáno</p></td>
                             </tr>';
                }else{ // pokud existuje
                    $status = AbsenceReason::getParticularReason($dochazka[0]->absence_reason_id);
                    $statView = "";
                    /* Definice hodnoty statusu */
                    if($status->isEmpty() || $dochazka[0]->absence_reason_id == NULL){
                        $statView = '<p>Nezapsáno</p>';
                    }else{
                        if($dochazka[0]->absence_reason_id == 5){
                            $statView = $status[0]->reason_description;
                        }else{
                            $statView = $status[0]->reason_description;
                        }
                    }
                    /* Pokud zamestnanec neprisel */
                    if($dochazka[0]->attendance_came == NULL || $dochazka[0]->attendance_came == 0){
                        $out .= '<tr>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_name . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_surname . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_phone . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->email . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_position . '</td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Ne</p></td>
                                    <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>'.$statView.'</p></td>
                                 </tr>';
                    }else{ // pokud zamestnanec prisel
                        $out .= '<tr>
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
            $out .= '</tbody></table>'; // ukonceni tabulky
            /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
            return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download(''.$smena->shift_start.' - '.$smena->shift_end .'_zamestnanci.pdf', 'UTF-8');
        }
    }

    /* Nazev funkce: generateEmployeesRatings
       Argumenty: zadne
       Ucel: Vygenerovani hodnoceni zamestnancu */
    public function generateEmployeesRatings(){
        $user = Auth::user();
        /* Promenna, do ktere se ulozi generovany obsah stranky */
        $out = '';
        /* Ziskani zamestnancu firmy */
        $zamestnanci = Employee::getCompanyEmployees($user->company_id);
        /* Definice zahlavi tabulky a nadpisu v souboru */
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Přehled hodnocení zaměstnanců firmy: '.$user->company_name.'</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 12px;border-collapse: collapse;">
                     <thead>
                          <tr style="text-align: left;">
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Jméno</th>
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Příjmení</th>
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Spolehlivost</th>
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Dochvilnost</th>
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Pracovitost</th>
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Počet nepříchodů</th>
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;padding-right: 25px;">Celkově</th>
                           </tr>
                      </thead>
                      <tbody>';
        /* Iterace skrze zamestnance */
        foreach ($zamestnanci as $zamestnanec){
            /* Zjisteni poctu absenci zamestnance */
            $pocetAbsenci = Attendance::getEmployeeAbsenceCount($zamestnanec->employee_id);
            /* Zapsani jmeno a prijmeni zamestnance do promenne out */
            $out .='<tr>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_name . '</td>
                      <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">' . $zamestnanec->employee_surname . '</td>';
            /* Usek kodu slouzici pro zapis jednotlivych dilcich casti hodnoceni zamestnancu (spolehlivost, dochvilnost, pracovitost)*/
             if($zamestnanec->employee_reliability == NULL){
                 $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">Nezapsáno</td>';
             }else{
                 $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">'.$zamestnanec->employee_reliability.'</td>';
             }
            if($zamestnanec->employee_absence == NULL){
                $out .= ' <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">Nezapsáno</td>';
            }else{
                $out .= ' <td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">'.$zamestnanec->employee_absence.'</td>';
            }
            if($zamestnanec->employee_workindex == NULL){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">Nezapsáno</td>';
            }else{
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 25px;">'.$zamestnanec->employee_workindex.'</td>';
            }
            $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;">' . $pocetAbsenci . '</td>';
            if($zamestnanec->employee_overall == NULL){
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>Nezapsáno</p></td>';
            }else{
                $out .= '<td style="text-align: center;border-bottom: 1px solid black;padding-bottom: 8px;padding-right: 20px;"><p>'.$zamestnanec->employee_overall.'</p></td>';
            }
            $out .= '</tr>'; // ukonceni radku tabulky
        }
        $out .= '</tbody></table>'; // ukonceni tabulky
        /* Samotne vygenerovani souboru ve formatu PDF z promenne out */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download('hodnoceni_zamestnanci.pdf', 'UTF-8');
    }

}
