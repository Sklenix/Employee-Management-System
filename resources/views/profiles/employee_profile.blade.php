<!DOCTYPE html>
<html>
<head>
    <style>
        [class*="col-"],[class^="col-"] { padding-left: 0; padding-right: 0; }
        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }
        nav { width: 100%; box-shadow: 0px 6px 0px #dedede;}
        html {
            scroll-behavior: smooth;
        }
        nav ul li a { text-decoration: none; font-weight: 800; text-transform: uppercase; }
        nav.fill ul li a { position: relative; }

        nav.fill ul li a:after { position: absolute; bottom: 0; left: 0; right: 0; margin: auto;
            width: 0%; content: '.'; color: transparent; height: 1px; }

        nav.fill ul li a:hover { z-index: 1; }

        nav.fill ul li a:hover:after { z-index: -10; animation: fill 1s forwards;
            -webkit-animation: fill 1s forwards; -moz-animation: fill 1s forwards; opacity: 1; }

        label{font-size: 17px;}

        .modal-content {
            background-color: #1a202c !important;

        }
        .modal-header h5{
            color:rgba(255, 255, 255, 0.95);
        }

        .btn-modalClose{
            background-color: #4aa0e6 !important;
        }

        .btn-modalClose:hover{
            background-color: #c51f1a !important;
        }

        .btn-modalSuccess{
            background-color: #4aa0e6 !important;
        }

        .btn-modalSuccess:hover{
            background-color: green !important;
        }

        @-webkit-keyframes fill {
            0% { width: 0%; height: 1px; }
            50% { width: 100%; height: 1px; }
            100% { width: 100%; height: 100%; background: #d9534f; }
        }
        .nav-stacked{
            font-size: 16px;
        }
        .nahratTlacitko label:hover{
            transform: scale(1.03);
        }
        .nahratTlacitko label span{
            font-weight: normal;
        }
    </style>
    <title>Tozondo - Profil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>

</head>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="20" style="background-color:cadetblue">
<nav class="fill navbar sticky-top navbar-light navbar-expand-sm " style="background-color: #F5F5F5" id="myScrollspy">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" /> | Profil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdownMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="dropdownMenu">

        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{route('homeEmployee')}}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Dashboard</a> </li>
            <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Odhlásit se</a> </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</nav>
<br>
<div class="container" style="background-color: white;padding:30px;border-radius: 25px;">
    <div class="row">
        <div class="col-12 text-center" style="margin-bottom: 15px;"><h1> {{ Auth::user()->employee_name}} {{Auth::user()->employee_surname}}</h1>
            <hr></div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="text-center">
                @if($profilovka == NULL)
                    <img src="{{ URL::asset('images/default_profile.png') }}" class="profilovka img-thumbnail" alt="profilovka">
                @else
                    <img src =" {{ asset('/storage/employee_images/'.Auth::user()->employee_picture) }}" width="250" style="margin-right: 5px;"  alt="profilovka" />
                @endif
                @if(Session::has('obrazekZpravaFail'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ Session::get('obrazekZpravaFail') }}
                    </div>
                @endif
                @if(Session::has('obrazekZpravaSuccess'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ Session::get('obrazekZpravaSuccess') }}
                    </div>
                @endif
                <form method="post" style="margin-top: 15px;" action="{{route('uploadEmployeeImage')}}" id="zamestnanec_form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group nahratTlacitko">
                        <input type="file" name="obrazek" required id="file" hidden />
                        <label for="file" style="max-width: 250px;padding: 12px 15px;border:3px solid #4aa0e6;font-size:14px;background-color:#4aa0e6;border-radius: 48px;text-transform: uppercase;letter-spacing: 2px;font-weight: bold;color:white;" id="selector">Vyberte soubor</label>

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
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fa fa-upload" aria-hidden="true"></i> Nahrát
                        </button>
                    </div>
                </form>
                <form method="post" action="{{route('deleteEmployeeOldImage')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger btn-block btn-lg">
                            <i class="fa fa-times" aria-hidden="true"></i> Smazat obrázek
                        </button>
                    </div>
                </form>
                    <ul class="list-group">
                        <li class="list-group-item" style="color:black;">Statistiky</li>
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Počet směn</strong></span> {{$pocetSmen}}</li>
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Počet absencí</strong></span> {{$pocetAbsenci}}</li>
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Počet dovolených</strong></span> {{$pocetDovolenych}}</li>
                    </ul>
                    <button type="button" data-id="{{Auth::user()->employee_id}}" data-toggle="modal" style="margin-top:15px;" data-target="#confirmDeleteModal" class="btn btn-danger btn-block" id="getDeleteId" ><i class="fa fa-trash-o" aria-hidden="true"></i> Smazat účet</button>
            </div><br>
        </div>
        <div class="col-sm-9">
            <ul class="nav nav-stacked nav-pills" id="menuTabu">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#zmenaHesla" >Změna hesla</a>
                </li>
            </ul>
            <script>
                $(document).ready(function () {
                    $('#menuTabu a[href="#{{ old('tab') }}"]').tab('show')
                });
            </script>
            <br>
            @if(Session::has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('message') }}
                </div>
            @endif
            @if(Session::has('errorZprava'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('errorZprava') }}
                </div>
            @endif
            <div class="tab-content">
                <div class="tab-pane active" id="obecneUdaje">
                    <form class="form" action="{{ route('updateEmployeeProfileData') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="employee_name"><h4>Jméno</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('employee_name') is-invalid @enderror" name="employee_name" value="{{ Auth::user()->employee_name }}" id="employee_name" placeholder="Vaše jméno ...">
                                    @error('employee_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="employee_surname"><h4>Příjmení</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('employee_surname') is-invalid @enderror" name="employee_surname" value="{{ Auth::user()->employee_surname }}" id="employee_surname" placeholder="Vaše příjmení ...">
                                    @error('employee_surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="employee_phone"><h4>Telefon</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-phone" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('employee_phone') is-invalid @enderror" name="employee_phone" value="{{ Auth::user()->employee_phone }}" id="employee_phone" placeholder="Vaše telefonní číslo...">
                                    @error('employee_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="employee_email"><h4>Email</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                    </div>
                                    <input type="email" class="form-control @error('employee_email') is-invalid @enderror" name="employee_email" id="employee_email" value="{{ Auth::user()->email }}" placeholder="Váš email ...">
                                    @error('employee_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="employee_city"><h4>Město</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('employee_city') is-invalid @enderror" name="employee_city" value="{{ Auth::user()->employee_city }}" id="employee_city" placeholder="Město, kde bydlíte ...">
                                    @error('employee_city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="employee_street"><h4>Ulice</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('employee_street') is-invalid @enderror" name="employee_street" id="employee_street" value="{{ Auth::user()->employee_street }}" placeholder="Ulice Vašeho bydliště ..." >
                                    @error('employee_street')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Uložit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="zmenaHesla">
                    <input type="button" class="btn btn-warning pull-right" value="Generovat heslo" onClick="generator();">
                    <form class="form" action="{{ route('updateEmployeeProfilePassword') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="password"><h4>Heslo</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Zadejte heslo..." title="Zadejte nové heslo.">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <span toggle="#password" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpassword"></span>
                                <script>
                                    $(".showpassword").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="password_verify"><h4>Zopakujte heslo</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('password_verify') is-invalid @enderror" name="password_verify" id="password_verify" placeholder="Zopakujte heslo ..." title="Znovu zadejte Vaše nové heslo.">
                                    @error('password_verify')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <span toggle="#password_verify" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpasswordverify"></span>
                                <script>
                                    function generator() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var password_tmp = "";
                                        for (var x = 0; x < 10; ++x) { password_tmp += znaky.charAt(Math.floor(Math.random()*znaky.length));}
                                        password.value = password_tmp;
                                        password_verify.value = password_tmp;
                                    }

                                    $(".showpasswordverify").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Změnit heslo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Smazani uctu zamestnance -->
<div id="confirmDeleteModal" class="modal fade" style="color:white;" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Potvrzení smazání Vašeho účtu</h5>
                <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete smazat Váš účet?</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <form method="post" action="{{route('deleteEmployeeProfile')}}">
                    @csrf
                    <button type="submit" name="confirm" style="color:white;" id="confirm" class="btn btn-modalSuccess">Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
