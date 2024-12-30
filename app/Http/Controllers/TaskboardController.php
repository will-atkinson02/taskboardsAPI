<?php

namespace App\Http\Controllers;

use App\Models\Taskboard;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskboardController extends Controller
{
    public function createTaskboard(Request $request): JsonResponse 
    {   
        $user_id = User::where('username', $request->username)->value('id');

        $nameCheck = Taskboard::where('name', 'LIKE', 'Untitled %')
                        ->whereRaw('name REGEXP ?', ['^Untitled [0-9]+$'])
                        ->orderByRaw('CAST(SUBSTRING(name, 9) AS UNSIGNED) DESC')
                        ->value('name');

        $taskboard = new Taskboard();

        if (!$nameCheck) {
            $taskboard->name = "Untitled 1";    
        } else {
            $taskboard->name = "Untitled ". (String)((int)substr($nameCheck, 9) + 1);
        }
        $taskboard->user_id = $user_id;

        if ($taskboard->save()) {
            return response()->json([
                'message' =>  'Taskboard created',
                'success' => true,
                'taskboard_id' => $taskboard->id
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }

    public function getTaskBoardData(int $taskboardId): JsonResponse
    {   
        $taskboard = Taskboard::with(['stages.tasks'])->find($taskboardId);
        
        return response()->json([
            'message' =>  'Taskboard data retrieved',
            'success' => true,
            'data' => $taskboard
        ]);
    }

    public function changeTaskboardName(int $taskboardId, Request $request): JsonResponse
    {
        $taskboard = Taskboard::find($taskboardId);

        $taskboard->name = $request->name;

        if ($taskboard->save()) {
            return response()->json([
                'message' =>  'Taskboard name changed',
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }
}
