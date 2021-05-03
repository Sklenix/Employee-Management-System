@extends('layouts.employee_dashboard')
@section('title') - Nemocenské @endsection
@section('content')
    <!-- Nazev souboru: diseases_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Centrum dovolených" v ramci uctu s roli zamestnance -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <!-- Usek kodu pro definici chybovych hlasek za pomoci Session -->
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
            <table class="diseases_employee_table">
                <thead>
                    <tr>
                        <th width="12%">Od</th>
                        <th width="12%">Do</th>
                        <th width="20%">Název</th>
                        <th width="25%">Poznámka</th>
                        <th width="7%">Aktuálnost</th>
                        <th width="9%">Stav</th>
                        <th width="15%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button" data-toggle="modal" data-target="#DiseaseCreateForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro vytvareni nemocenskych -->
    <div class="modal fade" id="DiseaseCreateForm" style="color:white;">
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
                            <label for="nazev_nemoc" class="col-md-2 text-left">Název(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                    <input placeholder="Zadejte název nemoci..." type="text" class="form-control" id="nazev_nemoc" name="nazev_nemoc" value="{{ old('nazev_nemoc') }}"  autocomplete="on" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="nemoc_zacatek" class="col-md-2 text-left">Datum od (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="nemoc_zacatek" id="nemoc_zacatek" value="{{ old('nemoc_zacatek') }}" autocomplete="on" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="nemoc_konec" class="col-md-2 text-left">Datum do (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="nemoc_konec" id="nemoc_konec" value="{{ old('nemoc_konec') }}" autocomplete="on">
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
                                    <textarea placeholder="Zadejte poznámku k nemocenské [maximálně 180 znaků]..." name="poznamka" id="poznamka" class="form-control" autocomplete="on"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" id="SubmitCreateDisease" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit nemocenskou"/>
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
                    <div id="EditDiseaseFormContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitEditDiseaseForm">Aktualizovat</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro odstranovani nemocenskych -->
    <div id="DeleteDiseaseForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání nemocenské</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete smazat tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteDisease" style="color:white;" id="SubmitDeleteDisease" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro zazadani o nemocenske -->
    <div id="ApplyDiseaseForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení žádosti o nemocenskou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu chcete zažádat o tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitApply" style="color:white;" id="SubmitApply" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro zruseni zadosti o nemocenske -->
    <div id="DeleteApplyDiseaseForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení zrušení žádosti o nemocenskou</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_delete_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete zrušit žádost o tuto nemocenskou?</p>
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
            $('#DiseaseCreateForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })

            $('#EditDiseaseForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#ApplyDiseaseForm').on('hidden.bs.modal', function () {
                $('.chyby_apply').hide();
            })

            $('#DeleteApplyDiseaseForm').on('hidden.bs.modal', function () {
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
              K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.
            */
            $('.diseases_employee_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná nemocenská."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné nemocenské.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[1, "asc"]],
                ajax: "{{ route('employee_diseases.list') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    {data: 'disease_from', name: 'disease_from', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // viz https://datatables.net/reference/option/columns.render
                    {data: 'disease_to', name: 'disease_to', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center',}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                    {data: 'disease_name', name: 'disease_name', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'disease_note', name: 'disease_note', sClass: 'text-center', orderable: false, searchable: false},
                    {data: 'disease_actuality', name: 'disease_actuality', sClass: 'text-center'},
                    {data: 'disease_state', name: 'disease_state', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit nemocenskou" dojde k pridani nemocenske do databaze */
            $('#SubmitCreateDisease').click(function() {
                $.ajax({
                    url: "{{ route('DiseaseActionsEmployee.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        nazev_nemoc: $('#nazev_nemoc').val(),
                        nemoc_zacatek: $('#nemoc_zacatek').val(),
                        nemoc_konec: $('#nemoc_konec').val(),
                        poznamka: $('#poznamka').val(),
                    },
                    beforeSend:function(){$('#SubmitCreateDisease').text('Vytváření...');},
                    success: function(odpoved) { // zpracovani odpovedi
                        if (!(odpoved.fail)) {
                            /* Smazani hodnot v modalnim okne */
                            $('.chyby_add').hide();
                            $('#nazev_nemoc').val('');
                            $('#nemoc_zacatek').val('');
                            $('#nemoc_konec').val('');
                            $('#poznamka').val('');
                            /* Nacteni tabulky po pridani nemocenske, aby sla ihned videt */
                            $('.diseases_employee_table').DataTable().ajax.reload();
                            var successAdd = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.flash-message').html(successAdd);
                            $('#DiseaseCreateForm').modal('hide');
                        } else {
                            $('#SubmitCreateDisease').text('Vytvořit nemocenskou');
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

            /* Zobrazeni profilu nemocenske po stisknuti tlacitka "Editovat" */
            var id_nemocenske;
            $('body').on('click', '#obtainDiseaseEdit', function() {
                id_nemocenske = $(this).data('id');
                $.ajax({
                    url: "/employee/DiseaseActionsEmployee/"+id_nemocenske+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#EditDiseaseFormContent').html(odpoved.out); // vlozeni obsahu do okna
                        $('#EditDiseaseForm').show(); // zobrazeni okna
                    }
                });
            });

            /* Ulozeni hodnot v profilu nemocenske do databaze */
            $('#SubmitEditDiseaseForm').click(function() {
                $.ajax({
                    url: "/employee/DiseaseActionsEmployee/"+id_nemocenske,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {
                        nazev_nemoci: $('#disease_name_edit').val(),
                        nemoc_zacatek: $('#disease_from_edit').val(),
                        nemoc_konec: $('#disease_to_edit').val(),
                        poznamka: $('#disease_note_edit').val(),
                    },
                    beforeSend:function(){ $('#SubmitEditDiseaseForm').text('Aktualizace...'); }, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.diseases_employee_table').DataTable().ajax.reload();
                            var uspechUlozeni;
                            if(odpoved.success != "0"){
                                uspechUlozeni = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong> ' + odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
                            $('.flash-message').html(uspechUlozeni);
                            $('.chyby').hide();
                            $("#EditDiseaseForm").modal('hide');
                        }else if(odpoved.errors) {
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
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
                            var failSave = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>';
                            $('.flash-message').html(failSave); // naplneni elementu div obsahem html, v tomto pripade promennou failSave
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
                            $("#EditDiseaseForm").modal('hide');
                        }
                    }
                });
            });

            /* Modalni okno slouzici pro smazani nemocenskych (po stisknuti tlacitka "Smazat") */
            var nemocenske_id_del;
            $('body').on('click', '#obtainDiseaseDelete', function(){
                /* ziskani id nemocenske skrze tlacitko (data-id atribut)*/
                nemocenske_id_del = $(this).data('id');
            });

            /* Odstraneni nemocenske z databaze */
            $('#SubmitDeleteDisease').click(function() {
                $.ajax({
                    url: "/employee/DiseaseActionsEmployee/"+nemocenske_id_del,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){ $('#SubmitDeleteDisease').text('Mazání...') ;}, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var successHtml = '<div class="alert alert-success">'+
                            '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                        var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</strong></div>';
                        if(odpoved.success === ''){ // zjisteni, zdali doslo k chybe
                            $('.flash-message').html(failHtml);
                        }else{
                            $('.flash-message').html(successHtml);
                        }
                        $('.diseases_employee_table').DataTable().ajax.reload();
                        $('#SubmitDeleteDisease').text('Ano');
                        $("#DeleteDiseaseForm").modal('hide');
                    }
                })
            });


            /* Ziskani ID nemocenske pri stisknuti tlacitka "Zažádat" */
            var id_nemocenske_zazadani;
            $('body').on('click', '#obtainDiseaseApply', function(){
                id_nemocenske_zazadani = $(this).data('id');
            });

            /* Zmena stavu zadosti pri zazadani o nemocenskou (zapis do databaze) */
            $('#SubmitApply').click(function() {
                $.ajax({
                    url: "/employee/disease/apply/"+id_nemocenske_zazadani,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){ $('#SubmitApply').text('Žádání...'); }, // zmena textu pri kliknuti
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.diseases_employee_table').DataTable().ajax.reload();
                            var successZazadani = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitApply').text('Ano');
                            $('.flash-message').html(successZazadani);
                            $('.chyby_apply').hide();
                            $("#ApplyDiseaseForm").modal('hide');
                        } else { // pokud doslo k chybe
                            $('.chyby_apply').show();
                            $('.chyby_apply').html('<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.fail + '</strong></div>');
                            $('#SubmitApply').text('Ano');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske pri zmacknuti tlacitka "Zrušit žádost" */
            var id_nemocenske_zruseni;
            $('body').on('click', '#obtainDiseaseDeleteApply', function(){
                id_nemocenske_zruseni = $(this).data('id');
            });

            /* Zruseni zadosti o nemocenskou v databazi */
            $('#SubmitDeleteApply').click(function() {
                $.ajax({
                    url: "/employee/disease/deleteApply/"+id_nemocenske_zruseni,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){ $('#SubmitDeleteApply').text('Žádání...'); }, // zobrazeni textu po zakliknuti tlacitka
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.diseases_employee_table').DataTable().ajax.reload();
                            var successZruseni = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitDeleteApply').text('Ano');
                            $('.flash-message').html(successZruseni);
                            $('.chyby_delete_apply').hide();
                            $("#DeleteApplyDiseaseForm").modal('hide');
                        } else { // pri chybe
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

