<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Proposition;
use App\Professor;
use Illuminate\Http\Request;

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
		$proposition = new Proposition();

		$professor = Professor::where('user_id', $request->user_id)->first();

		$proposition->professor_id = $professor['id'];

		$proposition->course_option_1 = $request->course1;

		if($request->mode1[0]) {
			$proposition->mode_option_1 = 'coordination';
		}
		elseif ($request->mode1[1]) {
			$proposition->mode_option_1 = 'theory';
		}
		elseif ($request->mode1[2]) {
			$proposition->mode_option_1 = 'practice';
		}
		elseif ($request->mode1[3]) {
			$proposition->mode_option_1 = 'laboratory';
		}

		$proposition->course_option_2 = $request->course2;

		if($request->mode2[0]) {
			$proposition->mode_option_2 = 'coordination';
		}
		elseif ($request->mode2[1]) {
			$proposition->mode_option_2 = 'theory';
		}
		elseif ($request->mode2[2]) {
			$proposition->mode_option_2 = 'practice';
		}
		elseif ($request->mode2[3]) {
			$proposition->mode_option_2 = 'laboratory';
		}

		$proposition->course_option_3 = $request->course3;

		if($request->mode3[0]) {
			$proposition->mode_option_3 = 'coordination';
		}
		elseif ($request->mode3[1]) {
			$proposition->mode_option_3 = 'theory';
		}
		elseif ($request->mode3[2]) {
			$proposition->mode_option_3 = 'practice';
		}
		elseif ($request->mode3[3]) {
			$proposition->mode_option_3 = 'laboratory';
		}

		$proposition->save();

		return response()->json([
				"msg" => "success",
				"id" => $proposition->id
			]);
		
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
