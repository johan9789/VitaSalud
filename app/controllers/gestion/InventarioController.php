<?php
namespace Gestion;

use BaseController;
use Inventario, Productos, Categoriaproducto, Porcentaje, MovimientosAdministrador;
use Redirect, View, Session, Input, DB;

class InventarioController extends BaseController {

	public function __construct(){		
		$this->beforeFilter('administrador');
	}

	public function getIndex(){
		$listaInventario = Inventario::listaStock()->get();
		$prodRecientes = Inventario::prodRecientes()->get();
		if(count($listaInventario) == 0){
			$aviso = (count($prodRecientes) > 1) ? "Productos" : "Producto";
		} else {
			$aviso = (count($prodRecientes) > 1) ? "Productos Nuevos" : "Producto Nuevo";
		}
		$title = 'Gestión de inventario';
		return View::make('gestion.inventario.index', compact('listaInventario', 'prodRecientes', 'aviso', 'title'));
	}

	public function getInventarioinicial(){
		$inventarios = Inventario::join('productos', 'inventario_adm.idProducto', '=', 'productos.idProducto')
                                ->where('productos.estado', 1)->get();

		if(count($inventarios) == 0){
            $productos = Productos::select('idProducto')->where('estado', 1)->get();

			$dataInventario = [];
			$dataMovimiento = [];

			$i = 0;
			foreach($productos as $producto){
				$dataInventario[$i] = [
				    'idProducto' => $producto->idProducto,
                    'Existencia' => 0,
                    'Costo' => 0,
                    'PrecDistribuidor' => 0,
                    'PrecPublico' => 0,
                    'Estado' => 0,
                    'Comentario' => ''
                ];
				$dataMovimiento[$i] = [
				    'idProductoMovimiento' => $producto->idProducto,
                    'CantidadMovimiento' => 0,
                    'CostoMovimiento' => 0,
                    'TotalCosto' => 0,
                    'TipoMovimiento' => 'Inventario Inicial',
                    'FechaMovimiento' => date('Y-m-d'),
                    'RegistradoPor' => Session::get('usuario')
                ];
				$i++;
			}

			DB::table('inventario_adm')->insert($dataInventario);
			DB::table('movimientos_adm')->insert($dataMovimiento);
			return Redirect::to('gestion/inventario');
		} else {
			return Redirect::to('gestion/inventario');
		}
	}

	public function getRegprodreciente(){
		$productos = Inventario::prodRecientes()->get();

		if(count($productos) != 0){
			$dataInventario = [];
			$dataMovimiento = [];

			$i = 0;
			foreach($productos as $producto){
				$dataInventario[$i] = [
				    'idProducto' => $producto->idProducto,
                    'Existencia' => 0,
                    'Costo' => 0,
                    'PrecDistribuidor' => 0,
                    'PrecPublico' => 0,
                    'Estado' => 0,
                    'Comentario' => ''
                ];
				$dataMovimiento[$i] = [
				    'idProductoMovimiento' => $producto->idProducto,
                    'CantidadMovimiento' => 0,
                    'CostoMovimiento' => 0,
                    'TotalCosto' => 0,
                    'TipoMovimiento' => 'Inventario Inicial',
                    'FechaMovimiento' => date('Y-m-d'),
                    'RegistradoPor' => Session::get('usuario')
                ];
				$i++;
			}

			DB::table('inventario_adm')->insert($dataInventario);
			DB::table('movimientos_adm')->insert($dataMovimiento);
			return Redirect::to('gestion/inventario');
		} else {
			return Redirect::to('gestion/inventario');
		}
	}

	public function getRegistroentrada(){
		$categorias = Categoriaproducto::has('productos')->with('productos')->get();
		$title = 'Gestión de inventario - Entradas';
		return View::make('gestion.inventario.registroentrada', compact('matriz', 'title', 'categorias'));
	}

	public function postActualizarentradas(){
		$cantErrores = 0;
		$mensajeError = 'Error; No se pudo realizar el envío: ';

		$fecha = date('Y-m-d');
		$inputs = Input::all();

		$productos = $inputs['sel_prod_inv'];
		$precios = $inputs['precio'];
		$entradas = $inputs['entrada'];
		
		$dataInventario = [];
		$dataMovimiento = [];

		$j = 0;

		for($i=0;$i<count($productos);$i++){
			if($productos[$i] == '0' || $precios[$i] == '' || $entradas[$i] == ''){
				$cantErrores++;
				$mensajeError .= '<br>* Verifique haber completado todos los campos correctamente';
            } else {
				$mensajeError .= '';
			}
			if(!is_numeric($precios[$i])){
				$cantErrores++;
				$mensajeError .= '<br>* Campo precio debe ser numérico';
			} else {
				$mensajeError .= '';
			}

			if($precios[$i] == '0'){
				$cantErrores++;
				$mensajeError .= '<br>* Campo precio debe ser mayor a 0';
			} else {
                $mensajeError .= '';
            }

			if(!ctype_digit($entradas[$i])){
				$cantErrores++;
				$mensajeError .= '<br>* Campo entrada solo debe ser número entero y mayor a 0';
			} else {
				$mensajeError .= '';
			}
			if($entradas[$i] == '0'){
				$cantErrores++;
				$mensajeError .= '<br>* Campo entrada debe ser mayor a 0';
			} else {
                $mensajeError .= '';
            }

			if($cantErrores > 0){
				Session::flash('mensaje_error', $mensajeError);
				return 'error_registro';
			}

			$porcentaje = Porcentaje::first();
			$distPorcentaje = $porcentaje->DistPorcentaje;
			$pubPorcentaje = $porcentaje->PubPorcentaje;

			$dataInventario[$j] = [
			    'idProducto' => $productos[$i],
                'Existencia' => $entradas[$i],
                'Costo' => $precios[$i],
                'PrecDistribuidor' => $precios[$i] + $precios[$i] * $distPorcentaje,
                'PrecPublico' => $precios[$i] + $precios[$i] * $pubPorcentaje,
                'Estado' => 1,
                'Comentario' => ''
            ];

			$dataMovimiento[$j] = [
			    'idProductoMovimiento' => $productos[$i],
                'CantidadMovimiento' => $entradas[$i],
                'CostoMovimiento' => $precios[$i],
                'TotalCosto' => $precios[$i] * $entradas[$i],
                'TipoMovimiento' => 'Entrada',
                'FechaMovimiento' => $fecha,
                'RegistradoPor' => Session::get('usuario')
            ];
			$j++;
		}

		DB::table('inventario_adm')->insert($dataInventario);
		DB::table('movimientos_adm')->insert($dataMovimiento);

		Session::flash('mensaje_entradas', 'Entradas Registradas');
		return 'Entradas Registradas Correctamente';
	}

	// Nos sirve para calcular las entradas y salidas correspondientes
	public function getFiltrarinventario($id=null){
		$result = ['idProducto' => 0];
		if($id == null || $id == '0'){
			return $result;
		}

		$producto = Productos::where('idProducto', $id)->with(['inventarios' => function($q){
		    $q->orderBy('idInventario', 'desc');
        }])->get()->first();
		return $producto->inventarios->first();
	}

	// Esta función devuelve los datos de un producto seleccionado en el kardex
	public function getFiltrardatos($id = null){
		$arreglo = ['idProducto' => 0];
		if($id == null || $id == '0'){
			return $arreglo;
		}
		return Productos::findOrFail($id);
	}

	public function getMovimientos($op = null){
		$anho = date('Y');
		$mes = date('m');

		if($op == null){
			/**
             * Nos servirá para determinar que meses son los que se están evaluando y para colocar en la vista
             * que button debe ser active
             */
			$i = 3;
			$k = 0;

			while($i<= 12){
				if($mes <= $i){
                    break;
                } else {
                    $k++;
                }
				$i = $i+3;
			}
		} else {
			$k = $op;
		}
		
		$trimestres = ['Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho];

		switch($k){
			case '0':
				$inicio = '-01-01';
				$fin 	= '-03-31';
				break;
			case '1':
				$inicio = '-04-01';
				$fin 	= '-06-30';
				break;
			case '2':
				$inicio = '-07-01';
				$fin 	= '-09-30';
				break;
			case '3':
				$inicio = '-10-01';
				$fin 	= '-12-31';
				break;
			default:
				$inicio = '-01-01';
				$fin 	= '-12-31';
				break;
		}

		$fecha_1 = $anho.$inicio;
		$fecha_2 = $anho.$fin;

		$movimientos = MovimientosAdministrador::entreFechas($fecha_1, $fecha_2)->get();

		$fechas_ini = explode('-', $fecha_1);
		$fechas_fin = explode('-', $fecha_2);

		$valFecha_ini = $fechas_ini[2].'/'.$fechas_ini[1].'/'.$fechas_ini[0];
		$valFecha_fin = $fechas_fin[2].'/'.$fechas_fin[1].'/'.$fechas_fin[0];
		
		$title = "Gestion de inventario - Movimientos <small>{$anho}</small>";

		return View::make('gestion.inventario.movimientos', compact('movimientos', 'anho', 'trimestres', 'k', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin', 'title'));
	}

	public function postMovimientos(){
		$anho = date('Y');

		$trimestres = ['Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho];

		$k = '-1';

		$bandera = false;

		$fechas = explode(' - ', Input::get('fechas'));
		$subfechas_ini = explode('/', $fechas[0]);
		$subfechas_fin = explode('/', $fechas[1]);

		$fecha_1 = $subfechas_ini[2].'-'.$subfechas_ini[1].'-'.$subfechas_ini[0];
		$fecha_2 = $subfechas_fin[2].'-'.$subfechas_fin[1].'-'.$subfechas_fin[0];

        $movimientos = MovimientosAdministrador::entreFechas($fecha_1, $fecha_2)->get();

		$valFecha_ini = $subfechas_ini[0].'-'.$subfechas_ini[1].'-'.$subfechas_ini[2];
		$valFecha_fin = $subfechas_fin[0].'-'.$subfechas_fin[1].'-'.$subfechas_fin[2];
		
		return View::make('gestion.inventario.movimientos', compact('movimientos', 'anho', 'trimestres', 'k', 'bandera', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin'));
	}

	/** 
	 * [GET | ajax/json]
	 * Devuelve el detalle de un producto en inventario en formato json
	 */
	public function getDetalleInventario($id = null){
        $detalleInventario = Inventario::select('*', DB::raw('MD5(idInventario) AS idInventario, MD5(idProducto) AS idProducto'))
                                ->where('Estado', 1)->whereRaw('MD5(idProducto) = ?', [$id])->get();
		return $detalleInventario;
	}

	public function getKardex($op = null){
		$anho = date('Y');
		$mes = date('m');

		if($op == null){
			/**
             * Nos servirá para determinar que meses son los que se están evaluando y para colocar en la vista
             * que button debe ser active
             */
			$i = 3;
			$k = 0;
			while( $i<= 12){
				if($mes <= $i){
                    break;
                } else {
                    $k++;
                }
				$i = $i+3;
			}
		} else {
			$k = $op;
		}
		
		$trimestres = ['Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho];

		switch($k){
			case '0':
				$inicio = '-01-01';
				$fin 	= '-03-31';
				break;
			case '1':
				$inicio = '-04-01';
				$fin 	= '-06-30';
				break;
			case '2':
				$inicio = '-07-01';
				$fin 	= '-09-30';
				break;
			case '3':
				$inicio = '-10-01';
				$fin 	= '-12-31';
				break;
			default:
				$inicio = '-01-01';
				$fin 	= '-12-31';
				break;
		}

		$fecha_1 = $anho.$inicio;
		$fecha_2 = $anho.$fin;

        $kardex = MovimientosAdministrador::entreFechas($fecha_1, $fecha_2)->get();

		$j = 1;

		$fechas_ini = explode('-', $fecha_1);
		$fechas_fin = explode('-', $fecha_2);

		$valFecha_ini = $fechas_ini[2].'/'.$fechas_ini[1].'/'.$fechas_ini[0];
		$valFecha_fin = $fechas_fin[2].'/'.$fechas_fin[1].'/'.$fechas_fin[0];
		
		$title = "Gestion de inventario - Kardex <small>{$anho}</small>";

        $categorias = Categoriaproducto::has('productos')->with('productos')->get();

		return View::make('gestion.inventario.kardex', compact('kardex', 'j', 'anho', 'trimestres', 'k', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin', 'categorias', 'title'));
	}

	/** 
	 * [POST | ajax/json]
	 * Actualizar cada fila de un detalle de un producto en inventario
	 */
	public function postActualizarDetalle(){
		$usuario = Session::get('usuario');
		$bandera = false;

		$inputs = Input::all();
		$idInventario = $inputs['idI'];
		$idProducto = $inputs['idP'];
		$precioDistribuidor = $inputs['precD'];
		$precioPublico = $inputs['precP'];
		$numeroCampos = $inputs['num_c'];

		$registroInventario = Inventario::whereRaw('Estado = ? AND MD5(idInventario) = ? AND MD5(idProducto) = ?', [1, $idInventario, $idProducto])->get();

		if(count($registroInventario) == 1){
			if(empty($precioDistribuidor) && empty($precioPublico)){
				$bandera = true;
			} else if($numeroCampos == '2'){
				if(is_numeric($precioDistribuidor) && is_numeric($precioPublico)){
					// Actualizamos tabla con ambos precios
					Inventario::whereRaw('MD5(idInventario) = ? AND MD5(idProducto) = ?', [$idInventario, $idProducto])
								->update([
								    'PrecDistribuidor' => $precioDistribuidor,
                                    'PrecPublico' => $precioPublico,
                                    'Comentario' => 'Precio Distribuidor y Público; actualizado por '.$usuario
                                ]);
				} else {
					$bandera = true;
				}
			} else if($numeroCampos == '1'){
				if(is_numeric($precioDistribuidor)){
					// Actualizamos tabla solo precio distribuidor
					Inventario::whereRaw('MD5(idInventario) = ? AND MD5(idProducto) = ?', [$idInventario, $idProducto])
								->update([
								    'PrecDistribuidor' => $precioDistribuidor,
                                    'Comentario' => 'Precio Distribuidor; actualizado por '.$usuario
                                ]);
				} else {
					$bandera = true;
				}
			} else if($numeroCampos == '3'){
				if(is_numeric($precioPublico)){
					// Actualizamos tabla solo precio público
					Inventario::whereRaw('MD5(idInventario) = ? AND MD5(idProducto) = ?', [$idInventario, $idProducto])
								->update([
								    'PrecPublico' => $precioPublico,
                                    'Comentario' => 'Precio Público; actualizado por '.$usuario
                                ]);
				} else {
					$bandera = true;
				}
            } else {
				$bandera = true;
			}
        } else {
			$bandera = true;
		}

		if($bandera){
			// 0: si hubo error, 1: no hubo error
			$resultado = ['errors' => '0', 'mensaje' => 'Error al actualizar, intente de nuevo'];
			return $resultado;
		}

		// 0: si hubo error, 1: no hubo error
		$resultado = ['errors' => '1', 'mensaje' => 'Registro Actualizado'];
		return $resultado;
	}

	public function postAgruparExistencia(){
		$usuario = Session::get('usuario');
		$bandera = false;
		$inputs = Input::all();
		$idProducto = $inputs['hd_id'];
		$nuevoCosto = $inputs['txt_prec_costo'];
		$precioDistribuidor = $inputs['txt_prec_dist'];
		$precioPublico = $inputs['txt_prec_pub'];

		$inventarios = Inventario::whereRaw('Estado = ? AND MD5(idProducto) = ?', [1, $idProducto]);

		$inventarioProducto = $inventarios->get();
		if(count($inventarioProducto) > 1){
			$totalExistencia = $inventarios->sum('Existencia');
			$inventarios->update([
			    'Estado' => 0,
                'Comentario' => 'Registro Actualizado por '.$usuario.' se dio agrupamiento de existencias'
            ]);

			$nuevo_inventario = new Inventario();
			$nuevo_inventario->idProducto = $inventarioProducto[0]->idProducto;
			$nuevo_inventario->Existencia = $totalExistencia;
			$nuevo_inventario->Costo = $nuevoCosto;
			$nuevo_inventario->PrecDistribuidor = $precioDistribuidor;
			$nuevo_inventario->PrecPublico = $precioPublico;
			$nuevo_inventario->Estado = 1;
			$nuevo_inventario->Comentario = 'Agregado por '.$usuario.' despúes de haber agrupado existencias anteriores';
			$nuevo_inventario->save();

		} else {
			$bandera = true;
		}

		if($bandera){
			// 0: si hubo error, 1: no hubo error
			$resultado = ['errors' => '0', 'mensaje' => 'Error al actualizar, intente de nuevo'];
			return $resultado;
		}

		// 0: si hubo error, 1: no hubo error
		$resultado = ['errors' => '1', 'mensaje' => 'Registro Actualizado'];
		return $resultado;
	}

}
