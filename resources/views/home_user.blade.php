@extends('layouts.dashboard_zamestnanec')

@section('content')
    <!-- Vlastnosti systému-->
    <section class="page-section"  id="vlastnosti" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;">
            <div class="row">

                <div class="col-lg-2 col-md-2 text-center">
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <div class="mt-5">
                        <img src="{{asset("images/smenyAdd.png")}}" alt="Směny ikonka" height="100" width="100" title="Směny" style="margin-bottom:15px;"/>
                        <h4>Zobrazit směny</h4>
                    </div>
                </div>


                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/smenyList.png")}}" alt="Seznam Směn ikonka" height="100" width="100" title="Seznam směn" style="margin-bottom:15px;"/>
                        <h4>Zobrazit aktuální směny</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/statistiky.png")}}" alt="Statistiky ikonka" height="100" width="100" title="Statistiky" style="margin-bottom:15px;"/>
                        <h4>Statistiky</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/pdfImage.png")}}" alt="Generování ikonka" height="100" width="90" title="Generování PDF, Excel" style="margin-bottom: 15px;"/>
                        <h4>Generátor souborů</h4>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 text-center">
                </div>
                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/dochazkaImage.png")}}" alt="Nepřítomnost ikonka" height="100" width="90" title="Evidování dovolených" style="margin-bottom: 15px;"/>
                        <h4>Důvod nepřítomnosti</h4>
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
                        <img src="{{asset("images/hodnoceni.png")}}" alt="Dovolená ikonka" height="100" width="90" title="Dovolená zaměstnanců" style="margin-bottom:15px;"/>
                        <h4>Přidat dovolenou</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/report.png")}}" alt="Nahlášení ikonka" height="100" width="90" title="Nahlášení" style="margin-bottom:15px;"/>
                        <h4>Nahlásit</h4>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 text-center">
                </div>



                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <div class="mt-5 mb-5">
                        <img src="{{asset("images/googleDrive.png")}}" alt="Google Drive ikonka" height="100" width="100" title="Google Drive" style="margin-bottom:15px;"/>
                        <h4>Google Drive</h4>
                    </div>
                </div>



            </div>
        </div>
    </section>

@endsection
