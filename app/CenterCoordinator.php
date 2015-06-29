<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CenterCoordinator extends Model {

	public function professor(){
		return $this->belongsToMany('App\Professor');
	}

	public function center(){
		return $this->belongsToMany('App\Center');
	}

}
