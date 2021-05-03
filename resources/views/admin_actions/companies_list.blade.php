@extends('layouts.admin_dashboard')
@section('title') - Firmy @endsection
@section('content')
    <!-- Nazev souboru: companies_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Seznam firem" v ramci uctu s roli admina -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br><br>
        <!-- Usek kodu pro definici chybovych hlasek za pomoci Session -->
        <div class="col-lg-11 col-md-10 col-sm-10" style="padding-bottom: 800px;">
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
            <!-- Usek kodu pro definici tabulky -->
            <div class="zprava text-center">
            </div>
            <table class="table_companies" width="100%">
                <thead>
                    <tr>
                        <th width="6%">Fotka</th>
                        <th width="12.5%">Název</th>
                        <th width="12.5%">Jméno</th>
                        <th width="10%">Příjmení</th>
                        <th width="15%">Email</th>
                        <th width="14%">Telefon</th>
                        <th width="18%">Adresa</th>
                        <th width="12%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#CreateCompanyModal"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno pro pridani firmy -->
    <div class="modal fade" id="CreateCompanyModal" style="color:white;">
        <div class="modal-dialog modal-lg" style="max-width: 850px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit novou firmu</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger chyby_add" role="alert">
                    </div>
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_name" class="col-md-2 text-center" style="font-size: 15px;"> Společnost (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-address-book " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_name" placeholder="Zadejte název společnosti..." type="text" class="form-control" name="company_name" autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_city" class="col-md-2 text-center" style="font-size: 15px;"> Město (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_city" placeholder="Zadejte město, kde se firma nachází..." type="text" class="form-control" name="company_city" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_street" class="col-md-2 text-center" style="font-size: 15px;"> Ulice </label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_street" placeholder="Zadejte ulici, kde se firma nachází (včetně čísla popisného)..." type="text" class="form-control" name="company_street" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_ico" class="col-md-2 text-center" style="font-size: 15px;"> IČO</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_ico" placeholder="Zadejte IČO firmy..." type="text" class="form-control" name="company_ico" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="first_name" class="col-md-2 text-center" style="font-size: 13px;"> Jméno zástupce (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="first_name" placeholder="Zadejte křestní jméno zástupce firmy..." type="text" class="form-control" name="first_name" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="surname" class="col-md-2 text-center" style="font-size: 12px;">Příjmení zástupce (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="surname" placeholder="Zadejte příjmení zástupce firmy..." type="text" class="form-control" name="surname" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_email" class="col-md-2 text-center" style="font-size: 15px;"> E-mail (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_email" placeholder="Zadejte e-mailovou adresu firmy..." type="email" class="form-control" name="company_email" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="phone" class="col-md-2 text-center" style="font-size: 15px;">Telefon (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="phone" placeholder="Zadejte telefonní číslo firmy..." type="text" class="form-control" name="phone" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_login" class="col-md-2 text-center" style="font-size: 15px;">Login (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_login" placeholder="Zadejte uživatelské jméno k systému..." type="text" class="form-control" name="company_login"  autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="button" style="margin-bottom: 15px;" class="btn btn-sm btn-warning pull-right" value="Generovat heslo" onClick="generator_admin();">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="password" class="col-md-2 text-center" style="font-size: 15px;">Heslo (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="password" placeholder="Zadejte heslo ..." type="password" class="form-control" name="password">
                                </div>
                                <span toggle="#password" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazHeslo"></span>
                                <script>
                                    /* Skryti/odkryti hesla */
                                    $(".zobrazHeslo").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {input.attr("type", "password");}});
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
                            <label for="password_confirmation" class="col-md-2 text-center" style="font-size: 15px;">Heslo znovu (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="password_confirmation" placeholder="Znovu zadejte heslo ..." type="password" class="form-control" name="password_confirmation">
                                </div>
                                <span toggle="#password_confirmation" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-eye zobrazPotvrzeniHesla"></span>
                                <script>
                                    /* Funkce pro vygenerovani hesla vytvarene firmy */
                                    function generator_admin() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var heslo = "";
                                        var i = 0;
                                        while(i < 10){
                                            heslo += znaky.charAt(Math.floor(Math.random()*znaky.length));
                                            i++;
                                        }
                                        document.getElementById("password").value = heslo;
                                        document.getElementById("password_confirmation").value = heslo;
                                    }

                                    /* Skryti/odkryti hesla */
                                    $(".zobrazPotvrzeniHesla").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {input.attr("type", "password");}});
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
                    <div class="form-check text-center" style="color:white;margin-bottom:15px;">
                            <input type="checkbox" class="form-check-input" id="povoleniGoogleDrive" name="povoleniGoogleDrive[]" value="1">
                            <label class="form-check-label" style="font-size: 16px;" for="povoleniGoogleDrive"> Aktivovat firmě Google Drive. </label><br>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn tlacitkoPotvrzeniOkna" id="SubmitCreateCompany" style="color:white;">Vytvořit firmu</button>
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" data-dismiss="modal" class="btn tlacitkoZavreniOkna">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro editaci firmy -->
    <div class="modal fade" id="CompanyEditForm">
        <div class="modal-dialog" style="max-width: 850px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Detail firmy</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby_edit alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="CompanyContentEdit">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" id="SubmitEditCompanyForm" style="color:white;">Aktualizovat</button>
                    <button type="button" data-dismiss="modal" class="btn tlacitkoZavreniOkna" style="color:white;">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro smazani firmy -->
    <div id="CompanyDeleteForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání firmy</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 17px;color:#4aa0e6;">Opravdu si přejete smazat tuto firmu?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteCompany" style="color:white;" id="SubmitDeleteCompany" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" data-dismiss="modal" class="btn tlacitkoZavreniOkna" style="color:white;">Ne</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            /* K inspiraci prace s datovymi tabulkami slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020,
             pro inspiraci prace  s modalnimi okny (udalosti) slouzil clanek https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event, ktery napsal David Meador v roce 2018 */

            /* Usek kodu starajici se o schovani chybovych hlaseni pri zavreni modalniho okna, inspirace z: https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event */
            $('#CreateCompanyModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })
            $('#CompanyEditForm').on('hidden.bs.modal', function () {
                $('.chyby_edit').hide();
            })

            /* Usek kodu pro schovani chybovych hlaseni pri nacteni webove stranky */
            $('.chyby_add').hide();
            $('.chyby_edit').hide();

            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
              Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
              Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
              K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.
            */
            $(".table_companies").DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná firma."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "V systému neexistují žádné firmy.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[1, "asc"]],
                ajax: "{{ route('admin_companies.list') }}", // nastaveni a odeslani AJAX pozadavku viz https://datatables.net/reference/option/ajax
                columns: [ // definice dat viz https://datatables.net/reference/option/data
                    {data: 'company_picture', name: 'company_picture', orderable: false, searchable: false},
                    {data: 'company_name', name: 'company_name', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'company_user_name', name: 'company_user_name', sClass: 'text-center'},
                    {data: 'company_user_surname', name: 'company_user_surname', sClass: 'text-center'},
                    {data: 'email', name: 'email', sClass: 'text-center'},
                    {data: 'company_phone', name: 'company_phone', sClass: 'text-center'},
                    {data: 'company_address', name: 'company_address', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit firmu" dojde k pridani firmy do databaze */
            $('#SubmitCreateCompany').click(function() {
                $.ajax({ // odeslani ajax pozadavku
                    url: "{{ route('CompanyActions.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat k zaslani
                        nazev_firmy: $('#company_name').val(),
                        mesto_sidla: $('#company_city').val(),
                        ulice_sidla: $('#company_street').val(),
                        ico: $('#company_ico').val(),
                        krestni_jmeno: $('#first_name').val(),
                        prijmeni: $('#surname').val(),
                        emailova_adresa: $('#company_email').val(),
                        telefon: $('#phone').val(),
                        prihlasovaci_jmeno: $('#company_login').val(),
                        heslo: $('#password').val(),
                        potvrzeni_hesla: $('#password_confirmation').val(),
                        googleDriveRequest: $('#povoleniGoogleDrive').is(':checked')
                    },
                    beforeSend:function(){ $('#SubmitCreateCompany').text('Vytváření...'); }, // zmena textu pred odeslanim
                    success: function(odpoved) { // zpracovani odpovedi
                        if (!(odpoved.fail)) {
                            /* Usek kodu pro vyresetovani hodnot formularovych prvku */
                            $('.chyby_add').hide();
                            $('#company_name').val('');
                            $('#company_city').val('');
                            $('#company_street').val('');
                            $('#company_ico').val('');
                            $('#first_name').val('');
                            $('#surname').val('');
                            $('#company_email').val('');
                            $('#phone').val('');
                            $('#company_login').val('');
                            $('#password').val('');
                            $('#password_confirmation').val('');
                            $(":checkbox").attr("checked", false);
                            /* Nacteni tabulky po pridani firmy, aby sla ihned videt */
                            $('.table_companies').DataTable().ajax.reload();
                            /* Definice hlasky o uspechu */
                            var uspech = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success +'</strong></div>';
                            $('.zprava').html(uspech);
                            $('#SubmitCreateCompany').text('Vytvořit firmu');
                            $('#CreateCompanyModal').modal('hide');
                        } else {
                            $('#SubmitCreateCompany').text('Vytvořit firmu');
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

            /* Zobrazeni profilu firmy po stisknuti tlacitka "Zobrazit" */
            var id_firmy_zobrazeni;
            $('body').on('click', '#obtainCompanyDataEdit', function() {
                id_firmy_zobrazeni = $(this).data('id');
                $.ajax({
                    url: "/admin/CompanyActions/"+id_firmy_zobrazeni+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#CompanyContentEdit').html(odpoved.out);
                        $('#CompanyEditForm').show();
                    }
                });
            });

            /* Ulozeni hodnot v profilu firmy do databaze */
            $('#SubmitEditCompanyForm').click(function() {
                $.ajax({
                    url: "/admin/CompanyActions/"+id_firmy_zobrazeni,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {  // definice dat k zaslani
                        nazev_firmy: $('#company_edit').val(),
                        mesto_sidla: $('#company_city_edit').val(),
                        ulice_sidla: $('#company_street_edit').val(),
                        ico: $('#company_ico_edit').val(),
                        krestni_jmeno: $('#first_name_edit').val(),
                        prijmeni: $('#surname_edit').val(),
                        emailova_adresa: $('#company_email_edit').val(),
                        telefon: $('#phone_edit').val(),
                        prihlasovaci_jmeno: $('#company_login_edit').val(),
                        heslo: $('#password_edit').val(),
                        potvrzeni_hesla: $('#password_confirmation_edit').val()
                    },
                    beforeSend:function(){$('#SubmitEditCompanyForm').text('Aktualizace...');}, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                         if (!(odpoved.fail)) {
                             $('.table_companies').DataTable().ajax.reload();
                             var zprava;
                             if(odpoved.success != "0"){ // pokud uzivatel neco zmenil
                                 zprava = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">x</button>'+'<strong>'+odpoved.success+'</strong></div>';
                             }
                             $('#SubmitEditCompanyForm').text('Aktualizovat');
                             $('.zprava').html(zprava);
                             $("#CompanyEditForm").modal('hide');
                        } else {
                             $('#SubmitEditCompanyForm').text('Aktualizovat');
                             $('.chyby_edit').html('');
                             /* Iterace skrze chyby a postupne pridavani jich do elementu chyby_add */
                             odpoved.fail.forEach(function (polozka){
                                 $('.chyby_edit').append('<strong>'+polozka+'</strong><br>');
                             });
                             /* Zobrazeni chyb */
                             $('.chyby_edit').show();
                        }
                    }
                });
            });

            /* Zobrazeni modalniho okna pro smazani firmy po kliknuti na tlacitko "Smazat" */
            var id_firmy_smazani;
            $('body').on('click', '#obtainDeleteIdCompany', function(){
                id_firmy_smazani = $(this).data('id');
            });

            /* Realizace smazani firmy */
            $('#SubmitDeleteCompany').click(function() {
                $.ajax({
                    url: "/admin/CompanyActions/"+id_firmy_smazani,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDeleteCompany').text('Mazání...');}, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var zprava = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">x</button>'+'<strong>'+odpoved.success+'</strong></div>';
                        $('.zprava').html(zprava);
                        $('.table_companies').DataTable().ajax.reload();
                        $('#SubmitDeleteCompany').text('Ano');
                        $("#CompanyDeleteForm").modal('hide');
                    }
                })
            });
        });
    </script>
@endsection
