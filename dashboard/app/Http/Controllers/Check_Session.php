<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class Check_Session extends Controller
{

    public function checkSession()
    {
        if (isset(Auth::user()->id) && Auth::user()->id!='') {
            echo 1;
        } else {
            echo 2;
        }
    }

}
