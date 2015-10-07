<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterPlanning extends Model {

	protected $table = 'semester_planning';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['content','active'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}