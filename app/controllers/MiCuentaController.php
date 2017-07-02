<?php
/**
 * ¿?
 */
class MiCuentaController extends BaseController {

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
		return View::make('mi_cuenta.index', compact('comisiones'));
	}

	/**
	 * [ajax]
	 * Elimina una comisión.
	 */
	public function postEliminar(){
		$id_comision = Input::get('comision');
		if(is_null($id_comision)){
			return 'Error';
		}
		$comisiones = Comisiones::find($id_comision);
		$comisiones->estado = 0;
		$comisiones->save();
		return 'Comsion eliminada :D';
	}
	
}