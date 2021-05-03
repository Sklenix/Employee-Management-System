<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- Nazev souboru: login.blade.php -->
    <!-- Tento soubor reprezentuje webovou stranku pro prihlasovani do informacniho systemu -->
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
    <title>Tozondo - Přihlašování</title>
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<!-- Pozadi webove stranky pochazi z https://www.toptal.com/designers/subtlepatterns/cloudy-day/, vytvorili Toptal Subtle Patterns -->
<body style="background-image: url('{{ asset('/images/cloudy-day.png')}}');">
<!-- Definice menu -->
<nav class="navbar navbar-light navbar-expand-sm efektMenu" style="background-color: #F5F5F5;box-shadow: 0px 5px 1px #DCDCDC;">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px;margin-left: 20px;margin-top: -5px;"> <img src="{{ URL::asset('images/logo.png') }}" height="25" alt="Logo" width="30"/> | Tozondo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidka">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="rozbalovaciNabidka">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{ route('register') }}" class="nav-link" style="font-family: 'Roboto', sans-serif;font-weight: bold;font-size: 15px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >REGISTROVAT SE</a></li>
        </ul>
    </div>
</nav>
<!-- Definice obsahu -->
<div class="container" >
    <div class="row justify-content-center" >
        <div class="col-md-10">
            <div class="card" style="margin-top: 30px;">
                <div class="card-header text-center" style="background-color: #0275d8;color:white;font-size: 28px;font-family: 'Pacifico', cursive;border-radius: 35px !important;">Přihlašování
                    @isset($role)  <!-- prihlasovaci formular se objevi na zaklade URL prihlasovacich formularu pro jednotlive role -->
                        @if($role == "employee") pro zaměstnance
                        @elseif($role == "admin") pro adminy
                        @elseif($role == "company") pro firmy
                        @endif
                    @endisset
                </div>
                <div class="card-body" style="background-color:#F8F8FF;">
                    <!-- Pripadne chybove hlaseni ci oznameni o uspechu akce pro uzivatele -->
                    @if(Session::has('fail'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            {{ Session::get('fail') }}
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <!-- Vytvoreni cesty k odeslani formulare na zaklade role uzivatele -->
                    @isset($role)
                        <form method="POST" action='{{ url("login/$role") }}'>
                        @csrf
                    @endisset
                        <!-- Definice obsahu formulare -->
                        <center>
                        <div class="form-group">
                            <label for="email" class="col-md-4"></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                    </div>
                                    <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" placeholder="Emailová adresa | Login" value="{{ old('email') }}" autocomplete="email" autofocus>
                                    @error('email') <!-- v pripade chyby -->
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="heslo" class="col-md-4"></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" style="padding-right: 5px;"></i></div>
                                    </div>
                                    <input id="heslo" type="password" class="form-control form-control-lg @error('heslo') is-invalid @enderror" placeholder="Heslo" name="heslo" autocomplete="on">
                                    @error('heslo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                            </div>
                                <span toggle="#password" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:27px;color:black;" class="fa fa-eye zobrazHeslo"></span>
                        </div>
                        </div>
                        </center>
                        <script>
                            /* Skryti/odkryti hesla */
                            $(".zobrazHeslo").click(function() {
                                $(this).toggleClass("fa-eye fa-eye-slash");
                                var input = $($(this).attr("toggle"));
                                if (input.attr("type") == "password") {
                                    input.attr("type", "text");
                                } else {
                                    input.attr("type", "password");
                                }
                            });
                            /* Tento jquery kod spolecne s elementem span (ktery byl upraven) byl prevzat dle licencnich podminek, viz nize.
                                           Copyright (c) 2021 - Sohail Aj. - www.codepen.io/Sohail05/pen/yOpeBm

                                           Permission is hereby granted, free of charge, to any person
                                           obtaining a copy of this software and associated documentation
                                           files (the "Software"), to deal in the Software without restriction,
                                            including without limitation the rights to use, copy, modify,
                                           merge, publish, distribute, sublicense, and/or sell copies of
                                           the Software, and to permit persons to whom the Software is
                                           furnished to do so, subject to the following conditions:

                                           The above copyright notice and this permission notice shall
                                           be included in all copies or substantial portions of the Software.

                                           THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
                                           EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
                                           OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
                                           NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
                                           HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
                                           WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
                                           OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
                                           DEALINGS IN THE SOFTWARE.
                                           */
                        </script>
                        <div class="form-group">
                            <div class="col-md-8 offset-md-2 col-sm-6">
                                <span class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember" style="font-size: 14px;">Zapamatovat</label>
                                     @isset($role)
                                        @if($role == "company") <!-- pokud je prihlasovaci formular urcen pro firmy, tak se take objevi moznost "Zapomněl jste heslo?" -->
                                            <a style="float:right;" href="{{ route('password.request') }}">Zapomněl jste heslo?</a>
                                        @endif
                                    @endisset
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Přihlásit se</button><br>
                                <center>
                                @isset($role) <!-- na zaklade URL prihlasovaciho formulare se urci moznost prepnuti na jiny prihlasovaci formular -->
                                    @if($role == "employee")
                                            <a style="font-size: 16px;" href="{{ route('renderCompanyLogin') }}">Jste firma?</a>
                                    @elseif($role == "company")
                                        <a style="font-size: 16px;" href="{{ route('renderEmployeeLogin') }}">Jste zaměstnanec?</a>
                                    @elseif($role == "admin")
                                        <a style="font-size: 16px;margin-right: 30px;" href="{{ route('renderCompanyLogin') }}">Firma</a>
                                        <a style="font-size: 16px;" href="{{ route('renderEmployeeLogin') }}">Zaměstnanec</a>
                                    @endif
                                @endisset
                                </center>
                                 </div>
                             </div>
                            </form>
                     </div>
                 </div>
             </div>
        </div>
    </div>
</body>
</html>
