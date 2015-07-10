<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposition extends Model {

	protected $table = 'propositions';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id', 'course_option_1', 'mode_option_1', 'course_option_2', 'mode_option_2', 'course_option_3', 'mode_option_3'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public function professor(){
		return $this->belongsTo('App\Professor')->withTimestamps();
	}

}
