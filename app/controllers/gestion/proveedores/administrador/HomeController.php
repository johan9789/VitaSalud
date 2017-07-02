<?php
namespace Gestion\Proveedores\Administrador;

use Proveedor, Distrito;
use View, Input, Response, Redirect, Session;

class HomeController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('administrador');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$title = 'Gestión de proveedores';
		$proveedores = Proveedor::lista()->get();
		$distritos = Distrito::lista()->lists('NombreDistrito', 'iddistrito');
		return View::make('gestion.proveedores.adm.index', compact('title', 'proveedores', 'distritos'));
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
		Proveedor::create(Input::except(['_token']));
		return 'Proveedor registrado correctamente.';
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id_proveedor
	 * @return Response
	 */
	public function show($id_proveedor){		
		if(Input::get('type') == 'json'){
			$proveedor = Proveedor::find($id_proveedor);
			Session::put('xid_prov', $proveedor->id_proveedor);
			return Response::json($proveedor);			
		} elseif(Input::get('type') == 'jsdist'){
			return Distrito::lista()->lists('NombreDistrito', 'iddistrito');
		} else {
			return Redirect::to('');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id_proveedor
	 * @return Response
	 */
	public function edit($id_proveedor){}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id_proveedor
	 * @return Response
	 */
	public function update($id_proveedor){
		$all = Input::except(['_token']);
		$proveedor = Proveedor::find($id_proveedor);
		$proveedor->RUC = $all['RUC'];
		$proveedor->razon_social_proveedor = $all['razon_social_proveedor'];
		$proveedor->direccion_proveedor = $all['direccion_proveedor'];
		$proveedor->telefono_proveedor = $all['telefono_proveedor'];
		$proveedor->email_proveedor = $all['email_proveedor'];
		$proveedor->iddistrito = $all['iddistrito'];
		$proveedor->save();
		return 'Proveedor modificado correctamente';
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id_proveedor
	 * @return Response
	 */
	public function destroy($id_proveedor){
		$proveedor = Proveedor::find($id_proveedor);
		$proveedor->estado_proveedor = 0;
		$proveedor->save();
		return 'Proveedor eliminado correctamente';
	}

}