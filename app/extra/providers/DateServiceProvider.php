<?php
namespace VitaSalud\Extra\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use VitaSalud\Extra\Helpers\Date;

class DateServiceProvider extends ServiceProvider {

	public function register(){
		$this->app['mydateclass'] = $this->app->share(function($app){
            return new Date();
        });
        $this->app->booting(function(){
            $loader = AliasLoader::getInstance();
            $loader->alias('Date', 'VitaSalud\Extra\Facades\Date');
        });
	}

}