<?php
namespace Gestion\Proveedores\Distribuidor;

use ProveedorDistribuidor, Distrito;
use Input, Session, Redirect, Response, View;

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
        $title = 'Gestión de proveedores';
        $proveedores = ProveedorDistribuidor::lista()->get();
        $distritos = Distrito::lista()->lists('NombreDistrito', 'iddistrito');
        return View::make('gestion.proveedores.dist.index', compact('title', 'proveedores', 'distritos'));
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
        ProveedorDistribuidor::create(Input::except(['_token']));
        return 'Proveedor registrado correctamente.';
    }

    /**
     * Display the specified resource.
     *
     * @param  int $idProveedor
     * @return Response
     */
    public function show($idProveedor){
        if(Input::get('type') == 'json'){
            $proveedor = ProveedorDistribuidor::find($idProveedor);
            Session::put('xid_prov', $proveedor->id_proveedor_dist);
            return $proveedor;
        } elseif(Input::get('type') == 'jsdist'){
            return Distrito::lista()->lists('NombreDistrito', 'iddistrito');
        } else {
            return Redirect::to('gestion/proveedores/dist');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id_proveedor
     * @return Response
     */
    public function edit($id_proveedor){}

    /**
     * Update the specified resource in storage.
     *
     * @param  int $idProveedor
     * @return Response
     */
    public function update($idProveedor){
        $all = Input::except(['_token']);
        $proveedor = ProveedorDistribuidor::find($idProveedor);
        $proveedor->RUC = $all['RUC'];
        $proveedor->razon_social_proveedor_dist = $all['razon_social_proveedor_dist'];
        $proveedor->direccion_proveedor_dist = $all['direccion_proveedor_dist'];
        $proveedor->telf_proveedor_dist = $all['telf_proveedor_dist'];
        $proveedor->email_proveedor_dist = $all['email_proveedor_dist'];
        $proveedor->iddistrito = $all['iddistrito'];
        $proveedor->save();
        return 'Proveedor modificado correctamente';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $idProveedor
     * @return Response
     */
    public function destroy($idProveedor){
        $proveedor = ProveedorDistribuidor::find($idProveedor);
        $proveedor->estado_proveedor_dist = 0;
        $proveedor->save();
        return 'Proveedor eliminado correctamente';
    }

}
