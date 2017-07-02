<?php
class Provincia extends Eloquent {
	protected $table = 'provincia';
	protected $primaryKey = 'idprovincia';
	public $timestamps = false;

	public function distritos(){
		return $this->hasMany('Distrito', 'idprovincia');
	}

}