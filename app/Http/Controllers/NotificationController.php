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

		$notifications = Notification::join('users', 'users.id', '=', 'notifications.creator_id')
			->where('notifications.receptor_id', '=', $user->id)
			->select(
				'notifications.created_at', 'notifications.id', 'notifications.read',
				'notifications.redirection', 'notifications.message', 'notifications.creator_role',
				'users.name', 'users.lastname'
			)
			->get();

		$unread = 0;

		foreach($notifications as $notification){
			if(!$notification->read){
				$unread++;
			}
		}

		return response()->json([
			'notifications' => $notifications,
			'unread' => $unread
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
	public function store(Request $request)
	{
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
				return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		if($user->is('departmenthead')){
			$users = $request->user;
			foreach($users as $receptor){
				Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $receptor['id'],
					'read' => '0',
					'redirection' => $request->redirection,
					'message'  => $request->message,
					'creator_role' => 'departmenthead',
				]);
				// \Mail::send('emails.notification', ['name' => $receptor['name'], 'lastname' => $receptor['lastname'], 'bodyMessage' => $request->message], function($message) use ($request, $receptor)
		        //{
		        //     //remitente
		        //    $message->from('noreply@sigeprod.com', 'SIGEPROD');
//
//		            //asunto
////		            $message->subject($request->subject);
//
//		            //receptor
//		            $message->to($receptor['email'], $receptor['name'] + ' ' + $receptor['lastname']);

	//	        });
			}

			return response()->json([
				'success' => true
			]);


		}
		else{
			return response()->json(['error' => 'Sólo el Jefe de Departamento puede enviar notificaciones masivas']);
		}
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

		if($notification->receptor_id == $user->id){
			$notification->read = true;

			$notification->save();

			return response()->json([
				'read' => true,
			]);
		}
		else{
			return response()->json(['error' => 'Sólo el receptor de la notificación puede marcarla como leída'], HttpResponse::HTTP_UNAUTHORIZED);
		}

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
