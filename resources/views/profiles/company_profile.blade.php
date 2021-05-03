<!DOCTYPE html>
<html>
<head>
    <!-- Nazev souboru: company_profile.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje profil firmy. -->
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
    <!-- import fontu -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- import kaskadovych stylu, javascriptu a jQuery -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;"><img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" alt="Logo"/> | Profil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidka">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="rozbalovaciNabidka">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{route('home')}}" class="nav-link" style="font-family: 'Roboto', sans-serif;font-weight: bold;font-size: 16px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;">DASHBOARD</a> </li>
            <li class="nav-item"><a href="{{ route('companyLogout') }}" class="nav-link" style="font-family: 'Roboto', sans-serif;font-weight: bold;font-size: 16px;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" onclick="event.preventDefault();document.getElementById('logout-form').submit();">ODHLÁSIT SE</a> </li>
            <form id="logout-form" action="{{ route('companyLogout') }}" method="POST"> @csrf </form>
        </ul>
    </div>
</nav>
<br>

<!-- Definice obsahu -->
<div class="container" style="background-color: white;padding:30px;border-radius: 25px;">
    <div class="row">
        <div class="col-12 text-center" style="margin-bottom: 15px;"><h1> {{ Auth::user()->company_name }}</h1><hr></div> <!-- Nadpis -->
    </div>
    <div class="row">
        <div class="col-3">
            <div class="text-center">
                 @if($profilovka == NULL) <!-- sekce pro zobrazeni profiloveho obrazku, pokud firma zadny obrazek nenahrala, je ji zobrazen defaultni profilovy obrazek -->
                        <!-- Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4# -->
                        <img src="{{ URL::asset('images/default_profile.png') }}" class="profilovka img-thumbnail" alt="Profilová fotka"/>
                 @else
                        <img src =" {{ asset('/storage/company_images/'.Auth::user()->company_picture) }}" width="250" style="margin-right: 5px;" alt="Profilová fotka"/>
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
                 <form method="post" style="margin-top: 15px;" action="{{route('uploadImage')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group nahratTlacitko">
                    <input type="file" onchange="ziskatNazevSouboru()" name="obrazek" required id="souborProNahrani" hidden />
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
                 <form method="post" style="margin-top: 15px;" action="{{route('deleteOldImage')}}" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group nahratTlacitko">
                         <button type="submit" class="btn btn-danger btn-block btn-lg"><i class="fa fa-times"></i> Smazat obrázek</button>
                     </div>
                 </form>
                 <!-- Definice tlacitka pro smazani uctu -->
                 <button type="button" data-id="{{Auth::user()->company_id}}" data-toggle="modal" style="margin-top:15px;" data-target="#DeleteAccountConfirm" class="btn btn-danger btn-block"><i class="fa fa-trash-o"></i> Smazat účet</button>
            </div><br>
        </div>
        <div class="col-9">
            <ul class="nav nav-stacked nav-pills">
                <!-- Definice tabu -->
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
                    <form class="form" action="{{ route('updateProfileData') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="nazev_spolecnosti"><h4>Název firmy <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-address-book"></i></div>
                                </div>
                                <input type="text" class="form-control @error('nazev_spolecnosti') is-invalid @enderror" name="nazev_spolecnosti" value="{{ Auth::user()->company_name }}" id="nazev_spolecnosti" placeholder="Jméno společnosti...">
                                @error('nazev_spolecnosti')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ico"><h4>IČO</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-info-circle"></i></div>
                                </div>
                                <input type="text" class="form-control @error('ico') is-invalid @enderror" name="ico" value="{{ Auth::user()->company_ico }}" id="ico" placeholder="IČO společnosti...">
                                @error('ico')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mesto_sidla"><h4>Město sídla <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                </div>
                                <input type="text" class="form-control @error('mesto_sidla') is-invalid @enderror" name="mesto_sidla" value="{{ Auth::user()->company_city }}" id="mesto_sidla" placeholder="Sídlo společnosti...">
                                @error('mesto_sidla')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ulice_sidla"><h4>Ulice sídla</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                </div>
                                <input type="text" class="form-control @error('ulice_sidla') is-invalid @enderror" name="ulice_sidla" value="{{ Auth::user()->company_street }}" id="ulice_sidla" placeholder="Ulice sídla společnosti...">
                                @error('ulice_sidla')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="krestni_jmeno"><h4>Křestní jméno zástupce <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                </div>
                                <input type="text" class="form-control @error('krestni_jmeno') is-invalid @enderror" name="krestni_jmeno" id="krestni_jmeno" value="{{ Auth::user()->company_user_name }}" placeholder="Křestní jméno zástupce..." >
                                @error('krestni_jmeno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prijmeni"><h4>Příjmení zástupce <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                </div>
                                <input type="text" class="form-control @error('prijmeni') is-invalid @enderror" name="prijmeni" id="prijmeni" value="{{ Auth::user()->company_user_surname }}" placeholder="Příjmení zástupce...">
                                @error('prijmeni')
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
                                <input type="text" class="form-control @error('telefon') is-invalid @enderror" name="telefon" id="telefon" placeholder="Váš telefon..."  value="{{ Auth::user()->company_phone }}">
                                @error('telefon')
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
                                <input type="text" class="form-control @error('prihlasovaci_jmeno') is-invalid @enderror"  name="prihlasovaci_jmeno" id="prihlasovaci_jmeno" placeholder="Váš login..." value="{{ Auth::user()->company_login }}" readonly>
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
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ Auth::user()->email }}" placeholder="Email společnosti ..." readonly>
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
                    <input type="button" class="btn btn-warning pull-right" value="Generovat heslo" onClick="generator_company_password();"> <!-- tlacitko pro generovani hesla -->
                    <form class="form" action="{{ route('updateProfilePassword') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="heslo"><h4>Heslo</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                </div>
                                <input type="password" class="form-control @error('heslo') is-invalid @enderror" name="heslo" id="heslo" placeholder="Zadejte heslo..." title="Zadejte heslo" >
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
                            <label for="overeni_heslo"><h4>Zopakujte heslo</h4></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                </div>
                                <input type="password" class="form-control @error('overeni_heslo') is-invalid @enderror" name="overeni_heslo" id="overeni_heslo" placeholder="Zopakujte heslo ..." title="Znovu zadejte Vaše nové heslo.">
                                @error('overeni_heslo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <span toggle="#overeni_heslo" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazOvereni"></span>
                            <script>
                                /* Skryti/odkryti hesla */
                                $(".zobrazOvereni").click(function() {
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

                                /* Generator pro zmenu hesla v profilu uctu s roli firmy */
                                function generator_company_password() {
                                    var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                    var heslo = "";
                                    var i = 0;
                                    while(i < 10){
                                        heslo += znaky.charAt(Math.floor(Math.random()*znaky.length));
                                        i++;
                                    }
                                    document.getElementById("heslo").value = heslo;
                                    document.getElementById("overeni_heslo").value = heslo;
                                }
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

<!-- Definice modalniho okna pro odstraneni uctu s roli firmy -->
<div id="DeleteAccountConfirm" class="modal fade" style="color:white;">
    <div class="modal-dialog">
        <div class="modal-content oknoBarvaPozadi">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání Vašeho účtu</h5>
                <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete smazat Váš účet?</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <form method="post" action="{{route('deleteCompanyProfile')}}">
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
