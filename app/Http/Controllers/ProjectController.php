<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProjectController
{
    public function index()
    {
        $projects = DB::table('project')->get();
        return view('browse', ['projects' => $projects]);
    }
}
