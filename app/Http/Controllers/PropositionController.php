<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Proposition;
use App\Professor;
use App\Center;
use App\Notification;
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

		$professor_id = $proposition->professor_id;

		$proposition->course_option_1 = $request->course1;

		$proposition->mode_option_1 = json_encode($request->mode1);

		$proposition->schedule_1_option_1 = $request->schedule1[0];
		$proposition->schedule_2_option_1 = $request->schedule1[1];

		$proposition->course_option_2 = $request->course2;

		$proposition->mode_option_2 = json_encode($request->mode2);

		$proposition->schedule_1_option_2 = $request->schedule2[0];
		$proposition->schedule_2_option_2 = $request->schedule2[1];

		$proposition->course_option_3 = $request->course3;

		$proposition->mode_option_3 = json_encode($request->mode3);

		$proposition->schedule_1_option_3 = $request->schedule3[0];
		$proposition->schedule_2_option_3 = $request->schedule3[1];

		$proposition->save();

		/*$center = Professor::where('id', $professor_id)->select('center_id');

		$coordinator_id = Center::where('id', $center)->select('center_center_coordinator.professor_id');

		$receptor = User::where('professor_id', $coordinator_id)->get()->first();

		$notification = Notification::create([
				'creator_id' => $request->user_id,
				'receptor_id' => $receptor->id,
				'read' => '0',
				'redirection' => 'centerCoordinator.semesterPlanning',
				'message'  => 'ha enviado sus preferencias',
				'creator_role' => 'professor'
			]);*/

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

		$proposition->mode_option_1 = json_decode($proposition->mode_option_1);
		$proposition->mode_option_2 = json_decode($proposition->mode_option_2);
		$proposition->mode_option_3 = json_decode($proposition->mode_option_3);

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
