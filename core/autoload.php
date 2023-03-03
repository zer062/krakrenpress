<?php

require_once ( APP_CORE_PATH . '/helpers/helpers.php' );
require_once ( APP_CONFIG_PATH . '/app.php' );

/**
 * Autoload app classes
 *
 * @return void
 */
spl_autoload_register( function ( $class ) {

    $explode_package = explode( '\\', $class );
    $class_path = get_package_path( strtolower( $explode_package[0] ) );

    foreach ( $explode_package as $path => $package_path ){
        if ( $path === 0) continue;
        $class_path .=  $path === ( count( $explode_package ) - 1)
            ? DIRECTORY_SEPARATOR . ucfirst( $package_path )
            : DIRECTORY_SEPARATOR . strtolower( $package_path );
    }

    if ( file_exists( $class_path . '.php' ) ) {
        require_once ( $class_path . '.php' );
    }
});

/**
 * Get class path by package name
 *
 * @param $package
 * @return mixed|string
 */
function get_package_path( $package ) {
    $app_settings = include ( APP_CONFIG_PATH . '/app.php' );

    switch ( $package ) {

        case 'model':
            return $app_settings['models_path'];
            break;

	    case 'taxonomy':
		    return $app_settings['taxonomies_path'];
		    break;

	    case 'hook':
		    return $app_settings['hooks_path'];
		    break;

	    case 'shortcode':
		    return $app_settings['shortcodes_path'];
		    break;

	    case 'ajax':
		    return $app_settings['ajax_path'];
		    break;

	    case 'jobs':
		    return $app_settings['jobs_path'];
		    break;

	    case 'vendors':
		    return APP_VENDORS_PATH;
		    break;

	    case 'plugins':
		    return APP_PLUGINS_PATH;
		    break;

		    default:
            return APP_CORE_PATH;
            break;
    }
}

$app = \Core\App::getInstance();
