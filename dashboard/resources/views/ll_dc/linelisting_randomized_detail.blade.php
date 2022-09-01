@extends('layouts.simple.master')
@section('title',  trans('lang.linelisting_main_heading')  )
@section('css')

@endsection
@section('style')
@endsection
@section('breadcrumb-title')
    <h3>Rapid Survey - Randomization</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item active">Rapid Survey - Randomization</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax data source array start-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header project-list">
                        <h5>UEN Rapid Survey - Randomization</h5>
                        <span><a class="btn btn-secondary" href="{{route('make_pdf').'/'.request()->id}}" id="add"> <i data-feather="plus-square"> </i>Export PDF</a></span>
                        <div class="text-2xl">Cluster No: <strong>{{request()->id}}</strong></div>
                        <div class="text-2xl">District: <strong>{{isset($data['get_randomized_table'][0]->district) &&$data['get_randomized_table'][0]->district!=''?ucfirst($data['get_randomized_table'][0]->district):'-'}}</strong></div>
                        <div class="text-2xl">Tehsil: <strong>{{isset($data['get_randomized_table'][0]->tehsil) &&$data['get_randomized_table'][0]->tehsil!=''?ucfirst($data['get_randomized_table'][0]->tehsil):'-'}}</strong></div>
                        <div class="text-2xl">UC: <strong>{{isset($data['get_randomized_table'][0]->uc) &&$data['get_randomized_table'][0]->uc!=''?ucfirst($data['get_randomized_table'][0]->uc):'-'}}</strong></div>
                        <div class="text-2xl">Village: <strong>{{isset($data['get_randomized_table'][0]->village) &&$data['get_randomized_table'][0]->village!=''?ucfirst($data['get_randomized_table'][0]->village):'-'}}</strong></div>
                        <div class="text-2xl">Randomization Date: <strong>{{isset($data['get_randomized_table'][0]->randDT) &&$data['get_randomized_table'][0]->randDT!=''?date('d-M-Y',strtotime($data['get_randomized_table'][0]->randDT)):'-'}}</strong></div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive2">
                            <table class="display datatables table-striped table-hover" id="datatable_custom">
                                <thead>
                                <tr>
                                    <th width="10%">SNo</th>
                                    <th width="10%">Household No</th>
                                    <th width="10">Head of Household</th>
                                    <th width="20%">Remarks</th>
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
                                            <td width="10%">{{$s}}</td>
                                            <td width="10%">{{isset($r->compid) && $r->compid!=''? $r->tabNo . '-' .substr($r->compid, 10, 8):'-'}}</td>
                                            <td width="10%">{{isset($r->hh08) &&$r->hh08!=''?ucfirst($r->hh08):'-'}}</td>
                                            <td width="20%"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th width="10%">SNo</th>
                                    <th width="10%">Household No</th>
                                    <th width="10">Head of Household</th>
                                    <th width="20%">Remarks</th>
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
                displayLength: 25,
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
