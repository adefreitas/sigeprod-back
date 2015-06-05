<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model {


    public function materias(){
        return $this->hasMany('App\Materia');
    }

    public function coordinador(){
        return $this->belongsToMany('App\Profesor', 'centro_coordinador');
    }

}
