<!doctype html>
<html lang="cs">
<head>
    <!-- Nazev souboru: company_dashboard.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje cele menu (vcetne postranniho panelu) a take element, do ktereho se nasledne vkladaji jednotlive moznosti domovske stranky. Tento soubor
     take obsahuje modalni okna pro manipulaci s Google Drive, pridani a odebrani zamestnance a pridani a odebrani smeny. Tento soubor se vaze na roli firmy -->
    <!-- layout je postaven na zaklade https://startbootstrap.com/template/simple-sidebar sablony, ktera byla upravena pro ucely tohoto informacniho systemu.
    Licence:
    The MIT License (MIT)

    Copyright (c) 2013-2021 Start Bootstrap LLC

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
    -->
    <!-- definice metadat -->
    <meta name="description" content="Tozondo - Systém pro rychlou a efektivní správu Vašich zaměstnanců.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="system, zamestnanci, sprava zamestnancu">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="googlebot" content="index, follow"/>
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Pavel">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- odkazy na favicony -->
    <link rel="icon" href="{{ asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>
    <!-- import fontu -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <title>Tozondo @yield('title')</title>
    <!-- import kaskadovych stylu -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styly.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.15/jquery.datetimepicker.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.15/jquery.datetimepicker.min.css" rel="stylesheet">
    <!-- import datovych tabulek, jquery, javascriptu, chart.js, chart.js datalabels plugin, datetimepicker, moment a modernizr -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/0.7.0/chartjs-plugin-datalabels.js" integrity="sha512-yvu1r8RRJ0EHKpe1K3pfHF7ntjnDrN7Z66hVVGB90CvWbWTXevVZ8Sy1p+X4sS9M9Z+Q9tZu1GjGzFFmu/pAmg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.15/jquery.datetimepicker.full.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; }
    </style>
</head>
<body>
<div class="d-flex" id="obsah">
    <div class="efektMenu" id="postranniPanel" style="background:rgba(0,0,0,0.85);">
        <div class="sekceLogo"><a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;color:rgba(255, 255, 255, 0.95);"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" alt="Logo"/> | Tozondo</a>
            <hr class="caraPodNazvem">
        </div>
        <!-- Definice jednotlivych polozek postranniho panelu, paklize se uzivatel nachazi v nejake polozce, tak je vybarvena cervene, viz https://laravel.com/docs/8.x/requests -->
        <div class="list-group">
            <a href="{{route('home')}}" class="odkaz border-bottom {{ request()->routeIs('home') ? 'active' : '' }}" style="padding-left:60px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 20px;padding-top: 20px;font-size:17px;"><i class="fa fa-cube"></i> Dashboard</a>
            <!-- Definice rozbalovaci nabidky v ramci zamestnancu -->
            <a href="#zamestnanciDropdown" data-toggle="collapse" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-user"></i> Zaměstnanci <i style="margin-left: 15px;" class="fa fa-caret-down"></i></a>
            <ul class="collapse list-unstyled" id="zamestnanciDropdown" style="margin-bottom:0px;">
                <a data-toggle="modal" data-target="#formAddEmployee" >
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-user-plus"></i> Vytvořit zaměstnance
                    </li>
                </a>
                <a data-toggle="modal" data-target="#formDeleteEmployee" id="getDeleteEmployeeData">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-user-times"></i> Odstranit zaměstnance
                    </li>
                </a>
                <a href="{{route('ratings.index')}}" style="text-decoration: none;color:rgba(255, 255, 255, 0.95);">
                    <li class="{{ request()->routeIs('ratings.index') ? 'active' : '' }} hoverList" style="padding-left:30px;text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-th-large"></i> Hodnocení zaměstnanců
                    </li>
                </a>
            </ul>
            <a href="{{route('employees.index')}}" class="border-bottom odkaz {{ request()->routeIs('employees.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);padding-top: 16px;padding-bottom: 16px;font-size: 16px;text-decoration: none;color:rgba(255, 255, 255, 0.95);"><i class="fa fa-list"></i> Seznam zaměstnanců</a>
            <!-- Definice rozbalovaci nabidky v ramci smen -->
            <a href="#smenyDropdown" data-toggle="collapse" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-clock-o"></i> Směny <i style="margin-left: 15px;" class="fa fa-caret-down"></i></a>
            <ul class="collapse list-unstyled" id="smenyDropdown" style="margin-bottom:0px;">
                <a data-toggle="modal" data-target="#formAddShift">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                       <i class="fa fa-plus-square-o"></i> Vytvořit směnu
                    </li>
                </a>
                <a data-toggle="modal" id="getDeleteShiftData" data-target="#formDeleteShift">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-minus-square-o"></i> Odstranit směny
                    </li>
                </a>
            </ul>
            <a href="{{route('shifts.index')}}" class="odkaz {{ request()->routeIs('shifts.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-list"></i> Seznam směn</a>
            <a href="{{route('attendance.index')}}" class="odkaz border-bottom {{ request()->routeIs('attendance.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-address-card-o"></i> Docházka</a>
            @if($company_url != "") <!-- Pokud si firma aktivovala Google Drive, tak ji nejsou zobrazeni moznosti Google Drive -->
                <!-- Definice rozbalovaci nabidky v ramci Google Drive -->
                <a href="#googleDriveDropdown" data-toggle="collapse" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-server"></i> Google Drive <i style="margin-left: 15px;" class="fa fa-caret-down"></i></a>
                <ul class="collapse list-unstyled" id="googleDriveDropdown" style="margin-bottom:0px;">
                    <a data-toggle="modal" data-target="#formAddFolder">
                        <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                            <i class="fa fa-plus-square-o"></i> Vytvořit složku
                        </li>
                    </a>
                    <a data-toggle="modal" id="getDeleteFileDataCheckBox" data-target="#formDeleteFile">
                        <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                            <i class="fa fa-times"></i> Odstranit soubory
                        </li>
                    </a>
                    <a data-toggle="modal" id="getUploadFileDataOptions" data-target="#formUpload">
                        <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                            <i class="fa fa-upload"></i> Nahrání souboru
                        </li>
                    </a>
                    <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->company_url }}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;" target="_blank">
                        <li style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                           <i class="fa fa-eye"></i> Zobrazit Google Drive
                        </li>
                    </a>
                </ul>
            @endif
            <!-- Definice rozbalovaci nabidky v ramci jazyku -->
            <a href="#languageDropdown" data-toggle="collapse" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-language"></i> Jazyky <i style="margin-left: 15px;" class="fa fa-caret-down"></i></a>
            <ul class="collapse list-unstyled" id="languageDropdown" style="margin-bottom:0px;">
                <a data-toggle="modal" data-target="#formAddLanguage">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                       <i class="fa fa-plus-square-o"></i> Vytvořit jazyk
                    </li>
                </a>
                <a data-toggle="modal" data-target="#formDeleteLanguage">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-minus-square-o"></i> Odstranit jazyky
                    </li>
                </a>
            </ul>
            <!-- Definice rozbalovaci nabidky v ramci center -->
            <a href="#centresDropdown" data-toggle="collapse" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-university"></i> Centra <i style="margin-left: 15px;" class="fa fa-caret-down"></i></a>
            <ul class="collapse list-unstyled" id="centresDropdown" style="margin-bottom:0px;">
                <a href="{{route('injuries.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('injuries.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                       <i class="fa fa-heartbeat"></i> Centrum zranění
                    </li>
                </a>
                <a href="{{route('vacations.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('vacations.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-sun-o"></i> Centrum dovolených
                    </li>
                </a>
                <a href="{{route('diseases.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('diseases.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-medkit"></i> Centrum nemocenských
                    </li>
                </a>
                <a href="{{route('reports.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('reports.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-list-alt"></i> Centrum nahlášení
                    </li>
                </a>
            </ul>
            <a href="{{route('statistics.index')}}" class="odkaz {{ request()->routeIs('statistics.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-pie-chart"></i> Statistiky</a>
            <a href="{{route('generator.index')}}" class="odkaz {{ request()->routeIs('generator.index') ? 'active' : '' }} border-bottom" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-rocket"></i> Generátor souborů</a>
        </div>
    </div>
    <!-- Definice rozklikavaciho menu vpravo nahore -->
    <div id="navigaceDashboard">
        <nav class="navbar navbar-expand-sm navbar-dark" style="background:rgba(0,0,0,0.85);">
            <button class="btn btn-danger btn-lg" id="zmacknutiSchovani"><i class="fa fa-bars"></i></button>
            <button class="navbar-toggler" style="color:rgba(255, 255, 255, 0.95);" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidkaToggler">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="rozbalovaciNabidkaToggler">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="rozbalovaciNabidka" role="button" style="color:rgba(255, 255, 255, 0.95);" data-toggle="dropdown">
                            @if($profilovka === NULL) <!-- Zobrazeni profiloveho obrazku na zaklade toho, zdali uzivatel ma nejaky nahrany profilovy obrazek, ci ne -->
                                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4# -->
                                <img src="{{ URL::asset('images/ikona_profil.png') }}" class="profilovka" style="margin-right: 5px;" width="45" alt="Profilová fotka">
                            @else
                                <img src =" {{ asset('/storage/company_images/'.Auth::user()->company_picture) }}" width="45" class="rounded-circle" style="margin-right: 5px;max-height: 45px;" alt="Profilová fotka"/>
                            @endif
                            {{ Auth::user()->company_name }}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('showCompanyProfileData')}}">Profil firmy</a>
                            <a class="dropdown-item" href="{{ route('companyLogout') }}" onclick="event.preventDefault();document.getElementById('logoutForm').submit();">Odhlásit se</a>
                            <form id="logoutForm" action="{{ route('companyLogout') }}" method="POST"> @csrf </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Definice obsahu uvnitr layoutu -->
        <div class="container-fluid" style="padding: 0;margin:0;">
            @yield('content')
        </div>

        <!-- Definice modalniho okna slouziciho pro pridani slozky do Google Drive -->
        <div>
            <div class="modal fade" id="formAddFolder">
                <div class="modal-dialog">
                    <form method="post" action="{{route('createFolder')}}" enctype="multipart/form-data">
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit složku na Google Drive</h5>
                                <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <div class="form-group">
                                    <label style="color:rgba(255, 255, 255, 0.90);font-size: 15px;" for="nazev">Jméno složky:</label>
                                    <input type="text" class="form-control" name="nazev" id="nazev" required/>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit" />
                                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Definice modalniho okna slouziciho pro nahrani souboru do Google Drive -->
        <div>
            <div class="modal fade" id="formUpload">
                <div class="modal-dialog" style="max-width: 600px;">
                    <form method="post" action="{{route('uploadDrive')}}" enctype="multipart/form-data">
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Nahrát soubor na Google Drive</h5>
                                <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <div id="UploadFileContent">
                                </div>
                                <div class="form-group nahratTlacitko text-center">
                                    <input type="file" onchange="ziskatNazevSouboru()" name="soubor" id="souborProNahrani" hidden required/>
                                    <label for="souborProNahrani" style="font-size:12px;margin-bottom:-20px;font-weight: bold;padding: 15px 20px;background-color:#4aa0e6;border-radius: 20px;text-transform:uppercase;letter-spacing: 2px;color:whitesmoke;" id="zobrazeniNazvu">Vyberte soubor</label>
                                    <script>
                                        /* Funkce pro ziskani nazvu vybraneho souboru */
                                        function ziskatNazevSouboru(){
                                            /* Po zmene vstupu pro soubory se ziska nazev souboru, diky tomu, ze lze nahravat pouze jeden soubor naraz staci ziskat nazev souboru na nultem indexu */
                                            document.getElementById("zobrazeniNazvu").innerHTML = "Vybrán soubor: " + event.target.files.item(0).name;
                                        }
                                    </script>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" style="color:rgba(255, 255, 255, 0.90);" id="SubmitUploadFile" class="btn tlacitkoPotvrzeniOkna" value="Nahrát"/>
                                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Definice modalniho okna pro smazani souboru z Google Drive -->
        <div>
            <div class="modal fade" id="formDeleteFile">
                <div class="modal-dialog" style="max-width: 750px;">
                    <form method="post" action="{{route('deleteFile')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h5 class="modal-title" style="color:#4aa0e6;">Odstranit soubory na Google Drive</h5>
                                <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body" style="color:white;">
                                <div id="FilesDeleteContent">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitDeleteFile" value="Odstranit">
                                <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

       <!-- Modalni okno pro odstranovani smen -->
        <div>
            <div class="modal fade" id="formDeleteShift">
                <div class="modal-dialog" style="max-width: 900px;">
                    <form method="post" action="{{route('dashboard.deleteShift')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h4 class="modal-title" style="color:#4aa0e6;">Odstranit směny</h4>
                                <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body" style="color:white;">
                                <div id="ShiftsDeleteContent">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitDeleteShift" value="Odstranit">
                                <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modalni okno pro odstranovani zamestnancu -->
        <div>
            <div class="modal fade" id="formDeleteEmployee">
                <div class="modal-dialog" style="max-width: 900px;">
                    <form method="post" action="{{route('dashboard.deleteEmployee')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h4 class="modal-title" style="color:#4aa0e6;">Odstranit zaměstnance</h4>
                                <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body" style="color:white;">
                                <div id="EmployeesDeleteContent">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitDeleteEmployee" value="Odstranit">
                                <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modalni okno pro pridani jazyku -->
        <div>
            <div class="modal fade" id="formAddLanguage">
                <div class="modal-dialog">
                    <form method="post" action="{{route('addLanguage')}}" id="jazyk_add_form" enctype="multipart/form-data">
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat jazyk do výběru zaměstnaneckých jazyků</h5>
                                <button type="button" style="color:white;" class="close" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                @csrf
                                @if($zprava = Session::get('errory'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong>{{$zprava}}</strong>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label style="color:rgba(255, 255, 255, 0.90);font-size: 15px;" for="jazyk">Název jazyka:</label>
                                    <input type="text" class="form-control" name="jazyk" id="jazyk" required />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="add_language_button" id="add_language_button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit"/>
                                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modalni okno pro odebrani jazyku -->
        <div>
            <div class="modal fade" id="formDeleteLanguage">
                <div class="modal-dialog">
                    <form method="post" action="{{route('removeLanguage')}}" id="jazyk_delete_form" enctype="multipart/form-data">
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Odstranit jazyky z výběru zaměstnaneckých jazyků</h5>
                                <button type="button" style="color:white;" class="close" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info alert-block text-center">
                                    <strong>Seznam Vámi vytvořených jazyků. Vyberte, které jazyky chcete odstranit.</strong>
                                </div>
                                @if (count($jazyky) == 0)
                                    <div class="alert alert-danger alert-block text-center">
                                        <strong>Nevytvořil jste zatím žádný jazyk.</strong>
                                    </div>
                                @endif
                                @csrf
                                <div class="form-check text-center" style="color:white;">
                                        @foreach($jazyky as $moznost)
                                            <input type="checkbox" class="form-check-input" id="nazevJazyky" name="jazyky[]" value="{{$moznost->language_id}}">
                                            <label class="form-check-label" style="font-size: 17px;" for="nazevJazyky"> {{$moznost->language_name}}</label><br>
                                        @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="delete_language_button" id="delete_language_button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Odstranit"/>
                                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modalni okno pro vytvoreni zamestnance -->
        <div>
            <div class="modal fade" id="formAddEmployee" style="color:white;">
                <div class="modal-dialog" style="max-width: 850px;">
                    <form method="post" action="{{route('addEmployee')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit nového zaměstnance</h4>
                                <button type="button" style="color:white;" class="close" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                                    Položky označené (<span style="color:red;">*</span>) jsou povinné.
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="krestni_jmeno" class="col-md-2 text-left">Křestní jméno (<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                                </div>
                                                <input id="krestni_jmeno" placeholder="Zadejte křestní jméno zaměstnance..." type="text" class="form-control @error('krestni_jmeno') is-invalid @enderror" name="krestni_jmeno" value="{{ old('krestni_jmeno') }}" autocomplete="on" autofocus>
                                                @error('krestni_jmeno')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="prijmeni" class="col-md-2 text-left">Příjmení (<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                                </div>
                                                <input id="prijmeni" placeholder="Zadejte příjmení zaměstnance..." type="text" class="form-control @error('prijmeni') is-invalid @enderror" name="prijmeni" value="{{ old('prijmeni') }}" autocomplete="on">
                                                @error('prijmeni')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="narozeniny" class="col-md-2 text-left">Datum narození</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-birthday-cake"></i></div>
                                                </div>
                                                <input type="date" class="form-control @error('narozeniny') is-invalid @enderror" name="narozeniny" id="narozeniny">
                                                @error('narozeniny')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="email" class="col-md-2 text-left">Emailová adresa(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-envelope "></i></div>
                                                </div>
                                                <input id="email" placeholder="Zadejte emailovou adresu zaměstnance..." type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="telefon" class="col-md-2 text-left">Telefonní číslo(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-phone "></i></div>
                                                </div>
                                                <input id="telefon" placeholder="Zadejte telefonní číslo zaměstnance ve tvaru +420 XXX XXX XXX či XXX XXX XXX ..." type="text" class="form-control @error('telefon') is-invalid @enderror" name="telefon" value="{{ old('telefon') }}"  autocomplete="telefon">
                                                @error('telefon')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="pozice" class="col-md-2 text-left">Pozice (<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-child"></i></div>
                                                </div>
                                                <input id="pozice" placeholder="Zadejte pozici zaměstnance..." type="text" class="form-control @error('pozice') is-invalid @enderror" name="pozice" value="{{ old('pozice') }}"  autocomplete="pozice">
                                                @error('pozice')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="mesto_bydliste" class="col-md-2 text-left">Město bydliště(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                                </div>
                                                <input id="mesto_bydliste" placeholder="Zadejte město bydliště zaměstnance..." type="text" class="form-control @error('mesto_bydliste') is-invalid @enderror" name="mesto_bydliste" value="{{ old('mesto_bydliste') }}"  autocomplete="mesto_bydliste">
                                                @error('mesto_bydliste')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="ulice_bydliste" class="col-md-2 text-left">Ulice bydliště</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                                </div>
                                                <input id="ulice_bydliste" placeholder="Zadejte ulici bydliště zaměstnance..." type="text" class="form-control @error('ulice_bydliste') is-invalid @enderror" name="ulice_bydliste" value="{{ old('ulice_bydliste') }}"  autocomplete="ulice_bydliste">
                                                @error('ulice_bydliste')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="prihlasovaci_jmeno" class="col-md-2 text-left">Uživatelské jméno (<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user "></i></div>
                                                </div>
                                                <input id="prihlasovaci_jmeno" placeholder="Zadejte uživatelské jméno zaměstnance..." type="text" class="form-control @error('prihlasovaci_jmeno') is-invalid @enderror" name="prihlasovaci_jmeno" value="{{ old('prihlasovaci_jmeno') }}"  autocomplete="prihlasovaci_jmeno">
                                                @error('prihlasovaci_jmeno')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="button" style="margin-bottom: 15px;" class="btn btn-sm btn-warning pull-right" value="Generovat heslo" onClick="generator_employee_password();">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="heslo" class="col-md-2 text-left">Heslo (<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                                </div>
                                                <input id="heslo" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control @error('heslo') is-invalid @enderror" name="heslo" value="{{ old('heslo') }}" >
                                                @error('heslo')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <span toggle="#heslo" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHeslo"></span>
                                            <script>
                                                /* Skryti/odkryti hesla */
                                                $(".zobrazHeslo").click(function() {
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
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="overeni_hesla" class="col-md-2 text-left">Heslo znovu (<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                                </div>
                                                <input id="overeni_hesla" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control @error('overeni_hesla') is-invalid @enderror" name="overeni_hesla" value="{{ old('overeni_hesla') }}">
                                                @error('overeni_hesla')
                                                <span class="invalid-feedback" role="alert">
                                                     <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <span toggle="#overeni_hesla" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazOvereni"></span>
                                            <script>
                                                /* Skryti/odkryti hesla */
                                                $(".zobrazOvereni").click(function() {
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

                                                /* Funkce pro vygenerovani hesla vytvareneho zamestnance */
                                                function generator_employee_password() {
                                                    var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                                    var heslo = "";
                                                    var i = 0;
                                                    while(i < 10){
                                                        heslo += znaky.charAt(Math.floor(Math.random()*znaky.length));
                                                        i++;
                                                    }
                                                    document.getElementById("heslo").value = heslo;
                                                    document.getElementById("overeni_hesla").value = heslo;
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="poznamka" class="col-md-2 text-left">Poznámka</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                                </div>
                                                <textarea name="poznamka" placeholder="Zadejte poznámku k zaměstnanci... [maximálně 180 znaků]" id="poznamka" class="form-control @error('poznamka') is-invalid @enderror" value="{{ old('poznamka') }}"></textarea>
                                                @error('poznamka')
                                                <span class="invalid-feedback" role="alert">
                                                     <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center" style="font-size: 16px;margin-bottom: 10px;">Výběr profilové fotky zaměstnance:</div>
                                <div class="form-group nahratTlacitko text-center">
                                    <input type="file" onchange="ziskatNazevSouboruObrazek()" name="profilovy_obrazek" id="souborProNahraniObrazek" hidden />
                                    <label for="souborProNahraniObrazek" style="font-size:12px;font-weight: bold;padding: 15px 20px;background-color:#4aa0e6;border-radius: 20px;text-transform:uppercase;letter-spacing: 2px;color:whitesmoke;" id="zobrazeniNazvuObrazku">Vyberte Fotku</label>
                                    <script type="text/javascript">
                                        /* Funkce pro ziskani nazvu vybraneho souboru */
                                        function ziskatNazevSouboruObrazek(){
                                            /* Po zmene vstupu pro soubory se ziska nazev souboru, diky tomu, ze lze nahravat pouze jeden soubor naraz staci ziskat nazev souboru na nultem indexu */
                                            document.getElementById("zobrazeniNazvuObrazku").innerHTML = "Vybrán soubor: " + event.target.files.item(0).name;
                                        }
                                    </script>
                                </div>
                                <div class="text-center" style="font-size: 16px;margin-bottom: 5px;background-color: #1d643b; padding: 5px 10px;border-radius: 10px;">Výběr jazyků, které zaměstnanec ovládá:</div>
                                <div class="form-check text-center" style="color:white;margin-bottom:15px;background-color: #1d643b;border-radius: 10px;padding:5px 10px;">
                                    @if (count($jazyky) == 0)
                                        <div class="alert alert-danger alert-block text-center">
                                            <strong>Nevytvořil jste zatím žádný jazyk.</strong>
                                        </div>
                                    @endif
                                    @foreach($jazyky as $moznost)
                                        <input type="checkbox" class="form-check-input" id="nazevJazykyEmployee" name="jazyky[]" value="{{$moznost->language_id}}">
                                        <label class="form-check-label" style="font-size: 17px;" for="nazevJazykyEmployee"> {{$moznost->language_name}}</label><br>
                                    @endforeach
                                </div>
                                @if($company_url != "")
                                    <div class="form-group text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="googleDriveRequest" id="googleDriveRequest">
                                            <label class="custom-control-label" style="font-size: 15px;" for="googleDriveRequest">Nasdílet zaměstnanci jeho Google Drive složku.</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit zaměstnance"/>
                                    <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoZavreniOkna" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modalni okno slouzici pro pridani smeny -->
        <div>
            <div class="modal fade" id="formAddShift" style="color:white;">
                <div class="modal-dialog" style="max-width: 800px;">
                    <form method="post" action="{{route('addShift')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                  <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit novou směnu</h4>
                                  <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                @if($datumy = Session::get('erroryShiftDatumy'))
                                    <div class="alert alert-danger alert-block">
                                            <button type="button" class="close" data-dismiss="alert">x</button>
                                            <strong>{{ $datumy }}</strong><br>
                                    </div>
                                @endif
                                @if($message = Session::get('erroryShift'))
                                    <div class="alert alert-danger alert-block">
                                            <button type="button" class="close" data-dismiss="alert">x</button>
                                            @foreach ($message as $error)
                                                <strong>{{ $error }}</strong><br>
                                            @endforeach
                                    </div>
                                @endif
                                <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="zacatek_smeny" class="col-md-2 text-left">Začátek směny(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                                <input type="datetime-local" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" class="form-control @error('zacatek_smeny') is-invalid @enderror" name="zacatek_smeny" id="zacatek_smeny" value="{{ old('zacatek_smeny') }}">
                                                @error('zacatek_smeny')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="konec_smeny" class="col-md-2 text-left">Konec směny(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                                <input type="datetime-local" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" class="form-control @error('konec_smeny') is-invalid @enderror" name="konec_smeny" id="konec_smeny" value="{{ old('konec_smeny') }}">
                                                @error('konec_smeny')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="lokace_smeny" class="col-md-2 text-left">Místo (<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-building"></i></div>
                                                </div>
                                                <input id="lokace_smeny" placeholder="Zadejte lokaci směny..." type="text" class="form-control @error('lokace_smeny') is-invalid @enderror" name="lokace_smeny" value="{{ old('lokace_smeny') }}">
                                                @error('lokace_smeny')
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="dulezitost_smeny" class="col-md-2 text-left">Důležitost</label>
                                        <div class="col-md-10">
                                        <select name="dulezitost_smeny" id="dulezitost_smeny" style="color:black;text-align-last: center;" class="form-control">
                                            <option value="6">Vyberte důležitost</option>
                                            @foreach($importances as $importance)
                                                <option value="{{$importance->importance_id}}">{{$importance->importance_description}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="poznamka" class="col-md-2 text-left">Poznámka</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                                </div>
                                                <textarea name="poznamka" placeholder="Zadejte poznámku ke směně... [maximálně 180 znaků]" id="poznamka" class="form-control @error('poznamka') is-invalid @enderror" value="{{ old('poznamka') }}"></textarea>
                                                @error('poznamka')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit směnu"/>
                                    <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoZavreniOkna" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- sekce pro odchytavani erroru, pokud jsou odchyceny tak znovu otevre dane modalni okno, viz https://getbootstrap.com/docs/4.0/components/modal/ -->
@if(Session::has('errors'))
    <script>$(document).ready(function(){$('#formAddEmployee').modal('show');});</script>
@endif
@if(Session::has('errory'))
    <script>$(document).ready(function(){$('#formAddLanguage').modal('show');});</script>
@endif
@if(Session::has('erroryShift'))
    <script>$(document).ready(function(){$('#formAddShift').modal('show');});</script>
@endif

<script type="text/javascript">
    $(document).ready(function() {
        /* Kvuli nepodpore inputu typu datetime-local napriklad v prohlizeci firefox bylo potreba pouzit knihovnu Modernizr, ktera zjisti, zdali je input typu datetime-local prohlizecem podporovan
           ,paklize neni, tak jako alternativa se vytvori datetimepicker za pomoci datetimepicker pluginu.
           Odkaz na datetimepicker: https://xdsoft.net/jqplugins/datetimepicker/, Odkaz na Modernizr: https://modernizr.com/docs
           */
        if((Modernizr.inputtypes['datetime-local']) === false) {
            $("input[type=datetime-local]").datetimepicker({
                format:'Y-m-d H:i' // nastaveni formatu datumu
            });
        }
        /* Po zmacknuti na cervene tlacitko se postranni panel schova */
        $("#zmacknutiSchovani").click(function () {$("#obsah").toggleClass("toggled");});

        /* Zobrazeni smen urcenych k mazani */
        $('body').on('click', '#getDeleteShiftData', function() {
            $.ajax({
                url: "/shiftsDashboard/show",
                method: 'GET',
                success: function(odpoved) {
                    $('#ShiftsDeleteContent').html(odpoved.out); // vlozeni obsahu do modalniho okna
                    $('#DeleteShiftModal').show(); // zobrazeni modalniho okna
                }
            });
        });

        /* Vypsani moznosti souborů pro smazani souboru v dashboard */
        $('body').on('click', '#getDeleteFileDataCheckBox', function() {
            $.ajax({
                url: "/dashboard/googleFilesCheckboxes/show",
                method: 'GET',
                success: function(odpoved) {
                    $('#FilesDeleteContent').html(odpoved.out);
                    $('#formDeleteFile').show();
                }
            });
        });

        /* Vypsani moznosti souborů pro upload souboru v dashboard */
        $('body').on('click', '#getUploadFileDataOptions', function() {
            $.ajax({
                url: "/dashboard/googleFoldersOptions/show",
                method: 'GET',
                success: function(odpoved) {
                    $('#UploadFileContent').html(odpoved.out);
                    $('#formUpload').show();
                }
            });
        });

        /* Zobrazeni zamestnancu urcenych k mazani */
        $('body').on('click', '#getDeleteEmployeeData', function() {
            $.ajax({
                url: "/employeesDashboard/show",
                method: 'GET',
                success: function(odpoved) {
                    $('#EmployeesDeleteContent').html(odpoved.out);
                    $('#formDeleteEmployee').show();
                }
            });
        });
    });

</script>
</body>
</html>
