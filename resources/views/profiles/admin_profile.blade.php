<!DOCTYPE html>
<html>
<head>
    <!-- Nazev souboru: admin_profile.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje profil admina. -->
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
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
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
<body style="background-color:cadetblue;">
<!-- Definice menu -->
<nav class="navbar navbar-light navbar-expand-sm efektMenu" style="background-color: #F5F5F5;box-shadow: 0px 5px 1px #DCDCDC;">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" alt="Logo"/> | Profil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rozbalovaciNabidka">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="rozbalovaciNabidka">
        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{route('homeAdmin')}}" class="nav-link" style="font-family: 'Roboto', sans-serif;font-weight: bold;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;font-size: 16px;">DASHBOARD</a> </li>
            <!-- realizace odhlasovani -->
            <li class="nav-item"><a href="{{ route('adminLogOut') }}" class="nav-link" style="font-family: 'Roboto', sans-serif; font-size: 16px;font-weight: bold;padding-left:15px;padding-right:15px;padding-bottom:15px;padding-top:15px;" onclick="event.preventDefault();document.getElementById('logoutform').submit();">ODHLÁSIT SE</a> </li>
            <form id="logoutform" action="{{ route('adminLogOut') }}" method="POST">@csrf </form>
        </ul>
    </div>
</nav>
<br>
<!-- Definice obsahu -->
<div class="container" style="background-color: white;padding:30px;border-radius: 25px;">
    <div class="row">
        <div class="col-12 text-center" style="margin-bottom: 15px;"><h1>{{ Auth::user()->jmeno}} {{Auth::user()->prijmeni}}</h1><hr></div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="button" data-id="{{Auth::user()->admin_id}}" data-toggle="modal" style="margin-top:15px;" data-target="#AdminDeleteAccountForm" class="pull-right btn btn-danger"><i class="fa fa-trash-o"></i> Smazat účet</button>
            <ul class="nav nav-stacked nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#obecneUdaje" style="font-size: 16px;">Obecné údaje</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#zmenaHesla" style="font-size: 16px;">Změna hesla</a>
                </li>
            </ul>
            <br>
            <!-- Definice zprav -->
            @if(Session::has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('success') }}
                </div>
            @endif
            @if(Session::has('errorZprava'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('errorZprava') }}
                </div>
            @endif

            <div class="tab-content">
                <div class="tab-pane active" id="obecneUdaje"> <!-- tab pro obecne udaje -->
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené <span style="color:red;">*</span> jsou povinné.
                    </div>
                    <form class="form" action="{{ route('updateAdminProfileData') }}" method="POST">
                        @csrf
                        <div class="form-group">
                                <label for="jmeno"><h4>Křestní jméno <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('jmeno') is-invalid @enderror" name="jmeno" value="{{ Auth::user()->admin_name }}" id="jmeno" placeholder="Vaše křestní jméno ..." autocomplete="on" autofocus>
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
                                    <input type="text" class="form-control @error('prijmeni') is-invalid @enderror" name="prijmeni" value="{{ Auth::user()->admin_surname }}" id="prijmeni" placeholder="Vaše příjmení ..." autocomplete="on">
                                    @error('prijmeni')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                        </div>
                        <div class="form-group">
                                <label for="email"><h4>Emailová adresa <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                    </div>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ Auth::user()->admin_email }}" placeholder="Vaše emailová adresa ..." autocomplete="on">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                        </div>
                        <div class="form-group">
                                <label for="prihlasovaci_jmeno"><h4>Uživatelské jméno <span class="text-danger" style="font-size: 18px;">*</span></h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('prihlasovaci_jmeno') is-invalid @enderror" name="prihlasovaci_jmeno" value="{{ Auth::user()->admin_login }}" id="prihlasovaci_jmeno" placeholder="Vaše uživatelské jméno ..." autocomplete="on">
                                    @error('prihlasovaci_jmeno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-floppy-o"></i> Uložit</button>
                        </div>
                    </form>
                </div> <!-- tab pro zmenu hesla -->
                <div class="tab-pane" id="zmenaHesla">
                    <input type="button" class="btn btn-warning pull-right" value="Generovat heslo" onClick="generator_profile();">
                    <form class="form" action="{{ route('updateAdminProfilePassword') }}" method="POST">
                        @csrf
                        <div class="form-group">
                                <label for="heslo"><h4>Heslo</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('heslo') is-invalid @enderror" name="heslo" id="heslo" placeholder="Zadejte heslo..." title="Zadejte nové heslo.">
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
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('heslo_overeni') is-invalid @enderror" name="heslo_overeni" id="heslo_overeni" placeholder="Zopakujte heslo ..." title="Znovu zadejte Vaše nové heslo.">
                                    @error('heslo_overeni')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <span toggle="#heslo_overeni" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazOvereniHesla"></span>
                                <script>
                                    /* Generator pro zmenu hesla v profilu uctu s roli admina */
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
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-floppy-o"></i> Změnit heslo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Definice modalniho okna pro odstraneni uctu s roli admina -->
<div id="AdminDeleteAccountForm" class="modal fade" style="color:white;">
    <div class="modal-dialog">
        <div class="modal-content oknoBarvaPozadi">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání Vašeho účtu</h5>
                <button type="button" class="close" style="color:white;" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete smazat Váš účet?</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <form method="POST" action="{{route('deleteAdminProfile')}}">
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
