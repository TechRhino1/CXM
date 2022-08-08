<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoretaskRequest;
use App\Models\Tasks;
use App\Models\SignInOut;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Tasks::all();
        return response()->json($tasks);
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
        $user_id = auth()-> user()-> id;
        $date = date('Y-m-d');


         
        $task = tasks::create([
            //'user_id' => $user_id,
            'Title' => $request->title,
            'Description' => $request->description,
            'ProjectID' => $request->project_id,
            'CreaterID' => $user_id,
            'EstimatedDate' => $date,
            'EstimatedTime' => $request->estimated_time,
            'Priority' => $request->priority,
            'CurrentStatus' => $request->current_status,
            'CompletedDate' =>$request->completed_date,
            'CompletedTime' => $request->completed_time,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'task' => $task
        
        ], 200);
      //UPDATE TotalTaskMins
      $data = $request->estimated_time;
      $data = str_replace(':','.',$data);
      if (!(strpos($data, '.') !== false)) $data = $data.'.0';

		$d = explode(".", $data);

        $totalmanmins = $d[0] * 60 + $d[1];         
      signinout::where('user_id',$user_id)->where('EVENTDATE',$date)->update(['TotalTaskMins' => $totalmanmins]);
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
        $tasks = Tasks::find($request->id);
     
        if($tasks->update($request->all())){
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $tasks
            ], 200);
        }
        return response()->json([
            'message' => 'Task not updated',
            
            'error' => error_get_last()
            
        ], 400);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo =Tasks::find($id);

       if(!$todo){
           return response()->json([
               'success' => false,
               'message' => 'Error deleting task',
               'error' => error_get_last()
           ], 400);
       }else{
           $todo->delete();
           return response()->json([
               'success' => true,
               'message' => 'Task deleted successfully',
               'Data' => $todo
           ], 200);
       }
    }
}
