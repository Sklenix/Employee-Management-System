@extends('layouts.company_dashboard')
@section('title') - Směny @endsection
@section('content2')
    <center>
        <br><br>
        <div class="col-lg-11 col-md-10 col-sm-10">
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
            <table class="table-responsive shift_list">
                <thead>
                <tr>
                    <th width="7%">Začátek</th>
                    <th width="7%">Konec</th>
                    <th width="7%">Lokace</th>
                    <th width="15%">Poznámka</th>
                    <th width="5%">Důležitost</th>
                    <th width="3%">Obsazeno</th>
                    <th width="10%">Akce <button style="float:right;font-weight: 200;" class="btn btn-dark btn-md" type="button"  data-toggle="modal" data-target="#CreateShiftModal"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Vytvořit</button></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

<!-- Pridani smeny !-->
    <div class="modal fade" id="CreateShiftModal" style="color:white;">
        <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                         <span class="col-md-12 text-center">
                              <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat novou směnu</h4>
                         </span>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger chyby_add" role="alert">
                        </div>
                        <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                            Položky označené (<span style="color:red;">*</span>) jsou povinné.
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Začátek směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="shift_start" id="shift_start" value="{{ old('shift_start') }}" autocomplete="shift_start" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Konec směny(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="shift_end" id="shift_end" value="{{ old('shift_end') }}" autocomplete="shift_end" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Místo(<span class="text-danger">*</span>)</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-building" aria-hidden="true"></i></div>
                                        </div>
                                        <input id="shift_place_add" placeholder="Zadejte lokaci směny..." type="text" class="form-control" name="shift_place_add" value="{{ old('shift_place') }}"  autocomplete="shift_place">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 text-left">Důležitost</label>
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
                                <label class="col-md-2 text-left">Poznámka</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                        </div>
                                        <textarea name="shift_note" placeholder="Zadejte poznámku ke směně..." id="shift_note" class="form-control" value="{{ old('shift_note') }}"  autocomplete="shift_note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="submit" name="button_action" id="SubmitCreateShift" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat směnu" />
                            <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                        </div>
                    </div>
                </div>
            </div>
     </div>

    <!-- Editace smeny -->
    <div class="modal fade" id="EditShiftModal">
        <div class="modal-dialog" style="max-width: 850px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:white;">Detail směny</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="EditShiftBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitEditShift">Aktualizovat</button>
                    <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Smazani smeny -->
    <div id="deleteShiftModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení smazání směny</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete smazat tuto směnu?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="ok_button" style="color:white;" id="SubmitDeleteShift" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Prirazeni zamestnancu ke smene -->
    <div class="modal fade" id="AssignEmployeeModal" style="color:white;">
        <div class="modal-dialog" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Přiřazení zaměstnanců ke směně</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="AssignEmployeeBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitAssignEmployee">Přiřadit</button>
                    <button type="button" class="btn btn-modalClose assignClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
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
                    <div class="alert alert-info alert-block text-center">
                        <strong>Vyberte zaměstnance, u kterého chcete vyplnit check-in, check-out, status, nebo poznámku.</strong>
                    </div>
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
            $('#CreateShiftModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#shift_start').val('');
                $('#shift_end').val('');
                $('#shift_place_add').val('');
                $('#shift_note').val('');
            })

            $('#EditShiftModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('.chyby_add').hide();
            $('.chyby').hide();
            /* Zobrazení datatable */
            var datatable = $('.shift_list').DataTable({
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
                    emptyTable: "Nemáte zaevidované žádné směny.",
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
                ajax: "{{ route('shifts.list') }}",
                columns: [
                    { data: 'shift_start', name: 'shift_start', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    { data: 'shift_end', name: 'shift_end',render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center'},
                    { data: 'shift_place', name: 'shift_place',sClass:'text-center'},
                    { data: 'shift_note', name: 'shift_note',sClass:'text-center'},
                    { data: 'shift_importance_id', name: 'shift_importance_id',sClass:'text-center'},
                    { data: 'shift_taken', name: 'shift_taken',sClass:'text-center'},
                    { data: 'action', name: 'action', orderable: false,searchable: false,sClass:'text-center'},
                ]
            });


            /* Vytvoreni smeny */
            $('#SubmitCreateShift').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('shiftsactions.store') }}",
                    method: 'POST',
                    data: {
                        shift_start: $('#shift_start').val(),
                        shift_end: $('#shift_end').val(),
                        shift_place: $('#shift_place_add').val(),
                        shift_importance_id: $('#shiftImportance').val(),
                        shift_note: $('#shift_note').val()
                    },
                    success: function(result) {
                        if(result.errors) {
                            $('.chyby_add').html('');
                            $.each(result.errors, function(key, value) {
                                $('.chyby_add').show();
                                $('.chyby_add').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.chyby_add').hide();
                            $('#shift_start').val(''),
                            $('#shift_end').val(''),
                            $('#shift_place_add').val(''),
                            $('#shift_note').val(''),
                            $('.shift_list').DataTable().ajax.reload();
                            var succadd = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ result.success +
                                '</div>';
                            $('.flash-message').html(succadd);
                            $('#CreateShiftModal').modal('hide');
                        }
                    }
                });
            });

            /* Nahled do detailu smeny */
            $('.modelClose').on('click', function(){
                $('#EditShiftModal').hide();
            });
            var id;
            $('body').on('click', '#getEditShiftData', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                id = $(this).data('id');
                $.ajax({
                    url: "/company/shiftsactions/"+id+"/edit",
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#EditShiftBody').html(result.html);
                        $('#EditShiftModal').show();
                    }
                });
            });

            /* Aktualizace detailu smeny */
            $('#SubmitEditShift').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/shiftsactions/"+id,
                    method: 'PUT',
                    data: {
                        shift_start: $('#shift_start_edit').val(),
                        shift_end: $('#shift_end_edit').val(),
                        shift_place: $('#shift_place_edit').val(),
                        shift_importance_id: $('#shiftImportance_edit').val(),
                        shift_note: $('#shift_note_edit').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitEditShift').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('.chyby').show();
                            $('.chyby').html('');
                            $('#SubmitEditShift').text('Aktualizovat');
                            $.each(data.errors, function(key, value) {
                                $('.chyby').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.shift_list').DataTable().ajax.reload();
                            var succ;
                            if(data.success != "0"){
                                succ = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                            }
                            $('#SubmitEditShift').text('Aktualizovat');
                            $('.flash-message').html(succ);
                            $("#EditShiftModal").modal('hide');
                        }
                    }
                });
            });

            /* Prirazeni zamestnancu ke smene */
            $('.assignClose').on('click', function(){
                $('#AssignEmployeeModal').hide();
            });
            var id;
            $('body').on('click', '#getShiftAssign', function(e) {
                id = $(this).data('id');
                $.ajax({
                    url: "/company/shifts/assign/" + id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#AssignEmployeeBody').html(result.html);
                        $('#AssignEmployeeModal').show();
                    }
                });
            });

            /* Ulozeni hodnot prirazeni zamestnancu ke smene do databaze */
            $('#SubmitAssignEmployee').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/shifts/assign/edit/" + id,
                    method: 'PUT',
                    data: {
                        employees_ids: $('.shift_employee_assign_id:checked').serialize()
                    },
                    beforeSend:function(){
                        $('#SubmitAssignEmployee').text('Přiřazování...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('#SubmitAssignEmployee').text('Přiřadit');
                        } else {
                            $('.shift_list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                            $('#SubmitAssignEmployee').text('Přiřadit');
                            $('.flash-message').html(succ);
                            $("#AssignEmployeeModal").modal('hide');
                        }
                    }
                });
            });

            /* Smazani smeny */
            var deleteID;
            $('body').on('click', '#getShiftID', function(){
                deleteID = $(this).data('id');
                $('#deleteShiftModal').modal('show');
                $("#deleteShiftModal").modal({backdrop: false});
            })
            $('#SubmitDeleteShift').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/shiftsactions/"+deleteID,
                    method: 'DELETE',
                    beforeSend:function(){
                        $('#SubmitDeleteShift').text('Mazání...');
                    },
                    success:function(data) {
                        var successHtml = '<div class="alert alert-success">'+
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                            '</div>';

                        $('.flash-message').html(successHtml);
                        setTimeout(function(){
                            $('.shift_list').DataTable().ajax.reload();
                            $('#SubmitDeleteShift').text('Ano');
                            $("#deleteShiftModal").modal('hide');
                        }, 200);
                    }
                })
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
                    url: "/shift/attendance/options/"+id,
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
                        if(data.errors) {
                            $('#SubmitShowAttendanceCheckin').text('Uložit');
                        } else {
                            $('.shift_list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
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
                        if(data.errors) {
                            $('#SubmitShowAttendanceCheckout').text('Uložit');
                        } else {
                            $('.shift_list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
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
                            $('.shift_list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
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
                            $('.shift_list').DataTable().ajax.reload();
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';

                            $('.attendancesuccess').html(successHtml);
                            $('#SubmitShowAttendanceNote').text('Uložit');

                            $("#ShowAttendanceNoteModal").modal('hide');
                        }
                    }
                });
            });
        });
    </script>
@endsection
