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


        <div class="row">
            <!-- Ajax data source array start-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header project-list">
                        <h5>{{ trans('lang.data_collection_main_heading') }}</h5>
                        <span>{{ trans('lang.data_collection_main_heading') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive2">
                            <table class="display datatables table-striped table-hover" id="datatable_custom">

                                <thead>
                                <tr>
                                    <th width="9%">SNo</th>
                                    <th width="9%">District</th>
                                    <th width="9%">Tehsil</th>
                                    <th width="9">Cluster No</th>
                                    <th width="9">Household Id</th>
                                    <th width="9%">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $s=0;
                                @endphp
                                @if (isset($data['get_dc_table']) && $data['get_dc_table'] != '')
                                    @foreach ($data['get_dc_table'] as $k=>$r)
                                        @php
                                            $s++;
                                                if(isset($r->cluster_code) && $r->cluster_code!=''){
                                                    $cluster_no=$r->cluster_code;
                                                }else{
                                                    $cluster_no=0;
                                                }
                                        @endphp
                                        <tr>
                                            <td width="9%">{{$s}}</td>
                                            <td width="9%">{{isset($r->district) &&$r->district!=''?ucwords(strtolower($r->district)):'-'}}</td>
                                            <td width="9%">{{isset($r->tehsil) &&$r->tehsil!=''?ucwords(strtolower($r->tehsil)):'-'}}</td>
                                            <td width="9%">{{isset($cluster_no) && $cluster_no!=''?$cluster_no:'-'}}</td>
                                            <td width="9%">{{isset($r->hhid) &&$r->hhid!=''?ucwords(strtolower($r->hhid)):'-'}}</td>
                                            <td width="9%"> @php
                                                    $status = '';
                                                    if(isset($r->istatus) && $r->istatus=='1' ){
                                                        $status='<a href="javascript:void(0)" class="btn btn-xs btn-success text-center">Completed</a>';
                                                    }elseif(isset($r->istatus) && $r->istatus=='3' ){
                                                        $status='<a href="javascript:void(0)" class="btn btn-xs btn-danger text-center">Refused</a>';
                                                    }elseif(isset($r->istatus) && $r->istatus=='4' ){
                                                        $status='<a href="javascript:void(0)" class="btn btn-xs btn-secondary text-center">No Eligible Respondent</a>';
                                                    }else{
                                                       $status='<a href="javascript:void(0)" class="btn btn-xs btn-info text-center">Other</a>';
                                                    }
                                                    echo $status;
                                                @endphp</td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th width="9%">SNo</th>
                                    <th width="9%">District</th>
                                    <th width="9%">Tehsil</th>
                                    <th width="9">Cluster No</th>
                                    <th width="9">Household Id</th>
                                    <th width="9%">Status</th>
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
    <script>
        $(document).ready(function () {
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
    </script>
@endsection
