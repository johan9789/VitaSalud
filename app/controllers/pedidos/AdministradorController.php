<?php
namespace Pedidos;

/** 
 * Revisión de pedidos por parte del administrador.
 */
class AdministradorController extends \BaseController {

	/**
	 * Validación de la sesión del administrador.
	 */
	public function __construct(){
		$this->beforeFilter('administrador');
	}

	/**
	 * Muestra la lista de pedidos realizados por el distribuidor
	 */
	public function getIndex(){
		$title = 'Pedidos';
		$pedidos_confirmados = \Pedidos::lista(3, 10)->get();	
		$pedidos_pendientes = \Pedidos::lista(2, 2)->get();
		return \View::make('pedidos.adm.index', compact('pedidos_confirmados', 'pedidos_pendientes', 'title'));
	}		

	/** 
	 * [POST | ajax/json]
	 * Devuelve el detalle de un pedido solicitado en formato json.
	 */
	public function postVerDetalle(){
		$pedido = \Input::get('pedido');
		if(!\PedidosProductos::where('idPedido','=', $pedido)->count()){
			return 'Error';
		} else {
			$detalle = \PedidosProductos::detalle($pedido)->get();
			return \Response::json($detalle);
		}		
	}

	/**
	 * [POST | ajax]
	 * Confirma un pedido que quedó pendiente.
	 */
	public function postConfirmarPendiente(){
		$usu_register = \Session::get('usuario');
		$bandera = false;
		$fecha = date('Y-m-d');
		$pedidos = \Pedidos::find(\Input::get('pedido'));
		if(count($pedidos) == 0 || $pedidos->EstadoPedido != 2){
			$bandera = true;
		}
		$pedidos_productos = \PedidosProductos::detalle(\Input::get('pedido'))->get();
		foreach($pedidos_productos as $ped_prod){
			if($ped_prod->Cantidad > $ped_prod->Existencia){
				$bandera = true;
				break;
			}
		}
		if($bandera == true){
			return "Error";
		}
		$j = 0;
		foreach ($pedidos_productos as $ped_prod) {
			$existencia_nueva = $ped_prod->Existencia - $ped_prod->Cantidad;
			$estado = ($existencia_nueva == 0)?0:1;
			$id_producto = $ped_prod->idProducto;
			$precio_total_nuevo = $existencia_nueva * $ped_prod->Precio;
			$salida_nueva = $ped_prod->Salida + $ped_prod->Cantidad;
			\DB::update('update inventario_adm set Existencia = '.$existencia_nueva.', 
												 Estado = '.$estado.' 
												 WHERE idProducto = '.$id_producto.'
												 AND idInventario = (
												 	SELECT idInventario
												 	FROM
												 	(
												 		SELECT idInventario
												 		FROM inventario_adm
												 		WHERE idProducto = '.$id_producto.'
												 		AND Estado = 1
												 		GROUP BY idProducto
												 	) AS id_inventario 
												 	)');

			$importe_movimiento = $ped_prod->Cantidad * $ped_prod->Costo;

			$arreglo_insert_movimiento[$j] = array('idProductoMovimiento' => $ped_prod->idProducto, 
													'CantidadMovimiento' => $ped_prod->Cantidad, 
													'CostoMovimiento' => $ped_prod->Costo, 
													'TotalCosto' => $importe_movimiento, 
													'TipoMovimiento' => 'Salida', 
													'FechaMovimiento' => $fecha,
													'RegistradoPor' => $usu_register,
													'idPedido' => $pedidos->idPedido);
			$precio_total_nuevo_inv_dist	= $ped_prod->Precio*$ped_prod->Cantidad;

			$j++;
		}

		\DB::table('movimientos_adm')->insert($arreglo_insert_movimiento);
		$pedidos->EstadoPedido = 3;
		$pedidos->save();
		return "Pedido Confirmado";
	}

	/**
	 * Muestra un formulario para modificar el pedido seleccionado.
	 */
	public function getModificar($id_pedido = null){
		$ped = \Pedidos::find($id_pedido);
		if(count($ped) == 0){
			return \Redirect::to('pedidos/dist');
		}		

		if($ped->EstadoPedido != 2){
			return \Redirect::to('pedidos/dist');
		}
			
		$totalproductos = \Productos::lista_total();
		$ped_prod = \PedidosProductos::ver_modificar($id_pedido)->get();		
		$prod_sob = \PedidosProductos::prod_sob($id_pedido);

		\Session::put('id_pedido_mod', $id_pedido);
		return \View::make('pedidos.adm.modificar', compact('prod_sob', 'totalproductos', 'ped_prod', 'ped'));
	}

	public function postOcultarPedido(){
		$pedidos = \Pedidos::find(\Input::get('pedido'));
		if($pedidos->EstadoPedido == 10)
			$pedidos->EstadoPedido = 12;
		else
		$pedidos->EstadoPedido = 11;
		$pedidos->save();
		return 'Pedido Ocultado.';
	}

	public function postRechazarYa(){
		$post = \Input::all();

		$id_pedido_rech = $post['id_pedido'];

		date_default_timezone_set('America/Lima');
		$date = date('Y-m-d');
		$dist = \Session::get('id_dist');

		$productos = $post['idprod'];
		$disponible = $post['disponible'];	

		
		/** Rechazo de pedido */
		$pedidos = \Pedidos::find($id_pedido_rech);
		$pedidos->EstadoPedido = 4;
		$pedidos->save();	
	
		for($i=0;$i<count($productos);$i++){
			if($productos[$i] == 0 || empty($productos[$i])){
				continue;
			}
			$cant_disponible = ($disponible[$i] == '')?"":$disponible[$i];
			\DB::update('update pedidos_productos set disponible = "'.$cant_disponible.'" where idPedido = ? and idProducto = ? ', array($id_pedido_rech, $productos[$i]));
			
		}
		return \Redirect::to('pedidos/adm');
	}

}