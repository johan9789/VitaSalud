<?php
namespace Compras\Administrador;

use Categoriaproducto, Productos, Compras, DetalleCompras, Distrito, Provincia;
use Proveedor, Impuesto, Inventario, Porcentaje, MovimientosAdministrador;
use Input, View, Date, Session;

class HomeController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('administrador');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$title = 'Compra de productos';
		$categoria_productos = Categoriaproducto::total()->get();		
		$lista_proveedores = Proveedor::lista()->get();;
		$impuesto = Impuesto::getIGV();				
		$distritos = Distrito::lista()->lists('NombreDistrito', 'iddistrito');
		return View::make('compras.adm.index', compact('title', 'categoria_productos', 'lista_proveedores', 'impuesto', 'distritos'));
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

		$compras = new Compras();
		$compras->FechaCompra = $all['fecha'];
		$compras->Factura = $all['factura'];
		$compras->SubTotalCompra = $sub_total_compra;
		$compras->IGVCompra = $sub_total_compra * Impuesto::getIGV();
		$compras->idImpuesto = 1;
		$compras->idProveedor = $all['proveedor'];
		$compras->TotalCompra = $sub_total_compra + ($sub_total_compra * Impuesto::getIGV());
		$compras->save();

		$ultima_compra = $compras->idCompra;

		for($i=0;$i<count($productos);$i++){
			$detalle_compra = new DetalleCompras();
			$detalle_compra->idProductoCompra = $productos[$i];
			$detalle_compra->CostoCompra = $costos[$i];
			$detalle_compra->CantidadCompra = $cantidades[$i];
			$detalle_compra->TotalCostoCompra = $costos[$i] * $cantidades[$i];
			$detalle_compra->idCompra = $ultima_compra;
			$detalle_compra->save();
		}

		$porcentaje = Porcentaje::all()->last();
		for($i=0;$i<count($productos);$i++){
			$inventario_adm = new Inventario();
			$inventario_adm->idProducto = $productos[$i];
			$inventario_adm->Existencia = $cantidades[$i];
			$inventario_adm->Costo = $costos[$i];
			$inventario_adm->PrecDistribuidor = $costos[$i] + ($costos[$i] * $porcentaje->DistPorcentaje);
			$inventario_adm->PrecPublico = $costos[$i] + ($costos[$i] * $porcentaje->PubPorcentaje);
			$inventario_adm->Estado = 1;
			$inventario_adm->Comentario = 'Compra registrada con factura N° '.$all['factura'].'.';
			$inventario_adm->save();
		}
		
		for($i=0;$i<count($productos); $i++){
			$movimientos_adm = new MovimientosAdministrador();
			$movimientos_adm->idProductoMovimiento = $productos[$i];
			$movimientos_adm->CantidadMovimiento = $cantidades[$i];
			$movimientos_adm->CostoMovimiento = $costos[$i];
			$movimientos_adm->TotalCosto = $costos[$i] * $cantidades[$i];
			$movimientos_adm->TipoMovimiento = 'Entrada';
			$movimientos_adm->FechaMovimiento = $all['fecha'];
			$movimientos_adm->RegistradoPor = Session::get('usuario');
			$movimientos_adm->idCompra = $ultima_compra;
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