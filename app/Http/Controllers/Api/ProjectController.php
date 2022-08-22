<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Projects;
use App\Models\Projectusers;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Symfony\Component\VarDumper\VarDumper;

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
            if($projects->count() > 0){
                return $this->success($projects, message: ' A total of '.$projects->count().' Project(s) retrieved');
            }else{
                return $this->success($projects, message: 'No Project(s) found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
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
            $project = Projects::create([
                    //'Title' => $request->Title,
                    'Description' => $request->Description,
                    'UserID' =>$request->UserID,
                    'ClientID' => $request->ClientID,
                    'Comments'=> $request->Comments,
                    'InternalComments'=> $request->InternalComments,
                    'Billing'=> $request->Billing,
                    'StartDate'=> $request->StartDate,
                    'EndDate'=> $request->EndDate,
                    'TotalHours'=> $request->TotalHours,
                    'TotalClientHours'=> $request->TotalClientHours,
                    'HourlyINR'=> $request->HourlyINR,
                    'BillingType'=> $request->BillingType,
                    'Status'=> $request->Status,
                    'Currency'=> $request->Currency,
                ]);
            return $this->success($project, 'Project created successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }



    // $project = Projects::create([
    //     //'Title' => $request->Title,
    //     'Description' => $request->Description,
    //     'UserID' =>$request->UserID,
    //     'ClientID' => $request->ClientID,
    //     'Comments'=> $request->Comments,
    //     'InternalComments'=> $request->InternalComments,
    //     'Billing'=> $request->Billing,
    //     'StartDate'=> $request->StartDate,
    //     'EndDate'=> $request->EndDate,
    //     'TotalHours'=> $request->TotalHours,
    //     'TotalClientHours'=> $request->TotalClientHours,
    //     'HourlyINR'=> $request->HourlyINR,
    //     'BillingType'=> $request->BillingType,
    //     'Status'=> $request->Status,
    //     'Currency'=> $request->Currency,
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
                return $this->success($project, 'Project updated successfully');
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
                return $this->success($project, 'Project deleted successfully');
            } else {
                return $this->error('Project not deleted', 400);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    // public function getprojectstatus()
    // {



    // }

    public function getmyprojectstatus()
    {
        //SELECT projects.ID, projects.DESCRIPTION, projects.TOTALHOURS, projects.INTERNALCOMMENTS FROM projects LEFT JOIN projectusers ON (projects.ID = projectusers.`project_id` );
        try {
           // $Userid = request('userid');
            $Userid = auth()->user()->id;
            $projects = projects::leftjoin('projectusers', 'projects.id', '=', 'projectusers.project_id')
                ->where('projectusers.user_id', $Userid)
                ->orderBy('projects.Description', 'DESC')
                ->get();
                foreach ($projects as $project) {
                    $project->TotalHours = gmdate("i:s", $project->TotalHours);
                }

            if($projects->count() > 0){

                return $this->success($projects, 'A total of '.$projects->count().' Information(s) retrieved successfully');
            }else{
                return $this->success($projects, 'No Projects found for this user');
            }

            // if(isset($projects)){
            //     $pid= $projects->project_id;
            //     $tasks = Tasks::select('EstimatedTime', 'CurrentlyAssignedToID')->where('ProjectID', '=', $pid)->get();
            //     $ET = $tasks->EstimatedTime;
            //     $CA = $tasks->CurrentlyAssignedToID;


            //     return $this->success($tasks, 200);
            // }else{
            //     return $this->error('No projects found', 400);
            // }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }

    }
    public function saveprojectofuser(Request $request)
    {
        try {
           // $project = Projects::find($request->id);
           // $project->UserID = auth()->user()->id;
            $projects = 0;
            return $this->success(  $projects, 'Project saved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    public function getprojectbyuser_id()
    {
        try {
            $Userid = request('id');
            print_r($Userid);
            $projects = Projects::where('UserID', $Userid)->get();
            if($projects->count() > 0){

                return $this->success($projects, 'A total of '.$projects->count().' Information(s) retrieved successfully');
            }else{
                return $this->success($projects, 'No Projects found for this user');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }


}
