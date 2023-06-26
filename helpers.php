<?php

if ( file_exists( Plugin_4_Snippets::DIR . '/vendor/autoload.php' ) ) {
    include Plugin_4_Snippets::DIR . '/vendor/autoload.php';
}

if ( ! function_exists( 'dd' ) ) :
/**
 * @param mixed ...$values
 * @return never
 */
function dd ( ...$values ) {
    if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) return;
    foreach ( $values as $x ) {
        echo '<pre>';
        var_dump( $x );
        echo '</pre>';
    }
    die;
}
endif;

if ( ! function_exists( 'err_log' ) ) :
/**
 * @param mixed ...$values
 * @return void
 */
function err_log ( ...$values ) {
    if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) return;
    foreach ( $values as $x ) {
        ob_start();
        var_dump( $x );
        error_log( trim( ob_get_clean() ) );
    }
}
endif;
