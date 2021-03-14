@extends('layouts.company_dashboard')
@section('title') - Statistiky @endsection
@section('content')
    <section class="page-section"  id="generator" style="padding-top:40px;padding-bottom: 60px;background-color: #F5F5F5" >
        <div class="fluid-container" style="margin-left: 20px;margin-right: 20px;padding-bottom: 1100px;">
            <div class="row">
                <br>
                <div class=" col-lg-1" style="font-size: 15px;">
                </div>
                <div class=" col-lg-10 alert alert-info alert-block text-center" style="font-size: 15px;">
                    <strong>Statistiky zatím nejsou hotové.</strong>
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
                <div class="col-lg-10">
                    <ul class="list-group">
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Zaměstnanců</strong></span> </li>
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Směn celkově</strong></span> {{$pocetSmen}}</li>
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Nadcházejících směn</strong></span> {{$pocetNadchazejicich}}</li>
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Proběhnutých směn</strong></span> {{$pocetHistorie}}</li>
                        <li class="list-group-item text-right"><span class="pull-left"><strong>Účet vytvořen</strong></span> {{$vytvorenUcet}}</li>
                    </ul>
                </div>
            </div>
            <center>
                <div class="col-lg-12" style="max-width: 800px;max-height: 500px;">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="col-lg-12" style="max-width: 800px;max-height: 500px;">
                    <canvas id="smeny"></canvas>
                </div>
            </center>
                <script>
                    $(function(){
                        var datas = <?php echo json_encode($datas); ?>;
                        var barCanvas = $("#barChart");
                        var barChart = new Chart(barCanvas,{
                            type:'bar',
                            data:{
                                labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                                datasets:[
                                    {
                                        label: 'Počet nových zaměstnanců dle měsíců',
                                        data: datas,
                                        backgroundColor: ['red', 'orange', 'green', 'blue', 'indigo', 'violet', 'purple', 'pink', 'silver', 'gold', 'brown']
                                    }
                                ]
                            },
                            options:{
                                scales:{
                                    yAxes:[{
                                        ticks:{
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        })
                    })
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $(function(){
                        var data_smeny = <?php echo json_encode($data_shifts); ?>;
                        var barCanvas = $("#smeny");
                        barCanvas.width  = 100;
                        barCanvas.height = 100;
                        var barChart = new Chart(barCanvas,{
                            type:'bar',
                            data:{
                                labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                                datasets:[
                                    {
                                        label: 'Počet nových směn dle měsíců',
                                        data: data_smeny,
                                        backgroundColor: ['red', 'orange', 'green', 'blue', 'indigo', 'violet', 'purple', 'pink', 'silver', 'gold', 'brown']
                                    }
                                ]
                            },
                            options:{
                                scales:{
                                    yAxes:[{
                                        ticks:{
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        })
                    })

                </script>

        </div>
    </section>
@endsection
