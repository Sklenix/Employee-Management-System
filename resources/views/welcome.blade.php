<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- Nazev souboru: welcome.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje uvodni webovou stranku informacniho systemu. -->
    <!-- definice metadat -->
    <meta name="description" content="Tozondo - Systém pro rychlou a efektivní správu Vašich zaměstnanců.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="system, zamestnanci, sprava zamestnancu">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google-site-verification" content="KMzF_yjuKSs5_WPMFKjjvZcIA8Q6NI0fgdSB_MmRMGw" />
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
    <!-- import kaskadovych stylu, javascriptu a jQuery -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styly.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Tozondo</title>
    <style>
        /* Nastaveni fontu obsahu stranky */
        body { font-family: 'Roboto', sans-serif; }
        /* Nastaveni efektu skrolovani, viz https://www.w3schools.com/cssref/pr_scroll-behavior.asp  */
        html { scroll-behavior: smooth; }
        /* Definice prvku seznamu */
        .seznam{padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:30px;margin-bottom:15px;border-radius: 25px;font-size: 17px;}
        /* Zmena obrazku pri nizkem rozliseni */
        /* obrazek pochazi od Billel Moula, odkaz: https://www.pexels.com/cs-cz/foto/ryma-snih-krajina-priroda-540518/ */
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
<body data-spy="scroll" data-target="#obsahUvodniStrana">
    <!-- Definice menu-->
    <nav class="navbar sticky-top navbar-light navbar-expand-sm efektMenu" style="background-color: #F5F5F5;box-shadow: 0px 5px 1px #DCDCDC;" id="obsahUvodniStrana">
        <!-- Sekce logo -->
        <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 24px;margin-left: 20px;"><img src="{{ URL::asset('images/logo.png') }}" alt="Logo" height="35" width="40"/> | Tozondo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidka">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="rozbalovaciNabidka">
            <ul class="navbar-nav navbar-collapse">
                <li class="nav-item"><a href="#funkce" class="nav-link p-3" style="font-family: 'Roboto', sans-serif;letter-spacing: 1px; font-size: 15px;font-weight: bold;text-transform: uppercase;">Jak to funguje</a></li>
                <li class="nav-item"><a href="#vlastnosti" class="nav-link p-3" style="font-family: 'Roboto', sans-serif;letter-spacing: 1px; font-size: 15px;font-weight: bold;text-transform: uppercase;">Funkce systému</a></li>
                <li class="nav-item"><a href="#formular" class="nav-link p-3" style="font-family: 'Roboto', sans-serif;letter-spacing: 1px; font-size: 15px;font-weight: bold;text-transform: uppercase;">Napište nám</a></li>
                <li class="nav-item"><a href="#kontakty" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; letter-spacing: 1px;font-size: 15px;font-weight: bold;text-transform: uppercase;">Kontakty</a></li>
            </ul>
            <ul class="navbar-nav navbar-collapse justify-content-end">
                    @auth <!-- Pokud je uzivatel uz v systemu prihlasen, tak se mu zobrazi misto moznosti "Přihlásit se" a "Registrace" možnost "Vstup do systému" -->
                        <li class="nav-item"><a href="{{ url('/company/dashboard') }}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif;font-size: 15px;font-weight: bold;text-transform: uppercase;">Vstoupit do systému</a></li>
                    @else
                        @auth('employee')
                            <li class="nav-item"><a href="{{ url('/employee/dashboard') }}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif;font-size: 15px;font-weight: bold;text-transform: uppercase;">Vstoupit do systému</a></li>
                        @else
                            @auth('admin')
                                <li class="nav-item"><a href="{{ url('/admin/dashboard') }}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif;font-size: 15px;font-weight: bold;text-transform: uppercase;">Vstoupit do systému</a></li>
                            @else
                                <li class="nav-item"><a href="{{ route('renderCompanyLogin') }}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 15px;font-weight: bold;text-transform: uppercase;">Přihlásit se</a></li>
                                <li class="nav-item"> <a href="{{ route('register') }}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 15px;font-weight: bold;text-transform: uppercase;">Registrace</a></li>
                            @endif
                        @endif
                    @endif
            </ul>
        </div>
    </nav>

    <!-- Pozadí (obrázek)-->
    <div class="container col-12 pozadi" style="margin-top:5px;"></div>

    <!-- Definice obsahu -->

    <!-- Jak to funguje-->
    <section class="page-section" id="funkce" style="padding-top:40px;padding-bottom: 60px;background-color: #17a2b8;" >
        <div class="container">
            <h2 class="text-center" style="color:white;padding-bottom: 5px;">Jak to funguje</h2>
            <div class="row">
                <div class="col-lg-1 col-md-1 text-center">
                </div>
                <div class="col-lg-10 col-md-10 text-center">
                    <ol class="text-white-50 text-justify" style="font-size: 16px;">
                        <div class="seznam">
                            <li>Zaregistrujete se pomocí tlačítka Registrace, které se nachází v pravém horním rohu, po registraci obdržíte email na Vámi zadanou emailovou adresu, kterou jste použil pro registraci.
                                Zaslaný email slouží pro ověření Vaší emailové adresy. V emailu se bude vyskytovat odkaz, po stisknutí na onen odkaz budete přesměrováni na domovskou stránku Vašeho profilu.
                                Zároveň s emailem určeným pro verifikaci Vaší emailové adresy Vám bude zaslána také pozvánka k Vaší složce na Google Drive (pakliže jste zaškrtli možnost "Chci v rámci svého účtu používat Google Drive.").
                                Na odkaz klikat nemusíte, Vaše Google Drive složka Vám bude automaticky zpřístupněna na domovské stránce Vašeho účtu.
                            </li>
                        </div>
                        <div class="seznam">
                            <li>Pro přihlášení stiskněte v pravém horním rohu možnost Přihlásit se. Do systému se lze přihlásit pomocí Vaší emailové adresy či pomocí uživatelského jména (login).
                                Po přihlášení se ocitnete na domovské stránce účtu (Dashboard), kde jsou vyobrazeny všechny možnosti systému (popsáno níže).
                            </li>
                        </div>
                        <div class="seznam">
                            <li>Na domovské stránce (dashboard) jsou jednotlivá tlačítka pro manipulaci s informačním systémem, k manipulaci se systémem lze také použít postranní panel, který se dá skrýt.
                                V horním pravém rohu máte možnost upravit údaje v rámci Vašeho účtu nebo se odhlásit.
                            </li>
                        </div>
                        <div class="seznam">
                            <li>
                                Při vytváření zaměstnance máte možnost mu nasdílet jeho Google Drive složku, jež se mu vytvoří po dokončení jeho registrace. Název nově vytvořené složky je následující: [Jméno] [Příjmení].
                                Tedy zaměstnanec může dostat přístup nejen ke svému účtu v rámci informačního systému, ale může také dostat možnost manipulace s jeho Google Drive složkou v rámci informačního systému. Zaměstnanec si ve svém účtu může zobrazit
                                aktuální směny (aktuální týden), historii směn, zranění, statistiky, a také může vytvářet, editovat a mazat dovolené, nemocenské a nahlášení. Vytvořené dovolené, nemocenské a nahlášení nejsou
                                pro účet firmy viditelné dokud zaměstnanec o tyto dovolené, nemocenské či nahlášení nepožádá pomocí tlačítka, poté lze tyto položky vidět v účtu firmy. Zaměstnanec také může generovat svůj
                                týdenní rozvrh ve formátu PDF, stejně tak historii směn. Také může generovat své dovolené, nemocenské a nahlášení. Pokud firma zaškrtne možnost nasdílení složky, tak zaměstnanec vidí do své složky v Google Drive, kde může přes informační systém
                                přidávat složky, nahrávat soubory, nebo také může jednotlivé složky a soubory mazat. Zaměstnanec nevidí do Google Drive složky firmy. V pravém horním rohu se zaměstnanec po stisknutí na své jméno může odhlásit
                                z informačního systému. Zaměstnanci je také umožněno upravovat si své údaje.
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
                                Samotná správa docházky se skládá ze šesti dílčích částí, těmito častmi jsou: Příchod, Odchod evidovaný samotným zaměstnancem (nižší priorita), Příchod, Odchod evidovaný firmou (vyšší priorita)
                                , další částí je status docházky (nejvyšší priorita), tedy pokud jen chcete evidovat, že zaměstnanec přišel a časy příchodu a odchodu Vás nezajímají, tak stačí nastavit status docházky na hodnotu "OK" a bude zaevidováno, že zaměstnanec přišel.
                                Poslední části je možnost poznámky k jednotlivým docházkám, ta slouží například pro evidování různých událostí při konkrétní směně u konkrétního zaměstnance. Odpracované hodiny se vždy prioritně týkají příchodu a odchodu evidovaných firmou,
                                pokud příchod, nebo odchod firma nevyplnila, bude se počítat s příchodem, nebo odchodem zaevidovaným zaměstnancem, pokud i zaměstnanec docházku nevyplní, tak u směny zaměstnance bude u příchodů, odchodů napsáno "Nezapsáno", pokud nebude vyplněn ani status docházky, tak status bude
                                zobrazen také jako nezapsaný.
                            </li>
                        </div>
                        <div class="seznam">
                            <li>
                                Na základě výkonů jednotlivých zaměstnanců můžete ony zaměstnance hodnotit na základě jejich absence, spolehlivosti a pracovitosti.
                                 Z těchto hodnot je poté vypočítán průměr, který je zobrazen v centru hodnocení, nebo v profilu zaměstnance.
                            </li>
                        </div>
                        <div class="seznam">
                            <li>V systému máte možnost vytvářet Jazyky. Po vytvoření účtu máte defaultně nula přístupných jazyků, pomocí tlačítka "Vytvořit jazyk" můžete přidávat libovolné jazyky, které následně můžete
                                přiřazovat jednotlivým zaměstnancům. Vaše přidané jazyky můžete i odstranit, pomocí tlačítka "Odstranit jazyky".
                            </li>
                        </div>
                        <div class="seznam">
                            <li>V systému také můžete vytvářet, editovat, schvalovat či neschvalovat žádosti o dovolené, nemocenské.
                                Můžete evidovat také zranění, která se stala při směnách. Dále můžete schvalovat či neschvalovat nahlášení jednotlivých zaměstnanců.</li>
                        </div>
                        <div class="seznam">
                            <li>V systému také máte možnost vytvářet složky do Vaši Google Drive složky, aniž by jste museli přímo do Google Drive,
                                tato možnost se také týká nahrávání souborů či jejich odstraňování. Ze systému lze odstraňovat ale pouze ty soubory a složky, které byly vytvořené za pomocí informačního systému.</li>
                        </div>
                        <div class="seznam">
                            <li>V systému můžete také generovat různé soubory ve formátu PDF, například můžete generovat seznam zaměstnanců, směn, souhrn hodnocení zaměstnanců, zaměstnancovy směny a zaměstnance na konkrétní směně. </li>
                        </div>
                        <div class="seznam">
                            <li>V systému si také můžete zobrazit různé statistiky, které se týkají Vašich zaměstnanců.</li>
                        </div>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Vlastnosti systému-->
    <section class="page-section"  id="vlastnosti" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="container">
            <h2 class="text-center" style="margin-bottom: 40px;">Funkce systému</h2>
            <div class="row">
                <!-- Ikonka Google Drive: https://freeicons.io/yellow-folders-with-web-icons/drive-google-drive-google-yellow-folder-work-archive-cloud-icon-52432#, vytvořil Reda -->
                <div class="col-lg-3">
                    <div class="text-center mt-1">
                        <img src="{{asset("images/google_drive.png")}}" alt="Google Drive ikonka" height="100" width="100" title="Google Drive"/>
                        <h4 class="mt-2">Google Drive</h4>
                        <p class="text-muted text-justify">Můžete spravovat Vaši Google Drive složku, vytvářet v ní složky, nahrávat soubory a mazat soubory či složky.</p>
                    </div>
                </div>
                <!-- Ikonka směny: https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3537#, vytvořil icon king1 -->
                <div class="col-lg-3">
                    <div class="text-center mt-1">
                        <img src="{{asset("images/SmenyWelcome.png")}}" alt="Směny ikonka" height="92" width="92" title="Směny"/>
                        <h4 class="mt-3">Správa směn</h4>
                        <p class="text-muted">Můžete vytvářet směny a poté je přidávat zaměstnancům.</p>
                    </div>
                </div>
                <!-- Ikonka docházky: https://freeicons.io/list,-mail-and-map-icons/list-icon-3298#, vytvořil icon king1 -->
                <div class="col-lg-3">
                    <div class="text-center mt-1">
                        <img src="{{asset("images/WelcomeList.png")}}" alt="Docházky ikonka" height="90" width="90" title="Evidování docházky" style="margin-bottom: 10px;"/>
                        <h4 class="mt-2">Docházka</h4>
                        <p class="text-muted">Můžete spravovat docházku jednotlivých zaměstnanců.</p>
                    </div>
                </div>
                <!-- Ikonka zamestnancu: https://freeicons.io/contact-us-set-1/a-icon-47345#, vytvořil Mohammed Salim -->
                <div class="col-lg-3">
                    <div class="text-center mt-1">
                        <img src="{{asset("images/EmployeeWelcome.png")}}" alt="Zaměstnanci ikonka" height="100" width="100" title="Zaměstnanci"/>
                        <h4 class="mt-2">Správa zaměstnanců</h4>
                        <p class="text-muted">Můžete vytvářet zaměstnance, které potom v systému můžete jednotlivě spravovat.</p>
                    </div>
                </div>
                <!-- Ikonka generovani PDF: https://freeicons.io/vector-file-types-icons/pdf-icon-2304#, vytvořil icon king1 -->
                <div class="col-lg-3">
                    <div class="text-center mt-4">
                        <img src="{{asset("images/pdf_generator.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                        <h4 class="mt-2">Generování souborů v PDF</h4>
                        <p class="text-muted">Můžete generovat například přehled zaměstnanců, směny, ... .</p>
                    </div>
                </div>
                <!-- Ikonka dovolenych: https://freeicons.io/weather-2/icon-sun-lineal-color-icon-28915, vytvořil Oscar EstMont -->
                <div class="col-lg-3">
                    <div class="text-center mt-4">
                        <img src="{{asset("images/vacation.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                        <h4 class="mt-2">Správa dovolených</h4>
                        <p class="text-muted">Můžete spravovat jednotlivé dovolené zaměstnanců.</p>
                    </div>
                </div>
                <!-- Ikonka nemocenskych: https://freeicons.io/flat-medical-icons-set/medical-report-medicament-medicine-hospital-care-healthcare-icon-52498#, vytvořil Reda -->
                <div class="col-lg-3 ">
                    <div class="text-center mt-4">
                        <img src="{{asset("images/WelcomeDiseaseIcon.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                        <h4 class="mt-2">Správa nemocenských</h4>
                        <p class="text-muted">Můžete spravovat jednotlivé nemocenské zaměstnanců.</p>
                    </div>
                </div>
                <!-- Ikonka nahlaseni: https://freeicons.io/material-icons-content-2/report-icon-16214#, vytvořil icon king1 -->
                <div class="col-lg-3 ">
                    <div class="text-center mt-4">
                        <img src="{{asset("images/report.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                        <h4 class="mt-2">Správa nahlášení</h4>
                        <p class="text-muted">Můžete spravovat jednotlivé nahlášení zaměstnanců.</p>
                    </div>
                </div>
                <div class="col-lg-3 text-center">
                </div>
                <!-- Ikonka jazyky: https://freeicons.io/contact-us-icons/communication-connect-international-network-browser-global-internet-icon-37837#, vytvořil MD Badsha Meah -->
                <div class="col-lg-3">
                    <div class="text-center mt-4">
                        <img src="{{asset("images/JazykyWelcome.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                        <h4 class="mt-2">Správa jazyků</h4>
                        <p class="text-muted">Můžete spravovat jazyky, které poté můžete přidělovat zaměstnancům.</p>
                    </div>
                </div>
                <!-- Ikonka statistik: https://freeicons.io/business-collection-icon/pie-chart-icon-22286#, vytvořil Muhammad Haq -->
                <div class="col-lg-3">
                    <div class="text-center mt-4">
                        <img src="{{asset("images/statistics.png")}}" alt="Generování ikonka" height="90" width="90" title="Generování PDF, Excel" style="margin-bottom: 10px;"/>
                        <h4 class="mt-2">Statistiky</h4>
                        <p class="text-muted">Můžete si zobrazovat například počet směn, zaměstnanců, pravděpodobnost příchodu na směnu u zaměstnanců, ...</p>
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
                    <h3>Dotazník.</h3>
                    <hr>
                    @if(count($errors) > 0) <!-- Zobrazeni chyb (pokud nejake nastaly) -->
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <ul class="text-left">
                                @foreach($errors->all() as $chyba)
                                    <li><strong>{{$chyba}}</strong></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if($zprava = Session::get('success')) <!-- Zobrazeni zpravy o uspechu -->
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$zprava}}</strong>
                        </div>
                    @endif
                    <form method="post" action="{{url('welcome/send')}}#formular">
                        @csrf
                        <div class="form-group">
                            <div class="alert alert-warning" role="alert" style="font-size: 16px;">
                                Položky označené (<span style="color:red;">*</span>) jsou povinné.
                            </div>
                            <label for="jmeno"><i class="fa fa-user"></i> Vaše celé jméno (<span style="color:red;">*</span>)</label>
                            <input type="text" name="jmeno" class="form-control" placeholder="Vložte své celé jméno ...">
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="fa fa-envelope"></i> Vaše emailová adresa (<span style="color:red;">*</span>)</label>
                            <input type="email" name="email" class="form-control" placeholder="Vložte svoji emailovou adresu ...">
                        </div>
                        <div class="form-group">
                            <label for="telefon"><i class="fa fa-phone"></i> Vaše telefonní číslo (<span style="color:red;">*</span>)</label>
                            <input type="text" name="telefon" class="form-control" placeholder="Vložte své telefonní číslo ve tvaru +420 123 456 789 ...">
                        </div>
                        <div class="form-group">
                            <label for="zprava"><i class="fa fa-comment"></i> Zpráva (<span style="color:red;">*</span>)</label>
                            <textarea rows="3" name="zprava" class="form-control" placeholder="Vložte text zprávy ..."></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="odeslat" value="Odeslat" class="btn btn-danger"/>
                        </div>
                    </form>
                </div>
            </center>
        </div>
    </section>

    <!-- Kontakty-->
    <section class="page-section" id="kontakty" style="background-color: #F5F5F5;color:black;padding-top:40px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8 text-center">
                    <h2 >Kontakty</h2>
                    <!-- Ikonka uzivatele: https://freeicons.io/contact-us-set-1/a-icon-47345#, vytvořil Mohammed Salim -->
                    <img src="{{asset("images/EmployeeWelcome.png")}}" alt="Uživatel ikonka" height="150" width="150" title="Kontakt" style="margin-top:15px;"/>
                    <p class="text-black" style="font-size: 20px;margin-top:10px;">Pavel Sklenář</p>
                </div>
            </div>
        <center><p class="">Informační systém pro správu zaměstnanců ve firmě 2021.</p></center>
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
                <li>Ikonka vytvoření zaměstnance - <a href="https://freeicons.io/user-icons/user-icon-3583#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka odstranění zaměstnance - <a href="https://freeicons.io/user-icons/user-icon-3586#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka seznamu zaměstnanců - <a href="https://freeicons.io/user-icons/user-icon-3593#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka hodnocení zaměstnanců - <a href="https://freeicons.io/user-icons/user-icon-3595#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka vytvoření směny  - <a href="https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3550#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka odstranění směny - <a href="https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3556#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka seznamu směn - <a href="https://freeicons.io/business-set-1/calendar-clock-icon-17782#" target="_blank">zde</a>, vytvořil uživatel: Anu Rocks (<a href="https://freeicons.io/profile/730" target="_blank">profil</a>)</li>
                <li>Ikonka seznamu firem - <a href="https://freeicons.io/regular-life-icons/building-icon-17778#" target="_blank">zde</a>, vytvořil uživatel: Anu Rocks (<a href="https://freeicons.io/profile/730" target="_blank">profil</a>)</li>
                <li>Ikonka aktuálních směn - <a href="https://freeicons.io/stopwatch-and-time-icons/stopwatch-time-clock-icon-3558#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka seznamu všech směn - <a href="https://freeicons.io/business-set-1/calendar-clock-icon-17782#" target="_blank">zde</a>, vytvořil uživatel: Anu Rocks (<a href="https://freeicons.io/profile/730" target="_blank">profil</a>)</li>
                <li>Ikonka docházky - <a href="https://freeicons.io/business-set-1/calendar-appointment-event-date-icon-38590#" target="_blank">zde</a>, vytvořil uživatel: MD Badsha Meah (<a href="https://freeicons.io/profile/3335" target="_blank">profil</a>)</li>
                <li>Ikonka vytvoření složky - <a href="https://freeicons.io/file,-folder-and-image-icons/folder-icon-3250#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka odebrání souborů/složek - <a href="https://freeicons.io/file,-folder-and-image-icons/folder-icon-3248#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka nahrání souboru - <a href="https://freeicons.io/file,-folder-and-image-icons/folder-icon-3258#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka Google Drive  - <a href="https://freeicons.io/yellow-folders-with-web-icons/drive-google-drive-google-yellow-folder-work-archive-cloud-icon-52432#" target="_blank">zde</a>, vytvořil uživatel: Reda (<a href="https://freeicons.io/profile/6156" target="_blank">profil</a>)</li>
                <li>Ikonka PDF - <a href="https://freeicons.io/vector-file-types-icons/pdf-icon-2304#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka statistik - <a href="https://freeicons.io/business-collection-icon/pie-chart-icon-22286#" target="_blank">zde</a>, vytvořil uživatel: Muhammad Haq (<a href="https://freeicons.io/profile/823" target="_blank">profil</a>)</li>
                <li>Ikonka dovolené - <a href="https://freeicons.io/weather-2/icon-sun-lineal-color-icon-28915" target="_blank">zde</a>, vytvořil uživatel: Oscar EstMont (<a href="https://freeicons.io/profile/3063" target="_blank">profil</a>)</li>
                <li>Ikonka zranění - <a href="https://freeicons.io/healthcare-2/healthcare-medical-injury-icon-43042#" target="_blank">zde</a>, vytvořil uživatel: www.wishforge.games (<a href="https://freeicons.io/profile/2257" target="_blank">profil</a>)</li>
                <li>Ikonka nemocenských - <a href="https://freeicons.io/medical-care-and-health-set/viruses-virus-icon-39031#" target="_blank">zde</a>, vytvořil uživatel: Shabna Ashraf (<a href="https://freeicons.io/profile/3423" target="_blank">profil</a>)</li>
                <li>Ikonka nahlášení - <a href="https://freeicons.io/material-icons-content-2/report-icon-16214#" target="_blank">zde</a>, vytvořil uživatel: icon king1 (<a href="https://freeicons.io/profile/3" target="_blank">profil</a>)</li>
                <li>Ikonka vytvoření jazyka  - <a href="https://freeicons.io/material-icons-actions/language-icon-8495#" target="_blank">zde</a>, vytvořil uživatel: Free Preloaders (<a href="https://freeicons.io/profile/726" target="_blank">profil</a>), upravil: sklenix (přidání pluska do ikonky)</li>
                <li>Ikonka odstranění jazyka  - <a href="https://freeicons.io/material-icons-actions/language-icon-8495#" target="_blank">zde</a>, vytvořil uživatel: Free Preloaders (<a href="https://freeicons.io/profile/726" target="_blank">profil</a>), upravil: sklenix (přidání mínuska do ikonky)</li>
                Ostatní ikonky jsou z balíčku ikonek <a href="https://fontawesome.com/v4.7.0/" target="_blank">Font Awesome</a>.
            </ul>
        </div>
        <div class="text-center pb-3">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Odkaz na pozadí při přihlašování, registraci, stránce pro zaslání emailové zprávy pro obnovu hesla, stránce pro obnovu hesla a ověření emailu:</u>
            <ul style="list-style-type: none;margin-top: 5px;">
                <li><a href="https://www.toptal.com/designers/subtlepatterns/cloudy-day/" target="_blank">zde</a>, vytvořili: <a href="https://www.toptal.com/designers/subtlepatterns/" target="_blank">Toptal Subtle Patterns</a>.</li>
            </ul>
        </div>
      </div>
    </section>
    <!-- Patička-->
    <footer class="bg-light">
        <div class="container"><br>
            <center><div class="small text-center text-muted">Copyright&copy; 2021 - sklenix</div></center>
        </div>
        <br>
    </footer>
</body>
</html>
