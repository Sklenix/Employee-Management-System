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

    </style>
</head>
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

<!-- Menu-->
<nav class="fill navbar sticky-top navbar-light navbar-expand-sm " style="background-color: #F5F5F5" id="myScrollspy">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="25" width="30" /> | Tozondo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdownMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="dropdownMenu">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{ route('login') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 15px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Přihlásit se</a> </li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top:30px;">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center" style="font-size: 23px;">Registrace</div>

                <div class="card-body">
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
                                    <label class="col-form-label text-md-right"><i class="fa fa-address-book " aria-hidden="true"></i> Společnost(<span style="color:red;">*</span>)</label>
                                    <input id="company" placeholder="Zadejte název Vaší společnosti..." type="text" class="form-control @error('company') is-invalid @enderror" name="company" value="{{ old('company') }}" required autocomplete="company" autofocus>

                                    @error('company')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="form-group">
                                    <label for="first_name" class="col-form-label text-md-right"><i class="fa fa-user " aria-hidden="true"></i> Jméno(<span style="color:red;">*</span>)</label>
                                    <input id="first_name" placeholder="Zadejte Vaše křestní jméno..." type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name">

                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="form-group">
                                    <label for="surname" class="col-form-label text-md-right"><i class="fa fa-user " aria-hidden="true"></i> Příjmení(<span style="color:red;">*</span>)</label>
                                    <input id="surname" placeholder="Zadejte Vaše příjmení..." type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" required autocomplete="surname">

                                    @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-form-label text-md-right"><i class="fa fa-envelope " aria-hidden="true"></i> E-mail(<span style="color:red;">*</span>)</label>
                                    <input id="email" placeholder="Zadejte Vaši e-mailovou adresu..." type="email" class="form-control  @error('email') is-invalid @enderror" name="email" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>


                                <div class="form-group">
                                    <label for="phone" class="col-form-label text-md-right"><i class="fa fa-phone " aria-hidden="true"></i> Telefon</label>
                                    <input id="phone" placeholder="Zadejte Váš telefon..." type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" required autocomplete="phone">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="login" class="col-form-label text-md-right"><i class="fa fa-user " aria-hidden="true"></i> Uživatelské jméno(<span style="color:red;">*</span>)</label>
                                    <input id="login" placeholder="Zadejte Vaše uživatelské jméno k systému..." type="text" class="form-control @error('login') is-invalid @enderror" name="login" required autocomplete="login">

                                    @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-form-label text-md-right"><i class="fa fa-lock" aria-hidden="true"></i> Heslo</label>
                                    <input id="password" placeholder="Zadejte Vaše heslo ..." type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="col-form-label text-md-right"><i class="fa fa-lock" aria-hidden="true"></i> Heslo znovu</label>
                                    <input id="password-confirm" placeholder="Znovu zadejte Vaše heslo ..." type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">
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
