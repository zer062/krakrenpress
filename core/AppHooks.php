<?php


namespace Core;


use Core\Abstracts\Hook;

class AppHooks {

	/**
	 * @var array
	 */
	protected $hooks = [];

	/**
	 * AppHooks constructor.
	 */
	public function __construct() {
		$this->load_hooks();
		$this->run_hooks();
	}

	/**
	 * load hooks
	 */
	protected function load_hooks() {
		$all_hooks = scandir( (new \Core\AppSettings)->get_app_setting( 'hooks_path' ) );

		foreach ( $all_hooks as $hook ) {
			if ( $hook === '.' || $hook === '..' ) continue;
			$hook_class = str_replace('.php', '', "\Hook\\{$hook}" );
			$this->hooks[] = new $hook_class();
		}
	}

	/**
	 * run all registred hooks
	 */
	public function run_hooks() {

		foreach ($this->hooks as $hook) {

			if ($hook->is_filter) {
				$this->executeFilter($hook);
			} else {
				$this->executeHook($hook);
			}
		}
	}

	/**
	 * @param Hook $hook
	 */
	protected function executeFilter(Hook $hook) {

		if ( is_array( $hook->action ) ) {

			foreach ( $hook->action as $action ) {
				add_filter($action, function() use($hook) {
					$params = func_get_args();

					$hookInstance = new $hook();

					if (count( $hookInstance->params ) > 0) {
						$hookInstance->params = [];

						foreach ($hook->params as $key => $paramName) {
							$hookInstance->params[$paramName] = isset($params[$key]) ? $params[$key] : null;
						}
					} else {
						$hookInstance->params = $params;
					}

					return $hookInstance->handle();
				}, $hook->priority, count($hook->params));
			}
		} else {
			add_filter($hook->action, function() use($hook) {
				$params = func_get_args();

				$hookInstance = new $hook();

				if (count( $hookInstance->params ) > 0) {
					$hookInstance->params = [];

					foreach ($hook->params as $key => $paramName) {
						$hookInstance->params[$paramName] = isset($params[$key]) ? $params[$key] : null;
					}
				} else {
					$hookInstance->params = $params;
				}

				return $hookInstance->handle();
			}, $hook->priority, count($hook->params));
		}
	}

	/**
	 * @param Hook $hook
	 */
	protected function executeHook(Hook $hook) {

		if ( is_array( $hook->action ) ) {

			foreach ( $hook->action as $action ) {

				add_action($action, function() use($hook) {
					$params = func_get_args();

					$hookInstance = new $hook();

					if (count( $hookInstance->params ) > 0) {
						$hookInstance->params = [];

						foreach ($hook->params as $key => $paramName) {
							$hookInstance->params[$paramName] = isset($params[$key]) ? $params[$key] : null;
						}
					} else {
						$hookInstance->params = $params;
					}

					$hookInstance->handle();
				}, $hook->priority, count($hook->params));
			}
		} else {

			add_action($hook->action, function() use($hook) {
				$params = func_get_args();

				$hookInstance = new $hook();

				if (count( $hookInstance->params ) > 0) {
					$hookInstance->params = [];

					foreach ($hook->params as $key => $paramName) {
						$hookInstance->params[$paramName] = isset($params[$key]) ? $params[$key] : null;
					}
				} else {
					$hookInstance->params = $params;
				}

				$hookInstance->handle();
			}, $hook->priority, count($hook->params));
		}

	}
}