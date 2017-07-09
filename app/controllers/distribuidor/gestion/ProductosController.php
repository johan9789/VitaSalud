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
        $productosDist = \DistProductos::where('EstadoProductoDist', 1)->with('categoria')->get();
        $listacategoria = ['0' => 'Elegir categoría'] + \DistCategoria::lists('NombreCategoriaProductoDist', 'idCategoriaProductoDist');
        return \View::make('distribuidor.productos.index', compact('title', 'productosDist', 'listacategoria'));
    }

    /**
     * [AJAX:POST]
     * Registro de Productos del Distribuidor
     */
    public function postRegistrarProducto(){
        $error = false;

        $idDistribuidor = \Session::get('id_dist');

        $inputs = \Input::all();
        $codBarrasDist = $inputs['codbarras'];
        $nombreProductoDist = $inputs['producto'];
        $detallesProductoDist = $inputs['detalles'];
        $urlFotoProductoDist = 'assets/products_dist_img/product-default.png';
        $idCategoriaProductoDist = $inputs['categoria'];

        $idUltimoProducto = null;

        if(empty($codBarrasDist) || empty($nombreProductoDist) || empty($idCategoriaProductoDist)){
            $error = true;
        } else {
            $codONombreProducto = \DistProductos::whereRaw('CodBarrasDist = ? OR NombreProductoDist = ?',
                                                                [$codBarrasDist, $nombreProductoDist])->get();
            $categoriaProducto = \DistCategoria::whereRaw('idCategoriaProductoDist = ? AND Estado = ?',
                                                            [$idCategoriaProductoDist, 1])->get();
            // Verificamos si ya existe el codBarras o NombreProducto
            if($codONombreProducto->count() > 0 || $categoriaProducto->count() == 0){
                $error = true;
            } else {
                $producto = new \DistProductos();
                $producto->CodBarrasDist = $codBarrasDist;
                $producto->NombreProductoDist = $nombreProductoDist;
                $producto->DetallesProductoDist = $detallesProductoDist;
                $producto->UrlFotoProductoDist = $urlFotoProductoDist;
                $producto->EstadoProductoDist = 1;
                $producto->idCategoriaProductoDist = $idCategoriaProductoDist;
                $producto->PerteneceA = 'D';
                $producto->idDistribuidorRef = $idDistribuidor;
                $producto->save();

                // Recuperamos el id del registro ingresado
                $idUltimoProducto = $producto->idProductoDist;

                $dataInventario = [
                    'Existencia' => 0,
                    'Costo' => 0,
                    'PrecPublico' => 0,
                    'Estado' => 0,
                    'Comentario' => '',
                    'idDistribuidor' => $idDistribuidor,
                    'idProductoDist' => $idUltimoProducto
                ];

                $dataMovimiento = [
                    'CantidadMovimiento' => 0,
                    'CostoMovimiento' => 0,
                    'TotalCosto' => 0,
                    'TipoMovimiento' => 'Inventario Inicial',
                    'FechaMovimiento' => date('Y-m-d'),
                    'RegistradoPor' => \Session::get('usuario'),
                    'idDistribuidor' => $idDistribuidor,
                    'idProductoDist' => $idUltimoProducto
                ];

                \DistInventario::insert($dataInventario);
                \DistMovimientos::insert($dataMovimiento);
			}
		}

		$idRespuesta = (is_null($idUltimoProducto)) ? '' : md5($idUltimoProducto);

        // 0: si hubo error, 1: no hubo error
		if($error){
			$resultado = ['errors' => '0', 'mensaje' => 'Error al registrar, intente de nuevo', 'id' => $idRespuesta];
            return $resultado;
        }

        $resultado = ['errors' => '1', 'mensaje' => 'Registro Actualizado', 'id' => $idRespuesta];
        return $resultado;
    }

    public function postUploadImgproducto(){
        $error = false;

        $path = 'assets/products_dist_img';
        $file = \Input::file('img_file');

        $idProducto = \Input::get('id_prod_dist_ult');

        if(empty($file) || empty($idProducto)){
            $error = true;
        } else {
            $tamanoImagen = number_format($file->getSize()/1024/1024, 2);
            $extensionImagen = strtolower($file->getClientOriginalExtension());
            $nombreImagen = md5(microtime()).'.'.$extensionImagen;
            $urlImagen = $path.'/'.$nombreImagen;

			$extensionesPermitidas = ["gif", "jpg", "png", "jpeg"];

            if($tamanoImagen > 3.10 || $tamanoImagen == 0.00){
                $error = true;
            } else {
                foreach($extensionesPermitidas as $extension){
                    if($extension == $extensionImagen){
                        $error = false;
                        break;
                    } else {
                        $error = true;
                    }
                }
                if($error == false){
                    $producto = \DistProductos::whereRaw('MD5(idProductoDist) = ?', [$idProducto])->first();
                    if(!$producto){
                        $error = true;
                    } else {
                        $upload = $file->move($path, $nombreImagen);
                        if($upload){
                            $producto->UrlFotoProductoDist = $urlImagen;
                            $producto->save();
                        } else {
                            $error = true;
                        }
                    }
                }
            }
        }

        if($error){
            return ['error' => true];
        }
        return ['success' => true];
    }

    public function postEliminarProducto($id = null){
        $producto = \DistProductos::whereRaw('MD5(idProductoDist) = ? and idDistribuidorRef = ?', [$id, \Session::get('id_dist')])->first();
        if(!$producto){
            return ['error' => true];
        } else {
            $producto->EstadoProductoDist = 0;
            $producto->save();
            return ['success' => true];
        }
    }

}
