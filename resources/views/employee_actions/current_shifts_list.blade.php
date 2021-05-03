@extends('layouts.employee_dashboard')
@section('title') - Aktuální směny @endsection
@section('content')
    <!-- Nazev souboru: current_shifts_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Aktuální směny" v ramci uctu s roli zamestnance -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/-->
    <center>
        <br>
        <!-- Usek kodu pro definici chybovych hlasek za pomoci Session -->
        <div class="col-lg-11 col-md-10 col-sm-10">
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
            <!-- Usek kodu pro zobrazeni chybovych hlasek pri spatnem zapisu prichodu ci odchodu -->
            <div class="attendancesuccess text-center">
            </div>
            <div class="attendancesfail text-center">
            </div>
            <!-- Usek kodu pro definici tabulky, do ktere budou pridany jednotlive zaznamy -->
            <table class="employee_current_shifts_table">
                <thead>
                    <tr>
                        <th width="12.5%">Začátek</th>
                        <th width="12.5%">Konec</th>
                        <th width="12.5%">Lokace</th>
                        <th width="12.5%">Důležitost</th>
                        <th width="12.5%">Příchod</th>
                        <th width="12.5%">Odchod</th>
                        <th width="12.5%">Status</th>
                        <th width="12.5%">Akce</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno slouzici pro nahled detailu smeny -->
    <div class="modal fade" id="CurrentShiftDetailForm">
        <div class="modal-dialog" style="max-width: 750px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Detail směny</h4>
                    <button type="button" class="close modelClose" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>
                    <div id="CurrentShiftDetailContent">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoZavreniOkna modelClose" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro potvrzení prichodu na smenu -->
    <div id="confirmCheckinForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení příchodu</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete zapsat příchod?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" style="color:white;" id="SubmitconfirmCheckin" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro potvrzení odchodu ze smeny -->
    <div id="confirmCheckoutForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení odchodu</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6;">Opravdu si přejete zapsat odchod?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" style="color:white;" id="SubmitconfirmCheckout" class="btn tlacitkoPotvrzeniOkna"  >Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            /* K inspiraci prace s datovymi tabulkami slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020 */

            /* Implicitni schovani chyb uzivateli pri nacteni stranky */
            $('.chyby').hide();

            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
               Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
               Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
               K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.
           */
            $('.employee_current_shifts_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná směna."},
                language: {
                    searchPlaceholder: "Vyhledávání ...",
                    emptyTable: "Nemáte žádné aktuální směny.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[0, "asc"]],
                ajax: "{{ route('shifts.getCurrentEmployeeShiftsList') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    {data: 'shift_start', name: 'shift_start', render: function(odpoved){ // viz https://datatables.net/reference/option/columns.render
                            return moment(odpoved).format('DD.MM.YYYY HH:mm'); // zmena formatu datumu pomoci knihovny moment, protoze yajra datove tabulky nepodporuji format DD.MM.YYYY HH:mm  tak bylo zapotrebi vyrenderovat tento format az na klientske strane
                        },sClass:'text-center',}, // knihovna moment viz https://momentjs.com/
                    {data: 'shift_end', name: 'shift_end',render: function(odpoved){
                            return moment(odpoved).format('DD.MM.YYYY HH:mm');
                        },sClass:'text-center'},
                    {data: 'shift_place', name: 'shift_place',sClass:'text-center'},
                    {data: 'shift_importance_id', name: 'shift_importance_id',sClass:'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'attendance_check_in', name: 'attendance_check_in', render: function(data){
                            var date = moment(data).format('DD.MM.YYYY HH:mm'); // zmena formatu datumu pomoci knihovny moment
                            if(date === "Invalid date"){ // pokud neni prichod zapsan
                                return "Nezapsáno";
                            }else{ return date; }
                        },sClass:'text-center',},
                    {data: 'attendance_check_out', name: 'attendance_check_out', render: function(data){
                            var date = moment(data).format('DD.MM.YYYY HH:mm'); // zmena formatu datumu pomoci knihovny moment
                            if(date === "Invalid date"){ // pokud neni odchod zapsan
                                return "Nezapsáno";
                            }else{ return date; }
                        },sClass:'text-center',},
                    {data: 'reason_description', name: 'reason_description',sClass:'text-center'},
                    {data: 'action', name: 'action', orderable: false,searchable: false,sClass:'text-center'},
                ]
            });


            /* Zobrazeni detailu smeny po stisknuti tlacitka "Detail" */
            var id_smeny;
            $('body').on('click', '#obtainDetailsCurrentShift', function() {
                id_smeny = $(this).data('id');
                $.ajax({ // nastaveni a odeslani AJAX pozadavku viz. https://datatables.net/reference/option/ajax
                    url: "/employee/currentshiftActions/"+id_smeny,
                    method: 'GET',
                    success: function(odpoved) { // zobrazeni dat do modalniho okna po provedeni ajax pozadavku
                        $('#CurrentShiftDetailContent').html(odpoved.out);
                        $('#CurrentShiftDetailForm').show();
                    }
                });
            });

            /* ziskani ID smeny pri zmacknuti tlacitka "Příchod" */
            var smena_id;
            $('body').on('click', '#updateCheckinEmployee', function() {
                $('.chyby').html(''); // vymazani chyb a jejich nasledovne schovani
                $('.chyby').hide();
                smena_id = $(this).data('id'); // ziskani id smeny
            });

            /* Ulozeni prichodu zamestnance do databaze */
            $('#SubmitconfirmCheckin').click( function() {
                $.ajax({
                    url: "/employee/checkin/update/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { shift_id: smena_id },
                    beforeSend:function(){ $('#SubmitconfirmCheckin').text('Zapisování...'); }, // zmena textu pred odeslanim
                    success: function(odpoved) { // zpracovani odpovedi
                        if (!(odpoved.fail)) {
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml);
                        }else{
                            var failHtml = '<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong> '+ odpoved.fail + '</strong></div>';
                            $('.attendancesfail').html(failHtml);
                        }
                        $('.employee_current_shifts_table').DataTable().ajax.reload();
                        $('#SubmitconfirmCheckin').text('Zapsat');
                        $("#confirmCheckinForm").modal('hide');
                    }
                });
            });

            /* ziskani ID smeny pri zmacknuti tlacitka "Odchod" */
            $('body').on('click', '#updateCheckoutEmployee', function() {
                $('.chyby').html('');
                $('.chyby').hide(); // vymazani chyb a jejich nasledovne schovani
                smena_id = $(this).data('id'); // ziskani id smeny
            });

            /* Ulozeni odchodu zamestnance do databaze */
            $('#SubmitconfirmCheckout').click(function() {
                $.ajax({
                    url: "/employee/checkout/update/"+smena_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: { shift_id: smena_id },
                    beforeSend:function(){ $('#SubmitconfirmCheckout').text('Zapisování...'); }, // zmena textu pred odeslanim
                    success: function(odpoved) { // zpracovani odpovedi
                        if (!(odpoved.fail)) {
                            var successHtml = '<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>'+ odpoved.success + '</strong></div>';
                            $('.attendancesuccess').html(successHtml);
                        }else{
                            var failHtml = '<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong> '+ odpoved.fail + '</strong></div>';
                            $('.attendancesfail').html(failHtml);
                        }
                        $('.employee_current_shifts_table').DataTable().ajax.reload();
                        $('#SubmitconfirmCheckout').text('Zapsat');
                        $("#confirmCheckoutForm").modal('hide');
                    }
                });
            });

        });
    </script>
@endsection
