<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProjectController
{
    public function index()
    {
        $projects = DB::table('projects')->get();
        return view('browse', ['projects' => $projects]);
    }
}
