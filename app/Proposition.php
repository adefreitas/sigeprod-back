<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposition extends Model {

	protected $table = 'propositions';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}
