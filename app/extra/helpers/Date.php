<?php
namespace VitaSalud\Extra\Helpers;

class Date {

	public function __construct(){
		date_default_timezone_set('America/Lima');
	}

	public function current($format = ''){
		if(empty($format)){
			$format = 'Y-m-d';
		}
		return date($format);
	}

	public function hour($format = ''){
		if(empty($format)){
			$format = 'H:i:s';
		}
		return date($format);
	}

	public function both($format = ''){
		if(empty($format)){
			$format = 'Y-m-d H:i:s';			
		}
		return date($format);
	}

	public function format($date, $format = ''){
		if(empty($format)){
			$format = 'Y-m-d';
		}
		return date($format, strtotime($date));
	}

}