<?php
class DetComprasDist extends Eloquent {
	protected $table = 'det_compras_dist';
	protected $primaryKey = 'idDetCompraDist';
	public $timestamps = false;

	public function producto(){
		return $this->belongsTo('ProductoDistribuidor', 'idProductoCompraDist');
	}

}