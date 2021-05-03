<!doctype html>
<html lang="cs">
<head>
    <!-- Nazev souboru: employee_dashboard.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje cele menu (vcetne postranniho panelu) a take element, do ktereho se nasledne vkladaji jednotlive moznosti domovske stranky. Tento soubor
     take obsahuje modalni okna pro manipulaci s Google Drive. Tento soubor se vaze na roli zamestnance -->
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
    <meta name="robots" content="index, follow"/>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.15/jquery.datetimepicker.min.css" rel="stylesheet">
    <!-- import datovych tabulek, jquery, javascriptu, chart.js, chart.js datalabels plugin, datetimepicker, moment a modernizr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/0.7.0/chartjs-plugin-datalabels.js" integrity="sha512-yvu1r8RRJ0EHKpe1K3pfHF7ntjnDrN7Z66hVVGB90CvWbWTXevVZ8Sy1p+X4sS9M9Z+Q9tZu1GjGzFFmu/pAmg==" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.15/jquery.datetimepicker.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; }
    </style>
</head>
<body>
<div class="d-flex" id="obsah">
    <!-- Definice postranniho panelu -->
    <div class="efektMenu" id="postranniPanel" style="background:rgba(0,0,0,0.85);">
        <div class="sekceLogo"><a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;color:rgba(255, 255, 255, 0.95);"><img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" alt="Logo"/> | Tozondo</a>
            <hr class="caraPodNazvem">
        </div>
        <div class="list-group">
            <!-- Definice jednotlivych polozek postranniho panelu, paklize se uzivatel nachazi v nejake polozce, tak je vybarvena cervene, viz https://laravel.com/docs/8.x/requests -->
            <a href="{{route('homeEmployee')}}" class="border-bottom {{ request()->routeIs('homeEmployee') ? 'active' : '' }} border-bottom odkaz" style="padding-left:60px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 20px;padding-top: 20px;font-size:17px;"><i class="fa fa-cube"></i> Dashboard</a>
            <a href="{{route('shifts.currentShiftsEmployee')}}" class="{{ request()->routeIs('shifts.currentShiftsEmployee') ? 'active' : '' }} odkaz"  style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-th-large"></i> Aktuální směny</a>
            <a href="{{route('shifts.AllShiftsEmployee')}}" class="{{ request()->routeIs('shifts.AllShiftsEmployee') ? 'active' : '' }} border-bottom odkaz" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-list"></i> Všechny směny</a>
            <a href="#centresDropdown" data-toggle="collapse" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-university"></i> Centra <i style="margin-left: 15px;" class="fa fa-caret-down"></i></a>
            <ul class="collapse list-unstyled" id="centresDropdown" style="margin-bottom:0px;"> <!-- Definice rozbalovaciho menu pro centra -->
                <a href="{{route('employee_vacations.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_vacations.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                       <i class="fa fa-sun-o"></i> Centrum dovolených
                    </li>
                </a>
                <a href="{{route('employee_reports.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_reports.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-list-alt"></i> Centrum nahlášení
                    </li>
                </a>
                <a href="{{route('employee_diseases.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_diseases.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-medkit"></i> Centrum nemocenských
                    </li>
                </a>
                <a href="{{route('employee_injuries.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_injuries.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-heartbeat"></i> Historie zranění
                    </li>
                </a>
            </ul>
            @if($employee_url != "") <!-- Pokud zamestnanci firma nenasdilela Google Drive slozku, tak neuvidi Google Drive moznosti -->
                <a href="#googleDriveDropdown" data-toggle="collapse" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-server"></i> Google Drive <i style="margin-left: 15px;" class="fa fa-caret-down"></i></a> <!-- Definice rozbalovaciho menu pro moznosti s Google Drive -->
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
                    <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->employee_url }}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;" target="_blank">
                        <li style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                           <i class="fa fa-eye"></i> Zobrazit Google Drive
                        </li>
                    </a>
                </ul>
            @endif
            <a href="{{route('employee_statistics.index')}}" class="{{ request()->routeIs('employee_statistics.index') ? 'active' : '' }} odkaz" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-pie-chart"></i> Statistiky</a>
            <a href="{{route('employee_generator.index')}}" class="{{ request()->routeIs('employee_generator.index') ? 'active' : '' }} odkaz border-bottom" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-rocket"></i> Generátor souborů</a>
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
                        <a class="nav-link dropdown-toggle" id="rozbalovaciNabidka" role="button" style="color:rgba(255, 255, 255, 0.95);" data-toggle="dropdown">
                            @if($profilovka === NULL) <!-- Zobrazeni profiloveho obrazku na zaklade toho, zdali uzivatel ma nejaky nahrany profilovy obrazek, ci ne -->
                                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4# -->
                                <img src="{{ URL::asset('images/ikona_profil.png') }}" class="profilovka" style="margin-right: 5px;" width="45" alt="Profilová fotka"/>
                            @else
                                <img src ="{{ asset('/storage/employee_images/'.Auth::user()->employee_picture) }}" width="45" class="rounded-circle" style="margin-right: 5px;max-height: 45px;"  alt="Profilová fotka"/>
                            @endif
                            {{ Auth::user()->employee_name }} {{ Auth::user()->employee_surname }}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('showEmployeeProfileData')}}">Profil zaměstnance</a>
                            <a class="dropdown-item" href="{{ route('employeeLogout') }}" onclick="event.preventDefault();document.getElementById('logoutForm').submit();">Odhlásit se</a>
                            <form id="logoutForm" action="{{ route('employeeLogout') }}" method="POST"> @csrf </form>
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
                    <form method="post" action="{{route('createFolderEmployee')}}" enctype="multipart/form-data">
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
                                    <input type="submit" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit"/>
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
                    <form method="post" action="{{route('uploadDriveEmployee')}}" enctype="multipart/form-data">
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
                                    <input type="file" onchange="ziskatNazevSouboru()" name="soubor" required id="souborProNahrani" hidden/>
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
                    <form method="post" action="{{route('deleteFileEmployee')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content oknoBarvaPozadi">
                            <div class="modal-header">
                                <h4 class="modal-title" style="color:#4aa0e6;">Smazat soubory na Google Drive</h4>
                                <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body" style="color:white;">
                                <div id="FilesDeleteContent">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitDeleteFile" value="Smazat">
                                <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        /* Po zmacknuti na cervene tlacitko se postranni panel schova */
        $("#zmacknutiSchovani").click(function() {$("#obsah").toggleClass("toggled");});

        /* Kvuli nepodpore inputu typu datetime-local napriklad v prohlizeci firefox bylo potreba pouzit knihovnu Modernizr, ktera zjisti, zdali je input typu datetime-local prohlizecem podporovan
          ,paklize neni, tak jako alternativa se vytvori datetimepicker za pomoci datetimepicker pluginu.
          Odkaz na datetimepicker: https://xdsoft.net/jqplugins/datetimepicker/, Odkaz na Modernizr: https://modernizr.com/docs
          */
        if((Modernizr.inputtypes['datetime-local']) === false) { // pokud neni input typu datetime-local podporovan
            $("input[type=datetime-local]").datetimepicker({
                format:'Y-m-d H:i' // nastaveni formatu datumu
            });
        }

        /* Vypsani moznosti souborů pro smazani do modalniho okna */
        $('body').on('click', '#getDeleteFileDataCheckBox', function() {
            $.ajax({
                url: "/dashboard/googleFilesCheckboxes/employee/show/",
                method: 'GET',
                success: function(odpoved) {
                    $('#FilesDeleteContent').html(odpoved.out);
                    $('#formDeleteFile').show();
                }
            });
        });

        /* Vypsani moznosti souborů pro upload souboru do modalniho okna */
        $('body').on('click', '#getUploadFileDataOptions', function() {
            $.ajax({
                url: "/dashboard/googleFoldersOptions/employee/show",
                method: 'GET',
                success: function(odpoved) {
                    $('#UploadFileContent').html(odpoved.out);
                    $('#formUpload').show();
                }
            });
        });
    });
</script>
</body>
</html>
