<?php

/**
 * Class WP_Modules
 */
class WP_Modules {

	/**
	 * @var null|array
	 */
	private static $_modules;

	/**
	 * Run tasks required on load of this file.
	 */
	static function on_load() {

		/**
		 * Registers this class to "help" the WP class
		 */
		WP::register_helper( __CLASS__ );

	}

	/**
	 * Return if a module is an active core module for the given type
	 *
	 * @param string $module_slug Directory name of module
	 *
	 * @return bool
	 */
	static function is_core_module( $module_slug ) {

		return self::is_module( $module_slug, 'core' );

	}

	/**
	 * Return if a module is an active core module for the given type
	 *
	 * @param string $module_slug Directory name of module
	 *
	 * @return bool
	 */
	static function is_site_module( $module_slug ) {

		return self::is_module( $module_slug, 'site' );

	}

	/**
	 * Return if a module is an active module for the given type
	 *
	 * @param string $module_slug Directory name of module
	 * @param string $module_type Type of module: 'core' or 'site' or 'any'
	 *
	 * @return bool
	 *
	 * @todo implement $module_type => 'any'
	 */
	static function is_module( $module_slug, $module_type = 'any' ) {

		$modules = self::get_modules( $module_type );

		return isset( $modules[ $module_slug ] );

	}

	/**
	 * Return the list of active modules for the given type
	 *
	 * @param string $module_type Type of module: 'core' or 'site'
	 *
	 * @return array
	 */
	static function get_modules( $module_type ) {

		do {  // Do is used here like try{}catch, but only affects the current method. Better than nested IFs.

			if ( ! preg_match( '#^(core|site)$#', $module_type ) ) {
				/**
				 * Return an empty array if not core or site modules
				 * @todo Add plugin and theme modules later
				 */
				self::$_modules[ $module_type ] = array();
				break;

			}

			if ( isset( self::$_modules[ $module_type ] ) ) {
				/**
				 * We already know the module. Break out.
				 */
				break;

			}

			$cache_key = "wp_{$module_type}_modules";

			if ( self::$_modules[ $module_type ] = wp_cache_get( $cache_key ) ) {

				break;

			}

			switch ( $module_type ) {
				case 'core':
					self::$_modules[ 'core' ] = self::_scan_modules( WP_MODULES_DIR );
					break;

				case 'site':
					self::$_modules[ 'site' ] = self::_scan_modules( WP_CONTENT_DIR . '/modules' );
					break;

			}

			/**
			 * Allow modules to be removed.
			 */
			self::$_modules[ $module_type ] = apply_filters(
				'active_modules',
				self::$_modules[ $module_type ],
				$module_type
			);

			/**
			 * Save it for improved performance with persistent caches
			 */
			wp_cache_set( $cache_key, self::$_modules[ $module_type ] );

		} while ( false );

		return self::$_modules[ $module_type ];

	}

	/**
	 * Loads the active modules
	 *
	 * @param string $module_type Type of module: 'core' or 'site'
	 */
	static function load_modules( $module_type ) {

		foreach ( self::get_modules( $module_type ) as $module_file ) {

			require( $module_file );

		}

	}
	/**
	 * Return array of module names and their files found in a given directory
	 *
	 *  Modules are defined by a PHP file with the same name of it containing directory,
	 *
	 *      i.e. "{$dir}/my-module/my-module.php"
	 *
	 * @param string $dir
	 *
	 * @return array
	 */
	static function _scan_modules( $dir ) {

		$modules = array();

		foreach ( scandir( $dir ) as $module ) {

			if ( '.' === $module || '..' === $module ) {

				continue;

			}

			$module_dir = "{$dir}/{$module}";

			if ( is_dir( $module_dir ) && is_file( $module_file = "{$module_dir}/{$module}.php" ) ) {

				$modules[ $module ] = $module_file;

		    }

		}

		return $modules;

	}

}
WP_Modules::on_load();
