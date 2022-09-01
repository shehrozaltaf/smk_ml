@extends('layouts.simple.master')
@section('title',  trans('lang.linelisting_main_heading')  )
@section('css')

@endsection
@section('style')
@endsection
@section('breadcrumb-title')
    <h3>{{ trans('lang.linelisting_main_heading') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.linelisting_main_heading') }}</li>
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
                                                @if (isset($data['totalcluster']) && $data['totalcluster'] != '')
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
                        <h5>In Progress</h5>
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
                                            <h6 class="font-primary">In Progress</h6>
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
                                <li onclick="r_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="r_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Remaining</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['total_r']) && $data['total_r'] != '')
                                                    @php
                                                        $perc_cal = ($data['total_r'] / $data['totalcluster']) * 100;
                                                        $perc=(isset($perc_cal) && $perc_cal!=''?$perc_cal:'0');
                                                    @endphp
                                                    {{$data['total_r']}}
                                                @endif
                                                <input type="hidden" id="r_hidden"
                                                       value="{{number_format($perc,1)}}">
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['r']) && $data['r'] != '')
                                @foreach ($data['r'] as $keys=>$r)
                                    @php
                                        $s++;
                                        $t=(isset($data['total'][$keys]['count']) && $data['total'][$keys]['count']!=''?$data['total'][$keys]['count']:'0');
                                        $d=(isset($r['count']) && $r['count']!=''?$r['count']:'0');
                                        $perc_cal = ($d / $t) * 100;
                                        $perc=(isset($perc_cal) && $perc_cal!='' && $perc_cal!='0'?number_format($perc_cal,1):'0');
                                    @endphp
                                    <div class="project-status mt-4">

                                        <div class="media mb-0">
                                            <div class="media-body"><h6 class=" text-start"><span
                                                        class="font-primary">{{$r['district']}}</span></h6>
                                            </div>
                                            <p class="text-end">{{$r['count']}}</p>
                                        </div>
                                        <div class="progress" style="height: 10px">
                                            <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                 role="progressbar"
                                                 style="width: {{number_format($perc,0)}}%"
                                                 aria-valuenow="{{$r['count']}}" aria-valuemin="0"
                                                 aria-valuemax="100">{{$perc}}%
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Ajax data source array start-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header project-list">
                        <h5>{{ trans('lang.linelisting_main_heading') }}</h5>
                        <span>{{ trans('lang.linelisting_main_heading') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive2">
                            <table class="display datatables table-striped table-hover" id="datatable_custom">

                                <thead>
                                <tr>
                                    <th width="9%">SNo</th>
                                    <th width="9%">Tehsil</th>
                                    <th width="9">Cluster No</th>
                                    <th width="9%">Total Structures</th>
                                    <th width="9%">Residential Structures</th>
                                    <th width="9%">Eligible HHs</th>
                                    <th width="9%">WRAs</th>
                                    <th width="9%">Collecting Tabs</th>
                                    <th width="9%">Completed Tabs</th>
                                    <th width="9%">Status</th>
                                    <th width="9%">Randomized</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $s=0;
                                @endphp
                                @if (isset($data['get_linelisting_table']) && $data['get_linelisting_table'] != '')
                                    @foreach ($data['get_linelisting_table'] as $k=>$r)
                                        @php
                                            $s++;
                                                if(isset($r->cluster_no) && $r->cluster_no!=''){
                                                    $cluster_no=$r->cluster_no;
                                                }else{
                                                    $cluster_no=0;
                                                }
                                        @endphp
                                        <tr>
                                            <td width="9%">{{$s}}</td>
                                            <td width="9%">{{isset($r->tehsil) && $r->tehsil!=''?$r->tehsil:'-'}}</td>
                                            <td width="9%">{{isset($cluster_no) && $cluster_no!=''?$cluster_no:'-'}}</td>
                                            <td width="9%">{{isset($r->structures) && $r->structures!=''?$r->structures:'0'}}</td>
                                            <td width="9%">{{isset($r->residential_structures) && $r->residential_structures!=''?$r->residential_structures:'0'}}</td>
                                            <td width="9%">{{isset($r->eligible_households) && $r->eligible_households!=''?$r->eligible_households:'0'}}</td>
                                            <td width="9%">{{isset($r->no_of_eligible_wras) && $r->no_of_eligible_wras!=''?$r->no_of_eligible_wras:'0'}}</td>
                                            <td width="9%">{{isset($r->collecting_tabs) && $r->collecting_tabs!=''?$r->collecting_tabs:'0'}}</td>
                                            <td width="9%">{{isset($r->completed_tabs) && $r->completed_tabs!=''?$r->completed_tabs:'0'}}</td>
                                            <td width="9%">
                                                @php
                                                    $rand_show = '';
                                                                    if ($r->structures == 0 || $r->structures == '') {
                                                                        $rand_show = '2';
                                                                        $stat = 'Remaining';
                                                                    } else if ($r->collecting_tabs !=$r->completed_tabs) {
                                                                        $rand_show = '2';
                                                                        $stat = 'In Progress';
                                                                    }else if ($r->collecting_tabs>2) {
                                                                        $rand_show = '4';
                                                                        $stat = 'The devices should not be greater than 2';
                                                                    } else if (isset($r->eligible_households) && $r->eligible_households<=19 && $r->collecting_tabs ==$r->completed_tabs) {
                                                                        $rand_show = '2';
                                                                        $stat = 'Completed but not enough eligible HHs';
                                                                    } else if ($r->randomized != '1' && isset($r->eligible_households) && $r->eligible_households>=20 && $r->collecting_tabs ==$r->completed_tabs) {
                                                                        $rand_show = '1';
                                                                        $stat = 'Ready to Randomize';
                                                                    }else if ($r->randomized == '1') {
                                                                        $rand_show = '3';
                                                                        $stat = 'Randomized';
                                                                    } else {
                                                                        $rand_show = '2';
                                                                        $stat = '-';
                                                                    }
                                                @endphp
                                                {{$stat}}
                                            </td>
                                            <td width="9%">
                                                @php
                                                    if (isset($data['permission'][0]->CanAdd) && $data['permission'][0]->CanAdd == 1 && $rand_show == '1') {
                                                               echo '<a href="javascript:void(0)" onclick="randomizeBtn(this)" data-cluster="' . $cluster_no . '"
                                                               class="btn btn-xs btn-primary text-center rand_btn">Randomize</a>';
                                                           } elseif ($rand_show == '3' ) {
                                                               echo '<a href="'.route('randomized_detail').'/'.$cluster_no.'" target="_blank" class="btn btn-xs btn-success text-center">View</a> ';
                                                               echo '| <a href="'.route('make_pdf').'/'.$cluster_no.'" target="_blank" class="btn btn-xs btn-danger text-center">Download Pdf</a> ';
                                                           } elseif ($rand_show == '4' ) {
                                                               echo '<a href="javascript:void(0)"  class="btn btn-xs btn-danger text-center">Error</a> ';
                                                                        if(Auth::user()->idGroup==1){
                                                                            echo '<a href="javascript:void(0)" onclick="randomizeBtn(this)" data-cluster="' . $cluster_no . '"
                                                               class="btn btn-xs btn-primary text-center rand_btn">Randomize</a>';
                                                                        }
                                                           } else {
                                                                echo '-';
                                                           }

                                                @endphp
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th width="9%">SNo</th>
                                    <th width="9%">Tehsil</th>
                                    <th width="9">Cluster No</th>
                                    <th width="9%">Total Structures</th>
                                    <th width="9%">Residential Structures</th>
                                    <th width="9%">Eligible HHs</th>
                                    <th width="9%">WRAs</th>
                                    <th width="9%">Collecting Tabs</th>
                                    <th width="9%">Completed Tabs</th>
                                    <th width="9%">Status</th>
                                    <th width="9%">Randomized</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
            <!-- Ajax data source array end-->
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
            completed_chart();
            ip_chart();
            r_chart();
            $('#datatable_custom').DataTable({
                "oSearch": {"sSearch": " "},
                autoFill: false,
                attr: {
                    autocomplete: 'off'
                },
                initComplete: function () {
                    $(this.api().table().container()).find('input[type="search"]').parent().wrap('<form>').parent().attr('autocomplete', 'off').css('overflow', 'hidden').css('margin', 'auto');
                },
                displayLength: 50,
                lengthMenu: [25, 50, 75, 100],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5', text: 'Copy', className: 'btn btn-sm btn-primary'

                    }, {
                        extend: 'csvHtml5', text: 'CSV', className: 'btn btn-sm btn-primary'
                    }
                ]
            });
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

                labels: ['Completed'],
                colors: [CubaAdminConfig.mycolor2],
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

                labels: ['InProgress'],
                colors: [CubaAdminConfig.warning],
            };
            var chart = new ApexCharts(document.querySelector("#ip_cluster"),
                options
            );
            chart.render();
        }

        function r_chart() {
            var r_hidden = $('#r_hidden').val();
            if (r_hidden == '' || r_hidden == undefined) {
                r_hidden = 0;
            }
            var options = {
                series: [r_hidden],
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

                labels: ['Remaining'],
                colors: [CubaAdminConfig.danger],
            };
            var chart = new ApexCharts(document.querySelector("#r_cluster"),
                options
            );
            chart.render();
        }

        function randomizeBtn(obj) {
            $('.rand_btn').css('display', 'none').attr('disabled', 'disabled');
            var data = {};
            data['cluster_no'] = $(obj).attr('data-cluster');
            if (data['cluster_no'] == '' || data['cluster_no'] == undefined || data['cluster_no'] == '0') {
                toastMsg('Cluster', 'Invalid Cluster No', 'error');
                $('.rand_btn').css('display', 'block').removeAttr('disabled', 'disabled');
                return false;
            } else {
                showloader();
                CallAjax('{{ route('systematic_randomizer') }}', data, 'POST', function (result) {
                    $('.rand_btn').css('display', 'block').removeAttr('disabled', 'disabled');
                    hideloader();
                    if (result !== '' && JSON.parse(result).length > 0) {
                        var response = JSON.parse(result);
                        try {
                            toastMsg(response[0], response[1], response[2]);
                            if (response[0] === 'Success') {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 700);
                            }
                        } catch (e) {
                        }
                    } else {

                        toastMsg('Error', 'Something went wrong', 'danger');
                    }
                });

            }
        }
    </script>
@endsection
