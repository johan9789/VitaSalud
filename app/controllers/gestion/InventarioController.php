<?php
namespace Gestion;
// use View, Inventario; para quitar los \

class InventarioController extends \BaseController {

	public function __construct(){		
		$this->beforeFilter('administrador');
	}

	public function getIndex(){		
		$aviso = '';

		$inventario = \Inventario::lista_stock();
		$prod_recientes = \Inventario::lista_prod_recientes();
		$j = 1;
		if(count($inventario) == 0){
			$aviso = (count($prod_recientes)>1)?"Productos":"Producto";
		} else {
			$aviso = (count($prod_recientes)>1)?"Productos Nuevos":"Producto Nuevo";
		}
		$title = 'Gestión de inventario';
		return \View::make('gestion.inventario.index', compact('inventario', 'j', 'prod_recientes', 'aviso', 'title'));
	}

	public function getInventarioinicial(){
		$inventario = \Inventario::lista()->get();

		$usu_register = \Session::get('usuario');//Usuario que registra el inventario

		if(count($inventario) == 0){

			$fecha = date('Y-m-d');

			$datos = \Inventario::lista_prod_actuales();

			$arreglo_insert_inventario = array();
			$arreglo_insert_movimiento = array();

			$i = 0;

			foreach ($datos as $d) {
				$arreglo_insert_inventario[$i] = array('idProducto' => $d->idProducto, 
														'Existencia' => 0, 
														'Costo' => 0, 
														'PrecDistribuidor' => 0, 
														'PrecPublico' => 0, 
														'Estado' => 0,
														'Comentario' => '');

				$arreglo_insert_movimiento[$i] = array('idProductoMovimiento' => $d->idProducto, 
														'CantidadMovimiento' => 0, 
														'CostoMovimiento' => 0, 
														'TotalCosto' => 0, 
														'TipoMovimiento' => 'Inventario Inicial', 
														'FechaMovimiento' => $fecha,
														'RegistradoPor' => $usu_register);
				$i++;
			}
			
			/*DB::table('inventario')->insert(array(
			    array('idProducto' => 1, 'Precio' => 0),
			    array('idProducto' => 2, 'Precio' => 0),
			));*/

			\DB::table('inventario_adm')->insert($arreglo_insert_inventario);
			\DB::table('movimientos_adm')->insert($arreglo_insert_movimiento);

			return \Redirect::to('gestion/inventario');

		}else{
			return \Redirect::to('gestion/inventario');
		}

	}

	public function getRegprodreciente(){
		$datos = \Inventario::lista_prod_recientes();

		$usu_register = \Session::get('usuario');//Usuario que registra el inventario
		
		if(count($datos) != 0){
			$fecha = date('Y-m-d');

			$arreglo_insert_inventario = array();
			$arreglo_insert_movimiento = array();
			$i = 0;

			foreach($datos as $d){
				$arreglo_insert_inventario[$i] = array('idProducto' => $d->idProducto, 
														'Existencia' => 0, 
														'Costo' => 0, 
														'PrecDistribuidor' => 0, 
														'PrecPublico' => 0, 
														'Estado' => 0,
														'Comentario' => '');

				$arreglo_insert_movimiento[$i] = array('idProductoMovimiento' => $d->idProducto, 
														'CantidadMovimiento' => 0, 
														'CostoMovimiento' => 0, 
														'TotalCosto' => 0, 
														'TipoMovimiento' => 'Inventario Inicial', 
														'FechaMovimiento' => $fecha,
														'RegistradoPor' => $usu_register);
				$i++;
			}

			\DB::table('inventario_adm')->insert($arreglo_insert_inventario);
			\DB::table('movimientos_adm')->insert($arreglo_insert_movimiento);

			return \Redirect::to('gestion/inventario');
		} else {
			return \Redirect::to('gestion/inventario');
		}
	}

	public function getRegistroentrada(){
		
		$listCategoriaUso = \Productos::prodcategoria()->lists('NombreCategoriaProducto', 'idCategoriaProducto');
		$listProductosConCat = \Inventario::lista_productos()->get();

		$matriz = [];
		foreach ($listCategoriaUso as $key => $value) {
			$matriz[$value] = array();
		}

		foreach($listCategoriaUso as $key1 => $value1){
			foreach($listProductosConCat as $key2 => $value2){
				if($value2['idCategoriaProducto'] == $key1){
					array_push($matriz[$value1], [$value2['idProducto'] => $value2['NombreProducto']]);
				}
			}
		}

		$title = 'Gestión de inventario - Entradas';
		return \View::make('gestion.inventario.registroentrada', compact('matriz', 'title'));
	}

	public function postActualizarentradas(){

		$usu_register = \Session::get('usuario');//Usuario que registra el inventario

		$cant_errores = 0;
		$mensaje_error = 'Error; No se pudo realizar el envío: ';

		$fecha = date('Y-m-d');
		$inputs = \Input::all();
		//Select
		$select_prod = $inputs['sel_prod_inv'];
		//Precio
		$precio_prod = $inputs['precio'];
		//Entrada
		$entrada_prod = $inputs['entrada'];
		
		$arreglo_insert_inventario = array();
		$arreglo_insert_movimiento = array();
		$j = 0;

		for($i = 0; $i < count($select_prod); $i++){

			if($select_prod[$i] == '0' || $precio_prod[$i] == '' || $entrada_prod[$i] == ''){
				$cant_errores++;
				$mensaje_error .= '<br>* Verifique haber completado todos los campos correctamente';
			}else{
				$mensaje_error .= '';
			}
			if(!is_numeric($precio_prod[$i])){
				$cant_errores++;
				$mensaje_error .= '<br>* Campo precio debe ser numérico';
				
			}else{
				$mensaje_error .= '';
			}

			if($precio_prod[$i] == '0'){
				$cant_errores++;
				$mensaje_error .= '<br>* Campo precio debe ser mayor a 0';
			}else
				$mensaje_error .= '';

			if(!ctype_digit($entrada_prod[$i]))
			{
				$cant_errores++;
				$mensaje_error .= '<br>* Campo entrada solo debe ser número entero y mayor a 0';
			}else{
				$mensaje_error .= '';
			}
			if($entrada_prod[$i] == '0'){
				$cant_errores++;
				$mensaje_error .= '<br>* Campo entrada debe ser mayor a 0';
			}else
				$mensaje_error .= '';

			if($cant_errores > 0){
				\Session::flash('mensaje_error', $mensaje_error);
				return 'error_registro';
			}

			$porcentaje = \Porcentaje::first();
			$distPorcentaje = $porcentaje->DistPorcentaje;
			$pubPorcentaje = $porcentaje->PubPorcentaje;

			$precio_entrante = $precio_prod[$i];

			$arreglo_insert_inventario[$j] = array('idProducto' => $select_prod[$i], 
														'Existencia' => $entrada_prod[$i], 
														'Costo' => $precio_prod[$i], 
														'PrecDistribuidor' => $precio_prod[$i]+$precio_prod[$i]*$distPorcentaje, 
														'PrecPublico' => $precio_prod[$i]+$precio_prod[$i]*$pubPorcentaje, 
														'Estado' => 1,
														'Comentario' => '');


			$importe_movimiento = $precio_entrante*$entrada_prod[$i];

			$arreglo_insert_movimiento[$j] = array('idProductoMovimiento' => $select_prod[$i], 
														'CantidadMovimiento' => $entrada_prod[$i], 
														'CostoMovimiento' => $precio_prod[$i], 
														'TotalCosto' => $importe_movimiento, 
														'TipoMovimiento' => 'Entrada', 
														'FechaMovimiento' => $fecha,
														'RegistradoPor' => $usu_register);
			$j++;
		}

		\DB::table('inventario_adm')->insert($arreglo_insert_inventario);
		\DB::table('movimientos_adm')->insert($arreglo_insert_movimiento);

		\Session::flash('mensaje_entradas', 'Entradas Registradas');

		return 'Entradas Registradas Correctamente';
	}

	//Nos sirve para calcular las entradas y salidas correspondientes
	public function postFiltrarinventario($id=null){
		$arreglo = ['idProducto' => 0];
		if($id == null || $id == '0'){
			return \Response::json($arreglo);
		}
		return \Response::json(\Inventario::filtradoxprod($id));
	}

	//Esta función devuelve los datos de un producto seleccionado en el kardex
	public function postFiltrardatos($id=null){
		$arreglo = ['idProducto' => 0];
		if($id == null || $id == '0'){
			return \Response::json($arreglo);
		}
		return \Response::json(\Productos::filtradokardex($id));
	}

	//Movimientos
	public function getMovimientos($op=null){

		$anho = date('Y');

		$mes = date('m');

		if($op == null){
			//Nos servirá para determinar que meses son los que se están evaluando y para colocar en la vista que button debe ser active
			$i = 3;
			$k = 0;

			while ( $i<= 12) {
				if($mes <= $i)
					break;
				else
					$k++;
				$i = $i+3;

			}
		}else{
			$k = $op;
		}
		
		$trimestres = array('Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho);

		switch ($k) {
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

		$movimientos = \Inventario::Movimientos($fecha_1, $fecha_2);

		$j = 1;

		$fechas_ini = explode('-', $fecha_1);
		$fechas_fin = explode('-', $fecha_2);

		$valFecha_ini = $fechas_ini[2].'/'.$fechas_ini[1].'/'.$fechas_ini[0];
		$valFecha_fin = $fechas_fin[2].'/'.$fechas_fin[1].'/'.$fechas_fin[0];
		
		$title = "Gestion de inventario - Movimientos <small>{$anho}</small>";

		return \View::make('gestion.inventario.movimientos', compact('movimientos', 'j', 'anho', 'trimestres', 'k', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin', 'title'));
	}

	public function postMovimientos(){

		$anho = date('Y');

		$trimestres = array('Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho);

		$k = '-1';

		$bandera = false;

		$fechas = explode(' - ', \Input::get('fechas'));
		$subfechas_ini = explode('/', $fechas[0]);
		$subfechas_fin = explode('/', $fechas[1]);

		$fecha_1 = $subfechas_ini[2].'-'.$subfechas_ini[1].'-'.$subfechas_ini[0];
		$fecha_2 = $subfechas_fin[2].'-'.$subfechas_fin[1].'-'.$subfechas_fin[0];

		$movimientos = \Inventario::Movimientos($fecha_1, $fecha_2);

		$j = 1;

		$valFecha_ini = $subfechas_ini[0].'-'.$subfechas_ini[1].'-'.$subfechas_ini[2];
		$valFecha_fin = $subfechas_fin[0].'-'.$subfechas_fin[1].'-'.$subfechas_fin[2];
		
		return \View::make('gestion.inventario.movimientos', compact('movimientos', 'j', 'anho', 'trimestres', 'k', 'bandera', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin'));

	}

	/** 
	 * [POST | ajax/json]
	 * Devuelve el detalle de un producto en inventario en formato json
	 */
	public function postDetalleInventario($id = null){
		$detalleInventario = \DB::select("SELECT MD5(idInventario) AS idInventario, MD5(idProducto) AS idProducto, Existencia, Costo, PrecDistribuidor, PrecPublico, Estado
										FROM inventario_adm
										WHERE Estado = 1
											AND MD5(idProducto) = '$id';");

		return \Response::json($detalleInventario);
	}

	public function getKardex($op=null){
		$anho = date('Y');

		$mes = date('m');

		if($op == null){
			//Nos servirá para determinar que meses son los que se están evaluando y para colocar en la vista que button debe ser active
			$i = 3;
			$k = 0;

			while ( $i<= 12) {
				if($mes <= $i)
					break;
				else
					$k++;
				$i = $i+3;

			}
		}else{
			$k = $op;
		}
		
		$trimestres = array('Ene-Mar', 'Abr-Jun', 'Jul-Set', 'Oct-Dic', $anho);

		switch ($k) {
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

		$kardex = \Inventario::kardex($fecha_1, $fecha_2);

		$j = 1;

		$fechas_ini = explode('-', $fecha_1);
		$fechas_fin = explode('-', $fecha_2);

		$valFecha_ini = $fechas_ini[2].'/'.$fechas_ini[1].'/'.$fechas_ini[0];
		$valFecha_fin = $fechas_fin[2].'/'.$fechas_fin[1].'/'.$fechas_fin[0];
		
		$title = "Gestion de inventario - Kardex <small>{$anho}</small>";

		//////////////////////////////
		$listCategoriaUso = \Productos::prodcategoria()->lists('NombreCategoriaProducto', 'idCategoriaProducto');
		$listProductosConCat = \Inventario::lista_productos()->get();

		$matriz = [];
		foreach ($listCategoriaUso as $key => $value) {
			$matriz[$value] = array();
		}

		foreach($listCategoriaUso as $key1 => $value1){
			foreach($listProductosConCat as $key2 => $value2){
				if($value2['idCategoriaProducto'] == $key1){
					array_push($matriz[$value1], [$value2['idProducto'] => $value2['NombreProducto']]);
				}
			}
		}
		
		///////////////////////////////

		return \View::make('gestion.inventario.kardex', compact('kardex', 'j', 'anho', 'trimestres', 'k', 'fecha_1', 'fecha_2', 'valFecha_ini', 'valFecha_fin', 'matriz', 'title'));
	}

	/** 
	 * [POST | ajax/json]
	 * Actualizar cada fila de un detalle de un producto en inventario
	 */
	public function postActualizarDetalle(){
		$usu_register = \Session::get('usuario');
		$bandera = false;

		$inputs = \Input::all();
		$id_Inventario = $inputs['idI'];
		$id_Producto = $inputs['idP'];
		$prec_Distribuidor = $inputs['precD'];
		$prec_Publico = $inputs['precP'];
		$numeroCampos = $inputs['num_c'];

		$registro_inventario = \Inventario::registro_detalle($id_Inventario, $id_Producto);

		if(count($registro_inventario) == 1){

			if(empty($prec_Distribuidor) && empty($prec_Publico)){
				$bandera = true;
			}else if($numeroCampos == '2'){
				if(is_numeric($prec_Distribuidor) && is_numeric($prec_Publico)){
					//Actualizamos tabla con ambos precios
					\Inventario::whereRaw('MD5(idInventario) = ? AND MD5(idProducto) = ?', [$id_Inventario, $id_Producto])
								->update(['PrecDistribuidor' => $prec_Distribuidor, 'PrecPublico' => $prec_Publico, 'Comentario' => 'Precio Distribuidor y Público; actualizado por '.$usu_register]);
				}else{
					$bandera = true;
				}

			}else if($numeroCampos == '1'){
				if(is_numeric($prec_Distribuidor)){
					//actualizamos tabla solo precio distribuidor
					\Inventario::whereRaw('MD5(idInventario) = ? AND MD5(idProducto) = ?', [$id_Inventario, $id_Producto])
								->update(['PrecDistribuidor' => $prec_Distribuidor, 'Comentario' => 'Precio Distribuidor; actualizado por '.$usu_register]);
				}else{
					$bandera = true;
				}
			}else if($numeroCampos == '3'){
				if(is_numeric($prec_Publico)){
					//actualizamos tabla solo precio público
					\Inventario::whereRaw('MD5(idInventario) = ? AND MD5(idProducto) = ?', [$id_Inventario, $id_Producto])
								->update(['PrecPublico' => $prec_Publico, 'Comentario' => 'Precio Público; actualizado por '.$usu_register]);
				}else{
					$bandera = true;
				}
			}else{
				$bandera = true;
			}

		}else{
			$bandera = true;
		}

		if($bandera){
			//0: si hubo error, 1: no hubo error
			$resultado = ['errors' => '0', 'mensaje' => 'Error al actualizar, intente de nuevo'];
			return $resultado;
		}

		//0: si hubo error, 1: no hubo error
		$resultado = ['errors' => '1', 'mensaje' => 'Registro Actualizado'];
		return $resultado;
	}

	public function postAgruparExistencia(){
		$usu_register = \Session::get('usuario');
		$bandera = false;
		$inputs = \Input::all();
		$id_Producto = $inputs['hd_id'];
		$nuevo_costo = $inputs['txt_prec_costo'];
		$prec_Distribuidor = $inputs['txt_prec_dist'];
		$prec_Publico = $inputs['txt_prec_pub'];

		$reg_inv_prod = \Inventario::reg_exist_agrup($id_Producto)->get();

		if(count($reg_inv_prod) > 1){

			$total_existencia = \Inventario::reg_exist_agrup($id_Producto)->sum('Existencia');
			\Inventario::whereRaw('Estado = ? AND MD5(idProducto) = ?', [1, $id_Producto])
								->update(['Estado' => 0, 'Comentario' => 'Registro Actualizado por '.$usu_register.' se dio agrupamiento de existencias']);

			$inv = new \Inventario();
			$inv->idProducto = $reg_inv_prod[0]->idProducto;
			$inv->Existencia = $total_existencia;
			$inv->Costo = $nuevo_costo;
			$inv->PrecDistribuidor = $prec_Distribuidor;
			$inv->PrecPublico = $prec_Publico;
			$inv->Estado = 1;
			$inv->Comentario = 'Agregado por '.$usu_register.' despúes de haber agrupado existencias anteriores';
			$inv->save();					

		}else{
			$bandera = true;
		}

		if($bandera){
			//0: si hubo error, 1: no hubo error
			$resultado = ['errors' => '0', 'mensaje' => 'Error al actualizar, intente de nuevo'];
			return $resultado;
		}

		//0: si hubo error, 1: no hubo error
		$resultado = ['errors' => '1', 'mensaje' => 'Registro Actualizado'];
		return $resultado;
	}

	public function getPrueba(){		
		return \Inventario::reg_exist_agrup('a5771bce93e200c36f7cd9dfd0e5deaa')->get()[0]->idProducto;
	}

}