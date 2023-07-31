<?php

/* Load plugins in '{plugin root}/plugins' directory */
$dir = Plugin_4_Snippets::DIR . '/plugins';
$files = scandir( $dir );

foreach( $files as $dirname ) {
	if ( '.' === $dirname ) continue;
	if ( '..' === $dirname ) continue;

	/* only loads directories */
	$path = path_join( $dir, $dirname );
	if ( ! is_dir( $path ) ) continue;

	/* ignore directorys that starts starts with dot or undescore */
	$first_char = substr( $dirname, 0, 1 );
	if ( '_' === $first_char ) continue;
	if ( '.' === $first_char ) continue;

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
