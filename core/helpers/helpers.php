<?php

/**
 * Debug vars
 * @return void
 */
function debug() {

    foreach ( func_get_args() as $arg ) {
        var_dump( $arg );
    }

    die;
}

function view($path, $data = []) {
	ob_start();
	extract($data);
	include_once ( APP_PATH . '/views/customer-documents.php' );
	$content = ob_get_contents();
	ob_clean();

	return $content;
}
