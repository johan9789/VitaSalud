<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** Recursos */
Route::resource('accesos', 'Resources\AccesosController', ['only' => ['destroy']]);
Route::resource('empresas', 'Resources\EmpresasController', ['only' => ['store', 'show']]);
Route::resource('clientes', 'Resources\ClientesController', ['only' => ['store', 'show']]);

/** Compras */
Route::group(['prefix' => 'compras'], function(){
	Route::group(['prefix' => 'dist'], function(){
		Route::resource('reportes', 'Compras\Distribuidor\ReportesController', ['only' => ['index', 'show']]);
		Route::resource('', 'Compras\Distribuidor\HomeController', ['only' => ['index', 'store', 'update']]);
	});
	Route::group(['prefix' => 'adm'], function(){
		Route::resource('reportes', 'Compras\Administrador\ReportesController', ['only' => ['index', 'show']]);
		Route::resource('', 'Compras\Administrador\HomeController', ['only' => ['index', 'store', 'update']]);
	});
});

/** Ventas */
Route::group(['prefix' => 'ventas'], function(){
	Route::group(['prefix' => 'adm'], function(){
		Route::controller('graficos', 'Ventas\Administrador\GraficosController');
		Route::controller('reportes', 'Ventas\Administrador\ReportesController');
	});
	Route::group(['prefix' => 'dist'], function(){
		Route::controller('reportes', 'Ventas\Distribuidor\ReportesController');
	});	
	Route::controller('', 'Ventas\HomeController');
});

/** Seccíón de pedidos */
Route::group(['prefix' => 'pedidos'], function(){
	/** Pedidos por parte del distribuidor */
	Route::group(['prefix' => 'dist'], function(){
		Route::post('/eliminar-pendiente', 'Pedidos\DistController@eliminar_pendiente');
		Route::post('/ocultar-pedido', 'Pedidos\DistController@ocultar_pedido');
		Route::post('/agregar-inventario', 'Pedidos\DistController@agregar_inventario');
		Route::post('/modificar-ya/', 'Pedidos\DistController@modificar_ya');
		Route::get('/modificar/{id_pedido?}', 'Pedidos\DistController@modificar');
		Route::post('/confirmar-pendiente', 'Pedidos\DistController@confirmar_pendiente');
		Route::post('/ver-detalle', 'Pedidos\DistController@ver_detalle');
		Route::post('/confirmar-ya', 'Pedidos\DistController@confirmar_ya');
		Route::get('/confirmar', 'Pedidos\DistController@confirmar');
		Route::post('/realizar-ya', 'Pedidos\DistController@realizar_ya');
		Route::get('/realizar', 'Pedidos\DistController@realizar');
		Route::get('/', 'Pedidos\DistController@index');
	});	
	/** Revisión de pedidos por parte del administrador */
	Route::controller('adm', 'Pedidos\AdministradorController');
});

/** Acceso a Distribuidor*/
Route::group(['prefix' => 'distribuidor'], function(){
	//Route::get('inventario/movimientos/{op?}', 'Distribuidor\Gestion\InventarioController@movimientos');
	//Route::post('inventario/movimiento', 'Distribuidor\Gestion\InventarioController@movimientos');
	Route::controller('inventario', 'Distribuidor\Gestion\InventarioController');
	Route::controller('productos', 'Distribuidor\Gestion\ProductosController');
});

/** Acceso directo a catálogo de productos */
Route::get('productos/catalogo', 'Gestion\ProductosController@getCatalogo');

/** Gestión */
Route::group(['prefix' => 'gestion'], function(){
	/** Gestión de Proveedores */
	Route::group(['prefix' => 'proveedores'], function(){
		Route::resource('dist', 'Gestion\Proveedores\Distribuidor\HomeController', ['except' => ['create', 'edit']]);
		Route::resource('adm', 'Gestion\Proveedores\Administrador\HomeController', ['except' => ['create', 'edit']]);		
	});
	/** Gestión de Clientes */
	Route::controller('clientes', 'Gestion\ClientesController');
	/** Gestión de usuarios */
	Route::group(['prefix' => 'usuarios'], function(){
		Route::resource('roles', 'Gestion\Usuarios\RolesController', ['only' => ['index', 'store']]);
        Route::controller('privilegios', 'Gestion\Usuarios\PrivilegiosController');
		Route::controller('', 'Gestion\Usuarios\HomeController');
	});
	/** Gestión de Productos */
	Route::controller('productos', 'Gestion\ProductosController');
	/** Gestión de Inventarios */
	Route::controller('inventario', 'Gestion\InventarioController');
});

/** En espera... */
Route::controller('mi-cuenta', 'MiCuentaController');
Route::controller('mi-oficina', 'MiOficinaController');

/** Editar perfil de usuario */
Route::controller('perfil', 'PerfilController');

/** Inicio */
Route::controller('/', 'HomeController');