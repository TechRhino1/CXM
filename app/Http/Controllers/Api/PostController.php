<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoretaskRequest;
use App\Models\Tasks;
use App\Models\SignInOut;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Projectusers;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class PostController extends Controller
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
            $userid = request('userid');
            $month = request('month');
            $year = request('year');

            $tasks = Tasks::where('CreaterID', $userid)->whereMonth('EstimatedDate', $month)->whereYear('EstimatedDate', $year)->get();
            if ($tasks->count() > 0) {
                return $this->success($tasks , 'A total of '.$tasks->count().' Task(s) retrieved' , 200);
            } else {
                return $this->success($tasks , 'No Task(s) found');
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
    public function store(StoretaskRequest $request)
    {
        try {
            $user_id = auth()->user()->id;
            $date = date('Y-m-d');


            $task = tasks::create([
                //'user_id' => $user_id,
                'Title' => $request->Title,
                'Description' => $request->Description,
                'ProjectID' => $request->ProjectID,
                'CreaterID' => $request->id,
                'EstimatedDate' => $request->EstimatedDate,
                'EstimatedTime' => $request->EstimatedTime,
                'Priority' => $request->Priority,
                "InitiallyAssignedToID" =>$user_id, //need fix
                "CurrentlyAssignedToID"=>$user_id, //need fix
                'CurrentStatus' => $request->CurrentStatus,
                'CompletedDate' => date('Y-m-d' ),
                'CompletedTime' => date('H:i:s'),
                'created_at' => $date,
                'updated_at' => $date,
                'ParentID' => 0 ,
            ]);
            // insert into productuser table
            Projectusers::create([
                'project_id' => $request->ProjectID,
                'user_id' => $user_id,
            ]);

            //UPDATE TotalTaskMins in signinout table
            $data = $request->EstimatedTime;
            $data = str_replace(':', '.', $data);

            if (!(strpos($data, '.') !== false)) $data = $data . '.0';

            $d = explode(".", $data);

            $totalmanmins = $d[0] * 60 + $d[1];
            //get TotalTaskMins of user today
            $signinout = SignInOut::where('user_id', $user_id)->where('EVENTDATE', $request->EstimatedDate)->Select('TotalTaskMins')->get();
            $totaltmin = $signinout[0]->TotalTaskMins;
            signinout::where('user_id', $user_id)->where('EVENTDATE', $request->EstimatedDate)->update(['TotalTaskMins' =>  $totaltmin + $totalmanmins]);
            return $this->success($task, 'Task created successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show(Tasks $tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function edit(Tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function update(StoretaskRequest $request, Tasks $tasks)
    {
        try {
            $tasks = Tasks::find($request->id);

            if ($tasks->update($request->all())) {

                //UPDATE TotalTaskMins in signinout table
                $user_id = auth()->user()->id;
                $tasks = $request->EstimatedTime;
                $tasks = str_replace(':', '.', $tasks);

                if (!(strpos($tasks, '.') !== false)) $data = $tasks . '.0';

                $d = explode(".", $tasks);

                $totalmanmins = $d[0] * 60 + $d[1];

                signinout::where('user_id', $user_id)->where('EVENTDATE', $request->EstimatedDate)->update(['TotalTaskMins' => $totalmanmins]); //need fix
                //////////////////////////////
                return $this->success([
                    'success' => true,
                    'message' => 'Task updated successfully',
                    'task' => $tasks
                ], 200);
            }
            return $this->error('Task not updated', 500);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $todo = Tasks::find($id);

            if (!$todo) {
                return $this->error('Task not found', 404);
            } else {
                $todo->delete();
                return $this->success($todo, 'Task deleted successfully');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getStatus()
    {
        try {
            $status = Status::all();
            return $this->success($status , 'A total of '.$status->count().' Status(s) retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getPriority()
    {
        try {
            $priority = Priority::all();
            return $this->success($priority , 'A total of '.$priority->count().' Priority(s) retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    //get uncompleted tasks of user
    public function getUncompletedTasks()
    {
        try {
            $user_id = auth()->user()->id;
            $tasks = Tasks::where('CreaterID', $user_id)->where('CurrentStatus', '!=', '3')->get();
            return $this->success($tasks , 'A total of '.$tasks->count().' Uncompleted Task(s) retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function gettaskdetails()
    {
        try {
            $id = request('id');
            $task = Tasks::find($id);
            return $this->success($task , 'Task retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
