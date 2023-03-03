<?php


namespace Core\Tentacles;


abstract class Ajax {

	/**
	 * @var string
	 */
	public $action;

	/**
	 * @var string
	 */
	public $nonce;

	/**
	 * @var bool
	 */
	public $public_request = true;

	/**
	 * @var bool
	 */
	public $private_request = true;

	/**
	 * @var array
	 */
	public $params = [];

	/**
	 * Ajax constructor.
	 */
	public function __construct() {
		$this->nonce = $this->action . '_nonce';
	}

	public function get_nonce() {
		return $this->nonce;
	}

	protected function validate_nonce() {
		if (!wp_verify_nonce($this->params[$this->nonce], $this->nonce)) {
			wp_send_json_error('Invalid token', '401');
		}
	}

	public function run_action() {
		$this->validate_nonce();
		$this->handle();
	}

	/**
	 * handle ajax function
	 */
	public function handle() {}
}
