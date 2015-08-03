<?php namespace App;

use App\Fileentry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TeacherHelper extends Model {

	protected $fillable = ['available', 'type', 'id'];

	public function user(){
		return $this->belongsToMany('App\User', 'teacher_helpers_users')->withTimestamps();
	}

	public function setCourse($course_id, $contest_id){

		$user = $this->user->first();
		\Log::info($this);
		\Log::info($user);
		$helper = \DB::table('teacher_helpers_users')
			->where('active', '=', true)
			->where('user_id', '=', $user->id)
			// ->where('contest_id', '=', $contest_id)
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

	public function setCenter($center_id, $contest_id){

		$user = $this->user->first();
		\Log::info($this);
		\Log::info($this->user);
		\Log::info($user);
		$helper = \DB::table('teacher_helpers_users')
			->where('active', '=', true)
			->where('teacher_helper_id', '=', $this->id)
			// ->where('contest_id', '=', $contest_id)
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


		$helper_ids = \DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->where('active', '=', true)
			->get();

		$helper_ids_array = array();
		$contests_ids = array();
		foreach($helper_ids as $helper_id){
			array_push($helper_ids_array, $helper_id->id);
			array_push($contests_ids, $helper_id->contest_id);
		}

		$courses = \DB::table('courses_teacher_helpers')
			->whereIn('helper_id', $helper_ids_array)
			->join('teacher_helpers_users', 'teacher_helpers_users.id', '=', 'courses_teacher_helpers.helper_id')
			->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id')
			->groupBy('courses.id', 'teacher_helpers_users.contest_id')
			->select('courses.id', 'teacher_helpers_users.contest_id')
			->get();

		// return $courses;
		// $courses_id = array();
		//
		// foreach($courses as $course){
		// 	array_push($courses_id, $course->id);
		// }
		//
		// $courses = \App\Course::whereIn('id', $courses_id)->get();

		return $courses;

	}

	public function centers(){
		$helper_ids = \DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->where('active', '=', true)
			// ->select('id')
			->get();

		$helper_ids_array = array();
		$contests_ids = array();
		foreach($helper_ids as $helper_id){
			array_push($helper_ids_array, $helper_id->id);
			array_push($contests_ids, $helper_id->contest_id);
		}
		// return $helper_ids_array;

		$centers = \DB::table('centers_teacher_helpers')
			->whereIn('helper_id', $helper_ids_array)
			->join('teacher_helpers_users', 'teacher_helpers_users.id', '=', 'centers_teacher_helpers.helper_id')
			->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id')
			->groupBy('centers.id', 'teacher_helpers_users.contest_id')
			->select('centers.id', 'teacher_helpers_users.contest_id')
			->get();

		// $centers_id = array();
		//
		// foreach($centers as $center){
		// 	array_push($centers_id, $center->id);
		// }

		// $centers = \App\Center::whereIn('id', $centers_id)->get();
		// $centers = \App\Center::get();

		return $centers;
	}

	public function removeCourse($course_id, $contest_id){

		$user = $this->user->first();
		// \Log::info($user);
		// \Log::info($contest_id);
		$helper = \DB::table('teacher_helpers_users')
			->where('active', '=', true)
			->where('user_id', '=', $user->id)
			->where('contest_id', '=', $contest_id)
			->first();
		// \Log::info($helper);

		$result = \DB::table('courses_teacher_helpers')
			->where('course_id', '=', $course_id)
			->where('helper_id', '=', $helper->id)
			->where('active', '=', true)
			->update([
					'active' => false
			]);

		return $result;

	}
	public function removeCenter($center_id, $contest_id){

		$user = $this->user->first();

		$helper = \DB::table('teacher_helpers_users')
			->where('active', '=', true)
			->where('user_id', '=', $user->id)
			->where('contest_id', '=', $contest_id)
			->first();

		$result = \DB::table('centers_teacher_helpers')
			->where('center_id', '=', $center_id)
			->where('helper_id', '=', $helper->id)
			->where('active', '=', true)
			->update([
					'active' => false
			]);

		return $result;

	}

	public function getFiles(){
		$user_id = \DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->where('active', '=', true)
			->select('user_id')
			->first()
			->user_id;

		$preapproved_id =  \DB::table('preapproved_users')
			->where('personal_id', '=', $user_id)
			->where('activated', '=', true)
			->orderBy('updated_at', 'desc')
			->first()
			->id;

		return Fileentry::where('preapproved_id','=', $preapproved_id)
			->get();

	}

	public function clear(){
		$this->reserved_for = null;
		$this->reserved = false;
		$this->available = true;

		$helpers = \DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->where('active', '=', true)
			->get();

		foreach($helpers as $helper){
			\DB::table('courses_teacher_helpers')
				->where('helper_id', '=', $helper->id)
				->where('active', '=', true)
				->update([
					'active' => false
			]);
			\DB::table('centers_teacher_helpers')
				->where('helper_id', '=', $helper->id)
				->where('active', '=', true)
				->update([
					'active' => false
			]);
		}

		// $this->user()->detach();

		\DB::table('teacher_helpers_users')
			->where('teacher_helper_id', '=', $this->id)
			->where('active', '=', true)
			->update([
				"active" => false
		]);

	}

}
