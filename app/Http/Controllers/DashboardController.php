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

    public function proj_clients(){
        $proj_clients = DB::table('projects_by_clients')->get();
        return $proj_clients;
    }

    public function proj_month()
    {
        $proj_month = DB::table('projects_by_month')->get();
        return $proj_month;
    }
}
