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
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="icon" href="{{ URL::asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
        <link rel="icon" href="{{ URL::asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawesome.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/solid.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/light.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.0.0/css/font-awesome.min.css">
        <link rel="icon" href="{{ URL::asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <title>Tozondo</title>

        <style>
            [class*="col-"],  /* Elements whose class attribute begins with "col-" */
            [class^="col-"] { /* Elements whose class attribute contains the substring "col-" */
                padding-left: 0;
                padding-right: 0;
            }
            body {
                font-family: 'Roboto', sans-serif;
            }
            .navbar-brand{
                font-family: 'Pacifico', cursive;
            }
            nav {
                width: 100%;
                box-shadow: 0px 6px 0px #dedede;
            }

            nav ul li a {
                text-decoration: none;
                font-weight: 800;
                text-transform: uppercase;
            }

            nav.fill ul li a {
                position: relative;
            }

            nav.fill ul li a:after {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
                width: 0%;
                content: '.';
                color: transparent;
                height: 1px;
            }


            nav.fill ul li a:hover {
                z-index: 1;
            }

            nav.fill ul li a:hover:after {
                z-index: -10;
                animation: fill 1s forwards;
                -webkit-animation: fill 1s forwards;
                -moz-animation: fill 1s forwards;
                opacity: 1;
            }


            /* Keyframes */
            @-webkit-keyframes fill {
                0% {
                    width: 0%;
                    height: 1px;
                }
                50% {
                    width: 100%;
                    height: 1px;
                }
                100% {
                    width: 100%;
                    height: 100%;
                    background: #6495ED;
                }
            }

            @media screen and (max-width: 730px) {
                .pozadi {
                    background-image: url({{ asset('images/pozadiMobily.png') }});
                    height: 110vh;
                    width: auto;
                    background-size: cover;
                    background-position: center center;

                }
            }
            @media screen and (min-width: 730px) {
                .pozadi {
                    background-image: url({{ asset('images/pozadi.png') }});
                    height: 95vh;
                    width: auto;
                    background-size: cover;

                    background-position: center center;

                }
            }
        </style>
    </head>
    <body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

    <nav class="fill navbar sticky-top navbar-light navbar-expand-sm " style="background-color: #F5F5F5" id="myScrollspy">
        <!-- Sekce logo -->
        <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" /> | Tozondo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdownMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="dropdownMenu">
            <ul class="navbar-nav navbar-collapse">
                <li class="nav-item"><a href="#aboutSystem" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >O Systému</a> </li>
            </ul>
        <ul class="navbar-nav navbar-collapse justify-content-end">
            @if (Route::has('login'))
                @auth
                    <li class="nav-item"><a href="{{ url('/home') }}" class="nav-link p-3" style="font-family: 'Amatic SC', cursive;" >Home</a> </li>
                @else
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;padding-left:25px;padding-right:25px;padding-bottom:15px;padding-top:15px;" >Přihlásit se</a> </li>
                    @if (Route::has('register'))
                        <li class="nav-item"> <a href="{{ route('register') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;margin-right: 20px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Registrace</a> </li>
                    @endif
                @endif
            @endif
        </ul>
        </div>
    </nav>

    <div class="container col-12 pozadi" style="margin-top:6px;"></div>

    <section class="page-section"  id="aboutSystem" style="padding-top:40px;padding-bottom: 40px;background-color: #F5F5F5" >
        <div class="container">
            <h2 class="text-center mt-0">O systému</h2>
            <hr />
            <div class="row">
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <img src="{{asset("images/googleDrive.png")}}" alt="Google Drive ikonka" height="100" width="100" title="Google Drive"/>
                        <h3 class="h4 mb-2">Google Drive</h3>
                        <p class="text-muted mb-0 text-justify">Můžete spravovat své zaměstnance i vzdáleně na Google Drive, kde se jim automaticky vytvoří složka, do které můžete ukládat!</p>
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
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <img src="{{asset("images/penizeImage.png")}}" alt="Výplata ikonka" height="90" width="90" title="Výplata" style="margin-bottom: 10px;"/>
                        <h3 class="h4 mb-2">Vyplácení zaměstnanců</h3>
                        <p class="text-muted mb-0">Můžete vyplácet konkrétní zaměstnance v jejich profilu.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">

                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <img src="{{asset("images/pdfImage.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                        <h3 class="h4 mb-2">Generování v Excelu, PDF</h3>
                        <p class="text-muted mb-0">Můžete generovat například přehled zaměstnanců, směny, ... .</p>
                    </div>
                </div>
                    <div class="col-lg-3 col-md-6 text-center">
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

    </body>
</html>
