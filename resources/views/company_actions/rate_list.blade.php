@extends('layouts.company_dashboard')
@section('title') - Hodnocení @endsection
@section('content2')
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
            <table class="table-responsive rate_list">
                <thead>
                <tr>
                    <th width="1%" style="padding-bottom: 20px;padding-top: 20px;">Fotka</th>
                    <th width="6%" style="padding-bottom: 20px;padding-top: 20px;">Jméno</th>
                    <th width="10%" style="padding-bottom: 20px;padding-top: 20px;">Příjmení</th>
                    <th width="4%" style="padding-bottom: 20px;padding-top: 20px;">Spolehlivost</th>
                    <th width="4%" style="padding-bottom: 20px;padding-top: 20px;">Absence</th>
                    <th width="4%" style="padding-bottom: 20px;padding-top: 20px;">Pracovitost</th>
                    <th width="4%" style="padding-bottom: 20px;padding-top: 20px;">Celkově</th>
                    <th width="4%" style="padding-bottom: 20px;padding-top: 20px;">Akce</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Prehodnotit zamestnance -->
    <div class="modal fade" id="RateEmployeeModal" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Přehodnotit zaměstnance</h5>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.chyby').hide();
            $('.chyby_add').hide();

            /* Zobrazení datatable */
            var dataTable = $('.rate_list').DataTable({
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
                order: [[1, "asc"]],
                dom: '<"pull-left"f><"pull-right"l>tip',
                ajax: "{{ route('ratings.list') }}",
                columns: [
                    {
                        data: 'employee_picture', name: 'employee_picture',
                        render: function (data, type, full, meta) {
                            if (data === null) {
                                return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60' />";
                            }
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + data + " width='60' height='50' style='max-width:100%;height:auto;' />";
                        }, orderable: false,sClass: 'text-center'
                    },
                    {data: 'employee_name', name: 'employee_name', sClass: 'text-center'},
                    {data: 'employee_surname', name: 'employee_surname', sClass: 'text-center'},
                    {data: 'employee_reliability', name: 'employee_reliability', sClass: 'text-center'},
                    {data: 'employee_absence', name: 'employee_absence',sClass: 'text-center'},
                    {data: 'employee_workindex', name: 'employee_workindex', sClass: 'text-center'},
                    {data: 'employee_overall', name: 'employee_overall', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
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
                    url: "/company/employees/ratings/rate/" + id,
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
                    url: "/company/employees/ratings/rate/edit/" + id,
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
                            $('.rate_list').DataTable().ajax.reload();
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

        });
    </script>
@endsection
