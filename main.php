<?php
/**
 * Plugin Name:  4 Snippets
 * Description:  Simple plugin for snippets in your development envoriment. Just put the code inside the plugin <code>includes</code> directory. Files that starts with <code>_</code> (underscore) will not be executed.
 * Version:      1.1.0
 * Plugin URI:   https://github.com/luizbills/4-snippets
 * Author:       Luiz Bills
 * Author URI:   https://github.com/luizbills
 * Text Domain:  wp-4-snippets
 * Requires PHP: 7.4
 * Update URI:   false
 */

defined( 'WPINC' ) || die;

final class Plugin_4_Snippets {
	const FILE = __FILE__;
	const DIR = __DIR__;
	const SAFE_MODE_QUERY_ARG = 'disable-snippets';
	const SAFE_MODE_CONSTANT = 'DISABLE_SNIPPETS';

	public function __construct () {
		if ( $this->is_safe_mode() ) {
			add_filter( 'home_url', [ $this, 'add_safe_mode_query_arg' ] );
			add_filter( 'admin_url', [ $this, 'add_safe_mode_query_arg' ] );
			add_filter( 'login_url', [ $this, 'add_safe_mode_query_arg' ] );
			add_filter( 'logout_url', [ $this, 'add_safe_mode_query_arg' ] );
			add_action( 'login_form', [ $this, 'add_safe_mode_login' ] );
		}
		add_action( 'plugins_loaded', [ $this, 'execute_codes' ] );
	}

	/**
	 * Include and execute all allowed snippets
	 *
	 * @return void
	 */
	public function execute_codes () {
		if ( $this->is_safe_mode() ) return;

		include_once self::DIR . '/helpers.php';

		$files = $this->rscandir( self::DIR . '/includes' );

		foreach ( $files as $filepath ) {
			$info = pathinfo( $filepath );
			if ( 'php' !== $info['extension'] ) continue;
			if ( '_' === substr( $info['filename'], 0, 1 ) ) continue;

			include $filepath;
		}
	}

	/**
	 * @return void
	 */
	public function add_safe_mode_login () {
		?>
		<input type="hidden" name="<?= esc_attr( self::SAFE_MODE_QUERY_ARG ) ?>" value="">
		<?php
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public function add_safe_mode_query_arg ( $url ) {
		return add_query_arg( self::SAFE_MODE_QUERY_ARG, '', $url );
	}

	/**
	 * @return boolean
	 */
	protected function is_safe_mode () {
		return isset( $_REQUEST[ self::SAFE_MODE_QUERY_ARG ] )
			|| (
				defined( self::SAFE_MODE_CONSTANT )
				&& true === constant( self::SAFE_MODE_CONSTANT )
			);
	}

	/**
	 * Scan all files in a directory and its subdirectories
	 *
	 * @param string $dir
	 * @return string[] files paths
	 */
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

new Plugin_4_Snippets();
