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
use App\Users; //Users model to connect to User table

class LoginController extends Controller
{
	public function callback(Request $request)
	{						
		$fb = $request->session()->get('fb');
		$helper = $fb->getRedirectLoginHelper();

		try {
		  $accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  $request->session()->flash('graph_error', $e->getMessage());
		  return Redirect::to('/');
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  $request->session()->flash('graph_error', $e->getMessage());
		  return Redirect::to('/');
		}

		if (! isset($accessToken)) {
		  if ($helper->getError()=="access_denied") {
		  	$request->session()->flash('access_error', 'Access Denied');
		  	return Redirect::to('/');
		  } else {
		    header('HTTP/1.0 400 Bad Request');
		    $request->session()->flash('graph_error', 'Bad Request');
		  	return Redirect::to('/');
		  }
		}

		try {
		  // Returns a `Facebook\FacebookResponse` object
		  $response = $fb->get('/me?fields=id,name,email', $accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  $request->session()->flash('response_error', $e->getMessage());
		  return Redirect::to('/');
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  $request->session()->flash('response_error', $e->getMessage());
		  return Redirect::to('/');
		}

		$user = $response->getGraphUser();
		$validate_user = Users::where('fb_id', $user['id'])->first();
		if($validate_user){ //if user exists redirect to todo home page
			$request->session()->put('user_id',$validate_user->fb_id);
			$request->session()->put('user_name',$validate_user->fb_name);
			$request->session()->put('accessToken', (string) $accessToken);
			return Redirect::to('todo');
		}
		else{ //if new user add user profile to database and then redirect to todo home page
			$add_user = new Users;
			$add_user->fb_id = $user['id'];
			$add_user->fb_name = $user['name'];
			if(isset($user['email'])){
				$add_user->email = $user['email'];
			}
			$add_user->save();
			$request->session()->put('user_id',$add_user->fb_id);
			$request->session()->put('user_name',$add_user->fb_name);
			$request->session()->put('accessToken', (string) $accessToken);	
			return Redirect::to('todo');		
		}
	}

	public function logout(Request $request)
	{
		$request->session()->flush();
		return Redirect::to('/');
	}
}