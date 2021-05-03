@extends('layouts.employee_dashboard')
@section('title') - Zranění @endsection
@section('content')
    <!-- Nazev souboru: injuries_history.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Historie zranění" v ramci uctu s roli zamestnance -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020 -->
    <center>
        <br>
        <!-- Usek kodu pro definici chybovych hlasek za pomoci Session -->
        <div class="col-11">
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
            <!-- Usek kodu pro definici tabulky -->
            <table class="employee_injuries_table">
                <thead>
                    <tr>
                        <th width="30%">Popis zranění</th>
                        <th width="17.5%">Datum zranění</th>
                        <th width="17.5%">Začátek směny</th>
                        <th width="17.5%">Konec směny</th>
                        <th width="17.5%">Lokace</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>
    <script type="text/javascript">
        $(document).ready(function() {
            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
              Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
              Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
              K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n. */
            $('.employee_injuries_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebylo nalezeno žádné zranění."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidované žádné zranění.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[1, "asc"]],
                ajax: "{{ route('employee_injuries.list') }}", // smerovani ajax pozadavku viz. https://datatables.net/reference/option/ajax
                columns: [ // definice dat (viz https://datatables.net/manual/data/)
                    {data: 'injury_description', name: 'injury_description', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'injury_date', name: 'injury_date', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // viz https://datatables.net/reference/option/columns.render
                    {data: 'shift_start', name: 'shift_start', render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                    {data: 'shift_end', name: 'shift_end',render: function(odpoved){return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'},
                    {data: 'shift_place', name: 'shift_place', sClass: 'text-center'}
                ]
            });
        });
    </script>
@endsection


