<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="Shahroz Khan - {{config('global.project_shortname')}}">
    <meta name="keywords"
          content="Shahroz Khan - {{config('global.project_shortname')}}">
    <meta name="author" content="Shahroz Khan">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset(config('global.asset_path').'/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset(config('global.asset_path').'/images/favicon.png')}}" type="image/x-icon">

    <title>{{config('global.project_shortname')}}</title>
    <style>
        .mytext2 strong {
            font-size: 15px;
            color: red;
        }
        .mytext {
            font-size: 11px;
        }
        .text-left{
            text-align: center;
        }
        table tr td{
            height: 27px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Ajax data source array start-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header project-list">

                    <h6>SMK_ML - Randomization</h6>
                    <div class="mytext2">Cluster No: <strong>{{request()->id}}</strong></div>
                    <div class="mytext">District:
                        <strong>{{isset($data['get_randomized_table'][0]->district) &&$data['get_randomized_table'][0]->district!=''?ucfirst($data['get_randomized_table'][0]->district):'-'}}</strong>
                    </div>
                    <div class="mytext">Tehsil:
                        <strong>{{isset($data['get_randomized_table'][0]->tehsil) &&$data['get_randomized_table'][0]->tehsil!=''?ucfirst($data['get_randomized_table'][0]->tehsil):'-'}}</strong>
                    </div>
                    <div class="mytext">UC:
                        <strong>{{isset($data['get_randomized_table'][0]->uc) &&$data['get_randomized_table'][0]->uc!=''?ucfirst($data['get_randomized_table'][0]->uc):'-'}}</strong>
                    </div>
                    <div class="mytext">Village:
                        <strong>{{isset($data['get_randomized_table'][0]->village) &&$data['get_randomized_table'][0]->village!=''?ucfirst($data['get_randomized_table'][0]->village):'-'}}</strong>
                    </div>
                    <div class="mytext">Randomization Date:
                        <strong>{{isset($data['get_randomized_table'][0]->randDT) &&$data['get_randomized_table'][0]->randDT!=''?date('d-M-Y',strtotime($data['get_randomized_table'][0]->randDT)):'-'}}</strong>
                    </div>
                </div>
                <div class="card-body">
                    <br>
                    <br>
                    <table border="1" cellpadding="0" cellspacing="0" style="width: 100%">
                        <thead>
                        <tr>
                            <th width="5%" class="text-left">SNo</th>
                            <th width="20%" class="text-left">Household No</th>
                            <th width="20%" class="text-left">Head of Household</th>
                            <th width="50%" class="text-left">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $s=0;
                        @endphp
                        @if (isset($data['get_randomized_table']) && $data['get_randomized_table'] != '')
                            @foreach ($data['get_randomized_table'] as $k=>$r)
                                @php
                                    $s++;
                                @endphp
                                <tr>
                                    <td class="text-left">{{$s}}</td>
                                    <td class="text-left">{{isset($r->compid) && $r->compid!=''? $r->tabNo . '-' .substr($r->compid, 10, 8):'-'}}</td>
                                    <td class="text-left">{{isset($r->hh08) &&$r->hh08!=''?ucfirst($r->hh08):'-'}}</td>
                                    <td class="text-left"></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-left">SNo</th>
                            <th class="text-left">Household No</th>
                            <th class="text-left">Head of Household</th>
                            <th class="text-left">Remarks</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
        <!-- Ajax data source array end-->
    </div>
</div>
</body>
</html>
