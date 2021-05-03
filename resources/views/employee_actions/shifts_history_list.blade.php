@extends('layouts.employee_dashboard')
@section('title') - Historie směn @endsection
@section('content')
    <!-- Nazev souboru: shifts_history_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Všechny směny" v ramci uctu s roli zamestnance -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020 -->
    <center>
        <br>
        <!-- Usek kodu pro definici chybovych hlasek za pomoci Session -->
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
            <!-- Usek kodu pro definici tabulky -->
            <table class="employee_allShifts_table">
                <thead>
                <tr>
                    <th width="12.5%">Začátek</th>
                    <th width="12.5%">Konec</th>
                    <th width="12.5%">Lokace</th>
                    <th width="12.5%">Důležitost</th>
                    <th width="12.5%">Příchod</th>
                    <th width="12.5%">Odchod</th>
                    <th width="12.5%" style="padding-bottom: 20px;padding-top: 20px;">Odpracováno</th>
                    <th width="12.5%">Status</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <script type="text/javascript">
        $(document).ready(function(){
            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
             Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
             Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
             K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n. */
            $('.employee_allShifts_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebyla nalezena žádná směna."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné směny.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[1, "asc"]],
                ajax: "{{ route('shifts.getAllEmployeeShiftsList') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    { data: 'shift_start', name: 'shift_start', render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center',}, // viz https://datatables.net/reference/option/columns.render
                    { data: 'shift_end', name: 'shift_end',render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                    { data: 'shift_place', name: 'shift_place',sClass:'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    { data: 'shift_importance_id', name: 'shift_importance_id',sClass:'text-center'},
                    { data: 'attendance_check_in', name: 'attendance_check_in', render: function(odpoved){
                            var date = moment(odpoved).format('DD.MM.YYYY HH:mm');
                            if(date === "Invalid date"){
                                return "Nezapsáno";
                            }else{
                                return date;
                            }
                        },sClass:'text-center',},
                    { data: 'attendance_check_out', name: 'attendance_check_out', render: function(odpoved){
                            var date = moment(odpoved).format('DD.MM.YYYY HH:mm');
                            if(date === "Invalid date"){
                                return "Nezapsáno";
                            }else{
                                return date;
                            }
                        },sClass:'text-center',},
                    { data: 'hours_total', name: 'hours_total',sClass:'text-center'},
                    { data: 'reason_description', name: 'reason_description',sClass:'text-center'},
                ]
            });
        });
    </script>
@endsection

