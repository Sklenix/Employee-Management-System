<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFileGeneratorController extends Controller
{
    public function index(){
        return view('admin_actions.file_generator');
    }

    public function generateCompaniesList(){
        $html = '';
        $firmy = Company::all();
        $html = '<h3 style="font-family: DejaVu Sans;text-align: center;border-collapse: collapse;margin-bottom: 20px;">Seznam firem</h3>
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
                      <tbody>
                    ';
        foreach ($firmy as $firma){
            $html .= '<tr>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->company_name.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->company_user_name.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->company_user_surname.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:15px;">'.$firma->email.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:25px;">'.$firma->company_phone.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;padding-right:25px;">'.$firma->company_ico.'</td>
                            <td style="text-align: center;border-bottom: 1px solid black;padding-top: 8px;padding-bottom: 8px;">'.$firma->company_street.', '.$firma->company_city.'</td>
                       </tr>';
        }
        $html .= '</tbody></table>';
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download('tozondo_firmy.pdf','UTF-8');
    }

}
