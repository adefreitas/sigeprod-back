<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PreapprovedUser extends Model {

	protected $fillable = ['name', 'email', 'lastname', 'activated', 'id'];

}
