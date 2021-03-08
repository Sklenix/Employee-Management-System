<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }

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

        .formularLabels{
            font-size: 16px;
        }

        .formularInputs{
            font-size: 15px;
        }

        .formularLabelsAjaxAdd{
            font-size: 15px;
        }

        .slidecontainer {
            width: 100%;
        }

        .slider {
            -webkit-appearance: none;
            width: 100%;
            height: 15px;
            border-radius: 5px;
            background: white;
            outline: none;
            opacity: 0.7;
            -webkit-transition: .2s;
            transition: opacity .2s;
        }

        .slider:hover {
            opacity: 1;
        }

        .pull-left{float:left!important;}
        .pull-right{float:right!important;}


        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background-color: #2d995b;
            cursor: pointer;
        }

        .slider::-moz-range-thumb {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: #4aa0e6;
            cursor: pointer;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 15px 10px;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 19rem;
            font-size: 16px;

        }

        .list-group .active { background:#d9534f;}

        #page-content-wrapper {
            min-width: 100vw;
            min-height: 85vw;
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

        .employee_list.dataTable thead th {
            border-bottom: 0;
        }

        .employee_list.dataTable.no-footer {
            border-bottom: 0;
        }

        table.employee_list.dataTable tbody tr:hover {
            background-color: #FFE4E1;
        }

        table.employee_list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #FFE4E1;
        }

       /* table.employee_list.dataTable thead:hover {
            background-color: #ffa;
        }*/

        table.employee_list.dataTable thead{
           background-color: #8B0000;
            color:white;

        }


        .shift_list.dataTable thead th {
            border-bottom: 0;
        }

        .shift_list.dataTable.no-footer {
            border-bottom: 0;
        }

        table.shift_list.dataTable tbody tr:hover {
            background-color: #FFE4E1;
        }

        table.shift_list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #FFE4E1;
        }

        /* table.employee_list.dataTable thead:hover {
             background-color: #ffa;
         }*/

        table.shift_list.dataTable thead{
            background-color: #8B0000;
            color:white;

        }

        .rate_list.dataTable thead th {
            border-bottom: 0;
        }

        .rate_list.dataTable.no-footer {
            border-bottom: 0;
        }

        table.rate_list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.rate_list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        /* table.employee_list.dataTable thead:hover {
             background-color: #ffa;
         }*/

        table.rate_list.dataTable thead{
            background-color: #333;
            color:white;
        }

        .attendance-list.dataTable thead th {
            border-bottom: 0;
        }

        .attendance-list.dataTable.no-footer {
            border-bottom: 0;
        }

        table.attendance-list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.attendance-list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        /* table.attendance-list.dataTable thead:hover {
             background-color: #ffa;
         }*/

        table.attendance-list.dataTable thead{
            background-color: #333;
            color:white;
        }


        table.injury_list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.injury_list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        /* table.attendance-list.dataTable thead:hover {
             background-color: #ffa;
         }*/

        table.injury_list.dataTable thead{
            background-color: #8B0000;
            color:white;
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

        .custom-control-label::before,
        .custom-control-label::after {
            top: .8rem;
            width: 1.25rem;
            height: 1.25rem;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
            <a href="{{route('home')}}" class="odkaz border-bottom {{ request()->routeIs('home') ? 'active' : '' }}" style="padding-left:60px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 20px;padding-top: 20px;font-size:17px;"><i class="fa fa-cube" aria-hidden="true"></i> Dashboard</a>
            <a href="#zamestnanciDropdown" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-user" aria-hidden="true"></i> Zaměstnanci <i style="margin-left: 15px;" class="fa fa-caret-down" aria-hidden="true"></i></a>
            <ul class="collapse list-unstyled keep-open" id="zamestnanciDropdown" style="margin-bottom:0px;">
                <a data-toggle="modal" data-target="#formAddEmployee" >
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-user-plus" aria-hidden="true"></i> Přidat zaměstnance
                    </li>
                </a>
                <a data-toggle="modal" data-target="#formDeleteEmployee" id="getDeleteEmployeeData">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-user-times" aria-hidden="true"></i> Smazat zaměstnance
                    </li>
                </a>
                <a href="{{route('ratings.index')}}" style="text-decoration: none;color:rgba(255, 255, 255, 0.95);">
                    <li class="{{ request()->routeIs('ratings.index') ? 'active' : '' }} hoverList" style="padding-left:30px;text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-th-large" aria-hidden="true"></i> Hodnocení zaměstnanců
                    </li>
                </a>
            </ul>
            <a href="{{route('employees.index')}}" class="border-bottom odkaz {{ request()->routeIs('employees.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);padding-top: 16px;padding-bottom: 16px;font-size: 16px;text-decoration: none;color:rgba(255, 255, 255, 0.95);"><i class="fa fa-list" aria-hidden="true"></i> Seznam zaměstnanců</a>

            <a href="#smenyDropdown" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-clock-o" aria-hidden="true"></i> Směny <i style="margin-left: 15px;" class="fa fa-caret-down" aria-hidden="true"></i></a>
            <ul class="collapse list-unstyled" id="smenyDropdown" style="margin-bottom:0px;">
                <a data-toggle="modal" data-target="#formAddShift">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                       <i class="fa fa-plus-square-o" aria-hidden="true"></i> Přidat směnu
                    </li>
                </a>
                <a data-toggle="modal" id="getDeleteShiftData" data-target="#formDeleteShift">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-minus-square-o" aria-hidden="true"></i> Smazat směnu(y)
                    </li>
                </a>
            </ul>

            <a href="{{route('shifts.index')}}" class="odkaz {{ request()->routeIs('shifts.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-list" aria-hidden="true"></i> Seznam směn</a>
            <a href="{{route('attendance.index')}}" class="odkaz border-bottom {{ request()->routeIs('attendance.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-address-card-o" aria-hidden="true"></i> Docházka</a>

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
                <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->company_url }}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;" target="_blank">
                    <li style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                       <i class="fa fa-eye" aria-hidden="true"></i> Zobrazit Google Drive
                    </li>
                </a>
            </ul>

            <a href="#languageDropdown" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-language" aria-hidden="true"></i> Jazyky <i style="margin-left: 15px;" class="fa fa-caret-down" aria-hidden="true"></i></a>
            <ul class="collapse list-unstyled keep-open" id="languageDropdown" style="margin-bottom:0px;">
                <a data-toggle="modal" data-target="#formAddLanguage">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                       <i class="fa fa-plus-square-o" aria-hidden="true"></i> Přidat jazyk
                    </li>
                </a>
                <a data-toggle="modal" data-target="#formDeleteLanguage">
                    <li style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" class="hoverList">
                        <i class="fa fa-minus-square-o" aria-hidden="true"></i> Odstranit jazyk(y)
                    </li>
                </a>
            </ul>

            <a href="#centresDropdown" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle odkaz" style="cursor: pointer;padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-university" aria-hidden="true"></i> Centra <i style="margin-left: 15px;" class="fa fa-caret-down" aria-hidden="true"></i></a>
            <ul class="collapse list-unstyled keep-open" id="centresDropdown" style="margin-bottom:0px;">
                <a href="{{route('injuries.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('injuries.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                       <i class="fa fa-heartbeat" aria-hidden="true"></i> Centrum zranění
                    </li>
                </a>
                <a href="{{route('vacations.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('vacations.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-sun-o" aria-hidden="true"></i> Centrum dovolených
                    </li>
                </a>
                <a href="{{route('diseases.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('diseases.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-medkit" aria-hidden="true"></i> Centrum nemocenských
                    </li>
                </a>
                <a href="{{route('reports.index')}}" style="color:rgba(255, 255, 255, 0.95);text-decoration: none;">
                    <li class="{{ request()->routeIs('reports.index') ? 'active' : '' }} hoverList" style="cursor: pointer;padding-left:30px;padding-bottom: 16px;padding-top: 16px;font-size: 16px;">
                        <i class="fa fa-list-alt" aria-hidden="true"></i> Centrum nahlášení
                    </li>
                </a>
            </ul>

            <a href="{{route('statistics.index')}}" class="odkaz {{ request()->routeIs('statistics.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-pie-chart" aria-hidden="true"></i> Statistiky</a>
            <a href="{{route('generator.index')}}" class="odkaz {{ request()->routeIs('generator.index') ? 'active' : '' }} border-bottom" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-rocket" aria-hidden="true"></i> Generátor souborů</a>
        </div>
    </div>
    <div id="page-content-wrapper">
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
                                <img src =" {{ asset('/storage/company_images/'.Auth::user()->company_picture) }}" width="45" class="rounded-circle"  style="margin-right: 5px;max-height: 45px;"  alt="profilovka" />
                            @endif
                            {{ Auth::user()->company_name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('showCompanyProfileData')}}">Profil firmy</a>
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
        <div class="container-fluid" style="padding: 0;margin:0;">
            @yield('content2')
        </div>
        <!-- Nahrani souboru do Google Drive v menu -->
        <div>
            <div class="modal fade" id="formUpload" role="dialog">
                <div class="modal-dialog" style="max-width: 600px;">
                    <form method="post" action="{{route('uploadDrive')}}" enctype="multipart/form-data">
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
                    <form method="post" action="{{route('deleteFile')}}" enctype="multipart/form-data">
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

        <!-- Smazani smeny -->
        <div>
            <div class="modal fade" id="formDeleteShift" role="dialog">
                <div class="modal-dialog" style="max-width: 900px;">
                    <form method="post" action="{{route('dashboard.deleteShift')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" style="color:white;">Smazat směnu(y)</h4>
                                <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" style="color:white;">
                                <div id="DeleteShiftBody">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn btn-modalSuccess" style="color:white;" id="SubmitDeleteShift" value="Smazat">
                                <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Smazani zamestnance -->
        <div>
            <div class="modal fade" id="formDeleteEmployee" role="dialog">
                <div class="modal-dialog" style="max-width: 900px;">
                    <form method="post" action="{{route('dashboard.deleteEmployee')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" style="color:white;">Smazat zaměstnance</h4>
                                <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" style="color:white;">
                                <div id="DeleteEmployeeBody">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn btn-modalSuccess" style="color:white;" id="SubmitDeleteEmployee" value="Smazat">
                                <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pridani slozky do Google Drive v menu -->
        <div>
            <div class="modal fade" id="formAddFolder" role="dialog">
                    <div class="modal-dialog">
                        <form method="post" action="{{route('createFolder')}}" enctype="multipart/form-data">
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

        <!-- Pridani jazyku do voleb zamestnance v menu -->
        <div>
            <div class="modal fade" id="formAddLanguage" role="dialog">
                <div class="modal-dialog">
                    <form method="post" action="{{route('addLanguage')}}" id="jazyk_add_form" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat jazyk do výběru zaměstnaneckých jazyků</h5>
                                <button type="button" style="color:white;" class="close modelClose" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                @csrf
                                @if($message = Session::get('errory'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong>{{$message}}</strong>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label style="color:rgba(255, 255, 255, 0.90);font-size: 15px;" for="jazyk">Název jazyka:</label>
                                    <input type="text" class="form-control" name="jazyk" id="jazyk" required />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="add_language_button" id="add_language_button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat" />
                                    <button type="button" class="btn btn-modalClose" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Odebrani jazyku z voleb zamestnance v menu -->
        <div>
            <div class="modal fade" id="formDeleteLanguage" role="dialog">
                <div class="modal-dialog">
                    <form method="post" action="{{route('removeLanguage')}}" id="jazyk_delete_form" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Odstranit jazyk/y z výběru zaměstnaneckých jazyků</h5>
                                <button type="button" style="color:white;" class="close modelClose" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info alert-block text-center">
                                    <strong>Seznam Vámi definovaných jazyků, vyberte, které jazyky chcete smazat.</strong>
                                </div>
                                @if (count($jazyky) == 0)
                                    <div class="alert alert-danger alert-block text-center">
                                        <strong>Nedefinoval jste žádný jazyk.</strong>
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
                                    <input type="submit" name="delete_language_button" id="delete_language_button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Odstranit" />
                                    <button type="button" class="btn btn-modalClose" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Vytvoreni zamestnance v menu !-->
        <div>
            <div class="modal fade" id="formAddEmployee" style="color:white;">
                <div class="modal-dialog  modal-lg">
                    <form method="post" action="{{route('addEmployee')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                 <span class="col-md-12 text-center">
                                         <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat nového zaměstnance</h4>
                                 </span>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                                    Položky označené (<span style="color:red;">*</span>) jsou povinné.
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-2 text-left">Jméno(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_name" placeholder="Zadejte křestní jméno zaměstnance..." type="text" class="form-control @error('employee_name') is-invalid @enderror" name="employee_name" value="{{ old('employee_name') }}"  autocomplete="employee_name" autofocus>
                                                @error('employee_name')
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
                                        <label class="col-md-2 text-left">Příjmení(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_surname" placeholder="Zadejte příjmení zaměstnance..." type="text" class="form-control @error('employee_surname') is-invalid @enderror" name="employee_surname" value="{{ old('employee_surname') }}"  autocomplete="employee_surname">
                                                @error('employee_surname')
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
                                        <label class="col-md-2 text-left">Email(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_email" placeholder="Zadejte email zaměstnance..." type="text" class="form-control @error('employee_email') is-invalid @enderror" name="employee_email" value="{{ old('employee_email') }}"  autocomplete="employee_email">
                                                @error('employee_email')
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
                                        <label class="col-md-2 text-left">Telefon(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_phone" placeholder="Zadejte telefon zaměstnance..." type="text" class="form-control @error('employee_phone') is-invalid @enderror" name="employee_phone" value="{{ old('employee_phone') }}"  autocomplete="employee_phone">
                                                @error('employee_phone')
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
                                        <label class="col-md-2 text-left">Pozice(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-child" aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_position" placeholder="Zadejte pozici zaměstnance..." type="text" class="form-control @error('employee_position') is-invalid @enderror" name="employee_position" value="{{ old('employee_position') }}"  autocomplete="employee_position">
                                                @error('employee_position')
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
                                        <label class="col-md-2 text-left">Město bydliště(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_city" placeholder="Zadejte město bydliště zaměstnance..." type="text" class="form-control @error('employee_city') is-invalid @enderror" name="employee_city" value="{{ old('employee_city') }}"  autocomplete="employee_city">
                                                @error('employee_city')
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
                                        <label class="col-md-2 text-left">Ulice bydliště</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_street" placeholder="Zadejte město bydliště zaměstnance..." type="text" class="form-control @error('employee_street') is-invalid @enderror" name="employee_street" value="{{ old('employee_street') }}"  autocomplete="employee_street">
                                                @error('employee_street')
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
                                        <label class="col-md-2 text-left">Login(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_login" placeholder="Zadejte login zaměstnance..." type="text" class="form-control @error('employee_login') is-invalid @enderror" name="employee_login" value="{{ old('employee_login') }}"  autocomplete="employee_login">
                                                @error('employee_login')
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
                                        <input type="button" style="margin-bottom: 15px;" class="btn btn-sm btn-warning pull-right" value="Generovat heslo" onClick="generator();">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-2 text-left">Heslo(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_password" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control @error('employee_password') is-invalid @enderror" name="employee_password" value="{{ old('employee_password') }}"  autocomplete="employee_password">
                                                @error('employee_password')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <span toggle="#employee_password" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpassword"></span>
                                            <script>
                                                $(".showpassword").click(function() {
                                                    $(this).toggleClass("fa-eye fa-eye-slash");
                                                    var input = $($(this).attr("toggle"));
                                                    if (input.attr("type") == "password") {
                                                        input.attr("type", "text");
                                                    } else {
                                                        input.attr("type", "password");
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-2 text-left">Heslo znovu(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                                </div>
                                                <input id="employee_password_confirm" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control @error('employee_password_confirm') is-invalid @enderror" name="employee_password_confirm" value="{{ old('employee_password_confirm') }}"  autocomplete="employee_password_confirm">
                                                @error('employee_password_confirm')
                                                <span class="invalid-feedback" role="alert">
                                                     <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <span toggle="#employee_password_confirm" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpasswordverify"></span>
                                            <script>
                                                function generator() {
                                                    var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                                    var password_tmp = "";
                                                    for (var x = 0; x < 10; ++x) { password_tmp += znaky.charAt(Math.floor(Math.random()*znaky.length));}
                                                    employee_password.value = password_tmp;
                                                    employee_password_confirm.value = password_tmp;
                                                }

                                                $(".showpasswordverify").click(function() {
                                                    $(this).toggleClass("fa-eye fa-eye-slash");
                                                    var input = $($(this).attr("toggle"));
                                                    if (input.attr("type") == "password") {
                                                        input.attr("type", "text");
                                                    } else {
                                                        input.attr("type", "password");
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-2 text-left">Poznámka</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                                </div>
                                                <textarea name="employee_note" placeholder="Zadejte poznámku k zaměstnanci..." id="employee_note" class="form-control @error('employee_note') is-invalid @enderror" value="{{ old('employee_note') }}"  autocomplete="employee_note"></textarea>

                                                @error('employee_note')
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
                                    <input type="file" name="employee_picture" id="fileEmployee" hidden />
                                    <label for="fileEmployee" style="padding: 12px 35px;border:3px solid #4aa0e6;border-radius: 48px;text-transform: uppercase;letter-spacing: 2px;font-weight: bold;color:#4aa0e6;" id="selector2">Vyberte Fotku</label>
                                    <script type="text/javascript">
                                        var loaderEmployee = function(e){
                                            let file = e.target.files;
                                            let show="<span> Vybrán soubor: </span>" + file[0].name;
                                            let output = document.getElementById("selector2");
                                            output.innerHTML = show;
                                            output.classList.add("active");
                                        };
                                        let fileInputEmployee = document.getElementById("fileEmployee");
                                        fileInputEmployee.addEventListener("change",loaderEmployee);
                                    </script>
                                </div>
                                <div class="text-center" style="font-size: 16px;margin-bottom: 5px;background-color: #1d643b; padding: 5px 10px;border-radius: 10px;">Výběr jazyků, které zaměstnanec ovládá:</div>
                                <div class="form-check text-center" style="color:white;margin-bottom:15px;background-color: #1d643b;border-radius: 10px;padding:5px 10px;">
                                    @if (count($jazyky) == 0)
                                        <div class="alert alert-danger alert-block text-center">
                                            <strong>Nedefinoval jste žádný jazyk.</strong>
                                        </div>
                                    @endif
                                    @foreach($jazyky as $moznost)
                                        <input type="checkbox" class="form-check-input" id="nazevJazykyEmployee" name="jazyky[]" value="{{$moznost->language_id}}">
                                        <label class="form-check-label" style="font-size: 17px;" for="nazevJazykyEmployee"> {{$moznost->language_name}}</label><br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit"  style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat zaměstnance" />
                                    <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Formular pro pridani smeny !-->
        <div>
            <div class="modal fade" id="formAddShift" style="color:white;">
                <div class="modal-dialog modal-lg">
                    <form method="post" action="{{route('addShift')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                 <span class="col-md-12 text-center">
                                      <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat novou směnu</h4>
                                 </span>
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
                                        <label class="col-md-2 text-left">Začátek směny(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                                </div>
                                                <input type="datetime-local" class="form-control @error('shift_start') is-invalid @enderror" name="shift_start" id="shift_start" value="{{ old('shift_start') }}" autocomplete="shift_start" autofocus>
                                                @error('shift_start')
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
                                        <label class="col-md-2 text-left">Konec směny(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                                </div>
                                                <input type="datetime-local" class="form-control @error('shift_end') is-invalid @enderror" name="shift_end" id="shift_end" value="{{ old('shift_end') }}" autocomplete="shift_end" autofocus>
                                                @error('shift_end')
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
                                        <label class="col-md-2 text-left">Místo(<span class="text-danger">*</span>)</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-building" aria-hidden="true"></i></div>
                                                </div>
                                                <input id="shift_place" placeholder="Zadejte lokaci směny..." type="text" class="form-control @error('shift_place') is-invalid @enderror" name="shift_place" value="{{ old('shift_place') }}"  autocomplete="shift_place">
                                                @error('shift_place')
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
                                        <label class="col-md-2 text-left">Důležitost</label>
                                        <div class="col-md-10">
                                        <select name="shiftImportance" id="shiftImportance" style="color:black;text-align-last: center;" class="form-control" data-dependent="state">
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
                                        <label class="col-md-2 text-left">Poznámka</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                                </div>
                                                <textarea name="shift_note" placeholder="Zadejte poznámku ke směně..." id="shift_note" class="form-control @error('shift_note') is-invalid @enderror" value="{{ old('shift_note') }}"  autocomplete="shift_note"></textarea>
                                                @error('shift_note')
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
                                    <input type="submit"  style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat směnu" />
                                    <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Konec obsahu -->
    </div>
</div>




@if(Session::has('errors'))
    <script>
        $(document).ready(function(){
            $('#formAddEmployee').modal({show: true});
        });
    </script>
@endif

@if(Session::has('errory'))
    <script>
        $(document).ready(function(){
            $('#formAddLanguage').modal({show: true});
        });
    </script>
@endif


@if(Session::has('erroryShift'))
    <script>
        $(document).ready(function(){
            $('#formAddShift').modal({show: true});
        });
    </script>
@endif

<script type="text/javascript">
    $(document).ready(function() {
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });

        /* Zobrazeni smen urcenych k mazani */
        $('.modelClose').on('click', function(){
            $('#DeleteShiftModal').hide();
        });
        $('body').on('click', '#getDeleteShiftData', function(e) {
            $.ajax({
                url: "/shiftsDashboard/show",
                method: 'GET',
                success: function(result) {
                    console.log(result);
                    $('#DeleteShiftBody').html(result.html);
                    $('#DeleteShiftModal').show();
                }
            });
        });

        /* Vypsani moznosti souborů pro smazani souboru v dashboard */
        $('.modelClose').on('click', function(){
            $('#formDeleteFile').hide();
        });
        $('body').on('click', '#getDeleteFileDataCheckBox', function(e) {
            $.ajax({
                url: "/dashboard/googleFilesCheckboxes/show",
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
                url: "/dashboard/googleFoldersOptions/show",
                method: 'GET',
                success: function(result) {
                    console.log(result);
                    $('#FileUploadBody').html(result.html);
                    $('#formUpload').show();
                }
            });
        });

        /* Zobrazeni zamestnancu urcenych k mazani */
        $('.modelClose').on('click', function(){
            $('#formDeleteEmployee').hide();
        });
        $('body').on('click', '#getDeleteEmployeeData', function(e) {
            $.ajax({
                url: "/employeesDashboard/show",
                method: 'GET',
                success: function(result) {
                    console.log(result);
                    $('#DeleteEmployeeBody').html(result.html);
                    $('#formDeleteEmployee').show();
                }
            });
        });
    });

    function zmenaIkonky(x) {
        x.classList.toggle("fa-sort-alpha-desc");
        x.classList.toggle("fa-sort-alpha-asc");
    }

    function zmenaIkonkyCisla(x) {
        x.classList.toggle("fa-sort-numeric-asc");
        x.classList.toggle("fa-sort-numeric-desc");
    }

    function Search() {
        var input, filter, table, tr, td, td2, td3, td4, i, txtValue, txtValue2, txtValue3, txtValue4;
        input = document.getElementById("vyhledavac");
        filter = input.value.toUpperCase();
        table = document.getElementById("show_table");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td3 = tr[i].getElementsByTagName("td")[0];
            td = tr[i].getElementsByTagName("td")[1];
            td2 = tr[i].getElementsByTagName("td")[2];
            td4 = tr[i].getElementsByTagName("td")[3];
            if (td || td2 || td3 || td4) {
                txtValue = td.textContent || td.innerText;
                txtValue2 = td2.textContent || td2.innerText;
                txtValue3 = td3.textContent || td3.innerText;
                txtValue4 = td4.textContent || td4.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1
                    || txtValue3.toUpperCase().indexOf(filter) > -1 || txtValue4.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }

            }

        }
    }

    function sortTable(n,ikonka) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("show_table");
        switching = true;
        dir = "asc";
        while (switching) {
            switching = false;
            rows = table.rows;

            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];

                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch= true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
</body>
</html>
