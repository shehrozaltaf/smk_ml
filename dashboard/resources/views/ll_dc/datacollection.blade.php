@extends('layouts.simple.master')
@section('title',  trans('lang.data_collection_main_heading')  )
@section('css')

@endsection
@section('style')
@endsection
@section('breadcrumb-title')
    <h3>{{ trans('lang.data_collection_main_heading') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.data_collection_main_heading') }}</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row size-column">
            @php
                $colors = array('primary', 'warning', 'danger', 'success', 'info', 'mycolor1', 'mycolor2', 'mycolor3',
                            'danger', 'success', 'mycolor3', 'mycolor1', 'info', 'mycolor2', 'primary', 'warning',
                            'info', 'danger', 'mycolor1', 'success', 'primary', 'warning', 'mycolor2', 'mycolor3',
                            'primary', 'warning', 'danger', 'success', 'info', 'mycolor1', 'mycolor2', 'mycolor3',
                            'danger', 'success', 'mycolor3', 'mycolor1', 'info', 'mycolor2', 'primary', 'warning',
                            'info', 'danger', 'mycolor1', 'success', 'primary', 'warning', 'mycolor2', 'mycolor3');
            @endphp
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Total</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="total_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="total_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Total</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['totalcluster']) && $data['totalcluster'] != ''&& $data['totalcluster'] != '0')
                                                    {{$data['totalcluster']}}
                                                @else
                                                    @php
                                                        $data['totalcluster']=100;
                                                    @endphp
                                                    {{$data['totalcluster']}}
                                                @endif
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['total']) && $data['total'] != '')
                                @foreach ($data['total'] as $keys=>$total)
                                    @php
                                        $s++;
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="{{route('data_collection_detail')}}/t/{{$keys}}" target="_blank">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class="text-start"><span
                                                            class="font-primary">{{$total['district']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$total['count']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: 100%"
                                                     aria-valuenow="{{$total['count']}}" aria-valuemin="0"
                                                     aria-valuemax="100">100%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Randomized</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="randomized_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="randomized_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Randomized</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['randomization_total']) && $data['randomization_total'] != '')
                                                    @php
                                                        $perc_cal = ($data['randomization_total'] / $data['totalcluster']) * 100;
                                                        $perc=(isset($perc_cal) && $perc_cal!=''?$perc_cal:'0');
                                                    @endphp
                                                    {{$data['randomization_total']}}
                                                @endif
                                                <input type="hidden" id="randomized_hidden"
                                                       value="{{number_format($perc,1)}}">
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['randomization']) && $data['randomization'] != '')
                                @foreach ($data['randomization'] as $keys=>$randomization)
                                    @php
                                        $s++;
                                        $t=(isset($data['total'][$keys]['count']) && $data['total'][$keys]['count']!=''?$data['total'][$keys]['count']:'0');
                                        $d=(isset($randomization['count']) && $randomization['count']!=''?$randomization['count']:'0');
                                        $perc_cal = ($d / $t) * 100;
                                        $perc=(isset($perc_cal) && $perc_cal!='' && $perc_cal!='0'?number_format($perc_cal,1):'0');
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="{{route('data_collection_detail')}}/r/{{$keys}}" target="_blank">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class=" text-start"><span
                                                            class="font-primary">{{$randomization['district']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$randomization['count']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: {{number_format($perc,0)}}%"
                                                     aria-valuenow="{{$randomization['count']}}" aria-valuemin="0"
                                                     aria-valuemax="100">{{$perc}}%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Completed</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="completed_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="completed_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Completed</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['total_completed']) && $data['total_completed'] != '')
                                                    @php
                                                        $perc_cal = ($data['total_completed'] / $data['totalcluster']) * 100;
                                                        $perc=(isset($perc_cal) && $perc_cal!=''?$perc_cal:'0');
                                                    @endphp
                                                    {{$data['total_completed']}}
                                                @endif
                                                <input type="hidden" id="completed_hidden"
                                                       value="{{number_format($perc,1)}}">
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['completed']) && $data['completed'] != '')
                                @foreach ($data['completed'] as $keys=>$completed)
                                    @php
                                        $s++;
                                        $t=(isset($data['total'][$keys]['count']) && $data['total'][$keys]['count']!=''?$data['total'][$keys]['count']:'0');
                                        $d=(isset($completed['count']) && $completed['count']!=''?$completed['count']:'0');
                                        $perc_cal = ($d / $t) * 100;
                                        $perc=(isset($perc_cal) && $perc_cal!='' && $perc_cal!='0'?number_format($perc_cal,1):'0');
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="{{route('data_collection_detail')}}/c/{{$keys}}" target="_blank">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class=" text-start"><span
                                                            class="font-primary">{{$completed['district']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$completed['count']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: {{number_format($perc,0)}}%"
                                                     aria-valuenow="{{$completed['count']}}" aria-valuemin="0"
                                                     aria-valuemax="100">{{$perc}}%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Remaining</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="ip_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="ip_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">InProgress</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['total_ip']) && $data['total_ip'] != '')
                                                    @php
                                                        $perc_cal = ($data['total_ip'] / $data['totalcluster']) * 100;
                                                        $perc=(isset($perc_cal) && $perc_cal!=''?$perc_cal:'0');
                                                    @endphp
                                                    {{$data['total_ip']}}
                                                @endif
                                                <input type="hidden" id="ip_hidden"
                                                       value="{{number_format($perc,1)}}">
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['ip']) && $data['ip'] != '')
                                @foreach ($data['ip'] as $keys=>$ip)
                                    @php
                                        $s++;
                                        $t=(isset($data['total'][$keys]['count']) && $data['total'][$keys]['count']!=''?$data['total'][$keys]['count']:'0');
                                        $d=(isset($ip['count']) && $ip['count']!=''?$ip['count']:'0');
                                        $perc_cal = ($d / $t) * 100;
                                        $perc=(isset($perc_cal) && $perc_cal!='' && $perc_cal!='0'?number_format($perc_cal,1):'0');
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="{{route('data_collection_detail')}}/i/{{$keys}}" target="_blank">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class=" text-start"><span
                                                            class="font-primary">{{$ip['district']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$ip['count']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: {{number_format($perc,0)}}%"
                                                     aria-valuenow="{{$ip['count']}}" aria-valuemin="0"
                                                     aria-valuemax="100">{{$perc}}%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset(config('global.asset_path').'/js/chart/apex-chart/apex-chart.js')}}"></script>
    <script src="{{asset(config('global.asset_path').'/js/custom-card/custom-card.js')}}"></script>
    <script src="{{asset(config('global.asset_path').'/js/clipboard/clipboard.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            total_chart();
            randomized_chart();
            completed_chart();
            ip_chart();
        });

        function total_chart() {
            var options4 = {
                series: [100],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.primary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#2b2b2b'],

                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#a927f9'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['Total'],
                colors: [CubaAdminConfig.primary],
            };
            var chart4 = new ApexCharts(document.querySelector("#total_cluster"),
                options4
            );
            chart4.render();
        }

        function randomized_chart() {
            var randomized_hidden = $('#randomized_hidden').val();
            if (randomized_hidden == '' || randomized_hidden == undefined) {
                randomized_hidden = 0;
            }
            var options = {
                series: [randomized_hidden],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.secondary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#7dafb7'],

                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#7dafb7'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['Randomized'],
                colors: [CubaAdminConfig.mycolor2],
            };
            var chart = new ApexCharts(document.querySelector("#randomized_cluster"),
                options
            );
            chart.render();
        }

        function completed_chart() {
            var completed_hidden = $('#completed_hidden').val();
            if (completed_hidden == '' || completed_hidden == undefined) {
                completed_hidden = 0;
            }
            var options = {
                series: [completed_hidden],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.secondary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#efe3a5'],
                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#efe3a5'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['Completed'],
                colors: [CubaAdminConfig.warning],
            };
            var chart = new ApexCharts(document.querySelector("#completed_cluster"),
                options
            );
            chart.render();
        }

        function ip_chart() {
            var ip_hidden = $('#ip_hidden').val();
            if (ip_hidden == '' || ip_hidden == undefined) {
                ip_hidden = 0;
            }
            var options = {
                series: [ip_hidden],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.secondary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#7367F0'],

                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#f3959e'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['In Progress'],
                colors: [CubaAdminConfig.danger],
            };
            var chart = new ApexCharts(document.querySelector("#ip_cluster"),
                options
            );
            chart.render();
        }
    </script>
@endsection
