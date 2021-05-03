<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- Nazev souboru: verify.blade.php -->
    <!-- Tento soubor reprezentuje webovou stranku slouzici jako brana do informacniho systemu. Teprve po overeni emailove adresy bude mozne pokracovat pres tuto branu na domovskou stranku uzivatele -->
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px;margin-left: 20px;margin-top: -5px;"> <img src="{{ URL::asset('images/logo.png') }}" height="25" width="30" alt="Logo"/> | Tozondo</a>
    <ul class="navbar-nav navbar-collapse justify-content-end">
        <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();document.getElementById('logoutform').submit();" style="font-size:15px;padding:15px;font-weight: bold;text-decoration: none;">ODHLÁSIT SE</a>
                <form id="logoutform" action="{{ route('logout') }}" method="POST"> @csrf </form>
        </li>
    </ul>
</nav>
<!-- Definice obsahu -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top: 30px;">
                <div class="card-header" style="font-size: 18px;background-color: #d9534f;color:white;border-radius: 35px !important;">Ověřte svou emailovou adresu</div>
                <div class="card-body">
                    @if(Session::has('successRegister'))
                        <div class="alert alert-success" style="margin-bottom: 15px;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            {{ Session::get('successRegister') }}
                        </div>
                    @endif
                    @if (session('resent'))
                        <div class="alert alert-success" style="margin-bottom: 10px;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            Nová verifikační emailová zpráva byla zaslána na Vaši emailovou adresu.
                        </div>
                    @endif
                        <center><span style="font-size: 18px;font-weight: bold;">Společnost: {{ Auth::user()->company_name }}</span><br><br>Pro používání informačního systému je nutné ověřit Vaši emailovou adresu, pakliže jste verifikační emailovou zprávu neobdrželi,</center>
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <center><button type="submit" class="btn btn-link">klikněte zde pro vygenerování nového emailu</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
