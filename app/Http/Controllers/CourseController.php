<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Log;
use App\Course;
use App\Center;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CourseController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$courses = Course::orderBy('semester')->get();

        return response()->json([
			"courses" => $courses->toArray()
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

		if($user->is('admin') || $user->is('departmenthead')){
			
			Log::create([
				'user_id' => $user->id,
				'activity' => 'Creó una nueva materia'
			]);

			$course = new Course();
			$course->id = $request->id;
			$course->name = $request->name;
			$course->credits = $request->credits;
			$course->semester = $request->semester;
			$course->center_id = $request->center_id;
			$course->active = $request->active;

			$course->save();


			return response()->json(['id' => $course->id]);

		}
		else
		{
			return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
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

		$course = Course::find($request->id);

		if($course->id == $id){
			Log::create([
				'user_id' => $user->id,
				'activity' => 'Actualizo el centro: ' .'['. $request->id .'] '. $request->name
			]);

			$course->id = $request->id;
			$course->name = $request->name;
			$course->credits = $request->credits;
			$course->semester = $request->semester;
			$course->center_id = $request->center_id;
			$course->active = $request->active;

			$course->save();

			return response()->json(['success' => true]);

		}
		return response()->json(['success' => false,'error'=>'No se encontro el centro con el ID especificado']);
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
