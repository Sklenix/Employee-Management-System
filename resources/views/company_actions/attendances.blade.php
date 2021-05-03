@extends('layouts.company_dashboard')
@section('title') - Docházka @endsection
@section('content')
    <!-- Nazev souboru: attendances.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Docházka" v ramci uctu s roli firmy -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/
    -->
    <div class="col-lg-12">
    <center>
        <br>
        <div class=" col-lg-10 alert alert-info alert-block text-center" style="font-size: 15px;">
            <strong>Vyberte zaměstnance, u kterého chcete vidět souhrn jeho docházek.</strong>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <!-- Usek kodu pro definici chybovych hlasek za pomoci Session -->
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
            <div class="flash-message text-center">
            </div>
            <!-- Usek kodu pro vybrani zamestnance -->
           <div class="form-group">
                <select name="vybrany_zamestnanec" required id="vybrany_zamestnanec" style="color:black" class="form-control input-lg dynamic vybrany_zamestnanec">
                    <option value="-1">Vyberte zaměstnance</option>';
                    @foreach ($zamestnanci as $zamestnanec)
                        <option id="{{$zamestnanec->employee_id}}" value="{{$zamestnanec->employee_id}}">{{$zamestnanec->employee_name}} {{$zamestnanec->employee_surname}}</option>
                    @endforeach
                </select>
           </div>
            <!-- Definice tabulky -->
            <table class="table_attendances">
                <thead>
                <tr>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Začátek</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Konec</th>
                    <th width="8%" style="padding-bottom: 20px;padding-top: 20px;">Lokace</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Důležitost</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Přišla/Přišel</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Status</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Příchod firmy</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Odchod firmy</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Příchod zaměstnance</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Odchod zaměstnance</th>
                    <th width="9%" style="padding-bottom: 20px;padding-top: 20px;">Odpracováno</th>
                    <th width="2%" style="padding-bottom: 20px;padding-top: 20px;">Akce</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>
    </div>

    <!-- Definice modalnich oken -->

    <!-- Nabidka moznosti dochazky -->
    <div class="modal fade" id="ShowAttendanceOptionsForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Možnosti docházky</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="attendancesuccess text-center">
                    </div>
                    <div id="AttendanceOptionsContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoZavreniOkna" data-dismiss="modal" style="color:white;">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabidka dochazky - Prichod -->
    <div class="modal fade" id="ShowAttendanceCheckinForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Příchod</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_checkin">
                    </div>
                    <div id="AttendanceCheckinContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" id="SubmitAttendanceCheckin" class="btn tlacitkoPotvrzeniOkna" style="color:white;">Uložit</button>
                    <button type="button" data-dismiss="modal" class="btn tlacitkoZavreniOkna" style="color:white;">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabidka dochazky - Odchod -->
    <div class="modal fade" id="ShowAttendanceCheckoutForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Odchod</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_checkout">
                    </div>
                    <div id="AttendanceCheckoutContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" id="SubmitAttendanceCheckout" class="btn tlacitkoPotvrzeniOkna" style="color:white;">Uložit</button>
                    <button type="button" data-dismiss="modal" class="btn tlacitkoZavreniOkna" style="color:white;">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabidka dochazky - Důvod absence -->
    <div class="modal fade" id="ShowAttendanceAbsenceForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Status</h5>
                </div>
                <div class="modal-body">
                    <div id="AttendanceAbsenceContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" id="SubmitAttendanceAbsence" class="btn tlacitkoPotvrzeniOkna" style="color:white;">Uložit</button>
                    <button type="button" data-dismiss="modal" class="btn tlacitkoZavreniOkna" style="color:white;">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabidka dochazky - Poznámka -->
    <div class="modal fade" id="ShowAttendanceNoteForm" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Poznámka</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_poznamka">
                    </div>
                    <div id="AttendanceNoteContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" id="SubmitAttendanceNote" class="btn tlacitkoPotvrzeniOkna" style="color:white;">Uložit</button>
                    <button type="button" data-dismiss="modal" class="btn tlacitkoZavreniOkna" style="color:white;">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            var id_zamestnance;
            /* K inspiraci prace s datovymi tabulkami slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020,
               pro inspiraci prace  s modalnimi okny (udalosti) slouzil clanek https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event, ktery napsal David Meador v roce 2018 */

            /* Usek kodu starajici se o schovani chybovych hlaseni pri zavreni modalniho okna, inspirace z: https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event */
            $('#ShowAttendanceOptionsForm').on('hidden.bs.modal', function () {
                $('.attendancesuccess').hide();
            })
            $('#ShowAttendanceCheckinForm').on('hidden.bs.modal', function () {
                $('.chyby_checkin').hide();
            })
            $('#ShowAttendanceCheckoutForm').on('hidden.bs.modal', function () {
                $('.chyby_checkout').hide();
            })

            /* Usek kodu pro schovani chybovych a uspesnych hlaseni pri nacteni webove stranky */
            $('.chyby_checkin').hide();
            $('.chyby_checkout').hide();
            $('.attendancesuccess').hide();
            /* Schovani datove tabulky (nejdrive je nutne vybrat zamestnance) */
            $('.table_attendances').hide();

            /* Po zmene vyberu zamestnance se provede vykresleni datove tabulky */
            $("#vybrany_zamestnanec").change(function(){
                id_zamestnance = $(this).find('option:selected').val();
                /* Odstraneni dosavadni datove tabulky */
                if ($.fn.DataTable.isDataTable('.table_attendances')){ // viz https://datatables.net/reference/api/%24.fn.dataTable.isDataTable()
                    $('.table_attendances').DataTable().destroy(); // viz https://datatables.net/reference/api/destroy()
                }
                if(id_zamestnance == -1){ // pokud neni vybran zadny zamestnanec, tak se datova tabulka schova
                    $('.table_attendances').hide();
                }else{
                    $('.table_attendances').show();
                    $('.table_attendances').DataTable({
                        serverSide: true,
                        paging: true,
                        autoWidth: true,
                        pageLength: 15,
                        scrollX: true,
                        oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná docházka."},
                        language: {
                            searchPlaceholder: "Vyhledávání ... ",
                            emptyTable: "U tohoto zaměstnance nemáte zaevidovanou žádnou docházku.",
                            paginate: { previous: "Předchozí", next: "Další"}
                        },
                        bInfo: false,
                        bLengthChange: false,
                        order: [[0, "asc"]],
                        ajax: "/company/attendance/list/"+id_zamestnance, // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                        columns: [ // definice dat
                            { data: 'shift_start', name: 'shift_start', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');},sClass:'text-center'}, // viz https://datatables.net/reference/option/columns.render
                            { data: 'shift_end', name: 'shift_end',render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');},sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                            { data: 'shift_place', name: 'shift_place',sClass:'text-center'},
                            { data: 'shift_importance_id', name: 'shift_importance_id',sClass:'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                            { data: 'attendance_came', name: 'attendance_came',sClass:'text-center'},
                            { data: 'reason_description', name: 'reason_description',sClass:'text-center'},
                            { data: 'attendance_check_in_company', name: 'attendance_check_in_company',render: function(odpoved){
                                  var date = moment(odpoved).format('DD.MM.YYYY HH:mm'); // pokud neni zadan prichod, tak se zobrazi "Nezadáno"
                                    if(date === "Invalid date"){
                                        return "Nezadáno";
                                    }else{
                                        return date;
                                    }
                                },sClass:'text-center'},
                            { data: 'attendance_check_out_company', name: 'attendance_check_out_company',render: function(odpoved){
                                    var date = moment(odpoved).format('DD.MM.YYYY HH:mm'); // pokud neni zadan odchod, tak se zobrazi "Nezadáno"
                                    if(date === "Invalid date"){
                                        return "Nezadáno";
                                    }else{
                                        return date;
                                    }
                                },sClass:'text-center'},
                            { data: 'attendance_check_in', name: 'attendance_check_in',render: function(odpoved){
                                    var date = moment(odpoved).format('DD.MM.YYYY HH:mm'); // pokud neni zadan prichod, tak se zobrazi "Nezadáno"
                                    if(date === "Invalid date"){
                                        return "Nezadáno";
                                    }else{
                                        return date;
                                    }
                                },sClass:'text-center'},
                            { data: 'attendance_check_out', name: 'attendance_check_out',render: function(odpoved){
                                    var date = moment(odpoved).format('DD.MM.YYYY HH:mm'); // pokud neni zadan odchod, tak se zobrazi "Nezadáno"
                                    if(date === "Invalid date"){
                                        return "Nezadáno";
                                    }else{
                                        return date;
                                    }
                                    },sClass:'text-center'},
                            { data: 'hours_total', name: 'hours_total',sClass:'text-center'},
                            { data: 'action', name: 'action', orderable: false,searchable: false,sClass:'text-center'}
                        ]
                    });
                }
            });

            /* Zobrazení moznosti dochazky v modalnim okne */
            var id_dochazky;
            $('body').on('click', '#obtainEmployeeOptions', function() {
                /* Ziskani identifikatoru dochazky pri kliknuti na tlacitko "Docházka" */
                id_dochazky = $(this).data('id');
                $.ajax({
                    url: "/attendance/option/"+id_dochazky+"/"+id_zamestnance, // URL pro zaslani pozadavku
                    method: 'GET',
                    success: function(odpoved) { // zpracovani odpovedi
                        $('#AttendanceOptionsContent').html(odpoved.out); // naplneni modalniho okna obsahem
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
                    success: function(odpoved) {
                        $('#AttendanceCheckinContent').html(odpoved.out); // vyplneni modalniho okna obsahem
                        $('#ShowAttendanceCheckinForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni zapsani prichodu do databaze */
            $('#SubmitAttendanceCheckin').click(function() {
                $.ajax({
                    url: "/shift/attendance/options/checkin/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_in_company: $('#attendance_create_checkin').val(),
                    },
                    beforeSend:function(){$('#SubmitAttendanceCheckin').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko ulozit
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.table_attendances').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>'+ '<strong>' + odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitAttendanceCheckin').text('Uložit');
                            $("#ShowAttendanceCheckinForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitAttendanceCheckin').text('Uložit');
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
                        $('#AttendanceCheckoutContent').html(odpoved.out); // vyplneni modalniho okna obsahem
                        $('#ShowAttendanceCheckoutForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni zapsani odchodu do databaze */
            $('#SubmitAttendanceCheckout').click(function() {
                $.ajax({
                    url: "/shift/attendance/options/checkout/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_out_company: $('#attendance_create_checkout').val(),
                    },
                    beforeSend:function(){$('#SubmitAttendanceCheckout').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko "Uložit"
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.table_attendances').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitAttendanceCheckout').text('Uložit');
                            $("#ShowAttendanceCheckoutForm").modal('hide');  // schovani modalniho okna
                        } else {
                            $('#SubmitAttendanceCheckout').text('Uložit');
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
            $('body').on('click', '#obtainAbsenceReasonAttendance',function() {
                /* Ziskani ID smeny a ID zamestnance */
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({ // zaslani AJAX pozadavku
                    url: "/shift/attendance/options/absence/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(odpoved) { // zpracovani odpovedi
                        $('#AttendanceAbsenceContent').html(odpoved.out); // nastaveni obsahu modalniho okna
                        $('#ShowAttendanceAbsenceForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni vybraneho statusu do databaze */
            $('#SubmitAttendanceAbsence').click(function() {
                $.ajax({
                    url: "/shift/attendance/options/absence/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_absence_reason_id: $('#duvody_absence').val(),
                    },
                    beforeSend:function(){$('#SubmitAttendanceAbsence').text('Aktualizace...');}, // zmena textu pri kliknuti na "Uložit"
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.table_attendances').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky o uspechu
                            $('#SubmitAttendanceAbsence').text('Uložit'); // nastaveni textu
                            $("#ShowAttendanceAbsenceForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitAttendanceAbsence').text('Uložit');
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
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({ // zaslani AJAX pozadavku
                    url: "/shift/attendance/options/note/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#AttendanceNoteContent').html(odpoved.out); // nastaveni obsahu modalniho okna
                        $('#ShowAttendanceNoteForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni poznamky k dochazce do databaze */
            $('#SubmitAttendanceNote').click(function() {
                $.ajax({
                    url: "/shift/attendance/options/note/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { // definice dat
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        poznamka: $('#attendance_note').val(),
                    },
                    beforeSend:function(){$('#SubmitAttendanceNote').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko "Uložit"
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.table_attendances').DataTable().ajax.reload(); // aktualizace datove tabulky
                            var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml); // nastaveni hlasky o uspechu
                            $('.attendancesuccess').show(); // zobrazeni hlasky
                            $('#SubmitAttendanceNote').text('Uložit'); // zmena textu u tlacitka
                            $("#ShowAttendanceNoteForm").modal('hide'); // schovani modalniho okna
                        } else { // pokud poznamka presahla 180 znaku
                            $('#SubmitAttendanceNote').text('Uložit'); // pri chybe nastaveni textu tlacitka na "Uložit"
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
