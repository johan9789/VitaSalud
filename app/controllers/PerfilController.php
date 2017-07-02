<?php
/**
 * Inicio | Editar perfil.
 */
class PerfilController extends BaseController {

	/** 
	 * [POST | ajax]
	 * Actualiza los datos de perfil del usuario
	 */	
	public function postEditar(){
		$post = Input::all();
		$rule = ['telefono' => 'required', 'celular' => 'required', 'email' => 'required', 'direccion' => 'required', 'distrito' => 'required'];
		if(Validator::make($post, $rule)->fails()){
			return '¡Completa todos los campos!';
		}
		$persona = Persona::find(Session::get('id_persona'));
		$persona->Telefono = $post['telefono'];
		$persona->Celular = $post['celular'];
		$persona->email = $post['email'];
		$persona->Direccion = $post['direccion'];
		$persona->iddistrito = $post['distrito'];
		$persona->save();
		return 'Perfil actualizado correctamente :D';
	}

	/**
	 * [POST]
	 * Actualiza la foto de perfil del usuario
	 */
	public function postImagen(){
		$post = Input::all();
		$rule = ['foto' => 'required'];
		if(Validator::make($post, $rule)->fails()){
			return Redirect::to('')->with('mensaje', 'No seleccionaste una imagen.');
		}
     	$file = Input::file('foto'); 
     	// $archivo = $file->getClientOriginalName();     	
     	$extension  = $file->getClientOriginalExtension();
     	$nuevo_nombre = md5(microtime()).'.'.$extension;
    	// $tamano = $file->getSize();        	
     	$upload = $file->move('assets/user_photos', $nuevo_nombre);
     	if($upload){
			$obra = Persona::find(Session::get('id_persona'));
			$obra->foto = $nuevo_nombre;
			$obra->save();
			Session::flash('mensaje', 'Se actualizó la imagen de perfil.');
			return Redirect::back();
		} else {
			Session::flash('mensaje', 'No se pudo subir el archivo ._.');
			return Redirect::back();
		}
	}

	/**
	 * [POST | ajax]
	 * Valida si la contraseña actual es real, valida si la contraseña nueva conicide con la contraseña a cambiar
	 * y realiza la actualización.
	 */	
	public function postContrasena(){
		$post = Input::all();
		$rule = ['contrasena_actual' => 'required', 'contrasena_nueva' => 'required', 'confirmar_contrasena' => 'required'];
		if(Validator::make($post, $rule)->fails()){
			return '¡Completa todos los campos!';
		}

		$actual = $post['contrasena_actual'];
		$nueva = $post['contrasena_nueva'];
		$conf = $post['confirmar_contrasena'];

		$user = Usuario::whereRaw('Usuario = ?', [Session::get('usuario')])->first();		

		if(!Hash::check($actual, $user->Password)){
			return 'Escribe tu contraseña actual.';
		}

		if($nueva != $conf){
			return 'Las contraseñas no coinciden.';
		}

		$us_x = Usuario::find(Session::get('id_usuario'));
		$us_x->Password = Hash::make($conf);
		$us_x->save();
		return 'Se cambió la contraseña.';
	}

}