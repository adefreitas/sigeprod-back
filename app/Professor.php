<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model {

	public function user(){
		return $this->hasOne('App\User');
	}

	public function course(){
		return $this->belongsToMany('App\Course');
	}

	public function center(){
		return $this->belongsToMany('App\Center');
	}

	public function courseCoordinator(){
		return $this->belongsToMany('App\courseCoordinator');
	}

	public function centerCoordinator(){
		return $this->belongsToMany('App\centerCoordinator');
	}

}
