<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- Nazev souboru: register.blade.php -->
    <!-- Tento soubor reprezentuje webovou stranku pro registraci firem do informacniho systemu -->
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
    <title>Tozondo - Registrace</title>
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
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidka">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="rozbalovaciNabidka">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{ route('renderCompanyLogin') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 15px;font-weight:bold;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;">PŘIHLÁSIT SE</a> </li>
        </ul>
    </div>
</nav>
<!-- Definice obsahu -->
<div class="container" style="margin-top:30px;">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" style="margin-bottom: 40px">
                <div class="card-header text-center" style="font-size: 30px;background-color: #0275d8;color:white;font-family: 'Pacifico', cursive;border-radius: 35px !important;">Registrace</div>
                <div class="card-body" style="background-color:#F8F8FF">
                    <div class="row justify-content-center">
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-8">
                            <div class="alert alert-warning" role="alert" style="font-size: 16px;">
                                Položky označené (<span style="color:red;">*</span>) jsou povinné.
                            </div>
                            <!-- Definice formulare -->
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="nazev_firmy"> Název firmy (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-address-book"></i></div>
                                        </div>
                                        <input id="nazev_firmy" placeholder="Zadejte název firmy..." type="text" class="form-control @error('nazev_firmy') is-invalid @enderror" name="nazev_firmy" value="{{ old('nazev_firmy') }}" autocomplete="on" autofocus>
                                        @error('nazev_firmy')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mesto_sidla"> Město sídla (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                        </div>
                                        <input id="mesto_sidla" placeholder="Zadejte město, kde se Vaše firma nachází..." type="text" class="form-control @error('mesto_sidla') is-invalid @enderror" name="mesto_sidla" value="{{ old('mesto_sidla') }}" autocomplete="on">
                                        @error('mesto_sidla')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ulice_sidla"> Ulice sídla </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                        </div>
                                        <input id="ulice_sidla" placeholder="Zadejte ulici, kde se Vaše firma nachází (včetně čísla popisného)..." type="text" class="form-control @error('ulice_sidla') is-invalid @enderror" name="ulice_sidla" value="{{ old('ulice_sidla') }}" autocomplete="on">
                                        @error('ulice_sidla')
                                            <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ico"> IČO</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-info-circle"></i></div>
                                        </div>
                                        <input id="ico" placeholder="Zadejte IČO firmy..." type="text" class="form-control @error('ico') is-invalid @enderror" name="ico" value="{{ old('ico') }}" autocomplete="on">
                                        @error('ico')
                                            <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="krestni_jmeno"> Křestní jméno zástupce (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user"></i></div>
                                        </div>
                                        <input id="krestni_jmeno" placeholder="Zadejte Vaše křestní jméno..." type="text" class="form-control @error('krestni_jmeno') is-invalid @enderror" name="krestni_jmeno" value="{{ old('krestni_jmeno') }}" autocomplete="on">
                                        @error('krestni_jmeno')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="prijmeni">Příjmení zástupce (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user"></i></div>
                                        </div>
                                        <input id="prijmeni" placeholder="Zadejte Vaše příjmení..." type="text" class="form-control @error('prijmeni') is-invalid @enderror" name="prijmeni" value="{{ old('prijmeni') }}" autocomplete="on">
                                        @error('prijmeni')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="emailova_adresa"> Emailová adresa (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                        </div>
                                        <input id="emailova_adresa" placeholder="Zadejte emailovou adresu..." type="email" class="form-control @error('emailova_adresa') is-invalid @enderror" name="emailova_adresa" value="{{ old('emailova_adresa') }}" autocomplete="on">
                                        @error('emailova_adresa')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="telefon">Telefonní číslo (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone"></i></div>
                                        </div>
                                        <input id="telefon" placeholder="Zadejte telefonní číslo ve tvaru +420 XXX XXX XXX či XXX XXX XXX" type="text" class="form-control @error('telefon') is-invalid @enderror" name="telefon" value="{{ old('telefon') }}" autocomplete="on">
                                        @error('telefon')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="prihlasovaci_jmeno">Uživatelské jméno (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user"></i></div>
                                        </div>
                                        <input id="prihlasovaci_jmeno" placeholder="Zadejte uživatelské jméno ..." type="text" value="{{ old('prihlasovaci_jmeno') }}" class="form-control @error('prihlasovaci_jmeno') is-invalid @enderror" name="prihlasovaci_jmeno" autocomplete="on">
                                        @error('prihlasovaci_jmeno')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <input type="button" class="btn btn-warning btn-sm pull-right" value="Generovat heslo" onClick="generator_registration();">
                                <div class="form-group">
                                    <label for="heslo">Heslo (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                        </div>
                                        <input id="heslo" placeholder="Zadejte heslo ..." type="password" class="form-control @error('heslo') is-invalid @enderror" name="heslo">
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
                                    <label for="potvrzeni_hesla">Heslo znovu (<span style="color:red;">*</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                        </div>
                                    <input id="potvrzeni_hesla" placeholder="Znovu zadejte heslo ..." type="password" class="form-control" name="potvrzeni_hesla">
                                </div>
                                <span toggle="#potvrzeni_hesla" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye ukazOvereni"></span>
                                <script>
                                    /* Funkce pro vygenerovani hesla vytvarene firmy */
                                    function generator_registration() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var heslo = "";
                                        var i = 0;
                                        while(i < 10){
                                            heslo += znaky.charAt(Math.floor(Math.random()*znaky.length));
                                            i++;
                                        }
                                        document.getElementById("heslo").value = heslo;
                                        document.getElementById("potvrzeni_hesla").value = heslo;
                                    }

                                    /* Skryti/odkryti hesla */
                                    $(".ukazOvereni").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                    /* Tento jquery kod spolecne s elementem span (ktery byl upraven) byl prevzat dle licencnich podminek, viz nize.
                                      Tento kod slouzi k odhaleni ci skryti hesla na zaklade kliknuti na ikonku.
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
                                <div class="form-group text-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="googleDriveRequest" id="googleDriveRequest">
                                        <label class="custom-control-label" style="font-size: 15px;" for="googleDriveRequest">Chci v rámci svého účtu používat Google Drive.</label>
                                    </div>
                                    <div class="alert alert-warning" role="alert" style="font-size: 14px;margin-top: 10px;">
                                        Pakliže se neregistrujete s emailovou adresou společnosti Google (@gmail.com), tak je doporučeno neaktivovat možnost Google Drive. V případě aktivace sice budete schopni vytvářet složky,
                                        nahrávat soubory a také je mazat (v rámci domovské stránky informačního systému), ale nebudete si moci samotnou Google Drive složku zobrazit.
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                                        Registrovat
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
