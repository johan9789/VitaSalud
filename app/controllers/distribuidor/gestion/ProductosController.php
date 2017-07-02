<?php 
namespace Distribuidor\Gestion;
/**
 * Inventario del Distribuidor
 */
class ProductosController extends \BaseController{
	
	public function __construct(){
		$this->beforeFilter('distribuidor');
	}

	// DistProductos y DistCategoria se encuentran definidos en /app/config/app.php
	public function getIndex(){

		$title = 'Gestión Productos Distribuidor';

		$productosDist = \DistProductos::lista();

		$listacategoria = ['0' => 'Elegir categoría'] + \DistCategoria::lists('NombreCategoriaProductoDist', 'idCategoriaProductoDist');

		return \View::make('distribuidor.productos.index', compact('title', 'productosDist', 'listacategoria'));
	}

	/**
	 * [AJAX:POST]
	 * Registro de Productos del Distribuidor
	 */
	public function postRegistrarProducto(){

		$bandera = false;

		$usu_register = \Session::get('usuario');
		$id_distribuidor = \Session::get('id_dist');
		$fecha = date('Y-m-d');

		$inputs = \Input::all();
		$codBarrasDist = $inputs['codbarras'];
		$nombreProductoDist = $inputs['producto'];
		$detallesProductoDist = $inputs['detalles'];
		$urlFotoProductoDist = 'assets/products_dist_img/product-default.png';
		$idCategoriaProductoDist = $inputs['categoria'];

		$id_ultimo = null;

		if(empty($codBarrasDist) || empty($nombreProductoDist) || empty($idCategoriaProductoDist)){
			$bandera = true;
		}else{

			$exist_codBarrasOnombreProd =  \DistProductos::whereRaw('CodBarrasDist = ? OR NombreProductoDist = ?', 
															[$codBarrasDist, $nombreProductoDist])->get();

			$valid_idCategoria = \DistCategoria::whereRaw('idCategoriaProductoDist = ? AND Estado = ?', 
																[$idCategoriaProductoDist, 1])->get();

			//Verificamos si ya existe el codBarras o NombreProducto
			if(count($exist_codBarrasOnombreProd) > 0 || count($valid_idCategoria) == 0){
				$bandera = true;
			}else{

					$p = new \DistProductos();
					$p->CodBarrasDist = $codBarrasDist;
					$p->NombreProductoDist = $nombreProductoDist;
					$p->DetallesProductoDist = $detallesProductoDist;
					$p->UrlFotoProductoDist = $urlFotoProductoDist;
					$p->EstadoProductoDist = 1;
					$p->idCategoriaProductoDist = $idCategoriaProductoDist;
					$p->PerteneceA = 'D';
					$p->idDistribuidorRef = $id_distribuidor;
					$p->save();

					//Recuperamos el id del registro ingresado
					$id_ultimo = $p->idProductoDist;

					$arr_inventarioDist = [	'Existencia' => 0,
											'Costo' => 0,
											'PrecPublico' => 0,
											'Estado' => 0,
											'Comentario' => '',
											'idDistribuidor' => $id_distribuidor,
											'idProductoDist' => $id_ultimo];

					$arr_movimientosDist = ['CantidadMovimiento' => 0, 
											'CostoMovimiento' => 0, 
											'TotalCosto' => 0, 
											'TipoMovimiento' => 'Inventario Inicial', 
											'FechaMovimiento' => $fecha,
											'RegistradoPor' => $usu_register,
											'idDistribuidor' => $id_distribuidor,
											'idProductoDist' => $id_ultimo];

					\DistInventario::insert($arr_inventarioDist);
					\DistMovimientos::insert($arr_movimientosDist);
			}
		}

		$id_enviar = (is_null($id_ultimo))?'':md5($id_ultimo);

		if($bandera){
			//0: si hubo error, 1: no hubo error
			$resultado = ['errors' => '0', 'mensaje' => 'Error al registrar, intente de nuevo', 'id' => $id_enviar];
			return $resultado;
		}

		//0: si hubo error, 1: no hubo error
		$resultado = ['errors' => '1', 'mensaje' => 'Registro Actualizado', 'id' => $id_enviar];
		return $resultado;

	}

	public function postUploadImgproducto(){
		$bandera = false;

		$path = 'assets/products_dist_img';
		$file = \Input::file('img_file');

		$id_Producto = \Input::get('id_prod_dist_ult');

		if(empty($file) || empty($id_Producto)){
			$bandera = true;
		}else{

			$tamArchivo = number_format($file->getSize()/1024/1024, 2);
			$extension = strtolower($file->getClientOriginalExtension());
			$nombre_img = md5(microtime()).'.'.$extension;
			$urlFotoProductoDist = $path.'/'.$nombre_img;

			$extensiones_permitidas = ["gif", "jpg", "png", "jpeg"];

			if($tamArchivo > 3.10 || $tamArchivo == 0.00){
				$bandera = true;
			}else{

				foreach ($extensiones_permitidas as $key => $value) {
					if($value == $extension){
						$bandera = false;
						break;
					}
					else{
						$bandera = true;
					}
				}

				if($bandera == false){

					$exist_idProducto = \DB::select("Select idProductoDist from producto_distribuidor where MD5(idProductoDist) = '".$id_Producto."'");
					if(count($exist_idProducto) == 0){
						$bandera = true;
					}else{

						$upload = $file->move($path, $nombre_img);
						if($upload){
							
							$p = \DistProductos::find($exist_idProducto[0]->idProductoDist);
							$p->UrlFotoProductoDist = $urlFotoProductoDist;
							$p->save();

						}else{
							$bandera = true;
						}
					}
				}
			}

		}

		if($bandera){
			return '0';
		}

		return '1';	
	}

	public function postEliminarProducto($id = null){
		
		$id_distribuidor = \Session::get('id_dist');

		$prodcutosDist = \DB::select("SELECT idProductoDist FROM producto_distribuidor WHERE MD5(idProductoDist) = '$id' AND idDistribuidorRef = $id_distribuidor");

		if(count($prodcutosDist) == 0){
			return '0';
		}else{
			$p = \DistProductos::find($prodcutosDist[0]->idProductoDist);
			$p->EstadoProductoDist = 0;
			$p->save();
			return '1';
		}

	}

	public function getPrueba(){
		return \DB::select("Select idProductoDist from producto_distribuidor where MD5(idProductoDist) = 'd3d9446802a44259755d38e6d163e820'")[0]->idProductoDist;//\DistProductos::where('MD5(idProductoDist)', '=', 'd3d9446802a44259755d38e6d163e820')->get();
	}

}