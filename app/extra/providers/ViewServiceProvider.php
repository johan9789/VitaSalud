<?php
namespace VitaSalud\Extra\Providers;

use Acceso;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {

    public function register(){
        $this->app['view']->composer('accesos', function($view){
            $view->with('accesos', Acceso::lista()->get());
            $view->with('sub_accesos', function($id_acceso){
                return Acceso::subLista($id_acceso)->get();
            });
        });
    }

}