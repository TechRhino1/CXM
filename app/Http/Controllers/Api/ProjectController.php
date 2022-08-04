<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Projects;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Projects::all();
        return response()->json($projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Projects::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'project' => $project
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function show(Projects $projects)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function edit(Projects $projects)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Projects $projects)
    {
        
       $project = Projects::find($request->id);
       
       if($project->update($request->all())){
           return response()->json([
               'success' => true,
               'message' => 'Project updated successfully',
               'project' => $project
           ], 200);
       }
       else{
           return response()->json([
               'success' => false,
               'message' => 'Project not updated',
               'project' => $project
           ], 400);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Projects::find($id);
        if($project->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully',
                'project' => $project
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'ERROR deleting project',
                'project' => $project
            ], 400);
        }
    }
}
