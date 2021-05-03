@extends('layouts.company_dashboard')
@section('title') - Generátor @endsection
@section('content')
<!-- Nazev souboru: file_generator.blade.php -->
<!-- Autor: Pavel Sklenář (xsklen12) -->
<!-- Tento soubor reprezentuje moznost "Generátor souborů" v ramci uctu s roli firmy -->
<!-- Definice obsahu moznosti generatoru souboru v uctech s roli firmy -->
<div class="row" style="padding-top:40px;padding-bottom: 60px;margin-left: 20px;margin-right: 20px;">
    <br>
    <div class="col-3" style="font-size: 15px;">
    </div>
    <div class="col-md-7 col-sm-12 alert alert-info alert-block text-center" style="font-size: 15px;">
        <strong>Pro generování souboru stiskněte libovolné tlačítko z níže uvedených.</strong>
    </div>
    <div class="col-md-2" style="font-size: 15px;">
    </div>
    <div class="col-md-3" style="font-size: 15px;">
    </div>
    <div class="col-md-7" style="font-size: 15px;">
        <!-- Zachyceni ruznych stavu pomoci Session -->
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
    <div class="col-md-2" style="font-size: 15px;">
    </div>
    <div class="col-md-3" style="font-size: 15px;">
    </div>
    <!-- Definice tlacitek -->
    <a class="btn btn-dark" href="{{route('generator.employeesList')}}" style="margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">Seznam zaměstnanců</h5>
        </div>
    </a>
    <a class="btn btn-primary" href="{{route('generator.shiftsList')}}" style="margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;Seznam směn&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        </div>
    </a>
    <a class="btn btn-primary" href="{{route('generator.companyProfile')}}" style="margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Údaje Firmy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        </div>
    </a>
    <a class="btn btn-dark" href="{{route('generator.generateEmployeesRatings')}}" style="margin-right: 25px;margin-bottom:5px;">
        <div style="padding-top: 50px;padding-bottom:50px;">
            <h5 style="color:white;">Souhrn hodnocení</h5>
        </div>
    </a>
    <div class="col-md-5">
    </div>
    <div class="col-md-12 text-center" style="margin-top: 12px;">
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 text-center">
    </div>
    <div class="col-lg-2 col-md-2 text-center" style="margin-top:5px;">
        <form id="aktualniSmeny" method="GET" action="{{route('generator.generateEmployeeCurrentShifts')}}">
            <div class="form-group">
                <select name="vybrany_zamestnanec" required id="vybrany_zamestnanec" style="color:black" class="form-control vybrany_zamestnanec">
                    <option value="-1">Vyberte zaměstnance</option>
                    @foreach ($zamestnanci as $zamestnanec)
                        <option id="{{$zamestnanec->employee_id}}" value="{{$zamestnanec->employee_id}}">{{$zamestnanec->employee_name}} {{$zamestnanec->employee_surname}}</option>
                    @endforeach
                </select>
            </div>
            <a class="btn btn-dark" onclick="document.getElementById('aktualniSmeny').submit();" style="color:black;">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <h5 style="color:white;">Aktuální směny zaměst.</h5>
                </div>
            </a>
        </form>
    </div>
    <div class="col-lg-2 col-md-2 text-center" style="margin-top:5px;">
        <form id="smenyZamestnance" method="GET" action="{{route('generator.generateEmployeeShifts')}}">
            <div class="form-group">
                <select name="vybrany_zamestnanec" required id="vybrany_zamestnanec" style="color:black" class="form-control vybrany_zamestnanec">
                    <option value="-1">Vyberte zaměstnance</option>
                    @foreach ($zamestnanci as $zamestnanec)
                        <option id="{{$zamestnanec->employee_id}}" value="{{$zamestnanec->employee_id}}">{{$zamestnanec->employee_name}} {{$zamestnanec->employee_surname}}</option>
                    @endforeach
                </select>
            </div>
            <a class="btn btn-dark" onclick="document.getElementById('smenyZamestnance').submit();" style="color:black;">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <h5 style="color:white;">Zaměstnancovi směny</h5>
                </div>
            </a>
        </form>
    </div>
    <div class="col-lg-2 col-md-2 text-center" style="margin-top:5px;">
        <form id="zamestnanciSmena" method="GET" action="{{route('generator.generateShiftEmployees')}}">
            <div class="form-group">
                <select name="vybrana_smena" required id="vybrana_smena" style="color:black" class="form-control vybrana_smena">
                    <option value="-1">Vyberte směnu</option>
                    @foreach ($smeny as $smena)
                        <option id="{{$smena->shift_id}}" value="{{$smena->shift_id}}">{{$smena->shift_start}} - {{$smena->shift_end}}</option>
                    @endforeach
                </select>
            </div>
            <a class="btn btn-dark" onclick="document.getElementById('zamestnanciSmena').submit();" style="color:black;">
                <div style="padding-top: 50px;padding-bottom:50px;">
                    <h5 style="color:white;">Zaměstnanci ve směně</h5>
                </div>
            </a>
        </form>
    </div>
</div>
@endsection
