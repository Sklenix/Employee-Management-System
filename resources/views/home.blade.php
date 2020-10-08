@extends('layouts.dashboard')

@section('content')
    <!-- Vlastnosti systému-->
    <section class="page-section"  id="vlastnosti" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;">
            <div class="row">
                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/zamestnanecAddImage.png")}}" alt="Ikonka Přidat zaměstnance" height="100" width="100" title="Přidat zaměstnance" style="margin-bottom:15px;"/>
                        <h4>Přidat zaměstnance</h4>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/zamestnanecList.png")}}" alt="Seznam zaměstnanců ikonka" height="100" width="100" title="Seznam Zaměstnanců" style="margin-bottom:15px;"/>
                        <h4>Seznam zaměstnanců</h4>
                    </div>
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
                <div class="col-lg-2 col-md-2 text-center">
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
                        <img src="{{asset("images/dochazkaImage.png")}}" alt="Docházky ikonka" height="90" width="90" title="Evidování docházky" style="margin-bottom: 15px;"/>
                        <h4>Docházka</h4>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/settings.png")}}" alt="Nastavení ikonka" height="100" width="100" title="Nastavení" style="margin-bottom:15px;"/>
                        <h4>Nastavení</h4>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/hodnoceni.png")}}" alt="Hodnocení ikonka" height="100" width="90" title="Hodnocení zaměstnanců" style="margin-bottom:15px;"/>
                        <h4>Přehled hodnocení zaměstnanců</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/report.png")}}" alt="Nahlášení ikonka" height="100" width="90" title="Nahlášení" style="margin-bottom:15px;"/>
                        <h4>Nahlášení</h4>
                    </div>
                </div>


                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/upload.png")}}" alt="Google Drive Upload ikonka" height="100" width="130" title="Nahrát na Google Drive" style="margin-bottom:15px;"/>
                        <h4>Nahrát na Google Drive</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="https://drive.google.com/drive/u/1/folders/{{ Auth::user()->company_url }}" target="_blank" style="color:black;text-decoration: none;">
                    <div style="padding-top: 50px;padding-bottom:50px;">
                        <img src="{{asset("images/googleDrive.png")}}" alt="Google Drive ikonka" height="100" width="100" title="Google Drive" style="margin-bottom:15px;"/>
                        <h4>Google Drive</h4>
                    </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
