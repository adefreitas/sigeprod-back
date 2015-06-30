<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CenterCoordinator extends Model {

	public function professor(){
		return $this->belongsToOne('App\Professor');
	}

	public function center(){
		return $this->belongsToMany('App\Center')->withTimestamps();
	}

}
