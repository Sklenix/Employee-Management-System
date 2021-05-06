@extends('layouts.employee_dashboard')
@section('title') - Generátor @endsection
@section('content')
<!-- Nazev souboru: file_generator.blade.php -->
<!-- Autor: Pavel Sklenář (xsklen12) -->
<!-- Tento soubor reprezentuje moznost "Generátor souborů" v ramci uctu s roli zamestnance -->
<!-- Definice obsahu moznosti generatoru souboru v uctech s roli zamestnance -->
<div class="row" style="margin-left: 20px;margin-right: 20px;">
    <div class="offset-3 col-6 alert alert-info alert-block text-center" style="font-size: 15px;margin-top:30px;">
        <strong>Pro generování souboru stiskněte libovolné tlačítko z níže uvedených.</strong>
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
    <div class="col-12" style="font-size: 15px;">
    </div>
    <div class="col-3" style="font-size: 15px;">
    </div>
    <!-- Definice tlacitek pro generovani souboru -->
    <a class="btn btn-dark" href="{{route('employee_generator.vacationsList')}}" style="color:black;margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">Seznam dovolených</h5>
        </div>
    </a>
    <a class="btn btn-primary" href="{{route('employee_generator.diseasesList')}}" style="color:black;margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">Seznam nemocenských</h5>
        </div>
    </a>
    <a class="btn btn-primary" href="{{route('employee_generator.reportsList')}}" style="color:black;margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;Seznam nahlášení&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        </div>
    </a>
    <a class="btn btn-dark" href="{{route('employee_generator.employeeprofile')}}" style="color:black;margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Moje údaje&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        </div>
    </a>
    <div class="col-12" style="margin-top: 10px;">
    </div>
    <div class="col-3" style="font-size: 15px;">
    </div>
    <a class="btn btn-dark" href="{{route('employee_generator.currentshiftsList')}}" style="color:black;margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;Aktuální směny&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        </div>
    </a>
    <a class="btn btn-dark" href="{{route('employee_generator.shifthistoryList')}}" style="color:black;margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Všechny směny&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        </div>
    </a>
    <a class="btn btn-dark" href="{{route('employee_generator.injuriesList')}}" style="color:black;margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seznam zranění&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        </div>
    </a>
</div>

@endsection
