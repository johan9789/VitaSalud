<?php
namespace Resources;

use Cliente, Empresa;
use Input, Redirect, Response;

class EmpresasController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){}

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
		$post = Input::all();

		$empresa = new Empresa();
		$empresa->RUC = $post['ruc'];
		$empresa->NombreEmpresa = $post['nombre'];
		$empresa->DireccionEmpresa = $post['direccion'];
		$empresa->TelefonoEmpresa = $post['telefono'];
		$empresa->EmailEmpresa = $post['email'];
		$empresa->idDistrito = $post['distrito'];
		$empresa->save();

		$cliente = new Cliente();
		$cliente->idEmpresa = $empresa->idEmpresa;
		$cliente->save();
		return $cliente->idCliente;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id_empresa
	 * @return Response
	 */
	public function show($id_empresa){
		if(isset($_GET['type']) && $_GET['type'] == 'json'){
			$empresa = Empresa::detalle($id_empresa)->get()[0];
			return Response::json($empresa);
		} else {
			return Redirect::to('');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id_cliente
	 * @return Response
	 */
	public function edit($id_cliente){}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id_cliente
	 * @return Response
	 */
	public function update($id_cliente){}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id_cliente
	 * @return Response
	 */
	public function destroy($id_cliente){}

}