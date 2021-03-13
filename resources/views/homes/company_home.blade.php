@extends('layouts.company_dashboard')

@section('content')
    <section class="page-section" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;">

            <div class="row">
                <div class="col-12 text-center">
                    @if($message = Session::get('fail'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif

                    @if($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif
                </div>
                <div class="col-lg-2 col-md-2 text-center">

                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a data-toggle="modal" data-target="#formAddEmployee" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:75px;">
                        <img src="{{asset("images/employee_add.png")}}" alt="Ikonka přidat zaměstnance" height="128" width="128" title="Přidat zaměstnance" style="margin-bottom:15px;"/>
                        <h4>Přidat zaměstnance</h4>
                    </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a data-toggle="modal" data-target="#formDeleteEmployee" id="getDeleteEmployeeData" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:75px;">
                            <img src="{{asset("images/employee_delete.png")}}" alt="Ikonka smazat zaměstnance" height="128" width="128" title="Smazat zaměstnance" style="margin-bottom:15px;"/>
                            <h4>Smazat zaměstnance</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{route('employees.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:75px;">
                        <img src="{{asset("images/employee_list.png")}}" alt="Seznam zaměstnanců ikonka" height="128" width="128" title="Seznam Zaměstnanců" style="margin-bottom:15px;"/>
                        <h4>Seznam zaměstnanců</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="{{route('ratings.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                        <img src="{{asset("images/employee_rating.png")}}" alt="Hodnocení ikonka" height="128" width="128" title="Hodnocení zaměstnanců" style="margin-bottom:15px;"/>
                        <h4>Přehled hodnocení zaměstnanců</h4>
                    </div>
                    </a>
                </div>
                <div class="col-lg-1 col-md-1 text-center">
                    <i class="fa fa-info-circle" style="font-size: 30px;" data-toggle="tooltip" data-placement="left" title="
                    Právě se nacházíte na hlavním menu informačního systému Tozondo.
                     Vámi požadovanou akci lze realizovat buďto přes tlačítka v menu, nebo přes možnosti postranního menu nalevo.
                    Některé možnosti vyvolají pouze modální okno (přidání, smazání zaměstnance, směny nebo jazyka či vytvoření, nahrání, nebo smazání souborů/složek na Google Drive). V
                    postranním menu se některé možnosti dají dále rozkliknout, takovéto možnosti mají napravo od sebe speciální ikonku." aria-hidden="true"></i>
                </div>
                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a data-toggle="modal" data-target="#formAddShift" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/shift_add.png")}}" alt="Přidat směnu ikonka" height="128" width="128" title="Přidat směnu" style="margin-bottom:15px;"/>
                            <h4>Přidat směnu</h4>
                         </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a data-toggle="modal" data-target="#formDeleteShift" id="getDeleteShiftData" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/shift_delete.png")}}" alt="Odstranit směny ikonka" height="128" width="128" title="Odstranit směny" style="margin-bottom:15px;"/>
                            <h4>Odstranit směnu(y)</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{route('shifts.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/shift_list.png")}}" alt="Seznam Směn ikonka" height="128" width="128" title="Seznam směn" style="margin-bottom:15px;"/>
                            <h4>Seznam směn</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{route('attendance.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/shift_attendance.png")}}" alt="Docházky ikonka" height="128" width="128" title="Evidování docházky" style="margin-bottom: 15px;"/>
                            <h4>Docházka</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formAddFolder" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/folder_add.png")}}" alt="Google Drive Upload ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                            <h4>Vytvořit složku<br>na Google Drive</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formDeleteFile" id="getDeleteFileDataCheckBox" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/file_delete.png")}}" alt="Google Drive Upload ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                            <h4>Smazat soubor(y)<br>na Google Drive</h4>
                        </div>
                    </a>
                </div>


                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" id="getUploadFileDataOptions" data-target="#formUpload" style="color:black;text-decoration: none;">
                    <div style="padding-top: 50px;padding-bottom:50px;">
                        <img src="{{asset("images/file_upload.png")}}" alt="Google Drive Upload ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                        <h4>Nahrát soubor na Google Drive</h4>
                    </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->company_url }}" target="_blank" style="color:black;text-decoration: none;">
                    <div style="padding-top: 50px;padding-bottom:50px;">
                        <img src="{{asset("images/google_drive.png")}}" alt="Google Drive ikonka" height="128" width="128" title="Google Drive" style="margin-bottom:15px;"/>
                        <h4>Zobrazit <br>Google Drive</h4>
                    </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">

                </div>
                <div class="col-lg-8 col-md-8 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">
                    <div class="alert alert-danger" role="alert">
                        Ostatní
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">
                </div>
                <div class="col-lg-2 col-md-2 text-center" style="font-size: 30px;padding-bottom: 5px;padding-top:10px;">
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a data-toggle="modal" data-target="#formAddLanguage" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/language_add.png")}}" alt="Přidat jazyk ikonka" height="128" width="128" title="Přidat jazyk" style="margin-bottom:15px;"/>
                            <h4>Přidat jazyk</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a data-toggle="modal" data-target="#formDeleteLanguage" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/language_delete.png")}}" alt="Přidat jazyk ikonka" height="128" width="128" title="Odstranit jazyk/y" style="margin-bottom:15px;"/>
                            <h4>Odstranit jazyk(y)</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="{{route('generator.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/pdf_generator.png")}}" alt="Generování ikonka" height="128" width="128" title="Generování PDF" style="margin-bottom: 15px;"/>
                            <h4>Generátor souborů</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="{{route('statistics.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
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
                            <img src="{{asset("images/injury.png")}}" alt="Zranění ikonka" height="128" width="128" title="Zranění" style="margin-bottom:15px;"/>
                            <h4>Centrum zranění</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('vacations.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/vacation.png")}}" alt="Dovolená ikonka" height="128" width="128" title="Dovolené" style="margin-bottom:15px;"/>
                            <h4>Centrum dovolených</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('diseases.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/disease.png")}}" alt="Nemocenské ikonka" height="128" width="128" title="Nemocenské" style="margin-bottom:15px;"/>
                            <h4>Centrum nemocenských</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('reports.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/report.png")}}" alt="Nahlášení ikonka" height="128" width="128" title="Nahlášení" style="margin-bottom:15px;"/>
                            <h4>Centrum nahlášení</h4>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>
@endsection
