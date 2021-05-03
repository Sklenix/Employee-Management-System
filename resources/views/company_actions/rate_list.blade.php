@extends('layouts.company_dashboard')
@section('title') - Hodnocení @endsection
@section('content')
    <!-- Nazev souboru: rate_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Přehled hodnocení zaměstnanců" v ramci uctu s roli firmy -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <div class="col-lg-11 col-md-10 col-sm-10">
            <!-- Usek kodu pro definici hlasek za pomoci Session -->
            @if($zprava = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$zprava}}</strong>
                </div>
            @endif
            @if($zprava = Session::get('fail'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$zprava}}</strong>
                </div>
            @endif
            <!-- Tento div slouzi k zobrazeni chyb v ramci AJAXovych pozadavku -->
            <div class="flash-message text-center">
            </div>
            <!-- Usek kodu pro definici tabulky -->
            <table class="company_ratings_table">
                <thead>
                    <tr>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Fotka</th>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Jméno</th>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Příjmení</th>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Spolehlivost</th>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Dochvilnost</th>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Pracovitost</th>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Celkově</th>
                        <th style="padding-bottom: 20px;padding-top: 20px;">Akce</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Prehodnotit zamestnance -->
    <div class="modal fade" id="RateEmployeeForm" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Přehodnotit zaměstnance</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div id="EmployeeRatingEdit">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitRateEmployee">Hodnotit</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            /* K inspiraci prace s datovymi tabulkami slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020 */

            /* Usek kodu pro schovani chybovych hlaseni pri nacteni webove stranky */
            $('.chyby').hide();
            $('.chyby_add').hide();

            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
            Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
            Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
            K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.
           */
            $('.company_ratings_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 12,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebylo nalezeno žádné hodnocení."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidováné žádné zaměstnance.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[2, "asc"]],
                ajax: "{{ route('ratings.list') }}",
                columns: [
                    {data: 'employee_picture', name: 'employee_picture', render: function (odpoved) { // vyrenderovani profiloveho obrazku zamestnance
                            if (odpoved === null) {return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60' alt='Profilová fotka'/>";} // viz https://datatables.net/reference/option/columns.render, Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4#
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + odpoved + " width='60' height='50' alt='Profilová fotka' style='max-width:100%;height:auto;'/>";
                        }, orderable: false,sClass: 'text-center'},
                    {data: 'employee_name', name: 'employee_name', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'employee_surname', name: 'employee_surname', sClass: 'text-center'},
                    {data: 'employee_reliability', name: 'employee_reliability', sClass: 'text-center'},
                    {data: 'employee_absence', name: 'employee_absence',sClass: 'text-center'},
                    {data: 'employee_workindex', name: 'employee_workindex', sClass: 'text-center'},
                    {data: 'employee_overall', name: 'employee_overall', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });

            /* Nahled do hodnoceni zamestnance */
            var id_hodnoceni;
            $('body').on('click', '#obtainEmployeeRate', function() {
                id_hodnoceni = $(this).data('id');
                $.ajax({
                    url: "/company/employees/ratings/rate/" + id_hodnoceni,
                    method: 'GET',
                    success: function(odpoved) {
                        $('#EmployeeRatingEdit').html(odpoved.out);  /* Vlozeni obsahu do modalniho okna */
                        $('#RateEmployeeForm').show(); /* Zobrazeni modalniho okna */

                        /* Usek kodu slouzici pro snimani a zobrazovani aktualnich hodnot posuvniku */
                        /* Pro posuvnik spolehlivosti */
                        $("#viewRealibility").html($("#realibitySlider").val());
                        /* Pro posuvnik dochvilnosti */
                        $("#viewAbsence").html($("#absenceSlider").val());
                        /* Pro posuvnik pracovitosti */
                        $("#viewWork").html($("#workSlider").val());

                        /* Zobrazovani aktualnich hodnot posuvniku */
                        $("#realibitySlider").on('input', function() {$("#viewRealibility").html($("#realibitySlider").val());});
                        $("#absenceSlider").on('input', function() {$("#viewAbsence").html($("#absenceSlider").val());});
                        $("#workSlider").on('input', function() {$("#viewWork").html($("#workSlider").val());});
                    }
                });
            });

            /* Ulozeni hodnot posuvniku do databaze */
            $('#SubmitRateEmployee').click(function() {
                $.ajax({
                    url: "/company/employees/ratings/rate/edit/" + id_hodnoceni,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        employee_absence: $("#absenceSlider").val(),
                        employee_reliability: $("#realibitySlider").val(),
                        employee_workindex: $("#workSlider").val(),
                    },
                    beforeSend:function(){$('#SubmitRateEmployee').text('Aktualizace...');}, // zmena textu pri kliknuti na tlacitko SubmitRateEmployee
                    success: function(odpoved) {
                        if(odpoved.success) {
                            $('.company_ratings_table').DataTable().ajax.reload(); // refresh datove tabulky
                            var successRating = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('#SubmitRateEmployee').text('Hodnotit'); // nastaveni textu tlacitka
                            $('.flash-message').html(successRating); // vlozeni hlasky o uspechu
                            $("#RateEmployeeForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitRateEmployee').text('Hodnotit'); // zmena textu
                        }
                    }
                });
            });
        });
    </script>
@endsection
