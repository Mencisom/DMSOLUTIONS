<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    public function index()
    {
        $projects = DB::table('project')->get();
        return view('browse', ['projects' => $projects]);
    }

}
