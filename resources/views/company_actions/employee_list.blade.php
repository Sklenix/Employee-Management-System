@extends('layouts.company_dashboard')
@section('title') - Zaměstnanci @endsection
@section('content')
    <!-- Nazev souboru: employee_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Seznam zaměstnanců" v ramci uctu s roli firmy -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <div class="col-lg-11 col-md-10 col-sm-10">
            <!-- Usek kodu pro definici hlasek za pomoci Session -->
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
            @if($zprava = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$zprava}}</strong>
                </div>
            @endif
            @if($zprava = Session::get('fail'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$zprava}}</strong>
                </div>
            @endif
            <!-- Tento div slouzi k zobrazeni chyb v ramci AJAXovych pozadavku -->
            <div class="flash-message text-center">
            </div>

            <table class="employees_list_table">
                <thead>
                    <tr>
                        <th width="5%">Fotka</th>
                        <th width="15%">Jméno</th>
                        <th width="15%">Příjmení</th>
                        <th width="20%">Email</th>
                        <th width="15%">Telefon</th>
                        <th width="10%">Pozice</th>
                        <th width="5%">Směna obsazena</th>
                        <th width="15%">Akce <button style="float:right;font-weight: 200;" class="btn btn-dark btn-md" type="button"  data-toggle="modal" data-target="#CreateEmployeeForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro smazani zamestnance -->
    <div id="DeleteEmployeeForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání zaměstnance</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete smazat tohoto zaměstnance?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteEmployee" style="color:white;" id="SubmitDeleteEmployee" class="btn tlacitkoPotvrzeniOkna"  >Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro editaci zamestnance -->
    <div class="modal fade" id="EmployeeEditForm">
        <div class="modal-dialog" style="max-width: 1050px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Profil zaměstnance</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="ArticleEditContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitEditArticleForm">Aktualizovat</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro pridani zamestnance -->
    <div class="modal fade" id="CreateEmployeeForm">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Vytvoření zaměstnance</h4>
                    <button type="button" style="color:white;" class="close" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger chyby_add" role="alert">
                    </div>
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="first_name_add" class="col-md-2 text-left formularLabelsAjaxAdd">Křestní jméno(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                    <input id="first_name_add" placeholder="Zadejte křestní jméno zaměstnance..." type="text" class="form-control" name="first_name_add" autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="surname_add" class="col-md-2 text-left formularLabelsAjaxAdd">Příjmení (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                    <input id="surname_add" placeholder="Zadejte příjmení zaměstnance..." type="text" class="form-control" name="surname_add" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="employee_birthday" class="col-md-2 text-left">Datum narození</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-birthday-cake"></i></div>
                                    </div>
                                    <input type="date" class="form-control" name="employee_birthday" id="employee_birthday">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="email_add" class="col-md-2 text-left formularLabelsAjaxAdd">Emailová adresa(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope "></i></div>
                                    </div>
                                    <input id="email_add" placeholder="Zadejte emailovou adresu zaměstnance..." type="text" class="form-control" name="email_add" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="phone_add" class="col-md-2 text-left formularLabelsAjaxAdd">Telefonní číslo(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-phone "></i></div>
                                    </div>
                                    <input id="phone_add" placeholder="Zadejte telefonní číslo zaměstnance ve tvaru +420 XXX XXX XXX či XXX XXX XXX ..." type="text" class="form-control" name="phone_add" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="position_add" class="col-md-2 text-left formularLabelsAjaxAdd">Pozice (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-child"></i></div>
                                    </div>
                                    <input id="position_add" placeholder="Zadejte pozici zaměstnance..." type="text" class="form-control" name="position_add" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="city_add" class="col-md-2 text-left formularLabelsAjaxAdd">Město bydliště(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                    </div>
                                    <input id="city_add" placeholder="Zadejte město bydliště zaměstnance..." type="text" class="form-control" name="city_add" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="street_add" class="col-md-2 text-left formularLabelsAjaxAdd">Ulice bydliště</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o"></i></div>
                                    </div>
                                    <input id="street_add" placeholder="Zadejte ulici bydliště zaměstnance..." type="text" class="form-control" name="street_add" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="login_add" class="col-md-2 text-left formularLabelsAjaxAdd">Uživatelské jméno(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                    <input id="login_add" placeholder="Zadejte uživatelské jméno zaměstnance..." type="text" class="form-control" name="login_add" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="button" style="margin-bottom: 15px;" class="btn btn-sm btn-warning pull-right" value="Generovat heslo" onClick="generatorEmployeePassword();">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="password_add" class="col-md-2 text-left formularLabelsAjaxAdd">Heslo (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input id="password_add" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control" name="password_add">
                                </div>
                                <span toggle="#password_add" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHeslo"></span>
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
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="password_repeat_add" class="col-md-2 text-left formularLabelsAjaxAdd">Heslo znovu(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input id="password_repeat_add" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control" name="password_repeat_add">
                                </div>
                                <span toggle="#password_repeat_add" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazOvereni"></span>
                                <script>
                                    /* Funkce pro generovani hesel do text inputu */
                                    function generatorEmployeePassword() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var heslo = "";
                                        var i = 0;
                                        while(i < 10){
                                            heslo += znaky.charAt(Math.floor(Math.random()*znaky.length));
                                            i++;
                                        }
                                        document.getElementById("password_add").value = heslo;
                                        document.getElementById("password_repeat_add").value = heslo;
                                    }
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
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="note_add" class="col-md-2 text-left formularLabelsAjaxAdd">Poznámka</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                    </div>
                                    <textarea name="note_add" placeholder="Zadejte poznámku k zaměstnanci [maximálně 180 znaků]..." id="note_add" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" style="font-size: 16px;margin-bottom: 5px;background-color: #1d643b; padding: 5px 10px;border-radius: 10px;">Výběr jazyků, které zaměstnanec ovládá:</div>
                    <div class="form-check text-center" style="color:white;margin-bottom:15px;background-color: #1d643b;border-radius: 10px;padding:5px 10px;">
                        @if (count($jazyky) == 0)
                            <div class="alert alert-danger alert-block text-center">
                                <strong>Nedefinoval jste žádný jazyk.</strong>
                            </div>
                        @endif
                        @foreach($jazyky as $moznost)
                            <input type="checkbox" class="form-check-input jazyky" id="jazyky" name="jazyky[]" value="{{$moznost->language_id}}">
                            <label class="form-check-label" style="font-size: 16px;" for="jazyky"> {{$moznost->language_name}}</label><br>
                        @endforeach
                    </div>
                    @if($company_url != "")
                        <div class="form-check text-center" style="color:white;margin-top:10px;margin-bottom:5px;">
                            <input type="checkbox" class="form-check-input" id="povoleniGoogleDrive" name="povoleniGoogleDrive[]" value="1">
                            <label class="form-check-label" style="font-size: 16px;" for="povoleniGoogleDrive"> Nasdílet zaměstnanci jeho Google Drive složku. </label><br>
                        </div>
                    @endif
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="EmployeeCreationClicked">Vytvořit zaměstnance</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro hodnoceni zamestnance -->
    <div class="modal fade" id="EmployeeRatingForm" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Hodnocení zaměstnance</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div id="EmployeeRatingContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitRateEmployee">Hodnotit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro prirazeni zamestnance ke smenam -->
    <div class="modal fade" id="AssignShiftForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Přiřazení směn k zaměstnancovi</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div id="AssignShiftContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitAssignShift">Přiřadit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro nabidku moznosti dochazky -->
    <div class="modal fade" id="AttendanceOptionsForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Možnosti docházky</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info alert-block text-center">
                        <strong>Vyberte směnu, u které chcete vyplnit příchod, odchod, status nebo poznámku.</strong>
                    </div>
                    <div class="attendancesuccess text-center">
                    </div>
                    <div id="ShowAttendanceOptionsContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro zapsani prichodu v ramci dochazky -->
    <div class="modal fade" id="ShowAttendanceCheckinForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Příchod</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_checkin">
                    </div>
                    <div id="ShowAttendanceCheckinContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitShowAttendanceCheckin">Uložit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro zapsani odchodu v ramci dochazky -->
    <div class="modal fade" id="ShowAttendanceCheckoutForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Odchod</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_checkout">
                    </div>
                    <div id="ShowAttendanceCheckoutContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitShowAttendanceCheckout">Uložit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro zapsani statusu dochazky -->
    <div class="modal fade" id="ShowAttendanceAbsenceForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Status</h5>
                </div>
                <div class="modal-body">
                    <div id="ShowAttendanceAbsenceContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitShowAttendanceAbsence">Uložit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro zapsani poznamky k dochazce -->
    <div class="modal fade" id="ShowAttendanceNoteForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Poznámka</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_poznamka">
                    </div>
                    <div id="ShowAttendanceNoteContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitShowAttendanceNote">Uložit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            /* K inspiraci prace s datovymi tabulkami slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020,
               pro inspiraci prace s modalnimi okny (udalosti) slouzil clanek https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event, ktery napsal David Meador v roce 2018 */

            /* Usek kodu starajici se o schovani chybovych hlaseni pri zavreni modalniho okna, inspirace z: https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event */
            $('#CreateEmployeeForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })

            $('#EmployeeEditForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#AttendanceOptionsForm').on('hidden.bs.modal', function () {
                $('.attendancesuccess').hide();
            })

            $('#ShowAttendanceCheckinForm').on('hidden.bs.modal', function () {
                $('.chyby_checkin').hide();
            })

            $('#ShowAttendanceCheckoutForm').on('hidden.bs.modal', function () {
                $('.chyby_checkout').hide();
            })

            /* Usek kodu pro schovani chybovych hlaseni pri nacteni webove stranky */
            $('.chyby').hide();
            $('.chyby_checkin').hide();
            $('.chyby_checkout').hide();
            $('.chyby_add').hide();

            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
             Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
             Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
             K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.
            */
            $('.employees_list_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 12,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyl nalezen žádný zaměstnanec."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte vytvořené žádné zaměstnance.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[2, "asc"]],
                ajax: "{{ route('employees.list') }}", // nastaveni a odeslani AJAX pozadavku viz https://datatables.net/reference/option/ajax
                columns: [ // definice dat viz https://datatables.net/reference/option/data
                    { data: 'employee_picture', name: 'employee_picture',  // vyrenderovani profiloveho obrazku zamestnance
                        render: function(odpoved){ // viz https://datatables.net/reference/option/columns.render
                            if(odpoved === null){ return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60'/>";} // Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4#
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + odpoved + " width='60' height='50' style='max-width:100%;height:auto;'/>";
                        }, orderable: false},
                    { data: 'employee_name', name: 'employee_name',sClass:'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    { data: 'employee_surname', name: 'employee_surname',sClass:'text-center'},
                    { data: 'email', name: 'email',sClass:'text-center'},
                    { data: 'employee_phone', name: 'employee_phone'},
                    { data: 'employee_position', name: 'employee_position',sClass:'text-center'},
                    { data: 'shift_taken', name: 'shift_taken',sClass:'text-center'},
                    { data: 'action', name: 'action', orderable: false,searchable: false,sClass:'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit" dojde k pridani zamestnance do databaze */
            $('#EmployeeCreationClicked').click(function() {
                $.ajax({
                    url: "{{ route('employeesactions.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        jmeno: $('#first_name_add').val(),
                        prijmeni: $('#surname_add').val(),
                        narozeniny: $('#employee_birthday').val(),
                        telefon: $('#phone_add').val(),
                        email: $('#email_add').val(),
                        poznamka: $('#note_add').val(),
                        pozice: $('#position_add').val(),
                        mesto_bydliste: $('#city_add').val(),
                        ulice_bydliste: $('#street_add').val(),
                        prihlasovaci_jmeno: $('#login_add').val(),
                        heslo: $('#password_add').val(),
                        heslo_overeni: $('#password_repeat_add').val(),
                        jazyky: $('.jazyky:checked').serialize(),
                        googleDriveRequest: $('#povoleniGoogleDrive').is(':checked')
                    },
                    beforeSend:function(){ $('#EmployeeCreationClicked').text('Vytváření...'); }, // zmena textu pred odeslanim
                    success: function(odpoved) {
                        if(odpoved.success) {
                            /* Usek kodu pro vyresetovani hodnot formularovych prvku */
                            $('.chyby_add').hide();
                            $('#first_name_add').val('');
                            $('#surname_add').val('');
                            $('#phone_add').val('');
                            $('#email_add').val('');
                            $('#note_add').val('');
                            $('#position_add').val('');
                            $('#city_add').val('');
                            $('#street_add').val('');
                            $('#login_add').val('');
                            $('#employee_birthday').val('');
                            $('#password_add').val('');
                            $('#password_repeat_add').val('');
                            $(":checkbox").attr("checked", false);
                            /* Refresh datove tabulky */
                            $('.employees_list_table').DataTable().ajax.reload();
                            var successAdd = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.flash-message').html(successAdd); // nastaveni hlasky o uspechu
                            $('#EmployeeCreationClicked').text('Vytvořit');
                            $('#CreateEmployeeForm').modal('hide'); // schovani modalniho okna
                        } else {
                            $('#EmployeeCreationClicked').text('Vytvořit zaměstnance');
                            $('.chyby_add').html('');
                            /* Iterace skrze chyby a postupne pridavani jich do elementu chyby_add */
                            odpoved.fail.forEach(function (polozka){
                                $('.chyby_add').append('<strong>'+polozka+'</strong><br>');
                            });
                            /* Zobrazeni chyb */
                            $('.chyby_add').show();
                        }
                    }
                });
            });

            /* Modalni okno slouzici pro smazani zamestnance (po stisknuti tlacitka "Smazat") */
            var zamestnanec_id_delete;
            $('body').on('click', '#obtainDeleteEmployee', function(){
                zamestnanec_id_delete = $(this).data('id');
                $('#DeleteEmployeeForm').modal('show');
            });

            /* Realizace smazani zamestnance z databaze za pomoci tlacitka "Smazat" */
            $('#SubmitDeleteEmployee').click(function() {
                $.ajax({
                    url: "/company/employeesactions/"+zamestnanec_id_delete,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    beforeSend:function(){$('#SubmitDeleteEmployee').text('Odstraňování...');}, // zmena textu pri kliknuti na tlacitko
                    success:function(odpoved){
                        var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                        $('.flash-message').html(successHtml); // nastaveni hlasky o uspechu
                        $('.employees_list_table').DataTable().ajax.reload(); // refresh datove tabulky
                        $('#SubmitDeleteEmployee').text('Ano');
                        $("#DeleteEmployeeForm").modal('hide'); // schovani modalniho okna
                    }
                })
            });

            /* Zobrazeni profilu zamestnance po stisknuti tlacitka "Zobrazit" */
            var zamestnanec_id_profil;
            $('body').on('click', '#obtainEditEmployee',function() {
                zamestnanec_id_profil = $(this).data('id');
                $.ajax({
                    url: "/company/employeesactions/"+zamestnanec_id_profil+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ArticleEditContent').html(odpoved.out); // vlozeni obsahu do modalniho okna
                        $('#EmployeeEditForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni hodnot v profilu zamestnance do databaze */
            $('#SubmitEditArticleForm').click(function() {
                $.ajax({
                    url: "/company/employeesactions/"+zamestnanec_id_profil,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {
                        jmeno: $('#edit_first_name').val(),
                        prijmeni: $('#edit_surname').val(),
                        telefon: $('#edit_phone_number').val(),
                        email: $('#edit_email').val(),
                        narozeniny: $('#employee_birthday_edit').val(),
                        pozice: $('#edit_position').val(),
                        mesto_bydliste: $('#edit_city').val(),
                        ulice_bydliste: $('#edit_street').val(),
                        prihlasovaci_jmeno: $('#edit_login').val(),
                        poznamka: $('#edit_note').val(),
                        heslo: $('#password_edit').val(),
                        heslo_overeni: $('#password_edit_confirm').val(),
                        jazyky_edit: $('.jazyky_edit:checked').serialize(),
                    },
                    beforeSend:function(){$('#SubmitEditArticleForm').text('Aktualizace...');}, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.employees_list_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successUpdate;
                            if(odpoved.success != "0"){
                                successUpdate = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditArticleForm').text('Aktualizovat'); // zmena textu
                            $('.flash-message').html(successUpdate); // nastaveni hlasky o uspechu
                            $("#EmployeeEditForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitEditArticleForm').text('Aktualizovat');
                            $('.chyby').html('');
                            /* Iterace skrze chyby a postupne pridavani jich do elementu chyby_add */
                            odpoved.errors.forEach(function (polozka){
                                $('.chyby').append('<strong>'+polozka+'</strong><br>');
                            });
                            /* Zobrazeni chyb */
                            $('.chyby').show();
                        }
                    }
                });
            });

            /* Nahled do hodnoceni zamestnance */
            var id_hodnoceni_zamestnanec;
            $('body').on('click', '#obtainEmployeeRate', function() {
                id_hodnoceni_zamestnanec = $(this).data('id');
                $.ajax({
                    url: "/company/employees/rate/" + id_hodnoceni_zamestnanec,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#EmployeeRatingContent').html(odpoved.out); /* Vlozeni obsahu do modalniho okna */
                        $('#EmployeeRatingForm').show(); /* Zobrazeni modalniho okna */

                        /* Usek kodu slouzici pro snimani a zobrazovani aktualnich hodnot posuvniku */
                        /* Pro posuvnik spolehlivosti */
                        $("#viewRealibility").html($("#realibitySlider").val());
                        /* Pro posuvnik dochvilnosti */
                        $("#viewAbsence").html($("#absenceSlider").val());
                        /* Pro posuvnik pracovitosti */
                        $("#viewWork").html($("#workSlider").val());

                        /* Zobrazovani aktualnich hodnot posuvniku */
                        $("#realibitySlider").on('input', function() {$("#viewRealibility").html($("#realibitySlider").val());});
                        $("#absenceSlider").on('input', function() {$("#viewAbsence").html($("#absenceSlider").val());});
                        $("#workSlider").on('input', function() {$("#viewWork").html($("#workSlider").val());});
                    }
                });
            });

            /* Ulozeni hodnot posuvniku do databaze */
            $('#SubmitRateEmployee').click(function() {
                $.ajax({
                    url: "/company/employees/rate/edit/" + id_hodnoceni_zamestnanec,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_absence: $("#absenceSlider").val(),
                        employee_reliability: $("#realibitySlider").val(),
                        employee_workindex:$("#workSlider").val(),
                    },
                    beforeSend:function(){$('#SubmitRateEmployee').text('Aktualizace...');},
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.employees_list_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successRating = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitRateEmployee').text('Hodnotit'); // nastaveni textu tlacitka
                            $('.flash-message').html(successRating); // vlozeni hlasky o uspechu
                            $("#EmployeeRatingForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitRateEmployee').text('Hodnotit'); // zmena textu
                        }
                    }
                });
            });

            /* Zobrazeni modalniho okna pro prirazeni smen */
            var zamestnanec_prirazeni_id;
            $('body').on('click', '#obtainEmployeeAssign',function() {
                /* ziskani ID zamestnance */
                zamestnanec_prirazeni_id = $(this).data('id');
                $.ajax({
                    url: "/company/employees/assign/" + zamestnanec_prirazeni_id,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#AssignShiftContent').html(odpoved.out); // vlozeni obsahu do modalniho okna
                        $('#AssignShiftForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni prirazenych smen k zamestnanci do databaze */
            $('#SubmitAssignShift').click(function(){
                $.ajax({
                    url: "/company/employees/assign/edit/" + zamestnanec_prirazeni_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {
                        shifts_ids: $('.shift_shift_assign_id:checked').serialize()
                    },
                    beforeSend:function(){$('#SubmitAssignShift').text('Přiřazování...');}, // po zmacknuti tlacitka zmena textu
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.employees_list_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successAssign = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitAssignShift').text('Přiřadit');
                            $('.flash-message').html(successAssign); // vlozeni obsahu do flash message
                            $("#AssignShiftForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitAssignShift').text('Přiřadit');
                        }
                    }
                });
            });

            /* Zobrazení moznosti dochazky v modalnim okne */
            var id_zamestnance_dochazka;
            $('body').on('click', '#obtainAttendanceOptions', function() {
                /* Ziskani ID zamestnance */
                id_zamestnance_dochazka = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/"+id_zamestnance_dochazka,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ShowAttendanceOptionsContent').html(odpoved.out); // zapsani obsahu do modalniho okna
                        $('#AttendanceOptionsForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Prichod v moznostech dochazky (zobrazeni) */
            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#obtainCheckInShift', function() {
                /* Ziskani ID smeny a ID zamestnance */
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/checkin/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ShowAttendanceCheckinContent').html(odpoved.out); // zapsani obsahu do modalniho okna
                        $('#ShowAttendanceCheckinForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni zapsani prichodu do databaze */
            $('#SubmitShowAttendanceCheckin').click(function() {
                $.ajax({
                    url: "/employee/attendance/options/checkin/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_in_company: $('#attendance_create_checkin').val(),
                    },
                    beforeSend:function(){$('#SubmitShowAttendanceCheckin').text('Aktualizace...');}, // zmena textu pri zmacknuti tlacitka
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.employees_list_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu a jeji zobrazeni
                            $('.attendancesuccess').show();
                            $('#SubmitShowAttendanceCheckin').text('Uložit');
                            $("#ShowAttendanceCheckinForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitShowAttendanceCheckin').text('Uložit');
                            /* Definice chybove hlasky */
                            var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>';
                            $('.chyby_checkin').html(failHtml); // vlozeni chybove hlasky do chyby_checkin
                            /* Zobrazeni chybove hlasky */
                            $('.chyby_checkin').show();
                        }
                    }
                });
            });

            /* Odchod v moznostech dochazky (zobrazeni) */
            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#obtainCheckOutShift', function() {
                /* Ziskani ID smeny a ID zamestnance */
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/checkout/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ShowAttendanceCheckoutContent').html(odpoved.out); // vyplneni modalniho okna obsahem
                        $('#ShowAttendanceCheckoutForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni zapsani odchodu do databaze */
            $('#SubmitShowAttendanceCheckout').click(function() {
                $.ajax({
                    url: "/employee/attendance/options/checkout/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_out_company: $('#attendance_create_checkout').val(),
                    },
                    beforeSend:function(){ $('#SubmitShowAttendanceCheckout').text('Aktualizace...'); }, // zmena textu pri kliknuti na tlacitko "Uložit"
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.shift_list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitShowAttendanceCheckout').text('Uložit');
                            $("#ShowAttendanceCheckoutForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitShowAttendanceCheckout').text('Uložit');
                            /* Nastaveni a zobrazeni chybove hlasky */
                            var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.fail + '</strong></div>';
                            $('.chyby_checkout').html(failHtml);
                            $('.chyby_checkout').show();
                        }
                    }
                });
            });

            /* Absence v moznostech dochazky (zobrazeni) */
            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#obtainAbsenceReasonAttendance',function() {
                /* Ziskani ID smeny a ID zamestnance */
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/absence/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ShowAttendanceAbsenceContent').html(odpoved.out);
                        $('#ShowAttendanceAbsenceForm').show();
                    }
                });
            });

            /* Ulozeni vybraneho statusu do databaze */
            $('#SubmitShowAttendanceAbsence').click(function() {
                $.ajax({
                    url: "/employee/attendance/options/absence/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_absence_reason_id: $('#duvody_absence').val(),
                    },
                    beforeSend:function(){ $('#SubmitShowAttendanceAbsence').text('Aktualizace...'); }, // zmena textu pri kliknuti na "Uložit"
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.employees_list_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky o uspechu
                            $('#SubmitShowAttendanceAbsence').text('Uložit');
                            $("#ShowAttendanceAbsenceForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitShowAttendanceAbsence').text('Uložit');
                        }
                    }
                });
            });

            /* Poznámka v možnostech docházky(zobrazení) */
            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#obtainNoteAttendance', function() {
                /* Vymazani a schovani chybove hlasky */
                $('.chyby_poznamka').html('');
                $('.chyby_poznamka').hide();
                /* Ziskani ID smeny a ID zamestnance */
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/note/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ShowAttendanceNoteContent').html(odpoved.out); // nastaveni obsahu modalniho okna
                        $('#ShowAttendanceNoteForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni poznamky k dochazce do databaze */
            $('#SubmitShowAttendanceNote').click(function() {
                $.ajax({
                    url: "/employee/attendance/options/note/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        poznamka: $('#attendance_note').val(),
                    },
                    beforeSend:function(){$('#SubmitShowAttendanceNote').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko "Uložit"
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.employees_list_table').DataTable().ajax.reload(); // aktualizace datove tabulky
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitShowAttendanceNote').text('Uložit'); // zmena textu u tlacitka
                            $("#ShowAttendanceNoteForm").modal('hide'); // schovani modalniho okna
                        } else { // pokud poznamka presahla 180 znaku
                            $('#SubmitShowAttendanceNote').text('Uložit');
                            var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</div>';
                            $('.chyby_poznamka').html(failHtml);
                            $('.chyby_poznamka').show();
                        }
                    }
                });
            });

        });
    </script>
@endsection
