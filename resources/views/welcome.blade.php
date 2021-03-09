<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="description" content="Tozondo - Open Source systém pro rychlou a efektivní správu Vašich zaměstnanců.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="system, zamestnanci, sprava zamestnancu">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google-site-verification" content="KMzF_yjuKSs5_WPMFKjjvZcIA8Q6NI0fgdSB_MmRMGw" />
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

        .seznam{
            padding-top:10px;
            padding-bottom:10px;
            padding-right:25px;
            padding-left:30px;
            margin-bottom:15px;
            border-radius: 25px;
            font-size: 17px;
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

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SVGZR6H95B"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-SVGZR6H95B');
    </script>

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
            <li class="nav-item"><a href="#vlastnosti" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Funkce systému</a> </li>
            <li class="nav-item"><a href="#formular" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Napište nám</a> </li>
            <li class="nav-item"><a href="#kontakty" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Kontakty</a> </li>
        </ul>
        <ul class="navbar-nav navbar-collapse justify-content-end">
            @if (Route::has('login') )
                @auth
                    <li class="nav-item"><a href="{{ url('/company/dashboard') }}" class="nav-link p-3" style="font-family: 'Amatic SC', cursive;" >Vstup do systému</a> </li>
                @else
                    @auth('employee')
                        <li class="nav-item"><a href="{{ url('/employee/dashboard') }}" class="nav-link p-3" style="font-family: 'Amatic SC', cursive;" >Vstup do systému</a> </li>
                    @else
                        @auth('admin')
                            <li class="nav-item"><a href="{{ url('/admin/dashboard') }}" class="nav-link p-3" style="font-family: 'Amatic SC', cursive;" >Vstup do systému</a> </li>
                        @else
                            <li class="nav-item"><a href="{{ route('company') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Přihlásit se</a> </li>
                    @if (Route::has('register'))
                        <li class="nav-item"> <a href="{{ route('register') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;margin-right: 20px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" >Registrace</a> </li>
                          @endif
                        @endif
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
                    <div class="seznam">
                        <li>Zaregistrujete se pomocí tlačítka Registrace, které se nachází v pravém horním rohu, po registraci obdržíte email na Vámi zadanou emailovou adresu, kterou jste použil pro registraci.
                            Zaslaný email slouží pro ověření Vaší emailové adresy. V emailu se bude vyskytovat odkaz, po stisknutí na onen odkaz budete přesměrováni na domovskou stránku Vašeho profilu.
                            Zároveň s emailem určeným pro verifikaci Vaší emailové adresy Vám bude zaslána také pozvánka k Vaší složce na Google Drive, na odkaz klikat nemusíte, Vaše Google Drive složka Vám bude automaticky zpřístupněna přes Váš profil v menu.
                        </li>
                    </div>
                    <div class="seznam">
                        <li>Pro přihlášení stiskněte v pravém horním rohu možnost Přihlásit se. Do systému se lze přihlásit Vaším e-mailem, nebo přihlašovacím jménem (loginem), který jste zadal při samotné registraci účtu. Po přihlášení se ocitnete na domovské stránce profilu (dashboard), kde jsou vyobrazeny všechny možnosti systému (popsáno níže).
                        </li>
                    </div>
                    <div class="seznam">
                        <li>Na domovské stránce (dashboard) jsou jednotlivá tlačítka pro manipulaci s informačním systémem, k manipulaci se systémem lze také použít postranní panel, který se dá skrýt. V horním pravém rohu máte možnost upravit Váš profil, nebo se odhlásit.
                        </li>
                    </div>
                    <div class="seznam">
                        <li>
                            Poté, co vytvoříte zaměstnance, se ve Vaší Google Drive složce vytvoří nová složka se jménem zaměstnance. Název nově vytvořené složky je následující: [Jméno] [Příjmení].
                            Po vytvoření zaměstnance dostane zaměstnanec přístup nejen ke svému účtu v rámci informačního systému, ale také odkaz na jeho složku v Google Drive. Zaměstnanec si ve svém účtu může zobrazit
                            aktuální směny (aktuální týden), historii směn, zranění, statistiky, a také může vytvářet, editovat a mazat dovolené, nemocenské a nahlášení. Vytvořené dovolené, nemocenské a nahlášení nejsou
                            pro účet firmy viditelné dokud zaměstnanec o tyto dovolené, nemocenské či nahlášení nepožádá pomocí tlačítka, poté lze tyto položky vidět v účtu firmy. Zaměstnanec také může generovat svůj
                            týdenní rozvrh ve formátu PDF, stejně tak historii směn. Také může generovat své dovolené, nemocenské a nahlášení. Zaměstnanec také vidí do své složky v Google Drive, kde může přes informační systém
                            přidávat složky, nahrávat soubory, nebo také může jednotlivé složky a soubory mazat. Zaměstnanec nevidí do Google Drive složky firmy. V pravém horním rohu se zaměstnanec po stisknutí na své jméno může odhlásit
                            z informačního systému, nebo může upravit svůj profil (včetně hesla).
                        </li>
                    </div>
                    <div class="seznam">
                        <li>
                            V systému můžete vytvářet směny, které poté můžete přiřazovat zaměstnancům, nebo také můžete zaměstnanci přiřazovat směny. U každé směny lze spravovat docházku jednotlivých zaměstnanců, je také
                            možnost spravovat docházku přímo zaměstnanci v profilu. Každé směně můžete dávat různou důležitost, od zaučení po velmi vysokou důležitost.
                        </li>
                    </div>
                    <div class="seznam">
                        <li>
                            Samotná správa docházky se skládá z pěti dílčích částí, těmito častmi jsou: Příchod, Odchod evidovaný samotným zaměstnancem (nižší priorita), Příchod, Odchod evidovaný firmou (vyšší priorita)
                            , další částí je status docházky (nejvyšší priorita), tedy pokud jen chcete evidovat, že zaměstnanec přišel a časy příchodu a odchodu Vás nezajímají, tak stačí nastavit status docházky na hodnotu "OK" a bude zaevidováno, že zaměstnanec přišel.
                            Poslední části je možnost poznámky k jednotlivým docházkám, ta slouží například pro evidování různých událostí při konkrétní směně u konkrétního zaměstnance. Odpracované hodiny se vždy prioritně týkají příchodu a odchodu evidovaných firmou,
                            pokud příchod, nebo odchod firma nevyplnila, bude se počítat s příchodem, nebo odchodem zaevidovaným zaměstnancem, pokud i zaměstnanec docházku nevyplní, tak u směny zaměstnance bude u příchodů, odchodů napsáno "Nezapsáno", pokud nebude vyplněn ani status docházky, tak status bude
                            zobrazen také jako nezapsaný. Pokud zaměstnanec vyplní příchod, nebo odchod, kterému nevěříte, lze pomocí statusu dát nepříchod, následně se časy zapsané zaměstnancem ignorují.
                        </li>
                    </div>
                    <div class="seznam">
                        <li>
                            Na základě výkonů jednotlivých zaměstnanců ony zaměstnance můžete hodnotit na základě jejich absence, spolehlivosti a pracovitosti, z těchto hodnot je poté vypočítán průměr, který je zobrazen v centru hodnocení, nebo v profilu zaměstnance.
                        </li>
                    </div>
                    <div class="seznam">
                        <li>V systému máte možnost vytvářet Jazyky. Po vytvoření účtu máte defaultně nula přístupných jazyků, pomocí tlačítka Přidat jazyk, můžete přidávat libovolné jazyky, které potom můžete přiřazovat jednotlivým zaměstnancům. Vaše přidané jazyky můžete i smazat, pomocí tlačítka Smazat jazyk/y.
                        </li>
                    </div>
                    <div class="seznam">
                        <li>V systému také můžete vytvářet, editovat, schvalovat či neschvalovat žádosti o dovolené, nemocenské. Můžete evidovat také zranění, která se stala při směnách. Dále můžete schvalovat, či neschvalovat nahlášení jednotlivých zaměstnanců.</li>
                    </div>
                    <div class="seznam">
                        <li>V systému také máte možnost vytvářet složky do Vaši Google Drive složky, aniž by jste museli přímo do Google Drive, tato možnost se týká také nahrání souborů a mazání souborů. Ze systému lze mazat ale pouze ty soubory a složky, které byly vytvořené za pomocí informačního systému.</li>
                    </div>
                    <div class="seznam">
                        <li>V systému můžete také generovat různé soubory ve formátu PDF, například můžete generovat seznam zaměstnanců, směn, souhrn hodnocení zaměstnanců, zaměstnancovy směny a zaměstnance v konkrétní směně. </li>
                    </div>
                    <div class="seznam">
                        <li>V systému si také můžete zobrazit různé statistiky, které se týkají Vašich zaměstnanců.</li>
                    </div>

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
            <div class="col-lg-3 col-md-3 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/google_drive.png")}}" alt="Google Drive ikonka" height="100" width="100" title="Google Drive"/>
                    <h3 class="h4 mb-2">Google Drive</h3>
                    <p class="text-muted mb-0 text-justify">Můžete spravovat Vaši Google Drive složku, vytvářet v ní složky, nahrávat soubory a mazat soubory či složky.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/SmenyWelcome.png")}}" alt="Směny ikonka" height="92" width="92" title="Směny"/>
                    <h3 class="h4 mt-2">Správa směn</h3>
                    <p class="text-muted mb-0">Můžete vytvářet směny a poté je přidávat zaměstnancům.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/WelcomeList.png")}}" alt="Docházky ikonka" height="90" width="90" title="Evidování docházky" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Docházka</h3>
                    <p class="text-muted mb-0">Můžete spravovat docházku jednotlivých zaměstnanců.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/EmployeeWelcome.png")}}" alt="Zaměstnanci ikonka" height="100" width="100" title="Zaměstnanci"/>
                    <h3 class="h4 mb-2">Správa zaměstnanců</h3>
                    <p class="text-muted mb-0">Můžete vytvářet zaměstnance, které potom v systému můžete jednotlivě spravovat.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/pdf_generator.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Generování souborů v PDF</h3>
                    <p class="text-muted mb-0">Můžete generovat například přehled zaměstnanců, směny, ... .</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/vacation.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Správa dovolených</h3>
                    <p class="text-muted mb-0">Můžete spravovat jednotlivé dovolené zaměstnanců.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/WelcomeDiseaseIcon.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Správa nemocenských</h3>
                    <p class="text-muted mb-0">Můžete spravovat jednotlivé nemocenské zaměstnanců.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/report.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Správa nahlášení</h3>
                    <p class="text-muted mb-0">Můžete spravovat jednotlivé nahlášení zaměstnanců.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 text-center">
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/JazykyWelcome.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Správa jazyků</h3>
                    <p class="text-muted mb-0">Můžete spravovat jazyky, které poté můžete přidělovat zaměstnancům.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <img src="{{asset("images/statistics.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                    <h3 class="h4 mb-2">Statistiky</h3>
                    <p class="text-muted mb-0">Můžete si zobrazovat například počet směn, zaměstnanců, pravděpodobnost příchodu na směnu u zaměstnanců, ...</p>
                </div>
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
                <img src="{{asset("images/EmployeeWelcome.png")}}" alt="User ikonka" height="150" width="150" title="User contact" style="margin-top:15px;"/>
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
    <center><p class="">Informační systém pro správu zaměstnanců ve firmě 2020.</p></center>
    <div class="text-center">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Odkaz na úvodní obrázek, který je umístěn na této stránce:</u>
        <ul style="list-style-type: none;margin-top: 5px;">
            <li><a href="https://www.pexels.com/cs-cz/foto/ryma-snih-krajina-priroda-540518/" target="_blank">zde</a>, vytvořil uživatel: Billel Moula (<a href="https://www.pexels.com/cs-cz/@billelmoula" target="_blank">profil</a>), upravil sklenix.</li>
        </ul>
    </div>
    <div class="text-center">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Odkazy na ikonky na této stránce:</u>
        <ul style="list-style-type: none;margin-top: 5px;">
            <li>Ikonka Google Drive  - <a href="https://freeicons.io/yellow-folders-with-web-icons/drive-google-drive-google-yellow-folder-work-archive-cloud-icon-52432#" target="_blank">zde</a>, vytvořil uživatel: Reda (<a href="https://freeicons.io/profile/6156" target="_blank">profil</a>)</li>
            <li>Ikonka směny - <a href="https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3537#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka docházky - <a href="https://freeicons.io/list,-mail-and-map-icons/list-icon-3298#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka osoby - <a href="https://freeicons.io/contact-us-set-1/a-icon-47345#" target="_blank">zde</a>, vytvořil uživatel: Mohammed Salim (<a href="https://freeicons.io/profile/5863" target="_blank">profil</a>)</li>
            <li>Ikonka PDF - <a href="https://freeicons.io/vector-file-types-icons/pdf-icon-2304#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka dovolené - <a href="https://freeicons.io/weather-2/icon-sun-lineal-color-icon-28915" target="_blank">zde</a>, vytvořil uživatel: Oscar EstMont (<a href="https://freeicons.io/profile/3063" target="_blank">profil</a>)</li>
            <li>Ikonka nemocenské - <a href="https://freeicons.io/flat-medical-icons-set/medical-report-medicament-medicine-hospital-care-healthcare-icon-52498#" target="_blank">zde</a>, vytvořil uživatel: Reda (<a href="https://freeicons.io/profile/6156" target="_blank">profil</a>)</li>
            <li>Ikonka nahlášení - <a href="https://freeicons.io/material-icons-content-2/report-icon-16214#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka jazyků - <a href="https://freeicons.io/contact-us-icons/communication-connect-international-network-browser-global-internet-icon-37837#" target="_blank">zde</a>, vytvořil uživatel: MD Badsha Meah (<a href="https://freeicons.io/profile/3335" target="_blank">profil</a>)</li>
            <li>Ikonka statistik - <a href="https://freeicons.io/business-collection-icon/pie-chart-icon-22286#" target="_blank">zde</a>, vytvořil uživatel: Muhammad Haq (<a href="https://freeicons.io/profile/823" target="_blank">profil</a>)</li>
            Ostatní ikonky jsou z balíčku ikonek <a href="https://fontawesome.com/v4.7.0/" target="_blank">Font Awesome</a>.
        </ul>
    </div>
    <div class="text-center">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Odkazy na ikonky v systému (dashboard, datové tabulky, profil):</u>
        <ul style="list-style-type: none;margin-top: 5px;">
            <li>Ikonka zaměstnance - <a href="https://freeicons.io/essential-collection-5/user-icon-icon-4#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka přidání zaměstnance - <a href="https://freeicons.io/user-icons/user-icon-3583#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka odebrání zaměstnance - <a href="https://freeicons.io/user-icons/user-icon-3586#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka seznamu zaměstnanců - <a href="https://freeicons.io/user-icons/user-icon-3593#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka hodnocení zaměstnanců - <a href="https://freeicons.io/user-icons/user-icon-3595#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka přidání směny  - <a href="https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3550#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka odebrání směny - <a href="https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3556#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka seznamu směn - <a href="https://freeicons.io/business-set-1/calendar-clock-icon-17782#" target="_blank">zde</a>, vytvořil uživatel: Anu Rocks (<a href="https://freeicons.io/profile/730" target="_blank">profil</a>)</li>
            <li>Ikonka seznamu firem - <a href="https://freeicons.io/regular-life-icons/building-icon-17778#" target="_blank">zde</a>, vytvořil uživatel: Anu Rocks (<a href="https://freeicons.io/profile/730" target="_blank">profil</a>)</li>
            <li>Ikonka aktuálních směn - <a href="https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3558#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka docházky - <a href="https://freeicons.io/business-set-1/calendar-appointment-event-date-icon-38590#" target="_blank">zde</a>, vytvořil uživatel: MD Badsha Meah (<a href="https://freeicons.io/profile/3335" target="_blank">profil</a>)</li>
            <li>Ikonka přidání složky - <a href="https://freeicons.io/file,-folder-and-image-icons/folder-icon-3250#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka smazání souborů/složek - <a href="https://freeicons.io/file,-folder-and-image-icons/folder-icon-3248#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka nahrání souboru - <a href="https://freeicons.io/file,-folder-and-image-icons/folder-icon-3258#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka Google Drive  - <a href="https://freeicons.io/yellow-folders-with-web-icons/drive-google-drive-google-yellow-folder-work-archive-cloud-icon-52432#" target="_blank">zde</a>, vytvořil uživatel: Reda (<a href="https://freeicons.io/profile/6156" target="_blank">profil</a>)</li>
            <li>Ikonka PDF - <a href="https://freeicons.io/vector-file-types-icons/pdf-icon-2304#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka statistik - <a href="https://freeicons.io/business-collection-icon/pie-chart-icon-22286#" target="_blank">zde</a>, vytvořil uživatel: Muhammad Haq (<a href="https://freeicons.io/profile/823" target="_blank">profil</a>)</li>
            <li>Ikonka dovolené - <a href="https://freeicons.io/weather-2/icon-sun-lineal-color-icon-28915" target="_blank">zde</a>, vytvořil uživatel: Oscar EstMont (<a href="https://freeicons.io/profile/3063" target="_blank">profil</a>)</li>
            <li>Ikonka zranění - <a href="https://freeicons.io/healthcare-2/healthcare-medical-injury-icon-43042#" target="_blank">zde</a>, vytvořil uživatel: www.wishforge.games (<a href="https://freeicons.io/profile/2257" target="_blank">profil</a>)</li>
            <li>Ikonka nemocenských - <a href="https://freeicons.io/medical-care-and-health-set/viruses-virus-icon-39031#" target="_blank">zde</a>, vytvořil uživatel: Shabna Ashraf (<a href="https://freeicons.io/profile/3423" target="_blank">profil</a>)</li>
            <li>Ikonka nahlášení - <a href="https://freeicons.io/material-icons-content-2/report-icon-16214#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
            <li>Ikonka přidání jazyka  - <a href="https://freeicons.io/material-icons-actions/language-icon-8495#" target="_blank">zde</a>, vytvořil uživatel: Free Preloaders (<a href="https://freeicons.io/profile/726" target="_blank">profil</a>), upravil: sklenix (přidání pluska do ikonky)</li>
            <li>Ikonka odebrání jazyka  - <a href="https://freeicons.io/material-icons-actions/language-icon-8495#" target="_blank">zde</a>, vytvořil uživatel: Free Preloaders (<a href="https://freeicons.io/profile/726" target="_blank">profil</a>), upravil: sklenix (přidání mínuska do ikonky)</li>
            Ostatní ikonky jsou z balíčku ikonek <a href="https://fontawesome.com/v4.7.0/" target="_blank">Font Awesome</a>.
        </ul>
    </div>
    <div class="text-center">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Odkaz na pozadí při přihlašování, registraci, resetování hesla a ověření emailu:</u>
        <ul style="list-style-type: none;margin-top: 5px;">
            <li><a href="https://www.toptal.com/designers/subtlepatterns/cloudy-day/" target="_blank">zde</a>, vytvořili: <a href="https://www.toptal.com/designers/subtlepatterns/" target="_blank">Toptal Subtle Patterns</a>.</li>
        </ul>
    </div>
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
