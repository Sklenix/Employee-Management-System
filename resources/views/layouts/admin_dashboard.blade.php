<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>

        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }

        .list-group a { text-decoration: none; font-weight: 100; text-transform: uppercase; }
        .fill .list-group a { position: relative; }

        .fill .list-group a:after { position: absolute; bottom: 0; left: 0; right: 0; margin: auto;
            width: 0%; content: '.'; color: transparent; height: 1px; }

        .fill .list-group a:hover { z-index: 1; }

        .fill .list-group a:hover:after { z-index: -10; animation: fill 1s forwards;
            -webkit-animation: fill 1s forwards; -moz-animation: fill 1s forwards; opacity: 1; }

        @-webkit-keyframes fill {
            0% { width: 0%; height: 1px; }
            50% { width: 100%; height: 1px; }
            100% { width: 100%; height: 100%; background: #6495ED; }
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

        table.company-list.dataTable tbody tr:hover {
            background-color: #A9A9A9;
        }

        table.company-list.dataTable tbody tr:hover > .sorting_1 {
            background-color: #A9A9A9;
        }

        table.company-list.dataTable thead{
            background-color: #333;
            color:white;
        }

    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tozondo @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/0.7.0/chartjs-plugin-datalabels.js" integrity="sha512-yvu1r8RRJ0EHKpe1K3pfHF7ntjnDrN7Z66hVVGB90CvWbWTXevVZ8Sy1p+X4sS9M9Z+Q9tZu1GjGzFFmu/pAmg==" crossorigin="anonymous"></script>
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
            <a href="{{route('homeAdmin')}}" class="border-bottom {{ request()->routeIs('homeAdmin') ? 'active' : '' }} border-bottom" style="padding-left:60px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 20px;padding-top: 20px;font-size:17px;"><i class="fa fa-cube" aria-hidden="true"></i> Dashboard</a>
            <a href="{{route('admin_statistics.index')}}" class="{{ request()->routeIs('admin_statistics.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-pie-chart" aria-hidden="true"></i> Statistiky</a>
            <a href="{{route('admin_companies.index')}}" class="{{ request()->routeIs('admin_companies.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-university" aria-hidden="true"></i> Seznam firem</a>
            <a href="{{route('admin_generator.index')}}" class="{{ request()->routeIs('admin_generator.index') ? 'active' : '' }}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-rocket" aria-hidden="true"></i> Generátor souborů</a>
            <a href="https://drive.google.com/drive/u/1/folders/1KsP-NAdwBpFaONID4CxTdY4jeKuWJFX4" target="_blank" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-server" aria-hidden="true"></i> Google Drive</a>
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
                            <i class="fa fa-user" style="font-size: 17px;margin-right: 4px;" aria-hidden="true"></i> {{ Auth::user()->admin_name }} {{ Auth::user()->admin_surname }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('showAdminProfileData')}}">Profil admina</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Odhlásit se
                            </a>
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
    </div>

</div>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>

</body>
</html>
