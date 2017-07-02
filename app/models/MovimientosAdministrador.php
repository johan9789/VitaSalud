<?php
class MovimientosAdministrador extends Eloquent {
	protected $table = 'movimientos_adm';
	protected $primaryKey = 'idMovimiento';
	public $timestamps = false;
}