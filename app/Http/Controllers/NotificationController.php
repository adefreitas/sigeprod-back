<?php namespace App\Http\Controllers;

use App\User;
use App\Notification;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;

class NotificationController extends Controller {

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

		$notifications = Notification::where('receptor_id', '=', $user->id)->get();

		return response()->json([
			'notifications' => $notifications,
		]);

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
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
				return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		$notification = Notification::find($id);

		$notification->read = true;

		$notification->save();

		return response()->json([
			'read' => true,
		]);

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
