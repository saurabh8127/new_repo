<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskBoardMapping;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{

    public function addTask($data)
    {

        $task_data = Task::create($data);
        if (!empty($task_data)) {
            $mapping = [
                'user_id' => $task_data->user_id,
                'board_id' => $data['board_id'],
                'task_id' => $task_data->id,
                'status' => 3,
            ];
            TaskBoardMapping::create($mapping);
        }
        return $task_data;
    }
    public function deleteTask($id)
    {
        $task_data = Task::find($id);
        if (!empty($task_data)) {
            TaskBoardMapping::where('task_id', $task_data->id)->delete();
        }
        $task_data->delete();
        return $task_data;
    }

    public function editTask($data)
    {
        $user = Auth::guard('api')->user();
        $task_data = Task::where('id', $data['task_id'])->first();

        if (!empty($task_data)) {
            $task_data->task_name = $data['task_name'];
            $task_data->description = $data['description'];
            $task_data->task_start_date = $data['task_start_date'];
            $task_data->task_end_date = $data['task_end_date'];
            $task_data->status = $data['status'];
            $task_data->user_id = $user->id;

            $task_data->update();
            return $task_data;
        } else {
            return $task_data = [];
        }
    }
}
