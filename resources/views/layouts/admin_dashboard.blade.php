<!doctype html>
<html lang="cs">
<head>
    <!-- Nazev souboru: admin_dashboard.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje cele menu (vcetne postranniho panelu) a take element, do ktereho se nasledne vkladaji jednotlive moznosti domovske stranky. Tento soubor se vaze na roli admina -->
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
    <!-- import datovych tabulek, jquery, javascriptu, chart.js a chart.js datalabels plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/0.7.0/chartjs-plugin-datalabels.js" integrity="sha512-yvu1r8RRJ0EHKpe1K3pfHF7ntjnDrN7Z66hVVGB90CvWbWTXevVZ8Sy1p+X4sS9M9Z+Q9tZu1GjGzFFmu/pAmg==" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
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
        <!-- Definice jednotlivych polozek postranniho panelu, paklize se uzivatel nachazi v nejake polozce, tak je vybarvena cervene, viz https://laravel.com/docs/8.x/requests -->
        <div class="list-group">
            <a href="{{route('homeAdmin')}}" class="{{request()->routeIs('homeAdmin') ? 'active' : ''}} border-bottom" style="padding-left:60px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 20px;padding-top: 20px;font-size:17px;"><i class="fa fa-cube"></i> Dashboard</a>
            <a href="{{route('admin_statistics.index')}}" class="{{request()->routeIs('admin_statistics.index') ? 'active' : ''}}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-pie-chart"></i> Statistiky</a>
            <a href="{{route('admin_companies.index')}}" class="{{request()->routeIs('admin_companies.index') ? 'active' : ''}}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-university"></i> Seznam firem</a>
            <a href="{{route('admin_generator.index')}}" class="{{request()->routeIs('admin_generator.index') ? 'active' : ''}}" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-rocket"></i> Generátor souborů</a>
            <a href="https://drive.google.com/drive/u/1/folders/1KsP-NAdwBpFaONID4CxTdY4jeKuWJFX4" target="_blank" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-server"></i> Google Drive</a>
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
                        <a class="nav-link dropdown-toggle" id="rozbalovaciNabidka" role="button" style="color:rgba(255, 255, 255, 0.95);" data-toggle="dropdown"><i class="fa fa-user" style="font-size: 17px;margin-right: 4px;"></i> {{ Auth::user()->admin_name }} {{ Auth::user()->admin_surname }} </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('showAdminProfileData')}}">Profil admina</a>
                            <a class="dropdown-item" href="{{ route('adminLogOut') }}" onclick="event.preventDefault();document.getElementById('logoutForm').submit();">Odhlásit se</a>
                            <form id="logoutForm" action="{{ route('adminLogOut') }}" method="POST"> @csrf </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Definice obsahu uvnitr layoutu -->
        <div class="container-fluid" style="padding: 0;margin:0;">
            @yield('content')
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        /* Po zmacknuti na cervene tlacitko se postranni panel schova */
        $("#zmacknutiSchovani").click(function() { $("#obsah").toggleClass("toggled");} );
    });
</script>
</body>
</html>
