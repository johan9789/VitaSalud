<?php 
namespace Model\Distribuidor;

class Productos extends \Eloquent{
	protected $table = 'producto_distribuidor';
	protected $primaryKey = 'idProductoDist';
	public $timestamps = false;

	public function categoria(){
	    return $this->belongsTo('DistCategoria', 'idCategoriaProductoDist');
    }

}
