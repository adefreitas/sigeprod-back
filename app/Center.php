<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Center extends Model {

	public function professor(){
		return $this->hasMany('App\Professor');
	}

	public function centerCoordinator(){
		return $this->belongsToMany('App\Professor', 'center_center_coordinator')->withTimestamps();
	}

	public function courses(){
		return $this->hasMany('App\Course');
	}

	public function contests(){
		return $this->belongsToMany('App\Contest')->withTimestamps();
	}

}
