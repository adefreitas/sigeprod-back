<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\TeacherHelper;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeacherHelperController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// try {
		// 	JWTAuth::parseToken();
		// 	$token = JWTAuth::getToken();
		// } catch (Exception $e){
		// 	return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		// }

		// $tokenOwner = JWTAuth::toUser($token);

		// $user = User::where('email', $tokenOwner->email)->first();
		
		// if($user->is('coursecoordinator') || $user->is('centercoordinator') || $user->is('departmenthead')){
			
			// if($user->is('departmentHead')){
				$helpers = TeacherHelper::where('available', '=', false)
					->get();
				foreach($helpers as $helper){
					$helper->files = $helper->getFiles();
				}
			// }
			
			return response()->json([
				'helpers' => $helpers
			]);
			
		// }
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
