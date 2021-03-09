@extends('layouts.admin_dashboard')
@section('title') - Statistiky @endsection
@section('content')
    <section class="page-section"  id="generator" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;padding-bottom: 950px;">
            <div class="row">
                <br>
                <div class=" col-lg-1" style="font-size: 15px;">
                </div>
                <div class=" col-lg-10 alert alert-info alert-block text-center" style="font-size: 15px;">
                    <strong>Statistiky zatím nejsou hotové.</strong>
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
            </div>
        </div>
    </section>
@endsection
