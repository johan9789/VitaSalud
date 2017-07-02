<?php
namespace Ventas\Distribuidor;

use DB, Input, Session, Redirect, Response, View;
use Date, Excel, PDF;
use Cliente, Venta, VentaDetalle;

class ReportesController extends \BaseController {
	private $val_tipo_cliente;
	private $clientes_XD;
	private $empresas_XD;

	public function __construct(){
		$this->beforeFilter('distribuidor');
		$this->val_tipo_cliente = function($id_cliente){
			return Cliente::where('idCliente', '=', $id_cliente)->get()[0];
		};
		$this->clientes_XD = function($id_cliente){
			return Cliente::join('persona', 'cliente.idPersona', '=', 'persona.idPersona')->where('idCliente', '=', $id_cliente)->get()[0];
		};
		$this->empresas_XD = function($id_cliente){
			return Cliente::join('empresa', 'cliente.idEmpresa', '=', 'empresa.idEmpresa')->where('idCliente', '=', $id_cliente)->get()[0];
		};
	}

	public function get_index(){
		$lista_reportes = Venta::where('idDistribuidor', '=', Session::get('id_dist'))->orderBy('Fecha', 'desc')->get();
		$val_tipo_cliente = $this->val_tipo_cliente;
		$clientes_XD = $this->clientes_XD;
		$empresas_XD = $this->empresas_XD;
		$fecha = '';
		$fechas = '';
		return View::make('ventas.dist.reportes.index', compact('lista_reportes', 'val_tipo_cliente', 'clientes_XD', 'empresas_XD', 'fecha', 'fechas'));
	}

	public function get_fecha($fecha = ''){
		if($fecha == ''){
			return Redirect::to('ventas/dist/reportes');
		}
		$lista_reportes = Venta::where('idDistribuidor', '=', \Session::get('id_dist'))->where('Fecha', 'like', $fecha.'%')->orderBy('Fecha', 'desc')->get();
		$val_tipo_cliente = $this->val_tipo_cliente;
		$clientes_XD = $this->clientes_XD;
		$empresas_XD = $this->empresas_XD;
		$fechas = '';
		return View::make('ventas.dist.reportes.index', compact('lista_reportes', 'val_tipo_cliente', 'clientes_XD', 'empresas_XD', 'fecha', 'fechas'));
	}

	public function get_fechas($fecha_1 = '', $fecha_2 = ''){
		if($fecha_1 == '' || $fecha_2 == ''){
			return Redirect::to('ventas/dist/reportes');
		}
		$fecha_2 = date("Y-m-d", strtotime("$fecha_2 +1 day"));

		$lista_reportes = DB::table('venta')->where('idDistribuidor', '=', Session::get('id_dist'))->whereBetween('Fecha', [$fecha_1, $fecha_2])->get();

        $val_tipo_cliente = $this->val_tipo_cliente;
		$clientes_XD = $this->clientes_XD;
		$empresas_XD = $this->empresas_XD;

		$fecha = '';
		$fechas = $fecha_2.' - '.$fecha_1;
		return View::make('ventas.dist.reportes.index', compact('lista_reportes', 'val_tipo_cliente', 'clientes_XD', 'empresas_XD', 'fecha', 'fechas'));
	}

	public function post_detalle(){
		$venta_detalle = VentaDetalle::lista(Input::get('venta'))->get();
		return Response::json($venta_detalle);
	}

	public function get_detalle($id_venta = '', $option = ''){
		if($id_venta == ''){
			return Redirect::to('ventas/dist/reportes');
		}

		$venta_detalle = VentaDetalle::lista($id_venta)->get();
		$fecha_fecha = Date::format($venta_detalle[0]->Fecha, 'd/m/Y');
		$fecha_hora = Date::format($venta_detalle[0]->Fecha, 'g:i:s A');

		$val_tipo_cliente = $this->val_tipo_cliente;
		$clientes_XD = $this->clientes_XD;
		$empresas_XD = $this->empresas_XD;

		if($option == ''){			
    		return PDF::loadView('ventas.dist.reportes.detalle', compact('venta_detalle', 'fecha_fecha', 'fecha_hora', 'val_tipo_cliente', 'clientes_XD', 'empresas_XD'))->stream();
		} elseif($option == 'download'){
    		return PDF::loadView('ventas.dist.reportes.detalle', compact('venta_detalle', 'fecha_fecha', 'fecha_hora', 'val_tipo_cliente', 'clientes_XD', 'empresas_XD'))->download($fecha_fecha.'  '.$fecha_hora.'.pdf');
		} else {
			return Redirect::to('ventas/dist/reportes');
		}
	}

	public function get_excel($id_venta = ''){
		$venta_detalle = VentaDetalle::lista($id_venta)->get();
		$fecha_fecha = Date::format($venta_detalle[0]->Fecha, 'd/m/Y');
		$fecha_hora = Date::format($venta_detalle[0]->Fecha, 'g:i:s A');

		Excel::create($fecha_fecha.'  '.$fecha_hora, function($excel) use($venta_detalle, $fecha_fecha, $fecha_hora){
			$excel->sheet('Hoja', function($sheet) use($venta_detalle, $fecha_fecha, $fecha_hora){
				$val_tipo_cliente = $this->val_tipo_cliente;
				$clientes_XD = $this->clientes_XD;
				$empresas_XD = $this->empresas_XD;

				$sheet->loadView('ventas.dist.reportes.detalle', compact('venta_detalle', 'fecha_fecha', 'fecha_hora', 'val_tipo_cliente', 'clientes_XD', 'empresas_XD'));
			});
		})->download('xlsx');
	}

	public function get_general(){
		$lista_reportes = Venta::where('idDistribuidor', '=', Session::get('id_dist'))->orderBy('Fecha', 'desc')->get();
		$val_tipo_cliente = $this->val_tipo_cliente;
		$clientes_XD = $this->clientes_XD;
		$empresas_XD = $this->empresas_XD;
		return PDF::loadView('ventas.adm.reportes.general', compact('lista_reportes', 'val_tipo_cliente', 'clientes_XD', 'empresas_XD'))->download('reporte_general.pdf');
	}

}