<?php

define( 'APP_BASE_PATH', get_stylesheet_directory() );

define( 'APP_PATH', APP_BASE_PATH . '/app' );

define( 'APP_URL', get_stylesheet_directory_uri() . '/app' );

define( 'APP_CONFIG_PATH', APP_PATH . '/config' );

define( 'APP_CORE_PATH', APP_PATH . '/core' );

define( 'APP_VENDORS_PATH', APP_PATH . '/vendors' );

define( 'APP_PLUGINS_PATH', APP_PATH . '/plugins' );

define( 'APP_HOOK_PATH', APP_PATH . '/hooks' );

define( 'APP_VERSION', '1.0.0' );

define( 'APP_DOMAIN', 'app' );

require_once ( APP_CORE_PATH . '/autoload.php' );

require_once ( APP_PATH . '/helpers/helpers.php' );
