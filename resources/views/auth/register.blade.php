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

    <title>Tozondo - Registrace</title>

    <style>

        [class*="col-"],[class^="col-"] { padding-left: 0; padding-right: 0; }
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
        .card, .card-header, .card-body, .card-footer{
            border-radius:35px !important;
        }
    </style>
</head>
<!-- Textura pouzita ze stranky https://www.toptal.com/designers/subtlepatterns/cloudy-day/, vytvorili Toptal Subtle Patterns -->
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20" style="background-image: url('{{ asset('/images/cloudy-day.png')}}');">
<nav class="fill navbar sticky-top navbar-light navbar-expand-sm " style="background-color: #F5F5F5" id="myScrollspy">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="25" width="30" /> | Tozondo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdownMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="dropdownMenu">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{ route('company') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 15px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Přihlásit se</a> </li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top:30px;">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" style="margin-bottom: 40px">
                <div class="card-header text-center" style="font-size: 30px;background-color: #0275d8;color:white;font-family: 'Pacifico', cursive;">Registrace</div>

                <div class="card-body" style="background-color:#F8F8FF">
                    <div class="row justify-content-center">

                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-8">
                            <div class="alert alert-warning" role="alert" style="font-size: 16px;">
                                Položky označené (<span style="color:red;">*</span>) jsou povinné.
                            </div>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="form-group">
                                    <label class="col-form-label text-md-right"> Společnost (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-address-book " aria-hidden="true"></i></div>
                                        </div>
                                    <input id="company" placeholder="Zadejte název Vaší společnosti..." type="text" class="form-control @error('company') is-invalid @enderror" name="company" value="{{ old('company') }}"  autocomplete="company" autofocus>
                                    @error('company')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                                </div>

                                <div class="form-group">
                                    <label for="company_city" class="col-form-label text-md-right"> Město (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                        </div>
                                        <input id="company_city" placeholder="Zadejte město, kde se Vaše firma nachází..." type="text" class="form-control @error('company_city') is-invalid @enderror" name="company_city" value="{{ old('company_city') }}"  autocomplete="company_city">

                                        @error('company_city')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="company_street" class="col-form-label text-md-right"> Ulice </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                        </div>
                                        <input id="company_street" placeholder="Zadejte ulici, kde se Vaše firma nachází (včetně čísla popisného)..." type="text" class="form-control @error('company_street') is-invalid @enderror" name="company_street" value="{{ old('company_street') }}"  autocomplete="company_street">

                                        @error('company_street')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="company_ico" class="col-form-label text-md-right"> IČO</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                                        </div>
                                        <input id="company_ico" placeholder="Zadejte IČO Vaší firmy..." type="text" class="form-control @error('company_ico') is-invalid @enderror" name="company_ico" value="{{ old('company_ico') }}"  autocomplete="company_ico">

                                        @error('company_ico')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="first_name" class="col-form-label text-md-right"> Jméno zástupce (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                        </div>
                                    <input id="first_name" placeholder="Zadejte Vaše křestní jméno..." type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}"  autocomplete="first_name">

                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="surname" class="col-form-label text-md-right">Příjmení zástupce (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                        </div>
                                    <input id="surname" placeholder="Zadejte Vaše příjmení..." type="text" class="form-control @error('surname') is-invalid @enderror" name="surname"  value="{{ old('surname') }}" autocomplete="surname">

                                    @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-form-label text-md-right"> E-mail (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                        </div>
                                    <input id="company_email" placeholder="Zadejte Vaši e-mailovou adresu..." type="email" class="form-control  @error('company_email') is-invalid @enderror" name="company_email" value="{{ old('company_email') }}"  autocomplete="email">

                                    @error('company_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-form-label text-md-right">Telefon (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                        </div>
                                    <input id="phone" placeholder="Zadejte Váš telefon..." type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="login" class="col-form-label text-md-right">Uživatelské jméno (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                        </div>
                                    <input id="company_login" placeholder="Zadejte Vaše uživatelské jméno k systému..." type="text" value="{{ old('company_login') }}" class="form-control @error('company_login') is-invalid @enderror" name="company_login"  autocomplete="company_login">

                                    @error('company_login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    </div>
                                </div>
                                <input type="button" class="btn btn-warning btn-sm pull-right" value="Generovat heslo" onClick="generator_registration();">
                                <div class="form-group">
                                    <label for="password" class="col-form-label text-md-right">Heslo (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                        </div>
                                    <input id="password" placeholder="Zadejte Vaše heslo ..." type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="password">

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

                                <div class="form-group">
                                    <label for="password_confirmation" class="col-form-label text-md-right">Heslo znovu (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                        </div>
                                    <input id="password_confirmation" placeholder="Znovu zadejte Vaše heslo ..." type="password" class="form-control" name="password_confirmation"  autocomplete="password_confirmation">
                                </div>
                                <span toggle="#password_confirmation" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpasswordverify"></span>
                                <script>
                                    function generator_registration() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var password_tmp = "";
                                        for (var x = 0; x < 10; ++x) { password_tmp += znaky.charAt(Math.floor(Math.random()*znaky.length));}
                                        password.value = password_tmp;
                                        password_confirm.value = password_tmp;
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

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                                        Registrovat
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
