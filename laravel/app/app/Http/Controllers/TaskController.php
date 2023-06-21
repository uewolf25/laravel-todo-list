<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 未完了のタスクを取得
        $incomplete_tasks = Task::where('status', false)->get();

        // 完了のタスクを取得
        $done_tasks = Task::where('status', true)->get();

        return view('tasks.index', compact('incomplete_tasks', 'done_tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 登録処理
     */
    public function store(Request $request)
    {
        $rules = [
            'task_name' => 'required|max:100',
        ];

        $messages = [
            'required' => '必須項目です',
            'max' => '100文字以下にしてください。'
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $task = new Task;

        $task->name = $request->input('task_name');
        // dd($task_name);

        $task->save();

        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * 編集内容をトップ画面へ送る処理
     */
    public function edit(string $id)
    {
        $task = Task::find($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     * 編集処理・ステータス変更処理
     */
    public function update(Request $request, string $id)
    {
        // 編集処理
        if($request->status === null){
            
            $rules = [
                'task_name' => 'required|max:100',
            ];
    
            $messages = [
                'required' => '必須項目です',
                'max' => '100文字以下にしてください。'
            ];
    
            Validator::make($request->all(), $rules, $messages)->validate();
    
            $task = Task::find($id);
    
            $task->name = $request->input('task_name');
    
            $task->save();

        // ステータス更新処理
        } else{
            $task = Task::find($id);

            // 完了→未完了
            if($task->status){
                $task->status = false;

            // 未完了→完了
            } else{
                $task->status = true;
            }

            $task->save();
        }

        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     * 削除処理
     */
    public function destroy(string $id)
    {
        Task::find($id)->delete();

        return redirect('tasks');
    }
}
