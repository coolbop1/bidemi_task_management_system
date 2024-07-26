<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'tag' => 'required|string|max:255',
            'estimated_hours' => 'nullable|integer',
            'assigner_id' => 'nullable|integer',
            'assignee_id' => 'nullable|integer',
            'status' => 'nullable|in:todo,in-progress,done',
        ]);
        if ($validator->fails()) {
            $responseArr['message'] = $validator->errors();
            return response()->json($responseArr, Response::HTTP_BAD_REQUEST);
        }


        $user = $request->user();
        $existing_titles_count = Tasks::where('tag', $request->tag)->where('title', $request->title)->count();
        $task_data = [
            'title' => $existing_titles_count > 0 ? $request->title."($existing_titles_count)" : $request->title,
            'tag' => $request->tag,
            'user_id' =>  $user->id,
            'estimated_hours' => $request->estimated_hours ?? null,
            'assigner_id' => $request->assigner_id ?? null,
            'assignee_id' => $request->assignee_id ?? null,
        ];
        $task = Tasks::create($task_data);


        return response()->json([
            'data' => $task,
        ], Response::HTTP_CREATED);
    }

    public function listAll(Request $request, $by_tag = null)
    {
        $tag = null;
        if($by_tag) {
            $tag = str_replace('_',' ', $by_tag);
        }
        $search = $request->q;

        $tasks = Tasks::when($tag, function ($q, $tag){
            return $q->where('tag', $tag);
        })->when($search, function ($q, $search){
            return $q->where('tag', 'LIKE', '%'.$search.'%')->orWhere('title', 'LIKE', '%'.$search.'%');
        })->paginate(20);

        return response()->json([
            'data' => $tasks,
        ]);
    }

    public function myTask(Request $request, $by_tag = null)
    {
        $tag = null;
        if($by_tag) {
            $tag = str_replace('_',' ', $by_tag);
        }
        $search = $request->q;

        $tasks = $request->user()->tasks()->when($tag, function ($q, $tag){
            return $q->where('tag', $tag);
        })->when($search, function ($q, $search){
            return $q->where('tag', 'LIKE', '%'.$search.'%')->orWhere('title', 'LIKE', '%'.$search.'%');
        })->paginate(20);
        

        return response()->json([
            'data' => $tasks,
        ]);
    }

    public function view(Request $request, $task_id)
    {
        $task = $this->getTask($task_id);
        $task = Tasks::with('owner', 'assignee', 'assigner')->find($task_id);
        return response()->json([
            'data' => $task,
        ]);
    }

    public function assignTask(Request $request, $task_id, $user_id = null)
    {
        $task = $this->getTask($task_id);

        $user = $assigner =  $request->user();
        if($user_id) {
            $user = User::find($user_id);
            if(is_null($user)) {
                $responseArr['message'] = "User not found";
                return response()->json($responseArr, Response::HTTP_NOT_FOUND);
            }
        }

        $task->assignee_id = $user->id;
        $task->assigner_id = $assigner->id;

        $task->save();

        $responseArr['message'] = "Task assigned successfully";
        $responseArr['data'] = $task;
        return response()->json($responseArr, Response::HTTP_OK);
    }

    public function updateTaskTime(Request $request, $task_id)
    {
        $validator = Validator::make($request->all(), [
            'hours' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $responseArr['message'] = $validator->errors();
            return response()->json($responseArr, Response::HTTP_BAD_REQUEST);
        }
        $task = $this->getTask($task_id);
        $task->estimated_hours = $request->hours;
        $task->save();


        $responseArr['message'] = "Task estimated time updated successfully";
        $responseArr['data'] = $task;
        return response()->json($responseArr, Response::HTTP_OK);

    }

    public function moveTask(Request $request, $task_id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:todo,in-progress,done',
        ]);
        if ($validator->fails()) {
            $responseArr['message'] = $validator->errors();
            return response()->json($responseArr, Response::HTTP_BAD_REQUEST);
        }
        $task = $this->getTask($task_id);
        if($task->assignee_id != $request->user()->id) {
            $responseArr['message'] = "You can only move task assigned to you";
            return response()->json($responseArr, Response::HTTP_FORBIDDEN);
        }
        $task->status = $request->status;
        $task->save();

        $responseArr['message'] = "Task moved successfully";
        $responseArr['data'] = $task;
        return response()->json($responseArr, Response::HTTP_OK);

    }

    public function deleteTask(Request $request, $task_id)
    {
        $task = $this->getTask($task_id);
        if($task->user_id != $request->user()->id) {
            $responseArr['message'] = "You can only delete task created by you";
            return response()->json($responseArr, Response::HTTP_FORBIDDEN);
        }
        $task->delete();
        $responseArr['message'] = "Task deleted successfully";
        $responseArr['data'] = [];
        return response()->json($responseArr, Response::HTTP_OK);
    }

    public function getTask($task_id) {
        $task = Tasks::find($task_id);
        if(is_null($task)) {
            $response = "Task not found";
            abort(Response::HTTP_NOT_FOUND, $response);
        }
        return $task;
    }

}