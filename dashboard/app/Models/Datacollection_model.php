<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class datacollection_model extends Model
{
    use HasFactory;

    protected $table = 'forms';

    public static function randomizedClusters_district($searchdata)
    {
        $sql = DB::table('clusters');
        $select = "dist_id,district,SUM ( CASE WHEN randomized = '1' THEN 1 ELSE 0 END ) AS randomized_c";
        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $select .= ',tehsil';
            $sql->groupBy('tehsil');
        }
        $sql->select(DB::raw($select));
        $sql->groupBy('dist_id', 'district');
        if (isset($searchdata[
            'district']) && $searchdata['district'] != '') {
            
                $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('dist_id', '=', trim($d));
                }
            });
        }
        $sql->where(function ($query) {
            $query->where('colflag')
                ->orWhere('colflag', '=', '0')
                ->orWhereNotIn('colflag', ['1','2', '3']);
        });
        $sql->where('cluster_no', 'NOT LIKE', '9999%');
        $sql->orderBy('dist_id', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function completed_rand_Clusters_district($searchdata)
    {

        $sql = DB::table('clusters as c');
        $select = "c.dist_id,c.district,c.cluster_no,
        (SELECT COUNT (*) FROM bl_randomised WHERE dist_id=c.dist_id AND hh01=c.cluster_no AND (bl_randomised.colflag IS NULL OR bl_randomised.colflag ='0')) AS hh_randomized,
        (SELECT COUNT (DISTINCT f.hhid) FROM  dbo.formhh AS f WHERE f.a105= c.dist_id AND f.a101 = c.cluster_no AND f.username NOT IN ('afg12345','user0001','user0113','user0123','user0211','user0234','user0252','user0414','user0432','user0434') AND c.cluster_no NOT LIKE '9999%' AND c.cluster_no !='null') AS hh_collected";

        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $select .= ',c.tehsil';
            $sql->groupBy('c.tehsil');
        }
        $sql->select(DB::raw($select));

        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('c.dist_id', '=', trim($d));
                }
            });
        }
        $sql->where(function ($query) {
            $query->where('c.colflag')
                ->orWhere('c.colflag', '=', '0')
                ->orWhereNotIn('c.colflag', ['1','2', '3']);
        });
        $sql->where('c.cluster_no', 'NOT LIKE', '9999%');
     
        $sql->where('c.cluster_no', '!=', 'null');
        $sql->where('c.randomized', '=', '1');
        $sql->groupBy('c.dist_id', 'c.district', 'c.cluster_no');
        $sql->orderBy('c.dist_id', 'ASC');
        $data = $sql->get();
        return $data;
    }

    /*============================ Data Collection Datatable  ============================*/
    public static function get_dc_table($searchdata)
    {
//        (SELECT COUNT (DISTINCT f.hhno) FROM  forms AS f WHERE dist_id= c.dist_id AND f.cluster_code = c.cluster_no AND f.username NOT IN ('afg12345','user0001','user0113','user0123','user0211','user0234','user0252','user0414','user0432','user0434') AND c.cluster_no NOT LIKE '%9501' AND c.cluster_no NOT LIKE '%9502' AND c.cluster_no !='null') AS hh_collected";
        $sql = DB::table('clusters as c');
        $select = "c.dist_id,c.district,c.tehsil,c.cluster_no,
        (SELECT COUNT (*) FROM bl_randomised WHERE dist_id=c.dist_id AND hh02=c.cluster_no AND (bl_randomised.colflag IS NULL OR bl_randomised.colflag ='0')) AS hh_randomized,
        (SELECT COUNT (DISTINCT f.hhid) FROM  dbo.formhh AS f WHERE f.a105= c.dist_id AND f.a101 = c.cluster_no AND f.username NOT IN ('afg12345','user0001','user0113','user0123','user0211','user0234','user0252','user0414','user0432','user0434') AND c.cluster_no NOT LIKE '9999%' AND c.cluster_no !='null') AS hh_collected";


        $sql->select(DB::raw($select));

        if (isset($searchdata['type']) && $searchdata['type'] == 'c') {
            /* $sql->whereRaw("( SELECT COUNT (distinct f.hhid) FROM formhh f
             LEFT JOIN bl_randomised bl ON f.a101 = bl.hh02 AND f.hhid = RIGHT (bl.compid, 10)
  WHERE bl.dist_id = c.dist_id AND f.a101 = c.cluster_no   ) >=
  (SELECT COUNT (*) FROM bl_randomised WHERE dist_id=c.dist_id AND hh02=c.cluster_no
  AND (bl_randomised.colflag IS NULL OR bl_randomised.colflag ='0')) ");*/
            $sql->whereRaw("(SELECT COUNT (DISTINCT f.hhid) FROM  dbo.formhh AS f WHERE f.a105= c.dist_id AND f.a101 = c.cluster_no AND
            f.username NOT IN ('afg12345','user0001','user0113','user0123','user0211','user0234','user0252','user0414','user0432','user0434')
            AND c.cluster_no NOT LIKE '9999%' AND c.cluster_no !='null') >= 20");
        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'i') {
            $sql->whereRaw("c.randomized = '1'");
            $sql->whereRaw("(SELECT COUNT (DISTINCT f.hhid) FROM  dbo.formhh AS f WHERE f.a105= c.dist_id AND f.a101 = c.cluster_no
             AND f.username NOT IN ('afg12345','user0001','user0113','user0123','user0211','user0234','user0252','user0414','user0432','user0434')
             AND c.cluster_no NOT LIKE '9999%' AND c.cluster_no !='null') < 20");
            /* $sql->whereRaw(" (SELECT COUNT (distinct f.hhno) FROM forms f LEFT JOIN bl_randomised bl ON f.cluster_code = bl.hh02 AND f.hhno = RIGHT (bl.compid, 10)
 WHERE bl.dist_id = c.dist_id AND ( f.colflag IS NULL OR f.colflag = '0' ) AND f.cluster_code = c.cluster_no  )<(SELECT COUNT (*) FROM bl_randomised WHERE dist_id=c.dist_id AND hh02=c.cluster_no AND (bl_randomised.colflag IS NULL OR bl_randomised.colflag ='0'))");*/
        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'r') {
            $sql->whereRaw("c.randomized = '1'");
        }


        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('c.dist_id', '=', trim($d));
                }
            });
        }
        $sql->where(function ($query) {
            $query->where('c.colflag')
                ->orWhere('c.colflag', '=', '0')
                ->orWhereNotIn('c.colflag', ['1','2', '3']);
        });
        $sql->where('c.cluster_no', 'NOT LIKE', '9999%');
        
        $sql->where('c.cluster_no', '!=', 'null');
        $sql->groupBy('c.dist_id', 'c.district', 'c.tehsil', 'c.cluster_no');
        $sql->orderBy('c.dist_id', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function get_dc_forms($searchdata)
    {
        $sql = DB::table('FormHH');
        $select = "DISTINCT hhid,a101 as cluster_code,MIN (istatus) AS istatus";

        $sql->select(DB::raw($select));


        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->whereRaw("a105 = '" . trim($d) . "'");
                }
            });
        }
        /*   $sql->where(function ($query) {
               $query->where('colflag')
                   ->orWhere('colflag', '=', '0') ;
           });*/
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('username');
        });
        $sql->whereRaw("istatus !='' ");
        $sql->groupBy('a101', 'hhid');
        $sql->orderBy('a101', 'ASC');
        $sql->orderBy('hhid', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function get_dc_forms_status($searchdata)
    {
        $sql = DB::table('FormHH as f');
        $select = "DISTINCT f.hhid, f.a101 as cluster_code,MIN (f.istatus) AS istatus,c.district,c.tehsil";

        $sql->select(DB::raw($select))->leftJoin('clusters as c', 'f.a101', '=', 'c.cluster_no');
        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->whereRaw("f.a105 = '" . trim($d) . "'");
                }
            });
        }
        if (isset($searchdata['cluster']) && $searchdata['cluster'] != '') {
            $cluster = trim($searchdata['cluster']);
            $sql->where('f.a101', '=', $cluster);
        }
        if (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] == 'c') {
            $sql->where('istatus', '=', 1);
        } elseif (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] == 'r') {
            $sql->where('istatus', '=', 3);
        } elseif (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] == 'n') {
            $sql->where('istatus', '=', 43);
        } elseif (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] == 'o') {
            $sql->whereIn('istatus', [2, 5, 6, 7, 8, 96, 99]);
        }
        /*   $sql->where(function ($query) {
               $query->where('colflag')
                   ->orWhere('colflag', '=', '0') ;
           });*/
        $sql->where(function ($query) {
            $query->whereNotIn('f.username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('f.username');
        });
        $sql->whereRaw("f.istatus !='' ");
        $sql->groupBy('f.a101', 'f.hhid', 'c.district', 'c.tehsil');
        $sql->orderBy('f.a101', 'ASC');
        $sql->orderBy('f.hhid', 'ASC');
        $data = $sql->get();
        return $data;
    }

}
