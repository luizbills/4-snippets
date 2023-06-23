<?php
/**
 * Plugin Name:  4 Snippets
 * Description:  Simple plugin for snippets in your development envoriment. Just put the code inside the plugin <code>includes</code> directory. Files that starts with <code>_</code> (underscore) will not be executed.
 * Version:      1.0.0
 * Plugin URI:   https://github.com/luizbills/4-snippets
 * Author:       Luiz Bills
 * Author URI:   https://github.com/luizbills
 * Text Domain:  wp-codes
 * Requires PHP: 7.4
 * Update URI:   false
 */

defined( 'WPINC' ) || die;

final class WP_Codes {
	const FILE = __FILE__;
	const DIR = __DIR__;

	public function __construct () {
		include_once self::DIR . '/helpers.php';
		add_action( 'plugins_loaded', [ $this, 'include_enabled_codes' ] );
	}

	public function include_enabled_codes () {
		$files = $this->rscandir( self::DIR . '/includes' );

		foreach ( $files as $filepath ) {
			$info = pathinfo( $filepath );
			if ( 'php' !== $info['extension'] ) continue;
			if ( '_' === substr( $info['filename'], 0, 1 ) ) continue;

			include $filepath;
		}
	}

	protected function rscandir ( $dir ) {
		$files = scandir( $dir );
		$result = [];

		foreach( $files as $entry ) {
			if ( '.' === $entry ) continue;
			if ( '..' === $entry ) continue;

			$entry = "$dir/$entry";
			if ( ! is_dir( $entry ) ) {
				$result[] = $entry;
			} else {
				$scandir = $this->rscandir( $entry );
				$result = $scandir ? array_merge( $result, $scandir ) : $result;
			}
		}

		return $result;
	}
}

new WP_Codes();