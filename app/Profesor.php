<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model {

	protected $table = 'profesores';

    public function materias(){
        return $this->belongsToMany('App\Materia', 'materia_profesor')->withTimestamps();
    }

    public function coordinacionesMaterias(){
        return $this->belongsToMany('App\Materia', 'coordinador_materia')->withTimestamps();
    }

    public function coordinacionesCentros(){
        return $this->belongsToMany('App\Centro', 'coordinador_centro')->withTimestamps();
    }
}
