<!DOCTYPE html>
<html>
<head>
    <!-- Nazev souboru: employee_profile.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje profil zamestnance. -->
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
    <link rel="icon" href="{{ asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- import kaskadovych stylu, javascriptu a jQuery -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styly.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <title>Tozondo - Profil</title>
    <style>
        body { font-family: 'Nunito', sans-serif; }
        @keyframes vyplneni {
            0% { width: 0%; }
            40% { width: 100%; height: 1px;background-color: #d9534f; }
            60% { width: 100%; width: 100%;background-color: #d9534f; }
            100% { background-color: #d9534f; width: 100%; height: 100%; }
        }
    </style>
</head>
<body style="background-color:cadetblue">
<!-- Definice menu -->
<nav class="navbar navbar-light navbar-expand-sm efektMenu" style="background-color: #F5F5F5;box-shadow: 0px 5px 1px #DCDCDC;">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40"/> | Profil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidka">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="rozbalovaciNabidka">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{route('homeEmployee')}}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;font-weight: bold;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;">DASHBOARD</a> </li>
            <li class="nav-item"><a href="{{ route('employeeLogout') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;font-weight: bold;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" onclick="event.preventDefault();document.getElementById('logoutForm').submit();">ODHLÁSIT SE</a> </li>
            <form id="logoutForm" action="{{ route('employeeLogout') }}" method="POST"> @csrf </form>
        </ul>
    </div>
</nav>
<br>
<!-- Definice obsahu -->
<div class="container" style="background-color: white;padding:30px;border-radius: 25px;">
    <div class="row">
        <div class="col-12 text-center" style="margin-bottom: 15px;"><h1> {{ Auth::user()->employee_name}} {{Auth::user()->employee_surname}}</h1><hr></div> <!-- Nadpis -->
    </div>
    <div class="row"> <!-- Sekce pro profilovou fotku -->
        <div class="col-3">
            <div class="text-center">
                @if($profilovka == NULL) <!-- sekce pro zobrazeni profiloveho obrazku -->
                    <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4# -->
                    <img src="{{URL::asset('images/default_profile.png') }}" class="profilovka img-thumbnail" alt="Profilový obrázek">
                @else
                    <img src ="{{asset('/storage/employee_images/'.Auth::user()->employee_picture) }}" width="250" style="margin-right: 5px;" alt="Profilový obrázek"/>
                @endif
                <!-- Definice chybove hlasky a take hlasky o uspechu, ktere se poji na nahravani ci odstranovani profiloveho obrazku -->
                @if(Session::has('obrazekZpravaFail'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ Session::get('obrazekZpravaFail') }}
                    </div>
                @endif
                @if(Session::has('obrazekZpravaSuccess'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ Session::get('obrazekZpravaSuccess') }}
                    </div>
                @endif
                <!-- Definice formulare pro nahravani profiloveho obrazku -->
                <form method="post" style="margin-top: 15px;" action="{{route('uploadEmployeeImage')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group nahratTlacitko">
                        <input type="file" onchange="ziskatNazevSouboru()" name="obrazek" required id="souborProNahrani" hidden/>
                        <label for="souborProNahrani" style="max-width: 250px;padding: 12px 12px;font-size:13px;background-color:#4aa0e6;border-radius: 20px;text-transform: uppercase;letter-spacing: 2px;margin-bottom:16px;font-weight: bold;color:white;" id="zobrazeniNazvu">Vyberte soubor</label>
                        <script>
                            /* Funkce pro ziskani nazvu vybraneho souboru */
                            function ziskatNazevSouboru(){
                                /* Po zmene vstupu pro soubory se ziska nazev souboru, diky tomu, ze lze nahravat pouze jeden soubor naraz staci ziskat nazev souboru na nultem indexu */
                                document.getElementById("zobrazeniNazvu").innerHTML = "Vybrán soubor: " + event.target.files.item(0).name;
                            }
                        </script>
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-upload"></i> Nahrát</button>
                    </div>
                </form>
                <!-- Definice formulare pro odstranovani obrazku -->
                <form method="post" action="{{route('deleteEmployeeOldImage')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger btn-block btn-lg"><i class="fa fa-times"></i> Smazat obrázek </button>
                    </div>
                </form>
                <!-- Definice tlacitka pro smazani uctu -->
                <button type="button" data-id="{{Auth::user()->employee_id}}" data-toggle="modal" style="margin-top:15px;" data-target="#DeleteAccountConfirm" class="btn btn-danger btn-block"><i class="fa fa-trash-o"></i> Smazat účet</button>
            </div><br>
        </div>
        <div class="col-9">
            <!-- Definice tabu -->
            <ul class="nav nav-stacked nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#obecneUdaje" style="font-size: 16px;">Obecné údaje</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#zmenaHesla" style="font-size: 16px;">Změna hesla</a>
                </li>
            </ul>
            <br>
            <!-- Definice chybovych hlasek -->
            @if(Session::has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('message') }}
                </div>
            @endif
            @if(Session::has('errorZprava'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('errorZprava') }}
                </div>
            @endif
            <!-- Definice obsahu -->
            <div class="tab-content">
                <!-- Tab Obecne udaje -->
                <div class="tab-pane active" id="obecneUdaje">
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené <span style="color:red;">*</span> jsou povinné.
                    </div>
                    <form class="form" action="{{ route('updateEmployeeProfileData') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="jmeno"><h4>Křestní jméno <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                </div>
                                <input type="text" class="form-control @error('jmeno') is-invalid @enderror" name="jmeno" value="{{ Auth::user()->employee_name }}" id="jmeno" placeholder="Vaše křestní jméno ..." autocomplete="on" autofocus>
                                @error('jmeno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prijmeni"><h4>Příjmení <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                </div>
                                <input type="text" class="form-control @error('prijmeni') is-invalid @enderror" name="prijmeni" value="{{ Auth::user()->employee_surname }}" id="prijmeni" placeholder="Vaše příjmení ..." autocomplete="on">
                                @error('prijmeni')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="narozeniny"><h4>Datum narození</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-birthday-cake"></i></div>
                                </div>
                                <input type="date" class="form-control @error('narozeniny') is-invalid @enderror" name="narozeniny" value="{{ Auth::user()->employee_birthday }}" id="narozeniny" autocomplete="on">
                                @error('narozeniny')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telefon"><h4>Telefonní číslo <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-phone"></i></div>
                                </div>
                                <input type="text" class="form-control @error('telefon') is-invalid @enderror" name="telefon" value="{{ Auth::user()->employee_phone }}" id="telefon" placeholder="Vaše telefonní číslo ve tvaru +420 XXX XXX XXX či XXX XXX XXX ..." autocomplete="on">
                                @error('telefon')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mesto_bydliste"><h4>Město bydliště <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                </div>
                                <input type="text" class="form-control @error('mesto_bydliste') is-invalid @enderror" name="mesto_bydliste" value="{{ Auth::user()->employee_city }}" id="mesto_bydliste" placeholder="Město Vašeho bydliště ..." autocomplete="on">
                                @error('mesto_bydliste')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ulice_bydliste"><h4>Ulice bydliště</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                </div>
                                <input type="text" class="form-control @error('ulice_bydliste') is-invalid @enderror" name="ulice_bydliste" id="ulice_bydliste" value="{{ Auth::user()->employee_street }}" placeholder="Ulice Vašeho bydliště ..." autocomplete="on">
                                @error('ulice_bydliste')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pozice"><h4>Pozice</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-child"></i></div>
                                </div>
                                <input type="text" class="form-control @error('pozice') is-invalid @enderror" name="pozice" id="pozice" value="{{ Auth::user()->employee_position }}" placeholder="Vaše pozice..." readonly>
                                @error('pozice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prihlasovaci_jmeno"><h4>Uživatelské jméno</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                </div>
                                <input type="email" class="form-control @error('prihlasovaci_jmeno') is-invalid @enderror" name="prihlasovaci_jmeno" id="prihlasovaci_jmeno" value="{{ Auth::user()->employee_login }}" placeholder="Vaše emailová adresa ..." readonly>
                                @error('prihlasovaci_jmeno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email"><h4>Emailová adresa</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ Auth::user()->email }}" placeholder="Váš email ..." autocomplete="on" readonly>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-floppy-o"></i> Uložit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="zmenaHesla"> <!-- Tab pro zmenu hesla -->
                    <input type="button" class="btn btn-warning pull-right" value="Generovat heslo" onClick="generator_profile();"> <!-- tlacitko pro generovani hesla -->
                    <form class="form" action="{{ route('updateEmployeeProfilePassword') }}" method="post">
                        @csrf
                        <div class="form-group">
                                <label for="heslo"><h4>Heslo</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('heslo') is-invalid @enderror" name="heslo" id="heslo" placeholder="Zadejte heslo..." title="Zadejte heslo.">
                                    @error('heslo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <span toggle="#heslo" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHeslo"></span>
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
                        </div>
                        <div class="form-group">
                                <label for="heslo_overeni"><h4>Zopakujte heslo</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('heslo_overeni') is-invalid @enderror" name="heslo_overeni" id="heslo_overeni" placeholder="Zopakujte heslo ..." title="Zopakujte heslo.">
                                    @error('heslo_overeni')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <span toggle="#heslo_overeni" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazOvereniHesla"></span>
                                <script>
                                    /* Generator pro zmenu hesla v profilu uctu s roli zamestnance */
                                    function generator_profile() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var heslo = "";
                                        var i = 0;
                                        while(i < 10){
                                            heslo += znaky.charAt(Math.floor(Math.random()*znaky.length));
                                            i++;
                                        }
                                        document.getElementById("heslo").value = heslo;
                                        document.getElementById("heslo_overeni").value = heslo;
                                    }

                                    /* Skryti/odkryti hesla */
                                    $(".zobrazOvereniHesla").click(function() {
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
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-floppy-o"></i> Změnit heslo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Definice modalniho okna pro odstraneni uctu s roli zamestnance -->
<div id="DeleteAccountConfirm" class="modal fade" style="color:white;">
    <div class="modal-dialog">
        <div class="modal-content oknoBarvaPozadi">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání Vašeho účtu</h5>
                <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
            </div>
            <div class="modal-body">
                <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete smazat Váš účet?</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <form method="post" action="{{route('deleteEmployeeProfile')}}">
                    @csrf
                    <button type="submit" name="confirm" style="color:white;" id="confirm" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
