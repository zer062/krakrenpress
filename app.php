<?php


define( 'APP_BASE_PATH', dirname(plugin_dir_path(__FILE__)));

define( 'APP_PATH', dirname(__FILE__) );

define( 'APP_URL', plugin_dir_url('alab'));

define( 'APP_CONFIG_PATH', APP_PATH . '/config' );

define( 'APP_CORE_PATH', APP_PATH . '/core' );

define( 'APP_VENDORS_PATH', APP_PATH . '/vendors' );

define( 'APP_PLUGINS_PATH', APP_PATH . '/plugins' );

define( 'APP_HOOK_PATH', APP_PATH . '/hooks' );

define( 'APP_VERSION', '1.0.0' );

define( 'APP_DOMAIN', 'app' );

require_once ( APP_CORE_PATH . '/autoload.php' );

if (file_exists(  APP_PATH . '/helpers/helpers.php' ) ) {
	require_once ( APP_PATH . '/helpers/helpers.php' );
}
