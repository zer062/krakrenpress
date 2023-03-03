<?php


namespace Core\Tentacles;


abstract class Hook {

	/**
	 * @var string
	 */
	public $action;

	/**
	 * @var int
	 */
	public $priority = 10;

	/**
	 * @var array
	 */
	public $params = [];

	/**
	 * @var bool
	 */
	public $is_filter = false;

	/**
	 * @param $name
	 * @param $value
	 */
	public function __set( $name, $value ) {
		$this->params[$name] = $value;
	}

	/**
	 * @param $name
	 */
	public function __get( $name ) {
		$this->params[$name];
	}

	public function handle() {
		// todo something...
	}
}
