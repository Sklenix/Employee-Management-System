@extends('layouts.company_dashboard')

@section('content')
    <!-- Vlastnosti systému-->
    <section class="page-section"  id="vlastnosti" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;">
            <div class="row">
                <div class="col-12 text-center">
                    @if($message = Session::get('successUpload'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif

                    @if($message = Session::get('successCreateFolder'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif

                    @if($message = Session::get('successDelete'))
                        <div class="alert alert-success alert-block">
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
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formAddEmployee" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                        <img src="{{asset("images/zamestnanecAddImage.png")}}" alt="Ikonka Přidat zaměstnance" height="100" width="100" title="Přidat zaměstnance" style="margin-bottom:15px;"/>
                        <h4>Přidat zaměstnance</h4>
                    </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/zamestnanecList.png")}}" alt="Seznam zaměstnanců ikonka" height="100" width="100" title="Seznam Zaměstnanců" style="margin-bottom:15px;"/>
                        <h4>Seznam zaměstnanců</h4>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/hodnoceni.png")}}" alt="Hodnocení ikonka" height="100" width="90" title="Hodnocení zaměstnanců" style="margin-bottom:15px;"/>
                        <h4>Přehled hodnocení zaměstnanců</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/dochazkaImage.png")}}" alt="Docházky ikonka" height="90" width="90" title="Evidování docházky" style="margin-bottom: 15px;"/>
                        <h4>Docházka</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <div class="mt-5">
                        <img src="{{asset("images/smenyAdd.png")}}" alt="Směny ikonka" height="100" width="100" title="Směny" style="margin-bottom:15px;"/>
                        <h4>Přidat směnu</h4>
                    </div>
                </div>


                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/smenyList.png")}}" alt="Seznam Směn ikonka" height="100" width="100" title="Seznam směn" style="margin-bottom:15px;"/>
                        <h4>Seznam směn</h4>
                    </div>
                </div>


                <div class="col-lg-4 col-md-4 text-center">
                </div>


                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formAddFolder" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/addFolder.png")}}" alt="Google Drive Upload ikonka" height="100" width="100" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                            <h4>Vytvořit složku<br>na Google Drive</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formDeleteFile" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/deleteFile.png")}}" alt="Google Drive Upload ikonka" height="100" width="100" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                            <h4>Smazat soubor<br>na Google Drive</h4>
                        </div>
                    </a>
                </div>


                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a style="cursor: pointer"  data-toggle="modal" data-target="#formUpload" style="color:black;text-decoration: none;">
                    <div style="padding-top: 50px;padding-bottom:50px;">
                        <img src="{{asset("images/upload.png")}}" alt="Google Drive Upload ikonka" height="100" width="130" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                        <h4>Nahrát soubor na Google Drive</h4>
                    </div>
                    </a>
                </div>


                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->company_url }}" target="_blank" style="color:black;text-decoration: none;">
                    <div style="padding-top: 50px;padding-bottom:50px;">
                        <img src="{{asset("images/googleDrive.png")}}" alt="Google Drive ikonka" height="100" width="100" title="Google Drive" style="margin-bottom:15px;"/>
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
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/statistiky.png")}}" alt="Statistiky ikonka" height="100" width="100" title="Statistiky" style="margin-bottom:15px;"/>
                        <h4>Statistiky</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/pdfImage.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 15px;"/>
                        <h4>Generátor souborů</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/settings.png")}}" alt="Nastavení ikonka" height="100" width="100" title="Nastavení" style="margin-bottom:15px;"/>
                        <h4>Nastavení</h4>
                    </div>
                </div>



                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/report.png")}}" alt="Nahlášení ikonka" height="100" width="90" title="Nahlášení" style="margin-bottom:15px;"/>
                        <h4>Nahlášení</h4>
                    </div>
                </div>


            </div>
        </div>
    </section>
    <!-- Modul pro google Upload !-->
    <div>
        <div class="modal fade" id="formUpload" role="dialog">
            <div class="modal-dialog">
                <form method="post" action="{{route('uploadDrive')}}" id="zamestnanec_form" enctype="multipart/form-data">

                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">

                            <span class="col-md-12 text-center">
                            <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Nahrát soubor na Google Drive</h5>

                            </span>
                           <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <select name="slozky" required id="slozky" style="color:black" class="form-control input-lg dynamic" data-dependent="state">
                                    <option value="">Vyber složku</option>
                                    <option value="{{ Auth::user()->company_url}}">/</option>
                                    @foreach($slozky as $slozka)
                                        <option value="{{$slozka->id}}">{{$slozka->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group nahratTlacitko">
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

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="id_zamestnance" id="id_zamestnance" />
                                    <input type="hidden" name="action" id="action" value="Add" />
                                    <input type="submit" name="button_action" id="button_action" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Nahrát" />
                                    <button type="button" class="btn btn-modalClose" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                                </div>

                            </div>

                        </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Modul pro google smazani souboru !-->
    <div>
        <div class="modal fade" id="formDeleteFile" role="dialog">
            <div class="modal-dialog">
                <form method="post" action="{{route('deleteFile')}}" id="zamestnanec_form" enctype="multipart/form-data">

                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">

                            <span class="col-md-12 text-center">
                            <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Smazat soubor na Google Drive</h5>

                            </span>
                            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <select name="slozkyDelete" required id="slozkyDelete" style="color:black" class="form-control input-lg dynamic" data-dependent="state">
                                    <option value="">Vyber soubor pro smazání</option>
                                    @foreach($slozkyDelete as $slozka)
                                        <option value="{{$slozka->id}}">{{$slozka->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="id_zamestnance" id="id_zamestnance" />
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="submit" name="button_action" id="button_action" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Smazat" />
                                <button type="button" class="btn btn-modalClose" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                            </div>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Modul pro google pridani slozky !-->
    <div>
        <div class="modal fade" id="formAddFolder" role="dialog">
            <div class="modal-dialog">
                <form method="post" action="{{route('createFolder')}}" id="zamestnanec_form" enctype="multipart/form-data">

                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">

                            <span class="col-md-12 text-center">
                            <h5 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit složku na Google Drive</h5>

                            </span>
                            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label style="color:rgba(255, 255, 255, 0.90);font-size: 15px;" for="nazev">Jméno složky:</label>
                                <input type="text" class="form-control" name="nazev" id="nazev" required />
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="id_zamestnance" id="id_zamestnance" />
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="submit" name="button_action" id="button_action" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat" />
                                <button type="button" class="btn btn-modalClose" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal">Zavřít</button>
                            </div>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
<!-- Modul pro zamestnance !-->
    <div>

        <div class="modal fade" id="formAddEmployee" style="color:white;">
            <div class="modal-dialog  modal-lg">
                <form method="post" action="{{route('addEmployee')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                             <span class="col-md-12 text-center">
                          <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat nového zaměstnance</h4>
                             </span>
                        </div>

                        <!-- Modal body -->
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
                                    <label class="col-md-2 text-left">Login</label>
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
                            <div class="form-group nahratTlacitko">
                                <input type="file" name="employee_picture" id="fileEmployee" hidden />
                                <label for="fileEmployee" style="padding: 12px 35px;border:3px solid #4aa0e6;border-radius: 48px;text-transform: uppercase;letter-spacing: 2px;font-weight: bold;color:#4aa0e6;" id="selector2">Vyberte Fotku</label>
                                <script>
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

                        </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="button_action" id="button_action" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat zaměstnance" />
                                    <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                                </div>
                            </div>

                        </div>

                </form>
            </div>
        </div>
    </div>



@endsection
