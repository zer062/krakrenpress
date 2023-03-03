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

