@extends('layouts.company_dashboard')
@section('title') - Zranění @endsection
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
            <div class="flash-message text-center">
            </div>
            <table class="table-responsive injury_list">
                <thead>
                <tr>
                    <th width="5%">Fotka</th>
                    <th width="6%">Jméno</th>
                    <th width="6%">Příjmení</th>
                    <th width="15%">Popis zranění</th>
                    <th width="10%">Datum zranění</th>
                    <th width="10%">Začátek směny</th>
                    <th width="10%">Konec směny</th>
                    <th width="10%">Lokace</th>
                    <th width="11%">Akce <button style="float:right;font-weight: 200;" class="btn btn-dark btn-md" type="button"  data-toggle="modal" data-target="#CreateInjuryModal"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Vytvořit</button></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Pridani smeny !-->
    <div class="modal fade" id="CreateInjuryModal" style="color:white;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                         <span class="col-md-12 text-center">
                              <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat nové zranění</h4>
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
                            <label class="col-md-2 text-left">Zaměstnanec(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <select name="employee_id_add" id="employee_id_add" style="color:black;text-align-last: center;" class="form-control ziskatZamestnance" data-dependent="shift_id_add">
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
                            <label class="col-md-2 text-left">Směna(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <select name="shift_id_add" id="shift_id_add" style="color:black;text-align-last: center;" class="form-control ziskatZamestnance">
                                    <option value="">Vyberte směnu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left">Datum zranění(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="injury_date_add" id="injury_date_add" value="{{ old('injury_date_add') }}" autocomplete="injury_date_add" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left">Popis zranění</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                    </div>
                                    <textarea name="injury_note_add" placeholder="Zadejte popis zranění ..." id="injury_note_add" class="form-control" autocomplete="injury_note_add"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" name="button_action" id="SubmitCreateInjury" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat směnu" />
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Editovat zraneni -->
    <div class="modal fade" id="EditInjuryModal">
        <div class="modal-dialog" style="max-width: 950px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:white;">Detail zranění</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>

                    <div id="EditInjuryModalBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitEditInjuryForm">Aktualizovat</button>
                    <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Smazani zraneni -->
    <div id="DeleteInjuryModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení smazání zranění</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete smazat toto zranění?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteInjury" style="color:white;" id="SubmitDeleteInjury" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#CreateInjuryModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#employee_id_add').val('');
                $('#shift_id_add').val('');
                $('#injury_date_add').val('');
                $('#injury_note_add').val('');
            })

            $('#EditInjuryModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('.chyby_add').hide();
            $('.chyby').hide();
            /* Zobrazení datatable */
            var dataTable = $('.injury_list').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: true,
                jQueryUI: true,
                scrollCollapse: true,
                oLanguage: {
                    "sSearch": ""
                },
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "U Vašich zaměstnanců nemáte zaevidovaná žádná zranění.",
                    paginate: {
                        previous: "Předchozí",
                        next: "Další",
                    }
                },
                bLengthChange: false,
                pageLength: 12,
                bInfo: false,
                order: [[1, "asc"]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('injuries.list') }}",
                columns: [
                    {
                        data: 'employee_picture', name: 'employee_picture',
                        render: function (data, type, full, meta) {
                            if (data === null) {
                                return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60' />";
                            }
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + data + " width='60' height='50' style='max-width:100%;height:auto;' />";
                        }, orderable: false
                    },
                    {data: 'employee_name', name: 'employee_name', sClass: 'text-center'},
                    {data: 'employee_surname', name: 'employee_surname', sClass: 'text-center'},
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
                    {data: 'shift_place', name: 'shift_place', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });


            /* Vytvoreni zraneni */
            $('#SubmitCreateInjury').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('injuriesactions.store') }}",
                    method: 'POST',
                    data: {
                        employee_id_add: $('#employee_id_add').val(),
                        shift_id_add: $('#shift_id_add').val(),
                        injury_date_add: $('#injury_date_add').val(),
                        injury_note_add: $('#injury_note_add').val()
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
                            $('#employee_id_add').val(''),
                                $('#shift_id_add').val(''),
                                $('#injury_date_add').val(''),
                                $('#injury_note_add').val(''),
                                $('.injury_list').DataTable().ajax.reload();
                            var succadd = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ result.success +
                                '</div>';
                            $('.flash-message').html(succadd);
                            $('#CreateInjuryModal').modal('hide');
                        }
                    }
                });
            });


            /* Nahled do detailu zraneni */
            $('.modelClose').on('click', function(){
                $('#EditInjuryModal').hide();
            });
            var id;
            $('body').on('click', '#getEditInjuryData', function(e) {
                id = $(this).data('id');
                $.ajax({
                    url: "injuriesactions/"+id+"/edit",
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#EditInjuryModalBody').html(result.html);
                        $('#EditInjuryModal').show();
                    }
                });
            });

            /* Ulozeni hodnot detailu zraneni */
            $('#SubmitEditInjuryForm').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "injuriesactions/"+id,
                    method: 'PUT',
                    data: {
                        injury_date: $('#injury_date_edit').val(),
                        injury_description: $('#injury_description_edit').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitEditInjuryForm').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('.chyby').show();
                            $('.chyby').html('');
                            $('#SubmitEditInjuryForm').text('Aktualizovat');
                            $.each(data.errors, function(key, value) {
                                $('.chyby').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.injury_list').DataTable().ajax.reload();
                            var succ;
                            if(data.success != "0"){
                                succ = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                            }
                            $('#SubmitEditInjuryForm').text('Aktualizovat');
                            $('.flash-message').html(succ);
                            $('.chyby').hide();
                            $("#EditInjuryModal").modal('hide');
                        }
                    }
                });
            });


            /* Smazani smeny */
            var deleteID;
            $('body').on('click', '#getInjuryDelete', function(){
                deleteID = $(this).data('id');
                $('#DeleteInjuryModal').modal('show');
                $("#DeleteInjuryModal").modal({backdrop: false});
            })
            $('#SubmitDeleteInjury').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "injuriesactions/"+deleteID,
                    method: 'DELETE',
                    beforeSend:function(){
                        $('#SubmitDeleteInjury').text('Mazání...');
                    },
                    success:function(data) {
                        var successHtml = '<div class="alert alert-success">'+
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                            '</div>';
                        $('.flash-message').html(successHtml);
                        setTimeout(function(){
                            $('.injury_list').DataTable().ajax.reload();
                            $('#SubmitDeleteInjury').text('Ano');
                            $("#DeleteInjuryModal").modal('hide');
                        }, 200);
                    }
                })
            });

            $('.ziskatZamestnance').change(function(){
                    var zamestnanec_id = $(this).val();
                    var smeny_select = $(this).data('dependent');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url:"{{ route('injuries.selectShift') }}",
                        method:"POST",
                        data:{
                            employee_id:zamestnanec_id,
                            smeny_select:smeny_select
                        },
                        success:function(result) {
                            $('#'+smeny_select).html(result.html);
                        }
                    })
            });
        });
        </script>

@endsection
