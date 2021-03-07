@extends('layouts.employee_dashboard')
@section('title') - Zranění @endsection
@section('content')
    <center>
        <br><br>
        <div class="col-lg-10 col-md-10 col-sm-10">
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
            <table class="table-responsive employee_injury_list">
                <thead>
                <tr>
                    <th width="15%">Popis zranění</th>
                    <th width="8%">Datum zranění</th>
                    <th width="8%">Začátek směny</th>
                    <th width="8%">Konec směny</th>
                    <th width="8%">Lokace</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <script type="text/javascript">
        $(document).ready(function() {
            /* Zobrazení datatable */
            $('.employee_injury_list').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: true,
                jQueryUI: true,
                scrollCollapse: true,
                oLanguage: {
                    "sSearch": "",
                },
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidovaná žádná zranění.",
                    paginate: {
                        previous: "Předchozí",
                        next: "Další",
                    }
                },
                bLengthChange: false,
                paging: false,
                bInfo: false,
                order: [[0, "asc"]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('employee_injuries.list') }}",
                columns: [
                    {data: 'injury_description', name: 'injury_description', sClass: 'text-center'},
                    { data: 'injury_date', name: 'injury_date', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    { data: 'shift_start', name: 'shift_start', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    { data: 'shift_end', name: 'shift_end',render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center'},
                    {data: 'shift_place', name: 'shift_place', sClass: 'text-center'}
                ]
            });
        });
    </script>
@endsection


