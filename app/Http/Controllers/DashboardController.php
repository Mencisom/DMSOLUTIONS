<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function proj_status(){
        $proj_status = DB::table('project_statuses')->get();
        return $proj_status;
    }

}
