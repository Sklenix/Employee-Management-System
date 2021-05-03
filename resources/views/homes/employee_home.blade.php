@extends('layouts.employee_dashboard')
@section('content')
<!-- Nazev souboru: employee_home.blade.php -->
<!-- Autor: Pavel Sklenář (xsklen12) -->
<!-- Tento soubor reprezentuje obsah domovske stranky v ramci uzivatele s roli zamestnance -->
<!-- Definice moznosti na domovske strance v ramci uctu s roli zamestnance -->
<div class="row menuZamestnanec" style="padding-top:40px;padding-bottom: 60px;">
    <div class="col-12 text-center">
        <!-- Definice hlasek -->
        <div class="offset-2 col-8 text-center">
            @if($zprava = Session::get('fail'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$zprava}}</strong>
                </div>
            @endif
            @if($zprava = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$zprava}}</strong>
                </div>
            @endif
        </div>
    </div>
    <!-- Zacatek definovani samotneho obsahu -->
    <div class="col-lg-2 col-md-2 text-center">
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{route('shifts.currentShiftsEmployee')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3558# -->
                <img src="{{asset("images/shift_current.png")}}" alt="Seznam aktuálních směn ikonka" height="128" width="128" title="Seznam aktuálních směn" style="margin-bottom:15px;"/>
                <h4>Aktuální směny</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('shifts.AllShiftsEmployee')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/business-set-1/calendar-clock-icon-17782# --->
                <img src="{{asset("images/shift_list.png")}}" alt="Směny ikonka" height="128" width="128" title="Seznam všech směn" style="margin-bottom:15px;"/>
                <h4>Všechny směny</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('employee_generator.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/vector-file-types-icons/pdf-icon-2304# -->
                <img src="{{asset("images/pdf_generator.png")}}" alt="Generování ikonka" height="128" width="128" title="Generátor PDF" style="margin-bottom: 15px;"/>
                <h4>Generátor souborů</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('employee_statistics.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Muhammad Haq, odkaz: https://freeicons.io/business-collection-icon/pie-chart-icon-22286# -->
                <img src="{{asset("images/statistiky.png")}}" alt="Statistiky ikonka" height="128" width="128" title="Statistiky" style="margin-bottom:15px;"/>
                <h4>Statistiky</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center">
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('employee_vacations.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Oscar EstMont, odkaz: https://freeicons.io/weather-2/icon-sun-lineal-color-icon-28915 -->
                <img src="{{asset("images/vacation.png")}}" alt="Dovolená ikonka" height="128" width="128" title="Seznam dovolených" style="margin-bottom:15px;"/>
                <h4>Centrum dovolených</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('employee_diseases.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Shabna Ashraf, odkaz: https://freeicons.io/medical-care-and-health-set/viruses-virus-icon-39031# -->
                <img src="{{asset("images/disease.png")}}" alt="Nemocenské ikonka" height="128" width="128" title="Seznam nemocenských" style="margin-bottom:15px;"/>
                <h4>Centrum nemocenských</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('employee_reports.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/material-icons-content-2/report-icon-16214# -->
                <img src="{{asset("images/report.png")}}" alt="Nahlášení ikonka" height="128" width="128" title="Seznam nahlášení" style="margin-bottom:15px;"/>
                <h4>Centrum nahlášení</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('employee_injuries.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril www.wishforge.games, odkaz https://freeicons.io/healthcare-2/healthcare-medical-injury-icon-43042# -->
                <img src="{{asset("images/employee_injury.png")}}" alt="Nemocenské ikonka" height="128" width="128" title="Seznam zranění" style="margin-bottom:15px;"/>
                <h4>Historie zranění</h4>
            </div>
        </a>
    </div>
    @if($employee_url != "") <!-- Pokud zamestnanec nema nasdileny Google Drive, tak se mu nezobrazi Google Drive moznosti -->
        <div class="col-lg-2 col-md-2 text-center">
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a style="cursor: pointer;color:black;text-decoration: none"  data-toggle="modal" data-target="#formAddFolder">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/file,-folder-and-image-icons/folder-icon-3250# -->
                    <img src="{{asset("images/folder_add.png")}}" alt="Vytvořit složku na Google Drive ikonka" height="128" width="128" title="Vytvořit složku na Google Drive" style="margin-bottom:15px;"/>
                    <h4>Vytvořit složku<br>na Google Drive</h4>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a style="cursor: pointer;color:black;text-decoration: none"  data-toggle="modal" data-target="#formDeleteFile" id="getDeleteFileDataCheckBox">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/file,-folder-and-image-icons/folder-icon-3248# -->
                    <img src="{{asset("images/file_delete.png")}}" alt="Smazat složky/soubory na Google Drive ikonka" height="128" width="128" title="Smazat složky/soubory na Google Drive" style="margin-bottom:15px;"/>
                    <h4>Smazat soubory<br>na Google Drive</h4>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a style="cursor: pointer;color:black;text-decoration: none;"  data-toggle="modal" id="getUploadFileDataOptions" data-target="#formUpload">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/file,-folder-and-image-icons/folder-icon-3258# -->
                    <img src="{{asset("images/file_upload.png")}}" alt="Nahrát na Google Drive ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                    <h4>Nahrát soubor na Google Drive</h4>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a style="cursor: pointer;color:black;text-decoration: none;" href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->employee_url }}" target="_blank"> <!-- Odkazovani se na Google Drive uzivatele -->
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <!-- Ikonku vytvoril Reda, odkaz: https://freeicons.io/yellow-folders-with-web-icons/drive-google-drive-google-yellow-folder-work-archive-cloud-icon-52432# -->
                    <img src="{{asset("images/google_drive.png")}}" alt="Google Drive ikonka" height="128" width="128" title="Zobrazit Google Drive" style="margin-bottom:15px;"/>
                    <h4>Zobrazit <br>Google Drive</h4>
                </div>
            </a>
        </div>
    @endif
</div>
@endsection
