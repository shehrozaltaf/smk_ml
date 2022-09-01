<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class App_Users_Model extends Model
{
    use HasFactory;


    protected $table = 'AppUser';


    public static function getAllData()
    {
        $sql =DB::table('AppUser')->select('*');
        $sql->where('enabled', '=', '1')->orderByDesc('id');
        $data = $sql->get();
        return $data;
    }

    public static function checkName($userName)
    {
        $data =DB::table('AppUser')
            ->where('enabled', '=', '1')
            ->where('username', $userName)
            ->get();
        return $data;
    }

    public static function getUserDetails($id)
    {
        $data = DB::table('AppUser')
            ->where('id', $id)
            ->get();
        return $data;
    }

    public static function getDistricts()
    {
        $sql =DB::table('clusters')->select('dist_id','district');
        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('dist_id', '=', trim($d));
                }
            });
        }
        $sql->where(function ($query) {
            $query->whereNull('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $sql->groupBy('dist_id','district');
        $data = $sql->get();
        return $data;
    }

}
