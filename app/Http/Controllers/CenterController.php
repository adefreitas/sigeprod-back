<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\User;
use App\Log;
use App\Center;
use App\Professor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Nitmedia\Wkhtml2pdf\Facades\Wkhtml2pdf;

class CenterController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return response()->json([
			'centers' => Center::get(),
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

		if($user->is('admin') || $user->is('departmenthead')){

			Log::create([
				'user_id' => $user->id,
				'activity' => 'Creó una nueva materia'
			]);

			$center = new center();
			$center->id = $request->id;
			$center->name = $request->name;
			$center->active = $request->active;

			$center->save();

			PDF::url('http://www.google.com');
			PDF::setOutputMode('F'); // force to file

			return response()->json(['id' => $center->id]);
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
	public function show($id)
	{
		$center = Center::where('id', $id)->first();

		return response()->json([
			"center_id" => $center->id,
			"center_name" => $center->name,
			"center_active" => $center->active
		]);
	}

	public function professors($id)
	{
		$professors = Professor::where('center_id', $id)
						->where('proposition_sent', true)
						->join('users', 'users.id', '=', 'professors.user_id')
						->select('users.name', 'users.lastname','users.email','professors.id')
						->get();

		return response()->json([
			"msg" => "success",
			"professors" => $professors
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

		$center = Center::find($request->id);

		if($center->id == $id){
			Log::create([
				'user_id' => $user->id,
				'activity' => 'Actualizó el centro: ' .'['. $request->id .'] '. $request->name
			]);

			$center->name = $request->name;
			$center->active = $request->active;

			$center->save();

			return response()->json(['success' => true]);

		}
		return response()->json(['success' => false,'error'=>'No se encontró el centro con el ID especificado']);
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
