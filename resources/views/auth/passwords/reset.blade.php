<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- Nazev souboru: reset.blade.php -->
    <!-- Tento soubor reprezentuje webovou stranku pro samotnou zmenu hesla v ramci obnovy hesla -->
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
    <!-- import kaskadovych stylu -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styly.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Tozondo - Reset</title>
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<!-- Pozadi webove stranky pochazi z https://www.toptal.com/designers/subtlepatterns/cloudy-day/, vytvorili Toptal Subtle Patterns -->
<body style="background-image: url('{{ asset('/images/cloudy-day.png')}}');">
<!-- Definice menu -->
<nav class="navbar navbar-light navbar-expand-sm efektMenu" style="background-color: #F5F5F5;box-shadow: 0px 5px 1px #DCDCDC;">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px;margin-left: 20px;margin-top: -5px;"><img src="{{ URL::asset('images/logo.png') }}" height="25" width="30"/> | Tozondo</a>
</nav>
<center>
<!-- Definice obsahu -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card" style="margin-top: 30px;">
                <div class="card-header text-center" style="background-color: #d9534f;color:white;font-size: 24px;font-family: 'Pacifico', cursive;border-radius: 35px !important;">Resetování hesla</div>
                <div class="card-body">
                    <!-- Definice formulare -->
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{$token}}">
                        <div class="form-group">
                            <div class="row">
                                <label for="email" class="col-3"></label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                        </div>
                                        <input id="email" type="email" readonly class="form-control @error('email') is-invalid @enderror" name="email" value="{{$email}}" autocomplete="on">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="password" class="col-3"></label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                        </div>
                                        <input id="password" type="password" placeholder="Zadejte nové heslo ..."  class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="on" autofocus>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="password_confirmation" class="col-3"></label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                        </div>
                                        <input id="password_confirmation" type="password" placeholder="Nové heslo znovu ..." class="form-control" name="password_confirmation" autocomplete="on">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-danger btn-block">
                                        Resetovat heslo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</center>
</body>
</html>
