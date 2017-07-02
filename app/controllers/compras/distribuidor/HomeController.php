<?php
namespace Compras\Distribuidor;

use Distrito, Impuesto, Porcentaje;
use Input, View, Date, Session;

use CategoriaProductoDistribuidor, ProveedorDistribuidor, ComprasDistribuidor, DetComprasDist;
use InventarioDistribuidor, MovimientosDistribuidor;

class HomeController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('distribuidor');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$title = 'Compra de productos';
		$categoria_productos = CategoriaProductoDistribuidor::total()->get();
        $productos = function($categoria){
            return \ProductoDistribuidor::lista($categoria)->where('idDistribuidorRef', Session::get('id_dist'))->get();
        };
		$lista_proveedores = ProveedorDistribuidor::lista()->get();;
		$impuesto = Impuesto::getIGV();
		$distritos = Distrito::lista()->lists('NombreDistrito', 'iddistrito');
		return View::make('compras.dist.index', compact('title', 'categoria_productos', 'productos', 'lista_proveedores', 'impuesto', 'distritos'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
		$all = Input::all();

		$productos = $all['sel_prod_inv'];
		$costos = $all['costo'];
		$cantidades = $all['cantidad'];

		$sub_total_compra = 0;

		for($i=0;$i<count($productos); $i++){
			$suma = $costos[$i] * $cantidades[$i];
			$sub_total_compra += $suma;
		}

		$compras = new ComprasDistribuidor();
		$compras->FechaCompraDist = $all['fecha'];
		$compras->FacturaDist = $all['factura'];
		$compras->SubTotalCompraDist = $sub_total_compra;
		$compras->IGVCompraDist = $sub_total_compra * Impuesto::getIGV();
		$compras->idImpuestoDist = 1;
		$compras->idProveedorDist = $all['proveedor'];
		$compras->TotalCompraDist = $sub_total_compra + ($sub_total_compra * Impuesto::getIGV());
		$compras->save();

		$ultima_compra = $compras->idCompraDist;

		for($i=0;$i<count($productos); $i++){
			$detalle_compra = new DetComprasDist();
			$detalle_compra->idProductoCompraDist = $productos[$i];
			$detalle_compra->CostoCompraDist = $costos[$i];
			$detalle_compra->CantidadCompraDist = $cantidades[$i];
			$detalle_compra->TotalCostoCompraDist = $costos[$i] * $cantidades[$i];
			$detalle_compra->idCompraDist = $ultima_compra;
			$detalle_compra->save();
		}

		$porcentaje = Porcentaje::all()->last();
		for($i=0;$i<count($productos); $i++){
			$inventario_adm = new InventarioDistribuidor();
			$inventario_adm->idProductoDist = $productos[$i];
			$inventario_adm->idDistribuidor = Session::get('id_dist');
			$inventario_adm->Existencia = $cantidades[$i];
			$inventario_adm->Costo = $costos[$i];
			$inventario_adm->PrecPublico = $costos[$i] + ($costos[$i] * $porcentaje->PubPorcentaje);
			$inventario_adm->Estado = 1;
			$inventario_adm->Comentario = 'Compra registrada con factura N°'.$all['factura'].'.';
			$inventario_adm->save();
		}
		
		for($i=0;$i<count($productos); $i++){
			$movimientos_adm = new MovimientosDistribuidor();
			$movimientos_adm->idProductoDist = $productos[$i];
			$movimientos_adm->CantidadMovimiento = $cantidades[$i];
			$movimientos_adm->CostoMovimiento = $costos[$i];
			$movimientos_adm->TotalCosto = $costos[$i] * $cantidades[$i];
			$movimientos_adm->TipoMovimiento = 'Entrada';
			$movimientos_adm->FechaMovimiento = $all['fecha'];
			$movimientos_adm->RegistradoPor = Session::get('usuario');
			$movimientos_adm->idDistribuidor = Session::get('id_dist');
			//$movimientos_adm->idCompraDist = $ultima_compra;
			$movimientos_adm->save();
		}
		return 'Compra registrada.';
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){}

}