<?php
class ProveedorDistribuidor extends Eloquent {
	protected $table = 'proveedor_distribuidor';
	protected $primaryKey = 'id_proveedor_dist';
	public $timestamps = false;
	protected $fillable = ['RUC', 'razon_social_proveedor_dist', 'direccion_proveedor_dist', 'telf_proveedor_dist', 'email_proveedor_dist', 'iddistrito'];

	public function distrito(){
		return $this->belongsTo('Distrito', 'iddistrito');
	}

	public function compras(){
		return $this->hasMany('ComprasDistribuidor', 'idProveedorDist');
	}

	/**
	 * Lista de proveedores.
	 * 
	 * Utilizado en:
	 * Compras\Distribuidor\HomeController@index
	 * 
	 */
	public function scopeLista($query){
		return $query->with('distrito')->where('estado_proveedor_dist', 1);
	}	

}
