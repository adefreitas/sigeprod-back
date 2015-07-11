<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Professor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfessorController extends Controller {

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

		//var_dump($user->Professor->id);

		$professor = Professor::where('id', $user->Professor->id)->first();

		//var_dump($professor->id);

		return response()->json([
				"msg" => "success",
				"sent" => $professor->proposition_sent
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
		$professor = Professor::where('user_id', $id)->first();

		return response()->json([

				"msg" => "success",
				"sent" => $professor->id

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
	public function update($id)
	{
		$professor = Professor::where('user_id', $id)->update(['proposition_sent' => true]);

		return response()->json([

				"msg" => "success"

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
