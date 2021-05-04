@extends('layouts.company_dashboard')
@section('content')
<!-- Nazev souboru: company_home.blade.php -->
<!-- Autor: Pavel Sklenář (xsklen12) -->
<!-- Tento soubor reprezentuje obsah domovske stranky v ramci uzivatele s roli firmy -->
<!-- Definice moznosti na domovske strance v ramci uctu s roli firmy -->
<div class="row menuFirma" style="padding-top:40px;padding-bottom: 60px;">
    <div class="col-12 text-center">
        <!-- Definice pro zobrazeni hlasek pro uzivatele s roli firmy -->
        <div class="offset-2 col-8 text-center">
            @if($zprava = Session::get('fail'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$zprava}}</strong>
                </div>
            @endif
            @if(Session::has('status'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>Vaše heslo bylo úspěšně změněno.</strong>
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
        <a data-toggle="modal" data-target="#formAddEmployee" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:75px;">
            <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/user-icons/user-icon-3583# -->
            <img src="{{asset("images/employee_add.png")}}" alt="Ikonka vytvořit zaměstnance" height="128" width="128" title="Vytvořit zaměstnance" style="margin-bottom:15px;"/>
            <h4>Vytvořit zaměstnance</h4>
        </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a data-toggle="modal" data-target="#formDeleteEmployee" id="getDeleteEmployeeData" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:75px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/user-icons/user-icon-3586# -->
                <img src="{{asset("images/employee_delete.png")}}" alt="Ikonka smazat zaměstnance" height="128" width="128" title="Smazat zaměstnance" style="margin-bottom:15px;"/>
                <h4>Odstranit zaměstnance</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{route('employees.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:75px;">
            <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/user-icons/user-icon-3593# -->
            <img src="{{asset("images/employee_list.png")}}" alt="Seznam zaměstnanců ikonka" height="128" width="128" title="Seznam Zaměstnanců" style="margin-bottom:15px;"/>
            <h4>Seznam zaměstnanců</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek" style="">
        <a href="{{route('ratings.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
            <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/user-icons/user-icon-3595 -->
            <img src="{{asset("images/employee_rating.png")}}" alt="Hodnocení ikonka" height="128" width="128" title="Hodnocení zaměstnanců" style="margin-bottom:15px;"/>
            <h4>Přehled hodnocení zaměstnanců</h4>
        </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center">
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a data-toggle="modal" data-target="#formAddShift" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3550# -->
                <img src="{{asset("images/shift_add.png")}}" alt="Vytvořit směnu ikonka" height="128" width="128" title="Vytvořit směnu" style="margin-bottom:15px;"/>
                <h4>Vytvořit směnu</h4>
             </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a data-toggle="modal" data-target="#formDeleteShift" id="getDeleteShiftData" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3556# -->
                <img src="{{asset("images/shift_delete.png")}}" alt="Odstranit směny ikonka" height="128" width="128" title="Odstranit směny" style="margin-bottom:15px;"/>
                <h4>Odstranit směny</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{route('shifts.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/business-set-1/calendar-clock-icon-17782# -->
                <img src="{{asset("images/shift_list.png")}}" alt="Seznam Směn ikonka" height="128" width="128" title="Seznam směn" style="margin-bottom:15px;"/>
                <h4>Seznam směn</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{route('attendance.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril MD Badsha Meah, odkaz: https://freeicons.io/business-set-1/calendar-appointment-event-date-icon-38590# -->
                <img src="{{asset("images/shift_attendance.png")}}" alt="Docházky ikonka" height="128" width="128" title="Evidování docházky" style="margin-bottom: 15px;"/>
                <h4>Docházka</h4>
            </div>
        </a>
    </div>
    @if($company_url != "")  <!-- Pokud si firma aktivovala Google Drive, tak ji neni zobrazen -->
        <div class="col-lg-2 col-md-2 text-center">
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a data-toggle="modal" data-target="#formAddFolder" style="cursor: pointer;color:black;text-decoration: none;">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/file,-folder-and-image-icons/folder-icon-3250# -->
                    <img src="{{asset("images/folder_add.png")}}" alt="Google Drive Upload ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                    <h4>Vytvořit složku<br>na Google Drive</h4>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a data-toggle="modal" data-target="#formDeleteFile" id="getDeleteFileDataCheckBox" style="cursor: pointer;color:black;text-decoration: none;">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/file,-folder-and-image-icons/folder-icon-3248# -->
                    <img src="{{asset("images/file_delete.png")}}" alt="Google Drive Upload ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                    <h4>Odstranit soubory<br>na Google Drive</h4>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a data-toggle="modal" id="getUploadFileDataOptions" data-target="#formUpload" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/file,-folder-and-image-icons/folder-icon-3258# -->
                <img src="{{asset("images/file_upload.png")}}" alt="Google Drive Upload ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                <h4>Nahrát soubor na Google Drive</h4>
            </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 text-center ramecek" style="">
            <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->company_url }}" target="_blank" style="color:black;text-decoration: none;"> <!-- Odkazovani se na Google Drive uzivatele -->
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Reda, odkaz: https://freeicons.io/yellow-folders-with-web-icons/drive-google-drive-google-yellow-folder-work-archive-cloud-icon-52432# -->
                <img src="{{asset("images/google_drive.png")}}" alt="Google Drive ikonka" height="128" width="128" title="Google Drive" style="margin-bottom:15px;"/>
                <h4>Zobrazit <br>Google Drive</h4>
            </div>
            </a>
        </div>
    @endif
    <div class="col-lg-2 col-md-2 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">
    </div>
    <div class="col-lg-8 col-md-8 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">
        <div class="alert alert-danger" role="alert">
            Ostatní <!-- Sekce ostatni -->
        </div>
    </div>
    <div class="col-lg-2 col-md-2 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">
    </div>
    <div class="col-lg-2 col-md-2 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a data-toggle="modal" data-target="#formAddLanguage" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Free Preloaders, odkaz: https://freeicons.io/material-icons-actions/language-icon-8495# -->
                <img src="{{asset("images/language_add.png")}}" alt="Vytvořit jazyk ikonka" height="128" width="128" title="Přidat jazyk" style="margin-bottom:15px;"/>
                <h4>Vytvořit jazyk</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a data-toggle="modal" data-target="#formDeleteLanguage" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Free Preloaders, odkaz: https://freeicons.io/material-icons-actions/language-icon-8495# -->
                <img src="{{asset("images/language_delete.png")}}" alt="Vytvořit jazyk ikonka" height="128" width="128" title="Odstranit jazyk/y" style="margin-bottom:15px;"/>
                <h4>Odstranit jazyky</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek" style="">
        <a href="{{route('generator.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/vector-file-types-icons/pdf-icon-2304# -->
                <img src="{{asset("images/pdf_generator.png")}}" alt="Generování ikonka" height="128" width="128" title="Generování PDF" style="margin-bottom: 15px;"/>
                <h4>Generátor souborů</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek" style="">
        <a href="{{route('statistics.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Muhammad Haq, odkaz: https://freeicons.io/business-collection-icon/pie-chart-icon-22286# -->
                <img src="{{asset("images/statistics.png")}}" alt="Statistiky ikonka" height="128" width="128" title="Statistiky" style="margin-bottom:15px;"/>
                <h4>Statistiky</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2" style="">
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{route('injuries.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril www.wishforge.games, odkaz: https://freeicons.io/healthcare-2/healthcare-medical-injury-icon-43042# -->
                <img src="{{asset("images/injury.png")}}" alt="Zranění ikonka" height="128" width="128" title="Zranění" style="margin-bottom:15px;"/>
                <h4>Centrum zranění</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('vacations.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Oscar EstMont, odkaz: https://freeicons.io/weather-2/icon-sun-lineal-color-icon-28915 -->
                <img src="{{asset("images/vacation.png")}}" alt="Dovolená ikonka" height="128" width="128" title="Dovolené" style="margin-bottom:15px;"/>
                <h4>Centrum dovolených</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('diseases.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril Shabna Ashraf, odkaz: https://freeicons.io/medical-care-and-health-set/viruses-virus-icon-39031# -->
                <img src="{{asset("images/disease.png")}}" alt="Nemocenské ikonka" height="128" width="128" title="Nemocenské" style="margin-bottom:15px;"/>
                <h4>Centrum nemocenských</h4>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-2 text-center ramecek">
        <a href="{{ route('reports.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
            <div style="padding-top: 50px;padding-bottom:50px;">
                <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/material-icons-content-2/report-icon-16214# -->
                <img src="{{asset("images/report.png")}}" alt="Nahlášení ikonka" height="128" width="128" title="Nahlášení" style="margin-bottom:15px;"/>
                <h4>Centrum nahlášení</h4>
            </div>
        </a>
    </div>
</div>
@endsection
