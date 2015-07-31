<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Log;
use App\User;
use App\Course;
use App\Contest;
use Carbon\Carbon;
use App\Professor;
use App\Observation;
use App\Notification;
use App\TeacherHelper;
use App\PreapprovedUser;
use App\CourseCoordinator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ContestController extends Controller {


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

	    /*
	     *  Si el usuario es coordinador de materia y coordinador de centro
	     */
	    if($user->is('coursecoordinator') && $user->is('centercoordinator')){

			$everything = Contest::join('professors','professors.id', '=', 'contests.professor_id')
				->join('users', 'users.id', '=', 'professors.user_id')
				->join('contest_course', 'contest_course.contest_id', '=', 'contests.id', 'left outer')
				->join('courses', 'courses.id', '=', 'contest_course.course_id', 'left outer')
				->join('center_contest', 'center_contest.contest_id', '=', 'contests.id', 'left outer')
				->join('centers', 'centers.id', '=', 'center_contest.center_id', 'left outer')
				->select(
					'contests.id as contest_id', 'contests.teacher_helpers_1', 'contests.teacher_helpers_2', 'contests.teacher_assistants', 'contests.status as contest_status',
					'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email', 'users.id as user_id',
					'courses.name as course_name', 'courses.id as course_id',
					'centers.id as center_id', 'centers.name as center_name'

				)
				->get();

			$result = array();

			foreach($everything as $item){
				$item->observations =
					Observation::where('contest_id', '=', $item->contest_id)
					->join('users', 'users.id', '=', 'observations.user_id')
					->orderBy('observations.created_at', 'desc')
					->select(
						'observations.created_at', 'observations.description',
						'users.lastname', 'users.name', 'users.id as user_id',
						'observations.id as observation_id'
					)
					->get();

				$item->preapproved_users =
					PreapprovedUser::where('contest_id', '=', $item->contest_id)
					->orderBy('preapproved_users.type', 'asc')
					->orderBy('preapproved_users.personal_id', 'asc')
					->select(
						'preapproved_users.name', 'preapproved_users.lastname', 'preapproved_users.email',
						'preapproved_users.personal_id', 'preapproved_users.name', 'preapproved_users.type',
						'preapproved_users.contest_id'
					)
					->get();

				array_push($result, $item);
			}

	        return response()->json([
	            'contests' => $result,
	        ]);
	    }

	    /*
	     *  Si el usuario es coordinador de Materia se envian las propuestas
	     *  que corresponden a las materias que coordina
	     */
		else if($user->is('coursecoordinator')){

			$course = $user->Professor->courseCoordinator[0];

			$everything = Contest::join('professors','professors.id', '=', 'contests.professor_id')
				->join('users', 'users.id', '=', 'professors.user_id')
				->join('contest_course', 'contest_course.contest_id', '=', 'contests.id', 'left outer')
				->join('courses', 'courses.id', '=', 'contest_course.course_id', 'left outer')
				->join('center_contest', 'center_contest.contest_id', '=', 'contests.id', 'left outer')
				->join('centers', 'centers.id', '=', 'center_contest.center_id', 'left outer')
				->where('courses.id', '=', $course->id)
				->select(
					'contests.id as contest_id', 'contests.teacher_helpers_1', 'contests.teacher_helpers_2', 'contests.teacher_assistants', 'contests.status as contest_status',
					'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email', 'users.id as user_id',
					'courses.name as course_name', 'courses.id as course_id',
					'centers.id as center_id', 'centers.name as center_name'

				)
				->get();

			$result = array();

			foreach($everything as $item){
				$item->observations =
					Observation::where('contest_id', '=', $item->contest_id)
					->join('users', 'users.id', '=', 'observations.user_id')
					->orderBy('observations.created_at', 'desc')
					->select(
						'observations.created_at', 'observations.description',
						'users.lastname', 'users.name', 'users.id as user_id',
						'observations.id as observation_id'
					)
					->get();
					$item->preapproved_users =
					PreapprovedUser::where('contest_id', '=', $item->contest_id)
					->orderBy('preapproved_users.type', 'asc')
					->orderBy('preapproved_users.personal_id', 'asc')
					->select(
						'preapproved_users.name', 'preapproved_users.lastname', 'preapproved_users.email',
						'preapproved_users.personal_id', 'preapproved_users.name', 'preapproved_users.type',
						'preapproved_users.contest_id'
					)
					->get();
				array_push($result, $item);
			}

	        return response()->json([
	            'contests' => $result,
	        ]);
		}

	    /*
	     *  Si el usuario es coordinador de Centro se envian las propuestas que corresponden a su centro
	     */

	    else if($user->is('centercoordinator')){

			$center = $user->Professor->centerCoordinator[0];

			$everything = Contest::join('professors','professors.id', '=', 'contests.professor_id')
				->join('users', 'users.id', '=', 'professors.user_id')
				->join('contest_course', 'contest_course.contest_id', '=', 'contests.id', 'left outer')
				->join('courses', 'courses.id', '=', 'contest_course.course_id', 'left outer')
				->join('center_contest', 'center_contest.contest_id', '=', 'contests.id', 'left outer')
				->join('centers', 'centers.id', '=', 'center_contest.center_id', 'left outer')
				->where('centers.id', '=', $center->id)
				->select(
					'contests.id as contest_id', 'contests.teacher_helpers_1', 'contests.teacher_helpers_2', 'contests.teacher_assistants', 'contests.status as contest_status',
					'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email', 'users.id as user_id',
					'courses.name as course_name', 'courses.id as course_id',
					'centers.id as center_id', 'centers.name as center_name'

				)
				->get();

			$result = array();

			foreach($everything as $item){
				$item->observations =
					Observation::where('contest_id', '=', $item->contest_id)
					->join('users', 'users.id', '=', 'observations.user_id')
					->orderBy('observations.created_at', 'desc')
					->select(
						'observations.created_at', 'observations.description',
						'users.lastname', 'users.name', 'users.id as user_id',
						'observations.id as observation_id'
					)
					->get();
					$item->preapproved_users =
					PreapprovedUser::where('contest_id', '=', $item->contest_id)
					->orderBy('preapproved_users.type', 'asc')
					->orderBy('preapproved_users.personal_id', 'asc')
					->select(
						'preapproved_users.name', 'preapproved_users.lastname', 'preapproved_users.email',
						'preapproved_users.personal_id', 'preapproved_users.name', 'preapproved_users.type',
						'preapproved_users.contest_id'
					)
					->get();
				array_push($result, $item);
			}

	        return response()->json([
	            'contests' => $result,
	        ]);

	    }
	    /*
	     *  Si el usuario es jefe de departamento se envian todas las propuestas existentes
	     */
	    else if($user->is('coursecoordinator') && $user->is('departmenthead')){

	    	$helper_1 = TeacherHelper::where('type', '=', 1)
	    								->where('reserved', '=', false)
	    								->count();
	    	$helper_2 = TeacherHelper::where('type', '=', 2)
	    								->where('reserved', '=', false)
	    								->count();
	    	$assistant = TeacherHelper::where('type', '=', 3)
	    								->where('reserved', '=', false)
	    								->count();

			$everything = Contest::join('professors','professors.id', '=', 'contests.professor_id')
				->join('users', 'users.id', '=', 'professors.user_id')
				->join('contest_course', 'contest_course.contest_id', '=', 'contests.id', 'left outer')
				->join('courses', 'courses.id', '=', 'contest_course.course_id', 'left outer')
				->join('center_contest', 'center_contest.contest_id', '=', 'contests.id', 'left outer')
				->join('centers', 'centers.id', '=', 'center_contest.center_id', 'left outer')
				->select(
					'contests.id as contest_id', 'contests.teacher_helpers_1', 'contests.teacher_helpers_2', 'contests.teacher_assistants', 'contests.status as contest_status',
					'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email', 'users.id as user_id',
					'courses.name as course_name', 'courses.id as course_id',
					'centers.id as center_id', 'centers.name as center_name'

				)
				->get();

			$result = array();

			foreach($everything as $item){
				$item->observations =
					Observation::where('contest_id', '=', $item->contest_id)
					->join('users', 'users.id', '=', 'observations.user_id')
					->orderBy('observations.created_at', 'desc')
					->select(
						'observations.created_at', 'observations.description',
						'users.lastname', 'users.name', 'users.id as user_id',
						'observations.id as observation_id'
					)
					->get();
					$item->preapproved_users =
					PreapprovedUser::where('contest_id', '=', $item->contest_id)
					->orderBy('preapproved_users.type', 'asc')
					->orderBy('preapproved_users.personal_id', 'asc')
					->select(
						'preapproved_users.name', 'preapproved_users.lastname', 'preapproved_users.email',
						'preapproved_users.personal_id', 'preapproved_users.name', 'preapproved_users.type',
						'preapproved_users.contest_id'
					)
					->get();
				array_push($result, $item);
			}

	        return response()->json([
	            'contests' => $result,
	            'helper_1' => $helper_1,
	            'helper_2' => $helper_2,
	            'assistant' => $assistant,
	        ]);
	    }
	    /*
	     *  Si el usuario es jefe de departamento se envian todas las propuestas existentes
	     */
	    else if($user->is('departmenthead')){

	    	$helper_1 = TeacherHelper::where('type', '=', 1)
	    								->where('reserved', '=', false)
	    								->count();
	    	$helper_2 = TeacherHelper::where('type', '=', 2)
	    								->where('reserved', '=', false)
	    								->count();
	    	$assistant = TeacherHelper::where('type', '=', 3)
	    								->where('reserved', '=', false)
	    								->count();

			$everything = Contest::join('professors','professors.id', '=', 'contests.professor_id')
				->join('users', 'users.id', '=', 'professors.user_id')
				->join('contest_course', 'contest_course.contest_id', '=', 'contests.id', 'left outer')
				->join('courses', 'courses.id', '=', 'contest_course.course_id', 'left outer')
				->join('center_contest', 'center_contest.contest_id', '=', 'contests.id', 'left outer')
				->join('centers', 'centers.id', '=', 'center_contest.center_id', 'left outer')
				->select(
					'contests.id as contest_id', 'contests.teacher_helpers_1', 'contests.teacher_helpers_2', 'contests.teacher_assistants', 'contests.status as contest_status',
					'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email', 'users.id as user_id',
					'courses.name as course_name', 'courses.id as course_id',
					'centers.id as center_id', 'centers.name as center_name'

				)
				->get();

			$result = array();

			foreach($everything as $item){
				$item->observations =
					Observation::where('contest_id', '=', $item->contest_id)
					->join('users', 'users.id', '=', 'observations.user_id')
					->orderBy('observations.created_at', 'desc')
					->select(
						'observations.created_at', 'observations.description',
						'users.lastname', 'users.name', 'users.id as user_id',
						'observations.id as observation_id'
					)
					->get();
				$item->preapproved_users =
					PreapprovedUser::where('contest_id', '=', $item->contest_id)
					->orderBy('preapproved_users.type', 'asc')
					->orderBy('preapproved_users.personal_id', 'asc')
					->select(
						'preapproved_users.name', 'preapproved_users.lastname', 'preapproved_users.email',
						'preapproved_users.personal_id', 'preapproved_users.name', 'preapproved_users.type',
						'preapproved_users.contest_id'
					)
					->get();
				array_push($result, $item);
			}

	        return response()->json([
	            'contests' => $result,
	            'helper_1' => $helper_1,
	            'helper_2' => $helper_2,
	            'assistant' => $assistant,
	        ]);

	    }

		else{
        	return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

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

		Log::create([
			'user_id' => $user->id,
			'activity' => 'Creó un nuevo concurso de preparadores'
		]);

		if($user->is('coursecoordinator') || $user->is('centercoordinator')){

			$contest = new Contest();
			$contest->professor_id = $user->Professor->id;
			$contest->status = 1;
			$contest->teacher_helpers_1 = $request->teacher_helpers_1;
			$contest->teacher_helpers_2 = $request->teacher_helpers_2;

			if(!$request->teacher_helpers_1){
				$contest->teacher_helpers_1 = 0;
			}
			if(!$request->teacher_helpers_2){
				$contest->teacher_helpers_2 = 0;
			}
			if($user->is('centercoordinator')){
				$contest->teacher_assistants = $request->teacher_assistants;
			}
			if(!$request->teacher_assistants || !$user->is('centercoordinator')){
				$contest->teacher_assistants = 0;
			}

			$contest->save();

			if($request->course_id){
				$contest->course()->attach($request->course_id);
			}
			if($request->center_id){
				$contest->center()->attach($request->center_id);
			}

			if($request->observations){

				$observation = new Observation();
				$observation->description = $request->observations;
				$observation->user_id = $user->id;

				$contest->observations()->save($observation);

				$observation->save();

				$observation->save();
			}

			$contest->save();

			$receptor = User::where('email', '=', 'jefe@ciens.ucv.ve')->get()->first();

			$notification = Notification::create([
				'creator_id' => $user->id,
				'receptor_id' => $receptor->id,
				'read' => '0',
				'redirection' => 'user.dashboard.departmentHead.helperContest',
				'message'  => 'ha solicitado un nuevo concurso de preparadores',
				'creator_role' => 'coordinator'
			]);

			return response()->json(['id' => $contest->id]);

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
	public function show(Request $request, $id)
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
			'activity' => 'Consultó el concurso de preparadores con ID: ' . $id
		]);

		$request = $request->all();

		$contest = Contest::find($id);

		return response()->json(['contest' => $contest]);

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
			'activity' => 'Actualizó el concurso de preparadores con ID: ' . $id
		]);
		$contest = Contest::find($id);

		// $request = $request->all();

		if($user->is('coursecoordinator') || $user->is('centercoordinator') || $user->is('departmenthead')){
			if($request->observations){
				$observation = new Observation();
				$observation->description = $request->observations;
				$observation->user_id = $user->id;

				$contest->observations()->save($observation);

				$observation->save();

				$contest->save();
			}

			if( $request->teacher_helpers_1 ){

				$contest->teacher_helpers_1 = $request->teacher_helpers_1;

			}

			if( $request->teacher_helpers_2 ){

				$contest->teacher_helpers_2 = $request->teacher_helpers_2;

			}

			if( $request->teacher_assistants ){

				$contest->teacher_assistants = $request->teacher_assistants;

			}

			if( $user->is('departmenthead') ){

				$contest->status = $request->status;
				
				for($i = 0; $i < $contest->teacher_helpers_1; $i++){
					
					$helper = TeacherHelper::where('type', '=', 1)
						->where('available', '=', true)
						->where('reserved', '=', false)
						->first();
								
					$helper->reserved = true;
					$helper->reserved_for = $contest->id;
					$helper->save();
				}
				for($i = 0; $i < $contest->teacher_helpers_2; $i++){
					
					$helper = TeacherHelper::where('type', '=', 2)
						->where('available', '=', true)
						->where('reserved', '=', false)
						->first();
								
					$helper->reserved = true;
					$helper->reserved_for = $contest->id;
					$helper->save();
				}
				for($i = 0; $i < $contest->teacher_assistants; $i++){
					
					$helper = TeacherHelper::where('type', '=', 3)
						->where('available', '=', true)
						->where('reserved', '=', false)
						->first();
								
					$helper->reserved = true;
					$helper->reserved_for = $contest->id;
					$helper->save();
				}

			}

			else if( $user->is('coursecoordinator') || $user->is('centercoordinator') ){
				if( $request->status == 1 || $request->status == 4){
					$contest->status = $request->status;
				}

				if($request->status == 4){
					if($request->results){
						$results = $request->results;
	
						foreach($results as $item){

							\DB::table('preapproved_users')
								->insert([
									"personal_id" => $item["id"],
									"email" => $item["email"],
									"name" => $item["name"],
									"lastname" => $item["lastname"],
									"type" => $item["type"],
									"contest_id" => $contest->id,
									"created_at" => Carbon::now(),
									"updated_at" => Carbon::now()
							]);
						}
					}
				}
			}

			$contest->save();

			/* Manejo de notifications */

			if($user->is('departmenthead')){
				$message = '';
				$redirection = '';

				if(count($contest->course) > 0){
					$redirection = 'user.dashboard.courseCoordinator.helperContest';
				}
				else{
					$redirection = 'user.dashboard.centerCoordinator.helperContest';
				}

				if($request->status == 2){
					$message = 'ha aceptado su solicitud de concurso de preparadores';
				}
				if($request->status == 3 ){
					$message = 'ha rechazado su solicitud de concurso de preparadores';
				}

				$receptor = Professor::find($contest->professor_id)->user;

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $receptor->id,
					'read' => '0',
					'redirection' => $redirection,
					'message'  => $message,
					'creator_role' => 'departmenthead',
				]);
			}

			else if( $user->is('coursecoordinator') || $user->is('centercoordinator') ){
				if($request->status == 4){
					$message = 'ha finalizado su concurso de preparadores';
				}
				else{
					$message = 'ha modificado su solicitud de concurso de preparadores';
				}

				$redirection = 'user.dashboard.departmentHead.helperContests';

				$receptor = User::where('email', '=', 'jefe@ciens.ucv.ve')->get()->first();

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $receptor->id,
					'read' => '0',
					'redirection' => $redirection,
					'message'  => $message,
					'creator_role' => 'coordinator'
				]);
			}

			return response()->json(['contest' => $contest, 'request' => $request]);

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
