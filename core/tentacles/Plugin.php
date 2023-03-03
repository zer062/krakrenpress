<?php

namespace Core\Tentacles;

abstract class Plugin {

	/**
	 * AppPlugins constructor.
	 */
	public function __construct() {
		$this->setup();
	}

	/**
	 * Setup the plugin definitions oif necessary
	 */
	public function setup() {
	}
}
