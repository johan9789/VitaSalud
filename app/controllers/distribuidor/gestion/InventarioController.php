<?php
namespace Distribuidor\Gestion;

/**
 * Inventario del Distribuidor
 */
class InventarioController extends \BaseController {

    public function __construct(){
        $this->beforeFilter('distribuidor');
    }

    // DistInventario se encuentra en /app/config/app.php
    public function getIndex(){
        $inventario = \DistInventario::stock(\Session::get('id_dist'))->get();
        $title = 'Gestión de inventario';
        return \View::make('distribuidor.inventario.index', compact('inventario', 'title'));
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
            while($i <= 12){
                if($mes <= $i){
                    break;
                } else {
                    $k++;
                }
                $i = $i + 3;
            }
        } else {
            $k = $op;
        }

        $trimestres = ['Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho];

        switch($k){
            case '0':
                $inicio = '-01-01';
                $fin = '-03-31';
                break;
            case '1':
                $inicio = '-04-01';
                $fin = '-06-30';
                break;
            case '2':
                $inicio = '-07-01';
                $fin = '-09-30';
                break;
            case '3':
                $inicio = '-10-01';
                $fin = '-12-31';
                break;
            default:
                $inicio = '-01-01';
                $fin = '-12-31';
                break;
        }

        $fecha1 = $anho . $inicio;
        $fecha2 = $anho . $fin;

        $distribuidor = \Distribuidores::findOrFail(\Session::get('id_dist'));
        $movimientos = $distribuidor->movimientos()->lista($fecha1, $fecha2)->get();

        $fechas_ini = explode('-', $fecha1);
        $fechas_fin = explode('-', $fecha2);

        $valFecha_ini = $fechas_ini[2] . '/' . $fechas_ini[1] . '/' . $fechas_ini[0];
        $valFecha_fin = $fechas_fin[2] . '/' . $fechas_fin[1] . '/' . $fechas_fin[0];

        $title = "Gestion de inventario - Movimientos <small>{$anho}</small>";
        return \View::make('distribuidor.inventario.movimientos', compact('movimientos', 'anho', 'trimestres', 'k', 'fecha1', 'fecha2', 'valFecha_ini', 'valFecha_fin', 'title'));
    }

    public function postMovimientos(){
        $anho = date('Y');

        $trimestres = ['Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho];

        $k = '-1';

        $fechas = explode(' - ', \Input::get('fechas'));
        $subfechas_ini = explode('/', $fechas[0]);
        $subfechas_fin = explode('/', $fechas[1]);

        $fecha1 = $subfechas_ini[2] . '-' . $subfechas_ini[1] . '-' . $subfechas_ini[0];
        $fecha2 = $subfechas_fin[2] . '-' . $subfechas_fin[1] . '-' . $subfechas_fin[0];

        $distribuidor = \Distribuidores::findOrFail(\Session::get('id_dist'));
        $movimientos = $distribuidor->movimientos()->lista($fecha1, $fecha2)->get();

        $valFecha_ini = $subfechas_ini[0] . '-' . $subfechas_ini[1] . '-' . $subfechas_ini[2];
        $valFecha_fin = $subfechas_fin[0] . '-' . $subfechas_fin[1] . '-' . $subfechas_fin[2];

        return \View::make('distribuidor.inventario.movimientos', compact('movimientos', 'anho', 'trimestres', 'k', 'fecha1', 'fecha2', 'valFecha_ini', 'valFecha_fin', 'title'));
    }

}
