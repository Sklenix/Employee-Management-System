@extends('layouts.company_dashboard')
@section('title') - Dovolené @endsection
@section('content')
    <!-- Nazev souboru: vacations_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Centrum dovolených" v ramci uctu s roli firmy -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <div class="col-11">
            <!-- Usek kodu pro definici hlasek za pomoci Session -->
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
            <!-- Usek kodu pro definici tabulky -->
            <table class="company_vacations_table">
                <thead>
                    <tr>
                        <th width="1%">Fotka</th>
                        <th width="8%">Jméno</th>
                        <th width="8%">Příjmení</th>
                        <th width="8%">Od</th>
                        <th width="8%">Do</th>
                        <th width="8%">Aktuálnost</th>
                        <th width="8%">Stav</th>
                        <th width="13%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#VacationCreateForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro vytvareni dovolene -->
    <div class="modal fade" id="VacationCreateForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                      <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit novou dovolenou</h4>
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
                            <label for="zamestnanec_vyber" class="col-md-2 text-left" style="font-size: 15px;">Zaměstnanec(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <select name="zamestnanec_vyber" id="zamestnanec_vyber" style="color:black;text-align-last: center;" class="form-control">
                                    <option value="">Vyberte zaměstnance</option>
                                    @foreach($zamestnanci as $zamestnanec)
                                        <option value="{{$zamestnanec->employee_id}}">{{$zamestnanec->employee_name}} {{$zamestnanec->employee_surname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="zacatek_dovolene" class="col-md-2 text-left" style="font-size: 15px;">Datum od (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="zacatek_dovolene" id="zacatek_dovolene">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="konec_dovolene" class="col-md-2 text-left" style="font-size: 15px;">Datum do (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="konec_dovolene" id="konec_dovolene">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="poznamka" class="col-md-2 text-left" style="font-size: 15px;">Poznámka</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                    </div>
                                    <textarea placeholder="Zadejte poznámku k dovolené... [maximálně 180 znaků]" name="poznamka" id="poznamka" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" id="SubmitCreateVacation" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit dovolenou"/>
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoZavreniOkna" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro editaci dovolene -->
    <div class="modal fade" id="EditVacationForm">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Detail dovolené</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="VacationEditContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitEditVacationForm">Aktualizovat</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro odstranovani dovolene -->
    <div id="DeleteVacationForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání dovolené</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete smazat tuto dovolenou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteVacation" style="color:white;" id="SubmitDeleteVacation" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro schvaleni dovolene -->
    <div id="AgreementVacationForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Schválení žádosti o dovolenou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_aggree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu chcete schválit žádost o tuto dovolenou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitApply" style="color:white;" id="SubmitAgreement" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro neschvaleni dovolene -->
    <div id="DisagreementVacationForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Neschválení žádosti o dovolenou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_disagree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete neschválit žádost o tuto dovolenou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteApply" style="color:white;" id="SubmitDisagreement" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro precteni dovolene -->
    <div id="SeenVacationForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení přečtení žádosti o dovolenou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_seen" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete označit tuto žádost jako žádost přečtenou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSeen" style="color:white;" id="SubmitSeen" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro uvedeni zadosti do vychoziho stavu "Odesláno" -->
    <div id="SentVacationForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení vrácení žádosti do stavu "Odesláno"</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_sent" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete vrátit tuto žádost do výchozího stavu (odesláno)?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSent" style="color:white;" id="SubmitSent" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            /* K inspiraci prace s datovymi tabulkami slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020,
                         pro inspiraci prace  s modalnimi okny (udalosti) slouzil clanek https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event, ktery napsal David Meador v roce 2018 */

            /* Usek kodu starajici se o schovani chybovych hlaseni pri zavreni modalniho okna, inspirace z: https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event */
            $('#VacationCreateForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })
            $('#EditVacationForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })
            $('#AgreementVacationForm').on('hidden.bs.modal', function () {
                $('.chyby_aggree').hide();
            })
            $('#DisagreementVacationForm').on('hidden.bs.modal', function () {
                $('.chyby_disagree').hide();
            })
            $('#SeenVacationForm').on('hidden.bs.modal', function () {
                $('.chyby_seen').hide();
            })
            $('#SentVacationForm').on('hidden.bs.modal', function () {
                $('.chyby_sent').hide();
            })

            /* Usek kodu pro schovani chybovych hlaseni pri nacteni webove stranky */
            $('.chyby_add').hide();
            $('.chyby').hide();
            $('.chyby_aggree').hide();
            $('.chyby_disagree').hide();
            $('.chyby_seen').hide();
            $('.chyby_sent').hide();

            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
              Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
              Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
              K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.*/
            $('.company_vacations_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 12,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná dovolená."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné dovolené zaměstnanců.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[3, "asc"]],
                ajax: "{{ route('vacations.list') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    { data: 'employee_picture', name: 'employee_picture', render: function(odpoved){ // vyrenderovani profiloveho obrazku zamestnance
                            if(odpoved === null){return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60'/>";} // Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4#
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + odpoved + " width='60' height='50' style='max-width:100%;height:auto;'/>";
                    }, orderable: false},
                    { data: 'employee_name', name: 'employee_name', sClass:'text-center'},
                    { data: 'employee_surname', name: 'employee_surname', sClass:'text-center'},
                    { data: 'vacation_start', name: 'vacation_start', render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');} ,sClass:'text-center'}, // viz https://datatables.net/reference/option/columns.render
                    { data: 'vacation_end', name: 'vacation_end', render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                    {data: 'vacation_actuality', name: 'vacation_actuality', sClass: 'text-center'},
                    {data: 'vacation_state', name: 'vacation_state', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });


            /* Po stisknuti tlacitka "Vytvořit dovolenou" dojde k pridani nemocenske do databaze */
            $('#SubmitCreateVacation').click(function() {
                $.ajax({
                    url: "{{ route('VacationActions.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        zamestnanec_vyber: $('#zamestnanec_vyber').val(),
                        zacatek_dovolene: $('#zacatek_dovolene').val(),
                        konec_dovolene: $('#konec_dovolene').val(),
                        poznamka: $('#poznamka').val(),
                    },
                    beforeSend:function(){$('#SubmitCreateVacation').text('Vytváření...');},
                    success: function(odpoved) {
                        if(odpoved.success) {
                            /* Smazani hodnot do puvodniho stavu */
                            $('.chyby_add').hide();
                            $('#zacatek_dovolene').val('');
                            $('#konec_dovolene').val('');
                            $('#poznamka').val('');
                            $('.company_vacations_table').DataTable().ajax.reload();
                            /* Definice zpravy o uspechu akce */
                            var successAdd = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong> </div>';
                            $('.flash-message').html(successAdd); // vlozeni do flash-message
                            $('#VacationCreateForm').modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitCreateVacation').text('Vytvořit dovolenou'); // zmena textu na puvodni
                            $('.chyby_add').html(''); // vyresetovani chybovych hlasek
                            /* Iterace skrze chyby a postupne pridavani jich do elementu chyby_add */
                            odpoved.errors.forEach(function (polozka){
                                $('.chyby_add').append('<strong>'+polozka+'</strong><br>');
                            });
                            /* Zobrazeni chyb */
                            $('.chyby_add').show();
                        }
                    }
                });
            });

            /* Zobrazeni profilu dovolene po stisknuti tlacitka "Zobrazit" */
            var id_dovolena;
            $('body').on('click', '#obtainEditVacation', function() {
                id_dovolena = $(this).data('id');
                $.ajax({
                    url: "/company/employees/VacationActions/"+id_dovolena+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#VacationEditContent').html(odpoved.out); // vlozeni obsahu do okna
                        $('#EditVacationForm').show(); // zobrazeni okna
                    }
                });
            });

            /* Ulozeni hodnot v profilu dovolene do databaze */
            $('#SubmitEditVacationForm').click(function() {
                $.ajax({
                    url: "/company/employees/VacationActions/"+id_dovolena,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {
                        zacatek_dovolene: $('#vacation_start_edit').val(),
                        konec_dovolene: $('#vacation_end_edit').val(),
                        poznamka: $('#vacation_note_edit').val(),
                    },
                    beforeSend:function(){$('#SubmitEditVacationForm').text('Aktualizace...');}, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_vacations_table').DataTable().ajax.reload();
                            var successUpdate;
                            if(odpoved.success != "0"){
                                successUpdate = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditVacationForm').text('Aktualizovat');
                            $('.flash-message').html(successUpdate); // vlozeni hlasky do flash message
                            $('.chyby').hide(); // schovani chyb
                            $("#EditVacationForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitEditVacationForm').text('Aktualizovat');
                            $('.chyby').html('');
                            /* Iterace skrze chyby a postupne pridavani jich do elementu chyby */
                            odpoved.errors.forEach(function (polozka){
                                $('.chyby').append('<strong>'+polozka+'</strong><br>');
                            });
                            /* Zobrazeni chyb */
                            $('.chyby').show();
                        }
                    }
                });
            });

            /* Modalni okno slouzici pro smazani dovolenych (po stisknuti tlacitka "Smazat") */
            var dovolena_id_smazat;
            $('body').on('click', '#obtainVacationDelete', function(){
                /* ziskani id dovolene skrze tlacitko (data-id atribut) */
                dovolena_id_smazat = $(this).data('id');
            });

            /* Odstraneni dovolene z databaze */
            $('#SubmitDeleteVacation').click(function() {
                $.ajax({
                    url: "/company/employees/VacationActions/"+dovolena_id_smazat,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDeleteVacation').text('Mazání...');}, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                        var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.fail + '</strong></div>';
                        if(odpoved.success === ''){ // zjisteni, zdali doslo k chybe
                            $('.flash-message').html(failHtml);
                        }else{
                            $('.flash-message').html(successHtml);
                        }
                        $('.company_vacations_table').DataTable().ajax.reload();
                        $('#SubmitDeleteVacation').text('Ano');
                        $("#DeleteVacationForm").modal('hide');
                    }
                })
            });

            /* Ziskani ID dovolene pri stisknuti tlacitka "Schválit" */
            var dovolena_id_schvalit;
            $('body').on('click', '#obtainVacationAgreement', function(){
                dovolena_id_schvalit = $(this).data('id');
            });

            /* Schvaleni zadosti o dovolenou */
            $('#SubmitAgreement').click(function() {
                $.ajax({
                    url: "/company/employees/vacation/agreed/"+dovolena_id_schvalit,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitAgreement').text('Schvalování...');}, // zmena textu pri kliknuti
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_vacations_table').DataTable().ajax.reload();
                            var successAgree = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong> '+ odpoved.success + '</strong></div>';
                            $('#SubmitAgreement').text('Ano');
                            $('.flash-message').html(successAgree);
                            $('.chyby_aggree').hide();
                            $("#AgreementVacationForm").modal('hide');
                        } else { // pokud doslo k chybe
                            $('.chyby_aggree').show();
                            $('.chyby_aggree').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitAgreement').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID dovolene pri zmacknuti tlacitka "Neschválit" */
            var id_dovolena_neschvaleni;
            $('body').on('click', '#obtainVacationDisagreement', function(){
                id_dovolena_neschvaleni = $(this).data('id');
            });

            /* Neschvaleni zadosti o dovolenou */
            $('#SubmitDisagreement').click(function() {
                $.ajax({
                    url: "/company/employees/vacation/disagreed/"+id_dovolena_neschvaleni,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDisagreement').text('Neschvalování...');}, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_vacations_table').DataTable().ajax.reload();
                            var successAgree = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong> '+ odpoved.success + '</strong></div>';
                            $('#SubmitDisagreement').text('Ano');
                            $('.flash-message').html(successAgree);
                            $('.chyby_disagree').hide();
                            $("#DisagreementVacationForm").modal('hide');
                        } else { // pri chybe
                            $('.chyby_disagree').show();
                            $('.chyby_disagree').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitDisagreement').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID dovolene pri zmacknuti tlacitka "Přečteno" */
            var id_dovolene_precteno;
            $('body').on('click', '#obtainVacationSeen', function(){
                id_dovolene_precteno = $(this).data('id');
            });

            /* Stav precteni zadosti o dovolenou */
            $('#SubmitSeen').click(function() {
                $.ajax({
                    url: "/company/employees/vacation/seen/"+id_dovolene_precteno,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitSeen').text('Přečtení...');}, // zmena textu pri kliknuti
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_vacations_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successSeen = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong> '+ odpoved.success + '</strong></div>';
                            $('#SubmitSeen').text('Ano');
                            $('.flash-message').html(successSeen);
                            $('.chyby_seen').hide();
                            $("#SeenVacationForm").modal('hide');
                        } else { // pokud doslo k chybe
                            $('.chyby_seen').show();
                            $('.chyby_seen').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitSeen').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID dovolene pri zmacknuti tlacitka "Odesláno" */
            var id_dovolene_odeslano;
            $('body').on('click', '#obtainVacationSent', function(){
                id_dovolene_odeslano = $(this).data('id');
            });

            /* Realizace uvedeni stavu zadosti do stavu "Odesláno" */
            $('#SubmitSent').click(function() {
                $.ajax({
                    url: "/company/employees/vacation/sent/"+id_dovolene_odeslano,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitSent').text('Odeslání...');}, // zmena textu pri kliknuti
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.company_vacations_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successSent = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong> '+ odpoved.success + '</strong></div>';
                            $('#SubmitSent').text('Ano');
                            $('.flash-message').html(successSent);
                            $('.chyby_sent').hide();
                            $("#SentVacationForm").modal('hide');
                        } else {
                            $('.chyby_sent').show(); // zobrazeni chyby
                            $('.chyby_sent').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitSent').text('Ano');
                        }
                    }
                });
            });

        });
    </script>
@endsection
