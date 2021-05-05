@extends('layouts.company_dashboard')
@section('title') - Statistiky @endsection
@section('content')
<!-- Nazev souboru: statistics.blade.php -->
<!-- Autor: Pavel Sklenář (xsklen12) -->
<!-- Tento soubor reprezentuje moznost "Statistiky" v ramci uctu s roli firmy -->
<div class="row" style="margin-top: 20px;">
    <div class=" col-lg-1" style="font-size: 15px;">
    </div>
    <div class=" col-lg-10 text-center" style="font-size: 15px;">
        <!-- Definice zachytavani chybovych hlasek ci hlasek o uspechu pomoci Session -->
        @if($zprava = Session::get('success'))
            <div class="alert alert-success alert-block" style="margin-top: 15px;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{$zprava}}</strong>
            </div>
        @endif
        @if($zprava = Session::get('fail'))
            <div class="alert alert-danger alert-block" style="margin-top: 15px;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{$zprava}}</strong>
            </div>
        @endif
    </div>
    <div class=" col-lg-1" style="font-size: 15px;">
    </div>
    <div class=" col-lg-1" style="font-size: 15px;">
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
        <canvas id="doughNutChartEmployeesCount"></canvas>

    </div>
    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
        <canvas id="doughNutChartShiftsCount"></canvas>
    </div>
    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
        <canvas id="doughNutChartUpcomingShiftsCount"></canvas>
    </div>
    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
        <canvas id="doughNutChartHistoricalShiftsCount"></canvas>
    </div>
</div>
<center>
    <!-- Import pluginu doughnutlabel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-doughnutlabel/2.0.3/chartjs-plugin-doughnutlabel.js"></script>

    <!-- Sekce urcena pro definovani HTML5 canvasu -->
    <div class="row justify-content-center">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_employees">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartEmployees"></canvas>
        </div>

        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_shifts">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShifts"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 70px;">
            <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                <canvas id="doughNutChartAssignedShiftsCount"></canvas>
            </div>
            <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                <canvas id="doughNutChartUnregisteredAttendanceCount"></canvas>
            </div>
            <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                <canvas id="doughNutChartOKCount"></canvas>
            </div>
            <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                <canvas id="doughNutChartAbsenceCount"></canvas>
            </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 70px;">
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAbsenceLateCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartNotCameCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAbsenceDeniedCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAbsenceDiseaseCount"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 15px;">
        <div class="col-lg-10" style="max-width: 1200px;max-height: 700px;margin-top: 40px;">
            <select class="form-control" id="year_attendances">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartAttendances"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 85px;">
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAverageEmployeeOverallScore"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAverageEmployeeReliabilityScore"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAverageEmployeeAbsenceScore"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAverageEmployeeWorkScore"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 70px;">
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartTotalShiftHours"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAverageShiftHour"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartMaxShiftHour"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartMinShiftHour"></canvas>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_shifts_assigned">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShiftsAssigned"></canvas>
        </div>

        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_shifts_total">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShiftsTotalHours"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 70px;">
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartTotalWorkedShiftHours"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartTotalLateShiftHours"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartLateShiftsCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartAverageEmployeeScoreByTime"></canvas>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_total_worked_hours">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShiftsTotalWorkedHours"></canvas>
        </div>

        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_total_late_hours">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShiftsTotalLateHours"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 75px;">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_late_flags_count">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShiftsTotalLateFlagsCount"></canvas>
        </div>

        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="year_injuries_flags_count">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartShiftsTotalInjuriesFlagsCount"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 70px;">
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartTotalInjuriesCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartTotalReportsCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartTotalVacationsCount"></canvas>
        </div>
        <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
            <canvas id="doughNutChartTotalDiseasesCount"></canvas>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="vacations_year">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartVacationsByMonths"></canvas>
        </div>

        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="diseases_year">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartDiseasesByMonths"></canvas>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-top: 75px;">
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="reports_year">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartReportsByMonths"></canvas>
        </div>
        <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 40px;">
            <select class="form-control" id="average_score_by_time_year">
                <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <canvas id="barChartAverageEmployeesScoreByTime"></canvas>
        </div>
    </div>

    <div class="col-5" style="margin-top: 50px;margin-bottom: 25px;">
        <li class="list-group-item text-right"><span class="pull-left"><strong>Účet vytvořen</strong></span> {{$vytvorenUcet}}</li>
    </div>
</center>
    <script>
        /* Pouzita knihovna https://www.chartjs.org/, ukazky grafu: https://www.chartjs.org/docs/latest/samples/bar/vertical.html a dokumentace https://www.chartjs.org/docs/latest/
           Byly zde take pouzity dva pluginy, a to: Chart.js datalabels plugin (https://github.com/chartjs/chartjs-plugin-datalabels) a Chart.js doughnutlabel plugin (https://github.com/ciprianciurea/chartjs-plugin-doughnutlabel) */

        /* Usek kodu pro renderovani jednotlivych grafu */

        /* Deklarace promennych pro jednotlive grafy */
        var barChartEmployees;
        var barChartShifts;
        var barChartAttendances;

        var doughNutChartEmployeesCount;
        var doughNutChartShiftsCount;

        var doughNutChartUpcomingShiftsCount;
        var doughNutChartHistoricalShiftsCount;

        var doughNutChartOKCount;
        var doughNutChartAbsenceCount;
        var doughNutChartAbsenceLateCount;
        var doughNutChartNotCameCount;

        var doughNutChartAbsenceDeniedCount;
        var doughNutChartAbsenceDiseaseCount;

        var doughNutChartAssignedShiftsCount;
        var doughNutChartUnregisteredAttendanceCount;

        var doughNutChartAverageEmployeeOverallScore;
        var doughNutChartAverageEmployeeReliabilityScore;
        var doughNutChartAverageEmployeeAbsenceScore;
        var doughNutChartAverageEmployeeWorkScore;

        var doughNutChartAverageShiftHour;
        var doughNutChartMaxShiftHour;
        var doughNutChartMinShiftHour;
        var doughNutChartTotalShiftHours;

        var barChartShiftsAssigned;
        var barChartShiftsTotalHours;

        var doughNutChartTotalWorkedShiftHours;
        var doughNutChartTotalLateShiftHours;
        var doughNutChartLateShiftsCount;
        var doughNutChartAverageEmployeeScoreByTime;

        var barChartShiftsTotalWorkedHours;
        var barChartShiftsTotalLateHours;
        var barChartShiftsTotalLateFlagsCount;
        var barChartShiftsTotalInjuriesFlagsCount;

        var doughNutChartTotalInjuriesCount;
        var doughNutChartTotalReportsCount;
        var doughNutChartTotalVacationsCount;
        var doughNutChartTotalDiseasesCount;

        var barChartVacationsByMonths;
        var barChartDiseasesByMonths;
        var barChartReportsByMonths;
        var barChartAverageEmployeesScoreByTime;
        /* Z duvodu kvantity radku tu bude popsan obecny princip pro vykreslovani grafu */
        /* Princip: 1. Ulozeni dat z kontroleru do promennych.
                    2. Zavolani metody pro vyrenderovani jednotlivych grafu.
                    3. Pro zavolani metody je potreba promenna reprezentujici HTML5 canvas a promenna, ktera reprezentuje dany graf. Dale jsou potreba samotne data a popisky.
                    4. Po zavolani metody je do HTML5 canvasu vykreslen graf. */
        function renderBarGraphAverageEmployeesScoreByTime(data_values, title, label_value){
            var barChartAverageEmployeesScoreByTimeCanvas = $("#barChartAverageEmployeesScoreByTime");
            barChartAverageEmployeesScoreByTime = new Chart(barChartAverageEmployeesScoreByTimeCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphReportsByMonths(data_values, title, label_value){
            var barChartReportsByMonthsCanvas = $("#barChartReportsByMonths");
            barChartReportsByMonths = new Chart(barChartReportsByMonthsCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphDiseasesByMonths(data_values, title, label_value){
            var barChartDiseasesByMonthsCanvas = $("#barChartDiseasesByMonths");
            barChartDiseasesByMonths = new Chart(barChartDiseasesByMonthsCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphVacationsByMonths(data_values, title, label_value){
            var barChartVacationsByMonthsCanvas = $("#barChartVacationsByMonths");
            barChartVacationsByMonths = new Chart(barChartVacationsByMonthsCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderDoughnutReportsCountGraph(data_values, title, label_value){
            var doughNutChartTotalReportsCountCanvas = $("#doughNutChartTotalReportsCount");
            doughNutChartTotalReportsCount = new Chart(doughNutChartTotalReportsCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'nahlášení celkově',
                                    font: {
                                        size: 11
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutInjuriesCountGraph(data_values, title, label_value){
            var doughNutChartTotalInjuriesCountCanvas = $("#doughNutChartTotalInjuriesCount");
            doughNutChartTotalInjuriesCount = new Chart(doughNutChartTotalInjuriesCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'zranění celkově',
                                    font: {
                                        size: 11
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutDiseasesCountGraph(data_values, title, label_value){
            var doughNutChartTotalDiseasesCountCanvas = $("#doughNutChartTotalDiseasesCount");
            doughNutChartTotalDiseasesCount = new Chart(doughNutChartTotalDiseasesCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'nemocenských celkově',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutVacationsCountGraph(data_values, title, label_value){
            var doughNutChartTotalVacationsCountCanvas = $("#doughNutChartTotalVacationsCount");
            doughNutChartTotalVacationsCount = new Chart(doughNutChartTotalVacationsCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'dovolených celkově',
                                    font: {
                                        size: 11
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderBarGraphShiftsTotalInjuriesFlagsCount(data_values, title, label_value){
            var barChartShiftsTotalInjuriesFlagsCountCanvas = $("#barChartShiftsTotalInjuriesFlagsCount");
            barChartShiftsTotalInjuriesFlagsCount = new Chart(barChartShiftsTotalInjuriesFlagsCountCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphShiftsTotalLateFlagsCount(data_values, title, label_value){
            var barChartShiftsTotalLateFlagsCountCanvas = $("#barChartShiftsTotalLateFlagsCount");
            barChartShiftsTotalLateFlagsCount = new Chart(barChartShiftsTotalLateFlagsCountCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphShiftsTotalLateHours(data_values, title, label_value){
            var barChartShiftsTotalLateHoursCanvas = $("#barChartShiftsTotalLateHours");
            barChartShiftsTotalLateHours = new Chart(barChartShiftsTotalLateHoursCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphShiftsTotalWorkedHours(data_values, title, label_value){
            var barChartShiftsTotalWorkedHoursCanvas = $("#barChartShiftsTotalWorkedHours");
            barChartShiftsTotalWorkedHours = new Chart(barChartShiftsTotalWorkedHoursCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderDoughnutAverageEmployeeScoreByTimeCountGraph(data_values, title, label_value){
            var doughNutChartAverageEmployeeScoreByTimeCanvas = $("#doughNutChartAverageEmployeeScoreByTime");
            doughNutChartAverageEmployeeScoreByTime = new Chart(doughNutChartAverageEmployeeScoreByTimeCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"b",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'průměrné skóre ze všech směn',
                                    font: {
                                        size: 8
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutTotalLateShiftCountGraph(data_values, title, label_value){
            var doughNutChartLateShiftsCountCanvas = $("#doughNutChartLateShiftsCount");
            doughNutChartLateShiftsCount = new Chart(doughNutChartLateShiftsCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"x",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'zpoždění celkově',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutTotalLateShiftHoursGraph(data_values, title, label_value){
            var doughNutChartTotalLateShiftHoursCanvas = $("#doughNutChartTotalLateShiftHours");
            doughNutChartTotalLateShiftHours = new Chart(doughNutChartTotalLateShiftHoursCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"h",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'zpoždění',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutTotalWorkedShiftHoursGraph(data_values, title, label_value){
            var doughNutChartTotalWorkedShiftHoursCanvas = $("#doughNutChartTotalWorkedShiftHours");
            doughNutChartTotalWorkedShiftHours = new Chart(doughNutChartTotalWorkedShiftHoursCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"h",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'celkově odpracováno',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutTotalShiftHoursGraph(data_values, title, label_value){
            var doughNutChartTotalShiftHoursCanvas = $("#doughNutChartTotalShiftHours");
            doughNutChartTotalShiftHours = new Chart(doughNutChartTotalShiftHoursCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"h",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'směny celkově',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderBarGraphShiftsTotalHours(data_values, title, label_value){
            var barChartShiftsTotalHoursCanvas = $("#barChartShiftsTotalHours");
            barChartShiftsTotalHours = new Chart(barChartShiftsTotalHoursCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphShiftsAssigned(data_values, title, label_value){
            var barChartShiftsAssignedCanvas = $("#barChartShiftsAssigned");
            barChartShiftsAssigned = new Chart(barChartShiftsAssignedCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderDoughnutMaxShiftHourGraph(data_values, title, label_value){
            var doughNutChartMaxShiftHourCanvas = $("#doughNutChartMaxShiftHour");
            doughNutChartMaxShiftHour = new Chart(doughNutChartMaxShiftHourCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"h",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'nejdelší směna',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutMinShiftHourGraph(data_values, title, label_value){
            var doughNutChartMinShiftHourCanvas = $("#doughNutChartMinShiftHour");
            doughNutChartMinShiftHour = new Chart(doughNutChartMinShiftHourCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"h",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'nejkratší směna',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAverageShiftHourGraph(data_values, title, label_value){
            var doughNutChartAverageShiftHourCanvas = $("#doughNutChartAverageShiftHour");
            doughNutChartAverageShiftHour = new Chart(doughNutChartAverageShiftHourCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"h",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'průměrná délka směny',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAverageEmployeeWorkScoreGraph(data_values, title, label_value){
            var doughNutChartAverageEmployeeWorkScoreCanvas = $("#doughNutChartAverageEmployeeWorkScore");
            doughNutChartAverageEmployeeWorkScore = new Chart(doughNutChartAverageEmployeeWorkScoreCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"b",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'průměrná pracovitost',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAverageEmployeeAbsenceScoreGraph(data_values, title, label_value){
            var doughNutChartAverageEmployeeAbsenceScoreCanvas = $("#doughNutChartAverageEmployeeAbsenceScore");
            doughNutChartAverageEmployeeAbsenceScore = new Chart(doughNutChartAverageEmployeeAbsenceScoreCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"b",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'průměrná dochvilnost',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAverageEmployeeReliabilityScoreGraph(data_values, title, label_value){
            var doughNutChartAverageEmployeeReliabilityScoreCanvas = $("#doughNutChartAverageEmployeeReliabilityScore");
            doughNutChartAverageEmployeeReliabilityScore = new Chart(doughNutChartAverageEmployeeReliabilityScoreCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"b",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'průměrná spolehlivost',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAverageEmployeeOverallScoreGraph(data_values, title, label_value){
            var doughNutChartAverageEmployeeOverallScoreCanvas = $("#doughNutChartAverageEmployeeOverallScore");
            doughNutChartAverageEmployeeOverallScore = new Chart(doughNutChartAverageEmployeeOverallScoreCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values+"b",
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'průměrné skóre zaměst. ',
                                    font: {
                                        size: 10
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutUnregisteredAttendanceCountGraph(data_values, title, label_value){
            var doughNutChartUnregisteredAttendanceCountCanvas = $("#doughNutChartUnregisteredAttendanceCount");
            doughNutChartUnregisteredAttendanceCount = new Chart(doughNutChartUnregisteredAttendanceCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 22,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'nezapsaných docházek',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutShiftsAssignedCountGraph(data_values, title, label_value){
            var doughNutChartAssignedShiftsCountCanvas = $("#doughNutChartAssignedShiftsCount");
            doughNutChartAssignedShiftsCount = new Chart(doughNutChartAssignedShiftsCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'obsazení směn',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderBarGraphAttendances(data_values, data_values_two, data_values_three, data_values_four, data_values_five, title, label_value,label_value_two, label_value_three, label_value_four, label_value_five){
            var barChartAttendancesCanvas = $("#barChartAttendances");
            barChartAttendances = new Chart(barChartAttendancesCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                        label: label_value,
                        data: data_values,
                        backgroundColor: ['#004c6d', '#004c6d', '#004c6d', '#004c6d', '#004c6d', '#004c6d', '#004c6d', '#004c6d', '#004c6d', '#004c6d', '#004c6d']
                        },
                        {
                            label: label_value_two,
                            data: data_values_two,
                            backgroundColor: ['#346888', '#346888', '#346888', '#346888', '#346888', '#346888', '#346888', '#346888', '#346888', '#346888', '#346888']
                        },
                        {
                            label: label_value_three,
                            data: data_values_three,
                            backgroundColor: ['#5886a5', '#5886a5', '#5886a5', '#5886a5', '#5886a5', '#5886a5', '#5886a5', '#5886a5', '#5886a5', '#5886a5', '#5886a5']
                        },
                        {
                            label: label_value_four,
                            data: data_values_four,
                            backgroundColor: ['#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2', '#7aa6c2']
                        },
                        {
                            label: label_value_five,
                            data: data_values_five,
                            backgroundColor: ['#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0', '#9dc6e0']
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: true,
                    },
                    scales: {
                        xAxes: [{
                            stacked:true,
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            stacked:true,
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] !== 0; // or >= 1 or ...
                            },
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderDoughnutAbsenceDeniedCountGraph(data_values, title, label_value){
            var doughNutChartAbsenceDeniedCountCanvas = $("#doughNutChartAbsenceDeniedCount");
            doughNutChartAbsenceDeniedCount = new Chart(doughNutChartAbsenceDeniedCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'odmítnutí směny',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAbsenceDiseaseCountGraph(data_values, title, label_value){
            var doughNutChartAbsenceDiseaseCountCanvas = $("#doughNutChartAbsenceDiseaseCount");
            doughNutChartAbsenceDiseaseCount = new Chart(doughNutChartAbsenceDiseaseCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'nepříchodů kvůli nemoci',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAbsenceNotCameCountGraph(data_values, title, label_value){
            var doughNutChartNotCameCountCanvas = $("#doughNutChartNotCameCount");
            doughNutChartNotCameCount = new Chart(doughNutChartNotCameCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'nepříchodů na směnu',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAbsenceLateCountGraph(data_values, title, label_value){
            var doughNutChartAbsenceLateCountCanvas = $("#doughNutChartAbsenceLateCount");
            doughNutChartAbsenceLateCount = new Chart(doughNutChartAbsenceLateCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'zpoždění na směnách',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutAbsenceCountGraph(data_values, title, label_value){
            var doughNutChartAbsenceCountCanvas = $("#doughNutChartAbsenceCount");
            doughNutChartAbsenceCount = new Chart(doughNutChartAbsenceCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'směn s absencí',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutOKCountGraph(data_values, title, label_value){
            var doughNutChartOKCountCanvas = $("#doughNutChartOKCount");
            doughNutChartOKCount = new Chart(doughNutChartOKCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text:  'směn v pořádku',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutHistoricalShiftsCountGraph(data_values, title, label_value){
            var doughNutChartHistoricalShiftsCountCanvas = $("#doughNutChartHistoricalShiftsCount");
            doughNutChartHistoricalShiftsCount = new Chart(doughNutChartHistoricalShiftsCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text: 'odpracovaných směn',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutUpcomingShiftsCountGraph(data_values, title, label_value){
            var doughNutChartUpcomingShiftsCountCanvas = $("#doughNutChartUpcomingShiftsCount");
            doughNutChartUpcomingShiftsCount = new Chart(doughNutChartUpcomingShiftsCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text: 'budoucích směn',
                                    font: {
                                        size: 12
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderDoughnutEmployeesCountGraph(data_values, title, label_value){
            var doughNutChartEmployeesCountCanvas = $("#doughNutChartEmployeesCount");
            doughNutChartEmployeesCount = new Chart(doughNutChartEmployeesCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                           display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                            {
                                text: 'zaměstnanců',
                                font: {
                                    size: 15
                                },
                                color: 'grey'
                            }]
                        },
                    },

                }
            })
        }

        function renderDoughnutShiftsCountGraph(data_values, title, label_value){
            var doughNutChartShiftsCountCanvas = $("#doughNutChartShiftsCount");
            doughNutChartShiftsCount = new Chart(doughNutChartShiftsCountCanvas, {
                type:'doughnut',
                data:{
                    labels: [label_value],
                    datasets:[{
                        data: [data_values],
                        backgroundColor: ['#665191'],
                        borderWidth: [0]
                    }]
                },
                options: {
                    cutoutPercentage: 80,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                                display: false
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display:false,
                        },
                        doughnutlabel: {
                            labels: [{
                                text: data_values,
                                font: {
                                    size: 24,
                                    color:'black',
                                    weight: 'bold'
                                }
                            },
                                {
                                    text: 'směn',
                                    font: {
                                        size: 15
                                    },
                                    color: 'grey'
                                }]
                        },
                    },

                }
            })
        }

        function renderBarGraphEmployees(data_values, title, label_value){
            var barEmployeesCanvas = $("#barChartEmployees");
            barChartEmployees = new Chart(barEmployeesCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[{
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        function renderBarGraphShifts(data_values, title, label_value){
            var barShiftsCanvas = $("#barChartShifts");
            barChartShifts = new Chart(barShiftsCanvas, {
                type:'bar',
                data:{
                    labels:['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                    datasets:[
                        {
                            label: label_value,
                            data: data_values,
                            backgroundColor: ['#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5', '#7aa6c2', '#5886a5']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontColor: "black",
                        text: title,
                        fontSize: 20,
                        position: "top",
                        padding: 25,
                        fontStyle:"normal"
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "black",
                            },
                            gridLines: {
                                display:false
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                display:false,
                                beginAtZero: true,
                                precision: 0,
                            },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: 'black',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold',
                                size:16
                            },
                        }
                    }
                }
            })
        }

        /* funkce, ktere umoznuji zmenu casu u sloupcovych grafu */
        $('#year_employees').change(function(){
            var rok = $( "#year_employees" ).val();
            $.ajax({
                url: '/company/statistics/employee/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartEmployees.destroy();
                    renderBarGraphEmployees(odpoved.data_employees,"Počet nových zaměstnanců dle měsíců","Počet nových zaměstnanců");
                },
            });
        });
        $('#year_shifts').change(function(){
            var rok = $("#year_shifts").val();
            $.ajax({
                url: '/company/statistics/shift/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    console.log(odpoved.data_shifts);
                    barChartShifts.destroy();
                    renderBarGraphShifts(odpoved.data_shifts,"Počet vypsaných směn dle měsíců","Počet vypsaných směn");
                },
            });
        });

        $('#year_attendances').change(function(){
            var rok = $("#year_attendances").val();
            $.ajax({
                url: '/company/statistics/attendances/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    console.log(odpoved.data_attendances);
                    barChartAttendances.destroy();
                    renderBarGraphAttendances(odpoved.absence_ok, odpoved.absence_not_come, odpoved.absence_delay,
                        odpoved.absence_disease, odpoved.absence_denied,"Analýza docházky dle měsíců", "V pořádku","Nepříchody", "Zpoždění", "Nemoci", "Odmítnutí");

                },
            });
        });

        $('#year_shifts_assigned').change(function(){
            var rok = $("#year_shifts_assigned").val();
            $.ajax({
                url: '/company/statistics/shiftsassigned/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShiftsAssigned.destroy();
                    renderBarGraphShiftsAssigned(odpoved.data_shifts_assigned,"Počet obsazených směn zaměstnanci dle měsíců","Počet obsazených směn zaměstnanci dle měsíců");
                },
            });
        });

        $('#year_shifts_total').change(function(){
            var rok = $("#year_shifts_total").val();
            $.ajax({
                url: '/company/statistics/shiftstotalhours/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShiftsTotalHours.destroy();
                    renderBarGraphShiftsTotalHours(odpoved.data_shifts_total_hours,"Celkový počet hodin směn dle měsíců","Celkový počet hodin směn dle měsíců");
                },
            });
        });

        $('#year_total_worked_hours').change(function(){
            var rok = $("#year_total_worked_hours").val();
            $.ajax({
                url: '/company/statistics/shiftstotalworkedhours/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShiftsTotalWorkedHours.destroy();
                    renderBarGraphShiftsTotalWorkedHours(odpoved.data_shifts_total_worked_hours, "Počet odpracovaných hodin na směnách dle měsíců", "Počet odpracovaných hodin na směnách dle měsíců");
                },
            });
        });

        $('#year_total_late_hours').change(function(){
            var rok = $("#year_total_late_hours").val();
            $.ajax({
                url: '/company/statistics/shiftstotallatehours/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShiftsTotalLateHours.destroy();
                    renderBarGraphShiftsTotalLateHours(odpoved.data_shifts_total_late_hours, "Počet celkových hodin zpoždění dle měsíců", "Počet celkových hodin zpoždění dle měsíců");
                },
            });
        });

        $('#year_late_flags_count').change(function(){
            var rok = $("#year_late_flags_count").val();
            $.ajax({
                url: '/company/statistics/lateflagscount/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShiftsTotalLateFlagsCount.destroy();
                    renderBarGraphShiftsTotalLateFlagsCount(odpoved.data_shifts_late_flags_count, "Počet zpoždění dle měsíců", "Počet zpoždění dle měsíců");
                },
            });
        });

        $('#year_injuries_flags_count').change(function(){
            var rok = $("#year_injuries_flags_count").val();
            $.ajax({
                url: '/company/statistics/injuriesflagscount/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartShiftsTotalInjuriesFlagsCount.destroy();
                    renderBarGraphShiftsTotalInjuriesFlagsCount(odpoved.data_shifts_injuries_flags_count, "Počet zranění na směnách dle měsíců", "Počet zranění na směnách dle měsíců");
                },
            });
        });

        $('#vacations_year').change(function(){
            var rok = $("#vacations_year").val();
            $.ajax({
                url: '/company/statistics/vacations/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartVacationsByMonths.destroy();
                    renderBarGraphVacationsByMonths(odpoved.data_vacations,"Počet dovolených dle měsíců","Počet dovolených dle měsíců");
                },
            });
        });

        $('#diseases_year').change(function(){
            var rok = $("#diseases_year").val();
            $.ajax({
                url: '/company/statistics/diseases/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartDiseasesByMonths.destroy();
                    renderBarGraphDiseasesByMonths(odpoved.data_diseases,"Počet nemocenských dle měsíců","Počet nemocenských dle měsíců");
                },
            });
        });

        $('#reports_year').change(function(){
            var rok = $("#reports_year").val();
            $.ajax({
                url: '/company/statistics/reports/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartReportsByMonths.destroy();
                    renderBarGraphReportsByMonths(odpoved.data_reports,"Počet nahlášení dle měsíců","Počet nahlášení dle měsíců");
                },
            });
        });

        $('#average_score_by_time_year').change(function(){
            var rok = $("#average_score_by_time_year").val();
            $.ajax({
                url: '/company/statistics/averagescorebytime/chart/year/'+rok,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function (odpoved) {
                    barChartAverageEmployeesScoreByTime.destroy();
                    renderBarGraphAverageEmployeesScoreByTime(odpoved.data_score, "Vývoj průměrného skóre zaměstnanců v čase", "Vývoj průměrného skóre zaměstnanců v čase");
                },
            });
        });

        /* Sekce ziskani dat (statistik) do promennych a nasledne zavolani metod pro vykreslovani jednotlivych grafu */
        var data_employees_count = {{$pocetZamestnancu}};
        renderDoughnutEmployeesCountGraph(data_employees_count,"Počet zaměstnanců","Počet zaměstnanců");
        var data_employees = {{$data_employees}};
        renderBarGraphEmployees(data_employees,"Počet nových zaměstnanců dle měsíců","Počet nových zaměstnanců");
        var data_shifts = {{$data_shifts}};
        renderBarGraphShifts(data_shifts,"Počet vypsaných směn dle měsíců","Počet vypsaných směn");
        var data_shifts_count = {{$pocetSmen}};
        renderDoughnutShiftsCountGraph(data_shifts_count,"Počet směn", "Počet směn");
        var data_shifts_upcoming_count = {{$pocetNadchazejicich}};
        renderDoughnutUpcomingShiftsCountGraph(data_shifts_upcoming_count, "Počet budoucích směn", "Počet budoucích směn");
        var data_shifts_historical_count = {{$pocetHistorie}};
        renderDoughnutHistoricalShiftsCountGraph(data_shifts_historical_count, "Počet odpracovaných směn", "Počet odpracovaných směn");
        var attendance_ok_count = {{$company_ok_count}};
        renderDoughnutOKCountGraph(attendance_ok_count, "Počet směn bez absence či zpoždění", "Počet směn bez absence či zpoždění");
        var attendance_absence_count = {{$company_absences_count}};
        renderDoughnutAbsenceCountGraph(attendance_absence_count, "Celkový počet absencí", "Celkový počet absencí");
        var attendance_absence_late_count = {{$company_late_count}};
        renderDoughnutAbsenceLateCountGraph(attendance_absence_late_count,"Celkový počet zpoždění na směnách", "Celkový počet zpoždění na směnách");
        var attendance_absence_not_came_count = {{$company_not_came_count}};
        renderDoughnutAbsenceNotCameCountGraph(attendance_absence_not_came_count,"Celkový počet nepříchodů na směnu","Celkový počet nepříchodů na směnu");
        var attendance_absence_disease_count = {{$company_disease_count}};
        renderDoughnutAbsenceDiseaseCountGraph(attendance_absence_disease_count,"Celkový počet nepříchodů kvůli nemoci","Celkový počet nepříchodů kvůli nemoci");
        var attendance_absence_denied_count = {{$company_denied_count}};
        renderDoughnutAbsenceDeniedCountGraph(attendance_absence_denied_count,"Celkový počet odmítnutí směn","Celkový počet odmítnutí směn");
        var data_attendances_absence_disease = {{$data_attendances_absence_disease}};
        var data_attendances_absence_not_come = {{$data_attendances_absence_not_come}};
        var data_attendances_absence_denied = {{$data_attendances_absence_denied}};
        var data_attendances_delay = {{$data_attendances_delay}};
        var data_attendances_ok = {{$data_attendances_ok}};
        renderBarGraphAttendances(data_attendances_ok, data_attendances_absence_not_come, data_attendances_delay,
                                           data_attendances_absence_disease, data_attendances_absence_denied,"Analýza docházky dle měsíců", "V pořádku","Nepříchody", "Zpoždění", "Nemoci", "Odmítnutí");
        var shifts_assigned_count = {{$data_assigned_shifts_count}};
        renderDoughnutShiftsAssignedCountGraph(shifts_assigned_count,"Celkový počet obsazení směn","Celkový počet obsazení směn");
        var unregistered_attendance_count = {{$data_unregistered_absence_shifts}};
        renderDoughnutUnregisteredAttendanceCountGraph(unregistered_attendance_count, "Celkový počet směn s nezapsanou docházkou", "Celkový počet směn s nezapsanou docházkou");
        var data_average_employee_overall_score = {{$data_average_overall_score}};
        renderDoughnutAverageEmployeeOverallScoreGraph(data_average_employee_overall_score, "Průměrné celkové skóre zaměstnance","Průměrné celkové skóre zaměstnance");
        var data_average_employee_realibility_score = {{$data_average_reliability_score}};
        renderDoughnutAverageEmployeeReliabilityScoreGraph(data_average_employee_realibility_score,"Průměrná spolehlivost zaměstnance","Průměrná spolehlivost zaměstnance");
        var data_average_employee_absence_score = {{$data_average_absence_score}};
        renderDoughnutAverageEmployeeAbsenceScoreGraph(data_average_employee_absence_score,"Průměrná dochvilnost zaměstnance","Průměrná dochvilnost zaměstnance");
        var data_average_employee_work_score = {{$data_average_work_score}};
        renderDoughnutAverageEmployeeWorkScoreGraph(data_average_employee_work_score,"Průměrná pracovitost zaměstnance","Průměrná pracovitost zaměstnance");
        var data_average_shift_hour = {{$data_average_shift_hour}};
        renderDoughnutAverageShiftHourGraph(data_average_shift_hour,"Průměrná délka směny","Průměrná délka směny");
        var data_average_min_hour = {{$data_min_shift_hour}};
        renderDoughnutMinShiftHourGraph(data_average_min_hour,"Nejkratší směna","Nejkratší směna");
        var data_average_max_hour = {{$data_max_shift_hour}};
        renderDoughnutMaxShiftHourGraph(data_average_max_hour,"Nejdelší směna","Nejdelší směna");
        var data_assigned_shifts_by_month = {{$data_shifts_assigned_by_months}};
        renderBarGraphShiftsAssigned(data_assigned_shifts_by_month,"Počet zaměstnaneckých směn dle měsíců","Počet zaměstnaneckých směn dle měsíců");
        var data_shifts_total_hours_by_month = {{$data_shifts_total_hours_by_months}};
        renderBarGraphShiftsTotalHours(data_shifts_total_hours_by_month,"Součet hodin zaměstnaneckých směn dle měsíců","Součet hodin zaměstnaneckých směn dle měsíců");
        var data_shifts_total_hours = {{$data_shifts_total_hours}};
        renderDoughnutTotalShiftHoursGraph(data_shifts_total_hours, "Součet hodin zaměstnaneckých směn", "Součet hodin zaměstnaneckých směn");
        var data_shifts_total_worked_hours = {{$data_shifts_total_worked_hours}};
        renderDoughnutTotalWorkedShiftHoursGraph(data_shifts_total_worked_hours, "Počet celkově odpracovaných hodin na zaměstnaneckých směnách", "Počet celkově odpracovaných hodin na zaměstnaneckých směnách");
        var data_shifts_total_late_hours = {{$data_shifts_total_late_hours}};
        renderDoughnutTotalLateShiftHoursGraph(data_shifts_total_late_hours,"Počet celkových hodin zpoždění zaměstnanců", "Počet celkových hodin zpoždění zaměstnanců");
        var data_shifts_total_late_count = {{$data_shifts_total_late_count}};
        renderDoughnutTotalLateShiftCountGraph(data_shifts_total_late_count, "Celkový počet zpoždění na směnách", "Celkový počet zpoždění na směnách");
        var data_employees_average_score_by_time = {{$data_employees_average_employee_score_by_time}};
        renderDoughnutAverageEmployeeScoreByTimeCountGraph(data_employees_average_score_by_time, "Průměrné skóre zaměstnanců v čase ze všech směn", "Průměrné skóre zaměstnanců v čase ze všech směn");
        var data_shifts_total_worked_hours_by_month = {{$data_shifts_total_worked_hours_by_months}};
        renderBarGraphShiftsTotalWorkedHours(data_shifts_total_worked_hours_by_month, "Počet odpracovaných hodin na zaměstnaneckých směnách dle měsíců", "Počet odpracovaných hodin na zaměstnaneckých směnách dle měsíců");
        var data_shifts_total_late_hours_by_month = {{$data_shifts_total_late_hours_by_months}};
        renderBarGraphShiftsTotalLateHours(data_shifts_total_late_hours_by_month, "Počet celkových hodin zpoždění zaměstnanců dle měsíců", "Počet celkových hodin zpoždění zaměstnanců dle měsíců");
        var data_shifts_total_late_flags_count = {{$data_shifts_total_late_flags_count_by_months}};
        renderBarGraphShiftsTotalLateFlagsCount(data_shifts_total_late_flags_count, "Počet zpoždění zaměstnanců, v rámci směn, dle měsíců", "Počet zpoždění zaměstnanců, v rámci směn, dle měsíců");
        var data_shift_total_injuries_flags_count = {{$data_injuries_count_by_month}};
        renderBarGraphShiftsTotalInjuriesFlagsCount(data_shift_total_injuries_flags_count, "Počet zranění zaměstnanců na směnách dle měsíců", "Počet zranění zaměstnanců na směnách dle měsíců");
        var data_vacations_count = {{$data_vacations_total_count}};
        renderDoughnutVacationsCountGraph(data_vacations_count, "Celkový počet dovolených", "Celkový počet dovolených");
        var data_diseases_count = {{$data_diseases_total_count}};
        renderDoughnutDiseasesCountGraph(data_diseases_count, "Celkový počet nemocenských", "Celkový počet nemocenských");
        var data_injuries_count = {{$data_injuries_total_count}};
        renderDoughnutInjuriesCountGraph(data_injuries_count, "Celkový počet zranění", "Celkový počet zranění");
        var data_reports_count = {{$data_reports_total_count}};
        renderDoughnutReportsCountGraph(data_reports_count, "Celkový počet nahlášení", "Celkový počet nahlášení");

        var data_vacations_by_months = {{$data_vacations_count_by_month}};
        renderBarGraphVacationsByMonths(data_vacations_by_months,"Počet dovolených zaměstnanců dle měsíců","Počet dovolených zaměstnanců dle měsíců");
        var data_diseases_by_months = {{$data_diseases_count_by_month}};
        renderBarGraphDiseasesByMonths(data_diseases_by_months,"Počet nemocenských zaměstnanců dle měsíců","Počet nemocenských zaměstnanců dle měsíců");
        var data_reports_by_months = {{$data_reports_count_by_month}};
        renderBarGraphReportsByMonths(data_reports_by_months,"Počet nahlášení zaměstnanců dle měsíců","Počet nahlášení zaměstnanců dle měsíců");
        var data_employee_average_score_by_months = {{$data_average_employees_scores_by_months}};
        renderBarGraphAverageEmployeesScoreByTime(data_employee_average_score_by_months, "Vývoj průměrného skóre zaměstnanců v čase", "Vývoj průměrného skóre zaměstnanců v čase");
    </script>
@endsection
