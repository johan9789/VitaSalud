<?php
namespace Ventas;

use Cliente, Empresa, Distrito, Productos, Venta, VentaDetalle;
use Impuesto, MovimientosDistribuidor, InventarioDistribuidor;
use ProductoDistribuidor;
use View, Input, Session, DB, Response;

/**
 * Realiza la venta de los productos disponibles en el inventario del administrador.
 */
class HomeController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('distribuidor');
	}

	/**
	 * Página inicial, muestra los productos a vender.
	 */
	public function getIndex(){
		$title = 'Ventas';
		$listaClientes = Cliente::lista()->get();
		$listaEmpresas = Empresa::lista()->get();
		$distritos = Distrito::lista()->lists('NombreDistrito', 'iddistrito');
		return View::make('ventas.index', compact('title', 'listaEmpresas', 'listaClientes', 'distritos'));
	}

	/**
	 * Registra una venta realizada.
	 */
	public function postIndex(){
		date_default_timezone_set('America/Lima');
		$all = Input::all();

		$codigos = $all['codigo'];
		$cantidad = $all['cantidad'];
		$precios = $all['precio'];

		$total_final = 0;
		for($i=0;$i<count($codigos);$i++){
			if(empty($codigos[$i]) || $codigos[$i] == 0){
				continue;
			}
			$total_parcial = $cantidad[$i] * $precios[$i];
			$total_final += $cantidad[$i] * $precios[$i];
		}		

		$venta = new Venta();
		$venta->Fecha = date('Y-m-d H:i:s');
		$venta->Tipo = $all['tipo'];
		$venta->Sub_Total = $total_final;
		$venta->Monto_IGV = Impuesto::getIGV() * $total_final;
		$venta->Total = $total_final + (Impuesto::getIGV() * $total_final);
		$venta->Correlativo = '';
		$venta->idimpuesto = 1;
		$venta->idDistribuidor = Session::get('id_dist');
		$venta->idCliente = $all['cliente'];
		$venta->save();

		$venta_realizada = $venta->idventa;

		$len = strlen($venta_realizada);
		$len = (11 - $len);
		$correlativo = '';
		for($i=1;$i<=$len;$i++){
			$correlativo.= '0';
		}
		$correlativo.= $venta_realizada.$all['tipo'];

		$upd_venta = Venta::find($venta_realizada);
		$upd_venta->Correlativo = $correlativo;
		$upd_venta->save();		

		$ids_prod = [];
		$cant_prod = [];

		for($i=0;$i<count($codigos);$i++){
			if(empty($codigos[$i]) || $codigos[$i] == 0){
				continue;
			}
			$venta_detalle = new VentaDetalle();
			$venta_detalle->idventa = $venta_realizada;
			$venta_detalle->Cantidad = $cantidad[$i];
			$venta_detalle->PrecioUnit = $precios[$i];
			$venta_detalle->PrecioTotal = $cantidad[$i] * $precios[$i];
            $venta_detalle->idProductoDist = ProductoDistribuidor::where('CodBarrasDist', '=', $codigos[$i])->get()->first()->idProductoDist;
			$venta_detalle->save();

			$movimientos_distribuidor = new MovimientosDistribuidor();
			$movimientos_distribuidor->CantidadMovimiento = $cantidad[$i];
			$movimientos_distribuidor->CostoMovimiento = $precios[$i];
			$movimientos_distribuidor->TotalCosto = $cantidad[$i] * $precios[$i];
			$movimientos_distribuidor->TipoMovimiento = 'Salida';
			$movimientos_distribuidor->FechaMovimiento = date('Y-m-d');
			$movimientos_distribuidor->RegistradoPor = Session::get('usuario');
			$movimientos_distribuidor->idDistribuidor = Session::get('id_dist');
			$movimientos_distribuidor->idventa = $venta_realizada;
            $movimientos_distribuidor->idProductoDist = ProductoDistribuidor::where('CodBarrasDist', '=', $codigos[$i])->get()->first()->idProductoDist;
			$movimientos_distribuidor->save();

			$ids_prod[] = ProductoDistribuidor::where('CodBarrasDist', '=', $codigos[$i])->first()->idProductoDist;
			$cant_prod[] = $cantidad[$i];
		}

		/** Actualización de inventario del distribuidor */
		for($i=0;$i<count($ids_prod);$i++){
			$inventario = InventarioDistribuidor::productos($codigos[$i])->get();
			
			$existencia = $inventario[0]->Existencia;
			$resta = $existencia - $cant_prod[$i];
						
			DB::update('update inventario_distribuidor set Existencia = ? where idDistribuidor = ? and idProductoDist = ? and Estado = 1 limit 1', [$resta, Session::get('id_dist'), $ids_prod[$i]]);
			if($resta <= 0){
				DB::update('update inventario_distribuidor set Estado = 0 where idDistribuidor = ? and idProductoDist = ? limit 1', [Session::get('id_dist'), $ids_prod[$i]]);
			}
		}
		return 'Venta realizada';
	}

	/**
	 * Obtiene los productos disponibles en el inventario del distribuidor.
	 */
	public function postProductos(){
		$codigo = Input::get('codigo');
		$val = InventarioDistribuidor::productos($codigo);
		if(!$val->count()){
			$json = 'Producto no encontrado.';	
		} else {
			$json = $val->first();
		}
		return Response::json($json);
	}

}