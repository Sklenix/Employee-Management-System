<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }
        .modal-content {
            background-color: #1a202c !important;

        }
        .modal-header h5{
            color:rgba(255, 255, 255, 0.95);
        }

        .btn-modalClose{
            background-color: #4aa0e6 !important;
        }

        .btn-modalClose:hover{
            background-color: #c51f1a !important;
        }

        .btn-modalSuccess{
            background-color: #4aa0e6 !important;
        }

        .btn-modalSuccess:hover{
            background-color: green !important;
        }

        .nahratTlacitko label:hover{
            transform: scale(1.03);
        }

        .nahratTlacitko label span{
            font-weight: normal;
        }
        .list-group .odkaz { text-decoration: none; font-weight: 100; text-transform: uppercase; }
        .fill .list-group .odkaz { position: relative; }

        .fill .list-group .odkaz:after { position: absolute; bottom: 0; left: 0; right: 0; margin: auto;
            width: 0%; content: '.'; color: transparent; height: 1px; }

        .fill .list-group .odkaz:hover { z-index: 1; }

        .fill .list-group .odkaz:hover:after { z-index: -10; animation: fill 1s forwards;
            -webkit-animation: fill 1s forwards; -moz-animation: fill 1s forwards; opacity: 1; }


        .list-group .hoverList { text-decoration: none; font-weight: 100; text-transform: uppercase; }
        .fill .list-group .hoverList { position: relative; }

        .fill .list-group .hoverList:after { position: absolute; bottom: 0; left: 0; right: 0; margin: auto;
            width: 0%; content: '.'; color: transparent; height: 1px; }

        .fill .list-group .hoverList:hover { z-index: 1; }

        .fill .list-group .hoverList:hover:after { z-index: -10; animation: fill 1s forwards;
            -webkit-animation: fill 1s forwards; -moz-animation: fill 1s forwards; opacity: 1; }

        @-webkit-keyframes fill {
            0% { width: 0%; height: 1px; }
            50% { width: 100%; height: 1px; }
            100% { width: 100%; height: 100%; background: #6495ED; }
        }

        .navbar-brand{ font-family: 'Pacifico', cursive; }
        #wrapper {
            overflow-x: hidden;
            background-color: #F5F5F5;
        }
        hr.caraPodNazvem {
            border: 1px solid white;
            margin-bottom: 0;
        }
        .ramecek{
            border-style: solid;
            margin-bottom:15px;
            margin-right: 15px;
            border-width: thin;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            -webkit-transition: margin .25s ease-out;
            -moz-transition: margin .25s ease-out;
            -o-transition: margin .25s ease-out;
            transition: margin .25s ease-out;

        }

        #sidebar-wrapper .sidebar-heading {
            padding: 15px 10px;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 19rem;
            font-size: 16px;

        }

        #page-content-wrapper {
            min-width: 100vw;
            min-height: 60vw;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -19rem;
            }
        }
        .list-group .active { background:#d9534f;}

        @media (max-width: 767px) {
            #sidebar-wrapper {
                margin-left: 0px;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -19rem;
            }
        }

        table.vacation-list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.vacation-list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        table.vacation-list.dataTable thead{
            background-color: #333;
            color:white;
        }

        table.disease-list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.disease-list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        table.disease-list.dataTable thead{
            background-color: #333;
            color:white;
        }

        table.report-list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.report-list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        table.report-list.dataTable thead{
            background-color: #333;
            color:white;
        }

        table.employee_current_shift_list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.employee_current_shift_list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        table.employee_current_shift_list.dataTable thead{
            background-color: #333;
            color:white;
        }

        table.employee_all_shifts_list.dataTable tbody tr:hover {
            background-color: #FFE4E1;
        }

        table.employee_all_shifts_list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #FFE4E1;
        }

        table.employee_all_shifts_list.dataTable thead{
            background-color: #8B0000;
            color:white;
        }

        table.employee_injury_list.dataTable tbody tr:hover {
            background-color: #FFE4E1;
        }

        table.employee_injury_list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #FFE4E1;
        }

        table.employee_injury_list.dataTable thead{
            background-color: #8B0000;
            color:white;
        }

    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tozondo @yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>

    <link rel="icon" href="{{ asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="d-flex" id="wrapper">

    <div class="fill" id="sidebar-wrapper" style="background:rgba(0,0,0,0.85);">
        <div class="sidebar-heading">  <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;color:rgba(255, 255, 255, 0.95);"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" /> | Tozondo</a>
            <hr class="caraPodNazvem">
        </div>
        <div class="list-group list-group-flush">
            <a href="{{route('homeEmployee')}}" class="border-bottom {{ request()->routeIs('homeEmployee') ? 'active' : '' }} border-bottom odkaz" style="padding-left:60px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 20px;padding-top: 20px;font-size:17px;"><i class="fa fa-cube" aria-hidden="true"></i> Dashboard</a>
            <a href="{{route('shifts.currentShiftsEmployee')}}" class="{{ request()->routeIs('shifts.currentShiftsEmployee') ? 'active' : '' }} odkaz"  style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-th-large" aria-hidden="true"></i> Aktuální směny</a>
            <a href="{{route('shifts.AllShiftsEmployee')}}" class="{{ request()->routeIs('shifts.AllShiftsEmployee') ? 'active' : '' }} border-bottom odkaz" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-list" aria-hidden="true"></i> Všechny směny</a>

            <a href="#centresDropdown" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-university" aria-hidden="true"></i> Centra <i style="margin-left: 15px;" class="fa fa-caret-down" aria-hidden="true"></i></a>
            <ul class="collapse list-unstyled keep-open" id="centresDropdown" style="margin-bottom:0px;">
                <a href="{{route('employee_vacations.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_vacations.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                       <i class="fa fa-sun-o" aria-hidden="true"></i> Centrum dovolených
                    </li>
                </a>
                <a href="{{route('employee_reports.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_reports.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-list-alt" aria-hidden="true"></i> Centrum nahlášení
                    </li>
                </a>
                <a href="{{route('employee_diseases.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_diseases.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-medkit" aria-hidden="true"></i> Centrum nemocenských
                    </li>
                </a>
                <a href="{{route('employee_injuries.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('employee_injuries.index') ? 'active' : '' }} hoverList" style="padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-heartbeat" aria-hidden="true"></i> Historie zranění
                    </li>
                </a>
            </ul>

            <a href="#googleDriveDropdown" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-server" aria-hidden="true"></i> Google Drive <i style="margin-left: 15px;" class="fa fa-caret-down" aria-hidden="true"></i></a>
            <ul class="collapse list-unstyled keep-open" id="googleDriveDropdown" style="margin-bottom:0px;">
                <a data-toggle="modal" data-target="#formAddFolder">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-upload" aria-hidden="true"></i> Přidat složku
                    </li>
                </a>
                <a data-toggle="modal" id="getDeleteFileDataCheckBox" data-target="#formDeleteFile">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-upload" aria-hidden="true"></i> Smazat soubor
                    </li>
                </a>
                <a data-toggle="modal" id="getUploadFileDataOptions" data-target="#formUpload">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                       <i class="fa fa-upload" aria-hidden="true"></i> Nahrání souboru
                    </li>
                </a>
                <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->employee_drive_url }}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;" target="_blank">
                    <li style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                       <i class="fa fa-eye" aria-hidden="true"></i> Zobrazit Google Drive
                    </li>
                </a>
            </ul>

            <a href="{{route('employee_statistics.index')}}" class="{{ request()->routeIs('employee_statistics.index') ? 'active' : '' }} odkaz" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-pie-chart" aria-hidden="true"></i> Statistiky</a>
            <a href="{{route('employee_generator.index')}}" class="{{ request()->routeIs('employee_generator.index') ? 'active' : '' }} odkaz" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-rocket" aria-hidden="true"></i> Generátor souborů</a>

        </div>
    </div>

    <div id="page-content-wrapper" >
        <nav class="navbar navbar-expand-lg navbar-dark border-bottom" style="background:rgba(0,0,0,0.85);">
            <button class="btn btn-danger btn-lg" id="menu-toggle"><i class="fa fa-bars" aria-hidden="true"></i></button>
            <button class="navbar-toggler" style="color:rgba(255, 255, 255, 0.95);" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0" >
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" style="color:rgba(255, 255, 255, 0.95);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($profilovka === NULL)
                                <img src="{{ URL::asset('images/ikona_profil.png') }}" class="profilovka" style="margin-right: 5px;" width="45" alt="profilovka">
                            @else
                                <img src =" {{ asset('/storage/employee_images/'.Auth::user()->employee_picture) }}" width="45" class="rounded-circle" style="margin-right: 5px;max-height: 45px;"  alt="profilovka" />
                            @endif
                            {{ Auth::user()->employee_name }} {{ Auth::user()->employee_surname }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('showEmployeeProfileData')}}">Profil zaměstnance</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Odhlásit se</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid" style="padding: 0;margin:0;">
            @yield('content')
        </div>

        <!-- Pridani slozky do Google Drive v menu -->
        <div>
            <div class="modal fade" id="formAddFolder" role="dialog">
                <div class="modal-dialog">
                    <form method="post" action="{{route('createFolderEmployee')}}" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                    <span class="col-md-12 text-center">
                                         <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit složku na Google Drive</h5>
                                    </span>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <div class="form-group">
                                    <label style="color:rgba(255, 255, 255, 0.90);font-size: 15px;" for="nazev">Jméno složky:</label>
                                    <input type="text" class="form-control" name="nazev" id="nazev" required />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat" />
                                    <button type="button" class="btn btn-modalClose" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Nahrani souboru do Google Drive v menu -->
        <div>
            <div class="modal fade" id="formUpload" role="dialog">
                <div class="modal-dialog" style="max-width: 600px;">
                    <form method="post" action="{{route('uploadDriveEmployee')}}" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                    <span class="col-md-12 text-center">
                    <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Nahrát soubor na Google Drive</h5>
                    </span>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <div id="FileUploadBody">
                                </div>
                                <div class="form-group nahratTlacitko text-center">
                                    <input type="file" name="fileInput" required id="file" hidden />
                                    <label for="file" style="padding: 12px 35px;border:3px solid #4aa0e6;border-radius: 48px;text-transform: uppercase;letter-spacing: 2px;font-weight: bold;color:#4aa0e6;" id="selector">Vyberte soubor</label>
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
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" style="color:rgba(255, 255, 255, 0.90);" id="SubmitUploadFile" class="btn btn-modalSuccess" value="Nahrát" />
                                    <button type="button" class="btn btn-modalClose" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Smazani souboru z Google Drive v menu -->
        <div>
            <div class="modal fade" id="formDeleteFile" role="dialog">
                <div class="modal-dialog" style="max-width: 750px;">
                    <form method="post" action="{{route('deleteFileEmployee')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" style="color:white;">Smazat soubor(y) z Google Drive</h4>
                                <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" style="color:white;">
                                <div id="DeleteFileBody">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn btn-modalSuccess" style="color:white;" id="SubmitDeleteFile" value="Smazat">
                                <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    /* Vypsani moznosti souborů pro smazani souboru v dashboard */
    $('.modelClose').on('click', function(){
        $('#formDeleteFile').hide();
    });
    $('body').on('click', '#getDeleteFileDataCheckBox', function(e) {
        $.ajax({
            url: "/dashboard/googleFilesCheckboxes/employee/show/",
            method: 'GET',
            success: function(result) {
                console.log(result);
                $('#DeleteFileBody').html(result.html);
                $('#formDeleteFile').show();
            }
        });
    });

    /* Vypsani moznosti souborů pro upload souboru v dashboard */
    $('.modelClose').on('click', function(){
        $('#formUpload').hide();
    });
    $('body').on('click', '#getUploadFileDataOptions', function(e) {
        $.ajax({
            url: "/dashboard/googleFoldersOptions/employee/show",
            method: 'GET',
            success: function(result) {
                console.log(result);
                $('#FileUploadBody').html(result.html);
                $('#formUpload').show();
            }
        });
    });


</script>

</body>
</html>
