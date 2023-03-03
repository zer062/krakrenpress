<?php


namespace Core\Tentacles;


abstract class ShortCode {

	/**
	 * @var string
	 */
	public $shortcode = '';

	/**
	 * @var array
	 */
	public $attributes = [];

	/**
	 * @var mixed|null
	 */
	public $content = null;

	/**
	 * @var array
	 */
	public $js = '';

	/**
	 * @var array
	 */
	public $css = '';

	public function __construct() {
	}

	/**
	 * @return mixed
	 */
	public function output() {
		return $this->content;
	}
}
