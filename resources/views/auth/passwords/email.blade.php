<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="description" content="Tozondo - Open Source systém pro rychlou a efektivní správu Vašich zaměstnanců.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="system, zamestnanci, sprava zamestnancu">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="googlebot" content="index, follow"/>
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Pavel">
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="{{ URL::asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ URL::asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ URL::asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>Tozondo - Reset</title>

    <style>
        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }
        nav { width: 100%; box-shadow: 0px 6px 0px #dedede;}

        nav ul li a { text-decoration: none; font-weight: 800; text-transform: uppercase; }
        nav.fill ul li a { position: relative; }

        nav.fill ul li a:after { position: absolute; bottom: 0; left: 0; right: 0; margin: auto;
            width: 0%; content: '.'; color: transparent; height: 1px; }

        nav.fill ul li a:hover { z-index: 1; }

        nav.fill ul li a:hover:after { z-index: -10; animation: fill 1s forwards;
            -webkit-animation: fill 1s forwards; -moz-animation: fill 1s forwards; opacity: 1; }

        label{font-size: 17px;}

        .field-icon {z-index: 2;position: relative;margin-right: 8px;margin-top: -28px;float: right;}
        .card{
            margin-top: 30px;
        }

        @-webkit-keyframes fill {
            0% { width: 0%; height: 1px; }
            50% { width: 100%; height: 1px; }
            100% { width: 100%; height: 100%; background: #6495ED; }
        }
        @media screen and (max-width: 730px) {
            .pozadi {background-image: url({{ asset('images/pozadiMobily.png') }});height: 110vh;width: auto;
                background-size: cover;background-position: center center;}
        }
        @media screen and (min-width: 730px) {
            .pozadi {background-image: url({{ asset('images/pozadi.png') }});height: 95vh;width: auto;
                background-size: cover;background-position: center center;}
        }

    </style>
</head>
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20" style="background-image: url('{{ asset('/images/cloudy-day.png')}}');">

<!-- Menu-->
<nav class="fill navbar sticky-top navbar-light navbar-expand-sm " style="background-color: #F5F5F5" id="myScrollspy">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="25" width="30" /> | Tozondo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdownMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="dropdownMenu">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{ route('company') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 15px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Přihlásit se</a> </li>
            <li class="nav-item"><a href="{{ route('register') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 15px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Registrovat se</a> </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center" style="font-size: 18px;background-color: #d9534f;color:white;">Resetování hesla</div>
                <div class="card-body">
                    <center>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            Ná váš email byl odeslán resetovací odkaz!
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group" style="margin-top: -20px;">
                            <label for="company_email" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                    </div>
                                <input id="company_email" type="email" placeholder="Zadejte Váš email ..." class="form-control form-control-lg @error('company_email') is-invalid @enderror" name="company_email" value="{{ old('company_email') }}"  autocomplete="email" autofocus>

                                @error('company_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Zadaná emailová adresa v našem systému není registrována.</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-danger">
                                    Resetovat heslo
                                </button>
                            </div>
                        </div>
                    </form>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
