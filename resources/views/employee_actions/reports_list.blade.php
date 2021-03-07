@extends('layouts.employee_dashboard')
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
                            <label class="col-md-2 text-left">Nadpis(<span class="text-danger">*</span>)</label>
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
                            <label class="col-md-2 text-left">Popis(<span class="text-danger">*</span>)</label>
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
                            <label class="col-md-2 text-left">Důležitost</label>
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

    <!-- Odeslat nahlaseni -->
    <div id="ApplyReportModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení odeslání nahlášení</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu chcete odeslat toto nahlášení?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitApply" style="color:white;" id="SubmitApply" class="btn btn-modalSuccess">Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Zrusit odeslani nahlaseni -->
    <div id="DeleteApplyReportModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení zrušení odeslání nahlášení</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chyby_delete_apply" style="text-decoration: none;">
                    </div>
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete zrušit odeslání tohoto nahlášení?</p>
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
            $('#CreateReportModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#nazev_nahlaseni').val('');
                $('#popis_nahlaseni').val('');
                $('#dulezitost_nahlaseni').val('');
            })

            $('#EditReportModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#ApplyReportModal').on('hidden.bs.modal', function () {
                $('.chyby_apply').hide();
            })

            $('#DeleteApplyReportModal').on('hidden.bs.modal', function () {
                $('.chyby_delete_apply').hide();
            })

            $('.chyby_add').hide();
            $('.chyby').hide();
            $('.chyby_apply').hide();
            $('.chyby_delete_apply').hide();

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
                    emptyTable: "Nemáte zaevidovaná žádná nahlášení.",
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
                ajax: "{{ route('employee_reports.list') }}",
                columns: [
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
                    url: "{{ route('ReportActionsEmployee.store') }}",
                    method: 'POST',
                    data: {
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
                    url: "/employee/ReportActionsEmployee/"+id+"/edit",
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
                    url: "/employee/ReportActionsEmployee/"+id,
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
                        }else if(data.fail) {
                            $('.chyby').hide();
                            $('.chyby').html('');
                            var failHtml = '<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.fail +
                                '</div>';
                            $('.flash-message').html(failHtml);
                            $('#SubmitEditReportForm').text('Aktualizovat');
                            $("#EditReportModal").modal('hide');
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
                    url: "/employee/ReportActionsEmployee/"+deleteID,
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
            $('body').on('click', '#getReportApply', function(){
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
                    url: "/employee/report/apply/"+id,
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
                            $('.report-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitApply').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_apply').hide();
                            $("#ApplyReportModal").modal('hide');
                        }
                    }
                });
            });

            /* Ziskani ID nemocenske */
            var id;
            $('body').on('click', '#getReportDeleteApply', function(){
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
                    url: "/employee/report/deleteApply/"+id,
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
                            $('.report-list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitDeleteApply').text('Ano');
                            $('.flash-message').html(succ);
                            $('.chyby_delete_apply').hide();
                            $("#DeleteApplyReportModal").modal('hide');
                        }
                    }
                });
            });
        });
    </script>

@endsection


