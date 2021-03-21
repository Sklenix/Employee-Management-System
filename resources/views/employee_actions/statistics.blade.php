@extends('layouts.employee_dashboard')
@section('title') - Statistiky @endsection
@section('content')
    <section class="page-section"  id="generator" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;padding-bottom: 550px;">
            <div class="row">
                <br>
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

                <div class="row justify-content-center" style="margin-bottom: 60px;">
                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        &nbsp;<canvas id="barChartShiftsAssigned"></canvas>
                    </div>
                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        &nbsp;<canvas id="barChartShiftsTotalHours"></canvas>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-bottom: 60px;">
                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        &nbsp;<canvas id="barChartShiftsTotalWorkedHours"></canvas>
                    </div>

                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        &nbsp;<canvas id="barChartShiftsTotalLateHours"></canvas>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-bottom: 60px;">
                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        &nbsp;<canvas id="barChartShiftsTotalLateFlagsCount"></canvas>
                    </div>

                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        &nbsp;<canvas id="barChartShiftsTotalInjuriesFlagsCount"></canvas>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-bottom: 60px;">
                    <div class="col-lg-6" style="max-width: 700px;max-height: 500px;margin-top: 15px;">
                        &nbsp;<canvas id="barChartAverageEmployeesScoreByTime"></canvas>
                    </div>
                </div>
                <script>

                    var DoughnutChartVacationsCount;
                    var DoughnutChartVacationsCountCanvas;
                    var DoughnutChartDiseasesCount;
                    var DoughnutChartDiseasesCountCanvas;
                    var DoughnutChartInjuriesCount;
                    var DoughnutChartInjuriesCountCanvas;
                    var DoughnutChartReportsCount;
                    var DoughnutChartReportsCountCanvas;

                    var barChartShiftsAssigned;
                    var barChartShiftsAssignedCanvas;
                    var barChartShiftsTotalHours;
                    var barChartShiftsTotalHoursCanvas;
                    var barChartShiftsTotalWorkedHours;
                    var barChartShiftsTotalWorkedHoursCanvas;
                    var barChartShiftsTotalLateHours;
                    var barChartShiftsTotalLateHoursCanvas;

                    var barChartShiftsTotalLateFlagsCount;
                    var barChartShiftsTotalLateFlagsCountCanvas;
                    var barChartShiftsTotalInjuriesFlagsCount;
                    var barChartShiftsTotalInjuriesFlagsCountCanvas;
                    var barChartAverageEmployeesScoreByTime;
                    var barChartAverageEmployeesScoreByTimeCanvas;

                    function renderBarGraph(data_values, title, label_value, element, canvas_element, element_id){
                        canvas_element = $(element_id);
                        element = new Chart(canvas_element, {
                            type:"bar",
                            data:{
                                labels:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],
                                datasets:[
                                    {
                                        label: label_value,
                                        data: data_values,
                                        backgroundColor: ["#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f","#d9534f"]
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                title: {
                                    display: true,
                                    fontColor: "white",
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
                                            fontColor: "white",
                                        },
                                        gridLines: {
                                            display:false,
                                        },
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            display:false,
                                            beginAtZero: true,
                                            precision: 0,
                                        },
                                        gridLines: {
                                            display:false,
                                            color: "white",
                                            zeroLineColor: "white"
                                        },
                                    }]
                                },
                                plugins: {
                                    datalabels: {
                                        color: "white",
                                        align: "top",
                                        font: {
                                            weight: "bold",
                                            size:16
                                        },
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
                                                text:  title,
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
                    var data_vacations_count = <?php echo $pocetDovolenych; ?>;
                    renderDoughnutGraph(data_vacations_count,"dovolených","Celkový počet dovolených",DoughnutChartVacationsCount, DoughnutChartVacationsCountCanvas, "#DoughnutChartVacationsCount");
                    var data_diseases_count = <?php echo $pocetNemocenskych; ?>;
                    renderDoughnutGraph(data_diseases_count,"nemocenských","Celkový počet nemocenských",DoughnutChartDiseasesCount, DoughnutChartDiseasesCountCanvas, "#DoughnutChartDiseasesCount");
                    var data_injuries_count = <?php echo $pocetZraneni; ?>;
                    renderDoughnutGraph(data_injuries_count,"zranění","Celkový počet zranění",DoughnutChartInjuriesCount, DoughnutChartInjuriesCountCanvas, "#DoughnutChartInjuriesCount");
                    var data_reports_count = <?php echo $pocetNahlaseni; ?>;
                    renderDoughnutGraph(data_reports_count,"nahlášení","Celkový počet nahlášení",DoughnutChartReportsCount, DoughnutChartReportsCountCanvas, "#DoughnutChartReportsCount");


                  /*  var data_assigned_shifts_by_months = '.json_encode($shifts_employee_assigned_count_by_months).';
                    renderBarGraph(data_assigned_shifts_by_months,"Počet směn dle měsíců","Počet směn dle měsíců",barChartShiftsAssigned, barChartShiftsAssignedCanvas, "#barChartShiftsAssigned");
                    var data_total_hours_shifts_by_months = '.json_encode($shift_total_employee_hours_by_months).';
                    renderBarGraph(data_total_hours_shifts_by_months,"Celkový počet hodin směn dle měsíců", "Celkový počet hodin směn dle měsíců", barChartShiftsTotalHours, barChartShiftsTotalHoursCanvas, "#barChartShiftsTotalHours");
                    var data_total_worked_hours_by_months = '.json_encode($shift_employee_total_worked_hours_by_months).';
                    renderBarGraph(data_total_worked_hours_by_months,"Počet celkově odpracovaných hodin na směnách", "Počet celkově odpracovaných hodin na směnách", barChartShiftsTotalWorkedHours, barChartShiftsTotalWorkedHoursCanvas, "#barChartShiftsTotalWorkedHours");
                    var data_total_late_hours_by_months = '.json_encode($shift_total_employee_late_hours_by_months).';
                    renderBarGraph(data_total_late_hours_by_months,"Počet celkových hodin zpoždění", "Počet celkových hodin zpoždění", barChartShiftsTotalLateHours, barChartShiftsTotalLateHoursCanvas, "#barChartShiftsTotalLateHours");
                    var data_total_late_flags_count_by_months = '.json_encode($total_late_flags_count_employee_by_months).';
                    renderBarGraph(data_total_late_flags_count_by_months, "Počet zpoždění dle měsíců", "Počet zpoždění dle měsíců", barChartShiftsTotalLateFlagsCount, barChartShiftsTotalLateFlagsCountCanvas, "#barChartShiftsTotalLateFlagsCount");
                    var data_total_injury_flags_count_by_months = '.json_encode($employee_injuries_count_by_months).';
                    renderBarGraph(data_total_injury_flags_count_by_months,"Počet zranění na směnách dle měsíců", "Počet zranění na směnách dle měsíců", barChartShiftsTotalInjuriesFlagsCount, barChartShiftsTotalInjuriesFlagsCountCanvas, "#barChartShiftsTotalInjuriesFlagsCount");
                    var data_average_employee_score_by_months = '.json_encode($average_employee_score_by_months).';
                    renderBarGraph(data_average_employee_score_by_months,"Vývoj průměrného skóre zaměstnance v čase", "Vývoj průměrného skóre zaměstnance v čase", barChartAverageEmployeesScoreByTime, barChartAverageEmployeesScoreByTimeCanvas, "#barChartAverageEmployeesScoreByTime");
              */
                </script>
            </div>
        </div>
    </section>
@endsection
