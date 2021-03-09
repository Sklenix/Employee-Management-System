@extends('layouts.employee_dashboard')
@section('title') - Generátor @endsection
@section('content')
    <section class="page-section"  id="generator" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;padding-bottom: 550px;">
            <div class="row">
                <br>
                <div class=" col-lg-3" style="font-size: 15px;">
                </div>
                <div class=" col-lg-6 alert alert-info alert-block text-center" style="font-size: 15px;">
                    <strong>Pro generování souboru stiskněte libovolné tlačítko z níže uvedených.</strong>
                    @if($message = Session::get('success'))
                        <div class="alert alert-success alert-block" style="margin-top: 15px;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif
                    @if($message = Session::get('fail'))
                        <div class="alert alert-danger alert-block" style="margin-top: 15px;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif
                </div>

                <div class=" col-lg-2" style="font-size: 15px;">
                </div>

                <div class=" col-lg-2" style="font-size: 15px;">
                </div>

                <a class="btn btn-dark" href="{{route('employee_generator.vacationsList')}}" style="cursor: pointer;color:black;text-decoration: none;margin-right: 25px;margin-bottom:5px;">
                    <div class="col-lg-1 col-md-1 text-center">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <h5 style="color:white;">Seznam dovolených</h5>
                        </div>
                    </div>
                </a>

                <a class="btn btn-primary" href="{{route('employee_generator.diseasesList')}}" style="cursor: pointer;color:black;text-decoration: none;margin-right: 25px;margin-bottom:5px;">
                    <div class="col-lg-1 col-md-1 text-center">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <h5 style="color:white;">Seznam nemocenských</h5>
                        </div>
                    </div>
                </a>

                <a class="btn btn-primary" href="{{route('employee_generator.reportsList')}}" style="cursor: pointer;color:black;text-decoration: none;margin-right: 25px;margin-bottom:5px;">
                    <div class="col-lg-1 col-md-1 text-center">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;Seznam nahlášení&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                        </div>
                    </div>
                </a>

                <a class="btn btn-dark" href="{{route('employee_generator.employeeprofile')}}" style="cursor: pointer;color:black;text-decoration: none;margin-right: 25px;margin-bottom:5px;">
                    <div class="col-lg-1 col-md-1 text-center">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <h5 style="color:white;">&nbsp;Údaje zaměstnance&nbsp;</h5>
                        </div>
                    </div>
                </a>

                <div class="col-lg-5 col-md-5 col-sm-5 text-center">
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <hr style="border-top: 2px solid #333333;">
                </div>
                <div class=" col-lg-4" style="font-size: 15px;">
                </div>
                <a class="btn btn-dark" href="{{route('employee_generator.currentshiftsList')}}" style="cursor: pointer;color:black;text-decoration: none;margin-right: 25px;margin-bottom:5px;">
                    <div class="col-lg-1 col-md-1 text-center">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;Aktuální směny&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                        </div>
                    </div>
                </a>
                <a class="btn btn-dark" href="{{route('employee_generator.shifthistoryList')}}" style="cursor: pointer;color:black;text-decoration: none;margin-right: 25px;margin-bottom:5px;">
                    <div class="col-lg-1 col-md-1 text-center">
                        <div style="padding-top: 50px;padding-bottom:50px;">
                            <h5 style="color:white;">&nbsp;&nbsp;&nbsp;&nbsp;Historie směn&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection
