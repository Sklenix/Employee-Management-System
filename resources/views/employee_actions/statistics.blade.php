@extends('layouts.employee_dashboard')
@section('title') - Statistiky @endsection
@section('content')
    <section class="page-section"  id="generator" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;">
            <div class="row">
                <div class="col-lg-12">
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
            </div>
                <div class="col-lg-1"></div>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-doughnutlabel/2.0.3/chartjs-plugin-doughnutlabel.js"></script>
                <div class="row justify-content-center">
                    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                        <canvas id="DoughnutChartVacationsCount"></canvas>
                    </div>
                    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                        <canvas id="DoughnutChartDiseasesCount"></canvas>
                    </div>
                    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                        <canvas id="DoughnutChartInjuriesCount"></canvas>
                    </div>
                    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                        <canvas id="DoughnutChartReportsCount"></canvas>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="row justify-content-center">
                    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                        <canvas id="DoughnutChartShiftsTotalCount"></canvas>
                    </div>
                    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                        <canvas id="DoughnutChartUpcomingShiftsTotalCount"></canvas>
                    </div>
                    <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                        <canvas id="DoughnutChartTotalAbsenceCount"></canvas>
                    </div>
                </div>

                <div class="row justify-content-center" style="margin-bottom: 60px;margin-top: 75px;">
                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        <select class="form-control" id="year_shifts_assigned">
                            <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                        &nbsp;<canvas id="barChartShiftsAssigned"></canvas>
                    </div>
                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        <select class="form-control" id="year_shifts_total">
                            <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                        &nbsp;<canvas id="barChartShiftsTotalHours"></canvas>
                    </div>
                </div>
            <div class="row justify-content-center" style="margin-top: 150px;">
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalShiftsHoursCount"></canvas>
                </div>
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalShiftsHoursThisWeek"></canvas>
                </div>
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalShiftsHoursThisMonth"></canvas>
                </div>
            </div>

            <div class="row justify-content-center" style="margin-top:75px;margin-bottom: 60px;">
                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                     <select class="form-control" id="year_total_worked_hours">
                        <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                     </select>
                    &nbsp;<canvas id="barChartShiftsTotalWorkedHours"></canvas>
                </div>

                <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                     <select class="form-control" id="year_total_late_hours">
                        <option value="2021">Vyberte rok (defaultně je nastaven rok 2021)</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                     </select>
                    &nbsp;<canvas id="barChartShiftsTotalLateHours"></canvas>
                </div>
            </div>

            <div class="row justify-content-center" style="margin-top: 150px;">
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalWorkedShiftsHoursCount"></canvas>
                </div>
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalWorkedShiftsHoursThisWeek"></canvas>
                </div>
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalWorkedShiftsHoursThisMonth"></canvas>
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
            <div class="row justify-content-center" style="margin-top: 100px;">
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalLateFlagsCountNumber"></canvas>
                </div>
                <div class="col-lg-2" style="max-width: 500px;max-height: 500px;margin-top: 15px;">
                    <canvas id="DoughnutChartTotalLateHoursCountNumber"></canvas>
                </div>
            </div>
            <center>
                <div class="col-5" style="margin-top: 50px;">
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Účet vytvořen</strong></span> {{$datumVytvoreni}}</li>
                </div>
            </center>
                <script>
                    var DoughnutChartVacationsCount;
                    var DoughnutChartVacationsCountCanvas;
                    var DoughnutChartDiseasesCount;
                    var DoughnutChartDiseasesCountCanvas;
                    var DoughnutChartInjuriesCount;
                    var DoughnutChartInjuriesCountCanvas;
                    var DoughnutChartReportsCount;
                    var DoughnutChartReportsCountCanvas;
                    var DoughnutChartShiftsTotalCount;
                    var DoughnutChartShiftsTotalCountCanvas;
                    var DoughnutChartUpcomingShiftsTotalCount;
                    var DoughnutChartUpcomingShiftsTotalCountCanvas;
                    var DoughnutChartTotalAbsenceCount;
                    var DoughnutChartTotalAbsenceCountCanvas;

                    var DoughnutChartTotalShiftsHoursCount;
                    var DoughnutChartTotalShiftsHoursCountCanvas;
                    var DoughnutChartTotalShiftsHoursThisWeek;
                    var DoughnutChartTotalShiftsHoursThisWeekCanvas;
                    var DoughnutChartTotalShiftsHoursThisMonth;
                    var DoughnutChartTotalShiftsHoursThisMonthCanvas;

                    var DoughnutChartTotalWorkedShiftsHoursCount;
                    var DoughnutChartTotalWorkedShiftsHoursCountCanvas;
                    var DoughnutChartTotalWorkedShiftsHoursThisWeek;
                    var DoughnutChartTotalWorkedShiftsHoursThisWeekCanvas;
                    var DoughnutChartTotalWorkedShiftsHoursThisMonth;
                    var DoughnutChartTotalWorkedShiftsHoursThisMonthCanvas;

                    var DoughnutChartTotalLateFlagsCountNumber;
                    var DoughnutChartTotalLateFlagsCountNumberCanvas;
                    var DoughnutChartTotalLateHoursCountNumber;
                    var DoughnutChartTotalLateHoursCountNumberCanvas;

                    var barChartShiftsAssigned;
                    var barChartShiftsTotalHours;
                    var barChartShiftsTotalWorkedHours;
                    var barChartShiftsTotalLateHours;
                    var barChartShiftsTotalLateFlagsCount;
                    var barChartShiftsTotalInjuriesFlagsCount;

                    function renderBarGraphInjuryFlagsCount(data_values, title, label_value) {
                        var barChartShiftsTotalInjuriesFlagsCountCanvas = $("#barChartShiftsTotalInjuriesFlagsCount");
                        barChartShiftsTotalInjuriesFlagsCount = new Chart(barChartShiftsTotalInjuriesFlagsCountCanvas, {
                            type: "bar",
                            data: {
                                labels: ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"],
                                datasets: [{
                                    label: label_value,
                                    data: data_values,
                                    backgroundColor: ["#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f", "#d9534f"]
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
                                    fontStyle: "normal"
                                },
                                legend: {display: false,},
                                scales: {
                                    xAxes: [{
                                        ticks: {fontColor: "black",},
                                        gridLines: {display: false,},
                                    }],
                                    yAxes: [{
                                        ticks: {display: false, beginAtZero: true, precision: 0,},
                                    }]
                                },
                                plugins: {
                                    datalabels: {
                                        color: "black", align: "top",
                                        font: {weight: "bold", size: 16},
                                    }
                                }
                            }
                        })
                    }

                    function renderBarGraphLateFlagsCount(data_values, title, label_value){
                        var barChartShiftsTotalLateFlagsCountCanvas = $("#barChartShiftsTotalLateFlagsCount");
                        barChartShiftsTotalLateFlagsCount = new Chart(barChartShiftsTotalLateFlagsCountCanvas, {
                            type:"bar",
                            data:{
                                labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                                datasets:[{label: label_value, data: data_values, backgroundColor: ["#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f"]}]
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

                    function renderBarGraphLateHours(data_values, title, label_value){
                        var barChartShiftsTotalLateHoursCanvas = $("#barChartShiftsTotalLateHours");
                        barChartShiftsTotalLateHours = new Chart(barChartShiftsTotalLateHoursCanvas, {
                            type:"bar",
                            data:{
                                labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                                datasets:[{label: label_value, data: data_values, backgroundColor: ["#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f"]}]
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

                    function renderBarGraphShiftAssigned(data_values, title, label_value){
                        var barChartShiftsAssignedCanvas = $("#barChartShiftsAssigned");
                        barChartShiftsAssigned = new Chart(barChartShiftsAssignedCanvas, {
                            type:"bar",
                            data:{
                                labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                                datasets:[{label: label_value, data: data_values, backgroundColor: ["#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f"]}]
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

                    function renderBarGraphShiftsTotalWorkedHours(data_values, title, label_value){
                        var barChartShiftsTotalWorkedHoursCanvas = $("#barChartShiftsTotalWorkedHours");
                        barChartShiftsTotalWorkedHours = new Chart(barChartShiftsTotalWorkedHoursCanvas, {
                            type:"bar",
                            data:{
                                labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                                datasets:[{label: label_value, data: data_values, backgroundColor: ["#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f"]}]
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

                    function renderBarGraphShiftsTotalHours(data_values, title, label_value){
                        var barChartShiftsTotalHoursCanvas = $("#barChartShiftsTotalHours");
                        barChartShiftsTotalHours = new Chart(barChartShiftsTotalHoursCanvas, {
                            type:"bar",
                            data:{
                                labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                                datasets:[{label: label_value, data: data_values, backgroundColor: ["#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f"]}]
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

                    function renderDoughnutGraph(data_values, title, label_value, element, canvas_element, element_id){
                        canvas_element = $(element_id);
                        element = new Chart(canvas_element, {
                            type:'doughnut',
                            data:{
                                labels: [label_value],
                                datasets:[{
                                    data: [data_values],
                                    backgroundColor: ["#d9534f"],
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

                    $('#year_shifts_assigned').change(function(){
                        var rok = $("#year_shifts_assigned").val();
                        $.ajax({
                            url: '/employee/statistics/shiftsassigned/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                barChartShiftsAssigned.destroy();
                                renderBarGraphShiftAssigned(response.data_shifts_assigned,"Počet směn zaměstnanců dle měsíců","Počet směn zaměstnanců dle měsíců");
                            },
                        });
                    });

                    $('#year_shifts_total').change(function(){
                        var rok = $("#year_shifts_total").val();
                        $.ajax({
                            url: '/employee/statistics/shiftstotalhours/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                barChartShiftsTotalHours.destroy();
                                renderBarGraphShiftsTotalHours(response.data_shifts_total_hours,"Celkový počet hodin směn dle měsíců","Celkový počet hodin směn dle měsíců");
                            },
                        });
                    });

                    $('#year_total_worked_hours').change(function(){
                        var rok = $("#year_total_worked_hours").val();
                        $.ajax({
                            url: '/employee/statistics/shiftstotalworkedhours/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                barChartShiftsTotalWorkedHours.destroy();
                                renderBarGraphShiftsTotalWorkedHours(response.data_shifts_total_worked_hours, "Počet odpracovaných hodin na směnách dle měsíců", "Počet odpracovaných hodin na směnách dle měsíců");
                            },
                        });
                    });

                    $('#year_total_late_hours').change(function(){
                        var rok = $("#year_total_late_hours").val();
                        $.ajax({
                            url: '/employee/statistics/shiftstotallatehours/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                barChartShiftsTotalLateHours.destroy();
                                renderBarGraphLateHours(response.data_shifts_total_late_hours, "Počet celkových hodin zpoždění dle měsíců", "Počet celkových hodin zpoždění dle měsíců");
                            },
                        });
                    });

                    $('#year_late_flags_count').change(function(){
                        var rok = $("#year_late_flags_count").val();
                        $.ajax({
                            url: '/employee/statistics/lateflagscount/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                barChartShiftsTotalLateFlagsCount.destroy();
                                renderBarGraphLateFlagsCount(response.data_shifts_late_flags_count, "Počet zpoždění dle měsíců", "Počet zpoždění dle měsíců");
                            },
                        });
                    });

                    $('#year_injuries_flags_count').change(function(){
                        var rok = $("#year_injuries_flags_count").val();
                        $.ajax({
                            url: '/employee/statistics/injuriesflagscount/chart/year/'+rok,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                barChartShiftsTotalInjuriesFlagsCount.destroy();
                                renderBarGraphInjuryFlagsCount(response.data_shifts_injuries_flags_count, "Počet zranění na směnách dle měsíců", "Počet zranění na směnách dle měsíců");
                            },
                        });
                    });

                    var data_vacations_count = <?php echo $pocetDovolenych; ?>;
                    renderDoughnutGraph(data_vacations_count,"dovolených","Celkový počet dovolených",DoughnutChartVacationsCount, DoughnutChartVacationsCountCanvas, "#DoughnutChartVacationsCount");
                    var data_diseases_count = <?php echo $pocetNemocenskych; ?>;
                    renderDoughnutGraph(data_diseases_count,"nemocenských","Celkový počet nemocenských",DoughnutChartDiseasesCount, DoughnutChartDiseasesCountCanvas, "#DoughnutChartDiseasesCount");
                    var data_injuries_count = <?php echo $pocetZraneni; ?>;
                    renderDoughnutGraph(data_injuries_count,"zranění","Celkový počet zranění",DoughnutChartInjuriesCount, DoughnutChartInjuriesCountCanvas, "#DoughnutChartInjuriesCount");
                    var data_reports_count = <?php echo $pocetNahlaseni; ?>;
                    renderDoughnutGraph(data_reports_count,"nahlášení","Celkový počet nahlášení",DoughnutChartReportsCount, DoughnutChartReportsCountCanvas, "#DoughnutChartReportsCount");
                    var data_shifts_total_count = <?php echo $pocetSmen; ?>;
                    renderDoughnutGraph(data_shifts_total_count,"směn","Celkový počet směn",DoughnutChartShiftsTotalCount, DoughnutChartShiftsTotalCountCanvas, "#DoughnutChartShiftsTotalCount");
                    var data_upcoming_shifts_total_count = <?php echo $pocetBudoucichSmen; ?>;
                    renderDoughnutGraph(data_upcoming_shifts_total_count,"budoucích směn","Celkový počet budoucích směn",DoughnutChartUpcomingShiftsTotalCount, DoughnutChartUpcomingShiftsTotalCount, "#DoughnutChartUpcomingShiftsTotalCount");
                    var data_absence_total_count = <?php echo $pocetAbsenci; ?>;
                    renderDoughnutGraph(data_absence_total_count,"absencí","Celkový počet absencí",DoughnutChartTotalAbsenceCount, DoughnutChartTotalAbsenceCountCanvas, "#DoughnutChartTotalAbsenceCount");
                    var data_shifts_total_hours_count = <?php echo $celkovyPocetHodinSmeny; ?>;
                    renderDoughnutGraph(data_shifts_total_hours_count,"hodin směn","Celkový počet hodin směn",DoughnutChartTotalShiftsHoursCount, DoughnutChartTotalShiftsHoursCountCanvas, "#DoughnutChartTotalShiftsHoursCount");
                    var data_shifts_total_hours_this_week = <?php echo $tydenniPocetHodin; ?>;
                    renderDoughnutGraph(data_shifts_total_hours_this_week,"hodin směn tento týden","Celkový počet hodin směn tento týden",DoughnutChartTotalShiftsHoursThisWeek, DoughnutChartTotalShiftsHoursThisWeekCanvas, "#DoughnutChartTotalShiftsHoursThisWeek");
                    var data_shifts_total_hours_this_month = <?php echo $mesicniPocetHodin; ?>;
                    renderDoughnutGraph(data_shifts_total_hours_this_month,"hodin směn tento měsíc","Celkový počet hodin směn tento měsíc",DoughnutChartTotalShiftsHoursThisMonth, DoughnutChartTotalShiftsHoursThisMonthCanvas, "#DoughnutChartTotalShiftsHoursThisMonth");


                    var data_shifts_total_worked_hours_count = <?php echo $celkoveOdpracovanoHodin; ?>;
                    renderDoughnutGraph(data_shifts_total_worked_hours_count,"odpracovaných hodin","Celkový počet odpracovaných hodin",DoughnutChartTotalWorkedShiftsHoursCount, DoughnutChartTotalWorkedShiftsHoursCountCanvas, "#DoughnutChartTotalWorkedShiftsHoursCount");
                    var data_shifts_total_worked_hours_this_week = <?php echo $tydenniOdpracovanyPocetHodin; ?>;
                    renderDoughnutGraph(data_shifts_total_worked_hours_this_week,"hodin tento týden","Celkový počet odpracovaných hodin tento týden",DoughnutChartTotalWorkedShiftsHoursThisWeek, DoughnutChartTotalWorkedShiftsHoursThisWeekCanvas, "#DoughnutChartTotalWorkedShiftsHoursThisWeek");
                    var data_shifts_total_worked_hours_this_month = <?php echo $mesicniOdpracovanyPocetHodin; ?>;
                    renderDoughnutGraph(data_shifts_total_worked_hours_this_month,"hodin tento měsíc","Celkový počet odpracovaných hodin tento měsíc",DoughnutChartTotalWorkedShiftsHoursThisMonth, DoughnutChartTotalWorkedShiftsHoursThisMonthCanvas, "#DoughnutChartTotalWorkedShiftsHoursThisMonth");
                    var data_shifts_total_late_flags_number = <?php echo $celkovyPocetZpozdeni; ?>;
                    renderDoughnutGraph(data_shifts_total_late_flags_number,"zpoždění","Celkový počet zpoždění",DoughnutChartTotalLateFlagsCountNumber, DoughnutChartTotalLateFlagsCountNumberCanvas, "#DoughnutChartTotalLateFlagsCountNumber");
                    var data_shifts_total_late_hours_number = <?php echo $celkovyPocetHodinZpozdeni; ?>;
                    renderDoughnutGraph(data_shifts_total_late_hours_number,"hodin zpoždění","Celkový počet hodin zpoždění",DoughnutChartTotalLateHoursCountNumber, DoughnutChartTotalLateHoursCountNumberCanvas, "#DoughnutChartTotalLateHoursCountNumber");

                    var data_assigned_shifts_by_months = <?php echo json_encode($pocetPrirazenychSmen); ?>;
                    renderBarGraphShiftAssigned(data_assigned_shifts_by_months,"Počet směn dle měsíců","Počet směn dle měsíců");
                    var data_total_hours_shifts_by_months = <?php echo json_encode($pocetHodinSmenDleMesicu); ?>;
                    renderBarGraphShiftsTotalHours(data_total_hours_shifts_by_months,"Celkový počet hodin směn dle měsíců", "Celkový počet hodin směn dle měsíců");
                    var data_total_worked_hours_by_months = <?php echo json_encode($pocetOdpracovanychHodinSmenDleMesicu); ?>;
                    renderBarGraphShiftsTotalWorkedHours(data_total_worked_hours_by_months,"Počet celkově odpracovaných hodin na směnách", "Počet celkově odpracovaných hodin na směnách");
                    var data_total_late_hours_by_months = <?php echo json_encode($pocetHodinZpozdeniSmenDleMesicu); ?>;
                    renderBarGraphLateHours(data_total_late_hours_by_months,"Počet celkových hodin zpoždění", "Počet celkových hodin zpoždění");
                    var data_total_late_flags_count_by_months = <?php echo json_encode($pocetZpozdeniSmenDleMesicu); ?>;
                    renderBarGraphLateFlagsCount(data_total_late_flags_count_by_months, "Počet zpoždění dle měsíců", "Počet zpoždění dle měsíců");
                    var data_total_injury_flags_count_by_months = <?php echo json_encode($pocetZraneniDleMesicu); ?>;
                    renderBarGraphInjuryFlagsCount(data_total_injury_flags_count_by_months,"Počet zranění na směnách dle měsíců", "Počet zranění na směnách dle měsíců");

                </script>
            </div>
    </section>
@endsection
