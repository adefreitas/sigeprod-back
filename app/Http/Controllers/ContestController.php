<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
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
			return response()->json([
                'message' => 'Coordinador de materia'
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
        return response()->json([
            'message' => 'Jefe de departamento'
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
	public function store($request)
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
				$this->createContest($request);
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
