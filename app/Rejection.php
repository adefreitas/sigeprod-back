<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rejection extends Model {

	protected $fillable = ['description', 'active', 'user_id','proposition_id'];

	public function proposition(){
		return $this->belongsTo('App\Proposition');
	}

}