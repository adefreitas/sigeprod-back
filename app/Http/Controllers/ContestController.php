<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Course;
use App\Contest;
use App\Professor;
use App\Observation;
use App\Notification;
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
				array_push($result, $item);
			}

	        return response()->json([
	            'contests' => $result,
	        ]);
	    }
	    /*
	     *  Si el usuario es jefe de departamento se envian todas las propuestas existentes
	     */
	    else if($user->is('departmenthead')){

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
				array_push($result, $item);
			}

	        return response()->json([
	            'contests' => $result,
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

		if($user->is('coursecoordinator') || $user->is('centercoordinator')){

			$contest = new Contest();
			$contest->professor_id = $user->id;
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
				'redirection' => 'departmentHead.helperContest',
				'message'  => $user->name . ' ' . $user->lastname . ' ' . 'ha solicitado un nuevo concurso de preparadores'
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

			}

			else if( $user->is('coursecoordinator') || $user->is('centercoordinator') ){
				if( $request->status == 1 || $request->status == 4){
					$contest->status = $request->status;
				}
			}

			$contest->save();

			/* Manejo de notifications */

			if($user->is('departmenthead')){
				$message = '';
				$redirection = '';

				if($contest->course.length() > 0){
					$redirection = 'courseCoordinator.helperContest';
				}
				else{
					$redirection = 'centerCoordinator.helperContest';
				}

				if($request->status == 2){
					$message = $user->name . ' ' . $user->lastname . ' ' . 'ha aceptado su solicitud de concurso de preparadores';
				}
				if($request->status == 3 ){
					$message = $user->name . ' ' . $user->lastname . ' ' . 'ha rechazado su solicitud de concurso de preparadores';
				}

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $contest->professor_id,
					'read' => '0',
					'redirection' => $redirection,
					'message'  => $message
				]);
			}

			else if( $user->is('coursecoordinator') || $user->is('centercoordinator') ){

				$message = $user->name . ' ' . $user->lastname . ' ' . 'ha modificado su solicitud de concurso de preparadores';

				$redirection = 'departmentHead.helperContests';

				$receptor = User::where('email', '=', 'jefe@ciens.ucv.ve')->get()->first();

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $receptor->id,
					'read' => '0',
					'redirection' => $redirection,
					'message'  => $message
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
