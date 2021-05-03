@extends('layouts.admin_dashboard')
@section('title') - Statistiky @endsection
@section('content')
<!-- Nazev souboru: statistics.blade.php -->
<!-- Autor: Pavel Sklenář (xsklen12) -->
<!-- Tento soubor reprezentuje moznost "Generátor souborů" v ramci uctu s roli admina -->
<!-- Sekce pro definici HTML5 canvasu -->
<div style="margin-left: 20px;margin-right: 20px;padding-top:40px;padding-bottom: 60px;">
    <!-- Import pluginu doughnutlabel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-doughnutlabel/2.0.3/chartjs-plugin-doughnutlabel.js"></script>
    <div class="row justify-content-center">
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="DoughnutCompaniesTotalCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="DoughnutEmployeesTotalCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="DoughnutShiftsTotalCount"></canvas>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_companies">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartCompanies"></canvas>
        </div>
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_employees">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartEmployees"></canvas>
        </div>
    </div>
    <div class="row justify-content-center" style="margin-top: 85px;">
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="DoughnutShiftsAssignedTotalCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="DoughnutShiftsFutureTotalCount"></canvas>
        </div>
    </div>
    <div class="row justify-content-center" style="padding-bottom:50px;">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_shifts">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShifts"></canvas>
        </div>
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_shifts_assigned">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShiftsAssigned"></canvas>
        </div>
    </div>
    <script>
        /* Pouzita knihovna https://www.chartjs.org/, ukazky grafu: https://www.chartjs.org/docs/latest/samples/bar/vertical.html a dokumentace https://www.chartjs.org/docs/latest/
           Byly zde take pouzity dva pluginy, a to: Chart.js datalabels plugin (https://github.com/chartjs/chartjs-plugin-datalabels) a Chart.js doughnutlabel plugin (https://github.com/ciprianciurea/chartjs-plugin-doughnutlabel) */

        /* Usek kodu pro renderovani jednotlivych grafu */

        /* Deklarace promennych pro jednotlive grafy */
        var DoughnutEmployeesTotalCount;
        var DoughnutEmployeesTotalCountCanvas;
        var DoughnutShiftsTotalCount;
        var DoughnutShiftsTotalCountCanvas;
        var DoughnutShiftsFutureTotalCount;
        var DoughnutShiftsFutureTotalCountCanvas;
        var DoughnutShiftsAssignedTotalCount;
        var DoughnutShiftsAssignedTotalCountCanvas;
        var DoughnutCompaniesTotalCount;
        var DoughnutCompaniesTotalCountCanvas;
        var barChartCompanies;
        var barChartEmployees;
        var barChartShifts;
        var barChartShiftsAssigned;

        /* Funkce: renderBarGraphShiftsAssigned
           Argumenty: data_values - predavana data pro vykresleni, title - titulek grafu, label_value - popisek grafu
           Ucel: Renderovani sloupcoveho grafu pro pocet obsazenych smen dle mesicu
        */
        function renderBarGraphShiftsAssigned(data_values, title, label_value){
            var barChartShiftsAssignedCanvas = $("#barChartShiftsAssigned");
            barChartShiftsAssigned = new Chart(barChartShiftsAssignedCanvas, {
                type:"bar",
                data:{
                    labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                    datasets:[{label: label_value, data: data_values, backgroundColor: ["#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57"]}]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {display: true, fontColor: "black", text: title, fontSize: 20, position: "top", padding: 25, fontStyle:"normal"},
                    legend: {display: false,},
                    scales: {
                        xAxes: [{
                            ticks: {fontColor: "black",},
                            gridLines: {display:false,},
                        }],
                        yAxes: [{
                            ticks: {display:false, beginAtZero: true, precision: 0,},
                        }]
                    },
                    plugins: {
                        datalabels: {color: "black", align: "top",
                            font: {weight: "bold", size:16},
                        }
                    }
                }
            })
        }

        /* Funkce: renderBarGraphCompanies
           Argumenty: data_values - predavana data pro vykresleni, title - titulek grafu, label_value - popisek grafu
           Ucel: Renderovani sloupcoveho grafu pro pocet nove zaregistrovanych firem dle mesicu
        */
        function renderBarGraphCompanies(data_values, title, label_value){
            var barChartCompaniesCanvas = $("#barChartCompanies");
            barChartCompanies = new Chart(barChartCompaniesCanvas, {
                type:"bar",
                data:{
                    labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                    datasets:[{label: label_value, data: data_values, backgroundColor: ["#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57"]}]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {display: true, fontColor: "black", text: title, fontSize: 20, position: "top", padding: 25, fontStyle:"normal"},
                    legend: {display: false,},
                    scales: {
                        xAxes: [{
                            ticks: {fontColor: "black",},
                            gridLines: {display:false,},
                        }],
                        yAxes: [{
                            ticks: {display:false, beginAtZero: true, precision: 0,},
                        }]
                    },
                    plugins: {
                        datalabels: {color: "black", align: "top",
                            font: {weight: "bold", size:16},
                        }
                    }
                }
            })
        }

        /* Funkce: renderBarGraphEmployees
           Argumenty: data_values - predavana data pro vykresleni, title - titulek grafu, label_value - popisek grafu
           Ucel: Renderovani sloupcoveho grafu pro pocet novych zamestnancu firem dle mesicu
        */
        function renderBarGraphEmployees(data_values, title, label_value){
            var barChartEmployeesCanvas = $("#barChartEmployees");
            barChartEmployees = new Chart(barChartEmployeesCanvas, {
                type:"bar",
                data:{
                    labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                    datasets:[{label: label_value, data: data_values, backgroundColor: ["#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57"]}]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {display: true, fontColor: "black", text: title, fontSize: 20, position: "top", padding: 25, fontStyle:"normal"},
                    legend: {display: false,},
                    scales: {
                        xAxes: [{
                            ticks: {fontColor: "black",},
                            gridLines: {display:false,},
                        }],
                        yAxes: [{
                            ticks: {display:false, beginAtZero: true, precision: 0,},
                        }]
                    },
                    plugins: {
                        datalabels: {color: "black", align: "top",
                            font: {weight: "bold", size:16},
                        }
                    }
                }
            })
        }

        /* Funkce: renderBarGraphShifts
           Argumenty: data_values - predavana data pro vykresleni, title - titulek grafu, label_value - popisek grafu
           Ucel: Renderovani sloupcoveho grafu pro pocet vypsanych smen dle mesicu
        */
        function renderBarGraphShifts(data_values, title, label_value){
            var barChartShiftsCanvas = $("#barChartShifts");
            barChartShifts = new Chart(barChartShiftsCanvas, {
                type:"bar",
                data:{
                    labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                    datasets:[{label: label_value, data: data_values, backgroundColor: ["#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57","#2E8B57"]}]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {display: true, fontColor: "black", text: title, fontSize: 20, position: "top", padding: 25, fontStyle:"normal"},
                    legend: {display: false,},
                    scales: {
                        xAxes: [{
                            ticks: {fontColor: "black",},
                            gridLines: {display:false,},
                        }],
                        yAxes: [{
                            ticks: {display:false, beginAtZero: true, precision: 0,},
                        }]
                    },
                    plugins: {
                        datalabels: {color: "black", align: "top",
                            font: {weight: "bold", size:16},
                        }
                    }
                }
            })
        }

        /* Funkce: renderDoughnutGraph
           Argumenty: data_values - predavana data pro vykresleni, title - titulek grafu, label_value - popisek grafu, element - promenna pro dany graf, canvas_element - promenna reprezentujici canvas, element_id - identifikator canvasu
           Ucel: Tato metoda slouzi pro renderovani prstencovych grafu
        */
        function renderDoughnutGraph(data_values, title, label_value, element, canvas_element, element_id){
            canvas_element = $(element_id);
            element = new Chart(canvas_element, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ["#2E8B57"],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false,},
                    scales: {
                        xAxes: [{
                            ticks: {fontColor: "black", display: false},
                            gridLines: {display:false},
                        }],
                        yAxes: [{
                            ticks: {display: false,},
                            gridLines: { display:false},
                        }]
                    },
                    plugins: {
                        datalabels: {display:false,},
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {size: 22, color:'black', weight: 'bold'}},
                                {
                                    text: title,
                                    font: {size: 12},
                                    color: 'grey'
                                }]
                        },
                    },
                }
            })
        }

        /* Ucel: Vykreslovani grafu pri zmene roku u grafu nove zaregistrovanych firem */
        $('#year_companies').change(function(){
            var rok = $("#year_companies").val();
            $.ajax({
                url: '/admin/statistics/companies/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // nastaveni csrf tokenu
                dataType: 'json',
                success: function (odpoved) {
                    barChartCompanies.destroy(); // odstraneni stareho grafu a nasledne vykresleni noveho
                    renderBarGraphCompanies(odpoved.data_companies,"Počet nově zaregistrovaných firem dle měsíců", "Počet nově zaregistrovaných firem dle měsíců");
                },
            });
        });

        /*  Ucel: Vykreslovani grafu pri zmene roku u grafu poctu zamestnancu dle mesicu */
        $('#year_employees').change(function(){
            var rok = $("#year_employees").val();
            $.ajax({
                url: '/admin/statistics/employees/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartEmployees.destroy();
                    renderBarGraphEmployees(odpoved.data_employees,"Počet nových zaměstnanců dle měsíců","Počet nových zaměstnanců");
                },
            });
        });

        /*  Ucel: Vykreslovani grafu pri zmene roku u grafu poctu vypsanych smen dle mesicu */
        $('#year_shifts').change(function(){
            var rok = $("#year_shifts").val();
            $.ajax({
                url: '/admin/statistics/shifts/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShifts.destroy();
                    renderBarGraphShifts(odpoved.data_shifts,"Počet vypsaných směn dle měsíců","Počet vypsaných směn");
                },
            });
        });

        /*  Ucel: Vykreslovani grafu pri zmene roku u grafu poctu obsazenych smen dle mesicu */
        $('#year_shifts_assigned').change(function(){
            var rok = $("#year_shifts_assigned").val();
            $.ajax({
                url: '/admin/statistics/shiftsassigned/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShiftsAssigned.destroy();
                    renderBarGraphShiftsAssigned(odpoved.data_shifts_assigned,"Počet obsazených směn dle měsíců","Počet obsazených směn");
                },
            });
        });
        /* Usek kodu pro volani nadefinonavych metod, ktere vykresli jednotlive grafy */
        var data_companies_total_count = {{$companies_total_count}};
        renderDoughnutGraph(data_companies_total_count,"firem","Celkový počet zaregistrovaných firem",DoughnutCompaniesTotalCount, DoughnutCompaniesTotalCountCanvas, "#DoughnutCompaniesTotalCount");
        var data_shifts_total_count = {{$shifts_total_count}};
        renderDoughnutGraph(data_shifts_total_count,"směn","Celkový počet vypsaných směn",DoughnutShiftsTotalCount, DoughnutShiftsTotalCountCanvas, "#DoughnutShiftsTotalCount");
        var data_shifts_upcoming_total_count = {{$assigned_shifts_future_count}};
        renderDoughnutGraph(data_shifts_upcoming_total_count,"budoucích směn","Celkový počet budoucích obsazených směn",DoughnutShiftsFutureTotalCount, DoughnutShiftsFutureTotalCountCanvas, "#DoughnutShiftsFutureTotalCount");
        var data_shifts_assigned_total_count = {{$assigned_shifts_count}};
        renderDoughnutGraph(data_shifts_assigned_total_count,"obsazených směn","Celkový počet obsazených směn",DoughnutShiftsAssignedTotalCount, DoughnutShiftsAssignedTotalCountCanvas, "#DoughnutShiftsAssignedTotalCount");
        var data_employee_total_count = {{$employees_total_count}};
        renderDoughnutGraph(data_employee_total_count,"zaměstnanců","Celkový počet zaměstnanců",DoughnutEmployeesTotalCount, DoughnutEmployeesTotalCountCanvas, "#DoughnutEmployeesTotalCount");
        var data_employees = {{$companies_employees_count_by_months}};
        renderBarGraphEmployees(data_employees,"Počet nových zaměstnanců dle měsíců","Počet nových zaměstnanců");
        var data_shifts = {{$companies_shifts_count_by_months}};
        renderBarGraphShifts(data_shifts,"Počet vypsaných směn dle měsíců","Počet vypsaných směn");
        var data_companies = {{$companies_count_by_months}};
        renderBarGraphCompanies(data_companies,"Počet nově zaregistrovaných firem dle měsíců", "Počet nově zaregistrovaných firem dle měsíců");
        var data_shifts_assigned = {{$company_assigned_shifts_count_by_months}};
        renderBarGraphShiftsAssigned(data_shifts_assigned,"Počet obsazených směn dle měsíců","Počet obsazených směn");
    </script>
</div>

@endsection
