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
		$productos = \Productos::where('estado', 1)->with('categoriaProducto')->get();
		$categoriaProductos = \Categoriaproducto::lists('NombreCategoriaProducto', 'idCategoriaProducto');
		$sel_categoria = ['0' => 'Elegir Categoria:'];
		$sel_categoria += $categoriaProductos;
		$title = 'Gestión de Productos';
		return \View::make('gestion.productos.index', compact('productos', 'categoriaProductos', 'sel_categoria', 'title'));
	}
	
	/**
	 *
	 */
	public function postRegistrarprod(){
		$bandera = false;
		$inputs = \Input::all();
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
		$nuevoNombre = md5(microtime()).'.'.$extension;
     	$upload = $file->move($path, $nuevoNombre);
     	if($upload){
			$producto = new \Productos();
			$producto->CodBarras = ($inputs['codbarras'] == '')?'':$inputs['codbarras'];
			$producto->NombreProducto = $inputs['producto'];
			$producto->DetallesProducto = $inputs['detalles'];
			$producto->UrlFotoProducto	= $nuevoNombre;
			$producto->idCategoriaProducto	= $inputs['categoria'];
			$producto->estado = 1;
			$producto->save();

			$dataInventario = [
			    'idProducto' => $producto->idProducto,
                'Existencia' => 0,
                'Costo' => 0,
                'PrecDistribuidor' => 0,
                'PrecPublico' => 0,
                'Estado' => 0,
                'Comentario' => ''
            ];

			$dataMovimiento = [
			    'idProductoMovimiento' => $producto->idProducto,
                'CantidadMovimiento' => 0,
                'CostoMovimiento' => 0,
                'TotalCosto' => 0,
                'TipoMovimiento' => 'Inventario Inicial',
                'FechaMovimiento' => $fecha,
                'RegistradoPor' => \Session::get('usuario')
            ];

			\DB::table('inventario_adm')->insert($dataInventario);
			\DB::table('movimientos_adm')->insert($dataMovimiento);

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
		$categoriaProducto = new \Categoriaproducto();
		$categoriaProducto->NombreCategoriaProducto = $inputs['categoria'];
		$categoriaProducto->DescripcionCategoriaProducto = $inputs['descripcion'];
		$categoriaProducto->estado = 1;
		$categoriaProducto->save();
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
		$categoriaProducto = \Categoriaproducto::find($idCategoriaProducto);
		if(is_null($idCategoriaProducto) || empty($categoriaProducto)){
			\Session::flash('ErrorElimCat', 'Error al eliminar categoria');
			return "Error al Eliminar";
		} else {
			$categoriaProducto->estado = 0;
			$categoriaProducto->save();
			return "Categoria eliminada.";
		}		
	}

	/**
	 * [ajax/json]
	 *
	 */
	public function getEditarcategoria($idCategoriaProducto = null){
		\Session::put('id_editar_cat', $idCategoriaProducto);
		$categoriaProducto = \Categoriaproducto::findOrFail($idCategoriaProducto);
		return $categoriaProducto;
	}

	/**
	 * [ajax]
	 *
	 */
	public function postModificarcategoria(){
		if(!\Session::has('id_editar_cat')){
			return 'Error';
		}
        $inputs = \Input::All();
		$id = \Session::get('id_editar_cat');
		$categoriaProducto = \Categoriaproducto::findOrFail($id);
		$categoriaProducto->NombreCategoriaProducto = $inputs['categoria'];
		$categoriaProducto->DescripcionCategoriaProducto= $inputs['descripcion'];
		$categoriaProducto->save();
		\Session::forget('id_editar_cat');
		return "Categoria Modificada";
	}

	/**
	 * [ajax]
	 * 
	 */
	public function postEliminarprod($idProducto = null){
		$producto = \Productos::find($idProducto);
		if(is_null($idProducto) || empty($producto)){
			\Session::flash('ErrorElimProd', 'Error al eliminar producto');
			return "Error al Eliminar";
		} else {
			$producto->estado = 0;
			$producto->save();
			return "Producto eliminado.";
		}		
	}

	/*
	 * [ajax/json]
	 *
	 */
	public function getEditarprod($idProducto = null){
		\Session::put('id_editar_prod', $idProducto);
		$producto = \Productos::findOrFail($idProducto);
		return $producto;
	}

	/**
	 * [ajax]
	 *
	 */
	public function postModificarprod(){
		if(!\Session::has('id_editar_prod')){
			return 'Error';
		}
        $inputs = \Input::All();
		$producto = \Productos::find(\Session::get('id_editar_prod'));
		$producto->NombreProducto = $inputs['producto'];
		$producto->DetallesProducto = $inputs['detalles'];
		$producto->idCategoriaProducto	= $inputs['categoria'];
		$producto->save();
		\Session::forget('id_editar_prod');
		return "Producto Modificado";
	}

	/**
	 * [ajax/json]
	 * Filtrar productos por categoria
	 */
	public function getFiltrarprodxcat($idCat = null){
		if($idCat == '0'){
			$productos = \Productos::listaInventario()->get();
			return $productos;
		}
        $categoriaProducto = \Categoriaproducto::findOrFail($idCat);
		$productos = $categoriaProducto->productos()->listaInventario()->get();
		return $productos;
	}

	/** 
	 * Muestra el catálogo de productos.
	 */
	public function getCatalogo(){
		$productos = \Productos::listaInventario()->get();
		$listacategoria = \Categoriaproducto::where('Estado', 1)->get();
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
		$categorias = \Categoriaproducto::where('Estado', 1)->get();
		$title = 'Categorías';
		return \View::make('gestion.productos.categoria', compact('categorias', 'title'));
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
     	$nuevoNombre = md5(microtime()).'.'.$extension;
    	// $tamano = $file->getSize();        	
     	$upload = $file->move($path, $nuevoNombre);
     	if($upload){
			$productos = \Productos::find($post['idProd']);
			$productos->UrlFotoProducto = $nuevoNombre;
			$productos->save();
			\Session::flash('mensaje', 'Se actualizó la imagen de producto.');
			return \Redirect::back();
		} else {
			\Session::flash('mensaje', 'No se pudo subir el archivo');
			return \Redirect::back();
		}
	}

}
