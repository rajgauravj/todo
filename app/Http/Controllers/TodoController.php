<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Redirect;
use Response;
use DateTime;
use Session;
use App\Users;
use App\Tasks; //Task model to connect to Task table

class TodoController extends Controller
{
	public function todo(Request $request)
	{
		$user_id = $request->session()->get('user_id');
		$tasks = Tasks::where('fb_id',$user_id)->where('deletion',null)->get();
		$all_tasks = Tasks::where('fb_id',$user_id)->where('deletion',null)->count();
		$pending_tasks = Tasks::where('fb_id',$user_id)->where('completion','pending')->where('deletion',null)->count();
		$completed_tasks = Tasks::where('fb_id',$user_id)->where('completion','complete')->where('deletion',null)->count();
		return view('todo',['tasks' => $tasks, 'all_tasks' => $all_tasks, 'pending_tasks' => $pending_tasks, 'completed_tasks' => $completed_tasks]);
	}

	public function tasks_input()
	{
		$fb_id = Input::get('user_id');
		$task = Input::get('task');
		$add_task = new Tasks;
		$add_task->fb_id = $fb_id;
		$add_task->task = $task;
		$add_task->completion = 'pending';
		$add_task->save();
		return Response::json(array('id' => $add_task->id));
	}

	public function tasks_delete()
	{
		$fb_id = Input::get('user_id');
		$task_id = Input::get('task_id');
		$update_task = Tasks::where('id',$task_id)->update(array('deletion' => 'deleted'));
		if($update_task)
			return 1;
		else
			return 0;
	}

	public function tasks_edit()
	{
		$fb_id = Input::get('user_id');
		$task_id = Input::get('task_id');
		$completion = Input::get('completion');
		$update_task = Tasks::where('id',$task_id)->update(array('completion' => $completion));
		if($update_task)
			return 1;
		else
			return 0;
	}
}