@extends('layouts.dashboard')

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
        <div class="modal" id="formAddEmployee">
            <div class="modal-dialog">
                <form method="post" id="zamestnanec_form" enctype="multipart/form-data">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_title">Přidat nového zaměstnance</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 text-right">Jméno(<span class="text-danger">*</span>)</label>
                                    <div class="col-md-8">
                                        <input type="text" name="zamestnanec_jmeno" id="zamestnanec_jmeno" class="form-control" />
                                        <span id="error_zamestnanec_jmeno" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 text-right">Příjmení(<span class="text-danger">*</span>)</label>
                                    <div class="col-md-8">
                                        <input type="text" name="zamestnanec_prijmeni" id="zamestnanec_prijmeni" class="form-control"></input>
                                        <span id="error_zamestnanec_prijmeni" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 text-right">E-mail(<span class="text-danger">*</span>)</label>
                                    <div class="col-md-8">
                                        <input type="text" name="zamestnanec_email" id="zamestnanec_email" class="form-control" />
                                        <span id="error_zamestnanec_email" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 text-right">Telefon(<span class="text-danger">*</span>)</label>
                                    <div class="col-md-8">
                                        <input type="text" name="zamestnanec_telefon" id="zamestnanec_telefon" class="form-control" />
                                        <span id="error_zamestnanec_telefon" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 text-right">Poznámka</label>
                                    <div class="col-md-8">
                                        <textarea name="zamestnanec_poznamka" id="zamestnanec_poznamka" class="form-control"></textarea>
                                        <span id="error_zamestnanec_poznamka" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <input type="hidden" name="id_zamestnance" id="id_zamestnance" />
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                            </div>

                        </div>

                </form>
            </div>
        </div>
    </div>



@endsection
