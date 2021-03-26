@extends('layouts.admin_dashboard')
@section('title') - Firmy @endsection
@section('content')
    <center>
        <br><br>
        <div class="col-lg-11 col-md-10 col-sm-10" style="padding-bottom: 800px;">
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
            <table class="table-responsive company-list">
                <thead>
                <tr>
                    <th width="5%">Fotka</th>
                    <th width="10%">Název</th>
                    <th width="10%">Jméno</th>
                    <th width="10%">Příjmení</th>
                    <th width="16%">Email</th>
                    <th width="14%">Telefon</th>
                    <th width="18%">Adresa</th>
                    <th width="12%">Akce <button style="float:right;font-weight: 200;" class="btn btn-primary btn-md" type="button"  data-toggle="modal" data-target="#CreateCompanyModal"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Vytvořit</button></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Pridani firmy !-->
    <div class="modal fade" id="CreateCompanyModal" style="color:white;">
        <div class="modal-dialog modal-lg" style="max-width: 850px;">
            <div class="modal-content">
                <div class="modal-header">
                         <span class="col-md-12 text-center">
                              <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Přidat novou firmu</h4>
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
                            <label class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Společnost (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-address-book " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company" placeholder="Zadejte název společnosti..." type="text" class="form-control" name="company" value="{{ old('company') }}"  autocomplete="company" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="company_city" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Město (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_city" placeholder="Zadejte město, kde se firma nachází..." type="text" class="form-control" name="company_city" value="{{ old('company_city') }}"  autocomplete="company_city">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_street" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> Ulice </label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_street" placeholder="Zadejte ulici, kde se firma nachází (včetně čísla popisného)..." type="text" class="form-control" name="company_street" value="{{ old('company_street') }}"  autocomplete="company_street">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_ico" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> IČO</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_ico" placeholder="Zadejte IČO firmy..." type="text" class="form-control" name="company_ico" value="{{ old('company_ico') }}"  autocomplete="company_ico">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="first_name" class="col-form-label col-md-2 text-center" style="font-size: 13px;"> Jméno zástupce (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="first_name" placeholder="Zadejte křestní jméno zástupce firmy..." type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"  autocomplete="first_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="surname" class="col-form-label col-md-2 text-center" style="font-size: 12px;">Příjmení zástupce (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="surname" placeholder="Zadejte příjmení zástupce firmy..." type="text" class="form-control" name="surname"  value="{{ old('surname') }}" autocomplete="surname">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="email" class="col-form-label col-md-2 text-center" style="font-size: 15px;"> E-mail (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_email" placeholder="Zadejte e-mailovou adresu firmy..." type="email" class="form-control" name="company_email" value="{{ old('company_email') }}"  autocomplete="email">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="phone" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Telefon (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="phone" placeholder="Zadejte telefonní číslo firmy..." type="text" class="form-control" name="phone" value="{{ old('phone') }}" autocomplete="phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="login" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Login (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="company_login" placeholder="Zadejte uživatelské jméno k systému..." type="text" value="{{ old('company_login') }}" class="form-control" name="company_login"  autocomplete="company_login">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="button" style="margin-bottom: 15px;" class="btn btn-sm btn-warning pull-right" value="Generovat heslo" onClick="generator_admin();">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="password" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Heslo (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="password" placeholder="Zadejte heslo ..." type="password" class="form-control" name="password"  autocomplete="password">
                                </div>
                                <span toggle="#password" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpassword"></span>
                                <script>
                                    $(".showpassword").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="password_confirmation" class="col-form-label col-md-2 text-center" style="font-size: 15px;">Heslo znovu (<span style="color:red;">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="password_confirmation" placeholder="Znovu zadejte heslo ..." type="password" class="form-control" name="password_confirmation"  autocomplete="password_confirmation">
                                </div>
                                <span toggle="#password_confirmation" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpasswordverify"></span>
                                <script>
                                    function generator_admin() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var password_tmp = "";
                                        for (var x = 0; x < 10; ++x) { password_tmp += znaky.charAt(Math.floor(Math.random()*znaky.length));}
                                        password.value = password_tmp;
                                        password_confirmation.value = password_tmp;
                                    }

                                    $(".showpasswordverify").click(function() {
                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        var input = $($(this).attr("toggle"));
                                        if (input.attr("type") == "password") {
                                            input.attr("type", "text");
                                        } else {
                                            input.attr("type", "password");
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitCreateCompany">Přidat firmu</button>
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn btn-modalClose" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Editace firmy -->
    <div class="modal fade" id="EditCompanyModal">
        <div class="modal-dialog" style="max-width: 850px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:white;">Detail firmy</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="EditCompanyModalBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitEditCompanyForm">Aktualizovat</button>
                    <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Smazani firmy -->
    <div id="DeleteCompanyModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení smazání firmy</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete smazat tuto firmu?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteCompany" style="color:white;" id="SubmitDeleteCompany" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#CreateCompanyModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#zacatek_dovolene').val('');
                $('#konec_dovolene').val('');
                $('#poznamka').val('');
            })

            $('#EditCompanyModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('#ApplyCompanyModal').on('hidden.bs.modal', function () {
                $('.chyby_apply').hide();
            })

            $('#DeleteApplyCompanyModal').on('hidden.bs.modal', function () {
                $('.chyby_delete_apply').hide();
            })

            $('.chyby_add').hide();
            $('.chyby').hide();
            $('.chyby_apply').hide();
            $('.chyby_delete_apply').hide();

            /* Zobrazení datatable */
            var dataTable = $('.company-list').DataTable({
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
                    emptyTable: "Nemáte zaevidované žádné dovolené.",
                    paginate: {
                        previous: "Předchozí",
                        next: "Další",
                    }
                },
                bLengthChange: false,
                paging: true,
                pageLength: 15,
                bInfo: false,
                order: [[1, "asc"]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('admin_companies.list') }}",
                columns: [
                    { data: 'company_picture', name: 'company_picture',
                        render: function(data, type, full, meta){
                            if(data === null){
                                return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60' />";
                            }
                            return "<img src={{ URL::to('/') }}/storage/company_images/" + data + " width='60' height='50' style='max-width:100%;height:auto;' />";
                        }, orderable: false},
                    {data: 'company_name', name: 'company_name', sClass: 'text-center'},
                    {data: 'company_user_name', name: 'company_user_name', sClass: 'text-center'},
                    {data: 'company_user_surname', name: 'company_user_surname', sClass: 'text-center'},
                    {data: 'email', name: 'email', sClass: 'text-center'},
                    {data: 'company_phone', name: 'company_phone', sClass: 'text-center'},
                    {data: 'company_address', name: 'company_address', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });


            /* Vytvoreni firmy */
            $('#SubmitCreateCompany').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('CompanyActions.store') }}",
                    method: 'POST',
                    data: {
                        company: $('#company').val(),
                        company_city: $('#company_city').val(),
                        company_street: $('#company_street').val(),
                        company_ico: $('#company_ico').val(),
                        first_name: $('#first_name').val(),
                        surname: $('#surname').val(),
                        company_email: $('#company_email').val(),
                        phone: $('#phone').val(),
                        company_login: $('#company_login').val(),
                        password: $('#password').val(),
                        password_confirmation: $('#password_confirmation').val()
                    },
                    beforeSend:function(){
                        $('#SubmitCreateCompany').text('Přidávání...');
                    },
                    success: function(result) {
                        if(result.errors) {
                            $('.chyby_add').html('');
                            $('#SubmitCreateCompany').text('Přidat firmu');
                            $.each(result.errors, function(key, value) {
                                $('.chyby_add').show();
                                $('.chyby_add').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.chyby_add').hide();
                            $('#company').val('');
                            $('#company_city').val('');
                            $('#company_street').val('');
                            $('#company_ico').val('');
                            $('#first_name').val('');
                            $('#surname').val('');
                            $('#company_email').val('');
                            $('#phone').val('');
                            $('#company_login').val('');
                            $('#password').val('');
                            $('#password-confirm').val('');
                            $('.company-list').DataTable().ajax.reload();
                            var succadd = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ result.success +
                                '</div>';
                            $('.flash-message').html(succadd);
                            $('#CreateCompanyModal').modal('hide');
                        }
                    }
                });
            });


            /* Nahled do detailu firmy */
            $('.modelClose').on('click', function(){
                $('#EditCompanyModal').hide();
            });
            var id;
            $('body').on('click', '#getEditCompanyData', function(e) {
                id = $(this).data('id');
                $.ajax({
                    url: "/admin/CompanyActions/"+id+"/edit",
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#EditCompanyModalBody').html(result.html);
                        $('#EditCompanyModal').show();
                    }
                });
            });

            /* Ulozeni hodnot detailu firmy */
            $('#SubmitEditCompanyForm').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/CompanyActions/"+id,
                    method: 'PUT',
                    data: {
                        company: $('#company_edit').val(),
                        company_city: $('#company_city_edit').val(),
                        company_street: $('#company_street_edit').val(),
                        company_ico: $('#company_ico_edit').val(),
                        first_name: $('#first_name_edit').val(),
                        surname: $('#surname_edit').val(),
                        company_email: $('#company_email_edit').val(),
                        phone: $('#phone_edit').val(),
                        company_login: $('#company_login_edit').val(),
                        password: $('#password_edit').val(),
                        password_confirmation: $('#password-confirm_edit').val()
                    },
                    beforeSend:function(){
                        $('#SubmitEditCompanyForm').text('Aktualizace...');
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
                            $('#SubmitEditCompanyForm').text('Aktualizovat');
                            $("#EditCompanyModal").modal('hide');
                        }else if(data.errors) {
                            $('.chyby').show();
                            $('.chyby').html('');
                            $('#SubmitEditCompanyForm').text('Aktualizovat');
                            $.each(data.errors, function(key, value) {
                                $('.chyby').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.company-list').DataTable().ajax.reload();
                            var succ;
                            if(data.success != "0"){
                                succ = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                            }
                            $('#SubmitEditCompanyForm').text('Aktualizovat');
                            $('.flash-message').html(succ);
                            $('.chyby').hide();
                            $("#EditCompanyModal").modal('hide');
                        }
                    }
                });
            });


            /* Smazani firmy */
            var deleteID;
            $('body').on('click', '#getCompanyDelete', function(){
                deleteID = $(this).data('id');
                $('#DeleteCompanyModal').modal('show');
                $("#DeleteCompanyModal").modal({backdrop: false});
            })
            $('#SubmitDeleteCompany').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/CompanyActions/"+deleteID,
                    method: 'DELETE',
                    beforeSend:function(){
                        $('#SubmitDeleteCompany').text('Mazání...');
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
                        $('.company-list').DataTable().ajax.reload();
                        $('#SubmitDeleteCompany').text('Ano');
                        $("#DeleteCompanyModal").modal('hide');
                    }
                })
            });
        });
    </script>
@endsection
