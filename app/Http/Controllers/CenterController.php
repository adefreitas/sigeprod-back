<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\User;
use App\Center;
use App\Professor;
use Illuminate\Http\Request;

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
		$center = Center::where('id', $id)->first();

		return response()->json([

				"center_id" => $center->id,
				"center_name" => $center->name

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
