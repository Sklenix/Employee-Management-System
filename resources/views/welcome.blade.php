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

    <title>Tozondo</title>

    <style>

        [class*="col-"],[class^="col-"] { padding-left: 0; padding-right: 0; }
        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }
        nav { width: 100%; box-shadow: 0px 6px 0px #dedede;}
        html {
            scroll-behavior: smooth
        }
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
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" /> | Tozondo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdownMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="dropdownMenu">
        <ul class="navbar-nav navbar-collapse">
            <li class="nav-item"><a href="#funkce" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Jak to funguje</a> </li>
            <li class="nav-item"><a href="#vlastnosti" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Vlastnosti systému</a> </li>
            <li class="nav-item"><a href="#formular" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Napište nám</a> </li>
            <li class="nav-item"><a href="#kontakty" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Kontakty</a> </li>
        </ul>
        <ul class="navbar-nav navbar-collapse justify-content-end">
            @if (Route::has('login'))
                @auth
                    <li class="nav-item"><a href="{{ url('/company/profile') }}" class="nav-link p-3" style="font-family: 'Amatic SC', cursive;" >Vstup do systému</a> </li>
                @else
                    <li class="nav-item"><a href="{{ route('company') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Přihlásit se</a> </li>
                    @if (Route::has('register'))
                        <li class="nav-item"> <a href="{{ route('register') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;margin-right: 20px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Registrace</a> </li>
                    @endif
                @endif
            @endif
        </ul>
    </div>
</nav>

<!-- Pozadí (obrázek)-->
<div class="container col-12 pozadi" style="margin-top:6px;"></div>

<!-- Jak to funguje-->
<section class="page-section"  id="funkce" style="padding-top:40px;padding-bottom: 60px;background-color: #17a2b8;" >
    <div class="container">
        <h2 class="text-center text-white">Jak to funguje</h2>
        <hr style="background-color: white;padding-top:2px;">
        <div class="row">
            <div class="col-lg-1 col-md-1 text-center">
            </div>
            <div class="col-lg-10 col-md-10 text-center">

                <ol class="text-white-50 mb-0 text-justify" style="font-size: 16px;">
                    <li>Zaregistrujete se jako firma pomocí tlačítka registrace, po založení účtu budete přesměrováni na domovskou stránku Vašeho profilu.</li>
                    <li>Po registraci obdržíte pozvánku do Vašeho prostoru v Google Drive na Vámi uvedený email.</li>
                    <li>Na domovské stránce máte menu, v kterém jsou vypsané možnosti, co vše lze se systémem dělat.</li>
                    <li>V horním pravém rohu máte možnost upravit Váš profil, nebo se odhlásit.</li>
                    <li>Po vytvoření zaměstnance se ve Vaší Google Drive složce vytvoří nová složka se jménem zaměstnance.</li>
                    <li>Formát složky zaměstnance je následující [Příjmení][Jméno][ID_zaměstnance].</li>
                    <li>Do systému se lze přihlásit Vaším e-mailem, nebo přihlašovacím  (loginem), který jste zadal v registraci účtu.</li>
                    <li>Po vytvoření směn je můžete přiřazovat jednotlivým zaměstnancům.</li>
                </ol>



                <p class="text-white-50 mb-0 text-justify" style="font-size: 16px;">


                </p>

            </div>

        </div>
    </div>
</section>


<!-- Vlastnosti systému-->
<section class="page-section"  id="vlastnosti" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
    <div class="container">
        <h2 class="text-center">Funkce systému</h2>
        <hr style="background-color: #FF7F50;padding-top:2px;">
        <div class="row">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/googleDrive.png")}}" alt="Google Drive ikonka" height="100" width="100" title="Google Drive"/>
                    <h3 class="h4 mb-2">Google Drive</h3>
                    <p class="text-muted mb-0 text-justify">Můžete spravovat soubory zaměstnance na Google Drive, kde se po registraci zaměstnance automaticky vytvoří složka, do které můžete ukládat!</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/smenyImage.png")}}" alt="Směny ikonka" height="100" width="100" title="Směny"/>
                    <h3 class="h4 mb-2">Správa směn</h3>
                    <p class="text-muted mb-0">Můžete vytvářet směny a poté je přidávat zaměstnancům.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/zamestnanecImage.png")}}" alt="Zaměstnanci ikonka" height="100" width="100" title="Zaměstnanci"/>
                    <h3 class="h4 mb-2">Správa zaměstnanců</h3>
                    <p class="text-muted mb-0">Můžete vytvářet zaměstnance, které potom v systému můžete jednotlivě spravovat.</p>
                </div>
            </div>
           <!-- <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/penizeImage.png")}}" alt="Výplata ikonka" height="90" width="90" title="Výplata" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Vyplácení zaměstnanců</h3>
                    <p class="text-muted mb-0">Můžete vyplácet konkrétní zaměstnance v jejich profilu.</p>
                </div>
            </div> !-->

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/pdfImage.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Generování v Excelu, PDF</h3>
                    <p class="text-muted mb-0">Můžete generovat například přehled zaměstnanců, směny, ... .</p>
                </div>
            </div>

            <div class="col-lg-12 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/dochazkaImage.png")}}" alt="Docházky ikonka" height="90" width="90" title="Evidování docházky" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Docházka</h3>
                    <p class="text-muted mb-0">Můžete evidovat zaměstnancovi příchody, nepříchody.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
            </div>
        </div>
    </div>
</section>


<!-- Formulář-->
<section class="formular" id="formular">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="background-color: #17a2b8;padding-top:60px;padding-bottom: 50px;">
        <center>
            <div class="col-md-6 col-sm-12 col-xs-12" style="background-color: #F5F5F5;border-radius: 10px;padding:40px;">
                <h3>Kontaktujte nás.</h3>
                <hr>
                @if(count($errors) > 0 )
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <ul class="text-left">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{$message}}</strong>
                    </div>
                @endif
                <form method="post" action="{{url('welcome/send')}}#formular">
                    @csrf
                    <div class="form-group">
                        <div class="alert alert-warning" role="alert" style="font-size: 16px;">
                            Položky označené (<span style="color:red;">*</span>) jsou povinné.
                        </div>
                        <label><i class="fa fa-user " aria-hidden="true"></i> Vaše celé jméno (<span style="color:red;">*</span>)</label>
                        <input type="text" name="name" class="form-control" placeholder="Vložte své celé jméno ...">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-envelope" aria-hidden="true"></i> Váš e-mail (<span style="color:red;">*</span>)</label>
                        <input type="email" name="email" class="form-control" placeholder="Vložte svůj e-mail ...">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-phone " aria-hidden="true"></i> Vaše číslo (<span style="color:red;">*</span>)</label>
                        <input type="text" name="phone" class="form-control" placeholder="Vložte své číslo ve tvaru +420 123 456 789 ...">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-comment " aria-hidden="true"></i> Zpráva (<span style="color:red;">*</span>)</label>
                        <textarea rows="3" name="message" class="form-control" placeholder="Vložte text zprávy ..."></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="send" value="Odeslat" class="btn btn-danger"/>
                    </div>
                </form>
            </div>
        </center>
    </div>
</section>

<!-- Kontakty-->
<section class="page-section" id="kontakty" style="background-color: #F5F5F5;color:black;padding-top:60px;padding-bottom:30px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 >Kontakty</h2>
                <hr style="background-color: #FF7F50;padding-top:2px;">
                <img src="{{asset("images/kontaktyImage.png")}}" alt="User ikonka" height="100" width="100" title="User contact" style="margin-top:15px;"/>
                <p class="text-black" style="font-size: 20px;margin-top:10px;">Pavel Sklenář</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
                <i class="fa fa-phone fa-3x"></i>
                <div class="">+420 123 456 789
                </div>
            </div>
            <div class="col-lg-4 mr-auto text-center">
                <i class="fa fa-envelope fa-3x"></i>
                <a class="d-block " href="mailto:sklenar@aksklenar.com">tozondoservice@gmail.com</a>
            </div>
        </div>
    </div>
    <br><br>
    <center><p class="">Informační systém pro správu zaměstnanců ve firmě 2020.</p><br></center>
</section>

<!-- Patička-->
<footer class="bg-light ">
    <div class="container"><br>
        <center><div class="small text-center text-muted">Copyright&copy 2020 - sklenix</div></center>
    </div>
    <br>
</footer>

</body>
</html>
