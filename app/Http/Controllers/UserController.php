<?php namespace App\Http\Controllers;

use \Hash;
use App\Log;
use App\User;
use App\Course;
use App\Center;
use App\Contest;
use App\Notification;
use App\TeacherHelper;
use App\Http\Requests;
use Bican\Roles\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() //función para enviar el perfil a la vista
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

		$userToCreate = new User();

		$userToCreate->name = $request->name;

		$userToCreate->lastname = $request->lastname;

		$userToCreate->email = $request->email;

		$userToCreate->alternate_email = $request->alternate_email;

		$userToCreate->local_phone = $request->local_phone;

		$userToCreate->cell_phone = $request->cell_phone;

		$userToCreate->state = $request->state;

		$userToCreate->municipality = $request->municipality;

		$userToCreate->address = $request->address;

		$userToCreate->password = \Hash::make($request->password);

		$userToCreate->save();

		Log::create([
				'user_id' => $user->id,
				'activity' => 'Creó el usuario con el id: ' . $userToCreate->id
			]);

		\Mail::send('emails.newuser', ['name' => $request->name, 'lastname' => $request->lastname, 'password' => $request->password], function($message) use ($request)
        {
           //remitente
           $message->from('noreply@sigeprod.com', 'SIGEPROD');

           //asunto
           $message->subject('Registro en SIGEPROD (Sistema de Gestión de la Programación Docente)');

           //receptor
           $message->to($request->email, $request->name);

        });

		return response()->json(['success' => true]);
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
	public function update(Request $request, $id) //función para actualizar los datos de perfil
	{
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
				return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		if($user->id == $id){

			Log::create([
				'user_id' => $user->id,
				'activity' => 'Actualizó su perfil de usuario: ' . $id
			]);

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

			$receptor = User::where('email', '=', 'jefe@ciens.ucv.ve')->get()->first();

			$notification = Notification::create([
				'creator_id' => $user->id,
				'receptor_id' => $receptor->id,
				'read' => '0',
				'redirection' => 'admin.log',
				'message'  => 'ha actualizado su perfil de usuario',
				'creator_role' => 'professor'
			]);

			return response()->json(['success' => true]);

		}

		else if($request->role == "admin") {

			$userToUpdate = User::where('id',$id)->get()->first();
			$userToUpdate->name = $request->name;
			$userToUpdate->lastname = $request->lastname;
			$userToUpdate->email = $request->email;
			$userToUpdate->alternate_email = $request->alternate_email;
			$userToUpdate->password = \Hash::make($request->password);
			$userToUpdate->local_phone = $request->local_phone;
			$userToUpdate->cell_phone = $request->cell_phone;
			$userToUpdate->state = $request->state;
			$userToUpdate->municipality = $request->municipality;
			$userToUpdate->address = $request->address;

			$userToUpdate->save();

			$departmentHead = User::join('role_user','role_user.user_id', '=', 'users.id')
				->join('roles','roles.id', '=', 'role_user.role_id')
				->where('roles.slug', 'departmenthead')
				->select('roles.slug as role_slug','users.id as user_id')
				->get();

			foreach($departmentHead as $item){
				if($user->id != $item->user_id){
					$notification = Notification::create([
						'creator_id' => $user->id,
						'receptor_id' => $item->user_id,
						'read' => '0',
						'redirection' => 'admin.log',
						'message'  => 'ha actualizado el perfil de '. $userToUpdate->name. ' '. $userToUpdate->lastname,
						'creator_role' => 'admin'
					]);
				}
			}

			$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userToUpdate->id,
					'read' => '0',
					'redirection' => '',
					'message'  => 'ha actualizado su perfil',
					'creator_role' => 'admin'
				]);

			Log::create([
				'user_id' => $user->id,
				'activity' => 'Actualizó el perfil del usuario con id: ' . $id
			]);

			return response()->json(['success' => true, 'jefes' => $departmentHead]);
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
		$userToDelete = User::where('id', $id)->get()->first();

		if($userToDelete != null) {
			$userToDelete->delete();

			return response()->json(['success' => true,
					'message' => "El usuario ha sido eliminado satisfactoriamente"]);
		}

		else {
			return response()->json(['success' => false,
					'message' => "El usuario especificado no se encuentra registrado en el sistema"]);
		}

	}
	public function showPreapprovedUser(Request $request, $id) //función para enviar a la vista la información de los usuarios preaprobados
	{

		$user = \DB::table('preapproved_users')
			->where('personal_id', '=', $id)
			->where('activated', '=', false)
			->get();

		return response()->json(['user' => $user]);
	}

	public function updatePreapprovedUser(Request $request, $id) //función para actualizar el estado de los usuarios preaprobados
	{
		if($request->discard){


			$preapproved_user = \DB::table('preapproved_users')
			->where('id', '=', $id)
			->first();

			TeacherHelper::where('reserved_for', '=', $preapproved_user->contest_id)
			->where('type', '=', $preapproved_user->type)
			->where('available', '=', true)
			->first()
			->update([
				"reserved" => false,
				"reserved_for" => null
			]);

			\DB::table('preapproved_users')
			->where('id', '=', $id)
			->delete();

			return response()->json(['success' => true]);
		}
		else{
			$user = \DB::table('preapproved_users')
				->where('personal_id', '=', $id)
				->where('id', '!=', $request->id)
				->where('activated', '=', true)
				->first();

			if($user){
				$user = User::find($id);
				$user->name = $request->name;
				$user->lastname = $request->lastname;
				$user->email = $request->email;
				$user->save();
				return response()->json(['success'=>"asdf"]);
			}
			else{
				$checkingEmail = User::where("email", "=", $request->email)->get();
				// echo $checkingEmail;
				if(count($checkingEmail) > 0){
					return response()->json(['error' => 'Ya existe un usuario con ese correo'], 404);
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
		}
	}

	public function createPreapprovedUser(Request $request, $id){ //función para registrar o actualizar a los usuarios preaprobados
		$preapproved = $request;

		if($preapproved == null){
			return response()->json(['error' => 'No existe un usuario preaprobado con esa cédula de identidad'], 404);
		}

		$user = User::find($id);
		$newUser = ($user == null);
		$checkingEmail = User::where("email", "=", $preapproved->email)->get();
		if($newUser && $checkingEmail->count() > 0){
			return response()->json(['error' => 'Ya existe un usuario con ese correo'], 404);
		}
		if($newUser){
			$role = Role::where('slug', '=', 'teacherhelper')->first();
			$user = User::create([
				'name' => $preapproved->name,
				'lastname' => $preapproved->lastname,
				'email' => $preapproved->email,
				'password' => Hash::make($preapproved->password),
				'id' => $preapproved->personal_id
			]);
			$user->attachRole($role->id);
		}

		$user->name = $preapproved->name;
		$user->lastname = $preapproved->lastname;
		$user->email = $preapproved->email;
		$user->password = Hash::make($preapproved->password);

		$user->save();

		$preapprovedUsers = \DB::table('preapproved_users')
			->where('personal_id', '=', $request->personal_id)
			->where('activated', '=', false)
			->get();

		$contestsIds = array();

		foreach ($preapprovedUsers as $preapprovedUser) {
				array_push($contestsIds, $preapprovedUser->contest_id);
		}

		$centersIds = array();
		$coursesIds = array();
		$centersInfo = array();
		$coursesInfo = array();

		$contests = Contest::whereIn("id", $contestsIds)->get();

		foreach ($contests as $contest) {
			if(count($contest->center)){
				array_push($centersIds, $contest->center->first()->id);
				array_push($centersInfo, Center::find($contest->center->first()->id));
			}
			if(count($contest->course)){
				array_push($coursesIds, $contest->course->first()->id);
				array_push($coursesInfo, Course::find($contest->course->first()->id));
			}
		}

		$isHelper = false;

		//Revisando si el alumno ya es preparador

		if(!$newUser){
			$helper = \DB::table('teacher_helpers_users')
				->where('user_id', '=', $user->id)
				->where('active', '=', true)
				->first();

			$isHelper = !($helper == null);

			if($isHelper){
				$foundHelper = TeacherHelper::where('type', '=', $preapproved->type)
					->where('available', '=', true)
					->where('reserved', '=', false)
					->first();

				$helper_id = \DB::table('teacher_helpers_users')
					->where('active', '=', true)
					->where('user_id', '=', $user->id)
					->first()
					->teacher_helper_id;

				$helper = TeacherHelper::find($helper_id);
				// Si la plaza que ya tenia asignada es mayor, se queda con esa
				if($foundHelper->type <= $helper->type){
					TeacherHelper::where('type', '=', $preapproved->type)
						->where('available', '=', true)
						->where('reserved', '=', true)
						->where('reserved_for', '=', $preapproved->contest_id)
						->update([
							'reserved' => false,
							'reserved_for' => null,
							'available' => true
					]);

					foreach ($preapprovedUsers as $preapprovedUser) {

						$contests = Contest::where("id", "=", $preapprovedUser->contest_id)->get();

						TeacherHelper::where('type', '=', $preapprovedUser->type)
							->where('available', '=', true)
							->where('reserved', '=', true)
							->where('reserved_for', '=', $preapprovedUser->contest_id)
							->first()
							->update([
								'reserved' => false,
								'reserved_for' => null,
								'available' => true
						]);

						foreach ($contests as $contest) {
							$helper->user()->attach($user->id, ['contest_id'=> $preapprovedUser->contest_id, 'type' => $preapprovedUser->type]);

							if(count($contest->center)){
								$helper->setCenter($contest->center->first()->id, $preapprovedUser->contest_id);
								$helper->is_center = true;
								$helper->from = '['.$contest->center->first()->id . '] ' . $contest->center->first()->name;
							}
							if(count($contest->course)){
								$helper->setCourse($contest->course->first()->id, $preapprovedUser->contest_id);
								$helper->is_center = false;
								$helper->from = '['.$contest->course->first()->id . '] ' . $contest->course->first()->name;
							}
						}
					}

					$helper->available = false;
					$helper->save();

				}
				//Sino, lo cambio
				else{
					$centers = $helper->centers();
					$courses = $helper->courses();

					$foundHelper->available = false;

					// 4 = subido de 1 a 2
					// 5 = subido de 1 a 3
					// 6 = subido de 2 a 3
					// 7 = bajado de 2 a 1
					// 8 = bajado de 3 a 1
					// 9 = bajado de 3 a 2

					if($helper->type == 1 && $foundHelper->type == 2){
						$foundHelper->status = 4;
						$helper->status = -4;
					}
					if($helper->type == 1 && $foundHelper->type == 3){
						$foundHelper->status = 5;
						$helper->status = -5;
					}
					if($helper->type == 2 && $foundHelper->type == 3){
						$foundHelper->status = 6;
						$helper->status = -6;
					}
					if($helper->type == 2 && $foundHelper->type == 1){
						$foundHelper->status = 7;
						$helper->status = -7;
					}
					if($helper->type == 3 && $foundHelper->type == 1){
						$foundHelper->status = 8;
						$helper->status = -8;
					}
					if($helper->type == 3 && $foundHelper->type == 2){
						$foundHelper->status = 9;
						$helper->status = -9;
					}

					foreach ($preapprovedUsers as $preapprovedUser) {

						$contests = Contest::where("id", "=", $preapprovedUser->contest_id)->get();

						TeacherHelper::where('type', '=', $preapprovedUser->type)
							->where('available', '=', true)
							->where('reserved', '=', true)
							->where('reserved_for', '=', $preapprovedUser->contest_id)
							->first()
							->update([
								'reserved' => false,
								'reserved_for' => null,
								'available' => true
						]);

						foreach ($contests as $contest) {
							$foundHelper->user()->attach($user->id, ['contest_id'=> $preapprovedUser->contest_id, 'type' => $preapprovedUser->type]);

							if(count($contest->center)){
								$foundHelper->setCenter($contest->center->first()->id, $preapprovedUser->contest_id, $preapprovedUser->type);
								$foundHelper->is_center = true;
								$foundHelper->from = '['.$contest->center->first()->id . '] ' . $contest->center->first()->name;
							}
							if(count($contest->course)){
								$foundHelper->setCourse($contest->course->first()->id, $preapprovedUser->contest_id, $preapprovedUser->type);
								$foundHelper->is_center = false;
								$foundHelper->from = '['.$contest->course->first()->id . '] ' . $contest->course->first()->name;
							}
						}
					}

					$foundHelper->save();

					foreach($centers as $center_item){
						$foundHelper->setCenter($center_item->id, $preapproved->contest_id, $center_item->type);
					}
					foreach($courses as $course_item){
						$foundHelper->setCourse($course_item->id, $preapproved->contest_id, $center_item->type);
					}

					$helper->clear();
					$helper->save();

					$foundHelper->save();

					// foreach($centersIds as $centerId){
					// 	$foundHelper->setCenter($centerId, $preapproved->contest_id);
					// }
					// foreach($coursesIds as $courseId){
					// 	$foundHelper->setCourse($courseId, $preapproved->contest_id);
					// }

					$foundHelper->save();

				}
			}
		}

		if($newUser || (!$newUser && !$isHelper)){
			$helper = TeacherHelper::where('type', '=', $preapproved->type)
				->where('available', '=', true)
				->where('reserved', '=', true)
				->first();


			if($helper){

				foreach ($preapprovedUsers as $preapprovedUser) {

					$contests = Contest::where("id", "=", $preapprovedUser->contest_id)->get();

					TeacherHelper::where('type', '=', $preapprovedUser->type)
						->where('available', '=', true)
						->where('reserved', '=', true)
						->where('reserved_for', '=', $preapprovedUser->contest_id)
						->first()
						->update([
							'reserved' => false,
							'reserved_for' => null,
							'available' => true
					]);

					foreach ($contests as $contest) {
						$helper->user()->attach($user->id, ['contest_id'=> $preapprovedUser->contest_id, 'type' => $preapprovedUser->type]);

						if(count($contest->center)){
							$helper->setCenter($contest->center->first()->id, $preapprovedUser->contest_id, $preapprovedUser->type);
							$helper->is_center = true;
							$helper->from = '['.$contest->center->first()->id . '] ' . $contest->center->first()->name;
						}
						if(count($contest->course)){
							$helper->setCourse($contest->course->first()->id, $preapprovedUser->contest_id, $preapprovedUser->type);
							$helper->is_center = false;
							$helper->from = '['.$contest->course->first()->id . '] ' . $contest->course->first()->name;
						}
					}
				}


				// foreach($centersIds as $centerId){
				// 	$helper->setCenter($centerId, $preapproved->contest_id);
				// }
				// foreach($coursesIds as $courseId){
				// 	$helper->setCourse($courseId, $preapproved->contest_id);
				// }

				$helper->available = false;
				$helper->save();
			}
			else{
				return response()->json(['error' => 'No hay plazas disponibles para éste tipo de preparador'], 404);
			}
		}

		\DB::table('preapproved_users')
			->where('personal_id', '=', $request->personal_id)
			->update([
				"activated" => true
			]);


			$secretaries =  User::join("role_user", "role_user.user_id", "=", "users.id")
				->join("roles", "roles.id", "=", "role_user.role_id")
				->where("slug", "=", "directionsecretary")
				->select("users.id")
				->get();

			foreach($secretaries as $secretary){
				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $secretary->id,
					'read' => '0',
					'redirection' => 'secretary.manageTeacherHelpers',
					'message'  => 'se ha registrado como preparador',
					'creator_role' => ''
				]);
			}

			foreach($centersInfo as $centerInfo){
				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $centerInfo->centerCoordinator->first()->user->id,
					'read' => '0',
					'redirection' => 'centerCoordinator.manageTeacherHelpers',
					'message'  => 'se ha registrado como preparador',
					'creator_role' => ''
				]);
			}
			foreach($coursesInfo as $courseInfo){
				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $courseInfo->courseCoordinator->first()->user->id,
					'read' => '0',
					'redirection' => 'courseCoordinator.manageTeacherHelpers',
					'message'  => 'se ha registrado como preparador',
					'creator_role' => ''
				]);
			}


		return response()->json(['success'=>true, 'centers' => $centersInfo, 'courses' => $coursesInfo, 'newUser' => $newUser]);

	}

}
