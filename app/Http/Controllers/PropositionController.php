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
			$proposition->mode_option_1 = 'Coordinador(a)';
		}
		elseif ($request->mode1[1]) {
			$proposition->mode_option_1 = 'Teoría';
		}
		elseif ($request->mode1[2]) {
			$proposition->mode_option_1 = 'Práctica';
		}
		elseif ($request->mode1[3]) {
			$proposition->mode_option_1 = 'Laboratorio';
		}

		$proposition->schedule_1_option_1 = $request->schedule1[0];
		$proposition->schedule_2_option_1 = $request->schedule1[1];

		$proposition->course_option_2 = $request->course2;

		if($request->mode2[0]) {
			$proposition->mode_option_2 = 'Coordinador(a)';
		}
		elseif ($request->mode2[1]) {
			$proposition->mode_option_2 = 'Teoría';
		}
		elseif ($request->mode2[2]) {
			$proposition->mode_option_2 = 'Práctica';
		}
		elseif ($request->mode2[3]) {
			$proposition->mode_option_2 = 'Laboratorio';
		}

		$proposition->schedule_1_option_2 = $request->schedule2[0];
		$proposition->schedule_2_option_2 = $request->schedule2[1];

		$proposition->course_option_3 = $request->course3;

		if($request->mode3[0]) {
			$proposition->mode_option_3 = 'Coordinador(a)';
		}
		elseif ($request->mode3[1]) {
			$proposition->mode_option_3 = 'Teoría';
		}
		elseif ($request->mode3[2]) {
			$proposition->mode_option_3 = 'Práctica';
		}
		elseif ($request->mode3[3]) {
			$proposition->mode_option_3 = 'Laboratorio';
		}

		$proposition->schedule_1_option_3 = $request->schedule3[0];
		$proposition->schedule_2_option_3 = $request->schedule3[1];

		$proposition->save();

		return response()->json([
				'msg' => "success",
				'id' => $proposition->id
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
		$proposition = Proposition::where('professor_id', $id)->get()->first();

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
		if($request->mode1[0]) {
			$mode1 = 'Coordinador(a)';
		}
		elseif ($request->mode1[1]) {
			$mode1 = 'Teoría';
		}
		elseif ($request->mode1[2]) {
			$mode1 = 'Práctica';
		}
		elseif ($request->mode1[3]) {
			$mode1 = 'Laboratorio';
		}

		if($request->mode2[0]) {
			$mode2 = 'Coordinador(a)';
		}
		elseif ($request->mode2[1]) {
			$mode2 = 'Teoría';
		}
		elseif ($request->mode2[2]) {
			$mode2 = 'Práctica';
		}
		elseif ($request->mode[3]) {
			$mode2 = 'Laboratorio';
		}

		if($request->mode3[0]) {
			$mode3 = 'Coordinador(a)';
		}
		elseif ($request->mode3[1]) {
			$mode3 = 'Teoría';
		}
		elseif ($request->mode3[2]) {
			$mode3 = 'Práctica';
		}
		elseif ($request->mode3[3]) {
			$mode3 = 'Laboratorio';
		}

		$proposition = Proposition::where('professor_id', $id)->update([
			'course_option_1' => $request->course1,
			'course_option_2' => $request->course2,
			'course_option_3' => $request->course3,
			'mode_option_1' => $mode1,
			'mode_option_2' => $mode2,
			'mode_option_3' => $mode3,
			'schedule_1_option_1' => $request->schedule1[0],
			'schedule_2_option_1' => $request->schedule1[1],
			'schedule_1_option_2' => $request->schedule2[0],
			'schedule_2_option_2' => $request->schedule2[1],
			'schedule_1_option_3' => $request->schedule3[0],
			'schedule_2_option_3' => $request->schedule3[1]
			]);

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
