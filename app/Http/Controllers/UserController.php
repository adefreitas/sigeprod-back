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

		Log::create([
			'user_id' => $user->id,
			'activity' => 'Actualizó su perfil de usuario: ' . $id
		]);


		if($user->id == $id){

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
				'redirection' => 'user.dashboard',
				'message'  => 'ha actualizado su perfil de usuario',
				'creator_role' => 'professor'
			]);

			return response()->json(['success' => true]);

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
	public function showPreapprovedUser(Request $request, $id) //función para enviar a la vista la información de los usuarios preaprobados
	{

		$user = \DB::table('preapproved_users')
			->where('personal_id', '=', $id)
			->where('activated', '=', false)
			->first();

		return response()->json(['user' => $user]);
	}

	public function updatePreapprovedUser(Request $request, $id) //función para actualizar el estado de los usuarios preaprobados
	{
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
			return response()->json(['success'=>true]);
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

	public function createPreapprovedUser(Request $request, $id){ //función para registrar o actualizar a los usuarios preaprobados
		$exists = \DB::table('preapproved_users')
			->where('personal_id', '=', $request->personal_id)
			->where('activated', '=', false)
			->orderBy('created_at', 'desc')
			->first();

		$preapproved = $request;

		if($preapproved == null){
			return response()->json(['error' => 'No existe un usuario preaprobado con esa cédula de identidad'], 404);
		}

		$user = User::find($id);
		$newUser = $user == null;

		if($newUser){
			$user = User::create([
				'name' => $preapproved->name,
				'lastname' => $preapproved->lastname,
				'email' => $preapproved->email,
				'password' => Hash::make($preapproved->password),
				'id' => $preapproved->personal_id
			]);
		}

		$user->name = $preapproved->name;
		$user->lastname = $preapproved->lastname;
		$user->email = $preapproved->email;
		$user->password = Hash::make($preapproved->password);



		// $preapproved->activated = true;

		// $preapproved->save();

		$user->save();

		$contest = Contest::find($preapproved->contest_id);

		$center = $contest->center;
		$course = $contest->course;

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
					->where('reserved', '=', true)
					->first();

				$helper_id = \DB::table('teacher_helpers_users')
					->where('active', '=', true)
					->where('user_id', '=', $user->id)
					->first()
					->teacher_helper_id;

				$helper = TeacherHelper::find($helper_id);
				// Si la plaza que ya tenia asignada es mayor, se queda con esa
				if($foundHelper->type < $helper->type){
					TeacherHelper::where('type', '=', $preapproved->type)
						->where('available', '=', true)
						->where('reserved', '=', true)
						->where('reserved_for', '=', $contest->contest_id)
						->update([
							'reserved' => false,
							'reserved_for' => null,
							'available' => true
					]);
					$helper->user()->attach($user->id, ['contest_id'=> $preapproved->contest_id, 'type' => $preapproved->type]);

					if(count($center) > 0){
						$center = $center[0]->id;
						$helper->setCenter($center, $preapproved->contest_id);
					}
					if(count($course) > 0){
						$course = $course[0]->id;
						$helper->setCourse($course, $preapproved->contest_id);
					}

					$helper->available = false;
					$helper->save();

				}
				//Sino, lo cambio
				else{
					$centers = $helper->centers();
					$courses = $helper->courses();

					$foundHelper->available = false;

					$foundHelper->user()->attach($id, ['contest_id'=> $preapproved->contest_id, 'type' => $preapproved->type]);
					$foundHelper->save();

					foreach($centers as $center_item){
						$foundHelper->setCenter($center_item->id, $preapproved->contest_id);
					}
					foreach($courses as $course_item){
						$foundHelper->setCourse($course_item->id, $preapproved->contest_id);
					}

					$helper->clear();
					$helper->save();

					$foundHelper->save();

					if(count($center) > 0){
						foreach($center as $ce){
							$foundHelper->setCenter($ce->id, $ce->contest_id);
						}
					}

					if(count($course) > 0){
						foreach($course as $co){
							$foundHelper->setCourse($co->id, $co->contest_id);
						}
					}

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
				$helper->user()->attach($user->id, ['contest_id'=> $preapproved->contest_id, 'type' => $preapproved->type]);

				if(count($center) > 0){
					$center = $center[0]->id;
					$helper->setCenter($center, $preapproved->contest_id);
				}
				if(count($course) > 0){
					$course = $course[0]->id;
					$helper->setCourse($course, $preapproved->contest_id);
				}

				$helper->available = false;
				$helper->save();
			}
			else{
				return response()->json(['error' => 'No hay plazas disponibles para éste tipo de preparador'], 404);
			}
		}
		$thu = \DB::table('preapproved_users')
			->where('personal_id', '=', $request->personal_id)
			->where('activated', '=', false)
			->select('id')
			->get();

		\DB::table('preapproved_users')
			->where('id', '=', $thu[0]->id)
			->update([
				"activated" => true
			]);
		return response()->json(['success'=>true]);

	}

}
