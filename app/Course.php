<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

	protected $table = 'courses';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'credits', 'semester'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public function id(){
		return 1;
	}
	public function center(){
		return $this->belongsTo('App\Center');
	}

	public function professor(){
		return $this->belongsToMany('App\Professor')->withTimestamps();
	}

	public function courseCoordinator(){
		return $this->belongsToMany('App\Professor', 'course_course_coordinator')->withTimestamps();
	}

	public function contest(){
		return $this->belongsToMany('App\Contest')->withTimestamps();
	}

}
