<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

	public function center(){
		return $this->belongsTo('App\Center');
	}

	public function professor(){
		return $this->belongsToMany('App\Professor')->withTimestamps();
	}

	public function courseCoordinator(){
		return $this->belongsToMany('App\CourseCoordinator')->withTimestamps();
	}

}
