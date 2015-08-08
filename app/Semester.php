<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model {

	protected $table = 'semesters';

	protected $fillable = ['name', 'beings_at', 'ends_at', 'intensive'];

}
