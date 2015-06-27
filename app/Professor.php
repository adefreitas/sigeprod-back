<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model {

	//

	public function user(){
		return $this->hasOne('App\User');
	}

	public function course(){
		return $this->belongsToMany('App\Course');
	}

}
