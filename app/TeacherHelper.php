<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TeacherHelper extends Model {

	protected $fillable = ['available', 'type', 'id'];

	public function user(){
		return $this->belongsToMany('App\User', 'teacher_helpers_users')->withTimestamps();
	}

	public function setCourse($course_id){

		$user = $this->user->first();

		$helper = \DB::table('teacher_helpers_users')
			->where('active', '=', true)
			->where('user_id', '=', $user->id)
			->select('id')
			->first();

		$result = \DB::table('courses_teacher_helpers')
			->insert([
				"course_id" => $course_id,
				"helper_id" => $helper->id,
				"created_at" => Carbon::now(),
				"updated_at" => Carbon::now()
		]);

		return $result;

	}

	public function setCenter($center_id){

		$user = $this->user->first();

		$helper = \DB::table('teacher_helpers_users')
			->where('active', '=', true)
			->where('user_id', '=', $user->id)
			->select('id')
			->first();

		$result = \DB::table('centers_teacher_helpers')
			->insert([
				"center_id" => $center_id,
				"helper_id" => $helper->id,
				"created_at" => Carbon::now(),
				"updated_at" => Carbon::now()
		]);

		return $result;
	}

	public function courses(){


		$helper_id = \DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->select('id')
			->first();



		$courses = \DB::table('courses_teacher_helpers')
			->where('helper_id', '=', $helper_id->id)
			->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id')
			->groupBy('courses.id')
			->select('courses.id')
			->get();

		// return $courses;
		$courses_id = array();

		foreach($courses as $course){
			array_push($courses_id, $course->id);
		}

		$courses = \App\Course::whereIn('id', $courses_id)->get();

		return $courses;

	}

	public function centers(){
		$helper_id = \DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->select('id')
			->first();

		$centers = \DB::table('centers_teacher_helpers')
			->where('helper_id', '=', $helper_id->id)
			->join('centers', 'centers.id', '=', 'centers_teacher_helpers.course_id')
			->groupBy('centers.id')
			->select('centers.id')
			->get();

		$centers_id = array();

		foreach($centers as $center){
			array_push($centers_id, $center->id);
		}

		$centers = \App\Center::whereIn('id', $centers_id)->get();

		return $centers;
	}

	public function clear(){
		$helper_id = \DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->where('active', '=', true)
			->select('id')
			->first();

		\DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->where('active', '=', true)
			->update([
				"active" => false
			]);

		\DB::table('centers_teacher_helpers')
			->where('helper_id', '=', $helper_id->id)
			->update([
				"active" => false
			]);

		\DB::table('courses_teacher_helpers')
			->where('helper_id', '=', $helper_id->id)
			->update([
				"active" => false
			]);


	}

}
