<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model {


    public function profesores(){
        return $this->belongsToMany('App\Profesor', 'materia_profesor')->withTimestamps();
    }

    public function coordinadores(){
        return $this->belongsToMany('App\Profesor', 'coordinador_materia')->withTimestamps();
    }

    public function centro(){
        return $this->belongsTo('App\Centro');
    }

}
