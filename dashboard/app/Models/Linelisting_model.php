<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class linelisting_model extends Model
{
    use HasFactory;

    protected $table = 'listings';


    public static function getClustersProvince($searchdata)
    {
        $sql = DB::table('clusters');
        $sql->select(DB::raw('dist_id,district,COUNT (dist_id) AS totalDistrict'));
        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $sql->select(DB::raw('dist_id,district,tehsil,COUNT (dist_id) AS totalDistrict'));
            $sql->groupBy('tehsil');
        }

        $sql->groupBy('dist_id', 'district');
        if (isset($searchdata['district']) && $searchdata['district'] != '') {
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
                ->orWhere('colflag', '=', '0');
        });
        $sql->where('cluster_no', 'NOT LIKE', '999%');
        $sql->orderBy('dist_id', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function completedClusters_district($searchdata)
    {
        $sql = DB::table('clusters as c');
        $select = "l.enumcode,c.district, l.hh01,  c.dist_id,
			(select count(distinct deviceid) from listings where hh01 = l.hh01 and enumcode = l.enumcode  and (hh11 not like 'Deleted') AND (colflag is null or colflag=0 )) as collecting_tabs,
			(select count(*) completed_tabs from(select deviceid, max(cast(hh04 as int)) ms from listings where enumcode = l.enumcode and hh01 = l.hh01  and (hh11 not like 'Deleted') AND (colflag is null OR colflag = 0  )  and hh07 = 9
            group by deviceid) AS completed_tabs) completed_tabs";

        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $select .= ',c.tehsil';
            $sql->groupBy('c.tehsil');
        }
        $sql->select(DB::raw($select))->leftJoin('listings as l', 'c.cluster_no', '=', 'l.hh01');

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
            $query->whereNotIn('l.username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('l.username');
        });
        $sql->where(function ($query) {
            $query->where('l.colflag')
                ->orWhere('l.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('c.colflag')
                ->orWhere('c.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('hh11', 'NOT LIKE', 'Deleted');

        });

        $sql->groupBy('c.district', 'c.dist_id', 'l.colflag', 'l.enumcode', 'l.hh01');
        $sql->orderBy('l.enumcode', 'ASC');
        $sql->orderBy('l.hh01', 'ASC');
        $data = $sql->get();
        return $data;
    }

    /*============================ LineListing Datatable  ============================*/
    public static function get_linelisting_table($searchdata)
    {
        $sql = DB::table('clusters as c');

        $select = "c.cluster_no,l.enumcode,l.hh01,c.randomized,c.tehsil, (
SELECT COUNT (*) FROM (SELECT DISTINCT hh04,tabNo FROM listings WHERE hh07 not in ('8','9') AND (colflag is null or colflag=0) and (hh11 not like 'Deleted') AND hh01=l.hh01) AS structures) AS structures,
(select DISTINCT COUNT (hh04) FROM listings where hh08 = '1' and (hh11 not like 'Deleted') and hh01 = l.hh01 AND (colflag is null or colflag=0)) as residential_Household,
(select DISTINCT COUNT (hh04) FROM listings where hh08 = '1' and (hh11 not like 'Deleted') and hh13='1' and hh01 = l.hh01 AND (colflag is null or colflag=0)) as eligible_households,
(select sum(cast(hh13a as int)) from listings where hh08 = '1' and (hh11 not like 'Deleted') and hh13 = '1' and hh01 = l.hh01 AND (colflag is null or colflag=0)) as no_of_eligible_MWRA,
(select sum(cast(hh15 as int)) from listings where hh08 = '1' and (hh11 not like 'Deleted') and hh13 = '1' and hh01 = l.hh01 AND (colflag is null or colflag=0)) as no_of_Adolescent,
(select count(distinct deviceid) from listings where hh01 = l.hh01 and (hh11 not like 'Deleted') AND (colflag is null or colflag=0)) as collecting_tabs, (select count(*) completed_tabs from
(select deviceid, max(cast(hh04 as int)) ms from listings where enumcode = l.enumcode and (hh11 not like 'Deleted') and hh01 = l.hh01 AND (colflag is null OR colflag = '0') and hh07 = '9' group by deviceid) AS completed_tabs) completed_tabs";
        $sql->select(DB::raw($select))
            ->leftJoin('listings as l', function ($join) {
                $join->on('c.cluster_no', '=', 'l.hh01');
                if (!isset($searchdata['type']) || $searchdata['type'] == ''|| $searchdata['type'] == 'r'|| $searchdata['type'] == 't') {
                    $join->where('l.hh11', 'NOT LIKE', 'Deleted');
                }
            }) ;



        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('c.dist_id', '=', trim($d));
                }
            });
        }
        if (isset($searchdata['type']) && $searchdata['type'] == 'c') {
            $sql->whereRaw("(select count(distinct deviceid) from listings where hh01 = l.hh01 and (hh11 not like 'Deleted') AND (colflag is null or colflag=0))
             = (select count(*) completed_tabs from (select deviceid, max(cast(hh04 as int)) ms from listings where enumcode = l.enumcode and (hh11 not like 'Deleted') and hh01 = l.hh01 AND (colflag is null OR colflag = '0') and hh07 = '9' group by deviceid) AS completed_tabs)");
            $sql->where('l.hh11', 'NOT LIKE', 'Deleted');
        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'i') {
            $sql->whereRaw(" (select count(distinct deviceid) from listings where hh01 = l.hh01 and (hh11 not like 'Deleted') AND (colflag is null or colflag=0))
             != (select count(*) completed_tabs from (select deviceid, max(cast(hh04 as int)) ms from listings where enumcode = l.enumcode and (hh11 not like 'Deleted') and hh01 = l.hh01 AND (colflag is null OR colflag = '0') and hh07 = '9' group by deviceid) AS completed_tabs)");
            $sql->where('l.hh11', 'NOT LIKE', 'Deleted');
        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'r') {
            $sql->whereRaw("(select count(distinct deviceid) from listings where hh01 = l.hh01 and (hh11 not like 'Deleted') AND (colflag is null or colflag=0))=0");
        } else {
            $cluster_type_where = '';
        }
        $sql->where(function ($query) {
            $query->whereNotIn('l.username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('l.username');
        });
        $sql->where(function ($query) {
            $query->where('l.colflag')
                ->orWhere('l.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('c.colflag')
                ->orWhere('c.colflag', '=', '0');
        });
        $sql->where('c.dist_id', 'NOT LIKE', '999');
        $sql->groupBy('c.cluster_no', 'l.enumcode', 'l.hh01', 'c.randomized', 'c.tehsil');
        $sql->orderBy('c.cluster_no', 'ASC');
        $sql->orderBy('l.enumcode', 'ASC');

        $data = $sql->get();
        return $data;
    }

    /*============================ Systematic Randomization ============================*/
    public static function get_rand_cluster($cluster)
    {
        $sql = DB::table('clusters as c')->select('c.randomized');
        $sql->where('cluster_no', '=', $cluster);
        $sql->where(function ($query) {
            $query->where('c.colflag')
                ->orWhere('c.colflag', '=', '0');
        });

        $data = $sql->get();
        return $data;
    }

    public static function chkDuplicateTabs($cluster)
    {
        $sql = DB::table('listings');
        $select = "COUNT ((tabNo + '-' + hh04 + '-' + hh05)) AS duplicates,(tabNo + '-' + hh04 + '-' + hh05) AS hh";
        $sql->select(DB::raw($select));
        $sql->where('hh01', '=', $cluster);
        $sql->whereNotIn('hh07', ['8','9']);
        $sql->where('hh11 ', 'not like', 'Deleted');
        $sql->where(function ($query) {
            $query->where('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('username');
        });
        $sql->groupByRaw("(tabNo + '-' + hh04 + '-' + hh05) ");
        $sql->havingRaw("(COUNT (tabNo + '-' + hh04 + '-' + hh05))  > 1");
        $data = $sql->get();
        return $data;


        /*$sql = DB::table('listings');
        $select = "COUNT ((tabNo + '-' + hh03 + '-' + hh07)) AS duplicates,(tabNo + '-' + hh03 + '-' + hh07) AS hh";
        $sql->select(DB::raw($select));
        $sql->where('hh02', '=', $cluster);
        $sql->where(function ($query) {
            $query->where('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('username');
        });
        $sql->where(function ($query) {
            $query->where('hh15')
                ->orWhere('hh15', '!=', '1');
        });
        $sql->groupByRaw("(tabNo + '-' + hh03 + '-' + hh07)");
        $sql->havingRaw("(COUNT (tabNo + '-' + hh03 + '-' + hh07)) > 1");
        $data = $sql->get();
        return $data;*/
    }

    public static function get_systematic_rand($cluster)
    {
        $sql = DB::table('listings');
        $select = "*";
        $sql->select(DB::raw($select));
        $sql->where('hh01', '=', $cluster);
        $sql->where('hh08', '=', '1');
        $sql->where('hh13', '=', '1');
        $sql->whereNotIn('hh07', ['8','9']);
        $sql->where('hh11', 'not like', 'Deleted');
        $sql->where(function ($query) {
            $query->where('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('username');
        });
        $sql->orderByRaw("tabNo, deviceid, cast(hh04 as int), cast(hh05 as int)");
        $data = $sql->get();
        return $data;

       /* $sql = DB::table('listings');
        $select = "col_id,tabNo, hh01,hh02,  hh03, hh04, hh05, hh06,  hh07, hh08, hh09, hh10,hh11,hhdt, enumcode, _uid";
        $sql->select(DB::raw($select));
        $sql->where('hh01', '=', $cluster);
        $sql->where('hh04', '=', '1');
        $sql->where('hh10', '=', '1');
        $sql->where(function ($query) {
            $query->where('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('username');
        });
        $sql->where(function ($query) {
            $query->where('hh15')
                ->orWhere('hh15', '!=', '1');
        });
        $sql->orderByRaw("tabNo, deviceid, cast(hh03 as int), cast(hh07 as int)");
        $data = $sql->get();
        return $data;*/

    }

    public static function get_randomized_table($cluster)
    {
        $sql = DB::table('bl_randomised');
        $select = "bl_randomised.randDT,bl_randomised.hh01,bl_randomised.hh11 as hh08,bl_randomised.compid,bl_randomised.tabNo,
        clusters.geoarea,clusters.district,clusters.tehsil,clusters.uc,clusters.village";
        $sql->select(DB::raw($select))->leftJoin('clusters', 'bl_randomised.hh01', '=', 'clusters.cluster_no');
        $sql->where('bl_randomised.hh01', '=', $cluster);
        $sql->where(function ($query) {
            $query->where('bl_randomised.colflag')
                ->orWhere('bl_randomised.colflag', '=', '0');
        });
        $sql->orderByRaw("bl_randomised.sno,bl_randomised.UID");
        $data = $sql->get();
        return $data;

        /*$sql = DB::table('bl_randomised');
        $select = "bl_randomised.randDT,bl_randomised.hh02,bl_randomised.hh08,bl_randomised.compid,bl_randomised.tabNo,
        clusters.geoarea,clusters.district,clusters.tehsil,clusters.uc,clusters.village";
        $sql->select(DB::raw($select))->leftJoin('clusters', 'bl_randomised.hh02', '=', 'clusters.cluster_no');
        $sql->where('bl_randomised.hh02', '=', $cluster);
        $sql->where(function ($query) {
            $query->where('bl_randomised.colflag')
                ->orWhere('bl_randomised.colflag', '=', '0');
        });
        $sql->orderByRaw("bl_randomised.sno,bl_randomised._id");
        $data = $sql->get();
        return $data;*/

    }
}
