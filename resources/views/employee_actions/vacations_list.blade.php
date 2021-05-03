@extends('layouts.employee_dashboard')
@section('title') - Dovolené @endsection
@section('content')
    <!-- Nazev souboru: vacations_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Centrum dovolených" v ramci uctu s roli zamestnance -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <div class="col-lg-11 col-md-10 col-sm-10">
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
            <table class="employee_vacations_table">
                <thead>
                    <tr>
                        <th width="14%">Od</th>
                        <th width="14%">Do</th>
                        <th width="31%">Poznámka</th>
                        <th width="13%">Aktuálnost</th>
                        <th width="13%">Stav</th>
                        <th width="15%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#VacationCreateForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro vytvareni dovolene -->
    <div class="modal fade" id="VacationCreateForm" style="color:white;">
        <div class="modal-dialog modal-lg">
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
                            <label for="zacatek_dovolene" class="col-md-2 text-left">Datum od (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="zacatek_dovolene" id="zacatek_dovolene" autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="konec_dovolene" class="col-md-2 text-left">Datum do (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="konec_dovolene" id="konec_dovolene" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="poznamka" class="col-md-2 text-left">Poznámka</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                    </div>
                                    <textarea placeholder="Zadejte poznámku k dovolené [maximálně 180 znaků]..." name="poznamka" id="poznamka" class="form-control" autocomplete="on"></textarea>
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
                    <button type="button" name="SubmitDeleteVacation" style="color:white;" id="SubmitDeleteVacation" class="btn tlacitkoPotvrzeniOkna"  >Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro zazadani o dovolenou -->
    <div id="ApplyVacationForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení žádosti o dovolenou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu chcete zažádat o tuto dovolenou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitApply" style="color:white;" id="SubmitApply" class="btn tlacitkoPotvrzeniOkna"  >Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro zruseni zadosti o dovolenou -->
    <div id="DeleteApplyVacationForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení zrušení žádosti o dovolenou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_delete_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete zrušit žádost o tuto dovolenou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteApply" style="color:white;" id="SubmitDeleteApply" class="btn tlacitkoPotvrzeniOkna"  >Ano</button>
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

        $('#ApplyVacationForm').on('hidden.bs.modal', function () {
            $('.chyby_apply').hide();
        })

        $('#DeleteApplyVacationForm').on('hidden.bs.modal', function () {
            $('.chyby_delete_apply').hide();
        })

        /* Usek kodu pro schovani chybovych hlaseni pri nacteni webove stranky */
        $('.chyby_add').hide();
        $('.chyby').hide();
        $('.chyby_apply').hide();
        $('.chyby_delete_apply').hide();

        /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
             Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
             Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
             K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.*/
        $('.employee_vacations_table').DataTable({
            serverSide: true,
            paging: true,
            autoWidth: true,
            pageLength: 15,
            scrollX: true,
            oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná dovolená."},
            language: {
                searchPlaceholder: "Vyhledávání ... ",
                emptyTable: "Nemáte zaevidovanou žádnou dovolenou.",
                paginate: { previous: "Předchozí", next: "Další"}
            },
            bInfo: false,
            bLengthChange: false,
            order: [[0, "asc"]],
            ajax: "{{ route('employee_vacations.list') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
            columns: [ // definice dat (viz https://datatables.net/manual/data/)
                {data: 'vacation_start', name: 'vacation_start', render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // viz https://datatables.net/reference/option/columns.render
                {data: 'vacation_end', name: 'vacation_end', render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                {data: 'vacation_note', name: 'vacation_note', sClass: 'text-center', orderable: false, searchable: false},
                {data: 'vacation_actuality', name: 'vacation_actuality', sClass: 'text-center'},
                {data: 'vacation_state', name: 'vacation_state', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
            ]
        });

        /* Po stisknuti tlacitka "Vytvořit dovolenou" dojde k pridani nemocenske do databaze */
        $('#SubmitCreateVacation').click( function() {
            $.ajax({
                url: "{{ route('VacationActionsEmployee.store') }}",
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                data: {
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
                        $('.employee_vacations_table').DataTable().ajax.reload();
                        /* Definice zpravy o uspechu akce */
                        var successAdd = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong> </div>';
                        $('.flash-message').html(successAdd); // vlozeni do flash-message
                        $('#VacationCreateForm').modal('hide'); // schovani modalniho okna
                    } else {
                        $('#SubmitCreateVacation').text('Vytvořit dovolenou'); // zmena textu na puvodni
                        $('.chyby_add').html(''); // vyresetovani chybovych hlasek
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

        /* Zobrazeni profilu dovolene po stisknuti tlacitka "Editovat" */
        var id_dovolene;
        $('body').on('click', '#obtainEditVacationData', function() {
            id_dovolene = $(this).data('id');
            $.ajax({
                url: "/employee/VacationActionsEmployee/"+id_dovolene+"/edit",
                method: 'GET',
                success: function(odpoved) {
                    $('#VacationEditContent').html(odpoved.out); // vlozeni obsahu do okna
                    $('#EditVacationForm').show(); // zobrazeni okna
                }
            });
        });

        /* Ulozeni hodnot v profilu dovolene do databaze */
        $('#SubmitEditVacationForm').click( function() {
            $.ajax({
                url: "/employee/VacationActionsEmployee/"+id_dovolene,
                method: 'PUT',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                data: {
                    zacatek_dovolene: $('#vacation_start_edit').val(),
                    konec_dovolene: $('#vacation_end_edit').val(),
                    poznamka: $('#vacation_note_edit').val(),
                },
                beforeSend:function(){ $('#SubmitEditVacationForm').text('Aktualizace...'); }, // zobrazeni textu po zakliknuti tlacitka
                success: function(odpoved) { // zpracovani odpovedi
                    if(odpoved.success) {
                        $('.employee_vacations_table').DataTable().ajax.reload();
                        var successUpdate;
                        if(odpoved.success != "0"){
                            successUpdate = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                        }
                        $('#SubmitEditVacationForm').text('Aktualizovat'); // zmena textu
                        $('.flash-message').html(successUpdate); // vlozeni hlasky do flash message
                        $('.chyby').hide(); // schovani chyb
                        $("#EditVacationForm").modal('hide'); // schovani modalniho okna
                    }else if(odpoved.errors) {
                        $('#SubmitEditVacationForm').text('Aktualizovat');
                        $('.chyby').html('');
                        /* Iterace skrze chyby a postupne pridavani jich do elementu chyby */
                        odpoved.errors.forEach(function (polozka){
                            $('.chyby').append('<strong>'+polozka+'</strong><br>');
                        });
                        /* Zobrazeni chyb */
                        $('.chyby').show();
                    } else {
                        $('.chyby').hide();
                        $('.chyby').html('');
                        var updateFailed = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>'; // definice chybove hlasky
                        $('.flash-message').html(updateFailed);
                        $('#SubmitEditVacationForm').text('Aktualizovat');
                        $("#EditVacationForm").modal('hide'); // schovani modalniho okna
                    }
                }
            });
        });


        /* Modalni okno slouzici pro smazani dovolenych (po stisknuti tlacitka "Smazat") */
        var dovolena_id_delete;
        $('body').on('click', '#obtainVacationDelete', function(){
            /* ziskani id dovolene skrze tlacitko (data-id atribut) */
            dovolena_id_delete = $(this).data('id');
        });

        /* Odstraneni dovolene z databaze */
        $('#SubmitDeleteVacation').click( function() {
            $.ajax({
                url: "/employee/VacationActionsEmployee/"+dovolena_id_delete,
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                beforeSend:function(){ $('#SubmitDeleteVacation').text('Mazání...'); }, // zmena textu pri kliknuti
                success:function(odpoved) { // zpracovani odpovedi
                    var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                    var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.fail + '</strong></div>';
                    if(odpoved.success === ''){ // zjisteni, zdali doslo k chybe
                        $('.flash-message').html(failHtml);
                    }else{
                        $('.flash-message').html(successHtml);
                    }
                    $('.employee_vacations_table').DataTable().ajax.reload();
                    $('#SubmitDeleteVacation').text('Ano');
                    $("#DeleteVacationForm").modal('hide');
                }
            })
        });

        /* Ziskani ID dovolene pri stisknuti tlacitka "Zažádat" */
        var dovolena_zazadani_id;
        $('body').on('click', '#obtainVacationApply', function(){
            dovolena_zazadani_id = $(this).data('id');
        });

        /* Zmena stavu zadosti pri zazadani o dovolenou (zapis do databaze) */
        $('#SubmitApply').click(function() {
            $.ajax({
                url: "/employee/vacation/apply/"+dovolena_zazadani_id,
                method: 'PUT',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                beforeSend:function(){ $('#SubmitApply').text('Žádání...'); }, // zmena textu pri kliknuti
                success: function(odpoved) { // zpracovani odpovedi
                    if(odpoved.success) {
                        $('.employee_vacations_table').DataTable().ajax.reload();
                        var successApply = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong> '+ odpoved.success + '</strong></div>';
                        $('#SubmitApply').text('Ano');
                        $('.flash-message').html(successApply);
                        $('.chyby_apply').hide();
                        $("#ApplyVacationForm").modal('hide');
                    } else { // pokud doslo k chybe
                        $('.chyby_apply').show();
                        $('.chyby_apply').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                        $('#SubmitApply').text('Ano');
                    }
                }
            });
        });

        /* Ziskani ID dovolene pri zmacknuti tlacitka "Zrušit žádost" */
        var dovolena_id_zruseni;
        $('body').on('click', '#obtainVacationDeleteApply', function(){
            dovolena_id_zruseni = $(this).data('id');
        });

        /* Zruseni zadosti o dovolenou */
        $('#SubmitDeleteApply').click( function() {
            $.ajax({
                url: "/employee/vacation/deleteApply/"+dovolena_id_zruseni,
                method: 'PUT',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                beforeSend:function(){ $('#SubmitDeleteApply').text('Žádání...'); }, // zobrazeni textu po zakliknuti tlacitka
                success: function(odpoved) { // zpracovani odpovedi
                    if(odpoved.success) {
                        $('.employee_vacations_table').DataTable().ajax.reload();
                        var successDel = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                        $('#SubmitDeleteApply').text('Ano');
                        $('.flash-message').html(successDel);
                        $('.chyby_delete_apply').hide();
                        $("#DeleteApplyVacationForm").modal('hide');
                    } else { // pri chybe
                        $('.chyby_delete_apply').show();
                        $('.chyby_delete_apply').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                        $('#SubmitDeleteApply').text('Ano');
                    }
                }
            });
        });
    });
</script>

@endsection
