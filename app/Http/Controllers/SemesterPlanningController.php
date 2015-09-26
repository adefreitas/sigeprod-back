<?php namespace App\Http\Controllers;

use App\SemesterPlanning;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SemesterPlanningController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$semesterPlanning = SemesterPlanning::where('active', true)->get()->first();

		if($semesterPlanning != null) {
			$semesterPlanning->content = json_decode($semesterPlanning->content);
			return response()->json([
				'semesterPlanning' => $semesterPlanning,
			]);
		}

		else {
			return response()->json([
				'semesterPlanning' => 0,
			]);
		}
		
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
	
		$semesterPlanning = new SemesterPlanning();

		$semesterPlanning->content = json_encode($request->actual);

		$semesterPlanning->active = true;

		$semesterPlanning->save();

		return response()->json([
			'message' => 'success',
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
