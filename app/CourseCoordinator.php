<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseCoordinator extends Model {

	public function professor(){
		return $this->belongsToMany('App\Professor');
	}

	public function course(){
		return $this->belongsToMany('App\Course');
	}

}
