@extends('layouts.company_dashboard')
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
                    <th width="1%">Fotka</th>
                    <th width="8%">Jméno</th>
                    <th width="8%">Příjmení</th>
                    <th width="8%">Od</th>
                    <th width="8%">Do</th>
                    <th width="9%">Název</th>
                    <th width="8%">Aktuálnost</th>
                    <th width="8%">Stav</th>
                    <th width="15%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#CreateDiseaseModal"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Vytvořit</button></th>
                </tr>
                </thead>
                <tbody></tbody>
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
                            <label class="col-md-2 text-left" style="font-size: 15px;">Zaměstnanec(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <select name="zamestnanec_vyber" id="zamestnanec_vyber" style="color:black;text-align-last: center;" class="form-control">
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
                            <label class="col-md-2 text-left" style="font-size: 15px;">Název(<span class="text-danger">*</span>)</label>
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
                            <label class="col-md-2 text-left" style="font-size: 15px;">Datum od (<span class="text-danger">*</span>)</label>
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
                            <label class="col-md-2 text-left" style="font-size: 15px;">Datum do (<span class="text-danger">*</span>)</label>
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
                            <label class="col-md-2 text-left" style="font-size: 15px;">Poznámka</label>
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

    <!-- Schvalit nemocenskou -->
    <div id="AgreementDiseaseModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schválení žádosti o nemocenskou</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_aggree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu chcete schválit žádost o tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitAgreement" style="color:white;" id="SubmitAgreement" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Neschvalit nemocenskou -->
    <div id="DisagreementDiseaseModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Neschválení žádosti o nemocenskou</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_disagree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete neschválit žádost o tuto nemocenskou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDisagreement" style="color:white;" id="SubmitDisagreement" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stav odeslano -->
    <div id="SentDiseaseModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení vrácení žádosti do stavu "Odesláno"</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_sent" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete vrátit tuto žádost do výchozího stavu (odesláno)?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSent" style="color:white;" id="SubmitSent" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stav precteno -->
    <div id="SeenDiseaseModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení přečtení žádosti o nemocenskou</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_seen" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete označit tuto žádost jako žádost přečtenou?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSeen" style="color:white;" id="SubmitSeen" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#CreateDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#zamestnanec_vyber').val('');
                $('#nazev_nemoc').val('');
                $('#nemoc_zacatek').val('');
                $('#nemoc_konec').val('');
                $('#poznamka').val('');
            })

            $('#EditDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#AgreementDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_aggree').hide();
            })

            $('#DisagreementDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_disagree').hide();
            })

            $('#SeenDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_seen').hide();
            })

            $('#SentDiseaseModal').on('hidden.bs.modal', function () {
                $('.chyby_sent').hide();
            })

            $('.chyby_add').hide();
            $('.chyby').hide();
            $('.chyby_aggree').hide();
            $('.chyby_disagree').hide();
            $('.chyby_seen').hide();
            $('.chyby_sent').hide();

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
                    emptyTable: "Nemáte zaevidované žádné nemocenské zaměstnanců.",
                    paginate: {
                        previous: "Předchozí",
                        next: "Další",
                    }
                },
                bLengthChange: false,
                paging: false,
                bInfo: false,
                order: [[3, "asc"]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('diseases.list') }}",
                columns: [
                    { data: 'employee_picture', name: 'employee_picture',
                        render: function(data, type, full, meta){
                            if(data === null){
                                return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60' />";
                            }
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + data + " width='60' height='50' style='max-width:100%;height:auto;' />";
                        }, orderable: false},
                    { data: 'employee_name', name: 'employee_name',sClass:'text-center'},
                    { data: 'employee_surname', name: 'employee_surname',sClass:'text-center'},
                    { data: 'disease_from', name: 'disease_from', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    { data: 'disease_to', name: 'disease_to', render: function(data, type, full, meta){
                            return moment(data).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center',},
                    {data: 'disease_name', name: 'disease_name', sClass: 'text-center'},
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
                    url: "{{ route('DiseaseActions.store') }}",
                    method: 'POST',
                    data: {
                        zamestnanec_vyber: $('#zamestnanec_vyber').val(),
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
                    url: "/company/employees/DiseaseActions/"+id+"/edit",
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
                    url: "/company/employees/DiseaseActions/"+id,
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
                        if(data.errors) {
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
                    url: "/company/employees/DiseaseActions/"+deleteID,
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
            $('body').on('click', '#getDiseaseAgreement', function(){
                id = $(this).data('id');
            })
            /* Schvaleni zadosti o nemocenskou */
            $('#SubmitAgreement').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/disease/agreed/"+id,
                    method: 'PUT',
                    beforeSend:function(){
                        $('#SubmitAgreement').text('Schvalování...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('.chyby_aggree').show();
                            $('.chyby_aggree').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>');
                            $('#SubmitAgreement').text('Ano');
                        } else {
                            $('.disease-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitAgreement').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_aggree').hide();
                            $("#AgreementDiseaseModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske */
            var id;
            $('body').on('click', '#getDiseaseDisagreement', function(){
                id = $(this).data('id');
            })
            /* Neschvaleni zadosti o nemocenskou */
            $('#SubmitDisagreement').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/disease/disagreed/"+id,
                    method: 'PUT',
                    beforeSend:function(){
                        $('#SubmitDisagreement').text('Neschvalování...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('.chyby_disagree').show();
                            $('.chyby_disagree').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>');
                            $('#SubmitDisagreement').text('Ano');
                        } else {
                            $('.disease-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitDisagreement').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_disagree').hide();
                            $("#DisagreementDiseaseModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske */
            var id;
            $('body').on('click', '#getDiseaseSeen', function(){
                id = $(this).data('id');
            })
            /* Stav precteni */
            $('#SubmitSeen').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/disease/seen/"+id,
                    method: 'PUT',
                    beforeSend:function(){
                        $('#SubmitSeen').text('Přečtení...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('.chyby_seen').show();
                            $('.chyby_seen').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>');
                            $('#SubmitSeen').text('Ano');
                        } else {
                            $('.disease-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitSeen').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_seen').hide();
                            $("#SeenDiseaseModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske */
            var id;
            $('body').on('click', '#getDiseaseSent', function(){
                id = $(this).data('id');
            })
            /* Stav odeslani */
            $('#SubmitSent').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/disease/sent/"+id,
                    method: 'PUT',
                    beforeSend:function(){
                        $('#SubmitSent').text('Odeslání...');
                    },
                    success: function(data) {
                        if(data.fail) {
                            $('.chyby_sent').show();
                            $('.chyby_sent').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>');
                            $('#SubmitSent').text('Ano');
                        } else {
                            $('.disease-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitSent').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_sent').hide();
                            $("#SentDiseaseModal").modal('hide');
                        }
                    }
                });
            });
        });
    </script>

@endsection

