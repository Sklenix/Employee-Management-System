@extends('layouts.company_dashboard')
@section('title') - Docházka @endsection
@section('content')
    <div class="col-lg-12">
    <center>
        <br>
        <div class=" col-lg-10 alert alert-info alert-block text-center" style="font-size: 15px;">
            <strong>Vyberte zaměstnance, u kterého chcete vidět souhrn jeho docházek.</strong>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$message}}</strong>
                </div>
            @endif
            @if($message = Session::get('fail'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$message}}</strong>
                </div>
            @endif
            <div class="flash-message text-center">
            </div>
           <div class="form-group">
                <select name="vybrany_zamestnanec" required id="vybrany_zamestnanec" style="color:black" class="form-control input-lg dynamic vybrany_zamestnanec" data-dependent="state">
                    <option value="-1">Vyberte zaměstnance</option>';
                    @foreach ($zamestnanci as $zamestnanec)
                        <option id="{{$zamestnanec->employee_id}}" value="{{$zamestnanec->employee_id}}">{{$zamestnanec->employee_name}} {{$zamestnanec->employee_surname}}</option>
                    @endforeach
                </select>
           </div>

            <table class="table-responsive attendance-list">
                <thead>
                <tr>
                    <th width="7%" style="padding-bottom: 20px;padding-top: 20px;">Začátek</th>
                    <th width="7%" style="padding-bottom: 20px;padding-top: 20px;">Konec</th>
                    <th width="7%" style="padding-bottom: 20px;padding-top: 20px;">Lokace</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Důležitost</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Přišla/Přišel</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Status</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Check-in firmy</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Check-out firmy</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Check-in zaměstnance</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Check-out zaměstnance</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Odpracováno</th>
                    <th width="1%" style="padding-bottom: 20px;padding-top: 20px;">Akce</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>
    </div>
    <!-- Nabídka docházky -->
    <div class="modal fade" id="ShowAttendanceOptionsModal" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Možnosti docházky</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="attendancesuccess text-center">
                    </div>
                    <div id="ShowAttendanceOptionsBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalClose assignClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabídka docházky - Check-in -->
    <div class="modal fade" id="ShowAttendanceCheckinModal" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Check-in</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_checkin">
                    </div>
                    <div id="ShowAttendanceCheckinBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitShowAttendanceCheckin">Uložit</button>
                    <button type="button" class="btn btn-modalClose assignClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabídka docházky - Check-out -->
    <div class="modal fade" id="ShowAttendanceCheckoutModal" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Check-out</h5>
                </div>
                <div class="modal-body">
                    <div class="chyby_checkout">
                    </div>
                    <div id="ShowAttendanceCheckoutBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitShowAttendanceCheckout">Uložit</button>
                    <button type="button" class="btn btn-modalClose assignClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabídka docházky - Důvod absence -->
    <div class="modal fade" id="ShowAttendanceAbsenceModal" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Status</h5>
                </div>
                <div class="modal-body">
                    <div id="ShowAttendanceAbsenceBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitShowAttendanceAbsence">Uložit</button>
                    <button type="button" class="btn btn-modalClose assignClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nabídka docházky - Poznámka -->
    <div class="modal fade" id="ShowAttendanceNoteModal" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Poznámka</h5>
                </div>
                <div class="modal-body">
                    <div id="ShowAttendanceNoteBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitShowAttendanceNote">Uložit</button>
                    <button type="button" class="btn btn-modalClose assignClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            var datatable;
            var value;

            $('#ShowAttendanceOptionsModal').on('hidden.bs.modal', function () {
                $('.attendancesuccess').hide();
            })

            $('#ShowAttendanceCheckinModal').on('hidden.bs.modal', function () {
                $('.chyby_checkin').hide();
            })

            $('#ShowAttendanceCheckoutModal').on('hidden.bs.modal', function () {
                $('.chyby_checkout').hide();
            })

            $('.chyby_checkin').hide();
            $('.chyby_checkout').hide();

            $('.attendance-list').hide();
            $("#vybrany_zamestnanec").change(function(){
                var $option = $(this).find('option:selected');
                value = $option.val();

                if ( $.fn.DataTable.isDataTable('.attendance-list') ) {
                    $('.attendance-list').DataTable().destroy();
                }
                if(value == -1){
                    $('.attendance-list').hide();
                }else{
                    $('.attendance-list').show();
                    datatable = $('.attendance-list').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        scrollX: true,
                        scrollY: false,
                        autoWidth: true,
                        jQueryUI: true,
                        scrollCollapse: true,
                        oLanguage: {
                            "sSearch": ""
                        },
                        language: {
                            searchPlaceholder: "Vyhledávání ... ",
                            emptyTable: "U tohoto zaměstnance nemáte zaevidované žádné směny.",
                            paginate: {
                                previous: "Předchozí",
                                next: "Další",
                            }
                        },
                        bLengthChange: false,
                        pageLength: 15,
                        bInfo: false,
                        order: [[ 0, "asc" ]],
                        dom: '<"pull-left"f><"pull-right"l>tip',
                        ajax: "/company/attendance/list/"+value,
                        columns: [
                            { data: 'shift_start', name: 'shift_start', render: function(data, type, full, meta){
                                    return moment(data).format('DD.MM.YYYY HH:mm');
                                },sClass:'text-center'},
                            { data: 'shift_end', name: 'shift_end',render: function(data, type, full, meta){
                                    return moment(data).format('DD.MM.YYYY HH:mm');
                                },sClass:'text-center'},
                            { data: 'shift_place', name: 'shift_place',sClass:'text-center'},
                            { data: 'shift_importance_id', name: 'shift_importance_id',sClass:'text-center'},
                            { data: 'attendance_came', name: 'attendance_came',sClass:'text-center'},
                            { data: 'reason_description', name: 'reason_description',sClass:'text-center'},
                            { data: 'attendance_check_in_company', name: 'attendance_check_in_company',render: function(data, type, full, meta){
                                  var date = moment(data).format('DD.MM.YYYY HH:mm');
                                    if(date === "Invalid date"){
                                        return "Nezadáno";
                                    }else{
                                        return date;
                                    }

                                },sClass:'text-center'},
                            { data: 'attendance_check_out_company', name: 'attendance_check_out_company',render: function(data, type, full, meta){
                                    var date = moment(data).format('DD.MM.YYYY HH:mm');
                                    if(date === "Invalid date"){
                                        return "Nezadáno";
                                    }else{
                                        return date;
                                    }

                                },sClass:'text-center'},
                            { data: 'attendance_check_in', name: 'attendance_check_in',render: function(data, type, full, meta){
                                    var date = moment(data).format('DD.MM.YYYY HH:mm');
                                    if(date === "Invalid date"){
                                        return "Nezadáno";
                                    }else{
                                        return date;
                                    }

                                },sClass:'text-center'},
                            { data: 'attendance_check_out', name: 'attendance_check_out',render: function(data, type, full, meta){
                                    var date = moment(data).format('DD.MM.YYYY HH:mm');
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

            /* Zobrazení možností docházky */
            $('.modelClose').on('click', function(){
                $('#ShowAttendanceOptionsModal').hide();
            });
            var id;
            $('body').on('click', '#getEmployeesOptions', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                id = $(this).data('id');
                $.ajax({
                    url: "/attendance/option/"+id+"/"+value,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#ShowAttendanceOptionsBody').html(result.html);
                        $('#ShowAttendanceOptionsModal').show();
                    }
                });
            });

            /* Check-in v možnostech docházky(zobrazení) */
            $('.modelClose').on('click', function(){
                $('#ShowAttendanceCheckinModal').hide();
            });

            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#getCheckInShift', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/checkin/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#ShowAttendanceCheckinBody').html(result.html);
                        $('#ShowAttendanceCheckinModal').show();
                    }
                });
            });

            /* Ulozeni check-in*/
            $('#SubmitShowAttendanceCheckin').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/shift/attendance/options/checkin/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_in_company: $('#attendance_create_checkin').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitShowAttendanceCheckin').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('#SubmitShowAttendanceCheckin').text('Uložit');
                            var failHtml = '<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>';
                            $('.chyby_checkin').html(failHtml);
                            $('.chyby_checkin').show();
                        } else {
                            $('.attendance-list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
                            $('.attendancesuccess').show();
                            $('#SubmitShowAttendanceCheckin').text('Uložit');
                            $("#ShowAttendanceCheckinModal").modal('hide');
                        }
                    }
                });
            });

            /* Check-out v možnostech docházky(zobrazení) */
            $('.modelClose').on('click', function(){
                $('#ShowAttendanceCheckoutModal').hide();
            });

            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#getCheckOutShift', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/checkout/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#ShowAttendanceCheckoutBody').html(result.html);
                        $('#ShowAttendanceCheckoutModal').show();
                    }
                });
            });

            /* Ulozeni check-out*/
            $('#SubmitShowAttendanceCheckout').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/shift/attendance/options/checkout/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_check_out_company: $('#attendance_create_checkout').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitShowAttendanceCheckout').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('#SubmitShowAttendanceCheckout').text('Uložit');
                            var failHtml = '<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>';
                            $('.chyby_checkout').html(failHtml);
                            $('.chyby_checkout').show();
                        }  else {
                            $('.attendance-list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
                            $('.attendancesuccess').show();
                            $('#SubmitShowAttendanceCheckout').text('Uložit');
                            $("#ShowAttendanceCheckoutModal").modal('hide');
                        }
                    }
                });
            });

            /* Absence v možnostech docházky(zobrazení) */
            $('.modelClose').on('click', function(){
                $('#ShowAttendanceAbsenceModal').hide();
            });

            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#getAbsenceReasonAttendance', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/absence/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#ShowAttendanceAbsenceBody').html(result.html);
                        $('#ShowAttendanceAbsenceModal').show();
                    }
                });
            });

            /* Ulozeni absence do databaze */
            $('#SubmitShowAttendanceAbsence').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/shift/attendance/options/absence/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_absence_reason_id: $('#duvody_absence').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitShowAttendanceAbsence').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('#SubmitShowAttendanceAbsence').text('Uložit');
                        } else {
                            $('.attendance-list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
                            $('.attendancesuccess').show();
                            $('#SubmitShowAttendanceAbsence').text('Uložit');
                            $("#ShowAttendanceAbsenceModal").modal('hide');
                        }
                    }
                });
            });

            /* Poznámka v možnostech docházky(zobrazení) */
            $('.modelClose').on('click', function(){
                $('#ShowAttendanceNoteModal').hide();
            });

            var zamestnanec_id;
            var smena_id;
            $('body').on('click', '#getNoteAttendance', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                zamestnanec_id = $('#vybrany_zamestnanec option:selected').attr('id');
                smena_id = $(this).data('id');
                $.ajax({
                    url: "/shift/attendance/options/note/"+zamestnanec_id+"/"+smena_id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#ShowAttendanceNoteBody').html(result.html);
                        $('#ShowAttendanceNoteModal').show();
                    }
                });
            });

            /* Ulozeni poznámky do databaze */
            $('#SubmitShowAttendanceNote').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/shift/attendance/options/note/update/"+zamestnanec_id+"/"+smena_id,
                    method: 'PUT',
                    data: {
                        employee_id: zamestnanec_id,
                        shift_id: smena_id,
                        attendance_note: $('#attendance_note').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitShowAttendanceNote').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('#SubmitShowAttendanceNote').text('Uložit');
                        } else {
                            $('.attendance-list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
                            $('.attendancesuccess').show();
                            $('#SubmitShowAttendanceNote').text('Uložit');
                            $("#ShowAttendanceNoteModal").modal('hide');
                        }
                    }
                });
            });

        });
    </script>
@endsection
