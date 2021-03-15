@extends('layouts.company_dashboard')
@section('title') - Statistiky @endsection
@section('content')
    <section class="page-section"  id="generator" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;padding-bottom: 1100px;">
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
                <div class="col-5" style="margin-top: 50px;">
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Účet vytvořen</strong></span> {{$vytvorenUcet}}</li>
                </div>
            </center>
                <script>
                    var barChartEmployees;
                    var barChartShifts;
                    var doughNutChartEmployeesCount;
                    var doughNutChartShiftsCount;

                    var doughNutChartUpcomingShiftsCount;
                    var doughNutChartHistoricalShiftsCount;

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
                </script>

        </div>
    </section>
@endsection
