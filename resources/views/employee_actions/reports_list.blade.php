@extends('layouts.employee_dashboard')
@section('title') - Nahlášení @endsection
@section('content')
    <!-- Nazev souboru: reports_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Centrum nahlášení" v ramci uctu s roli zamestnance -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <!-- Usek kodu pro definici hlasek za pomoci Session -->
        <div class="col-11">
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
            <table class="employee_reports_table">
                <thead>
                    <tr>
                        <th width="20%">Název</th>
                        <th width="35%">Popis</th>
                        <th width="12%">Důležitost</th>
                        <th width="10%">Stav</th>
                        <th width="23%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#CreateReportForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro vytvareni nahlaseni -->
    <div class="modal fade" id="CreateReportForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                      <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit nové nahlášení</h4>
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
                            <label for="nazev_nahlaseni" class="col-md-2 text-left">Nadpis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-file-text-o"></i></div>
                                    </div>
                                    <input placeholder="Zadejte, čeho se nahlášení týká..." type="text" class="form-control" id="nazev_nahlaseni" name="nazev_nahlaseni" autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="popis_nahlaseni" class="col-md-2 text-left">Popis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                    </div>
                                    <textarea placeholder="Zadejte popis nahlášení [maximálně 180 znaků]..." name="popis_nahlaseni" id="popis_nahlaseni" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="dulezitost_nahlaseni" class="col-md-2 text-left">Důležitost</label>
                            <div class="col-md-10">
                                <select name="dulezitost_nahlaseni" id="dulezitost_nahlaseni" style="color:black;text-align-last: center;" class="form-control">
                                    <option value="">Vyberte důležitost</option>
                                    @foreach($dulezitosti as $dulezitost)
                                        <option value="{{$dulezitost->importance_report_id}}">{{$dulezitost->importance_report_description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" id="SubmitCreateReport" style="color:rgba(255, 255, 255, 0.90);" value="Vytvořit nahlášení" class="btn tlacitkoPotvrzeniOkna"/>
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoZavreniOkna" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro editaci nahlaseni -->
    <div class="modal fade" id="EditReportForm">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Detail nahlášení</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="ReportEditFormContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" id="SubmitEditReportForm" style="color:white;">Aktualizovat</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro smazani nahlaseni -->
    <div id="DeleteReportForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání nahlášení</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete smazat toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteReport" style="color:white;" id="SubmitDeleteReport" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro odeslani nahlaseni -->
    <div id="ApplyReportForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení odeslání nahlášení</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu chcete odeslat toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitApply" style="color:white;" id="SubmitApply" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro zruseni odeslani nahlaseni -->
    <div id="DeleteApplyReportForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení zrušení odeslání nahlášení</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_delete_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete zrušit odeslání tohoto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteApply" style="color:white;" id="SubmitDeleteApply" class="btn tlacitkoPotvrzeniOkna">Ano</button>
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
            $('#CreateReportForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })

            $('#EditReportForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#ApplyReportForm').on('hidden.bs.modal', function () {
                $('.chyby_apply').hide();
            })

            $('#DeleteApplyReportForm').on('hidden.bs.modal', function () {
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
            $('.employee_reports_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebylo nalezeno žádné nahlášení."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné nahlášení.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[0, "asc"]],
                ajax: "{{ route('employee_reports.list') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    {data: 'report_title', name: 'report_title', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'report_description', name: 'report_description', sClass: 'text-center', orderable: false, searchable: false},
                    {data: 'importance_report_description', name: 'importance_report_description', sClass: 'text-center'},
                    {data: 'report_state', name: 'report_state', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit nahlášení" dojde k pridani nahlaseni do databaze */
            $('#SubmitCreateReport').click( function() {
                $.ajax({
                    url: "{{ route('ReportActionsEmployee.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        nazev_nahlaseni: $('#nazev_nahlaseni').val(),
                        popis_nahlaseni: $('#popis_nahlaseni').val(),
                        dulezitost_nahlaseni: $('#dulezitost_nahlaseni').val(),
                    },
                    beforeSend:function(){$('#SubmitCreateReport').text('Vytváření...');},
                    success: function(odpoved) {
                        if(odpoved.success) {
                            /* Smazani hodnot v modalnim okne */
                            $('.chyby_add').hide();
                            $('#nazev_nahlaseni').val('');
                            $('#popis_nahlaseni').val('');
                            $('#dulezitost_nahlaseni').val('');
                            $('.employee_reports_table').DataTable().ajax.reload();
                            var successAdd = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.flash-message').html(successAdd);
                            $('#CreateReportForm').modal('hide');
                        } else {
                            $('#SubmitCreateReport').text('Vytvořit nemocenskou');
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

            /* Zobrazeni profilu nahlaseni po stisknuti tlacitka "Editovat" */
            var id_nahlaseni;
            $('body').on('click', '#obtainEditReport', function() {
                id_nahlaseni = $(this).data('id');
                $.ajax({
                    url: "/employee/ReportActionsEmployee/"+id_nahlaseni+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ReportEditFormContent').html(odpoved.out);
                        $('#EditReportForm').show();
                    }
                });
            });

            /* Ulozeni hodnot v profilu nahlaseni do databaze */
            $('#SubmitEditReportForm').click( function() {
                $.ajax({
                    url: "/employee/ReportActionsEmployee/"+id_nahlaseni,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {
                        nazev_nahlaseni: $('#nazev_nahlaseni_edit').val(),
                        popis_nahlaseni: $('#popis_nahlaseni_edit').val(),
                        dulezitost_nahlaseni: $('#dulezitost_nahlaseni_edit').val(),
                    },
                    beforeSend:function(){ $('#SubmitEditReportForm').text('Aktualizace...'); },  // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.employee_reports_table').DataTable().ajax.reload();
                            var successUpdate;
                            if(odpoved.success != "0"){
                                successUpdate = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditReportForm').text('Aktualizovat');
                            $('.flash-message').html(successUpdate);
                            $('.chyby').hide();
                            $("#EditReportForm").modal('hide');
                        }else if(odpoved.errors) {
                            $('#SubmitEditReportForm').text('Aktualizovat');
                            $('.chyby').html('');
                            /* Iterace skrze chyby a postupne pridavani jich do elementu chyby */
                            odpoved.errors.forEach(function (polozka){
                                $('.chyby').append('<strong>'+polozka+'</strong><br>');
                            });
                            /* Zobrazeni chyb */
                            $('.chyby').show();
                        } else {
                            $('.chyby').hide(); // schovani a smazani chybovych hlasek
                            $('.chyby').html('');
                            var failUpdate = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>';
                            $('.flash-message').html(failUpdate); // naplneni elementu div obsahem html, v tomto pripade promennou failUpdate
                            $('#SubmitEditReportForm').text('Aktualizovat');
                            $("#EditReportForm").modal('hide');
                        }
                    }
                });
            });

            /* Modalni okno slouzici pro smazani nahlaseni (po stisknuti tlacitka "Smazat") */
            var nahlaseni_id_delete;
            $('body').on('click', '#obtainReportDelete', function(){
                /* Ziskani ID nahlaseni na zaklade atributu data-id, ktery je obsazen v tlacitku "Smazat" */
                nahlaseni_id_delete = $(this).data('id');
            });

            /* Realizace smazani nahlaseni */
            $('#SubmitDeleteReport').click( function() {
                $.ajax({
                    url: "/employee/ReportActionsEmployee/"+nahlaseni_id_delete,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){ $('#SubmitDeleteReport').text('Mazání...'); }, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                        var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.fail + '</strong></div>';
                        if(odpoved.success === ''){ // pokud nastala chyba, tak se zobrazi chybova hlaska, pokud ne, tak se objevi hlaska o uspechu akce
                            $('.flash-message').html(failHtml);
                        }else{
                            $('.flash-message').html(successHtml);
                        }
                        $('.employee_reports_table').DataTable().ajax.reload();
                        $('#SubmitDeleteReport').text('Ano');
                        $("#DeleteReportForm").modal('hide');
                    }
                })
            });

            /* Ziskani ID nahlaseni pri stisknuti tlacitka "Odeslat" */
            var id_nahlaseni_odeslat;
            $('body').on('click', '#obtainReportApply', function(){
                id_nahlaseni_odeslat = $(this).data('id');
            });

            /* Zmena stavu zadosti pri poslani nahlaseni (zapis do databaze) */
            $('#SubmitApply').click( function() {
                $.ajax({
                    url: "/employee/report/apply/"+id_nahlaseni_odeslat,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){ $('#SubmitApply').text('Žádání...'); }, // zmena textu pri kliknuti
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) { // pri uspechu
                            $('.employee_reports_table').DataTable().ajax.reload();
                            var successSent = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitApply').text('Ano');
                            $('.flash-message').html(successSent);
                            $('.chyby_apply').hide();
                            $("#ApplyReportForm").modal('hide');
                        } else { // pri neuspechu
                            $('.chyby_apply').show();
                            $('.chyby_apply').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitApply').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID nahlaseni pri zmacknuti tlacitka "Zrušit odeslání" */
            var id_nahlaseni_zrusit;
            $('body').on('click', '#obtainReportDeleteApply', function(){
                id_nahlaseni_zrusit = $(this).data('id');
            });

            /* Zruseni odeslani nahlaseni */
            $('#SubmitDeleteApply').click( function() {
                $.ajax({
                    url: "/employee/report/deleteApply/"+id_nahlaseni_zrusit,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){ $('#SubmitDeleteApply').text('Žádání...'); },
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.employee_reports_table').DataTable().ajax.reload();
                            var successDeleteApply = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitDeleteApply').text('Ano');
                            $('.flash-message').html(successDeleteApply);
                            $('.chyby_delete_apply').hide();
                            $("#DeleteApplyReportForm").modal('hide');
                        } else {
                            $('.chyby_delete_apply').show();
                            $('.chyby_delete_apply').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.fail + '</strong></div>');
                            $('#SubmitDeleteApply').text('Ano');
                        }
                    }
                });
            });
        });
    </script>

@endsection


