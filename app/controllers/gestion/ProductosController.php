<?php
namespace Gestion;
/** 
 * Gestión de Productos. 
 */
class ProductosController extends \BaseController {

	/**
	 *
	 */
	public function getIndex(){
		if(!\Session::has('usuario') || \Session::get('tipo_usuario') != 'Administrador'){
			return \Redirect::to('');
		}
		$productos = \Productos::lista_prod();
		$categoriaproductos = \Categoriaproducto::lists('NombreCategoriaProducto', 'idCategoriaProducto');
		$sel_categoria = ['0' => 'Elegir Categoria:'];
		$sel_categoria += $categoriaproductos;
		$title = 'Gestión de Productos';
		return \View::make('gestion.productos.index', compact('productos', 'i', 'categoriaproductos', 'sel_categoria', 'title'));
	}
	
	/**
	 *
	 */
	public function postRegistrarprod(){
		$bandera = false;
		$inputs = \Input::all();
		$usu_register = \Session::get('usuario');
		$fecha = date('Y-m-d');
		$codbarrasexist = '';
		$nomprodexist = '';
		$productos = \Productos::lista()->get();
		foreach ($productos as $value) {
			if($value->CodBarras == $inputs['codbarras']){
				$bandera = true;
				$codbarrasexist = 'Cod. Barras ya existe';
				break;
			}
			if($value->NombreProducto == $inputs['producto']){
				$bandera = true;
				$nomprodexist = 'Nombre producto ya existe';
				break;
			}
		}
		if($bandera == true){
			\Session::flash('mensaje', 'Error al registrar: \n'.$codbarrasexist.'\n'.$nomprodexist);
			return \Redirect::back();
		}

		$path = 'assets/products_img';
		$file = \Input::file('img_file');
		$extension = strtolower($file->getClientOriginalExtension());
		// Establecemos nuevo nombre
		$nuevo_nombre = md5(microtime()).'.'.$extension;      	
     	$upload = $file->move($path, $nuevo_nombre);
     	if($upload){
			$p = new \Productos();
			$p->CodBarras			= ($inputs['codbarras'] == '')?'':$inputs['codbarras'];
			$p->NombreProducto 		= $inputs['producto'];
			$p->DetallesProducto	= $inputs['detalles'];
			$p->UrlFotoProducto		= $nuevo_nombre;
			$p->idCategoriaProducto	= $inputs['categoria'];
			$p->estado		 		= 1;
			$p->save();

			$id_ult_producto = \Productos::orderBy('idProducto', 'desc')->get()[0]->idProducto;

			$arreglo_insert_inventario = array('idProducto' => $id_ult_producto, 
													'Existencia' => 0, 
													'Costo' => 0, 
													'PrecDistribuidor' => 0, 
													'PrecPublico' => 0, 
													'Estado' => 0,
													'Comentario' => '');

			$arreglo_insert_movimiento = array('idProductoMovimiento' => $id_ult_producto, 
													'CantidadMovimiento' => 0, 
													'CostoMovimiento' => 0, 
													'TotalCosto' => 0, 
													'TipoMovimiento' => 'Inventario Inicial', 
													'FechaMovimiento' => $fecha,
													'RegistradoPor' => $usu_register);

			\DB::table('inventario_adm')->insert($arreglo_insert_inventario);
			\DB::table('movimientos_adm')->insert($arreglo_insert_movimiento);

			\Session::flash('mensaje', 'Producto Registrado');
			return \Redirect::to('gestion/productos');
		} else {
			\Session::flash('mensaje', 'Error al Registrar');
			return \Redirect::back();
		}
	}

	/**
	 *
	 */
	public function postRegistrarcat(){
		$inputs = \Input::all();
		$c = new \Categoriaproducto();
		$c->NombreCategoriaProducto 		= $inputs['categoria'];
		$c->DescripcionCategoriaProducto 	= $inputs['descripcion'];
		$c->estado 							= 1;
		$c->save();
		if($inputs['ubicacion'] == 'productos'){
			return \Redirect::to('gestion/productos');
		} else {
			return \Redirect::to('gestion/productos/categoria');
		}
	}

	/**
	 * [ajax]
	 * 
	 */
	public function postEliminarcategoria($idCategoriaProducto=null){
		$cp = \Categoriaproducto::find($idCategoriaProducto);
		if(is_null($idCategoriaProducto) || empty($cp)){
			\Session::flash('ErrorElimCat', 'Error al eliminar categoria');
			return "Error al Eliminar";
		} else {
			$cp->estado = 0;
			$cp->save();
			return "Categoria eliminada.";
		}		
	}

	/*
	 * [ajax/json]
	 *
	 */
	public function postEditarcategoria($idCategoriaProducto = null){
		if(is_null($idCategoriaProducto)){
			return "Error";
		}
		\Session::put('id_editar_cat', $idCategoriaProducto);
		$cp = \Categoriaproducto::find($idCategoriaProducto);
		return \Response::json($cp);
	}

	/**
	 * [ajax]
	 *
	 */
	public function postModificarcategoria(){
		if(!\Session::has('id_editar_cat')){
			return 'Error';
		}
		$id_c_m = \Session::get('id_editar_cat');
		$cp = \Categoriaproducto::find($id_c_m);
		$inputs = \Input::All();
		$cp->NombreCategoriaProducto 		= $inputs['categoria'];
		$cp->DescripcionCategoriaProducto	= $inputs['descripcion'];
		$cp->save();
		\Session::forget('id_editar_cat');
		return "Categoria Modificada";
	}

	/**
	 * [ajax]
	 * 
	 */
	public function postEliminarprod($idProducto=null){
		$p = \Productos::find($idProducto);
		if(is_null($idProducto) || empty($p)){
			\Session::flash('ErrorElimProd', 'Error al eliminar producto');
			return "Error al Eliminar";
		} else {
			$p->estado = 0;
			$p->save();
			return "Producto eliminado.";
		}		
	}

	/*
	 * [ajax/json]
	 *
	 */
	public function postEditarprod($idProducto = null){
		if(is_null($idProducto)){
			return "Error";
		}
		\Session::put('id_editar_prod', $idProducto);
		$productos = \Productos::find($idProducto);
		return \Response::json($productos);
	}

	/**
	 * [ajax]
	 *
	 */
	public function postModificarprod(){
		if(!\Session::has('id_editar_prod')){
			return 'Error';
		}
		$id_p_m = \Session::get('id_editar_prod');
		$p = \Productos::find($id_p_m);
		$inputs = \Input::All();
		$p->NombreProducto 		= $inputs['producto'];
		$p->DetallesProducto	= $inputs['detalles'];
		$p->idCategoriaProducto	= $inputs['categoria'];
		$p->save();
		\Session::forget('id_editar_prod');
		return "Producto Modificado";
	}

	/**
	 * [ajax/json]
	 * Filtrar productos por categoria
	 */
	public function postFiltrarprodxcat($idCat = null){
		if($idCat == '0'){
			$productos = \Productos::lista_inventario()->get();
			return \Response::json($productos);
		}
		$productos = \Productos::listaxcategoria($idCat)->get();
		return \Response::json($productos);
	}

	/** 
	 * Muestra el catálogo de productos.
	 */
	public function getCatalogo(){
		$productos = \Productos::lista_inventario()->get();
		$listacategoria = \Categoriaproducto::lista();
		$categoriaproductos = \Categoriaproducto::lists('NombreCategoriaProducto', 'idCategoriaProducto');
		$sel_categoria = ['0' => 'Elegir Categoria:'];
		$sel_categoria += $categoriaproductos;
		$title = 'Catalogo de Productos';
		return \View::make('gestion.productos.catalogo', compact('productos', 'sel_categoria', 'listacategoria', 'title'));
	}

	/**
	*	Muestra las categorías registradas
	*/
	public function getCategoria(){
		$j = 1;
		$listacategoria = \Categoriaproducto::lista();
		$title = 'Categorías';
		return \View::make('gestion.productos.categoria', compact('listacategoria', 'title', 'j'));
	}

	/**
	 *
	 */
	public function postCambimgprod(){
		$post = \Input::all();
		$rule = ['camb_img_file' => 'required'];
		if(\Validator::make($post, $rule)->fails()){
			\Session::flash('mensaje', 'No seleccionaste una imagen');
			return \Redirect::to('gestion/productos');
		}
		$path = 'assets/products_img';
     	$file = \Input::file('camb_img_file'); 
     	// $nombre = $file->getClientOriginalName();
     	$extension  = $file->getClientOriginalExtension();
     	$nuevo_nombre = md5(microtime()).'.'.$extension; 
    	// $tamano = $file->getSize();        	
     	$upload = $file->move($path, $nuevo_nombre);
     	if($upload){
			$productos = \Productos::find($post['idProd']);
			$productos->UrlFotoProducto = $nuevo_nombre;
			$productos->save();
			\Session::flash('mensaje', 'Se actualizó la imagen de producto.');
			return \Redirect::back();
		} else {
			\Session::flash('mensaje', 'No se pudo subir el archivo');
			return \Redirect::back();
		}
	}

}