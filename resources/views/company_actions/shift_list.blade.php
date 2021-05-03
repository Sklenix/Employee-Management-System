@extends('layouts.company_dashboard')
@section('title') - Směny @endsection
@section('content')
    <!-- Nazev souboru: shift_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Seznam směn" v ramci uctu s roli firmy -->
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
            <table class="company_shifts_table">
                <thead>
                    <tr>
                        <th width="7%">Začátek</th>
                        <th width="7%">Konec</th>
                        <th width="7%">Lokace</th>
                        <th width="15%">Poznámka</th>
                        <th width="5%">Důležitost</th>
                        <th width="3%">Obsazeno</th>
                        <th width="10%">Akce <button style="float:right;font-weight: 200;" class="btn btn-dark btn-md" type="button" data-toggle="modal" data-target="#CreateShiftForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

<!-- Modalni okno pro vytvoreni smeny -->
    <div class="modal fade" id="CreateShiftForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content oknoBarvaPozadi">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit novou směnu</h4>
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
                                <label for="shift_start" class="col-md-2 text-left">Začátek směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="shift_start" id="shift_start" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shift_end" class="col-md-2 text-left">Konec směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="shift_end" id="shift_end">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shift_place_add" class="col-md-2 text-left">Místo (<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building"></i></div>
                                        </div>
                                        <input id="shift_place_add" placeholder="Zadejte lokaci směny..." type="text" class="form-control" name="shift_place_add" autocomplete="on">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shiftImportance" class="col-md-2 text-left">Důležitost</label>
                                <div class="col-md-10">
                                    <select name="shiftImportance" id="shiftImportance" style="color:black;text-align-last: center;" class="form-control">
                                        <option value="6" selected>Vyberte důležitost</option>
                                        @foreach($importances as $importance)
                                            <option value="{{$importance->importance_id}}">{{$importance->importance_description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="shift_note" class="col-md-2 text-left">Poznámka</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                        </div>
                                        <textarea name="shift_note" placeholder="Zadejte poznámku ke směně... [maximálně 180 znaků]" id="shift_note" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="submit" id="SubmitCreateShift" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit směnu"/>
                            <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoZavreniOkna" data-dismiss="modal">Zavřít</button>
                        </div>
                    </div>
                </div>
            </div>
     </div>

    <!-- Modalni okno pro editaci smeny -->
    <div class="modal fade" id="EditShiftForm">
        <div class="modal-dialog" style="max-width: 850px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Detail směny</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="ShiftEditContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitEditShift">Aktualizovat</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro smazani smeny -->
    <div id="DeleteShiftForm" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání směny</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete smazat tuto směnu?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" style="color:white;" id="SubmitDeleteShift" class="btn tlacitkoPotvrzeniOkna"  >Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modalni okno pro prirazeni zamestnancu ke smene -->
    <div class="modal fade" id="AssignEmployeeForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 700px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Přiřazení zaměstnanců ke směně</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div id="AssignEmployeeContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitAssignEmployee">Přiřadit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno pro zobrazeni moznosti dochazky -->
    <div class="modal fade" id="ShowAttendanceOptionsForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Možnosti docházky</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info alert-block text-center">
                        <strong>Vyberte zaměstnance, u kterého chcete vyplnit příchod, odchod, status nebo poznámku.</strong>
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

    <!-- Modalni okno pro zobrazeni prichodu v ramci dochazky -->
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

    <!-- Modalni okno pro zobrazeni odchodu v ramci dochazky -->
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

    <!-- Modalni okno pro zobrazeni statusu v ramci dochazky -->
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

    <!-- Modalni okno pro zobrazeni poznamky v ramci dochazky -->
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
                         pro inspiraci prace  s modalnimi okny (udalosti) slouzil clanek https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event, ktery napsal David Meador v roce 2018 */

            /* Usek kodu starajici se o schovani chybovych hlaseni pri zavreni modalniho okna, inspirace z: https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event */
            $('#CreateShiftForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })
            $('#EditShiftForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })
            $('#ShowAttendanceOptionsForm').on('hidden.bs.modal', function () {
                $('.attendancesuccess').hide();
            })
            $('#ShowAttendanceCheckinForm').on('hidden.bs.modal', function () {
                $('.chyby_checkin').hide();
            })
            $('#ShowAttendanceCheckoutForm').on('hidden.bs.modal', function () {
                $('.chyby_checkout').hide();
            })

            /* Usek kodu pro schovani chybovych hlaseni pri nacteni webove stranky */
            $('.chyby_add').hide();
            $('.chyby_checkin').hide();
            $('.chyby_checkout').hide();
            $('.chyby').hide();

            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
           Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
           Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
           K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.*/
            $('.company_shifts_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná směna."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné směny.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[0, "desc"]],
                ajax: "{{ route('shifts.list') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    { data: 'shift_start', name: 'shift_start', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center',}, // viz https://datatables.net/reference/option/columns.render
                    { data: 'shift_end', name: 'shift_end', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                    { data: 'shift_place', name: 'shift_place', sClass:'text-center'},
                    { data: 'shift_note', name: 'shift_note', sClass:'text-center'},
                    { data: 'shift_importance_id', name: 'shift_importance_id', sClass:'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    { data: 'shift_taken', name: 'shift_taken', sClass:'text-center'},
                    { data: 'action', name: 'action', orderable: false,searchable: false, sClass:'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit směnu" dojde k pridani smeny do databaze */
            $('#SubmitCreateShift').click(function(){
                $.ajax({
                    url: "{{ route('shiftsactions.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        zacatek_smeny: $('#shift_start').val(),
                        konec_smeny: $('#shift_end').val(),
                        lokace_smeny: $('#shift_place_add').val(),
                        dulezitost_smeny: $('#shiftImportance').val(),
                        poznamka: $('#shift_note').val()
                    },
                    beforeSend:function(){$('#SubmitCreateVacation').text('Vytváření...');},
                    success: function(odpoved) {
                        if(odpoved.success) {
                            /* Smazani hodnot do puvodniho stavu */
                            $('.chyby_add').hide();
                            $('#shift_start').val('');
                            $('#shift_end').val('');
                            $('#shift_place_add').val('');
                            $('#shift_note').val('');
                            $('.company_shifts_table').DataTable().ajax.reload();
                            /* Definice zpravy o uspechu akce */
                            var successAdd = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.success + '</strong> </div>';
                            $('.flash-message').html(successAdd); // vlozeni do flash-message
                            $('#CreateShiftForm').modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitCreateShift').text('Vytvořit směnu'); // zmena textu na puvodni
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

            /* Nahled do detailu smeny a ziskani id smeny */
            var smena_id;
            $('body').on('click', '#obtainEditShiftData', function() {
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/company/shiftsactions/"+smena_id+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ShiftEditContent').html(odpoved.out);
                        $('#EditShiftForm').show();
                    }
                });
            });

            /* Ulozeni hodnot v detailu smeny do databaze */
            $('#SubmitEditShift').click(function() {
                $.ajax({
                    url: "/company/shiftsactions/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    data: {
                        zacatek_smeny: $('#shift_start_edit').val(),
                        konec_smeny: $('#shift_end_edit').val(),
                        lokace_smeny: $('#shift_place_edit').val(),
                        dulezitost_smeny: $('#shiftImportance_edit').val(),
                        poznamka: $('#shift_note_edit').val(),
                    },
                    beforeSend:function(){$('#SubmitEditShift').text('Aktualizace...');},
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_shifts_table').DataTable().ajax.reload();
                            var successUpdate;
                            if(odpoved.success != "0"){
                                successUpdate = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditShift').text('Aktualizovat'); // zmena textu
                            $('.flash-message').html(successUpdate); // vlozeni hlasky do flash message
                            $("#EditShiftForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitEditShift').text('Aktualizovat');
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


            /* Zobrazeni a ulozeni id smeny pri prirazeni smeny zamestnancum */
            var smena_id_prirazeni;
            $('body').on('click', '#obtainShiftAssigned', function() {
                smena_id_prirazeni = $(this).data('id');
                $.ajax({
                    url: "/company/shifts/assign/" + smena_id_prirazeni,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#AssignEmployeeContent').html(odpoved.out);
                        $('#AssignEmployeeForm').show();
                    }
                });
            });

            /* Ulozeni hodnot prirazeni zamestnancu ke smene do databaze */
            $('#SubmitAssignEmployee').click(function() {
                $.ajax({
                    url: "/company/shifts/assign/edit/" + smena_id_prirazeni,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat k odeslani, v tomto pripade identifikatory jednotlive vybranych zamestnancu
                        employees_ids: $('.shift_employee_assign_id:checked').serialize()
                    },
                    beforeSend:function(){$('#SubmitAssignEmployee').text('Přiřazování...');}, // zmena textu po kliknuti
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.company_shifts_table').DataTable().ajax.reload();
                            successAssign = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('#SubmitAssignEmployee').text('Přiřadit'); // zmena textu
                            $('.flash-message').html(successAssign); // vlozeni hlasky do flash message
                            $("#AssignEmployeeForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitAssignEmployee').text('Přiřadit');
                        }
                    }
                });
            });

            /* Zobrazeni modalniho okna pro smazani smeny a ziskani id smeny */
            var smena_id_smazat;
            $('body').on('click', '#obtainShiftDelete', function(){
                smena_id_smazat = $(this).data('id');
            })

            /* Realizace smazani smeny v databazi */
            $('#SubmitDeleteShift').click(function() {
                $.ajax({
                    url: "/company/shiftsactions/"+smena_id_smazat,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    beforeSend:function(){$('#SubmitDeleteShift').text('Mazání...');}, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                        $('.flash-message').html(successHtml);
                        $('.company_shifts_table').DataTable().ajax.reload(); // refresh datove tabulky
                        $('#SubmitDeleteShift').text('Ano');
                        $("#DeleteShiftForm").modal('hide'); // schovani modalniho okna
                    }
                })
            });

            /* Zobrazení moznosti dochazky ke smene v modalnim okne */
            var smena_id_dochazka;
            $('body').on('click', '#obtainEmployeeOptions', function() {
                /* Ziskani identifikatoru smeny pri kliknuti na tlacitko "Docházka" */
                smena_id_dochazka = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/"+smena_id_dochazka,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#ShowAttendanceOptionsContent').html(odpoved.out); // naplneni modalniho okna obsahem
                        $('#ShowAttendanceOptionsForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Prichod v moznostech dochazky (zobrazeni) */
            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#obtainCheckInShift', function() {
                /* Ziskani ID smeny a ID zamestnance */
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/checkin/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(odpoved) { // zpracovani odpovedi
                        $('#ShowAttendanceCheckinContent').html(odpoved.out);
                        $('#ShowAttendanceCheckinForm').show();
                    }
                });
            });

            /* Ulozeni zapsani prichodu do databaze */
            $('#SubmitShowAttendanceCheckin').click(function() {
                $.ajax({
                    url: "/shift/attendance/options/checkin/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_in_company: $('#attendance_create_checkin').val(),
                    },
                    beforeSend:function(){$('#SubmitShowAttendanceCheckin').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko ulozit
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.company_shifts_table').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitShowAttendanceCheckin').text('Uložit');
                            $("#ShowAttendanceCheckinForm").modal('hide'); // schovani modalniho okna
                        }  else {
                            $('#SubmitShowAttendanceCheckin').text('Uložit');
                            var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.fail + '</strong></div>';
                            $('.chyby_checkin').html(failHtml);
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
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/checkout/"+zamestnanec_id+"/"+smena_id,
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
                    url: "/shift/attendance/options/checkout/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_out_company: $('#attendance_create_checkout').val(),
                    },
                    beforeSend:function(){$('#SubmitShowAttendanceCheckout').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko "Uložit"
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_shifts_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitShowAttendanceCheckout').text('Uložit');
                            $("#ShowAttendanceCheckoutForm").modal('hide'); // schovani modalniho okna
                        }  else {
                            $('#SubmitShowAttendanceCheckout').text('Uložit');
                            var failHtml = '<div class="alert alert-danger">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.fail + '</div>';
                            $('.chyby_checkout').html(failHtml);
                            $('.chyby_checkout').show();
                        }
                    }
                });
            });

            /* Absence v moznostech dochazky (zobrazeni) */
            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#obtainAbsenceReasonAttendance', function() {
                /* Ziskani ID smeny a ID zamestnance */
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/absence/"+zamestnanec_id+"/"+smena_id,
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
                    url: "/shift/attendance/options/absence/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_absence_reason_id: $('#duvody_absence').val(),
                    },
                    beforeSend:function(){$('#SubmitShowAttendanceAbsence').text('Aktualizace...');}, // zmena textu pri kliknuti na "Uložit"
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_shifts_table').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky o uspechu
                            $('#SubmitShowAttendanceAbsence').text('Uložit'); // nastaveni textu
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
            $('body').on('click', '#obtainNoteAttendance', function(e) {
                /* Vymazani a schovani chybove hlasky */
                $('.chyby_poznamka').html('');
                $('.chyby_poznamka').hide();
                /* Ziskani ID smeny a ID zamestnance */
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/note/"+zamestnanec_id+"/"+smena_id,
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
                    url: "/shift/attendance/options/note/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        poznamka: $('#attendance_note').val(),
                    },
                    beforeSend:function(){$('#SubmitShowAttendanceNote').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko "Uložit"
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.company_shifts_table').DataTable().ajax.reload(); // aktualizace datove tabulky
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitShowAttendanceNote').text('Uložit'); // zmena textu u tlacitka
                            $("#ShowAttendanceNoteForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitShowAttendanceNote').text('Uložit'); // pri chybe nastaveni textu tlacitka na "Uložit"
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
