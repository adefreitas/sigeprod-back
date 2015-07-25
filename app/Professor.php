<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model {

	protected $table = 'professors';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id', 'dedication', 'proposition_sent'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];


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
		return $this->belongsToMany('App\Course', 'course_course_coordinator')->withTimestamps();
	}

	public function centerCoordinator(){
		return $this->belongsToMany('App\Center', 'center_center_coordinator')->withTimestamps();
	}

}
