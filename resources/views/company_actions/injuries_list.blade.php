@extends('layouts.company_dashboard')
@section('title') - Zranění @endsection
@section('content')
    <!-- Nazev souboru: injuries_list.blade.php -->
    <!-- Autor: Pavel Sklenář (xsklen12) -->
    <!-- Tento soubor reprezentuje moznost "Centrum zranění" v ramci uctu s roli firmy -->
    <!-- K inspiraci prace s datovymi tabulky slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020
         Modalni okna viz dokumentace Bootstrap: https://getbootstrap.com/docs/4.0/components/modal/ -->
    <center>
        <br>
        <div class="col-lg-11 col-md-10 col-sm-10">
            <!-- Usek kodu pro definici chybovych hlasek za pomoci Session -->
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
                <table class="company_injuries_table">
                <thead>
                    <tr>
                        <th width="5%">Fotka</th>
                        <th width="9%">Jméno</th>
                        <th width="9%">Příjmení</th>
                        <th width="18%">Popis zranění</th>
                        <th width="12%">Datum zranění</th>
                        <th width="12%">Začátek směny</th>
                        <th width="12%">Konec směny</th>
                        <th width="10%">Lokace</th>
                        <th width="13%">Akce <button style="float:right;font-weight: 200;" class="btn btn-dark btn-md" type="button"  data-toggle="modal" data-target="#InjuryCreateForm"><i class="fa fa-plus-square-o"></i> Vytvořit</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </center>

    <!-- Modalni okno pro vytvoreni zraneni -->
    <div class="modal fade" id="InjuryCreateForm" style="color:white;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                      <h4 class="modal-title" id="modal_title" style="color:#4aa0e6;">Vytvořit nové zranění</h4>
                      <button type="button" class="close" style="color:white;" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger chyby_add" role="alert">
                    </div>
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="employee_id_add" class="col-md-2 text-left">Zaměstnanec(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <select name="employee_id_add" id="employee_id_add" style="color:black;text-align-last: center;" class="form-control ziskatZamestnance" data-dependent="shift_id_add">
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
                            <label for="shift_id_add" class="col-md-2 text-left">Směna(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <select name="shift_id_add" id="shift_id_add" style="color:black;text-align-last: center;" class="form-control ziskatZamestnance">
                                    <option value="">Vyberte směnu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="injury_date_add" class="col-md-2 text-left">Datum zranění(<span class="text-danger">*</span>)</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="datetime-local" class="form-control" placeholder="Zadejte datum ve formátu YYYY-mm-dd H:i" name="injury_date_add" id="injury_date_add">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="injury_note_add" class="col-md-2 text-left">Popis zranění</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note-o"></i></div>
                                    </div>
                                    <textarea name="injury_note_add" placeholder="Zadejte popis zranění ... [maximálně 180 znaků]" id="injury_note_add" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="submit" id="SubmitCreateInjury" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoPotvrzeniOkna" value="Vytvořit zranění"/>
                        <button type="button" style="color:rgba(255, 255, 255, 0.90);" class="btn tlacitkoZavreniOkna" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro editaci zraneni -->
    <div class="modal fade" id="EditInjuryForm">
        <div class="modal-dialog" style="max-width: 950px;">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h4 class="modal-title" style="color:#4aa0e6;">Detail zranění</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color:white;">
                    <div class="alert alert-danger" role="alert" style="font-size: 16px;">
                        Položky označené (<span style="color:red;">*</span>) jsou povinné.
                    </div>
                    <div class="chyby alert alert-danger" style="text-decoration: none;">
                    </div>

                    <div id="InjuryEditForm">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn tlacitkoPotvrzeniOkna" style="color:white;" id="SubmitEditInjuryForm">Aktualizovat</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni okno slouzici pro smazani zraneni -->
    <div id="DeleteInjuryForm" class="modal fade" style="color:white;">
        <div class="modal-dialog">
            <div class="modal-content oknoBarvaPozadi">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#4aa0e6;">Potvrzení smazání zranění</h5>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p align="center" style="margin:0;font-size: 16px;color:#4aa0e6">Opravdu si přejete smazat toto zranění?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" name="SubmitDeleteInjury" style="color:white;" id="SubmitDeleteInjury" class="btn tlacitkoPotvrzeniOkna">Ano</button>
                    <button type="button" class="btn tlacitkoZavreniOkna" style="color:white;" data-dismiss="modal">Ne</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            /* K inspiraci prace s datovymi tabulkami slouzil clanek https://www.laravelcode.com/post/laravel-8-ajax-crud-with-yajra-datatable-and-bootstrap-model-validation-example, ktery napsal Harsukh Makwana v roce 2020,
             pro inspiraci prace  s modalnimi okny (udalosti) slouzil clanek https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event, ktery napsal David Meador v roce 2018 */

            /* Usek kodu starajici se o schovani chybovych hlaseni pri zavreni modalniho okna, inspirace z: https://www.tutorialspoint.com/hidden-bs-modal-Bootstrap-Event */
            $('#InjuryCreateForm').on('hidden.bs.modal', function () {
                $('.chyby_add').hide();
            })

            $('#EditInjuryForm').on('hidden.bs.modal', function () {
                $('.chyby').hide();
            })

            /* Usek kodu pro schovani chybovych hlaseni pri nacteni webove stranky */
            $('.chyby_add').hide();
            $('.chyby').hide();

            /* Nastaveni zobrazeni datove tabulky a odeslani do controlleru za pomoci AJAX pozadavku
             Odkaz na yajra datove tabulky: https://yajrabox.com/docs/laravel-datatables/master/installation
             Odkaz na datove tabulky jQuery: https://datatables.net/ a manual k nim https://datatables.net/manual/ a jednotlive moznosti k nim https://datatables.net/reference/option/.
             K prepsani pravidel pro datovou tabulku slouzila tato dokumentace https://legacy.datatables.net/usage/i18n.
            */
            $('.company_injuries_table').DataTable({
                serverSide: true,
                paging: true,
                autoWidth: true,
                pageLength: 15,
                scrollX: true,
                oLanguage: {"sSearch": "", sZeroRecords: "Nebylo nalezeno žádné zranění."},
                language: {
                    searchPlaceholder: "Vyhledávání ... ",
                    emptyTable: "Nemáte zaevidována žádná zranění zaměstnanců.",
                    paginate: { previous: "Předchozí", next: "Další"}
                },
                bInfo: false,
                bLengthChange: false,
                order: [[1, "asc"]],
                ajax: "{{ route('injuries.list') }}",  // nastaveni a odeslani AJAX pozadavku viz https://datatables.net/reference/option/ajax
                columns: [ // definice dat viz https://datatables.net/reference/option/data
                    {data: 'employee_picture', name: 'employee_picture',
                        render: function (odpoved) { // viz https://datatables.net/reference/option/columns.render
                            if (odpoved === null) {return "<img src={{ URL::to('/') }}/images/ikona_profil.png width='60'/>";} // Ikonku vytvoril icon king1, odkaz: https://freeicons.io/essential-collection-5/user-icon-icon-4#
                            return "<img src={{ URL::to('/') }}/storage/employee_images/" + odpoved + " width='60' height='50' style='max-width:100%;height:auto;'/>";
                        }, orderable: false},
                    {data: 'employee_name', name: 'employee_name', sClass: 'text-center'}, // atribut sClass viz. https://legacy.datatables.net/usage/columns
                    {data: 'employee_surname', name: 'employee_surname', sClass: 'text-center'},
                    {data: 'injury_description', name: 'injury_description', sClass: 'text-center'},
                    {data: 'injury_date', name: 'injury_date', render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'}, // zmena formatu datumu pomoci knihovny moment (https://momentjs.com/)
                    {data: 'shift_start', name: 'shift_start', render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'},
                    {data: 'shift_end', name: 'shift_end',render: function(odpoved){ return moment(odpoved).format('DD.MM.YYYY HH:mm');}, sClass:'text-center'},
                    {data: 'shift_place', name: 'shift_place', sClass: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'text-center'},
                ]
            });

            /* Po stisknuti tlacitka "Vytvořit zranění" dojde k pridani zraneni do databaze */
            $('#SubmitCreateInjury').click(function() {
                $.ajax({
                    url: "{{ route('injuriesactions.store') }}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        zamestnanec: $('#employee_id_add').val(),
                        smena: $('#shift_id_add').val(),
                        datum_zraneni: $('#injury_date_add').val(),
                        popis_zraneni: $('#injury_note_add').val()
                    },
                    success: function(odpoved) {
                        if(odpoved.success) {
                            /* Smazani hodnot v modalnim okne */
                            $('.chyby_add').hide();
                            $('#employee_id_add').val('');
                            $('#shift_id_add').val('');
                            $('#injury_date_add').val('');
                            $('#injury_note_add').val('');
                            /* Nacteni tabulky po pridani nemocenske, aby sla ihned videt */
                            $('.company_injuries_table').DataTable().ajax.reload();
                            var successAdd = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            $('.flash-message').html(successAdd);
                            $('#InjuryCreateForm').modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitCreateInjury').text('Vytvořit zranění');
                            $('.chyby_add').html('');
                            /* Iterace skrze chyby a postupne pridavani jich do elementu chyby_add */
                            odpoved.errors.forEach(function (polozka){
                                $('.chyby_add').append('<strong>'+polozka+'</strong><br>');
                            });
                            /* Zobrazeni chyb */
                            $('.chyby_add').show();
                        }
                    }
                });
            });

            /* Modalni okno slouzici pro nahlednuti do detailu zraneni */
            var zraneni_id;
            $('body').on('click', '#obtainEditInjury', function() {
                zraneni_id = $(this).data('id');
                $.ajax({
                    url: "injuriesactions/"+zraneni_id+"/edit",
                    method: 'GET',
                    success: function(odpoved) {
                        $('#InjuryEditForm').html(odpoved.out); // vlozeni obsahu do modalniho okna
                        $('#EditInjuryForm').show(); // zobrazeni modalniho okna
                    }
                });
            });

            /* Ulozeni hodnot detailu zraneni do databaze */
            $('#SubmitEditInjuryForm').click(function() {
                $.ajax({
                    url: "injuriesactions/"+zraneni_id,
                    method: 'PUT',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani
                    data: {
                        datum_zraneni: $('#injury_date_edit').val(),
                        popis_zraneni: $('#injury_description_edit').val(),
                    },
                    beforeSend:function(){$('#SubmitEditInjuryForm').text('Aktualizace...');},
                    success: function(odpoved) { // zpracovani odpovedi
                        if(odpoved.success) {
                            $('.company_injuries_table').DataTable().ajax.reload();
                            var successUpdate;
                            if(odpoved.success != "0"){
                                successUpdate = '<div class="alert alert-success">'+ '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                            }
                            $('#SubmitEditInjuryForm').text('Aktualizovat');
                            $('.flash-message').html(successUpdate);
                            $('.chyby').hide(); // schovani chyb
                            $("#EditInjuryForm").modal('hide'); // schovani modalniho okna
                        } else {
                            $('#SubmitEditInjuryForm').text('Aktualizovat');
                            $('.chyby').html('');
                            /* Iterace skrze chyby a postupne pridavani jich do elementu chyby */
                            odpoved.errors.forEach(function (polozka){
                                $('.chyby').append('<strong>'+polozka+'</strong><br>');
                            });
                            /* Zobrazeni chyb */
                            $('.chyby').show();
                        }
                    }
                });
            });

            /* Zobrazeni modalniho okna pro smazani zraneni */
            var zraneni_delete_id;
            $('body').on('click', '#obtainDeleteInjury', function(){
                zraneni_delete_id = $(this).data('id');
            });

            /* Realizace odstraneni zraneni z databaze */
            $('#SubmitDeleteInjury').click(function() {
                $.ajax({
                    url: "injuriesactions/"+zraneni_delete_id,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    beforeSend:function(){$('#SubmitDeleteInjury').text('Mazání...');}, // zmena textu pri kliknuti
                    success:function(odpoved) { // zpracovani odpovedi
                        var successHtml = '<div class="alert alert-success">' + '<button type="button" class="close" data-dismiss="alert">x</button>' + '<strong>' + odpoved.success + '</strong></div>';
                        $('.flash-message').html(successHtml);
                        $('.company_injuries_table').DataTable().ajax.reload(); // refresh datove tabulky
                        $('#SubmitDeleteInjury').text('Ano');
                        $("#DeleteInjuryForm").modal('hide'); // schovani modalniho okna
                    }
                })
            });

            /* Ziskani zamestnancovych smen po jeho vyberu v selectboxu */
            $('.ziskatZamestnance').change(function(){
                    $("#injury_date_add").val('');
                    var zamestnanec_id = $(this).val();
                    var smeny_select = $(this).data('dependent');
                    $.ajax({
                        url:"{{ route('injuries.selectShift') }}",
                        method:"POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                        data:{
                            employee_id:zamestnanec_id,
                            smeny_select:smeny_select
                        },
                        success:function(odpoved) {
                            $('#'+smeny_select).html(odpoved.out);
                        }
                    })
            });

            /* Zobrazeni datumu zraneni na zaklade zacatku smeny */
            $('#shift_id_add').change(function(){
                var shift_id = $("#shift_id_add").val();
                $.ajax({
                    url: '/company/injuries/get/shift/start/'+shift_id,
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu pro odeslani viz vyse
                    dataType: 'json',
                    success: function (odpoved) {
                        $("#injury_date_add").val(odpoved.shift_start);
                    },
                });
            });
        });
    </script>
@endsection
