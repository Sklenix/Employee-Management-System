@extends('layouts.company_dashboard')
@section('title') - Statistiky @endsection
@section('content')
    <section class="page-section"  id="generator" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;">
            <div class="row">
                <div class=" col-lg-1" style="font-size: 15px;">
                </div>
                <div class=" col-lg-10 text-center" style="font-size: 15px;">
                    @if($message = Session::get('success'))
                        <div class="alert alert-success alert-block" style="margin-top: 15px;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif
                    @if($message = Session::get('fail'))
                        <div class="alert alert-danger alert-block" style="margin-top: 15px;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
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
                <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-doughnutlabel/2.0.3/chartjs-plugin-doughnutlabel.js"></script>

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


                <div class="col-5" style="margin-top: 50px;">
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Účet vytvořen</strong></span> {{$vytvorenUcet}}</li>
                </div>
            </center>
                <script>
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
                                    backgroundColor: ['#a05195'],
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
                                    backgroundColor: ['#de425b'],
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
                                    backgroundColor: ['#ff7c43'],
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
                                        backgroundColor: ['#488f31', '#83af70', '#488f31', '#83af70', '#488f31', '#83af70', '#488f31', '#83af70', '#488f31', '#83af70', '#488f31']
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
    ////////////////////////////////////////////////////////////////////////////////////
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
                    $('#year_employees').change(function(){
                        var rok = $( "#year_employees" ).val();
                        $.ajax({
                            url: '/company/statistics/employee/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                barChartEmployees.destroy();
                                renderBarGraphEmployees(response.data_employees,"Počet nových zaměstnanců dle měsíců","Počet nových zaměstnanců");
                            },
                        });
                    });
                    $('#year_shifts').change(function(){
                        var rok = $("#year_shifts").val();
                        $.ajax({
                            url: '/company/statistics/shift/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log(response.data_shifts);
                                barChartShifts.destroy();
                                renderBarGraphShifts(response.data_shifts,"Počet vypsaných směn dle měsíců","Počet vypsaných směn");
                            },
                        });
                    });

                    $('#year_attendances').change(function(){
                        var rok = $("#year_attendances").val();
                        $.ajax({
                            url: '/company/statistics/attendances/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log(response.data_attendances);
                                barChartAttendances.destroy();
                                renderBarGraphAttendances(response.absence_ok, response.absence_not_come, response.absence_delay,
                                    response.absence_disease, response.absence_denied,"Analýza docházky dle měsíců", "V pořádku","Nepříchody", "Zpoždění", "Nemoci", "Odmítnutí");

                            },
                        });
                    });

                    var data_employees_count = <?php echo $pocetZamestnancu; ?>;
                    renderDoughnutEmployeesCountGraph(data_employees_count,"Počet zaměstnanců","Počet zaměstnanců");
                    var data_employees = <?php echo json_encode($data_employees); ?>;
                    renderBarGraphEmployees(data_employees,"Počet nových zaměstnanců dle měsíců","Počet nových zaměstnanců");
                    var data_shifts = <?php echo json_encode($data_shifts); ?>;
                    renderBarGraphShifts(data_shifts,"Počet vypsaných směn dle měsíců","Počet vypsaných směn");
                    var data_shifts_count = <?php echo json_encode($pocetSmen); ?>;
                    renderDoughnutShiftsCountGraph(data_shifts_count,"Počet směn", "Počet směn");
                    var data_shifts_upcoming_count = <?php echo json_encode($pocetNadchazejicich); ?>;
                    renderDoughnutUpcomingShiftsCountGraph(data_shifts_upcoming_count, "Počet budoucích směn", "Počet budoucích směn");
                    var data_shifts_historical_count = <?php echo json_encode($pocetHistorie); ?>;
                    renderDoughnutHistoricalShiftsCountGraph(data_shifts_historical_count, "Počet odpracovaných směn", "Počet odpracovaných směn");
                    var attendance_ok_count = <?php echo json_encode($company_ok_count); ?>;
                    renderDoughnutOKCountGraph(attendance_ok_count, "Počet směn bez absence či zpoždění", "Počet směn bez absence či zpoždění");
                    var attendance_absence_count = <?php echo json_encode($company_absences_count); ?>;
                    renderDoughnutAbsenceCountGraph(attendance_absence_count, "Celkový počet absencí", "Celkový počet absencí");
                    var attendance_absence_late_count = <?php echo json_encode($company_late_count); ?>;
                    renderDoughnutAbsenceLateCountGraph(attendance_absence_late_count,"Celkový počet zpoždění na směnách", "Celkový počet zpoždění na směnách");
                    var attendance_absence_not_came_count = <?php echo json_encode($company_not_came_count); ?>;
                    renderDoughnutAbsenceNotCameCountGraph(attendance_absence_not_came_count,"Celkový počet nepříchodů na směnu","Celkový počet nepříchodů na směnu");
                    var attendance_absence_disease_count = <?php echo json_encode($company_disease_count); ?>;
                    renderDoughnutAbsenceDiseaseCountGraph(attendance_absence_disease_count,"Celkový počet nepříchodů kvůli nemoci","Celkový počet nepříchodů kvůli nemoci");
                    var attendance_absence_denied_count = <?php echo json_encode($company_denied_count); ?>;
                    renderDoughnutAbsenceDeniedCountGraph(attendance_absence_denied_count,"Celkový počet odmítnutí směn","Celkový počet odmítnutí směn");
                    var data_attendances_absence_disease = <?php echo json_encode($data_attendances_absence_disease); ?>;
                    var data_attendances_absence_not_come = <?php echo json_encode($data_attendances_absence_not_come); ?>;
                    var data_attendances_absence_denied = <?php echo json_encode($data_attendances_absence_denied); ?>;
                    var data_attendances_delay = <?php echo json_encode($data_attendances_delay); ?>;
                    var data_attendances_ok = <?php echo json_encode($data_attendances_ok); ?>;

                    renderBarGraphAttendances(data_attendances_ok, data_attendances_absence_not_come, data_attendances_delay,
                                                       data_attendances_absence_disease, data_attendances_absence_denied,"Analýza docházky dle měsíců", "V pořádku","Nepříchody", "Zpoždění", "Nemoci", "Odmítnutí");

                </script>

        </div>
    </section>
@endsection
