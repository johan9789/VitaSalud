<?php
namespace Ventas\Administrador;

use Graphics\Venta as GraphVenta;
use Graphics\VentaLineal as GraphVentaLineal;
use Graphics\VentaLinealTotal as GraphVentaLinealTotal;
use Venta as VentaModel;
use View, Redirect, Date;

class GraficosController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('administrador');
	}

	public function getIndex(){
		$title = 'Gráficos';
		$fechas = 'Fecha actual';
		$venta_actual = GraphVenta::actual();
		$venta_actual_ingresos = GraphVenta::actualIngresos();
		return View::make('ventas.adm.graficos.index', compact('title', 'venta_actual', 'venta_actual_ingresos', 'fechas'));
	}

	public function getFechas($fecha_1 = '', $fecha_2 = ''){
		if($fecha_1 == '' || $fecha_2 == ''){
			return Redirect::to('ventas/adm/graficos');
		}
		$title = 'Gráficos';
		$fechas = $fecha_1.' - '.$fecha_2;
		$fecha_2 = date("Y-m-d", strtotime("$fecha_2 +1 day"));
		$venta_actual = GraphVenta::entreFechas($fecha_1, $fecha_2);
		$venta_actual_ingresos = GraphVenta::ingresosEntreFechas($fecha_1, $fecha_2);
		return View::make('ventas.adm.graficos.index', compact('title', 'venta_actual', 'venta_actual_ingresos', 'fechas'));
	}

	public function getLineal($año = ''){
		$seleccionar_año = $año;
		if(empty($año)){
			$año = Date::current('Y');
			$seleccionar_año = Date::current('Y');
		}
		$title = 'Gráficos - Vista mensual';
		$cantidad = (object)[
			'enero' => (count(GraphVentaLineal::enero($año)) == 0) ? 0 : GraphVentaLineal::enero($año)[0]->cantidad_final,
			'febrero' => (count(GraphVentaLineal::febrero($año)) == 0) ? 0 : GraphVentaLineal::febrero($año)[0]->cantidad_final,
			'marzo' => (count(GraphVentaLineal::marzo($año)) == 0) ? 0 : GraphVentaLineal::marzo($año)[0]->cantidad_final,
			'abril' => (count(GraphVentaLineal::abril($año)) == 0) ? 0 : GraphVentaLineal::abril($año)[0]->cantidad_final,
			'mayo' => (count(GraphVentaLineal::mayo($año)) == 0) ? 0 : GraphVentaLineal::mayo($año)[0]->cantidad_final,
			'junio' => (count(GraphVentaLineal::junio($año)) == 0) ? 0 : GraphVentaLineal::junio($año)[0]->cantidad_final,
			'julio' => (count(GraphVentaLineal::julio($año)) == 0) ? 0 : GraphVentaLineal::julio($año)[0]->cantidad_final,
			'agosto' => (count(GraphVentaLineal::agosto($año)) == 0) ? 0 : GraphVentaLineal::agosto($año)[0]->cantidad_final,
			'septiembre' => (count(GraphVentaLineal::septiembre($año)) == 0) ? 0 : GraphVentaLineal::septiembre($año)[0]->cantidad_final,
			'octubre' => (count(GraphVentaLineal::octubre($año)) == 0) ? 0 : GraphVentaLineal::octubre($año)[0]->cantidad_final,
			'noviembre' => (count(GraphVentaLineal::noviembre($año)) == 0) ? 0 : GraphVentaLineal::noviembre($año)[0]->cantidad_final,
			'diciembre' => (count(GraphVentaLineal::diciembre($año)) == 0) ? 0 : GraphVentaLineal::diciembre($año)[0]->cantidad_final
		];
		$total = (object)[			
			'enero' => (count(GraphVentaLinealTotal::enero($año)) == 0) ? 0 : GraphVentaLinealTotal::enero($año)[0]->total_final,
			'febrero' => (count(GraphVentaLinealTotal::febrero($año)) == 0) ? 0 : GraphVentaLinealTotal::febrero($año)[0]->total_final,
			'marzo' => (count(GraphVentaLinealTotal::marzo($año)) == 0) ? 0 : GraphVentaLinealTotal::marzo($año)[0]->total_final,
			'abril' => (count(GraphVentaLinealTotal::abril($año)) == 0) ? 0 : GraphVentaLinealTotal::abril($año)[0]->total_final,
			'mayo' => (count(GraphVentaLinealTotal::mayo($año)) == 0) ? 0 : GraphVentaLinealTotal::mayo($año)[0]->total_final,
			'junio' => (count(GraphVentaLinealTotal::junio($año)) == 0) ? 0 : GraphVentaLinealTotal::junio($año)[0]->total_final,
			'julio' => (count(GraphVentaLinealTotal::julio($año)) == 0) ? 0 : GraphVentaLinealTotal::julio($año)[0]->total_final,
			'agosto' => (count(GraphVentaLinealTotal::agosto($año)) == 0) ? 0 : GraphVentaLinealTotal::agosto($año)[0]->total_final,
			'septiembre' => (count(GraphVentaLinealTotal::septiembre($año)) == 0) ? 0 : GraphVentaLinealTotal::septiembre($año)[0]->total_final,
			'octubre' => (count(GraphVentaLinealTotal::octubre($año)) == 0) ? 0 : GraphVentaLinealTotal::octubre($año)[0]->total_final,
			'noviembre' => (count(GraphVentaLinealTotal::noviembre($año)) == 0) ? 0 : GraphVentaLinealTotal::noviembre($año)[0]->total_final,
			'diciembre' => (count(GraphVentaLinealTotal::diciembre($año)) == 0) ? 0 : GraphVentaLinealTotal::diciembre($año)[0]->total_final
		];
		$fecha = VentaModel::orderBy('Fecha', 'desc')->get()[0]->Fecha;
        $part_fecha = explode('-', $fecha);
        $values_fecha = array_values($part_fecha);
        $ultimo_año = array_shift($values_fecha);
		return View::make('ventas.adm.graficos.lineal', compact('title', 'año', 'seleccionar_año', 'cantidad', 'total', 'ultimo_año'));
	}

	public function getLinealAnual(){
		$title = 'Gráficos - Vista anual';
		$cantidad = function($año){
			return (count(GraphVentaLineal::anual($año)) == 0) ? 0 : GraphVentaLineal::anual($año)[0]->cantidad_final;
		};
		$total = function($año){
			return (count(GraphVentaLinealTotal::anual($año)) == 0) ? 0 : GraphVentaLinealTotal::anual($año)[0]->total_final;
		};
		return View::make('ventas.adm.graficos.lineal_anual', compact('title', 'año', 'seleccionar_año', 'cantidad', 'total'));	
	}

}