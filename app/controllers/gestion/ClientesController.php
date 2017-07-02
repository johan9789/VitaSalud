<?php
namespace Gestion;

use Cliente, Persona, Empresa; 
use Input, View;

class ClientesController extends \BaseController {
	
	public function __construct(){		
		$this->beforeFilter('administrador');
	}

	public function getIndex(){
		$title = 'Gestión de clientes';
		$clientesN = Cliente::natural()->get();
		$clientesJ = Cliente::juridica()->get();
		return View::make('gestion.clientes.index', compact('title', 'clientesN', 'clientesJ'));
	}

	public function postActualizarClientes(){
		$inputs = Input::all();
		$id = $inputs['idPersona'];
		$clientes = Cliente::where('idPersona', '=', $id)->get();
		$bandera = false;

		if(!empty($id)){
			if(count($clientes) == 0){
				$bandera = true;
			}
		} else {
			$bandera = true;
		}

		if($bandera == true){
			return 'Error al Modificar';
		} else {
			$p = Persona::find($id);
			$p->Nombres = $inputs['nombre'];
			$p->Apellidos = $inputs['apellidos'];
			$p->DNI = $inputs['dni'];
			$p->Telefono = $inputs['telefono'];
			$p->Celular = $inputs['celular'];
			$p->email = $inputs['email'];
			$p->Direccion = $inputs['direccion'];
			$p->save();
		}
		return 'Cliente Modificado';
	}

	public function postActualizarEmpresas(){
		$inputs = Input::all();
		$id = $inputs['idEmpresa'];
		$clientes = Cliente::where('idEmpresa', '=', $id)->get();
		$bandera = false;

		if(!empty($id)){
			if(count($clientes) == 0){
				$bandera = true;
			}
		} else {
			$bandera = true;
		}

		if($bandera == true){
			return 'Error al Modificar';
		} else {
			$e = Empresa::find($id);
			$e->RUC = $inputs['ruc'];
			$e->NombreEmpresa = $inputs['empresa'];
			$e->DireccionEmpresa = $inputs['direccion'];
			$e->TelefonoEmpresa = $inputs['telefono'];
			$e->EmailEmpresa = $inputs['email'];
			$e->save();
		}
		return 'Cliente Modificado';
	}

}
