@extends('layouts.company_dashboard')
@section('title') - Nahlášení @endsection
@section('content')
    <!-- Nazev souboru: reports_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Centrum nahlášení" v ramci uctu s roli firmy -->
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
            <table class="company_reports_table">
                <thead>
                    <tr>
                        <th width="2%">Fotka</th>
                        <th width="13%">Jméno</th>
                        <th width="13%">Příjmení</th>
                        <th width="12%">Název</th>
                        <th width="23%">Popis</th>
                        <th width="10%">Důležitost</th>
                        <th width="11%">Stav</th>
                        <th width="16%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button" data-toggle="modal" data-target="#ReportCreateForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro vytvareni nahlaseni -->
    <div class="modal fade" id="ReportCreateForm" style="color:white;">
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
                            <label for="nazev_nahlaseni" class="col-md-2 text-left" style="font-size: 15px;">Nadpis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-file-text-o"></i></div>
                                    </div>
                                    <input placeholder="Zadejte, čeho se nahlášení týká..." type="text" class="form-control" id="nazev_nahlaseni" name="nazev_nahlaseni" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="popis_nahlaseni" class="col-md-2 text-left" style="font-size: 15px;">Popis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                    </div>
                                    <textarea placeholder="Zadejte popis nahlášení... [maximálně 180 znaků]" name="popis_nahlaseni" id="popis_nahlaseni" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="dulezitost_nahlaseni" class="col-md-2 text-left" style="font-size: 15px;">Důležitost</label>
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
                        <input type="submit" id="SubmitCreateReport" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit nahlášení"/>
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
                    <div id="ReportEditContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitEditReportForm">Aktualizovat</button>
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
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete smazat toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteReport" style="color:white;" id="SubmitDeleteReport" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro schvaleni nahlaseni -->
    <div id="AgreementReportForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Schválení nahlášení</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_aggree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu chcete schválit toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitAgreement" style="color:white;" id="SubmitAgreement" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro neschvaleni nahlaseni -->
    <div id="DisagreementReportForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Neschválení nahlášení</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_disagree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete neschválit toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDisagreement" style="color:white;" id="SubmitDisagreement" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro uvedeni nahlaseni do vychoziho stavu ("Odesláno") -->
    <div id="SentReportForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení vrácení nahlášení do stavu "Odesláno"</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_sent" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete vrátit toto nahlášení do výchozího stavu (odesláno)?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSent" style="color:white;" id="SubmitSent" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro nastaveni precteni nahlaseni -->
    <div id="SeenReportForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení přečtení nahlášení</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_seen" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete označit toto nahlášení jako přečtené?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSeen" style="color:white;" id="SubmitSeen" class="btn tlacitkoPotvrzeniOkna">Ano</button>
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
            $('#ReportCreateForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })

            $('#EditReportForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#AgreementReportForm').on('hidden.bs.modal', function () {
                $('.chyby_aggree').hide();
            })

            $('#DisagreementReportForm').on('hidden.bs.modal', function () {
                $('.chyby_disagree').hide();
            })

            $('#SeenReportForm').on('hidden.bs.modal', function () {
                $('.chyby_seen').hide();
            })

            $('#SentReportForm').on('hidden.bs.modal', function () {
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
            $('.company_reports_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 12,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebylo nalezeno žádné nahlášení."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné nahlášení zaměstnanců.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[2, "asc"]],
                ajax: "{{ route('reports.list') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat
                    {data: 'employee_picture', name: 'employee_picture', render: function(odpoved){ // vyrenderovani profiloveho obrazku zamestnance
                            if(odpoved === null){return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60'/>";} // viz https://datatables.net/reference/option/columns.render, Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4#
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + odpoved + " width='60' height='50' style='max-width:100%;height:auto;'/>";}, orderable: false},
                    {data: 'employee_name', name: 'employee_name',sClass:'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'employee_surname', name: 'employee_surname',sClass:'text-center'},
                    {data: 'report_title', name: 'report_title', sClass: 'text-center'},
                    {data: 'report_description', name: 'report_description', sClass: 'text-center', orderable: false, searchable: false},
                    {data: 'importance_report_description', name: 'importance_report_description', sClass: 'text-center'},
                    {data: 'report_state', name: 'report_state', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit nahlášení" dojde k pridani nahlaseni do databaze */
            $('#SubmitCreateReport').click(function() {
                $.ajax({
                    url: "{{ route('ReportActions.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat
                        zamestnanec_vyber: $('#zamestnanec_vyber').val(),
                        nazev_nahlaseni: $('#nazev_nahlaseni').val(),
                        popis_nahlaseni: $('#popis_nahlaseni').val(),
                        dulezitost_nahlaseni: $('#dulezitost_nahlaseni').val(),
                    },
                    success: function(odpoved) {
                        if(odpoved.success) {
                            /* Smazani hodnot v modalnim okne */
                            $('.chyby_add').hide();
                            $('#zamestnanec_vyber').val('');
                            $('#nazev_nahlaseni').val('');
                            $('#popis_nahlaseni').val('');
                            $('#dulezitost_nahlaseni').val('');
                            $('.company_reports_table').DataTable().ajax.reload();
                            var successAdd = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.flash-message').html(successAdd);
                            $('#ReportCreateForm').modal('hide');
                        } else {
                            $('#SubmitCreateReport').text('Přidat nemocenskou');
                            $('.chyby_add').html('');
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

            /* Zobrazeni profilu nahlaseni po stisknuti tlacitka "Zobrazit" */
            var nahlaseni_id;
            $('body').on('click', '#obtainEditReport', function() {
                nahlaseni_id = $(this).data('id');
                $.ajax({
                    url: "/company/employees/ReportActions/"+nahlaseni_id+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ReportEditContent').html(odpoved.out);
                        $('#EditReportForm').show();
                    }
                });
            });

            /* Ulozeni hodnot v profilu nahlaseni do databaze */
            $('#SubmitEditReportForm').click(function() {
                $.ajax({
                    url: "/company/employees/ReportActions/"+nahlaseni_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: { // definice dat
                        nazev_nahlaseni: $('#nazev_nahlaseni_edit').val(),
                        popis_nahlaseni: $('#popis_nahlaseni_edit').val(),
                        dulezitost_nahlaseni: $('#dulezitost_nahlaseni_edit').val(),
                    },
                    beforeSend:function(){$('#SubmitEditReportForm').text('Aktualizace...');},  // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_reports_table').DataTable().ajax.reload();
                            var successUpdate;
                            if(odpoved.success != "0"){
                                successUpdate = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditReportForm').text('Aktualizovat');
                            $('.flash-message').html(successUpdate);
                            $('.chyby').hide();
                            $("#EditReportForm").modal('hide');
                        } else {
                            $('#SubmitEditReportForm').text('Aktualizovat');
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

            /* Modalni okno slouzici pro smazani nahlaseni (po stisknuti tlacitka "Smazat") */
            var nahlaseni_id_delete;
            $('body').on('click', '#obtainReportDelete', function(){
                /* Ziskani ID nahlaseni na zaklade atributu data-id, ktery je obsazen v tlacitku "Smazat" */
                nahlaseni_id_delete = $(this).data('id');
            });

            $('#SubmitDeleteReport').click(function(e) {
                $.ajax({
                    url: "/company/employees/ReportActions/"+nahlaseni_id_delete,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDeleteReport').text('Mazání...');}, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                        var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.fail + '</strong></div>';
                        if(odpoved.success === ''){ // pokud nastala chyba, tak se zobrazi chybova hlaska, pokud ne, tak se objevi hlaska o uspechu akce
                            $('.flash-message').html(failHtml);
                        }else{
                            $('.flash-message').html(successHtml);
                        }
                        $('.company_reports_table').DataTable().ajax.reload();
                        $('#SubmitDeleteReport').text('Ano');
                        $("#DeleteReportForm").modal('hide');
                    }
                })
            });


            /* Ziskani ID nahlaseni pri stisknuti tlacitka "Schvalit" */
            var id_nahlaseni_schvalit;
            $('body').on('click', '#obtainReportAgreement', function(){
                id_nahlaseni_schvalit = $(this).data('id');
            });

            /* Schvaleni zadosti o nahlaseni */
            $('#SubmitAgreement').click(function() {
                $.ajax({
                    url: "/company/employees/report/agreed/"+id_nahlaseni_schvalit,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitAgreement').text('Schvalování...');},
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) { // pri uspechu
                            $('.company_reports_table').DataTable().ajax.reload();
                            var successAgree = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitAgreement').text('Ano');
                            $('.flash-message').html(successAgree);
                            $('.chyby_aggree').hide();
                            $("#AgreementReportForm").modal('hide');
                        } else { // pri neuspechu
                            $('.chyby_aggree').show();
                            $('.chyby_aggree').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitAgreement').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID nahlaseni pri zmacknuti tlacitka "Neschválit" */
            var id_nahlaseni_neschvalit;
            $('body').on('click', '#obtainReportDisagreement', function(){
                id_nahlaseni_neschvalit = $(this).data('id');
            });

            /* Neschvaleni zadosti o hlaseni (zapis v databazi) */
            $('#SubmitDisagreement').click(function() {
                $.ajax({
                    url: "/company/employees/report/disagreed/"+id_nahlaseni_neschvalit,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDisagreement').text('Neschvalování...');},
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.company_reports_table').DataTable().ajax.reload();
                            var successDisagree= '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitDisagreement').text('Ano');
                            $('.flash-message').html(successDisagree);
                            $('.chyby_disagree').hide();
                            $("#DisagreementReportForm").modal('hide');
                        } else {
                            $('.chyby_disagree').show();
                            $('.chyby_disagree').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.fail + '</strong></div>');
                            $('#SubmitDisagreement').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID nahlaseni pri zmacknuti tlacitka "Přečteno" */
            var id_nahlaseni_precteno;
            $('body').on('click', '#obtainReportSeen', function(){
                id_nahlaseni_precteno = $(this).data('id');
            });

            /* Realizace zmeny stavu nahlaseni do stavu precteno */
            $('#SubmitSeen').click(function() {
                $.ajax({
                    url: "/company/employees/report/seen/"+id_nahlaseni_precteno,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitSeen').text('Přečtení...');},
                    success: function(odpoved) {
                        if(odpoved.fail) {
                            $('.chyby_seen').show();
                            $('.chyby_seen').html('<div class="alert alert-danger">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitSeen').text('Ano');
                        } else {
                            $('.company_reports_table').DataTable().ajax.reload();
                            var successSeen = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitSeen').text('Ano');
                            $('.flash-message').html(successSeen);
                            $('.chyby_seen').hide();
                            $("#SeenReportForm").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID nahlaseni pri zmacknuti tlacitka "Odesláno" */
            var id_nahlaseni_odeslano;
            $('body').on('click', '#obtainReportSent', function(){
                id_nahlaseni_odeslano = $(this).data('id');
            });

            /* Realizace zmeny stavu nahlaseni do stavu "Odesláno" */
            $('#SubmitSent').click(function() {
                $.ajax({
                    url: "/company/employees/report/sent/"+id_nahlaseni_odeslano,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitSent').text('Odeslání...');},
                    success: function(odpoved) {
                        if(odpoved.fail) {
                            $('.chyby_sent').show();
                            $('.chyby_sent').html('<div class="alert alert-danger">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitSent').text('Ano');
                        } else {
                            $('.company_reports_table').DataTable().ajax.reload();
                            var successSent = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitSent').text('Ano');
                            $('.flash-message').html(successSent);
                            $('.chyby_sent').hide();
                            $("#SentReportForm").modal('hide');
                        }
                    }
                });
            });

        });
    </script>
@endsection


