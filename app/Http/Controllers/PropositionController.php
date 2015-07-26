<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Log;
use App\User;
use App\Center;
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

        return response()->json([
			"propositions" => $propositions->toArray()
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

		$proposition->course_option_1 = $request->course1;

		$proposition->mode_option_1 = json_encode($request->modeChecked1);

		$proposition->schedule_1_option_1 = $request->schedule1[0];
		$proposition->schedule_2_option_1 = $request->schedule1[1];

		$proposition->course_option_2 = $request->course2;

		$proposition->mode_option_2 = json_encode($request->modeChecked2);

		$proposition->schedule_1_option_2 = $request->schedule2[0];
		$proposition->schedule_2_option_2 = $request->schedule2[1];

		$proposition->course_option_3 = $request->course3;

		$proposition->mode_option_3 = json_encode($request->modeChecked3);

		$proposition->schedule_1_option_3 = $request->schedule3[0];
		$proposition->schedule_2_option_3 = $request->schedule3[1];

		$proposition->save();

		Log::create([
			'user_id' => $user->id,
			'activity' => 'Envió sus preferencias para la planificación docente'
		]);

		$center = Professor::where('id', $professor_id)->select('center_id')->first();

		$coordinator = Center::where('id', '=', $center->center_id)
			->join('center_center_coordinator', 'center_center_coordinator.center_id', '=', 'centers.id')
			->select('center_center_coordinator.professor_id as coordinator_id')
			->first();

		$receptorProfessor = Professor::where('id', $coordinator->coordinator_id)->select('user_id')->first();

		$receptorUser = User::where('id', $receptorProfessor->user_id)->first();


		$notification = Notification::create([
				'creator_id' => $request->user_id,
				'receptor_id' => $receptorUser->id,
				'read' => '0',
				'redirection' => 'centerCoordinator.semesterPlanning',
				'message'  => 'ha enviado sus preferencias',
				'creator_role' => 'professor'
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

		$proposition->mode_option_1 = json_decode($proposition->mode_option_1);
		$proposition->mode_option_2 = json_decode($proposition->mode_option_2);
		$proposition->mode_option_3 = json_decode($proposition->mode_option_3);

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

		Log::create([
			'user_id' => $user->id,
			'activity' => "Modificó las preferencias del profesor ".$userModified->name." ".$userModified->lastname
		]);

		$proposition = Proposition::where('professor_id', $id)->update([
			'course_option_1' => $request->course1,
			'course_option_2' => $request->course2,
			'course_option_3' => $request->course3,
			'mode_option_1' => json_encode($request->modeChecked1),
			'mode_option_2' => json_encode($request->modeChecked2),
			'mode_option_3' => json_encode($request->modeChecked3),
			'schedule_1_option_1' => $request->schedule1[0],
			'schedule_2_option_1' => $request->schedule1[1],
			'schedule_1_option_2' => $request->schedule2[0],
			'schedule_2_option_2' => $request->schedule2[1],
			'schedule_1_option_3' => $request->schedule3[0],
			'schedule_2_option_3' => $request->schedule3[1]
			]);

		return response()->json([
				"msg" => "success",
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
