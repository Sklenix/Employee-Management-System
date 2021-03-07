<!DOCTYPE html>
<html>
<head>
    <style>
        [class*="col-"],[class^="col-"] { padding-left: 0; padding-right: 0; }
        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }
        nav { width: 100%; box-shadow: 0px 6px 0px #dedede;}
        html {
            scroll-behavior: smooth;
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
            100% { width: 100%; height: 100%; background: #d9534f; }
        }
        .nav-stacked{
            font-size: 16px;
        }
        .nahratTlacitko label:hover{
            transform: scale(1.03);
        }
        .nahratTlacitko label span{
            font-weight: normal;
        }
    </style>
    <title>Tozondo - Profil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>

</head>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="20" style="background-color:cadetblue">
<nav class="fill navbar sticky-top navbar-light navbar-expand-sm " style="background-color: #F5F5F5" id="myScrollspy">
    <!-- Sekce logo -->
    <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" /> | Profil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdownMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="dropdownMenu">

        <ul class="navbar-nav navbar-collapse justify-content-end">
            <li class="nav-item"><a href="{{route('homeAdmin')}}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" >Dashboard</a> </li>
            <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link p-3" style="font-family: 'Roboto', sans-serif; font-size: 16px;" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Odhlásit se</a> </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</nav>
<br>
<div class="container" style="background-color: white;padding:30px;border-radius: 25px;">
    <div class="row">
        <div class="col-12 text-center" style="margin-bottom: 15px;"><h1>{{ Auth::user()->admin_name}} {{Auth::user()->admin_surname}}</h1>
            <hr></div>
    </div>
    <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-9">
            <ul class="nav nav-stacked nav-pills" id="menuTabu">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#obecneUdaje">Obecné údaje</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#zmenaHesla" >Změna hesla</a>
                </li>
            </ul>
            <script>
                $(document).ready(function () {
                    $('#menuTabu a[href="#{{ old('tab') }}"]').tab('show')
                });
            </script>
            <br>
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

            <div class="tab-content">
                <div class="tab-pane active" id="obecneUdaje">
                    <form class="form" action="{{ route('updateAdminProfileData') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="admin_name"><h4>Jméno</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('admin_name') is-invalid @enderror" name="admin_name" value="{{ Auth::user()->admin_name }}" id="admin_name" placeholder="Vaše jméno ...">
                                    @error('admin_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="admin_surname"><h4>Příjmení</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('admin_surname') is-invalid @enderror" name="admin_surname" value="{{ Auth::user()->admin_surname }}" id="admin_surname" placeholder="Vaše příjmení ...">
                                    @error('admin_surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="admin_email"><h4>Email</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                    </div>
                                    <input type="email" class="form-control @error('admin_email') is-invalid @enderror" name="admin_email" id="admin_email" value="{{ Auth::user()->admin_email }}" placeholder="Váš email ...">
                                    @error('admin_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="admin_login"><h4>Login</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control @error('admin_login') is-invalid @enderror" name="admin_login" value="{{ Auth::user()->admin_login }}" id="admin_login" placeholder="Váš login ...">
                                    @error('admin_login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="btn btn-primary btn-block btn-lg" type="submit" value="Uložit">
                            </div>
                        </div>
                    </form>
                </div><!--/tab-pane-->
                <div class="tab-pane" id="zmenaHesla">
                    <input type="button" class="btn btn-warning pull-right" value="Generovat heslo" onClick="generator();">
                    <form class="form" action="{{ route('updateAdminProfilePassword') }}" method="post">
                        @csrf
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="password"><h4>Heslo</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Zadejte heslo..." title="Zadejte nové heslo.">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <span toggle="#password" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpassword"></span>
                                <script>
                                    $(".showpassword").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="password_verify"><h4>Zopakujte heslo</h4></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="password" class="form-control @error('password_verify') is-invalid @enderror" name="password_verify" id="password_verify" placeholder="Zopakujte heslo ..." title="Znovu zadejte Vaše nové heslo.">
                                    @error('password_verify')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <span toggle="#password_verify" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpasswordverify"></span>
                                <script>
                                    function generator() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var password_tmp = "";
                                        for (var x = 0; x < 10; ++x) { password_tmp += znaky.charAt(Math.floor(Math.random()*znaky.length));}
                                        password.value = password_tmp;
                                        password_verify.value = password_tmp;
                                    }

                                    $(".showpasswordverify").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="btn btn-primary btn-block btn-lg" type="submit" value="Změnit heslo">
                            </div>
                        </div>
                    </form>

                </div><!--/tab-pane-->

            </div><!--/tab-pane-->
        </div><!--/tab-content-->

    </div><!--/col-9-->
</div><!--/row-->
</body>
</html>
