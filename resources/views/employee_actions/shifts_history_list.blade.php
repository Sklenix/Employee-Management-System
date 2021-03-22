@extends('layouts.employee_dashboard')
@section('title') - Historie směn @endsection
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
            <table class="table-responsive employee_all_shifts_list">
                <thead>
                <tr>
                    <th width="5%">Začátek</th>
                    <th width="5%">Konec</th>
                    <th width="5%">Lokace</th>
                    <th width="4%">Důležitost</th>
                    <th width="5%">Příchod</th>
                    <th width="5%">Odchod</th>
                    <th width="5%" style="padding-bottom: 20px;padding-top: 20px;">Odpracováno</th>
                    <th width="5%">Status</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <script type="text/javascript">
        $(document).ready(function(){

            /* Zobrazení datatable */
            $('.employee_all_shifts_list').DataTable({
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
                    emptyTable: "Nemáte zaevidanou žádnou historii směn.",
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
                ajax: "{{ route('shifts.getAllEmployeeShiftsList') }}",
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
                    { data: 'hours_total', name: 'hours_total',sClass:'text-center'},
                    { data: 'reason_description', name: 'reason_description',sClass:'text-center'},
                ]
            });

        });
    </script>
@endsection

