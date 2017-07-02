<?php
namespace Compras\Distribuidor;

use ComprasDistribuidor, DetComprasDist;
use App, Request, Response, View;

class ReportesController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('distribuidor');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$lista_reportes = ComprasDistribuidor::with('proveedor')->get();
		return View::make('compras.dist.reportes', compact('lista_reportes'));
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
	public function store(){}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id_compra
	 * @return Response
	 */
	public function show($id_compra){
		if(!Request::ajax()){
			return App::abort(404);
		}
		return Response::json(DetComprasDist::with('producto')->where('idCompraDist', $id_compra)->get());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id_compra
	 * @return Response
	 */
	public function edit($id_compra){}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id_compra
	 * @return Response
	 */
	public function update($id_compra){}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id_compra
	 * @return Response
	 */
	public function destroy($id_compra){}

}