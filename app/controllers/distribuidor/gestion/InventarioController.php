<?php 
namespace Distribuidor\Gestion;

/**
* Inventario del Distribuidor
*/
class InventarioController extends \BaseController {
	
	public function __construct(){
		$this->beforeFilter('distribuidor');
	}

	//DistInventario se encuentra en /app/config/app.php
	public function getIndex(){
		$id_distribuidor = \Session::get('id_dist');

		$aviso = '';

		$inventario = \DistInventario::lista_stock($id_distribuidor);
		$j = 1;

		$title = 'Gestión de inventario';

		return \View::make('distribuidor.inventario.index', compact('inventario', 'j', 'title'));
	}

	public function getMovimientos($op = null){

		$id_distribuidor = \Session::get('id_dist');

		$anho = date('Y');

		$mes = date('m');

		//Esta variable no sirve
		$bandera = false;

		if($op == null){
			//Nos servirá para determinar que meses son los que se están evaluando y para colocar en la vista que button debe ser active
			$i = 3;
			$k = 0;
			while( $i<= 12){
				if($mes <= $i){
					break;
				} else {
					$k++;
				}
				$i = $i + 3;
			}
			/*echo 'EN '.$i.' es '.$k;
			exit();*/
		} else {
			$k = $op;
		}
		
		$trimestres = array('Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho);

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

		$movimientos = \DistMovimientos::listamovimentos($id_distribuidor, $fecha_1, $fecha_2);

		$j = 1;

		$fechas_ini = explode('-', $fecha_1);
		$fechas_fin = explode('-', $fecha_2);

		$valFecha_ini = $fechas_ini[2].'/'.$fechas_ini[1].'/'.$fechas_ini[0];
		$valFecha_fin = $fechas_fin[2].'/'.$fechas_fin[1].'/'.$fechas_fin[0];
		
		$title = "Gestion de inventario - Movimientos <small>{$anho}</small>";

		//return \View::make('gestion.inventario.movimientos', compact('movimientos', 'j', 'anho', 'trimestres', 'k', 'bandera', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin', 'title'));

		return \View::make('distribuidor.inventario.movimientos', compact('movimientos', 'j', 'anho', 'trimestres', 'k', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin', 'title'));
	}

	public function postMovimientos(){

		$id_distribuidor = Session::get('id_dist');

		$anho = date('Y');

		$trimestres = array('Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho);

		$k = '-1';

		$bandera = false;

		$fechas = explode(' - ', \Input::get('fechas'));
		$subfechas_ini = explode('/', $fechas[0]);
		$subfechas_fin = explode('/', $fechas[1]);

		$fecha_1 = $subfechas_ini[2].'-'.$subfechas_ini[1].'-'.$subfechas_ini[0];
		$fecha_2 = $subfechas_fin[2].'-'.$subfechas_fin[1].'-'.$subfechas_fin[0];

		$movimientos = \DistMovimientos::listamovimentos($id_distribuidor, $fecha_1, $fecha_2);

		$j = 1;

		$valFecha_ini = $subfechas_ini[0].'-'.$subfechas_ini[1].'-'.$subfechas_ini[2];
		$valFecha_fin = $subfechas_fin[0].'-'.$subfechas_fin[1].'-'.$subfechas_fin[2];
		
		return \View::make('distribuidor.inventario.movimientos', compact('movimientos', 'j', 'anho', 'trimestres', 'k', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin', 'title'));
	}

}