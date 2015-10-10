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
		$propositions = Proposition::where('active', true)->get();
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

		$professor = Professor::where('user_id', $request->user_id)->first();

		$previousProposition = Proposition::where('professor_id', $professor['id'])->where('active', true)->first();

		if($previousProposition != null) {

			$previousProposition->active = false;
			$previousProposition->save();
		}
		
		$proposition = new Proposition();

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

		$proposition->active = true;

		$proposition->save();

		if($request->viewer == 'centerCoordinator') {

			$center = Professor::where('id', $professor_id)->select('center_id')->first();

			$coordinator = Center::where('id', '=', $center->center_id)
				->join('center_center_coordinator', 'center_center_coordinator.center_id', '=', 'centers.id')
				->select('center_center_coordinator.professor_id as coordinator_id')
				->first();

			$receptorProfessor = Professor::where('id', $coordinator->coordinator_id)->select('user_id')->first();

			$receptorUser = User::where('id', $receptorProfessor->user_id)->first();
			

			if($request->user_id != $receptorUser->id) {

				if($request->edit) {
					$notification = Notification::create([
						'creator_id' => $request->user_id,
						'receptor_id' => $receptorUser->id,
						'read' => '0',
						'redirection' => 'centerCoordinator.semesterPlanning',
						'message'  => 'ha modificado sus propuestas',
						'creator_role' => 'professor'
					]);
				}

				else {
					$notification = Notification::create([
						'creator_id' => $request->user_id,
						'receptor_id' => $receptorUser->id,
						'read' => '0',
						'redirection' => 'centerCoordinator.semesterPlanning',
						'message'  => 'ha enviado sus propuestas',
						'creator_role' => 'professor'
					]);
				}

				Log::create([
					'user_id' => $user->id,
					'activity' => 'Envió sus propuestas para la programación docente'
				]);
			}

			return response()->json(['id' => $proposition->id,
			'coordinator_id' => $coordinator->coordinator_id,
			'receptor' => $receptorUser->name,
			'propuestasPrevias' => $previousProposition]);
		}

		else if($request->viewer == 'departmentHead') {

			Log::create([
				'user_id' => $user->id,
				'activity' => 'Envió sus propuestas para la programación docente'
			]);

			$receptorUser = User::where('email', '=', 'jefe@ciens.ucv.ve')->get()->first();

			$notification = Notification::create([
				'creator_id' => $request->user_id,
				'receptor_id' => $receptorUser->id,
				'read' => '0',
				'redirection' => 'departmentHead.semesterPlanning',
				'message'  => 'ha enviado sus propuestas',
				'creator_role' => 'professor'
			]);

			return response()->json(['id' => $proposition->id,
			'receptor' => $receptorUser->name,
			'propuestasPrevias' => $previousProposition]);
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
		$proposition = Proposition::where('professor_id', $id)->where('active', true)->get()->first();

		if($proposition->status == 2 || $proposition->status == 4) {
			$rejection = Rejection::where('proposition_id', $proposition->id)->where('active', true)->get()->first();

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

		$proposition = Proposition::where('professor_id', $id)->where('active', true)->update([
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

		if($request->status == 1) {  // Entra aqui cuando el profesor edita las propuestas debido a un rechazo previo

			$center = Professor::where('id', $id)->select('center_id')->first();

			$coordinator = Center::where('id', '=', $center->center_id)
				->join('center_center_coordinator', 'center_center_coordinator.center_id', '=', 'centers.id')
				->select('center_center_coordinator.professor_id as coordinator_id')
				->first();

			$receptorProfessor = Professor::where('id', $coordinator->coordinator_id)->select('user_id')->first();

			$receptorUser = User::where('id', $receptorProfessor->user_id)->first();

			$notification = Notification::create([
				'creator_id' => $user->id,
				'receptor_id' => $receptorUser->id,
				'read' => '0',
				'redirection' => 'centerCoordinator.semesterPlanning',
				'message'  => 'ha editado sus propuestas',
				'creator_role' => 'professor'
			]);

			Log::create([
				'user_id' => $user->id,
				'activity' => "Editó sus propuestas "
				]);
			
		}

		else if($request->status == 2) {

			$propositionId = Proposition::where('professor_id', $id)->where('active', true)->select('id')->first();

			$previousRejection = Rejection::where('user_id', $userModified->id)->first();

			if($previousRejection) {

				Rejection::where('user_id', $userModified->id)->update([
								'active' => false
							]);

				Rejection::create([
				'description' => $request->rejectionMessage,
				'active' => true,
				'limit_days' => $request->rejectionDays,
				'user_id' => $userModified->id,
				'proposition_id' => $propositionId->id
			]);

			}

			else {

				Rejection::create([
				'description' => $request->rejectionMessage,
				'active' => true,
<<<<<<< HEAD
=======
				'limit_days' => $request->rejectionDays,
>>>>>>> 852fd647b1c7c846b36ec0cd931aa50974c74c74
				'user_id' => $userModified->id,
				'proposition_id' => $propositionId->id
				]);
			}

			if($user->id != $userModified->id) {
				
				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userModified->id,
					'read' => '0',
					'redirection' => 'professor.semesterPlanning',
					'message'  => 'ha rechazado sus propuestas',
					'creator_role' => 'coordinator'
				]);
				
				Log::create([
				'user_id' => $user->id,
				'activity' => "Rechazó las propuestas del profesor ".$userModified->name." ".$userModified->lastname
				]);
			}

			if($request->rejectionEmailCheck) {
				\Mail::send('emails.rejection', ['name' => $userModified['name'], 'role' => 'Coordinador de Centro', 'rejectionMessage' => $request->rejectionMessage, 'lastname' => $userModified['lastname']], function($message) use ($userModified)
		        {
		           //remitente
		           $message->from('noreply@sigeprod.com', 'SIGEPROD');

		           //asunto
		           $message->subject('Rechazo de preferencias');

		           //receptor
		           $message->to($userModified['email'], $userModified['name']);

		        });
			}
			
		}

		else if($request->status == 3) {

			if($user->id == $userModified->id) {

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userDepartmentHead->id,
					'read' => '0',
					'redirection' => 'departmentHead.semesterPlanning',
					'message'  =>"ha enviado sus propuestas",
					'creator_role' => 'coordinator'
				]);

				Log::create([
				'user_id' => $user->id,
				'activity' => "Envió sus propuestas para la programación docente"
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

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userModified->id,
					'read' => '0',
					'redirection' => 'professor.semesterPlanning',
					'message'  =>"ha aprobado sus propuestas",
					'creator_role' => 'coordinator'
				]);

				Log::create([
				'user_id' => $user->id,
				'activity' => "Aprobó las propuestas del profesor ".$userModified->name." ".$userModified->lastname
				]);
			}
		}

		else if($request->status == 4) {

			//*****Obteniendo el coordinador del centro del profesor que ha enviado las propuestas*****//

			$center = Professor::where('id', $id)->select('center_id')->first();

			if($center->center_id > 0) {
				$coordinator = Center::where('id', '=', $center->center_id)
				->join('center_center_coordinator', 'center_center_coordinator.center_id', '=', 'centers.id')
				->select('center_center_coordinator.professor_id as coordinator_id')
				->first();

				$receptorProfessor = Professor::where('id', $coordinator->coordinator_id)->select('user_id')->first();

				$receptorUser = User::where('id', $receptorProfessor->user_id)->first();
			}
			

			if($center->center_id > 0 && ($receptorUser->id != $userModified->id)) {

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $receptorUser->id,
					'read' => '0',
					'redirection' => 'centerCoordinator.semesterPlanning',
					'message'  => "ha rechazado las propuestas del profesor ".$userModified->name." ".$userModified->lastname,
					'creator_role' => 'departmenthead'
				]);
			}

			//***************************************************************************************//

			$notification = Notification::create([
				'creator_id' => $user->id,
				'receptor_id' => $userModified->id,
				'read' => '0',
				'redirection' => 'professor.semesterPlanning',
				'message'  => 'ha rechazado sus propuestas',
				'creator_role' => 'departmenthead'
			]);

			$propositionId = Proposition::where('professor_id', $id)->where('active', true)->select('id')->first();

			$previousRejection = Rejection::where('user_id', $userModified->id)->first();

			if($previousRejection) {

				Rejection::where('user_id', $userModified->id)->update([
								'active' => false
							]);

				Rejection::create([
				'description' => $request->rejectionMessage,
				'active' => true,
				'limit_days' => $request->rejectionDays,
				'user_id' => $userModified->id,
				'proposition_id' => $propositionId->id
			]);

			}

			else {

				Rejection::create([
				'description' => $request->rejectionMessage,
				'active' => 'true',
				'limit_days' => $request->rejectionDays,
				'user_id' => $userModified->id,
				'proposition_id' => $propositionId->id
				]);
			}

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

			if($request->rejectionEmailCheck) {
				\Mail::send('emails.rejection', ['name' => $userModified['name'], 'role' => 'Jefe de Departamento', 'rejectionMessage' => $request->rejectionMessage, 'lastname' => $userModified['lastname']], function($message) use ($userModified)
		        {
		           //remitente
		           $message->from('noreply@sigeprod.com', 'SIGEPROD');

		           //asunto
		           $message->subject('Rechazo de preferencias');

		           //receptor
		           $message->to($userModified['email'], $userModified['name']);

		        });
			}
			
		}

		else if($request->status == 5) {

			//*****Obteniendo el coordinador del centro del profesor que ha enviado las propuestas*****//

			$center = Professor::where('id', $id)->select('center_id')->first();

			if($center->center_id > 0) {
				$coordinator = Center::where('id', '=', $center->center_id)
					->join('center_center_coordinator', 'center_center_coordinator.center_id', '=', 'centers.id')
					->select('center_center_coordinator.professor_id as coordinator_id')
					->first();

				$receptorProfessor = Professor::where('id', $coordinator->coordinator_id)->select('user_id')->first();

				$receptorUser = User::where('id', $receptorProfessor->user_id)->first();
			}

			//***************************************************************************************//
			
			if($center->center_id > 0 && $receptorUser->id != $userModified->id) {
				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $receptorUser->id,
					'read' => '0',
					'redirection' => 'centerCoordinator.semesterPlanning',
					'message'  => "ha aprobado las propuestas del profesor ".$userModified->name." ".$userModified->lastname,
					'creator_role' => 'departmenthead'
				]);

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userModified->id,
					'read' => '0',
					'redirection' => 'professor.semesterPlanning',
					'message'  =>"ha aprobado sus propuestas ",
					'creator_role' => 'departmenthead'
				]);
			}

			else {
				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userModified->id,
					'read' => '0',
					'redirection' => 'professor.semesterPlanning',
					'message'  =>"ha aprobado sus propuestas ",
					'creator_role' => 'departmenthead'
				]);
			}
			

			if($user->id == $userModified->id) {

				Log::create([
				'user_id' => $user->id,
				'activity' => "Aprobó sus propuestas"
				]);
			}

			else {

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
