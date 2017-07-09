<?php
namespace Model\Distribuidor;

class Categoria extends \Eloquent{
	protected $table = 'categoria_producto_distribuidor';
	protected $primaryKey = 'idCategoriaProductoDist';
	public $timestamps = false;
}
