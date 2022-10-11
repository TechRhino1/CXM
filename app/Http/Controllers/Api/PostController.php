<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoretaskRequest;
use App\Models\Tasks;
use App\Models\SignInOut;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Projectusers;
use App\Models\invoices;
use App\Models\invoice_details;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

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
            $user = auth()->user();
            $role = $user->role;
            $deleted = request('deleted');
            if ($role == '0') {
                if($deleted == '1'){
                    $tasks = Tasks::where('CreaterID', $userid)->whereMonth('EstimatedDate', $month)->whereYear('EstimatedDate', $year)
                    ->where('deleted', '=', '1')
                    ->orderby('EstimatedDate', 'desc')
                    ->get();
                }else{
                    $tasks = Tasks::where('CreaterID', $userid)->whereMonth('EstimatedDate', $month)->whereYear('EstimatedDate', $year)
                    ->where('deleted', '=', '0')
                    ->orderby('EstimatedDate', 'desc')
                    ->get();
                }

            } else {
                $tasks = Tasks::where('CreaterID', $userid)->whereMonth('EstimatedDate', $month)->whereYear('EstimatedDate', $year)
                    ->where('deleted', '=', '0')
                    ->orderby('EstimatedDate', 'desc')
                    ->get();
                foreach ($tasks as $task) {
                    $task->deleted = ($task->deleted == '1' ? 'Deleted' : 'Not Deleted');
                }
            }




            if ($tasks->count() > 0) {
                return $this->success($tasks, 'A total of ' . $tasks->count() . ' Task(s) retrieved', 200);
            } else {
                return $this->success($tasks, 'No Task(s) found');
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
            $totalmanmins = 0;
            $user_id = auth()->user()->ID;
            $date = date('Y-m-d');
            $task = tasks::create([
                'Title' => $request->Title,
                'Description' => $request->Description,
                'ProjectID' => $request->ProjectID,
                'CreaterID' => $request->ID,
                'EstimatedDate' => $request->EstimatedDate,
                'EstimatedTime' => $request->EstimatedTime,
                'Priority' => $request->Priority,
                "InitiallyAssignedToID" => $user_id, //need fix
                "CurrentlyAssignedToID" => $user_id, //need fix
                'CurrentStatus' => $request->CurrentStatus,
                'CompletedDate' => date('Y-m-d'),
                'CompletedTime' => date('H:i:s'),
                'created_at' => $date,
                'updated_at' => $date,
                'ParentID' => 0,
            ]);
            // insert into productuser table
            Projectusers::create([
                'ProjectID' => $request->ProjectID,
                'UserID' => $user_id,
            ]);
            if ($task) {
                $user_id = $request->ID;
                $tasktime = Tasks::where('CreaterID', $user_id)
                    ->where('EstimatedDate', $request->EstimatedDate)
                    ->where('deleted', '0')
                    ->Select('EstimatedTime')
                    ->get();
                if ($tasktime->count() > 0) {
                    foreach ($tasktime as $time) {
                        $data = $time->EstimatedTime;
                        $data = str_replace(':', '.', $data);

                        if (!(strpos($data, '.') !== false)) $data = $data . '.0';

                        $d = explode(".", $data);

                        $totalmanmins += $d[0] * 60 + $d[1];
                    }
                }
                signinout::where('USERID', $user_id)->where('EVENTDATE', $request->EstimatedDate)->update(['TotalTaskMins' => $totalmanmins]);


                return $this->success([$task], 'Task created successfully');
            }
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
            $totalmanmins = 0;
            $tasks = Tasks::where('ID', $request->ID);
            $tasks->update([
                'Title' => $request->Title,
                'Description' => $request->Description,
                'ProjectID' => $request->ProjectID,
                'EstimatedDate' => $request->EstimatedDate,
                'EstimatedTime' => $request->EstimatedTime,
                'Priority' => $request->Priority,
                'CurrentStatus' => $request->CurrentStatus,
                'CompletedDate' => $request->CompletedDate,
                'CompletedTime' => $request->CompletedTime,
                'updated_at' => date('Y-m-d'),
            ]);

            if ($tasks) {
                $user_id = auth()->user()->ID;
                $tasktime = Tasks::where('CreaterID', $user_id)
                    ->where('EstimatedDate', $request->EstimatedDate)
                    ->where('deleted', '0')
                    ->Select('EstimatedTime')
                    ->get();
                if ($tasktime->count() > 0) {
                    foreach ($tasktime as $time) {
                        $data = $time->EstimatedTime;
                        $data = str_replace(':', '.', $data);

                        if (!(strpos($data, '.') !== false)) $data = $data . '.0';

                        $d = explode(".", $data);

                        $totalmanmins += $d[0] * 60 + $d[1];
                    }
                }
                signinout::where('USERID', $user_id)->where('EVENTDATE', $request->EstimatedDate)->update(['TotalTaskMins' => $totalmanmins]);

                return $this->success(message: 'Task updated successfully');
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
    public function destroy(Tasks $tasks)
    {
        try {
            $user = auth()->user();
            $role = $user->role;
            $id = Request('ID');
            $totalmanmins = 0;
            if ($role == '0') {

                $tasks = Tasks::where('ID', $id)->delete();
                if ($tasks) {
                    return $this->success(message: 'Task deleted successfully');
                }
                return $this->error('Task not deleted', 500);
            } elseif ($role == '1') {
                $tasks = Tasks::where('ID', $id);
                $tasks->update([
                    'deleted' => 1,
                    'updated_at' => date('Y-m-d'),
                ]);
                ///////////////////////////////////////////////
                $user_id = auth()->user()->ID;
                $getEstimatedDate = Tasks::where('ID', $id)->get('EstimatedDate');
                $getEstimatedDate = $getEstimatedDate[0]->EstimatedDate;
                $tasktime = Tasks::where('CreaterID', $user_id)
                    ->where('EstimatedDate', $getEstimatedDate)
                    ->where('deleted', '0')
                    ->Select('EstimatedTime')
                    ->get();
                if ($tasktime->count() > 0) {
                    foreach ($tasktime as $time) {
                        $data = $time->EstimatedTime;
                        $data = str_replace(':', '.', $data);

                        if (!(strpos($data, '.') !== false)) $data = $data . '.0';

                        $d = explode(".", $data);

                        $totalmanmins += $d[0] * 60 + $d[1];
                    }
                } else {
                    $totalmanmins = 00.00;
                }
                signinout::where('USERID', $user_id)->where('EVENTDATE', $getEstimatedDate)->update(['TotalTaskMins' => $totalmanmins]);
                //////////////////////////////////////////
                if ($tasks) {
                    return $this->success(message: 'Task deleted successfully');
                }
                return $this->error('Task not deleted', 500);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getStatus()
    {
        try {
            $status = Status::all();
            return $this->success($status, 'A total of ' . $status->count() . ' Status(s) retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getPriority()
    {
        try {
            $priority = Priority::all();
            return $this->success($priority, 'A total of ' . $priority->count() . ' Priority(s) retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    //get uncompleted tasks of user
    public function getUncompletedTasks()
    {
        try {
            $user_id = auth()->user()->ID;
            $tasks = Tasks::where('CreaterID', $user_id)->where('CurrentStatus', '!=', '5')->where('deleted', '=', '0')->orderby('EstimatedDate', 'desc')->get();
            return $this->success($tasks, 'A total of ' . $tasks->count() . ' Uncompleted Task(s) retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function gettaskdetails()
    {
        try {
            $id = request('ID');
            $task = Tasks::find($id);
            return $this->success([$task], 'Task retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getalltasks()
    {
        try {

            $month = request('month');
            $year = request('year');
            $deleted = request('deleted');

            $tasks = Tasks::whereMonth('EstimatedDate', $month)->whereYear('EstimatedDate', $year)
                ->where('deleted', $deleted)
                ->orderby('EstimatedDate', 'desc')
                ->get();

            if ($tasks->count() > 0) {
                return $this->success($tasks, 'A total of ' . $tasks->count() . ' Task(s) retrieved', 200);
            } else {
                return $this->success($tasks, 'No Task(s) found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    //inv
    public function getalltaskbyprojectid()
    {
        try {
            $month = request('month');
            $year = request('year');
            $project_id = request('projectid');
            /////////////////////////////////-----ddd-------//////////////////////////////////////////////////////
            $check = invoice_details::where('project_id', $project_id)
                ->whereMonth('created_at', $month)->whereYear('created_at', $year)
                ->get();
                //////////////////-----ddd-------////////////////
                $totaltaskmins = Tasks::whereMonth('EstimatedDate', $month)->whereYear('EstimatedDate', $year)
                    ->join('projects', function ($join) use ($project_id) {
                        $join->on('tasks.ProjectID', '=', 'projects.ID')
                            ->where('projects.ID', '=', $project_id);
                    })
                    ->where('tasks.deleted', '0')
                    ->sum('EstimatedTime');

                $totaltaskmins = str_replace(':', '.', $totaltaskmins);
                if (!(strpos($totaltaskmins, '.') !== false)) $totaltaskmins = $totaltaskmins . '.0';
                $d = explode(".", $totaltaskmins);
                $totaltaskmins = $d[0] * 60 + $d[1];
                $totaltaskmins = $totaltaskmins / 60;

                //////////////////////////////////////////////////////////////////
                $tasks = Tasks::whereMonth('EstimatedDate', $month)->whereYear('EstimatedDate', $year)
                    ->join('projects', function ($join) use ($project_id) {
                        $join->on('tasks.ProjectID', '=', 'projects.ID')
                            ->where('projects.ID', '=', $project_id);
                    })
                    ->select('tasks.*', 'projects.ClientID', DB::raw($totaltaskmins . ' as totaltaskmins'))
                    ->orderby('EstimatedDate', 'desc')
                    ->get();
                    if ($check->count() > 0) {
                        $totalinvoicemins = invoice_details::whereMonth('invoices.invoice_date', $month)->whereYear('invoices.invoice_date', $year)
                        ->leftjoin ('invoices', function ($join) use ($project_id) {
                            $join->on('invoice_details.invoice_id', '=', 'invoices.ID')
                                ->where('invoice_details.project_id', '=', $project_id);
                        })
                        //->where('project_id', $project_id)
                        ->sum('updated_time');

                        $totalinvoicemins = str_replace(':', '.', $totalinvoicemins);
                        if (!(strpos($totalinvoicemins, '.') !== false)) $totalinvoicemins = $totalinvoicemins . '.0';
                        $d = explode(".", $totalinvoicemins);
                        $totalinvoicemins = $d[0] * 60 + $d[1];
                        $totalinvoicemins = $totalinvoicemins / 60;
                       // $totalinvoicemins = date('H:i', mktime(0, $totalinvoicemins));
                      //  dd($totalinvoicemins);

                        foreach ($tasks as $task) {
                            foreach ($check as $change) {
                                if ($task->ID == $change->task_id) {
                                    $task->Description = $change->updated_comments;
                                    $task->EstimatedTime = $change->updated_time;
                                    $task->totalinvoicemins = $totalinvoicemins;
                                }
                            }
                        }

                    }

            if ($tasks->count() > 0) {
                return $this->success($tasks, 'A total of ' . $tasks->count() . ' Task(s) retrieved', 200);
            } else {
                return $this->success($tasks, 'No Task(s) found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
