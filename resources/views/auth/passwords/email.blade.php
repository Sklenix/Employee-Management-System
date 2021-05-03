<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- Nazev souboru: email.blade.php -->
    <!-- Tento soubor reprezentuje webovou stranku pro odeslani emailove zpravy na zadanou emailovou adresu za ucelem obnovy hesla -->
    <!-- Tento soubor byl vygenerovan autentizacnim a autorizacnim balickem pro Laravel a byl nasledne upraven pro potreby webove aplikace -->
    <!-- definice metadat -->
    <meta name="description" content="Tozondo - Systém pro rychlou a efektivní správu Vašich zaměstnanců.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="system, zamestnanci, sprava zamestnancu">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="googlebot" content="index, follow"/>
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Pavel">
    <meta charset="utf-8">
    <!-- odkazy na favicony -->
    <link rel="icon" href="{{ URL::asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ URL::asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ URL::asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>
    <!-- import fontu -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <!-- import kaskadovych stylu, jQuery a javascriptu -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styly.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
    <title>Tozondo - Reset</title>
</head>
<!-- Pozadi webove stranky pochazi z https://www.toptal.com/designers/subtlepatterns/cloudy-day/, vytvorili Toptal Subtle Patterns -->
<body style="background-image: url('{{ asset('/images/cloudy-day.png')}}');">
<!-- Definice menu -->
<nav class="navbar navbar-light navbar-expand-sm efektMenu" style="background-color: #F5F5F5;box-shadow: 0px 5px 1px #DCDCDC;">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px;margin-left: 20px;margin-top: -5px;"> <img src="{{ URL::asset('images/logo.png') }}" height="25" width="30"/> | Tozondo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidka">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="rozbalovaciNabidka">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{ route('renderCompanyLogin') }}" class="nav-link" style="font-family: 'Roboto', sans-serif;font-weight:bold;font-size: 15px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >PŘIHLÁSIT SE</a> </li>
            <li class="nav-item"><a href="{{ route('register') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 15px;font-weight:bold;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >REGISTROVAT SE</a> </li>
        </ul>
    </div>
</nav>
<!-- Definice obsahu -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top: 30px;">
                <div class="card-header text-center" style="background-color: #d9534f;color:white;font-size: 23px;font-family: 'Pacifico', cursive;border-radius: 35px !important;">Resetování hesla</div>
                <div class="card-body" style="padding-bottom: 30px;">
                    <center>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            Ná váš email byl odeslán resetovací odkaz!
                        </div>
                    @endif
                    <!-- Definice formulare -->
                    <form method="POST" style="padding-top: 7px;" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group" style="margin-top: -20px;">
                            <label for="email" class="col-md-4"></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                    </div>
                                    <input id="email" type="email" placeholder="Zadejte Vaši emailovou adresu ..." class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="on" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
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
