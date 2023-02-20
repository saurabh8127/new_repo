<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    protected $task;
    public function __construct(TaskRepository $task)
    {
        $this->task = $task;
    }
    //Create task
    public function create(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = $request->all();
        //validate value
        $validated = Validator::make($request->all(), [
            'task_name' => 'required|string',
            'task_start_date' => 'date',
            'task_end_date' => 'date',
            'description' => 'required|string',
            'status' => 'required|integer',
            'board_id' => 'required|integer',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'data' => $validated->errors()->all(),
                'status' => false,
                'message' => 'Enter valid data.',
            ], 400);
        }
        $data = [
            'task_name' => $request->task_name,
            'task_start_date' => $request->task_start_date,
            'task_end_date' => $request->task_end_date,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $user->id,
            'board_id' => $request->board_id,
        ];
        //Create Task Repository
        $task_data = $this->task->addTask($data);

        if (!empty($task_data)) {
            return response()->json([
                'data' => $task_data,
                'status' => true,
                'message' => 'Task added successfully.',
            ], 200);
        } else {
            return response()->json([
                'data' => '',
                'status' => true,
                'message' => 'Data not added..',
            ], 200);
        }

    }

    //Delete task
    public function delete(Request $request, $id)
    {
        //Delete Task Repository
        $this->task->deleteTask($id);

        return response()->json([
            'data' => '',
            'status' => true,
            'message' => 'Data deleted successfully.',
        ], 200);
    }

    public function edit(Request $request)
    {
        $data = $request->all();

        $validated = Validator::make($request->all(), [
            'task_name' => 'required|string',
            'task_start_date' => 'date',
            'task_end_date' => 'date',
            'description' => 'required|string',
            'status' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'data' => $validated->errors()->all(),
                'status' => false,
                'massage' => 'Enter valid value',
            ], 400);
        } else {
            //Edit Task Repository
            $task_data = $this->task->editTask($data);

            if (!empty($task_data)) {
                return response()->json([
                    'data' => $task_data,
                    'status' => true,
                    'message' => 'Data update successfully.',
                ], 200);
            } else {
                return response()->json([
                    'data' => '',
                    'status' => true,
                    'message' => 'Data not found.',
                ], 404);
            }
        }
    }
}
