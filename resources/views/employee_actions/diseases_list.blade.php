@extends('layouts.employee_dashboard')
@section('title') - Nemocenské @endsection
@section('content')
    <center>
        <br><br>
        <div class="col-lg-11 col-md-11 col-sm-11">
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
            <table class="table-responsive disease-list">
                <thead>
                <tr>
                    <th width="8%">Od</th>
                    <th width="8%">Do</th>
                    <th width="9%">Název</th>
                    <th width="15%">Poznámka</th>
                    <th width="8%">Aktuálnost</th>
                    <th width="8%">Stav</th>
                    <th width="15%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#CreateDiseaseModal"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Vytvořit</button></th>
                </tr>
                </thead>
            </table>
        </div>
    </center>

    <!-- Pridani nemocenske !-->
    <div class="modal fade" id="CreateDiseaseModal" style="color:white;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                         <span class="col-md-12 text-center">
                              <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat novou nemocenskou</h4>
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
                            <label class="col-md-2 text-left">Název(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input placeholder="Zadejte název nemoci..." type="text" class="form-control" id="nazev_nemoc" name="nazev_nemoc" value="{{ old('nazev_nemoc') }}"  autocomplete="nazev_nemoc">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left">Datum od (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="nemoc_zacatek" id="nemoc_zacatek" value="{{ old('nemoc_zacatek') }}" autocomplete="nemoc_zacatek" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left">Datum do (<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="nemoc_konec" id="nemoc_konec" value="{{ old('nemoc_konec') }}" autocomplete="nemoc_konec" autofocus>
                                </div>
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
                                    <textarea placeholder="Zadejte poznámku k dovolené..." name="poznamka" id="poznamka" class="form-control" autocomplete="poznamka"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" name="button_action" id="SubmitCreateDisease" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat nemocenskou" />
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Editace nemocenske -->
    <div class="modal fade" id="EditDiseaseModal">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:white;">Detail nemocenské</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
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
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitEditDiseaseForm">Aktualizovat</button>
                    <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Smazani nemocenske -->
    <div id="DeleteDiseaseModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení smazání nemocenské</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete smazat tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteDisease" style="color:white;" id="SubmitDeleteDisease" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Zazadat nemocenskou -->
    <div id="ApplyDiseaseModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení žádosti o nemocenskou</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu chcete zažádat o tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitApply" style="color:white;" id="SubmitApply" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Zrusit zadost dovolene -->
    <div id="DeleteApplyDiseaseModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení zrušení žádosti o nemocenskou</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_delete_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete zrušit žádost o tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteApply" style="color:white;" id="SubmitDeleteApply" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#CreateDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#nazev_nemoc').val('');
                $('#nemoc_zacatek').val('');
                $('#nemoc_konec').val('');
                $('#poznamka').val('');
            })

            $('#EditDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#ApplyDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_apply').hide();
            })

            $('#DeleteApplyDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_delete_apply').hide();
            })

            $('.chyby_add').hide();
            $('.chyby').hide();
            $('.chyby_apply').hide();
            $('.chyby_delete_apply').hide();

            /* Zobrazení datatable */
            var dataTable = $('.disease-list').DataTable({
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
                    emptyTable: "Nemáte zaevidované žádné nemocenské.",
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
                ajax: "{{ route('employee_diseases.list') }}",
                columns: [
                    { data: 'disease_from', name: 'disease_from', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    { data: 'disease_to', name: 'disease_to', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    {data: 'disease_name', name: 'disease_name', sClass: 'text-center'},
                    {data: 'disease_note', name: 'disease_note', sClass: 'text-center', orderable: false, searchable: false},
                    {data: 'disease_actuality', name: 'disease_actuality', sClass: 'text-center'},
                    {data: 'disease_state', name: 'disease_state', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });


            /* Vytvoreni nemocenske */
            $('#SubmitCreateDisease').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('DiseaseActionsEmployee.store') }}",
                    method: 'POST',
                    data: {
                        nazev_nemoc: $('#nazev_nemoc').val(),
                        nemoc_zacatek: $('#nemoc_zacatek').val(),
                        nemoc_konec: $('#nemoc_konec').val(),
                        poznamka: $('#poznamka').val(),
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
                            $('#nazev_nemoc').val('');
                            $('#nemoc_zacatek').val('');
                            $('#nemoc_konec').val('');
                            $('#poznamka').val('');
                            $('.disease-list').DataTable().ajax.reload();
                            var succadd = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ result.success +
                                '</div>';
                            $('.flash-message').html(succadd);
                            $('#CreateDiseaseModal').modal('hide');
                        }
                    }
                });
            });

            /* Nahled do detailu nemocenske */
            $('.modelClose').on('click', function(){
                $('#EditDiseaseModal').hide();
            });
            var id;
            $('body').on('click', '#getEditDisease', function(e) {
                id = $(this).data('id');
                $.ajax({
                    url: "/employee/DiseaseActionsEmployee/"+id+"/edit",
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#EditDiseaseModalBody').html(result.html);
                        $('#EditDiseaseModal').show();
                    }
                });
            });

            /* Ulozeni hodnot detailu nemocenske */
            $('#SubmitEditDiseaseForm').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/employee/DiseaseActionsEmployee/"+id,
                    method: 'PUT',
                    data: {
                        disease_name_edit: $('#disease_name_edit').val(),
                        disease_from_edit: $('#disease_from_edit').val(),
                        disease_to_edit: $('#disease_to_edit').val(),
                        disease_note_edit: $('#disease_note_edit').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitEditDiseaseForm').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('.chyby').hide();
                            $('.chyby').html('');
                            var failHtml = '<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>';
                            $('.flash-message').html(failHtml);
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
                            $("#EditDiseaseModal").modal('hide');
                        }else if(data.errors) {
                            $('.chyby').show();
                            $('.chyby').html('');
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
                            $.each(data.errors, function(key, value) {
                                $('.chyby').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.disease-list').DataTable().ajax.reload();
                            var succ;
                            if(data.success != "0"){
                                succ = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                            }
                            $('#SubmitEditDiseaseForm').text('Aktualizovat');
                            $('.flash-message').html(succ);
                            $('.chyby').hide();
                            $("#EditDiseaseModal").modal('hide');
                        }
                    }
                });
            });

            /* Smazani nemocenske */
            var deleteID;
            $('body').on('click', '#getDiseaseDelete', function(){
                deleteID = $(this).data('id');
            })
            $('#SubmitDeleteDisease').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/employee/DiseaseActionsEmployee/"+deleteID,
                    method: 'DELETE',
                    beforeSend:function(){
                        $('#SubmitDeleteDisease').text('Mazání...');
                    },
                    success:function(data) {
                        var successHtml = '<div class="alert alert-success">'+
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                            '</div>';
                        var failHtml = '<div class="alert alert-danger">'+
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                            '</div>';
                        if(data.success === ''){
                            $('.flash-message').html(failHtml);
                        }else{
                            $('.flash-message').html(successHtml);
                        }
                        $('.disease-list').DataTable().ajax.reload();
                        $('#SubmitDeleteDisease').text('Ano');
                        $("#DeleteDiseaseModal").modal('hide');
                    }
                })
            });

            /* Ziskani ID nemocenske */
            var id;
            $('body').on('click', '#getDiseaseApply', function(){
                id = $(this).data('id');
            })
            /* Zmena stavu zadosti pri zazadani o nemocenskou */
            $('#SubmitApply').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/employee/disease/apply/"+id,
                    method: 'PUT',
                    beforeSend:function(){
                        $('#SubmitApply').text('Žádání...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('.chyby_apply').show();
                            $('.chyby_apply').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>');
                            $('#SubmitApply').text('Ano');
                        } else {
                            $('.disease-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitApply').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_apply').hide();
                            $("#ApplyDiseaseModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske */
            var id;
            $('body').on('click', '#getDiseaseDeleteApply', function(){
                id = $(this).data('id');
            })
            /* Zruseni zadosti o nemocenskou */
            $('#SubmitDeleteApply').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/employee/disease/deleteApply/"+id,
                    method: 'PUT',
                    beforeSend:function(){
                        $('#SubmitDeleteApply').text('Žádání...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('.chyby_delete_apply').show();
                            $('.chyby_delete_apply').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>');
                            $('#SubmitDeleteApply').text('Ano');
                        } else {
                            $('.disease-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitDeleteApply').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_delete_apply').hide();
                            $("#DeleteApplyDiseaseModal").modal('hide');
                        }
                    }
                });
            });
        });
    </script>

@endsection

