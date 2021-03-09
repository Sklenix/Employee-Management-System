@extends('layouts.employee_dashboard')
@section('content')
    <section class="page-section"  id="vlastnosti" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
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
                    <a href="{{route('shifts.currentShiftsEmployee')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/shift_current.png")}}" alt="Seznam aktuálních směn ikonka" height="128" width="128" title="Seznam aktuálních směn" style="margin-bottom:15px;"/>
                            <h4>Aktuální směny</h4>
                        </div>
                    </a>
                </div>


                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('shifts.AllShiftsEmployee')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/shift_list.png")}}" alt="Směny ikonka" height="128" width="128" title="Směny" style="margin-bottom:15px;"/>
                            <h4>Všechny směny</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('employee_generator.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/pdf_generator.png")}}" alt="Generování ikonka" height="128" width="128" title="Generování PDF, Excel" style="margin-bottom: 15px;"/>
                            <h4>Generátor souborů</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('employee_statistics.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
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
                            <img src="{{asset("images/vacation.png")}}" alt="Dovolená ikonka" height="128" width="128" title="Dovolené" style="margin-bottom:15px;"/>
                            <h4>Centrum dovolených</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('employee_diseases.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/disease.png")}}" alt="Nemocenské ikonka" height="128" width="128" title="Nemocenské" style="margin-bottom:15px;"/>
                            <h4>Centrum nemocenských</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('employee_reports.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/report.png")}}" alt="Nahlášení ikonka" height="128" width="128" title="Nahlášení" style="margin-bottom:15px;"/>
                            <h4>Centrum nahlášení</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('employee_injuries.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/employee_injury.png")}}" alt="Nemocenské ikonka" height="128" width="128" title="Nemocenské" style="margin-bottom:15px;"/>
                            <h4>Historie zranění</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formAddFolder" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/folder_add.png")}}" alt="Vytvořit složku na Google Drive ikonka" height="128" width="128" title="Vytvořit složku na Google Drive" style="margin-bottom:15px;"/>
                            <h4>Vytvořit složku<br>na Google Drive</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formDeleteFile" id="getDeleteFileDataCheckBox" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/file_delete.png")}}" alt="Smazat složky/soubory na Google Drive ikonka" height="128" width="128" title="Smazat složky/soubory na Google Drive" style="margin-bottom:15px;"/>
                            <h4>Smazat soubor(y)<br>na Google Drive</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" id="getUploadFileDataOptions" data-target="#formUpload" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/file_upload.png")}}" alt="Nahrát na Google Drive ikonka" height="128" width="128" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                            <h4>Nahrát soubor na Google Drive</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->employee_drive_url }}" target="_blank" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/google_drive.png")}}" alt="Google Drive ikonka" height="128" width="128" title="Zobrazit Google Drive" style="margin-bottom:15px;"/>
                            <h4>Zobrazit <br>Google Drive</h4>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
