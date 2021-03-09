@extends('layouts.company_dashboard')
@section('title') - Nahlášení @endsection
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
            <table class="table-responsive report-list">
                <thead>
                <tr>
                    <th width="1%">Fotka</th>
                    <th width="8%">Jméno</th>
                    <th width="8%">Příjmení</th>
                    <th width="8%">Název</th>
                    <th width="15%">Popis</th>
                    <th width="9%">Důležitost</th>
                    <th width="8%">Stav</th>
                    <th width="15%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#CreateReportModal"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Vytvořit</button></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Pridani nahlaseni !-->
    <div class="modal fade" id="CreateReportModal" style="color:white;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                         <span class="col-md-12 text-center">
                              <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat nové nahlášení</h4>
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
                            <label class="col-md-2 text-left" style="font-size: 15px;">Nadpis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input placeholder="Zadejte, čeho se nahlášení týká..." type="text" class="form-control" id="nazev_nahlaseni" name="nazev_nahlaseni" value="{{ old('nazev_nahlaseni') }}"  autocomplete="nazev_nahlaseni">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left" style="font-size: 15px;">Popis(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                    </div>
                                    <textarea placeholder="Zadejte popis nahlášení..." name="popis_nahlaseni" id="popis_nahlaseni" class="form-control" autocomplete="popis_nahlaseni"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left" style="font-size: 15px;">Důležitost</label>
                            <div class="col-md-10">
                                <select name="dulezitost_nahlaseni" id="dulezitost_nahlaseni" style="color:black;text-align-last: center;" class="form-control" data-dependent="state">
                                    <option value="">Vyberte důležitost</option>
                                    @foreach($dulezitosti as $dulezitost)
                                        <option value="{{$dulezitost->importance_report_id}}">{{$dulezitost->importance_report_description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" name="button_action" id="SubmitCreateReport" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalSuccess" value="Přidat nahlášení" />
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Editace nemocenske -->
    <div class="modal fade" id="EditReportModal">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:white;">Detail nahlášení</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="EditReportModalBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitEditReportForm">Aktualizovat</button>
                    <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Smazani nahlaseni -->
    <div id="DeleteReportModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení smazání nahlášení</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete smazat toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteReport" style="color:white;" id="SubmitDeleteReport" class="btn btn-modalSuccess">Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schvalit nahlaseni -->
    <div id="AgreementReportModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schválení nahlášení</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_aggree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu chcete schválit toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitAgreement" style="color:white;" id="SubmitAgreement" class="btn btn-modalSuccess">Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Neschvalit nahlaseni -->
    <div id="DisagreementReportModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Neschválení nahlášení</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_disagree" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete neschválit toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDisagreement" style="color:white;" id="SubmitDisagreement" class="btn btn-modalSuccess">Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stav odeslano -->
    <div id="SentReportModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení vrácení nahlášení do stavu "Odesláno"</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_sent" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete vrátit toto nahlášení do výchozího stavu (odesláno)?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitSent" style="color:white;" id="SubmitSent" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stav precteno -->
    <div id="SeenReportModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení přečtení nahlášení</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_seen" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete označit toto nahlášení jako přečtené?</p>
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
            $('#CreateReportModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#nazev_nahlaseni').val('');
                $('#popis_nahlaseni').val('');
                $('#dulezitost_nahlaseni').val('');
            })

            $('#EditReportModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#AgreementReportModal').on('hidden.bs.modal', function () {
                $('.chyby_aggree').hide();
            })

            $('#DisagreementReportModal').on('hidden.bs.modal', function () {
                $('.chyby_disagree').hide();
            })

            $('#SeenReportModal').on('hidden.bs.modal', function () {
                $('.chyby_seen').hide();
            })

            $('#SentReportModal').on('hidden.bs.modal', function () {
                $('.chyby_sent').hide();
            })

            $('.chyby_add').hide();
            $('.chyby').hide();
            $('.chyby_aggree').hide();
            $('.chyby_disagree').hide();
            $('.chyby_seen').hide();
            $('.chyby_sent').hide();

            /* Zobrazení datatable */
            var dataTable = $('.report-list').DataTable({
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
                    emptyTable: "Nemáte zaevidovaná žádná nahlášení zaměstnanců.",
                    paginate: {
                        previous: "Předchozí",
                        next: "Další",
                    }
                },
                bLengthChange: false,
                paging: false,
                bInfo: false,
                order: [[2, "asc"]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('reports.list') }}",
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
                    {data: 'report_title', name: 'report_title', sClass: 'text-center'},
                    {data: 'report_description', name: 'report_description', sClass: 'text-center', orderable: false, searchable: false},
                    {data: 'importance_report_description', name: 'importance_report_description', sClass: 'text-center'},
                    {data: 'report_state', name: 'report_state', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });


            /* Vytvoreni nahlaseni */
            $('#SubmitCreateReport').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('ReportActions.store') }}",
                    method: 'POST',
                    data: {
                        zamestnanec_vyber: $('#zamestnanec_vyber').val(),
                        nazev_nahlaseni: $('#nazev_nahlaseni').val(),
                        popis_nahlaseni: $('#popis_nahlaseni').val(),
                        dulezitost_nahlaseni: $('#dulezitost_nahlaseni').val(),
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
                            $('#zamestnanec_vyber').val('');
                            $('#nazev_nahlaseni').val('');
                            $('#popis_nahlaseni').val('');
                            $('#dulezitost_nahlaseni').val('');
                            $('.report-list').DataTable().ajax.reload();
                            var succadd = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ result.success +
                                '</div>';
                            $('.flash-message').html(succadd);
                            $('#CreateReportModal').modal('hide');
                        }
                    }
                });
            });

            /* Nahled do detailu nahlaseni */
            $('.modelClose').on('click', function(){
                $('#EditDiseaseModal').hide();
            });
            var id;
            $('body').on('click', '#getEditReport', function(e) {
                id = $(this).data('id');
                $.ajax({
                    url: "/company/employees/ReportActions/"+id+"/edit",
                    method: 'GET',
                    success: function(result) {
                        $('#EditReportModalBody').html(result.html);
                        $('#EditReportModal').show();
                    }
                });
            });

            /* Ulozeni hodnot detailu nahlaseni */
            $('#SubmitEditReportForm').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/ReportActions/"+id,
                    method: 'PUT',
                    data: {
                        nazev_nahlaseni_edit: $('#nazev_nahlaseni_edit').val(),
                        popis_nahlaseni_edit: $('#popis_nahlaseni_edit').val(),
                        dulezitost_nahlaseni_edit: $('#dulezitost_nahlaseni_edit').val(),
                    },
                    beforeSend:function(){
                        $('#SubmitEditReportForm').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('.chyby').show();
                            $('.chyby').html('');
                            $('#SubmitEditReportForm').text('Aktualizovat');
                            $.each(data.errors, function(key, value) {
                                $('.chyby').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.report-list').DataTable().ajax.reload();
                            var succ;
                            if(data.success != "0"){
                                succ = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                            }
                            $('#SubmitEditReportForm').text('Aktualizovat');
                            $('.flash-message').html(succ);
                            $('.chyby').hide();
                            $("#EditReportModal").modal('hide');
                        }
                    }
                });
            });

            /* Smazani nahlaseni */
            var deleteID;
            $('body').on('click', '#getReportDelete', function(){
                deleteID = $(this).data('id');
            })
            $('#SubmitDeleteReport').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/ReportActions/"+deleteID,
                    method: 'DELETE',
                    beforeSend:function(){
                        $('#SubmitDeleteReport').text('Mazání...');
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
                        $('.report-list').DataTable().ajax.reload();
                        $('#SubmitDeleteReport').text('Ano');
                        $("#DeleteReportModal").modal('hide');
                    }
                })
            });

            /* Ziskani ID nahlaseni */
            var id;
            $('body').on('click', '#getReportAgreement', function(){
                id = $(this).data('id');
            })
            /* Schvaleni zadosti o nahlaseni */
            $('#SubmitAgreement').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/report/agreed/"+id,
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
                            $('.report-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitAgreement').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_aggree').hide();
                            $("#AgreementReportModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID hlaseni */
            var id;
            $('body').on('click', '#getReportDisagreement', function(){
                id = $(this).data('id');
            })
            /* Neschvaleni zadosti o hlaseni */
            $('#SubmitDisagreement').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/report/disagreed/"+id,
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
                            $('.report-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitDisagreement').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_disagree').hide();
                            $("#DisagreementReportModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID hlaseni */
            var id;
            $('body').on('click', '#getReportSeen', function(){
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
                    url: "/company/employees/report/seen/"+id,
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
                            $('.report-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitSeen').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_seen').hide();
                            $("#SeenReportModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID hlaseni */
            var id;
            $('body').on('click', '#getReportSent', function(){
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
                    url: "/company/employees/report/sent/"+id,
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
                            $('.report-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitSent').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_sent').hide();
                            $("#SentReportModal").modal('hide');
                        }
                    }
                });
            });

        });
    </script>

@endsection


