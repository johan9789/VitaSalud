<?php
/**
 * Inicio | Página inicial.
 */
class HomeController extends BaseController {

	/**
	 * Muestra la página principal.
	 */
	public function getIndex(){
		return View::make('index');
	}	

	/**
	 * Muestra la página de inicio de sesión.
	 */
	public function getLogin(){
		if(Session::has('usuario')){
			return Redirect::to('');
		}
		return View::make('login');
	}

	/**
	 * Valida los datos del inicio de sesión.
	 */
	public function postLogin(){
		$post = Input::all();
		$rule = ['usuario' => 'required', 'contrasena' => 'required'];
		if(Validator::make($post, $rule)->fails()){
			return Redirect::to('')->with('mensaje', '¡Complete todos los campos!');
		}

		$val_user = Usuario::where('Usuario', '=', $post['usuario']);
		if($val_user->count() && Hash::check($post['contrasena'], $val_user->get()[0]->Password)){
			$us = $val_user->first();
			$t_us = TipoUsuario::where('id_tipousuario', '=', $us->id_tipousuario)->get()[0];

			Session::put('user_model', Usuario::find($us->idUsuario));
			Session::put('usuario', $post['usuario']);
			Session::put('id_tipousuario', $us->id_tipousuario);
			Session::put('tipo_usuario', $t_us->nombretipo);
			Session::put('id_persona', $us->idPersona);
			Session::put('id_usuario', $us->idUsuario);
			
			if($t_us->nombretipo == 'Distribuidor'){
				$dist = Distribuidores::where('idPersona', '=', $us->idPersona);
				if(!$dist->count()){
					return Redirect::to('login')->with('mensaje', 'Error en el usuario y/o contraseña.');
				}
				$dist_t = $dist->first();
				Session::put('id_dist', $dist_t->idDistribuidor);
			}
			return Redirect::to('');
		} else {
			return Redirect::to('login')->with('mensaje', 'Error en el usuario y/o contraseña.');
		}
	}

	/**
	 * Cierra la sesión.
	 */
	public function get_logout(){
		Session::flush();
		return Redirect::to('');
	}		

	private function login(){
		if(Auth::attempt(['Usuario' => $post['usuario'], 'password' => $post['contrasena']], true)){
			return Redirect::to('');
		} else {
			return Redirect::to('login')->with('mensaje', 'Error en el usuario y/o contraseña.');
		}
	}

}