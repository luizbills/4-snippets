<?php

/* Load the Composer autoload */
$autoload = Plugin_4_Snippets::DIR . '/vendor/autoload.php';
if ( file_exists( $autoload ) {
	include $autoload;
} else {
	wp_die( "<code>$autoload</code> not found!" );
}
