<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Log;
use App\User;
use App\Center;
use App\Rejection;
use App\Professor;
use App\Proposition;
use App\Notification;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PropositionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$propositions = Proposition::get();
		for($i = 0; $i < count($propositions); ++$i) {
			$propositions[$i]->course_option_1 = json_decode($propositions[$i]->course_option_1);
    		$propositions[$i]->mode_option_1 = json_decode($propositions[$i]->mode_option_1);
    		$propositions[$i]->schedule_option_1 = json_decode($propositions[$i]->schedule_option_1);
    		$propositions[$i]->course_option_2 = json_decode($propositions[$i]->course_option_2);
    		$propositions[$i]->mode_option_2 = json_decode($propositions[$i]->mode_option_2);
    		$propositions[$i]->schedule_option_2 = json_decode($propositions[$i]->schedule_option_2);
    		$propositions[$i]->course_option_3 = json_decode($propositions[$i]->course_option_3);
			$propositions[$i]->mode_option_3 = json_decode($propositions[$i]->mode_option_3);
			$propositions[$i]->schedule_option_3 = json_decode($propositions[$i]->schedule_option_3);
		}

		return response()->json([
			'propositions' => $propositions,
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

		$proposition = new Proposition();

		$professor = Professor::where('user_id', $request->user_id)->first();

		$proposition->professor_id = $professor['id'];

		$professor_id = $proposition->professor_id;

		$proposition->course_option_1 = json_encode($request->course1);

		$proposition->mode_option_1 = json_encode($request->mode1);

		$proposition->schedule_option_1 = json_encode($request->schedule1);

		$proposition->course_option_2 = json_encode($request->course2);

		$proposition->mode_option_2 = json_encode($request->mode2);

		$proposition->schedule_option_2 = json_encode($request->schedule2);

		$proposition->course_option_3 = json_encode($request->course3);

		$proposition->mode_option_3 = json_encode($request->mode3);

		$proposition->schedule_option_3 = json_encode($request->schedule3);

		$proposition->viewer = $request->viewer;

		$proposition->status = $request->status;

		$proposition->save();

		Log::create([
			'user_id' => $user->id,
			'activity' => 'Envió sus propuestas para la planificación docente'
		]);

		$center = Professor::where('id', $professor_id)->select('center_id')->first();

		$coordinator = Center::where('id', '=', $center->center_id)
			->join('center_center_coordinator', 'center_center_coordinator.center_id', '=', 'centers.id')
			->select('center_center_coordinator.professor_id as coordinator_id')
			->first();

		$receptorProfessor = Professor::where('id', $coordinator->coordinator_id)->select('user_id')->first();

		$receptorUser = User::where('id', $receptorProfessor->user_id)->first();

		if($request->viewer == 'centerCoordinator'){

			$notification = Notification::create([
				'creator_id' => $request->user_id,
				'receptor_id' => $receptorUser->id,
				'read' => '0',
				'redirection' => 'centerCoordinator.semesterPlanning',
				'message'  => 'ha enviado sus propuestas',
				'creator_role' => 'professor'
			]);
		}

		else if($request->viewer == 'departmentHead')

		$notification = Notification::create([
				'creator_id' => $request->user_id,
				'receptor_id' => $receptorUser->id,
				'read' => '0',
				'redirection' => 'departmentHead.semesterPlanning',
				'message'  => 'ha aprobado sus propuestas',
				'creator_role' => 'centerCoordinator'
			]);

		return response()->json(['id' => $proposition->id,
			'coordinator_id' => $coordinator->coordinator_id,
			'receptor' => $receptorUser->name]);
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$proposition = Proposition::where('professor_id', $id)->get()->first();

		if($proposition->status == 2) {
			$rejection = Rejection::where('proposition_id', $proposition->id)->get()->first();

			$proposition->rejection = $rejection;
		}
		

		$proposition->course_option_1 = json_decode($proposition->course_option_1);
		$proposition->mode_option_1 = json_decode($proposition->mode_option_1);
		$proposition->schedule_option_1 = json_decode($proposition->schedule_option_1);
		$proposition->course_option_2 = json_decode($proposition->course_option_2);
		$proposition->mode_option_2 = json_decode($proposition->mode_option_2);
		$proposition->schedule_option_2 = json_decode($proposition->schedule_option_2);
		$proposition->course_option_3 = json_decode($proposition->course_option_3);
		$proposition->mode_option_3 = json_decode($proposition->mode_option_3);
		$proposition->schedule_option_3 = json_decode($proposition->schedule_option_3);

		return response()->json([

				"msg" => "success",
				"proposition" => $proposition

			]);
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

		$professor = Professor::where('id', $id)->select('user_id')->first(); //Profesor cuyas preferencias están siendo modificadas

		$userModified = User::where('id', $professor->user_id)->first();

		$userDepartmentHead = User::where('email', 'jefe@ciens.ucv.ve')->first();

		$proposition = Proposition::where('professor_id', $id)->update([
			'course_option_1' => json_encode($request->course1),
			'course_option_2' => json_encode($request->course2),
			'course_option_3' => json_encode($request->course3),
			'mode_option_1' => json_encode($request->mode1),
			'mode_option_2' => json_encode($request->mode2),
			'mode_option_3' => json_encode($request->mode3),
			'schedule_option_1' => json_encode($request->schedule1),
			'schedule_option_2' => json_encode($request->schedule2),
			'schedule_option_3' => json_encode($request->schedule3),
			'viewer' => $request->viewer,
			'status' => $request->status
			]);

		if($request->status == 2) {

			$notification = Notification::create([
				'creator_id' => $user->id,
				'receptor_id' => $userModified->id,
				'read' => '0',
				'redirection' => 'professor.semesterPlanning',
				'message'  => 'ha rechazado sus propuestas',
				'creator_role' => 'coordinator'
			]);

			$propositionId = Proposition::where('professor_id', $id)->select('id')->first();

			$rejection = Rejection::create([
				'description' => $request->rejectionMessage,
				'user_id' => $userModified->id,
				'proposition_id' => $propositionId->id
			]);

			if($user->id == $userModified->id) {
				Log::create([
				'user_id' => $user->id,
				'activity' => "Rechazó sus propuestas "
				]);
			}

			else {
				Log::create([
				'user_id' => $user->id,
				'activity' => "Rechazó las propuestas del profesor ".$userModified->name." ".$userModified->lastname
				]);
			}
			
		}

		else if($request->status == 4) {
			

			if($user->id == $userModified->id) {

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userDepartmentHead->id,
					'read' => '0',
					'redirection' => 'departmentHead.semesterPlanning',
					'message'  =>"ha enviado sus propuestas ",
					'creator_role' => 'coordinator'
				]);

				Log::create([
				'user_id' => $user->id,
				'activity' => "Envió sus propuestas"
				]);
			}

			else {

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userDepartmentHead->id,
					'read' => '0',
					'redirection' => 'departmentHead.semesterPlanning',
					'message'  =>"ha aprobado las propuestas del profesor ".$userModified->name." ".$userModified->lastname,
					'creator_role' => 'coordinator'
				]);

				Log::create([
				'user_id' => $user->id,
				'activity' => "Aprobó las propuestas del profesor ".$userModified->name." ".$userModified->lastname
				]);
			}
		}

		

		return response()->json([
				"professor" => $professor,
				"userModified" => $userModified
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
