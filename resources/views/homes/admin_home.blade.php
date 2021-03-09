@extends('layouts.admin_dashboard')
@section('content')
    <section class="page-section"  id="vlastnosti" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;">
            <div class="row">
                <div class="col-lg-2 col-md-2 text-center">
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{route('admin_companies.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/companies.png")}}" alt="Seznam firem ikonka" height="128" width="128" title="Seznam firem" style="margin-bottom:15px;"/>
                            <h4>Seznam firem</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('admin_statistics.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/statistiky.png")}}" alt="Statistiky ikonka" height="128" width="128" title="Statistiky" style="margin-bottom:15px;"/>
                            <h4>Statistiky</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek">
                    <a href="{{ route('admin_generator.index')}}" style="cursor: pointer;color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/pdf_generator.png")}}" alt="Generování ikonka" height="128" width="128" title="Generování PDF souborů" style="margin-bottom: 15px;"/>
                            <h4>Generátor souborů</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center ramecek" style="">
                    <a href="https://drive.google.com/drive/u/1/folders/1KsP-NAdwBpFaONID4CxTdY4jeKuWJFX4" target="_blank" style="color:black;text-decoration: none;">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <img src="{{asset("images/google_drive.png")}}" alt="Google Drive ikonka" height="128" width="128" title="Google Drive" style="margin-bottom:15px;"/>
                            <h4>Zobrazit <br>Google Drive</h4>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 text-center">
                </div>

            </div>
        </div>
    </section>
@endsection
