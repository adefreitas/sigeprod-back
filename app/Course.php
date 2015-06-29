<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

	//

	public function professor(){
		return $this->belongsToMany('App\Professor');
	}

	public function coordinator(){
		return $this->belongsToMany('App\CourseCoordinator');
	}

}
