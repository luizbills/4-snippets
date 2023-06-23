<?php
/**
 * Plugin Name:  4 Snippets
 * Description:  Simple plugin for snippets in your development envoriment. Just put the code inside the plugin's <code>includes</code> directory. Files that starts with <code>_</code> (underscore) will not be executed.
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

		$snippets_dir = self::DIR . '/includes/';
		if ( ! file_exists( $snippets_dir ) ) return;

		$files = $this->get_snippets_files( $snippets_dir );

		foreach ( $files as $filepath ) {
			// include $filepath;
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
	 * Find PHP files inside a directory and its subdirectories,
	 * but ignore files or folders thats starts with _ (undescore).
	 *
	 * @param string $dir
	 * @return string[] List of file paths
	 */
	protected function get_snippets_files ( $dir ) {
		$files = scandir( $dir );
		$result = [];

		foreach( $files as $filename ) {
			if ( '.' === $filename ) continue;
			if ( '..' === $filename ) continue;
			if ( '_' === substr( $filename, 0, 1 ) ) continue;

			$path = path_join( $dir, $filename );

			if ( is_dir( $path ) ) {
				$scandir = $this->get_snippets_files( $path );
				$result = $scandir ? array_merge( $result, $scandir ) : $result;
			} else {
				$info = pathinfo( $path );
				if ( 'php' === $info['extension'] ) {
					$result[] = $path;
				}
			}
		}

		return $result;
	}
}

new Plugin_4_Snippets();
