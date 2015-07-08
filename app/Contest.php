<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model {

	protected $table = 'contests';

	public function center(){
		return $this->belongsToMany('App\Center')->withTimestamps();
	}

	public function course(){
		return $this->belongsToMany('App\Course')->withTimestamps();
	}

}
