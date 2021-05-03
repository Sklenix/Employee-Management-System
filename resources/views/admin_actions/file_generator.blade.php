@extends('layouts.admin_dashboard')
@section('title') - Generátor @endsection
@section('content')
<!-- Nazev souboru: file_generator.blade.php -->
<!-- Autor: Pavel Sklenář (xsklen12) -->
<!-- Tento soubor reprezentuje moznost "Statistiky" v ramci uctu s roli admina -->
<!-- Definice obsahu moznosti generatoru souboru v uctech s roli admina -->
<div class="container-fluid" style="margin-left: 20px;margin-right: 20px;">
    <div class="row" style="padding-top:40px;padding-bottom: 60px;">
            <br>
            <div class="col-3">
            </div>
            <div class="col-6 alert alert-info alert-block text-center" style="font-size: 15px;">
                <strong>Pro generování souboru stiskněte tlačítko.</strong>
                <!-- Usek kodu pro zachyt hlasek -->
                @if($zprava = Session::get('success'))
                    <div class="alert alert-success alert-block" style="margin-top: 15px;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{$zprava}}</strong>
                    </div>
                @endif
                @if($zprava = Session::get('fail'))
                    <div class="alert alert-danger alert-block" style="margin-top: 15px;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{$zprava}}</strong>
                    </div>
                @endif
            </div>
            <div class="col-12">
            </div>
            <div class="col-5">
            </div>
            <a class="btn btn-dark" href="{{route('admin_generator.companiesList')}}">
                <div style="padding-top: 50px;padding-bottom:50px;padding-left: 18px;padding-right: 18px;">
                    <h5 style="color:white;">Seznam firem</h5>
                </div>
            </a>
    </div>
</div>
@endsection
