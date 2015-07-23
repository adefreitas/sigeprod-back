<?php namespace App\Http\Controllers;

use App\Log;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;

class LogController extends Controller {

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
			return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		if($user->is('departmenthead') || $user->is('admin')){
			$log = Log::join('users', 'users.id', '=', 'logs.user_id')
			->select('logs.created_at as created_at', 'users.name', 'users.lastname', 'users.id as user_id', 'logs.activity', 'logs.id')
			->get();

			Log::create([
				'user_id' => $user->id,
				'activity' => 'consulto la bitacora'
			]);

			return response()->json([
			'log' => $log,
			]);

		}

		else{
			return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}
	}
		/**
		* Show the form for creating a new resource.
		*
		* @return Response
		*/
		public function create()
		{
			//
		}

		/**
		* Store a newly created resource in storage.
		*
		* @return Response
		*/
		public function store()
		{
			//
		}

		/**
		* Display the specified resource.
		*
		* @param  int  $id
		* @return Response
		*/
		public function show($id)
		{
			//
		}

		/**
		* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return Response
		*/
		public function edit($id)
		{
			//
		}

		/**
		* Update the specified resource in storage.
		*
		* @param  int  $id
		* @return Response
		*/
		public function update($id)
		{
			//
		}

		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return Response
		*/
		public function destroy($id)
		{
			//
		}

	}
