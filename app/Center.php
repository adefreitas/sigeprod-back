<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Center extends Model {

	public function professor(){
		return $this->hasMany('App\Professor');
	}

	public function centerCoordinator(){
		return $this->belongsToMany('App\CenterCoordinator')->withTimestamps();
	}

	public function courses(){
		return $this->hasMany('App\Course');
	}

}
