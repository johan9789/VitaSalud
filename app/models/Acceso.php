<?php
class Acceso extends Eloquent {
	protected $table = 'acceso';
	protected $primaryKey = 'id_acceso';
	public $timestamps = false;

	public function subAccesos(){
		return $this->hasMany('Acceso', 'id_acceso_ref');
	}

	public function roles(){
		return $this->belongsToMany('TipoUsuario', 'acceso_tipousuario', 'id_acceso', 'id_tipousuario');
	}

	/**
	 * Lista de accesos.
	 * 
	 * Utilizado en:
	 * Gestion\Usuarios\PrivilegiosController@getIndex
     * VitaSalud\Extra\Providers\ViewServiceProvider@register
	 *
	 */
	public function scopeLista($query){
		return $query->join('acceso_tipousuario', 'acceso.id_acceso', '=', 'acceso_tipousuario.id_acceso')
					->where('acceso_tipousuario.id_tipousuario', '=', Session::get('id_tipousuario'))
					->where('acceso.estado_acceso', '=', 1)
					->whereNull('acceso.id_acceso_ref');
	}	

	/** 
	 * Lista de sub accesos.
	 *
	 * Utilizado en:
     * VitaSalud\Extra\Providers\ViewServiceProvider@register
	 *
	 */
	public function scopeSubLista($query, $acceso_padre){
		return $query->join('acceso_tipousuario', 'acceso.id_acceso', '=', 'acceso_tipousuario.id_acceso')
					->where('acceso_tipousuario.id_tipousuario', '=', Session::get('id_tipousuario'))
					->where('acceso.estado_acceso', '=', 1)
					->where('acceso.id_acceso_ref', '=', $acceso_padre);
	}

	/**
	 * Lista general de accesos.
	 *
	 * Utilizado en:
	 * Gestion\Usuarios\PrivilegiosController@getIndex
	 *
	 */
	public function scopeListaGeneral($query){
		return $query->where('estado_acceso', '=', 1)->whereNull('id_acceso_ref');
	}

	/**
	 * Lista de roles actuales.
	 * 
	 * Utilizado en:
	 * Gestion\Usuarios\PrivilegiosController@postRolesActuales
	 *
	 */
	public function scopeRolesActuales($query, $acceso){
		return $query->select('tipousuario.nombretipo')
					->join('acceso_tipousuario', 'acceso_tipousuario.id_acceso', '=', 'acceso.id_acceso')
					->join('tipousuario', 'acceso_tipousuario.id_tipousuario', '=', 'tipousuario.id_tipousuario')
					->where('acceso.id_acceso', '=', $acceso);
	}

	/**
	 * Lista de roles restantes.
	 * 
	 * Utilizado en:
	 * Gestion\Usuarios\PrivilegiosController@postRolesRestantes
	 *
	 */
	public static function rolesRestantes($acceso){
		return TipoUsuario::whereNotIn('id_tipousuario', function($query) use($acceso){
					$query->select('tipousuario.id_tipousuario')
					->from('acceso_tipousuario')
					->join('acceso', 'acceso_tipousuario.id_acceso', '=', 'acceso.id_acceso')
					->join('tipousuario', 'acceso_tipousuario.id_tipousuario', '=', 'tipousuario.id_tipousuario')
					->where('acceso.id_acceso', $acceso);
		});
	}

}