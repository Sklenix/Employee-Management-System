@extends('layouts.company_dashboard')
@section('title') - Zaměstnanci @endsection
@section('content2')
    <center>
        <br><br>
        <div class="col-lg-11 col-md-10 col-sm-10">
            @if(Session::has('obrazekZpravaFail'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('obrazekZpravaFail') }}
                </div>
            @endif
            @if(Session::has('obrazekZpravaSuccess'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ Session::get('obrazekZpravaSuccess') }}
                </div>
            @endif
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
            <table class="table-responsive employee_list">
                <thead>
                <tr>
                    <th width="5%">Fotka</th>
                    <th width="6%">Jméno</th>
                    <th width="10%">Příjmení</th>
                    <th width="10%">Email</th>
                    <th width="10%">Telefon</th>
                    <th width="8%">Pozice</th>
                    <th width="5%">Směna obsazena</th>
                    <th width="10%">Akce <button style="float:right;font-weight: 200;" class="btn btn-dark btn-md" type="button"  data-toggle="modal" data-target="#CreateArticleModal"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Vytvořit</button></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Smazani zamestnance -->
    <div id="confirmModal" class="modal fade" style="color:white;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potvrzení smazání zaměstnance</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;">Opravdu si přejete smazat tohoto zaměstnance?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="ok_button" style="color:white;" id="ok_button" class="btn btn-modalSuccess"  >Ano</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profil zamestnance -->
    <div class="modal fade" id="EditArticleModal">
        <div class="modal-dialog" style="max-width: 1050px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:white;">Profil zaměstnance</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>

                    <div id="EditArticleModalBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitEditArticleForm">Aktualizovat</button>
                    <button type="button" class="btn btn-modalClose modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vytvoreni zamestnance -->
    <div class="modal fade" id="CreateArticleModal">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:white;">Vytvoření zaměstnance</h5>
                    <button type="button" style="color:white;" class="close modelClose" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger chyby_add" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Jméno(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="first_name_add" placeholder="Zadejte křestní jméno zaměstnance..." type="text" class="form-control" name="first_name_add" value="{{ old('first_name_add') }}"  autocomplete="first_name_add" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Příjmení(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="surname_add" placeholder="Zadejte příjmení zaměstnance..." type="text" class="form-control" name="surname_add" value="{{ old('surname_add') }}"  autocomplete="surname_add">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Email(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-envelope " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="email_add" placeholder="Zadejte email zaměstnance..." type="text" class="form-control" name="email_add" value="{{ old('email_add') }}"  autocomplete="email_add">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Telefon(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-phone " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="phone_add" placeholder="Zadejte telefon zaměstnance..." type="text" class="form-control" name="phone_add" value="{{ old('phone_add') }}"  autocomplete="phone_add">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Pozice(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-child" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="position_add" placeholder="Zadejte pozici zaměstnance..." type="text" class="form-control" name="position_add" value="{{ old('position_add') }}"  autocomplete="position_add">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Město bydliště(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="city_add" placeholder="Zadejte město bydliště zaměstnance..." type="text" class="form-control" name="city_add" value="{{ old('city_add') }}"  autocomplete="city_add">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Ulice bydliště</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="street_add" placeholder="Zadejte město bydliště zaměstnance..." type="text" class="form-control" name="street_add" value="{{ old('street_add') }}"  autocomplete="street_add">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Login(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-user " aria-hidden="true"></i></div>
                                    </div>
                                    <input id="login_add" placeholder="Zadejte login zaměstnance..." type="text" class="form-control" name="login_add" value="{{ old('login_add') }}"  autocomplete="login_add">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="button" style="margin-bottom: 15px;" class="btn btn-sm btn-warning pull-right" value="Generovat heslo" onClick="generator_add();">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Heslo(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="password_add" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control" name="password_add" value="{{ old('password_add') }}"  autocomplete="password_add">
                                </div>
                                <span toggle="#password_add" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpassword_add"></span>
                                <script>
                                    $(".showpassword_add").click(function() {
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
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Heslo znovu(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    </div>
                                    <input id="password_repeat_add" placeholder="Zadejte heslo zaměstnance..." type="password" class="form-control" name="password_repeat_add" value="{{ old('password_repeat_add') }}"  autocomplete="password_repeat_add">
                                </div>
                                <span toggle="#password_repeat_add" style="z-index: 3;float:right;margin-right: 12px;position: relative;bottom:25px;color:black;" class="fa fa-fw fa-eye field-icon showpasswordverify_add"></span>
                                <script>
                                    function generator_add() {
                                        var znaky = "PQRSTUVWXYZ123!@#$()4567890abcd+efghijklm-nop456789qABCDEFGHIJKLMNOrst456789uvwxyz";
                                        var password_tmp = "";
                                        for (var x = 0; x < 10; ++x) { password_tmp += znaky.charAt(Math.floor(Math.random()*znaky.length));}
                                        password_add.value = password_tmp;
                                        password_repeat_add.value = password_tmp;
                                    }

                                    $(".showpasswordverify_add").click(function() {
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
                            <label class="col-md-2 text-left formularLabelsAjaxAdd">Poznámka</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></div>
                                    </div>
                                    <textarea name="note_add" placeholder="Zadejte poznámku k zaměstnanci..." id="note_add" class="form-control" value="{{ old('note_add') }}"  autocomplete="note_add"></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" style="font-size: 16px;margin-bottom: 5px;background-color: #1d643b; padding: 5px 10px;border-radius: 10px;">Výběr jazyků, které zaměstnanec ovládá:</div>
                    <div class="form-check text-center" style="color:white;margin-bottom:15px;background-color: #1d643b;border-radius: 10px;padding:5px 10px;">
                        @if (count($jazyky) == 0)
                            <div class="alert alert-danger alert-block text-center">
                                <strong>Nedefinoval jste žádný jazyk.</strong>
                            </div>
                        @endif
                        @foreach($jazyky as $moznost)
                            <input type="checkbox" class="form-check-input jazyky" id="jazyky" name="jazyky[]" value="{{$moznost->language_id}}">
                            <label class="form-check-label" style="font-size: 16px;" for="jazyky"> {{$moznost->language_name}}</label><br>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitCreateArticleForm">Vytvořit</button>
                    <button type="button" class="btn btn-modalClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hodnoceni zamestnance -->
    <div class="modal fade" id="RateEmployeeModal" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hodnocení zaměstnance</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="RateEmployeeModalBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitRateEmployee">Hodnotit</button>
                    <button type="button" class="btn btn-modalClose rateClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Prirazeni smen k zamestnancum -->
    <div class="modal fade" id="AssignShiftModal" style="color:white;">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Přiřazení směn k zaměstnancovi</h5>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="AssignShiftBody">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-modalSuccess" style="color:white;" id="SubmitAssignShift">Přiřadit</button>
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
                        <strong>Vyberte směnu, u které chcete vyplnit check-in, check-out, status, nebo poznámku.</strong>
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
            $('#CreateArticleModal').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
                $('#first_name_add').val('');
                $('#surname_add').val('');
                $('#phone_add').val('');
                $('#email_add').val('');
                $('#note_add').val('');
                $('#position_add').val('');
                $('#city_add').val('');
                $('#street_add').val('');
                $('#login_add').val('');
                $('#password_add').val('');
                $('#password_repeat_add').val('');
                $('.jazyky').attr('checked', false);
            })

            $('#EditArticleModal').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            $('.chyby').hide();
            $('.chyby_add').hide();

            /* Zobrazení datatable */
            var dataTable = $('.employee_list').DataTable({
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
                    emptyTable: "Nemáte zaevidované žádné zaměstnance.",
                    paginate: {
                        previous: "Předchozí",
                        next: "Další",
                    }
                },
                bLengthChange: false,
                pageLength: 12,
                bInfo: false,
                order: [[ 1, "asc" ]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('employees.list') }}",
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
                    { data: 'email', name: 'email',sClass:'text-center'},
                    { data: 'employee_phone', name: 'employee_phone'},
                    { data: 'employee_position', name: 'employee_position',sClass:'text-center'},
                    { data: 'shift_taken', name: 'shift_taken',sClass:'text-center'},
                    { data: 'action', name: 'action', orderable: false,searchable: false,sClass:'text-center'},
                ]
            });


            $('.btn-modalClose').click(function(e) {
                $('.chyby_add').hide();
            });

            /* Vytvoreni zamestnance */
            $('#SubmitCreateArticleForm').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('employeesactions.store') }}",
                    method: 'POST',
                    data: {
                        employee_name: $('#first_name_add').val(),
                        employee_surname: $('#surname_add').val(),
                        employee_phone: $('#phone_add').val(),
                        email: $('#email_add').val(),
                        employee_note: $('#note_add').val(),
                        employee_position: $('#position_add').val(),
                        employee_city: $('#city_add').val(),
                        employee_street: $('#street_add').val(),
                        employee_login: $('#login_add').val(),
                        password: $('#password_add').val(),
                        password_confirm: $('#password_repeat_add').val(),
                        jazyky: $('.jazyky:checked').serialize(),
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
                            $('#first_name_add').val(''),
                                $('#surname_add').val(''),
                                $('#phone_add').val(''),
                                $('#email_add').val(''),
                                $('#note_add').val(''),
                                $('#position_add').val(''),
                                $('#city_add').val(''),
                                $('#street_add').val(''),
                                $('#login_add').val(''),
                                $('#password_add').val(''),
                                $('#password_repeat_add').val(''),
                                $(":checkbox").attr("checked", false);
                            $('.employee_list').DataTable().ajax.reload();
                            var succadd = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ result.success +
                                '</div>';
                            $('.flash-message').html(succadd);
                            $('#CreateArticleModal').modal('hide');
                        }
                    }
                });
            });

            /* Smazání zaměstnance */
            var deleteID;
            $('body').on('click', '#getDeleteId', function(){
                deleteID = $(this).data('id');
                $('#confirmModal').modal('show');
                $("#confirmModal").modal({backdrop: false});
            })
            $('#ok_button').click(function(e) {
                e.preventDefault();
                var id = deleteID;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employeesactions/"+deleteID,
                    method: 'DELETE',
                    beforeSend:function(){
                        $('#ok_button').text('Mazání...');
                    },
                    success:function(data)
                    {
                        var successHtml = '<div class="alert alert-success">'+
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                            '</div>';

                        $('.flash-message').html(successHtml);

                        setTimeout(function(){
                            $('.employee_list').DataTable().ajax.reload();
                            $('#ok_button').text('Ano');
                            $("#confirmModal").modal('hide');
                        }, 200);
                    }
                })
            });

            /* Náhled do profilu zaměstnance */
            $('.modelClose').on('click', function(){
                $('#EditArticleModal').hide();
            });
            var id;
            $('body').on('click', '#getEditArticleData', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                id = $(this).data('id');
                $.ajax({
                    url: "/company/employeesactions/"+id+"/edit",
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#EditArticleModalBody').html(result.html);
                        $('#EditArticleModal').show();
                    }
                });
            });

            /* Ulozeni hodnot profilu zamestnance do databaze */
            $('#SubmitEditArticleForm').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employeesactions/"+id,
                    method: 'PUT',
                    data: {
                        employee_name: $('#edit_first_name').val(),
                        employee_surname: $('#edit_surname').val(),
                        employee_phone: $('#edit_phone_number').val(),
                        email: $('#edit_email').val(),
                        employee_position: $('#edit_position').val(),
                        employee_city: $('#edit_city').val(),
                        employee_street: $('#edit_street').val(),
                        employee_login: $('#edit_login').val(),
                        employee_note: $('#edit_note').val(),
                        password: $('#password_edit').val(),
                        password_repeat: $('#password_edit_confirm').val(),
                        jazyky_edit: $('.jazyky_edit:checked').serialize(),
                    },
                    beforeSend:function(){
                        $('#SubmitEditArticleForm').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('.chyby').show();
                            $('.chyby').html('');
                            $('#SubmitEditArticleForm').text('Aktualizovat');
                            $.each(data.errors, function(key, value) {
                                $('.chyby').append('<strong><li>'+value+'</li></strong>');
                            });
                        } else {
                            $('.employee_list').DataTable().ajax.reload();
                            var succ;
                            if(data.success != "0"){
                                succ = '<div class="alert alert-success">'+
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                    '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                    '</div>';
                            }
                            $('#SubmitEditArticleForm').text('Aktualizovat');
                            $('.flash-message').html(succ);
                            $("#EditArticleModal").modal('hide');
                        }
                    }
                });
            });

            var realibility;
            var absence;
            var work;

            /* Náhled do hodnocení zaměstnance */
            $('.rateClose').on('click', function(){
                $('#RateEmployeeModal').hide();
            });
            var id;
            $('body').on('click', '#getEmployeeRate', function(e) {
                $('.alert-danger').html('');
                $('.alert-danger').hide();
                id = $(this).data('id');
                $.ajax({
                    url: "/company/employees/rate/" + id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#RateEmployeeModalBody').html(result.html);

                        $('#RateEmployeeModal').show();
                        realibility = document.getElementById("realibitySlider");
                        var realibilityView = document.getElementById("viewRealibility");
                        realibilityView.innerHTML = realibility.value;

                        absence = document.getElementById("absenceSlider");
                        var absenceView = document.getElementById("viewAbsence");
                        absenceView.innerHTML = absence.value;

                        work = document.getElementById("workSlider");
                        var workView = document.getElementById("viewWork");
                        workView.innerHTML = work.value;

                        realibility.oninput = function() {
                            realibilityView.innerHTML = this.value;
                        }

                        absence.oninput = function() {
                            absenceView.innerHTML = this.value;
                        }

                        work.oninput = function() {
                            workView.innerHTML = this.value;
                        }
                    }
                });
            });

            /* Ulozeni hodnot slideru do databaze*/
            $('#SubmitRateEmployee').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/rate/edit/" + id,
                    method: 'PUT',
                    data: {
                        employee_absence: absence.value,
                        employee_reliability: realibility.value,
                        employee_workindex: work.value,
                    },
                    beforeSend:function(){
                        $('#SubmitRateEmployee').text('Aktualizace...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('#SubmitRateEmployee').text('Hodnotit');
                        } else {
                            $('.employee_list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitRateEmployee').text('Hodnotit');
                            $('.flash-message').html(succ);
                            $("#RateEmployeeModal").modal('hide');
                        }
                    }
                });
            });

            /* Prirazeni smeny zamestnancum */
            $('.assignClose').on('click', function(){
                $('#AssignShiftModal').hide();
            });
            var id;
            $('body').on('click', '#getEmployeeAssign', function(e) {
                id = $(this).data('id');
                $.ajax({
                    url: "/company/employees/assign/" + id,
                    method: 'GET',
                    success: function(result) {
                        console.log(result);
                        $('#AssignShiftBody').html(result.html);
                        $('#AssignShiftModal').show();
                    }
                });
            });

            /* Ulozeni hodnot prirazeni smen zamestnancum do databaze */
            $('#SubmitAssignShift').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/company/employees/assign/edit/" + id,
                    method: 'PUT',
                    data: {
                        shifts_ids: $('.shift_shift_assign_id:checked').serialize()
                    },
                    beforeSend:function(){
                        $('#SubmitAssignShift').text('Přiřazování...');
                    },
                    success: function(data) {
                        if(data.errors) {
                            $('#SubmitAssignShift').text('Přiřadit');
                        } else {
                            $('.employee_list').DataTable().ajax.reload();
                            var succ = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></i></strong> '+ data.success +
                                '</div>';
                            $('#SubmitAssignShift').text('Přiřadit');
                            $('.flash-message').html(succ);
                            $("#AssignShiftModal").modal('hide');
                        }
                    }
                });
            });

            /* Zobrazení možností docházky */
            $('.modelClose').on('click', function(){
                $('#ShowAttendanceOptionsModal').hide();
            });
            var id;
            $('body').on('click', '#getShiftsOptions', function(e) {
                $('.chyby').html('');
                $('.chyby').hide();
                id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/"+id,
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
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/checkin/"+zamestnanec_id+"/"+smena_id,
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
                    url: "/employee/attendance/options/checkin/update/"+zamestnanec_id+"/"+smena_id,
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
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/checkout/"+zamestnanec_id+"/"+smena_id,
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
                    url: "/employee/attendance/options/checkout/update/"+zamestnanec_id+"/"+smena_id,
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
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/absence/"+zamestnanec_id+"/"+smena_id,
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
                    url: "/employee/attendance/options/absence/update/"+zamestnanec_id+"/"+smena_id,
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
                smena_id = $('#vybrana_smena option:selected').attr('id');
                zamestnanec_id = $(this).data('id');
                $.ajax({
                    url: "/employee/attendance/options/note/"+zamestnanec_id+"/"+smena_id,
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
                    url: "/employee/attendance/options/note/update/"+zamestnanec_id+"/"+smena_id,
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
