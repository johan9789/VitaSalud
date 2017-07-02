<?php
namespace Gestion\Usuarios;

use Acceso, TipoUsuario, AccesoTipoUsuario, Persona, Distrito, RangoDistribuidor;
use Input, Response, Session, View;

class PrivilegiosController extends \BaseController {

	/**
	 * Validación de la sesión del administrador.
	 */
	public function __construct(){
		$this->beforeFilter('administrador');
	}

	public function getIndex(){
		$title = 'Privilegios';
		$lista_accesos_generales = Acceso::listaGeneral()->get();
		$lista_roles = TipoUsuario::all();
		$personas = Persona::with(['usuario' => function($q){ $q->with('tipoUsuario'); }])->get();
        $distritos = Distrito::lista()->lists('NombreDistrito', 'iddistrito');
        $tipo_usuarios = TipoUsuario::where('estado_tipousuario', 1)->lists('nombretipo', 'id_tipousuario');
        $rango_distribuidor = RangoDistribuidor::lists('NombreRangoDistribuidor', 'idRangoDistribuidor');
		return View::make('gestion.usuarios.privilegios', compact('title', 'lista_accesos_generales', 'lista_roles', 'personas', 'distritos', 'tipo_usuarios', 'rango_distribuidor'));
	}

	public function postDetalleAcceso(){
		$data = Acceso::find(Input::get('acceso'));
		Session::put('xm_id_acceso', Input::get('acceso'));
		return Response::json($data);
	}

	public function postActualizarAcceso(){
		$all = Input::all();
		$acceso = Acceso::findOrFail(Session::get('xm_id_acceso'));
		$acceso->nombre_acceso = $all['nombre_acceso'];
		$acceso->descripcion_acceso = $all['descripcion_acceso'];
		$acceso->save();
		Session::forget('xm_id_acceso');
		return 'Acceso actualizado correctamente.';
	}

	public function postRolesActuales($acceso){
		return Response::json(Acceso::rolesActuales($acceso)->get());
	}

	public function postRolesRestantes($acceso){
		return Response::json(Acceso::rolesRestantes($acceso)->get());
	}

	public function postAsignarRol(){
		$all = Input::all();
		$act = new AccesoTipoUsuario();
		$act->id_acceso = $all['acceso'];
		$act->id_tipousuario = $all['roles_disponibles'];
		$act->save();
		return 'Asignación realizada.';
	}

}