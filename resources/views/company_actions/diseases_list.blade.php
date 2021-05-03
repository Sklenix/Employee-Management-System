@extends('layouts.company_dashboard')
@section('title') - Nemocenské @endsection
@section('content')
    <!-- Nazev souboru: diseases.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Centrum nemocenských" v ramci uctu s roli firmy -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <!-- Usek kodu pro definici hlasek za pomoci Session -->
        <div class="col-lg-11 col-md-11 col-sm-11">
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
            <table class="company_diseases_table">
                <thead>
                    <tr>
                        <th width="1%">Fotka</th>
                        <th width="8%">Jméno</th>
                        <th width="8%">Příjmení</th>
                        <th width="8%">Od</th>
                        <th width="8%">Do</th>
                        <th width="9%">Název</th>
                        <th width="8%">Aktuálnost</th>
                        <th width="8%">Stav</th>
                        <th width="15%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#CreateDiseaseForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro vytvareni nemocenskych -->
    <div class="modal fade" id="CreateDiseaseForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit novou nemocenskou</h4>
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
                            <label for="nazev_nemoc" class="col-md-2 text-left" style="font-size: 15px;">Název(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                    <input placeholder="Zadejte název nemoci..." type="text" class="form-control" id="nazev_nemoc" name="nazev_nemoc" autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="nemoc_zacatek" class="col-md-2 text-left" style="font-size: 15px;">Datum od (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="nemoc_zacatek" id="nemoc_zacatek" autocomplete="on">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="nemoc_konec" class="col-md-2 text-left" style="font-size: 15px;">Datum do (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="nemoc_konec" id="nemoc_konec" autocomplete="on">
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
                                    <textarea placeholder="Zadejte poznámku k nemocenské [maximálně 180 znaků]..." name="poznamka" id="poznamka" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" name="button_action" id="SubmitCreateDisease" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit nemocenskou" />
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoZavreniOkna" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro editaci nemocenskych -->
    <div class="modal fade" id="EditDiseaseForm">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Detail nemocenské</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="EditDiseaseModalBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitEditDiseaseForm">Aktualizovat</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro smazani nemocenske -->
    <div id="DeleteDiseaseForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání nemocenské</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete smazat tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteDisease" style="color:white;" id="SubmitDeleteDisease" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro schvaleni nemocenske -->
    <div id="AgreementDiseaseForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Schválení žádosti o nemocenskou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_aggree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu chcete schválit žádost o tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitAgreement" style="color:white;" id="SubmitAgreement" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro neschvaleni nemocenske -->
    <div id="DisagreementDiseaseForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Neschválení žádosti o nemocenskou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_disagree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete neschválit žádost o tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDisagreement" style="color:white;" id="SubmitDisagreement" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro zruseni zadosti o nemocenskou -->
    <div id="SentDiseaseForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení vrácení žádosti do stavu "Odesláno"</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_sent" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete vrátit tuto žádost do výchozího stavu (odesláno)?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSent" style="color:white;" id="SubmitSent" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro udeleni stavu precteno k nemocenske -->
    <div id="SeenDiseaseForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení přečtení žádosti o nemocenskou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_seen" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete označit tuto žádost jako žádost přečtenou?</p>
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
            $('#CreateDiseaseForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })

            $('#EditDiseaseForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#AgreementDiseaseForm').on('hidden.bs.modal', function () {
                $('.chyby_aggree').hide();
            })

            $('#DisagreementDiseaseForm').on('hidden.bs.modal', function () {
                $('.chyby_disagree').hide();
            })

            $('#SeenDiseaseForm').on('hidden.bs.modal', function () {
                $('.chyby_seen').hide();
            })

            $('#SentDiseaseForm').on('hidden.bs.modal', function () {
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
              K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.
            */
            $('.company_diseases_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná nemocenská."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné nemocenské zaměstnanců.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[3, "asc"]],
                ajax: "{{ route('diseases.list') }}",
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    { data: 'employee_picture', name: 'employee_picture', // vyrenderovani profiloveho obrazku zamestnance
                        render: function(odpoved){ // viz https://datatables.net/reference/option/columns.render
                            if(odpoved === null){return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60'/>";} // Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4#
                            return "<img src={{URL::to('/') }}/storage/employee_images/" + odpoved + "width='60' height='50' style='max-width:100%;height:auto;'/>";
                        }, orderable: false},
                    { data: 'employee_name', name: 'employee_name',sClass:'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    { data: 'employee_surname', name: 'employee_surname',sClass:'text-center'},
                    { data: 'disease_from', name: 'disease_from', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');},sClass:'text-center'},
                    { data: 'disease_to', name: 'disease_to', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');},sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                    {data: 'disease_name', name: 'disease_name', sClass: 'text-center'},
                    {data: 'disease_actuality', name: 'disease_actuality', sClass: 'text-center'},
                    {data: 'disease_state', name: 'disease_state', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit nemocenskou" dojde k pridani nemocenske do databaze */
            $('#SubmitCreateDisease').click(function() {
                $.ajax({
                    url: "{{ route('DiseaseActions.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat
                        zamestnanec_vyber: $('#zamestnanec_vyber').val(),
                        nazev_nemoc: $('#nazev_nemoc').val(),
                        nemoc_zacatek: $('#nemoc_zacatek').val(),
                        nemoc_konec: $('#nemoc_konec').val(),
                        poznamka: $('#poznamka').val(),
                    },
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            /* Smazani hodnot v modalnim okne */
                            $('.chyby_add').hide();
                            $('#nazev_nemoc').val('');
                            $('#nemoc_zacatek').val('');
                            $('#nemoc_konec').val('');
                            $('#poznamka').val('');
                            /* Nacteni tabulky po pridani nemocenske, aby sla ihned videt */
                            $('.company_diseases_table').DataTable().ajax.reload();
                            var successAdd = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success + '</strong></div>';
                            $('.flash-message').html(successAdd); // nastaveni hlasky o uspechu
                            $('#CreateDiseaseForm').modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitCreateDisease').text('Vytvořit nemocenskou');
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

            /* Zobrazeni profilu nemocenske po stisknuti tlacitka "Zobrazit" */
            var id_nemocenske;
            $('body').on('click', '#obtainDiseaseEdit',function() {
                /* Ziskani identifikatoru nemocenske */
                id_nemocenske = $(this).data('id');
                $.ajax({
                    url: "/company/employees/DiseaseActions/"+id_nemocenske+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#EditDiseaseModalBody').html(odpoved.out); // nastaveni obsahu do modalniho okna
                        $('#EditDiseaseForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni hodnot v profilu nemocenske do databaze */
            $('#SubmitEditDiseaseForm').click(function() {
                $.ajax({
                    url: "/company/employees/DiseaseActions/"+id_nemocenske,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {
                        nazev_nemoci: $('#disease_name_edit').val(),
                        nemoc_zacatek: $('#disease_from_edit').val(),
                        nemoc_konec: $('#disease_to_edit').val(),
                        poznamka: $('#disease_note_edit').val(),
                    },
                    beforeSend:function(){$('#SubmitEditDiseaseForm').text('Aktualizace...');}, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) {
                        if(odpoved.success) {
                            /* Refresh datove tabulky */
                            $('.company_diseases_table').DataTable().ajax.reload();
                            var successUpdate;
                            /* Pokud se neco zmenilo, tak se nastavi hlaska o uspechu */
                            if(odpoved.success != "0"){
                                successUpdate = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
                            $('.flash-message').html(successUpdate); // nastaveni hlasky o uspechu
                            $('.chyby').hide(); // schovani chyb
                            $("#EditDiseaseForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
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

            /* Modalni okno slouzici pro smazani nemocenskych (po stisknuti tlacitka "Smazat") */
            var nemocenska_delete_id;
            $('body').on('click', '#obtainDiseaseDelete', function(){
                /* Ziskani ID nemocenske po kliknuti na tlacitko "Smazat" */
                nemocenska_delete_id = $(this).data('id');
            });

            /* Realizace smazani nemocenske */
            $('#SubmitDeleteDisease').click(function() {
                $.ajax({
                    url: "/company/employees/DiseaseActions/"+nemocenska_delete_id,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDeleteDisease').text('Mazání...');}, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                        var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>';
                        if(odpoved.success === ''){ // pokud nastala chyba zobrazi se chybova hlaska, pokud ne, objevi se hlaska o uspechu akce
                            $('.flash-message').html(failHtml);
                        }else{
                            $('.flash-message').html(successHtml);
                        }
                        $('.company_diseases_table').DataTable().ajax.reload(); // refresh datove tabulky
                        $('#SubmitDeleteDisease').text('Ano'); // zmena textu tlacitka
                        $("#DeleteDiseaseForm").modal('hide'); // schovani modalniho okna
                    }
                })
            });

            /* Ziskani ID nemocenske pri stisknuti tlacitka "Schválit" */
            var nemocenska_id_schvaleno;
            $('body').on('click', '#obtainDiseaseAgreement', function(){
                nemocenska_id_schvaleno = $(this).data('id');
            });

            /* Zmena stavu zadosti pri schvaleni nemocenske (zapis do databaze) */
            $('#SubmitAgreement').click(function() {
                $.ajax({
                    url: "/company/employees/disease/agreed/"+nemocenska_id_schvaleno,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitAgreement').text('Schvalování...');}, // zmena textu pri kliknuti
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.fail) {
                            $('.chyby_aggree').show(); // zobrazeni chyb
                            $('.chyby_aggree').html('<div class="alert alert-danger">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitAgreement').text('Ano');
                        } else {
                            $('.company_diseases_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successApply = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitAgreement').text('Ano');
                            $('.flash-message').html(successApply); // nastaveni hlasky o uspechu
                            $('.chyby_aggree').hide(); // schovani chyb
                            $("#AgreementDiseaseForm").modal('hide'); // schovani modalniho okna
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske pri stisknuti tlacitka "Neschválit" */
            var nemocenska_id_neschvaleni;
            $('body').on('click', '#obtainDiseaseDisagreement', function(){
                nemocenska_id_neschvaleni = $(this).data('id');
            });

            /* Zmena stavu zadosti pri neschvaleni nemocenske (zapis do databaze) */
            $('#SubmitDisagreement').click(function() {
                $.ajax({
                    url: "/company/employees/disease/disagreed/"+nemocenska_id_neschvaleni,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDisagreement').text('Neschvalování...');}, // zmena textu pri kliknuti
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_diseases_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successDisagree = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitDisagreement').text('Ano');
                            $('.flash-message').html(successDisagree); // nastaveni hlasky o uspechu
                            $('.chyby_disagree').hide(); // schovani chyb
                            $("#DisagreementDiseaseForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('.chyby_disagree').show(); // zobrazeni chyb
                            $('.chyby_disagree').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitDisagreement').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske pri zmacknuti tlacitka "Přečteno" */
            var id_nemocenska_precteno;
            $('body').on('click', '#obtainDiseaseSeen', function(){
                id_nemocenska_precteno = $(this).data('id');
            });

            /* Zmena stavu zadosti pri precteni nemocenske (zapis do databaze) */
            $('#SubmitSeen').click(function() {
                $.ajax({
                    url: "/company/employees/disease/seen/"+id_nemocenska_precteno,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitSeen').text('Přečtení...');}, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_diseases_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successSeen = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitSeen').text('Ano');
                            $('.flash-message').html(successSeen); // nastaveni hlasky o uspechu
                            $('.chyby_seen').hide(); // schovani chyb
                            $("#SeenDiseaseForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('.chyby_seen').show(); // zobrazeni chyb
                            $('.chyby_seen').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitSeen').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske pri zmacknuti tlacitka "Odesláno" */
            var id;
            $('body').on('click', '#obtainDiseaseSent', function(){
                id = $(this).data('id');
            });

            /* Zmena stavu zadosti pri navraceni do vychozi hodnoty "Odesláno" (zapis do databaze) */
            $('#SubmitSent').click(function() {
                $.ajax({
                    url: "/company/employees/disease/sent/"+id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitSent').text('Odeslání...');}, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_diseases_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successSent = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitSent').text('Ano');
                            $('.flash-message').html(successSent); // nastaveni hlasky o uspechu
                            $('.chyby_sent').hide(); // schovani chyb
                            $("#SentDiseaseForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('.chyby_sent').show(); // zobrazeni chyb
                            $('.chyby_sent').html('<div class="alert alert-danger">'+ '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>'+ odpoved.fail + '</strong></div>');
                            $('#SubmitSent').text('Ano');
                        }
                    }
                });
            });
        });
    </script>
@endsection

