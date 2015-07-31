<?php namespace App\Http\Controllers;

use \Hash;
use App\Log;
use App\User;
use App\TeacherHelper;
use App\Contest;
use App\Course;
use App\Center;
use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

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

		$users = User::select('name','lastname','email','id', 'municipality', 'state', 'address', 'local_phone', 'cell_phone', 'alternate_email')
					->get();

		return response()->json([
	            'users' => $users,
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
	public function update(Request $request, $id)
	{
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
				return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		Log::create([
			'user_id' => $user->id,
			'activity' => 'Actualizó su perfil de usuario: ' . $id
		]);


		if($user->id == $id){

			$user->name = $request->name;
			$user->lastname = $request->lastname;
			$user->email = $request->email;
			$user->alternate_email = $request->alternate_email;
			$user->password = \Hash::make($request->password);
			$user->local_phone = $request->local_phone;
			$user->cell_phone = $request->cell_phone;
			$user->state = $request->state;
			$user->municipality = $request->municipality;
			$user->address = $request->address;

			$user->save();

			return response()->json(['success' => true]);

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
	public function showPreapprovedUser(Request $request, $id){
		
		$user = \DB::table('preapproved_users')
			->where('personal_id', '=', $id)
			->first();
			
		return response()->json(['user' => $user]);
	}
	
	public function updatePreapprovedUser(Request $request, $id){
		$user = \DB::table('preapproved_users')
			->where('personal_id', '=', $id)
			->where('id', '!=', $request->id)
			->first();
		if($user){
			return response()->json(['error' => 'Ya existe un usuario con esa cedula de identidad'], 404);
		}
		else{
			$user = \DB::table('preapproved_users')
			->where('personal_id', '=', $id)
			->update([
				'personal_id' => $request->personal_id,
				'name' => $request->name,
				'lastname' => $request->lastname,
				'email' => $request->email
			]);
			return response()->json(['success' => true]);	
		}
	}
	
	public function createPreapprovedUser(Request $request, $id){
		$exists = \DB::table('preapproved_users')
			->where('personal_id', '=', $request->personal_id)
			->where('activated', '=', false)
			->orderBy('created_at', 'desc')
			->first();
		$preapproved = $request;
		if($preapproved == null){
			return response()->json(['error' => 'No existe un usuario preaprobado con esa cedula de identidad'], 404);
		}
		
		$user = User::find($id);
		
		if($user == null){
			$user = User::create([
				'name' => $preapproved->name,
				'lastname' => $preapproved->lastname,
				'email' => $preapproved->email,
				'password' => Hash::make($preapproved->password),
				'id' => $preapproved->personal_id
			]);
		}
			
		$user->name = $preapproved->name;
		$user->lastname = $preapproved->lastname;
		$user->email = $preapproved->email;
		$user->password = Hash::make($preapproved->password);
		
		\DB::table('preapproved_users')
			->where('personal_id', '=', $request->personal_id)
			->where('activated', '=', false)
			->update([
				"activated" => true
			]);
			
		// $preapproved->activated = true;
		
		// $preapproved->save();
		
		$user->save();
		
		$contest = Contest::find($preapproved->contest_id);
		
		$center = $contest->center;
		$course = $contest->course;
		
		$helper = TeacherHelper::where('type', '=', $preapproved->type)
			->where('available', '=', true)
			->where('reserved', '=', true)
			->first();
			
					
		if($helper){				
			$helper->user()->attach($user->id);
			
			if(count($center) > 0){
				$center = $center[0]->id;
				$helper->setCenter($center);
			}
			if(count($course) > 0){
				$course = $course[0]->id;
				$helper->setCourse($course);
			}
			
			$helper->available = false;
			$helper->save();
		}
		
		else{
			return response()->json(['error' => 'No hay plazas disponibles para este tipo de preparador'], 404);
		}
		
	}

}
