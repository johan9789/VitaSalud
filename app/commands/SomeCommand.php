<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SomeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:some';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Aqui, comandeando ._.?';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){
		/* if($this->argument('view')){
			$html = "@extends('layout')\n\n";
			$html.= "@section('body')\n\n";
			$html.= "@stop\n\n";
			File::put('app/views/view.blade.php', $html);
		} elseif($this->argument('controller')){
			File::put('app/controllers/SomeController.php', '');
		}*/
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments(){
		return array(
			// array('view', InputArgument::OPTIONAL, 'An example argument.'),
			array('controller', InputArgument::REQUIRED, 'An example argument.')
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions(){
		return array(array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null));
	}

}
