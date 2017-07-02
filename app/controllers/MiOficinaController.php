<?php
/**
 * ¿?
 */
class MiOficinaController extends BaseController {

	/**
	 * Validación de la sesión del distribuidor.
	 */
	public function __construct(){
		$this->beforeFilter('invalido');
	}

	/**
	 * Muestra la página principal.
	 */
	public function getIndex(){
		$comisiones = Distribuidores::misComisiones(Session::get('id_dist'))->get();
		return View::make('mi_oficina.index', compact('comisiones'));
	}

	/**
	 * ¿?
	 */
	public function getVer(){
		return Comisiones::where('idDistribuidor', '=', Session::get('id_dist'))->get()->last();
	}
		
}