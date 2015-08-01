<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\TeacherHelper;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeacherHelperController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// try {
		// 	JWTAuth::parseToken();
		// 	$token = JWTAuth::getToken();
		// } catch (Exception $e){
		// 	return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		// }

		// $tokenOwner = JWTAuth::toUser($token);

		// $user = User::where('email', $tokenOwner->email)->first();
		
		// if($user->is('coursecoordinator') || $user->is('centercoordinator') || $user->is('departmenthead')){
			
			// if($user->is('departmentHead')){
				
				$helpers = \DB::table('teacher_helpers_users')
					->join('teacher_helpers', 'teacher_helpers.id', '=', 'teacher_helpers_users.teacher_helper_id')
					->join('users', 'users.id', '=', 'teacher_helpers_users.user_id')
					->join('courses_teacher_helpers', 'courses_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
					->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id', 'left outer')
					->join('centers_teacher_helpers', 'centers_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
					->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id', 'left outer')
					// ->join('fileentries', 'fileentries.preapproved_id', '=', 'teacher_helpers_users.user_id')
					->where('teacher_helpers_users.active', '=', true)
					// ->select('teacher_helpers.id', 'users.name', 'users.lastname', 'users.email', 'users.id',
					// 	'courses.name', 'courses.id', 'centers.name', 'centers.id',
					// 	'fileentries'
					// )
					->orderBy('teacher_helpers.type', 'desc')
					->orderBy('users.id', 'asc')
					->select(
						'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email',
						'users.id as user_id', 'teacher_helpers.id as teacher_helper_id', 
						'courses.name as course_name', 'courses.id as course_id',
						'centers.name as center_name', 'centers.id as center_id',
						'teacher_helpers.updated_at', 'teacher_helpers.created_at',
						'teacher_helpers_users.id as thu_id',
						'teacher_helpers.type as type'
					)
					->get();	
				
					foreach($helpers as $helper){
						$helper->files = \DB::table('fileentries')
							->where('fileentries.preapproved_id', '=', $helper->thu_id)
							->orderBy('updated_at', 'desc')
							->get();
					}
				// $helpers = TeacherHelper::where('available', '=', false)
				// 	->get();
				// $response = array();
				// foreach($helpers as $helper){
				// 	$object =  new \Illuminate\Database\Eloquent\Collection;
					
				// 	$object->user = $helper->user;
				// 	$object->files = $helper->getFiles();
				// 	$object->helper = $helper;
				// 	\Log::info($object);
				// 	array_push($response, $object);
				// 	// $helper->files = $helper->getFiles();
				// }
			// }
			
			return response()->json([
				'helpers' => $helpers
			]);
			
		// }
		return response()->json([ 'message' => 'No autorizado' ], 404);
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
