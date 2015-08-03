<?php namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Http\Requests;
use App\TeacherHelper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class TeacherHelperController extends Controller {

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

		$helpers = [];

		if($user->is('departmenthead') || $user->is('departmentsecretary')){

			$helpers = \DB::table('teacher_helpers_users')
			->join('teacher_helpers', 'teacher_helpers.id', '=', 'teacher_helpers_users.teacher_helper_id')
			->join('users', 'users.id', '=', 'teacher_helpers_users.user_id')
			->join('courses_teacher_helpers', 'courses_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id', 'left outer')
			->join('centers_teacher_helpers', 'centers_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id', 'left outer')
			// ->where('teacher_helpers_users.active', '=', true)
			->orderBy('teacher_helpers.type', 'asc')
			->orderBy('users.id', 'asc')
			->select(
			'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email',
			'users.id as user_id', 'users.local_phone', 'users.cell_phone',
			'users.state', 'users.municipality', 'users.address',
			'teacher_helpers.id as teacher_helper_id',
			'courses.name as course_name', 'courses.id as course_id',
			'centers.name as center_name', 'centers.id as center_id',
			'teacher_helpers.updated_at', 'teacher_helpers.created_at',
			'teacher_helpers_users.id as thu_id',
			'teacher_helpers.type as type', 'courses_teacher_helpers.active as course_active', 'centers_teacher_helpers.active as center_active',
			'teacher_helpers_users.contest_id'
			)
			->get();

			foreach($helpers as $helper){
				$pre = \DB::table('preapproved_users')
				->where('email', '=', $helper->user_email)
				->orderBy('updated_at', 'desc')
				->get();
				$helper->files = \DB::table('fileentries')
				->where('fileentries.preapproved_id', '=', $pre[0]->id)
				->orderBy('updated_at', 'desc')
				->get();
			}


		}
		else if($user->is('centercoordinator') || $user->is('coursecoordinator')){
			$centers = $user->professor->centerCoordinator;
			$courses = $user->professor->courseCoordinator;

			$centers_ids = array();
			$courses_ids = array();

			foreach($centers as $center){
				array_push($centers_ids, $center->id);
			}
			foreach($courses as $course){
				array_push($courses_ids, $course->id);
			}
			// return response()->json([
			// 	'response' => $courses_ids
			// 	]);
			$helpers = \DB::table('teacher_helpers_users')
			->join('teacher_helpers', 'teacher_helpers.id', '=', 'teacher_helpers_users.teacher_helper_id')
			->join('users', 'users.id', '=', 'teacher_helpers_users.user_id')
			->join('courses_teacher_helpers', 'courses_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id', 'left outer')
			->join('centers_teacher_helpers', 'centers_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id', 'left outer')
			// ->where('teacher_helpers_users.active', '=', true)
			->orderBy('teacher_helpers_users.active', 'desc')
			->orderBy('teacher_helpers.type', 'asc')
			->orderBy('users.id', 'asc')
			->whereIn('courses_teacher_helpers.course_id', $courses_ids)
			->orWhereIn('centers_teacher_helpers.center_id', $centers_ids)
			->select(
			'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email',
			'users.id as user_id', 'users.local_phone', 'users.cell_phone',
			'users.state', 'users.municipality', 'users.address',
			'teacher_helpers.id as teacher_helper_id',
			'courses.name as course_name', 'courses.id as course_id',
			'centers.name as center_name', 'centers.id as center_id',
			'teacher_helpers.updated_at', 'teacher_helpers.created_at',
			'teacher_helpers_users.id as thu_id',
			'teacher_helpers.type as type', 'courses_teacher_helpers.active as course_active', 'centers_teacher_helpers.active as center_active',
			'teacher_helpers_users.contest_id'
			)
			->get();

		}

		return response()->json([
			'helpers' => $helpers
			]);

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
		* @param  int  $id = id en la tabla teacher_helpers_users
		* @return Response
		*/
		public function update(Request $request, $id)
		{

			$current = \DB::table('teacher_helpers_users')
				->where('id', '=', $id)
				->where('contest_id', '=', $request->contest_id)
				// ->where('active', '=', true)
				->first();

			//Si se esta ratificando el preparador para ese concurso, se modifica la fecha de actualizacion
			if(!$request->retiring)
			{

				\DB::table('teacher_helpers_users')
					->where('id', '=', $id)
					->where('contest_id', '=', $request->contest_id)
					->where('active', '=', true)
					->update([
						'updated_at' => Carbon::now()
					]);

					return response()->json(['success'=>true]);

			}
			//Sino
			else if($request->retiring)
			{
				\Log::info('current->type'.$current->type);
				$current_type = $current->type;
				$currenthelper_id = $current->teacher_helper_id;


				$others = \DB::table('teacher_helpers_users')
					->where('teacher_helper_id', '=', $currenthelper_id)
					->where('active', '=', true)
					->where('id', '!=', $id)
					->get();

				$typeIsDifferent = false;
				$newtype = $current_type;

				$currenthelper = TeacherHelper::find($currenthelper_id);

				if($request->center_id){
						$currenthelper->removeCenter($request->center_id, $request->contest_id);
				}
				else if($request->course_id){
						$currenthelper->removeCourse($request->course_id, $request->contest_id);
				}

				$currenthelper->save();

				foreach($others as $other){
					if($other->type > $current_type){
						$typeIsDifferent = true;
						$newtype = $other->type;
					}
				}

				if(!$typeIsDifferent && $others == null){

					\DB::table('teacher_helpers_users')
						->where('id', '=', $id)
						->where('contest_id', '=', $request->contest_id)
						->where('active', '=', true)
						->update([
							'active' => false
					]);

					$currenthelper->clear();

					return response()->json(['success', true]);

				}
				else if(!$typeIsDifferent && $others != null){

					\DB::table('teacher_helpers_users')
						->where('id', '=', $id)
						->where('contest_id', '=', $request->contest_id)
						->where('active', '=', true)
						->update([
							'active' => false
					]);

					return response()->json(['success', true]);
				}

				else if($typeIsDifferent && $others != null){


					$old_centers = $currenthelper->centers();
					$old_courses = $currenthelper->courses();

					$old_centers_ids = array();
					$old_courses_ids = array();

					foreach($old_centers as $old_center){
							array_push($old_centers_ids, $old_center->id);
					}
					foreach($old_courses as $old_course){
							array_push($old_courses_ids, $old_course->id);
					}

					$newhelper = TeacherHelper::where('available', '=', true)
						->where('reserved', '=', false)
						->where('type', '=', $newtype)
						->first();

					if($newhelper == null){

						// \DB::table('teacher_helpers_users')
						// 	->where('id', '=', $id)
						// 	->where('contest_id', '=', $request->contest_id)
						// 	->where('active', '=', true)
						// 	->update([
						// 		'active' => false
						// ]);

						//Se queda con su ID de preparador anterior por no haber plazas disponibles del nuevo tipo
						return response()->json(['success', true]);
					}
					else{
						//Se le asigna un ID de preparador nuevo que cumpla con el nuevo tipo
						$helper_users = $currenthelper->user;
						$current_user = null;
						foreach($helper_users as $helper_user){
							$aux = \DB::table('teacher_helpers_users')
								->where('active', '=', true)
								->where('user_id', '=', $helper_user->id)
								->first();
								if($aux != null){
									$current_user = $aux;
								}
						}

						TeacherHelper::where('available', '=', true)
							->where('reserved', '=', false)
							->where('id', '=', $newhelper->id)
							->update([
								'available' => false
						]);
						\Log::info('current_user->id'.$current_user->id);
						$newhelper->user()->attach($current_user->id, ['contest_id'=> $request->contest_id, 'type' => $newtype]);



						foreach($old_centers_ids as $old_center_id){
							$newhelper->setCenter($old_center_id, $request->contest_id);
						}
						foreach($old_courses_ids as $old_course_id){
							$newhelper->setCourse($old_course_id, $request->contest_id);
						}

						$currenthelper->clear();

						\DB::table('teacher_helpers_users')
							->where('id', '=', $id)
							->where('contest_id', '=', $request->contest_id)
							->where('active', '=', true)
							->update([
								'active' => false
						]);
					}
					return response()->json(['success' => true]);
				}
			}
			return response()->json(['success', true]);
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
