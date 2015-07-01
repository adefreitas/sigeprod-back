<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model {

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function course(){
		return $this->belongsToMany('App\Course')->withTimestamps();
	}

	public function center(){
		return $this->belongsToMany('App\Center')->withTimestamps();
	}

	public function courseCoordinator(){
		return $this->belongsToMany('App\CourseCoordinator')->withTimestamps();
	}

	public function centerCoordinator(){
		return $this->belongsToMany('App\CenterCoordinator')->withTimestamps();
	}

}
