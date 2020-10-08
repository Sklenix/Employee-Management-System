<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>

        body { font-family: 'Roboto', sans-serif; }
        .navbar-brand{ font-family: 'Pacifico', cursive; }

        .list-group a { text-decoration: none; font-weight: 100; text-transform: uppercase; }
        .fill .list-group a { position: relative; }

        .fill .list-group a:after { position: absolute; bottom: 0; left: 0; right: 0; margin: auto;
            width: 0%; content: '.'; color: transparent; height: 1px; }

        .fill .list-group a:hover { z-index: 1; }

        .fill .list-group a:hover:after { z-index: -10; animation: fill 1s forwards;
            -webkit-animation: fill 1s forwards; -moz-animation: fill 1s forwards; opacity: 1; }

        @-webkit-keyframes fill {
            0% { width: 0%; height: 1px; }
            50% { width: 100%; height: 1px; }
            100% { width: 100%; height: 100%; background: #6495ED; }
        }

        .navbar-brand{ font-family: 'Pacifico', cursive; }
        #wrapper {
            overflow-x: hidden;
            background-color: #F5F5F5;
        }
        hr.caraPodNazvem {
            border: 1px solid white;
            margin-bottom: 0;
        }
        .ramecek{
            border-style: solid;
            margin-bottom:15px;
            margin-right: 15px;
            border-width: thin;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            -webkit-transition: margin .25s ease-out;
            -moz-transition: margin .25s ease-out;
            -o-transition: margin .25s ease-out;
            transition: margin .25s ease-out;

        }

        #sidebar-wrapper .sidebar-heading {
            padding: 15px 10px;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 19rem;
            font-size: 16px;

        }

        #page-content-wrapper {
            min-width: 100vw;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -19rem;
            }
        }
        .list-group .active { background:#d9534f;}

        @media (max-width: 767px) {
            #sidebar-wrapper {
                margin-left: 15px;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -19rem;
            }
        }


    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tozondo - Dashboard</title>

    <!-- Scripts -->
    <script src="{{ asset('js/dashboard.js') }}" defer></script>

    <!-- Fonts -->

    <link rel="icon" href="{{ asset('images/favicon16x16.png') }}" type="image/png" sizes="16x16"/>
    <link rel="icon" href="{{ asset('images/favicon32x32.png') }}" type="image/png" sizes="32x32"/>
    <link rel="icon" href="{{ asset('images/favicon96x96.png') }}" type="image/png" sizes="96x96"/>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="fill" id="sidebar-wrapper" style="background:rgba(0,0,0,0.85);">
        <div class="sidebar-heading">  <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 25px;margin-left: 20px;color:rgba(255, 255, 255, 0.95);"> <img src="{{ URL::asset('images/logo.png') }}" height="35" width="40" /> | Tozondo</a>
            <hr class="caraPodNazvem">
        </div>
        <div class="list-group list-group-flush">
            <a href="#" class="border-bottom active" style="padding-left:60px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 20px;padding-top: 20px;font-size:17px;"><i class="fa fa-cube" aria-hidden="true"></i> Dashboard</a>
            <a href="#" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-user-o" aria-hidden="true"></i> Zobrazit směny</a>
            <a href="#" class="border-bottom" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-list-ol" aria-hidden="true"></i> Zobrazit aktuální směny</a>
            <a href="#" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Přidat dovolenou</a>
            <a href="#" class="border-bottom" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-list-alt" aria-hidden="true"></i> Nahlásit</a>
            <a href="#" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-server" aria-hidden="true"></i> Důvod nepřítomnosti</a>
            <a href="#" class="border-bottom" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-upload" aria-hidden="true"></i> Google Drive</a>
            <a href="#" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-rocket" aria-hidden="true"></i> Generátor souborů</a>
            <a href="#" class="border-bottom" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;"><i class="fa fa-pie-chart" aria-hidden="true"></i> Statistiky</a>
            <a href="#" style="padding-left:30px;color:rgba(255, 255, 255, 0.95);text-decoration: none;padding-bottom: 16px;padding-top: 16px;font-size: 16px;" ><i class="fa fa-cog" aria-hidden="true"></i> Nastavení</a>

        </div>

    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" >

        <nav class="navbar navbar-expand-lg navbar-dark border-bottom" style="background:rgba(0,0,0,0.85);">
            <button class="btn btn-danger" id="menu-toggle">Schovat menu</button>

            <button class="navbar-toggler" style="color:rgba(255, 255, 255, 0.95);" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0" >
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" style="color:rgba(255, 255, 255, 0.95);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->employee_name }} {{ Auth::user()->employee_surname }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Profil zaměstnance</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Odhlásit se
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid" style="padding: 0;margin:0;">
            @yield('content')
        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Bootstrap core JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>

</body>
</html>
