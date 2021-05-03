<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Barryvdh\DomPDF\Facade as PDF;

class AdminFileGeneratorController extends Controller{
    /* Nazev souboru:  AdminFileGeneratorController.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi ke generovani souboru ve formatu PDF pro ucty s roli admina.
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
        Ucel: Zobrazeni prislusneho pohledu pro generovani souboru v ramci uctu s roli admina */
    public function index(){
        return view('admin_actions.file_generator');
    }

    /* Nazev funkce: generateCompaniesList
       Argumenty: zadne
       Ucel: Vygenerovani seznamu firem v souboru ve formatu PDF */
    public function generateCompaniesList(){

        /* Promenna, do ktere se ulozi obsah stranky, ktera bude nasledne vygenerovana jako PDF soubor */
        $out = '';

        /* Ziskani vsech firem */
        $firmy = Company::all();

        /* Pripraveni zahlavi tabulky a nadpisu v souboru*/
        $out = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam firem</h3>
                    <table class="table" style="font-family: DejaVu Sans;font-size: 12px;border-collapse: collapse;">
                         <thead>
                              <tr style="text-align: left;">
                                <th style="border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;font-size:13px;">Název</th>
                                <th style="border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;font-size:13px;">Jméno</th>
                                <th style="border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;font-size:13px;">Příjmení</th>
                                <th style="border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;font-size:13px;">Email</th>
                                <th style="border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:25px;font-size:13px;">Telefon</th>
                                <th style="border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:25px;font-size:13px;">IČO</th>
                                <th style="border-bottom: 1px solid black;padding-bottom: 8px;font-size:13px;">Adresa</th>
                               </tr>
                          </thead>
                          <tbody>';

        /* Iterace skrzy vsechny firmy a vytvareni jednotlivych radku tabulky podle udaju jednotlivych firem */
        foreach ($firmy as $firma){
            $out .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->company_name.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->company_user_name.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->company_user_surname.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->email.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:25px;">'.$firma->company_phone.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:25px;">'.$firma->company_ico.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;">'.$firma->company_street.', '.$firma->company_city.'</td>
                       </tr>';
        }
        $out .= '</tbody></table>'; //ukonceni tabulky

        /* Usek kodu slouzici pro samotne generovani souboru PDF
         Nacteni promenne, nastaveni formatu papiru a nasledne vygenerovani souboru PDF */
        return PDF::loadHTML($out)->setPaper('a4', 'portrait')->download('tozondo_firmy.pdf', 'UTF-8');
    }

}
