<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Projects;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class ProjectController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $projects = Projects::all();
            return response()->json($projects);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
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
        try {
            $project = Projects::create($request->all());
            return $this->success([
                'message' => 'Project created successfully',
                'project' => $project
            ], 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }


     // $project = Projects::create([
    //     'Title' => $request->Title,
    //     'Description' => $request->Description,
    //     'UserID' => auth()->user()->id,
    //     'Billing'=> $request->Billing,
    //     'TotalHours'=> $request->TotalHours,
    //     'InternalComments'=> $request->InternalComments,
    //     'BillingType'=> $request->BillingType,
    //     'Status'=> $request->Status,
    //     'HourlyINR'=> $request->HourlyINR,
    //     'Currency'=> $request->Currency,
    //     'StartDate'=> $request->StartDate,
    //     'EndDate'=> $request->EndDate,
    //     'TotalClientHours'=> $request->TotalClientHours,
    //     'Comments'=> $request->Comments,
    // ]);

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
        try {
            $project = Projects::find($request->id);

            if ($project->update($request->all())) {
                return $this->success([
                    'success' => true,
                    'message' => 'Project updated successfully',
                    'project' => $project
                ], 200);
            } else {
                return $this->error('Project not updated', 400);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
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
        try {
            $project = Projects::find($id);
            if ($project->delete()) {
                return $this->success(['Project deleted successfully', $project], 200);
            } else {
                return $this->error('Project not deleted', 500);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
