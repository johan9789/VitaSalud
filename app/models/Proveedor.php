<?php
class Proveedor extends Eloquent {
	protected $table = 'proveedor';
	protected $primaryKey = 'id_proveedor';
	public $timestamps = false;
	protected $fillable = ['RUC', 'razon_social_proveedor', 'direccion_proveedor', 'telefono_proveedor', 'email_proveedor', 'iddistrito'];

	public function distrito(){
		return $this->belongsTo('Distrito', 'iddistrito');
	}

	public function compras(){
		return $this->hasMany('Compras', 'idProveedor');
	}

	/**
	 * Lista de proveedores.
	 * 
	 * Utilizado en:
	 * Compras\Administrador\HomeController@index
	 * Gestion\Proveedores\Administrador\HomeController@index
	 * 
	 */
	public function scopeLista($query){
		return $query->with('distrito')->where('estado_proveedor', 1);
	}
	
}
