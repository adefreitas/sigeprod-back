<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseCoordinator extends Model {

	public function professor(){
		return $this->belongsToOne('App\Professor');
	}

	public function course(){
		return $this->belongsToMany('App\Course');
	}

}
