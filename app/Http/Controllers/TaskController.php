<?php
namespace App\Http\Controllers;

use App\Folder;
use App\Http\Requests\CreateTask;
use App\Task;
use Illuminate\Http\Request;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{
    public function index($id)
    {
        
        $folders = Auth::user()->folders()->get();
        
        // すべてのフォルダを取得する
        $folders = Folder::all();
        // 選ばれたフォルダを取得する
        $current_folder = Folder::find($id);
        // 選ばれたフォルダに紐づくタスクを取得する
        $tasks = $current_folder->tasks()->get();
        
        
        // var_dump($current_folder);
        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $current_folder->id,
            'tasks' => $tasks,
        ]);
    }
    
    public function showCreateForm($id)
    {
        
        return view('tasks/create', [
            'folder_id' => $id,
        ]);
        // var_dump($id); 
    }
  public function showEditForm( $id,  $task_id)
{
    $task = Task::find($task_id);

    return view('tasks/edit', [
        'task' => $task,
    ]);
}
    
    public function create($id, CreateTask $request)
    {
        $current_folder = Folder::find($id);
        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;
        
        // var_dump($current_folder);
        $current_folder->tasks()->save($task);
        
        return redirect()->route('tasks.index', [
            'id' => $current_folder->id,
        ]);
    }
    public function edit( $id,  $task_id, EditTask $request)
{
    // 1
    $task = Task::find($task_id);

    // 2
    $task->title = $request->title;
    $task->status = $request->status;
    $task->due_date = $request->due_date;
    $task->save();

    // 3
    return redirect()->route('tasks.index', [
        'id' => $task->folder_id,
    ]);
}

}