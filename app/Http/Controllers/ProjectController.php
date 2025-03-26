<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController
{
    public function index()
    {
        $projects = DB::table('projects')->get();
        return view('browse', ['projects' => $projects]);
    }

    public function consult(Request $request)
    {
        try {
            $projectConsult = DB::table('projects')->where('quote_id', $request->project)->first();
            if ($projectConsult) {
                return response()->json([
                    'success' => 'true',
                    'data' => $projectConsult
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron productos'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function store(Request $request, Project $project){
        $project -> id = 0;
        $project -> quote_id = $request->hiddenQuoteId;
        $project -> proj_name= $request->projName;
        $project -> proj_start_date = $request->projStartDate;
        $project -> proj_end_date = $request->projEndDate;
        $project -> proj_visit = $request->calendar;
        $project -> proj_deposit = $request->projDeposit;
        $project -> proj_warranty = $request->projWarranty;
        $project -> status_id = 1;
        try{
            $project->save();
            return to_route('browse')->with('status','Proyecto Agregado exitosamente');
        }catch (Exception $e){
            return to_route('browse')->with('status','Error al agregar el proyecto');
        }
    }
}
