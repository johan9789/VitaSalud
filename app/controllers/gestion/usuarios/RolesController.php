<?php
namespace Gestion\Usuarios;

use TipoUsuario;
use Input, View;

class RolesController extends \BaseController {

	/**
	 * Validación de la sesión del administrador.
	 */
	public function __construct(){
		$this->beforeFilter('administrador');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$title = 'Roles';
		$lista_roles = TipoUsuario::where('estado_tipousuario', 1)->get();
		return View::make('gestion.usuarios.roles', compact('title', 'lista_roles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
		$tipo_usuario = new TipoUsuario();
		$tipo_usuario->nombretipo = Input::get('nombretipo');
		$tipo_usuario->save();
		return 'Rol creado correctamente';
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){}

}