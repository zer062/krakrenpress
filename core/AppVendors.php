<?php

namespace Core;

class AppVendors {

	public function __construct() {
		$this->load_vendors();
		$this->load_integrated_plugins();
	}

	public function load_vendors() {
		$settings = include ( APP_CONFIG_PATH . '/app.php' );
		$vendors = $settings[ 'vendors' ];

		if ( !is_null( $vendors ) && !empty( $vendors ) ) {
			foreach ( $vendors as $vendor ) {
				include APP_VENDORS_PATH . $vendor;
			}
		}
	}

	protected function load_integrated_plugins() {
		$settings = include ( APP_CONFIG_PATH . '/app.php' );
		$plugins = $settings[ 'plugins' ];

		if ( !is_null( $plugins ) || !empty( $plugins ) ) {

			foreach ( $plugins as $plugin ) {
				include APP_PLUGINS_PATH . '/' .$plugin;
//				new $plugin();
			}
		}

	}
}
