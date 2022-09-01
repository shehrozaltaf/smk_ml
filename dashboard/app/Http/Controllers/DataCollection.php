<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Custom_Model;
use App\Models\datacollection_model;
use App\Models\linelisting_model;
use App\Models\Settings_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataCollection extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'data_collection');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "DataCollection",
            "action" => "View DataCollection -> Function: DataCollection/index()",
            "PostData" => "",
            "affectedKey" => "",
            "idUser" =>Auth::user()->id,
            "username" => Auth::user()->username,
        );
        /*==========Log=============*/
        if ($data['permission'][0]->CanView == 1) {
            $trackarray["mainResult"] = "Success";
            $trackarray["result"] = "View Success";
            Custom_Model::trackLogs($trackarray, "all_logs");

            $searchdata = array();
            $searchdata['district'] = '';
            $searchdata['pageLevel'] = 1;
            if (isset($data['permission'][0]->CanViewAllDetail) && $data['permission'][0]->CanViewAllDetail != 1
                && isset(Auth::user()->district) && Auth::user()->district != 0) {
                $searchdata['district'] = Auth::user()->district;
            }
            $getClustersProvince = linelisting_model::getClustersProvince($searchdata);

            $overall_dist_array = array();
            $totalcluster = 0;
            foreach ($getClustersProvince as $k => $v) {
                $dist_id = $v->dist_id;
                $overall_dist_array[$dist_id]['dist_id'] = $v->dist_id;
                $overall_dist_array[$dist_id]['district'] = $v->district;
                $overall_dist_array[$dist_id]['count'] = $v->totalDistrict;
                $totalcluster += $overall_dist_array[$dist_id]['count'];
            }
            $data['total'] = $overall_dist_array;
            $data['totalcluster'] = $totalcluster;


            /*==============Completed Clusters List==============*/
            $randomization = datacollection_model::randomizedClusters_district($searchdata);
            $randomization_array = array();
            $data['randomization_total'] = 0;
            foreach ($randomization as $key => $val) {
                $dist_id = $val->dist_id;
                $randomization_array[$dist_id]['dist_id'] = $val->dist_id;
                $randomization_array[$dist_id]['district'] = $val->district;
                $randomization_array[$dist_id]['count'] = $val->randomized_c;

                $data['randomization_total'] += $val->randomized_c;
            }
            $data['randomization'] = $randomization_array;
            /*==============Completed & Pending Clusters List==============*/
            $completedClusters_district = datacollection_model::completed_rand_Clusters_district($searchdata);
            if (isset($district) && $district != '') {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;
                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;
                }
            } else {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;

                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;

                }
            }
            $combine_ip_comp = 0;
            $completed = 0;
            $ip = 0;
            $s = 1;
            foreach ($completedClusters_district as $row) {
                $ke = $row->dist_id;
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    if ($ke == $dist_id) {
                        $combine_ip_comp++;
                        $data['combine_ip_comp'][$dist_id]['count']++;
                        if (isset($row->hh_collected) && $row->hh_collected!=0 && $row->hh_collected >= $row->hh_randomized ) {
                            $data['completed'][$dist_id]['count']++;
                            $completed++;
                        } else {
                            $data['ip'][$dist_id]['count']++;
                            $ip++;
                        }
                    }
                }
            }
            $data['total_completed'] = $completed;
            $data['total_ip'] = $ip;
            return view('ll_dc.datacollection', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function dataCollection_detail(Request $request)
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'DataCollection');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "DataCollection",
            "action" => "View DataCollection Detail -> Function: DataCollection/dataCollection_detail()",
            "PostData" => "",
            "affectedKey" => "",
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        /*==========Log=============*/

        if ($data['permission'][0]->CanView == 1) {
            $trackarray["mainResult"] = "Success";
            $trackarray["result"] = "View Success";
            Custom_Model::trackLogs($trackarray, "all_logs");
            $searchdata = array();
            if (isset($data['permission'][0]->CanViewAllDetail) && $data['permission'][0]->CanViewAllDetail != 1
                && isset(Auth::user()->district) && Auth::user()->district != 0) {
                $searchdata['district'] = Auth::user()->district;
            }
            if (isset(request()->id) && request()->id != '' && !empty(request()->id)) {
                $searchdata['district'] = request()->id;
            }
            $searchdata['pageLevel'] = 2;

            $getClustersProvince = linelisting_model::getClustersProvince($searchdata);

            $overall_dist_array = array();
            $totalcluster = 0;
            foreach ($getClustersProvince as $k => $v) {
                $dist_id = $v->tehsil;
                $overall_dist_array[$dist_id]['dist_id'] = $v->dist_id;
                $overall_dist_array[$dist_id]['district'] = $v->tehsil;
                $overall_dist_array[$dist_id]['count'] = $v->totalDistrict;
                $totalcluster += $overall_dist_array[$dist_id]['count'];
            }
            $data['total'] = $overall_dist_array;
            $data['totalcluster'] = $totalcluster;

            /*==============Completed Clusters List==============*/
            $randomization = datacollection_model::randomizedClusters_district($searchdata);
            $randomization_array = array();
            $data['randomization_total'] = 0;
            foreach ($randomization as $key => $val) {
                $dist_id = $val->tehsil;
                $randomization_array[$dist_id]['dist_id'] = $val->dist_id;
                $randomization_array[$dist_id]['district'] = $val->tehsil;
                $randomization_array[$dist_id]['count'] = $val->randomized_c;

                $data['randomization_total'] += $val->randomized_c;
            }
            $data['randomization'] = $randomization_array;

            /*==============Completed & Pending Clusters List==============*/
            $completedClusters_district = datacollection_model::completed_rand_Clusters_district($searchdata);
            if (isset($district) && $district != '') {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;
                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;
                }
            }
            else {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;

                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;

                }
            }
            $combine_ip_comp = 0;
            $completed = 0;
            $ip = 0;
            foreach ($completedClusters_district as $row) {
                $ke = $row->tehsil;
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    if ($ke == $dist_id) {
                        $combine_ip_comp++;
                        $data['combine_ip_comp'][$dist_id]['count']++;
                        if (isset($row->hh_collected) && $row->hh_collected!=0 && $row->hh_collected >= $row->hh_randomized ) {
                            $data['completed'][$dist_id]['count']++;
                            $completed++;
                        } else {
                            $data['ip'][$dist_id]['count']++;
                            $ip++;
                        }
                    }
                }
            }
            $data['total_completed'] = $completed;
            $data['total_ip'] = $ip;
            /*==============Data Table==============*/
            $searchdata['type'] = request()->type;
            $get_dc_table = datacollection_model::get_dc_table($searchdata);
            $get_dc_forms = datacollection_model::get_dc_forms($searchdata);

            $get_dc = array();
            foreach ($get_dc_table as $row) {
                $ke = $row->cluster_no;
                $get_dc[$ke]['cluster_no'] = $row->cluster_no;
                $get_dc[$ke]['dist_id'] = $row->dist_id;
                $get_dc[$ke]['district'] = $row->district;
                $get_dc[$ke]['tehsil'] = $row->tehsil;
                $get_dc[$ke]['hh_randomized'] = $row->hh_randomized;
                $get_dc[$ke]['hh_collected'] = $row->hh_collected;
                $get_dc[$ke]['completed_forms'] = 0;
                $get_dc[$ke]['refused_forms'] = 0;
                $get_dc[$ke]['not_elig'] = 0;
                $get_dc[$ke]['remaining_forms'] = 0;
            }
            foreach ($get_dc_forms as $row2) {
                $key = $row2->cluster_code;
                if (isset($get_dc[$key]) && $get_dc[$key] != '') {
                    if ($row2->istatus == 1) {
                        $get_dc[$key]['completed_forms']++;
                    } elseif ($row2->istatus == 3) {
                        $get_dc[$key]['refused_forms']++;
                    } elseif ($row2->istatus == 4) {
                        $get_dc[$key]['not_elig']++;
                    } elseif ($row2->istatus == 2 || $row2->istatus == 5 || $row2->istatus == 6 || $row2->istatus == 7 || $row2->istatus == 8 || $row2->istatus == 96 || $row2->istatus == 99) {
                        $get_dc[$key]['remaining_forms']++;
                    }
                }

            }
            $data['get_dc_table'] = $get_dc;
            return view('ll_dc.datacollection_table', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function dataCollection_statusdetail(Request $request)
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'DataCollection');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "DataCollection",
            "action" => "View DataCollection Status Detail -> Function: DataCollection/dataCollection_statusdetail()",
            "PostData" => "",
            "affectedKey" => "",
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        /*==========Log=============*/

        if ($data['permission'][0]->CanView == 1) {
            $trackarray["mainResult"] = "Success";
            $trackarray["result"] = "View Success";
            Custom_Model::trackLogs($trackarray, "all_logs");
            $searchdata = array();
            if (isset($data['permission'][0]->CanViewAllDetail) && $data['permission'][0]->CanViewAllDetail != 1
                && isset(Auth::user()->district) && Auth::user()->district != 0) {
                $searchdata['district'] = Auth::user()->district;
            }
            if (isset(request()->id) && request()->id != '' && !empty(request()->id)) {
                $searchdata['cluster'] = request()->id;
            }
            /*==============Data Table==============*/
            $searchdata['type'] = request()->type;
            $get_dc_forms_status = datacollection_model::get_dc_forms_status($searchdata);

            $data['get_dc_table'] = $get_dc_forms_status;
            return view('ll_dc.datacollection_table_detail', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }
}
