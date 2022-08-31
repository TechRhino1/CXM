<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Projects;
use App\Models\Projectusers;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
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
            if ($projects->count() > 0) {
                return $this->success($projects, message: ' A total of ' . $projects->count() . ' Project(s) retrieved');
            } else {
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

                'Description' => $request->Description,
                'UserID' => $request->UserID,
                'ClientID' => $request->ClientID,
                'Comments' => $request->Comments,
                'InternalComments' => $request->InternalComments,
                'Billing' => $request->Billing,
                'StartDate' => $request->StartDate,
                'EndDate' => $request->EndDate,
                'TotalHours' => $request->TotalHours,
                'TotalClientHours' => $request->TotalClientHours,
                'HourlyINR' => $request->HourlyINR,
                'BillingType' => $request->BillingType,
                'Status' => $request->Status,
                'Currency' => $request->Currency,
            ]);
            return $this->success($project, 'Project created successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
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


    public function saveprojectofuser(Request $request) //need clarification
    {
        try {
            // $project = Projects::find($request->id);
            // $project->UserID = auth()->user()->id;
            $projects = 0;
            return $this->success($projects, 'Project saved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    public function getprojectbyuser_id()
    {
        try {
            //$Userid = request('id');
            $Userid = auth()->user()->id;
            DB::statement("SET SQL_MODE=''");
            $projects = Projects::where('UserID', $Userid)->groupBy('Description')->get();
            if ($projects->count() > 0) {

                return $this->success($projects, 'A total of ' . $projects->count() . ' Information(s) retrieved successfully');
            } else {
                return $this->success($projects, 'No Projects found for this user');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    public function getprojectstatusbyuser()
    {
        try {
            $totalmins = 0;
            $totalmanmins = 0;
            $projectsarray = [];
            $Userid = auth()->user()->id;
            $getprojectid = DB::table("tasks")->where("CURRENTLYASSIGNEDTOID", "=", $Userid)->distinct()->get(['PROJECTID']);
            DB::statement("SET SQL_MODE=''");
            $getprojectid  = $getprojectid->pluck('PROJECTID');

            $projects = projects::select('projects.id', 'projects.Description', 'projects.TotalHours', 'projects.InternalComments', 'projects.UserID')
                //->leftjoin('projectusers', 'projects.id', '=', 'projectusers.project_id')
                //->join('tasks', 'projects.id', '=', 'tasks.ProjectID')
                // ->where('tasks.CreaterID',  $Userid)
                //->where('projects.UserID', $Userid)
                ->whereIn('projects.id', $getprojectid)
                ->groupBy('projects.Description')
                // ->orderBy('projects.Description', 'asc')
                ->get();

            foreach ($projects as $project) {
                $taskdata = Tasks::select('EstimatedTime', 'CurrentlyAssignedToID')
                    ->where('ProjectID', $project->id)->get();
                foreach ($taskdata as $task) {
                    $ET =  $task->EstimatedTime;
                    $CA =  $task->CurrentlyAssignedToID;
                    $data = str_replace(':', '.', $ET);
                    if (!(strpos($ET, '.') !== false)) $ET = $ET . '.0';
                    $d = explode(".", $data);
                    $totalmins += $d[0] * 60 + $d[1];


                    if ($CA == $Userid) {

                        $totalmanmins = $totalmanmins + ($d[0] * 60 + $d[1]);
                        // $totalmins = $totalmins + $totalmanmins;
                    }
                }
                // $totalmins = gmdate('i:s', $totalmins);

                $pro = [

                    'id' => $project->id,

                    'description' =>  $project->Description,

                    'userid' => $project->UserID,

                    'totalworkhours' => date("i:s", ($totalmins)),

                    'totaluserworkhours' => date("i:s", ($totalmanmins)),

                    'totalhours' => $project->TotalHours,

                    'internalcomments' => $project->InternalComments,
                ];
                $projectsarray[] = $pro;
            }

            if ($projects->count() > 0) {
                return $this->success($projectsarray, 'A total of ' . $projects->count() . ' Information(s) retrieved successfully');
            } else {
                return $this->success($projectsarray, 'No Projects found ');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    public function getprojectstatusbystatus()
    {
        try {
            $getstatus = request('status');
            $projects = Projects::where('Status', $getstatus)->get();
            if ($projects->count() > 0) {
                return $this->success($projects, message: ' A total of ' . $projects->count() . ' Project(s) retrieved');
            } else {
                return $this->success($projects, message: 'No Project(s) found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function projectsInProgress()
    {
        //SELECT p.ID, p.DESCRIPTION, p.TOTALHOURS, p.INTERNALCOMMENTS,FROM projects p LEFTJOIN projectusers pu ON (p.ID = pu.PROJECTID AND pu.USERID = $d->id) WHERE p.STATUS = 'i'
        try {
            $totalmins = 0;
            $totalmanmins = 0;
            $Userid = auth()->user()->id;
            $projects = Projects::select('projects.id', 'projects.Description', 'projects.TotalHours', 'projects.InternalComments', 'projects.UserID')
                ->leftJoin('projectusers', function ($join) {
                    $join->on('projects.id', '=', 'projectusers.project_id')
                        ->where('projectusers.user_id', '=', 2);
                })
                ->where('projects.Status', 'i')
                ->join('tasks', 'projects.id', '=', 'tasks.ProjectID')
                //->groupBy('projects.Description')
                ->orderBy('projects.Description', 'Desc')
                ->get();

            foreach ($projects as $project) {

                $taskdata = Tasks::select('EstimatedTime', 'CurrentlyAssignedToID')
                    ->where('ProjectID', $project->id)->get();

                foreach ($taskdata as $task) {
                    $ET =  $task->EstimatedTime;
                    $CA =  $task->CurrentlyAssignedToID;
                    $data = str_replace(':', '.', $ET);
                    if (!(strpos($ET, '.') !== false)) $ET = $ET . '.0';
                    $d = explode(".", $data);
                    $totalmins += $d[0] * 60 + $d[1];


                    if ($CA == $Userid) {

                        $totalmanmins = $totalmanmins + ($d[0] * 60 + $d[1]);

                    }
                }

                $pro = [

                    'id' => $project->id,

                    'description' =>  $project->Description,

                    'userid' => $project->UserID,

                    'totalworkhours' => date("i:s", ($totalmins)),

                    'totaluserworkhours' => date("i:s", ($totalmanmins)),

                    'totalhours' => $project->TotalHours,

                    'internalcomments' => $project->InternalComments,
                ];
                $projectsarray[] = $pro;
            }

            if ($projects->count() > 0) {
                return $this->success($projectsarray, 'A total of ' . $projects->count() . ' Information(s) retrieved successfully');
            } else {
                return $this->success($projects, 'No Projects found for this user');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    public function getprojectbyid()
    {
        try {
            $getprojectid = request('id');
            $projects = Projects::join ('users', 'users.id', '=', 'projects.UserID')
                ->where('projects.id', $getprojectid)
                ->select('projects.*', 'users.name')
                ->get();

            if ($projects->count() > 0) {
                return $this->success($projects,' Information(s) retrieved successfully');
            } else {
                return $this->success($projects, 'No Projects found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
}
}
