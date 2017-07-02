<?php
namespace Resources;

use Cliente, Persona;
use Input, Redirect, Response;

class ClientesController extends \BaseController {

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

		$persona = new Persona();
		$persona->Nombres = $post['nombre'];
		$persona->Apellidos = $post['apellidos'];
		$persona->DNI = $post['dni'];
		$persona->Telefono = $post['telefono'];
		$persona->Celular = $post['celular'];
		$persona->email = $post['email'];	
		$persona->Direccion = $post['direccion'];		
		$persona->estado = 1;
		$persona->foto = '';
		$persona->iddistrito = $post['distrito'];
		$persona->save();

		$cliente = new Cliente();
		$cliente->idPersona = $persona->idPersona;
		$cliente->save();
		return $cliente->idCliente;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id_cliente
	 * @return Response
	 */
	public function show($id_cliente){
		if(Input::get('type') == 'json'){
			$cliente = Cliente::detalle($id_cliente)->get()[0];
			return Response::json($cliente);			
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