<?php
namespace Gestion\Usuarios;

use Distribuidores, Distrito, Persona, RangoDistribuidor, TipoUsuario, Usuario;
use Hash, Input, Redirect, Response, Session, Validator, View;

/**
 * Gestión de usuarios.
 */
class HomeController extends \BaseController {

	/**
	 * Validación de la sesión del administrador.
	 */
	public function __construct(){
		$this->beforeFilter('administrador');
	}

	/**
	 * Muestra la página principal.
	 */
	public function getIndex(){
		$title = 'Gestión de usuarios';
		$personas = Persona::lista(Session::get('usuario'))->get();
		$tipo_usuarios = TipoUsuario::where('estado_tipousuario', 1)->lists('nombretipo', 'id_tipousuario');
		$rango_distribuidor = RangoDistribuidor::lists('NombreRangoDistribuidor', 'idRangoDistribuidor');
		$distritos = Distrito::lista()->lists('NombreDistrito', 'iddistrito');
		return View::make('gestion.usuarios.index', compact('personas', 'tipo_usuarios', 'rango_distribuidor', 'distritos', 'title'));
	}

	/**
	 * [ajax]
	 * Registra un nuevo usuario y devuelve la lista de usuarios actualizada.
	 */
	public function postRegistrar(){
		$post = Input::all();
		$rule = ['nombre' => 'required', 'apellidos' => 'required', 'usuario' => 'required', 'contrasena' => 'required', 'tipo_usuario' => 'required'];
		if(Validator::make($post, $rule)->fails()){
			Session::flash('mensaje', '¡Completa todos los campos!');
			return Redirect::back();
		}

		if(Usuario::where('usuario', '=', $post['usuario'])->count() != 0){
			Session::flash('mensaje', '¡El usuario ya está registrado!');
			return Redirect::back();
		}

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

		$usuario = new Usuario();
		$usuario->Usuario = $post['usuario'];
		$usuario->Password = Hash::make($post['contrasena']);
		$usuario->idPersona = $persona->idPersona;
		$usuario->id_tipousuario = $post['tipo_usuario'];
		$usuario->save();

		if($post['tipo_usuario'] == 2){
			date_default_timezone_set('America/Lima');
			$dist = new Distribuidores();
			$dist->FechaIngreso = date('Y-m-d');
			$dist->idPersona = $persona->idPersona;
			$dist->idDistribuidorReferido = 0;
			$dist->idRangoDistribuidor = $post['rango_distribuidor'];
			$dist->save();
		}
		return 'Usuario registrado correctamente.';
	}	

	/**
	 * [ajax]
	 * Elimina el usuario seleccionado.
	 */
	public function postEliminar(){
		$id_persona = Input::get('id');
		
		if(!Persona::where('idPersona', '=', $id_persona)->count()){
			return 'Error';
		}

		if(!Persona::where('idPersona', '=', Session::get('id_persona'))->count()){
			return "Error.";
		}

		$persona = Persona::find($id_persona);
		$persona->estado = 0;
		$persona->save();
		return 'Usuario eliminado correctamente.';
	}

	/**
	 * [ajax/json]
	 * Devuelve los datos del usuario que se va a editar, en formato json.
	 */
	public function getEditar(){
		$id_persona = Input::get('id');

		if(!Persona::where('idPersona', '=', $id_persona)->count()){
			return "Error.";
		}

		if(!Persona::where('idPersona', '=', \Session::get('id_persona'))->count()){
			return "Error.";
		}

		Session::put('id_a_editar', $id_persona);
		$persona = Persona::byId($id_persona);
		return Response::json($persona);
	}

	/**
	 * [ajax]
	 * Actualiza los datos del usuario seleccionado.
	 */
	public function postActualizar(){
		if(!Session::has('id_a_editar')){
			return "Error.";
		}

		if(!Persona::where('idPersona', '=', Session::get('id_persona'))->count()){
			return "Error.";	
		}

		$post = Input::all();
		$rule = ['nombre' => 'required', 'apellidos' => 'required', 'usuario' => 'required'];
		if(Validator::make($post, $rule)->fails()){
			return '¡Completa todos los campos!';
		}		

		$s_id = Session::get('id_a_editar');

		$persona = Persona::find($s_id);
		$persona->Nombres = $post['nombre'];
		$persona->Apellidos = $post['apellidos'];
		$persona->DNI = $post['dni'];
		$persona->Telefono = $post['telefono'];
		$persona->Celular = $post['celular'];
		$persona->email = $post['email'];	
		$persona->Direccion = $post['direccion'];
		$persona->iddistrito = $post['distrito'];
		$persona->save();

		$us = Usuario::where('idPersona', '=', $s_id)->get()[0];

		$usuario = Usuario::find($us->idUsuario);
		$usuario->usuario = $post['usuario'];
		$usuario->id_tipousuario = $post['tipo_usuario'];
		$usuario->save();
		
		Session::forget('id_a_editar');
		return 'Usuario modificado correctamente.';
	}

}