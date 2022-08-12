<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoretaskRequest;
use App\Models\Tasks;
use App\Models\SignInOut;
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
            $tasks = Tasks::all();
            return response()->json($tasks);
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
    public function store(StoretaskRequest $request)
    {
        try {
            $user_id = auth()->user()->id;
            $date = date('Y-m-d');
     
            $task = tasks::create([
                'user_id' => $user_id,
                'Title' => $request->Title,
                'Description' => $request->Description,
                'ProjectID' => $request->ProjectID,
                'CreaterID' => $user_id,
                'EstimatedDate' => $request->EstimatedDate,
                'EstimatedTime' => $request->EstimatedTime,
                'Priority' => $request->Priority,
                'CurrentStatus' => $request->CurrentStatus,
                'CompletedDate' => $request->CompletedDate,
                'CompletedTime' => $request->CompletedTime,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            return $this->success( $task,'Task created successfully');

            //UPDATE TotalTaskMins
            $data = $request->estimated_time;
            $data = str_replace(':', '.', $data);
            if (!(strpos($data, '.') !== false)) $data = $data . '.0';

            $d = explode(".", $data);

            $totalmanmins = $d[0] * 60 + $d[1];
            signinout::where('user_id', $user_id)->where('EVENTDATE', $date)->update(['TotalTaskMins' => $totalmanmins]);
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
                return $this->success( $todo,'Task deleted successfully');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
