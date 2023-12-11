<?php


namespace Core;


class AppAjax {

	/**
	 * @var array
	 */
	protected $actions = [];

	/**
	 * AppAjax constructor.
	 */
	public function __construct() {
		$this->load_ajax();
		$this->register_actions();
	}

	/**
	 * @return void
	 */
	protected function load_ajax() {
		$settings = include ( APP_CONFIG_PATH . '/app.php' );
		$all_actions = scandir( $settings['ajax_path'] );

		foreach ( $all_actions as $ajax ) {
			if ( $ajax === '.' || $ajax === '..' ) continue;
			$ajax_class = str_replace('.php', '', "\Ajax\\{$ajax}" );
			$this->actions[] = new $ajax_class();
		}
	}

	/**
	 * Register all ajax actions
	 * @return void
	 */
	protected function register_actions() {

		foreach ($this->actions as $action) {
			$the_action = new $action();

			if (is_null($the_action->action)) continue;

			if ($the_action->public_request) {
				add_action('wp_ajax_' . $the_action->action, function () use($the_action) {
					$the_action->params = isset($_POST) ? $_POST : [];
					$the_action->run_action();
				});
			}

			if ($the_action->public_request) {
				add_action('wp_ajax_nopriv_' . $the_action->action, function () use($the_action) {
					$the_action->params = isset($_POST) ? $_POST : [];
					$the_action->run_action();
				});
			}
		}
	}
}
