<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Course;
use App\Contest;
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
        return response()->json([
            'message' => 'Coordinador de materia y de centro'
        ]);
    }

    /*
     *  Si el usuario es coordinador de Materia se envian las propuestas
     *  que corresponden a las materias que coordina
     */
	else if($user->is('coursecoordinator')){

		$professor = $user->professor;

		$professor_id = $professor->id;

		$courses = $professor->courseCoordinator;

		$courses_ids = array();

		$courses_contests = array();
		foreach($courses as $course){

			array_push($courses_ids, $course->id);

			array_push($courses_contests, $course->contests);

		}

		$courses = array();
		foreach($courses_ids as $course_id){
			echo $course_id + '\n';
		}
		foreach($courses_ids as $course_id){
			echo $course_id;
			echo "\n";
			array_push($courses, Course::find($course_id));
		}
		// $materia = Course::find($courses[0]->id);

		// $contestses = $materia->contests;
		// $contest = $course;

		return response()->json([
			'professor_id' => $professor_id,
			'courses' => $courses,
			// 'materia' => $materia,
			'contestses' => $courses_contests,
		]);
	}

    /*
     *  Si el usuario es coordinador de Centro se envian las propuestas que corresponden a su centro
     */

    else if($user->is('centercoordinator')){
        return response()->json([
            'message' => 'Coordinador de centro'
        ]);
    }
    /*
     *  Si el usuario es jefe de departamento se envian todas las propuestas existentes
     */
    else if($user->is('coursecoordinator') && $user->is('departmenthead')){
        return response()->json([
            'message' => 'Coordinador de Materia y Jefe de departamento'
        ]);
    }
    /*
     *  Si el usuario es jefe de departamento se envian todas las propuestas existentes
     */
    else if($user->is('departmenthead')){
		$departmentHead = Contest::get();

        return response()->json([
			'departmentHead' => $departmentHead
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
			$contest->professor_id = $request->professor_id;
			$contest->teacher_helpers_1 = $request->teacher_helpers_1;
			$contest->teacher_helpers_2 = $request->teacher_helpers_2;
			$contest->status = $request->status;
			$contest->save();

			$contest->course()->attach($request->course_id);

			$contest->save();
			return response()->json(['id' => $contest->id]);
				// $this->createContest($request);
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
