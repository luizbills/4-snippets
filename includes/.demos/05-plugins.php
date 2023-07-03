<?php

/* Load plugins inside 'includes/_plugin' directory */
$dir = Plugin_4_Snippets::DIR . '/_plugins';
$files = scandir( $dir );

foreach( $files as $dirname ) {
	if ( '.' === $dirname ) continue;
	if ( '..' === $dirname ) continue;

	$path = path_join( $dir, $dirname );
	if ( ! is_dir( $path ) ) return;

	$main_files = [
		"{$dirname}.php",
		'main.php',
	];

	foreach ( $main_files as $main_file ) {
		$main_file_path = path_join( $path, $main_file );
		if ( file_exists( $main_file_path ) && ! is_dir( $main_file_path ) ) {
			include $main_file_path;
			err_log( 'plugin loaded: ' . $dirname );
			break;
		}
	}
}
