@extends('layouts.employee_dashboard')
@section('title') - Aktuální směny @endsection
@section('content')
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
            <div class="attendancesuccess text-center">
            </div>
            <div class="attendancesfail text-center">
            </div>
            <div class="flash-message text-center">
            </div>
            <table class="table-responsive employee_current_shift_list">
                <thead>
                <tr>
                    <th width="5%">Začátek</th>
                    <th width="5%">Konec</th>
                    <th width="5%">Lokace</th>
                    <th width="4%">Důležitost</th>
                    <th width="5%">Příchod</th>
                    <th width="5%">Odchod</th>
                    <th width="5%">Status</th>
                    <th width="5%">Akce</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Editace smeny -->
    <div class="modal fade" id="CurrentShiftDetailModal">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:white;">Detail směny</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="CurrentShiftDetailBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Potvrzení checkin smeny -->
    <div id="confirmCheckinModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení příchodu</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete zapsat příchod?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="ok_button" style="color:white;" id="SubmitconfirmCheckin" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Potvrzení checkout smeny -->
    <div id="confirmCheckoutModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení odchodu</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete zapsat odchod?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="ok_button" style="color:white;" id="SubmitconfirmCheckout" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.chyby_add').hide();
            $('.chyby').hide();
            /* Zobrazení datatable */
            var datatable = $('.employee_current_shift_list').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                scrollY: false,
                autoWidth: true,
                jQueryUI: true,
                scrollCollapse: true,
                oLanguage: {
                    "sSearch": "",
                },
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné aktuální směny.",
                    paginate: {
                        previous: "Předchozí",
                        next: "Další",
                    }
                },
                bLengthChange: false,
                paging: false,
                bInfo: false,
                order: [[ 0, "asc" ]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('shifts.getCurrentEmployeeShiftsList') }}",
                columns: [
                    { data: 'shift_start', name: 'shift_start', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    { data: 'shift_end', name: 'shift_end',render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center'},
                    { data: 'shift_place', name: 'shift_place',sClass:'text-center'},
                    { data: 'shift_importance_id', name: 'shift_importance_id',sClass:'text-center'},
                    { data: 'attendance_check_in', name: 'attendance_check_in', render: function(data, type, full, meta){
                            var date = moment(data).format('DD.MM.YYYY HH:mm');
                            if(date === "Invalid date"){
                                return "Nezapsáno";
                            }else{
                                return date;
                            }
                        },sClass:'text-center',},
                    { data: 'attendance_check_out', name: 'attendance_check_out', render: function(data, type, full, meta){
                            var date = moment(data).format('DD.MM.YYYY HH:mm');
                            if(date === "Invalid date"){
                                return "Nezapsáno";
                            }else{
                                return date;
                            }
                        },sClass:'text-center',},
                    { data: 'reason_description', name: 'reason_description',sClass:'text-center'},
                    { data: 'action', name: 'action', orderable: false,searchable: false,sClass:'text-center'},
                ]
            });

            /* Nahled do detailu smeny */
            $('.modelClose').on('click', function(){
                $('#EditShiftCurrentModal').hide();
            });
            var id;
            $('body').on('click', '#getDetailsCurrentShift', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                id = $(this).data('id');
                $.ajax({
                    url: "/employee/currentshiftActions/"+id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#CurrentShiftDetailBody').html(result.html);
                        $('#CurrentShiftDetailModal').show();
                    }
                });
            });

            /* ziskani ID smeny */
            var smena_id;
            $('body').on('click', '#updateCheckinEmployee', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                smena_id = $(this).data('id');
            });

            /* Ulozeni check-in*/
            $('#SubmitconfirmCheckin').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/employee/checkin/update/"+smena_id,
                    method: 'PUT',
                    data: {
                        shift_id: smena_id,
                    },
                    beforeSend:function(){
                        $('#SubmitconfirmCheckin').text('Zapisování...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('#SubmitconfirmCheckin').text('Zapsat');
                        } else {
                            $('.employee_current_shift_list').DataTable().ajax.reload();
                            if(data.success !== undefined){
                                var successHtml = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                                $('.attendancesuccess').html(successHtml);
                            }
                            if(data.fail !== undefined){
                                var failHtml = '<div class="alert alert-danger">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                    '</div>';
                                $('.attendancesfail').html(failHtml);
                            }



                            $('#SubmitconfirmCheckin').text('Zapsat');
                            $("#confirmCheckinModal").modal('hide');
                        }
                    }
                });
            });

            /* ziskani ID smeny */
            $('body').on('click', '#updateCheckoutEmployee', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                smena_id = $(this).data('id');
            });

            /* Ulozeni check-out */
            $('#SubmitconfirmCheckout').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/employee/checkout/update/"+smena_id,
                    method: 'PUT',
                    data: {
                        shift_id: smena_id,
                    },
                    beforeSend:function(){
                        $('#SubmitconfirmCheckout').text('Zapisování...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('#SubmitconfirmCheckout').text('Zapsat');
                        } else {
                            $('.employee_current_shift_list').DataTable().ajax.reload();
                            if(data.success !== undefined){
                                var successHtml = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                                $('.attendancesuccess').html(successHtml);
                            }
                            if(data.fail !== undefined){
                                var failHtml = '<div class="alert alert-danger">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                    '</div>';
                                $('.attendancesfail').html(failHtml);
                            }



                            $('#SubmitconfirmCheckout').text('Zapsat');
                            $("#confirmCheckoutModal").modal('hide');
                        }
                    }
                });
            });


            });
        </script>
@endsection
