<?php namespace App\Http\Controllers;

use App\Log;
use App\User;
use App\Center;
use App\Course;
use App\Professor;
use App\Notification;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Bican\Roles\Models\Role;

use Illuminate\Http\Request;

class RoleController extends Controller {

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

		$roles = Role::select('id','name','slug','description')->get();

		return response()->json([
	            'roles' => $roles,
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

	public function addRoleToUser(Request $request, $id)
	{
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
			return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		$userToAddRole = User::where('id', $id)->first();

		$roleToAttach = Role::where('id', $request->role['id'])->first();

		if($request->role['slug'] == "professor") {

			$check = $userToAddRole -> getRoles()->contains('slug', $request->role['slug']);

			if(!$check)
			 {

		 		$professor = new Professor;
				$professor->dedication = $request->dedication['name'];
				$professor->center_id = $request->center['id'];
				$professor->status = 'Activo';
				$professor->proposition_sent = false;
		        // $professor =   Professor::create([
		        //     'dedication' => $request->dedication['name'],
		        //     'center_id' => $request->center['id'],
		        //     'status' => 'Activo',
		        //     'proposition_sent' => false
		        // ]);

		        $professor->user()->associate($userToAddRole);

	        	$professor->save();

	        	$userToAddRole -> attachRole($roleToAttach);

				$userToAddRole->save();

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userToAddRole->id,
					'read' => '0',
					'redirection' => 'professor.semesterPlanning',
					'message'  => 'le ha asignado el rol de '.$request->role['description'],
					'creator_role' => 'departmenthead'
				]);

				Log::create([
					'user_id' => $user->id,
					'activity' => 'Le asignó el rol de '.$request->role['description'].' al usuario ' . $userToAddRole->name . ' ' . $userToAddRole->lastname
				]);

	        	return response()->json([
	        		'success' => true,
					'message' => 'El rol ha sido asignado satisfactoriamente'
				]);
			}

			else{
				return response()->json([
	        		'success' => false,
					'message' => 'El usuario ya posee el rol especificado'
				]);
			}


		}

		else if($request->role['slug'] == "centercoordinator") {

			$check = $userToAddRole -> getRoles()->contains('slug', $request->role['slug']);

			if(!$check)
			{
				$isThereCoordinator = Center::find($request->center['id'])->centerCoordinator()->get()->first();

				if($isThereCoordinator == null) {

					$professorToAddRole = Professor::where('user_id', $userToAddRole->id)->get()->first();

					if($professorToAddRole != null) {
						if($professorToAddRole->center_id == $request->center['id']) {
							$userToAddRole -> attachRole($roleToAttach);

							Center::find($request->center['id'])
					          ->centerCoordinator()->attach(
					            User::where('id', $id)->firstOrFail()->professor
					        );

					        $notification = Notification::create([
								'creator_id' => $user->id,
								'receptor_id' => $userToAddRole->id,
								'read' => '0',
								'redirection' => 'centerCoordinator.semesterPlanning',
								'message'  => 'le ha asignado el rol de '.$request->role['description'],
								'creator_role' => 'departmenthead'
							]);

							Log::create([
								'user_id' => $user->id,
								'activity' => 'Le asignó el rol de '.$request->role['description'].' al profesor ' . $userToAddRole->name . ' ' . $userToAddRole->lastname
							]);

					        return response()->json([
					        	'success' => true,
								'message' => 'El coordinador ha sido asignado al centro satisfactoriamente'
							]);
						}

						else {
							return response()->json([
								'success' => false,
								'message' => 'El usuario no pertenece a este centro',
								'center_coordinator' => $isThereCoordinator
							]);
						}
					}

					else {
						return response()->json([
								'success' => false,
								'message' => 'El usuario no posee el rol de profesor, por lo tanto no puede ser coordinador de centro',
								'center_coordinator' => $isThereCoordinator
							]);
					}


				}

				else {
					return response()->json([
						'success' => false,
						'message' => 'El centro ya posee coordinador',
						'center_coordinator' => $isThereCoordinator
					]);
				}
			}

			else {
				return response()->json([
	        		'success' => false,
					'message' => 'El usuario ya posee el rol especificado'
				]);
			}




		}

		else if($request->role['slug']== "coursecoordinator") {

			$check = $userToAddRole -> getRoles()->contains('slug', $request->role['slug']);

			$isThereCoordinator = Course::find($request->course['id'])->courseCoordinator()->get()->first();

			if($isThereCoordinator == null) {

				$professorToAddRole = Professor::where('user_id', $userToAddRole->id)->get()->first();

				if($professorToAddRole != null) {

					$userToAddRole -> attachRole($roleToAttach);

					$course = Course::find($request->course['id']);


			        $course->courseCoordinator()->attach(
			            User::where('id', $id)->firstOrFail()->professor
			        );

			        $course->save();

			        $notification = Notification::create([
						'creator_id' => $user->id,
						'receptor_id' => $userToAddRole->id,
						'read' => '0',
						'redirection' => 'courseCoordinator.helperContest',
						'message'  => 'le ha asignado el rol de '.$request->role['description'],
						'creator_role' => 'departmenthead'
					]);

					Log::create([
						'user_id' => $user->id,
						'activity' => 'Le asignó el rol de '.$request->role['description'].' al profesor ' . $userToAddRole->name . ' ' . $userToAddRole->lastname
					]);

			        return response()->json([
			        	'success' => true,
						'message' => 'El coordinador ha sido asignado a la materia satisfactoriamente'
					]);
			    }
			    else {
					return response()->json([
							'success' => false,
							'message' => 'El usuario no posee el rol de profesor, por lo tanto no puede ser coordinador de materia',
							'center_coordinator' => $isThereCoordinator
						]);
				}
			}

			else {
				return response()->json([
					'success' => false,
					'message' => 'La materia ya posee coordinador'
				]);
			}
		}

		else {
			$check = $userToAddRole -> getRoles()->contains('slug', $request->role['slug']);

			if(!$check)
			{
				$userToAddRole -> attachRole($roleToAttach);

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userToAddRole->id,
					'read' => '0',
					'redirection' => '',
					'message'  => 'le ha asignado el rol de '.$request->role['description'],
					'creator_role' => 'departmenthead'
				]);

				$professorToAddRole = Professor::where('user_id', $userToAddRole->id)->get()->first();

				if($professorToAddRole != null) {
					Log::create([
						'user_id' => $user->id,
						'activity' => 'Le asignó el rol de '.$request->role['description'].' al profesor ' . $userToAddRole->name . ' ' . $userToAddRole->lastname
					]);
				}

				else {
					Log::create([
						'user_id' => $user->id,
						'activity' => 'Le asignó el rol de '.$request->role['description'].' al usuario ' . $userToAddRole->name . ' ' . $userToAddRole->lastname
					]);
				}

				return response()->json([
					'success' => true,
					'message' => 'El rol ha sido asignado satisfactoriamente'
				]);

			}

			else {

				return response()->json([
					'success' => false,
					'message' => 'El usuario ya posee el rol especificado'
				]);
			}
		}


	}

	public function deleteRoleToUser(Request $request, $id)
	{
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
			return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		$userToDeleteRole = User::where('id', $id)->first();

		$roleToDetach = Role::where('id', $request->role['id'])->first();

		if($request->role['slug']== "professor") {

		}

		else if($request->role['slug']== "centercoordinator") {
			$check = $userToDeleteRole -> getRoles()->contains('slug', $request->role['slug']);

			if($check) {

				$professorToDeleteRole = Professor::where('user_id', $userToDeleteRole->id)->get()->first();

				Center::find($professorToDeleteRole->center_id)
		          ->centerCoordinator()->detach(
		            User::where('id', $id)->firstOrFail()->professor
		        );

				$userToDeleteRole -> detachRole($roleToDetach);

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userToDeleteRole->id,
					'read' => '0',
					'redirection' => '',
					'message'  => 'le ha eliminado el rol de '.$request->role['description'],
					'creator_role' => 'departmenthead'
				]);

				Log::create([
					'user_id' => $user->id,
					'activity' => 'Le eliminó el rol de '.$request->role['description'].' al profesor ' . $userToDeleteRole->name . ' ' . $userToDeleteRole->lastname
				]);

				return response()->json([
					'success' => true,
					'message' => 'El rol ha sido eliminado satisfactoriamente'
				]);
			}

			else {
				return response()->json([
					'success' => false,
					'message' => 'El usuario no posee el rol especificado'
				]);
			}
		}

		else if($request->role['slug']== "coursecoordinator") {
			$check = $userToDeleteRole -> getRoles()->contains('slug', $request->role['slug']);

			if($check) {

				$courseCoordinator = Course::find($request->course['id'])->courseCoordinator()->get()->first();

				$professorToDeleteRole = Professor::where('user_id', $userToDeleteRole->id)->get()->first();

				if($courseCoordinator != null && $courseCoordinator->id == $professorToDeleteRole->id) {

					Course::find($request->course['id'])
			          ->coursecoordinator()->detach(
			            User::where('id', $id)->firstOrFail()->professor
			        );

					$userToDeleteRole -> detachRole($roleToDetach);

					$notification = Notification::create([
						'creator_id' => $user->id,
						'receptor_id' => $userToDeleteRole->id,
						'read' => '0',
						'redirection' => '',
						'message'  => 'le ha eliminado el rol de '.$request->role['description'],
						'creator_role' => 'departmenthead'
					]);

					Log::create([
						'user_id' => $user->id,
						'activity' => 'Le eliminó el rol de '.$request->role['description'].' al profesor ' . $userToDeleteRole->name . ' ' . $userToDeleteRole->lastname
					]);

					return response()->json([
						'success' => true,
						'message' => 'El rol ha sido eliminado satisfactoriamente',
						'coordinadoractual' => $courseCoordinator
					]);

				}

				else {
					return response()->json([
						'success' => false,
						'message' => 'El usuario seleccionado no coordina la materia especificada'
					]);
				}
			}

			else {
				return response()->json([
					'success' => false,
					'message' => 'El usuario no posee el rol especificado'
				]);
			}
		}

		else {

			$check = $userToDeleteRole -> getRoles()->contains('slug', $request->role['slug']);

			if($check) {

				$userToDeleteRole -> detachRole($roleToDetach);

				$notification = Notification::create([
					'creator_id' => $user->id,
					'receptor_id' => $userToDeleteRole->id,
					'read' => '0',
					'redirection' => '',
					'message'  => 'le ha eliminado el rol de '.$request->role['description'],
					'creator_role' => 'departmenthead'
				]);

				$professorToDeleteRole = Professor::where('user_id', $userToDeleteRole->id)->get()->first();

				if($professorToDeleteRole != null) {
					Log::create([
						'user_id' => $user->id,
						'activity' => 'Le eliminó el rol de '.$request->role['description'].' al profesor ' . $userToDeleteRole->name . ' ' . $userToDeleteRole->lastname
					]);
				}

				else {
					Log::create([
						'user_id' => $user->id,
						'activity' => 'Le eliminó el rol de '.$request->role['description'].' al usuario ' . $userToDeleteRole->name . ' ' . $userToDeleteRole->lastname
					]);
				}


				return response()->json([
					'success' => true,
					'message' => 'El rol ha sido eliminado satisfactoriamente'
				]);
			}

			else {
				return response()->json([
					'success' => false,
					'message' => 'El usuario no posee el rol especificado'
				]);
			}

		}
	}

}
