<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
		$fb = new Facebook\Facebook(Config::get('facebook')); //get facebook credentials from the configuration file
		$request->session()->put('fb',$fb); //the initiated signed request is saved as session
		$helper = $fb->getRedirectLoginHelper(); //invoke login url function
		$permissions = ['email']; // Optional permissions
		$loginUrl = $helper->getLoginUrl('http://localhost/todo/callback', $permissions); //generate login url
		return view('login',['loginUrl' => $loginUrl]); //return to login page with the login url on the Facebook button
});

Route::get('callback', 'LoginController@callback'); //callback url to get access tokens and user profile

Route::get('logout', 'LoginController@logout'); //logout from the app

Route::get('todo', 'TodoController@todo'); // go to todo home page

Route::get('tasks_input', 'TodoController@tasks_input'); //tasks input function through ajax

Route::get('tasks_edit', 'TodoController@tasks_edit'); //tasks edit function through ajax

Route::get('tasks_delete', 'TodoController@tasks_delete'); //tasks delete function through ajax