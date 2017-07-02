<?php
namespace Pedidos;

/** 
 * Pedidos por parte del distribuidor.
 */
class DistController extends \BaseController {

	/**
	 * Validación de la sesión del distribuidor.
	 */
	public function __construct(){
		$this->beforeFilter('distribuidor');
	}

	/**
	 * Muestra la lista de pedidos realizados por el distribuidor
	 */
	public function index(){		
		if(\Input::get('confirmar') == 'despues'){
			\Session::forget('ult_pedido');
			return \Redirect::to('pedidos/dist');
		}
		$title = 'Pedidos';
		$pedidos_confirmados = \Pedidos::whereRaw('idDistribuidor = ? and (EstadoPedido = 2 or EstadoPedido = 3 or EstadoPedido = 11)', [\Session::get('id_dist')])->get();
		$pedidos_pendientes = \Pedidos::whereRaw('idDistribuidor = ? and (EstadoPedido = 1 or EstadoPedido = 4)', [\Session::get('id_dist')])->get();
		return \View::make('pedidos.dist.index', compact('pedidos_confirmados', 'pedidos_pendientes', 'title'));
	}		

	/**
	 * Muestra la página de realización de pedidos por parte del distribuidor
	 */
	public function realizar(){
		$productos = \Productos::lista()->get();
		$totalproductos = count(\Productos::lista_total2());
		$title = 'Realizar pedidos';
		return \View::make('pedidos.dist.realizar', compact('productos', 'totalproductos', 'title'));
	}

	/**
	 * [POST]
	 * Realiza la inserción de pedidos por parte del distribuidor en estado 1.
	 */
	public function realizar_ya(){
		$post = \Input::all();

		date_default_timezone_set('America/Lima');
		$date = date('Y-m-d');
		$dist = \Session::get('id_dist');

		$prod_prod = $post['prod_prod'];
		$cantidad = $post['cantidad'];		

		$total_total = 0;

		for($i=0;$i<count($cantidad);$i++){
			if($cantidad[$i] == 0 || empty($cantidad[$i])){
				continue;
			}
			//$precio = \Productos::find($prod_prod[$i])->PrecDistribuidor;
			$precio = \Inventario::whereRaw('Estado = ? and idProducto = ?', [1, $prod_prod[$i]])->first()->PrecDistribuidor;
			$total_parcial = $precio * $cantidad[$i];
			$total_total += $precio * $cantidad[$i];
		}

		/** Creacion de pedido */
		$pedidos = new \Pedidos();
		$pedidos->idDistribuidor = $dist;
		$pedidos->FechaPedido = $date;
		$pedidos->ValorTotalPedido = $total_total;
		$pedidos->EstadoPedido = 1;
		$pedidos->FechaEntrega = $post['fecha_entrega'];
		$pedidos->save();

		$ult_pedido = \Pedidos::orderBy('idPedido', 'desc')->get()[0]->idPedido;		
	
		for($i=0;$i<count($cantidad);$i++){
			if($cantidad[$i] == 0 || empty($cantidad[$i])){
				continue;
			}
			//$precio = \Productos::find($prod_prod[$i])->PrecioDistribuidor;
			$precio = \Inventario::whereRaw('Estado = ? and idProducto = ?', [1, $prod_prod[$i]])->first()->PrecDistribuidor;
			$total_parcial = $precio * $cantidad[$i];
			/** Inserción múltiple del detalle de pedidos */
			$pedidos_productos = new \PedidosProductos();
			$pedidos_productos->idPedido = $ult_pedido;
			$pedidos_productos->idProducto = $prod_prod[$i];
			$pedidos_productos->Cantidad = $cantidad[$i];
			$pedidos_productos->Valor = $total_parcial;
			$pedidos_productos->Comentarios = '';
			$pedidos_productos->disponible = '';
			$pedidos_productos->save();
		}

		\Session::put('ult_pedido', $ult_pedido);
		return \Redirect::to('pedidos/dist/confirmar');
	}

	/** 
	 * Muestra la página para confirmar el pedido realizado recientemente
	 */
	public function confirmar(){
		if(!\Session::has('ult_pedido')){
			return \Redirect::to('pedidos/dist');
		}
		$pedidos_productos = \PedidosProductos::ultimo_conf(\Session::get('ult_pedido'))->get();
		return \View::make('pedidos.dist.confirmar', compact('pedidos_productos'));
	}

	/**
	 * [POST]
	 * Realizar la confirmación del último pedido
	 */
	public function confirmar_ya(){
		if(!\Session::has('ult_pedido')){
			return \Redirect::to('pedidos/dist');
		}
		/** */
		$pedido = \Pedidos::find(\Session::get('ult_pedido'));
		$pedido->EstadoPedido = 2;
		$pedido->save();
		/** */
		\Session::forget('ult_pedido');
		\Session::flash('mensaje', 'Pedido confirmado.');
		/** */
		return \Redirect::to('pedidos/dist');
	}

	/** 
	 * [POST | ajax/json]
	 * Devuelve el detalle de un pedido solicitado en formato json
	 */
	public function ver_detalle(){
		$detalle = \PedidosProductos::detalle(\Input::get('pedido'))->get();
		return \Response::json($detalle);
	}

	/**
	 * [POST | ajax]
	 * Confirma un pedido que quedó pendiente por parte del distribuidor
	 */
	public function confirmar_pendiente(){
		$pedidos = \Pedidos::find(\Input::get('pedido'));
		if(\Session::get('id_dist') != $pedidos->idDistribuidor){
			return 'Error este pedido no pertenece a este dist';	
		}
		$pedidos->EstadoPedido = 2;
		$pedidos->save();
		return 'Pedido confirmado.';
	}

	/**
	 * Ver formulario para modificar el pedido seleccionado.
	 */
	public function modificar($id_pedido = null){
		$ped = \Pedidos::find($id_pedido);
		if(count($ped) == 0){
			return \Redirect::to('pedidos/dist');
		}

		if(\Session::get('id_dist') != $ped->idDistribuidor){
			return \Redirect::to('pedidos/dist');	
		}

		if($ped->EstadoPedido != 1 && $ped->EstadoPedido != 4){
			return \Redirect::to('pedidos/dist');
		}
			
		$totalproductos = \Productos::lista_total2();
		$ped_prod = \PedidosProductos::ver_modificar($id_pedido)->get();		
		$prod_sob = \PedidosProductos::prod_sob($id_pedido);

		\Session::put('id_pedido_mod', $id_pedido);
		return \View::make('pedidos.dist.modificar', compact('prod_sob', 'totalproductos', 'ped_prod', 'ped'));
	}

	/**
	 * [POST]
	 * Realiza la modificación del pedido seleccionado.
	 */
	public function modificar_ya(){
		$id_pedido_mod = \Session::get('id_pedido_mod');

		$post = \Input::all();

		date_default_timezone_set('America/Lima');
		$date = date('Y-m-d');
		$dist = \Session::get('id_dist');

		$prod_prod = $post['prod_prod'];
		$cantidad = $post['cantidad'];	
		$listo = $post['listo'];	

		$total_total = 0;

		for($i=0;$i<count($cantidad);$i++){
			if($cantidad[$i] == 0 || empty($cantidad[$i])){
				continue;
			}
			//$precio = \Productos::find($prod_prod[$i])->PrecioDistribuidor;
			$precio = \Inventario::whereRaw('Estado = ? and idProducto = ?', [1, $prod_prod[$i]])->first()->PrecDistribuidor;
			$total_parcial = $precio * $cantidad[$i];
			$total_total += $precio * $cantidad[$i];
		}

		/** Creacion de pedido */
		$pedidos = \Pedidos::find($id_pedido_mod);
		$pedidos->idDistribuidor = $dist;
		$pedidos->FechaPedido = $date;
		$pedidos->ValorTotalPedido = $total_total;
		$pedidos->EstadoPedido = 1;
		$pedidos->FechaEntrega = $post['fecha_entrega'];
		$pedidos->save();

		$ult_pedido = \Pedidos::orderBy('idPedido', 'desc')->get()[0]->idPedido;		
	
		for($i=0;$i<count($cantidad);$i++){
			if($cantidad[$i] == 0 || empty($cantidad[$i])){
				continue;
			}
			//$precio = \Productos::find($prod_prod[$i])->PrecioDistribuidor;
			$precio = \Inventario::whereRaw('Estado = ? and idProducto = ?', [1, $prod_prod[$i]])->first()->PrecDistribuidor;
			$total_parcial = $precio * $cantidad[$i];
			if($listo[$i] == 'ya'){
				\DB::update('update pedidos_productos set Cantidad = '.$cantidad[$i].', Valor = '.$total_parcial.' where idPedido = ? and idProducto = ? ', array($id_pedido_mod, $prod_prod[$i]));
			} else {
				//$precio = \Productos::find($prod_prod[$i])->PrecioDistribuidor;
				$precio = \Inventario::whereRaw('Estado = ? and idProducto = ?', [1, $prod_prod[$i]])->first()->PrecDistribuidor;
				$total_parcial = $precio * $cantidad[$i];
				/** Inserción múltiple del detalle de pedidos */
				$pedidos_productos = new \PedidosProductos();
				$pedidos_productos->idPedido = $id_pedido_mod;
				$pedidos_productos->idProducto = $prod_prod[$i];
				$pedidos_productos->Cantidad = $cantidad[$i];
				$pedidos_productos->Valor = $total_parcial;
				$pedidos_productos->Comentarios = '';
				$pedidos_productos->save();
			}
		}
		return \Redirect::to('pedidos/dist');
	}

	/**
	 * [POST | ajax]
	 * Elimina un pedido pendiente por parte del distribuidor.
	 */
	public function eliminar_pendiente(){
		$pedidos = \Pedidos::find(\Input::get('pedido'));
		$pedidos->EstadoPedido = 0;
		$pedidos->save();
		return 'Pedido eliminado.';
	}

	/**
	 * [POST | ajax]
	 * Agrega los pedidos confirmados al inventario.
	 */
	public function agregar_inventario(){
		$id_pedido = \Input::get('pedido');
		$pedidos = \Pedidos::find($id_pedido);
		$mensaje = '';
		$fecha = date('Y-m-d');
		$usu_register = \Session::get('usuario');

		$id_Distribuidor = \Session::get('id_dist');

		if($id_Distribuidor != $pedidos->idDistribuidor){
			return 'Error este pedido no pertenece a este dist';	
		}
		if($pedidos->EstadoPedido != 3 && $pedidos->EstadoPedido != 11){
			return ' Error no se puede agregar a inventario';	
		}

		$prods_no_inventario = \PedidosProductos::where('idPedido', '=', $id_pedido)->get();

		$arreglo_insert_mov1 = array();
		
		if(count($prods_no_inventario) != 0){
			$arreglo_insert = array();
			$j = 0;
			foreach ($prods_no_inventario as $key => $value) {
				$costo = $value->Valor/$value->Cantidad;
				$arreglo_insert[$j] = array('Existencia' => $value->Cantidad,
									 		'Costo' => $costo,
									 		'PrecPublico' => $costo*0.25+$costo,
									 		'Estado' => 1,
									 		'idProducto' => $value->idProducto,
									 		'idDistribuidor' => $id_Distribuidor);

				$arreglo_insert_mov1[$j] = array('CantidadMovimiento' => $value->Cantidad,
									 			 'CostoMovimiento' => $costo,
									 			 'TotalCosto' => $value->Valor,
									 			 'TipoMovimiento' => 'Entrada',
									 			 'FechaMovimiento' => $fecha,
									 			 'RegistradoPor' => $usu_register,
									 			 'idProducto' => $value->idProducto,
									 			 'idDistribuidor' => $id_Distribuidor,
									 			 'idPedido' => $value->idPedido);

				$j++;
			}
			\DB::table('inventario_distribuidor')->insert($arreglo_insert);
			$mensaje .= ' Inventario Registrado';
		}

		\DB::table('movimientos_distribuidor')->insert($arreglo_insert_mov1);

		$pedidos->AgregadoInvDist = 'si';
		$pedidos->save();

		return $mensaje;
	}

	/**
	 * [POST | ajax]
	 * Oculta el pedido confirmado y agregado a inventario.
	 */
	public function ocultar_pedido(){
		$pedidos = \Pedidos::find(\Input::get('pedido'));
		if(\Session::get('id_dist') != $pedidos->idDistribuidor){
			return 'Error este pedido no pertenece a este dist';	
		}
		if($pedidos->EstadoPedido == 11){
			$pedidos->EstadoPedido = 12;
		} else {
			$pedidos->EstadoPedido = 10;
		}
		$pedidos->save();
		return 'Pedido Ocultado.';
	}

}