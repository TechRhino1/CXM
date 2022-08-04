<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoretaskRequest;
use App\Models\Tasks;
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
         
        $task = tasks::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'task' => $task
        
        ], 200);
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
            'success' => false,
            'message' => 'Task not updated',
            'task' => $tasks,
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
