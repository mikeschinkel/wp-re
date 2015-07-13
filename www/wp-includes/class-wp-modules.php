<?php

/**
 * Class WP_Modules
 */
class WP_Modules {

	/**
	 * @var null|array
	 */
	private $_modules;

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
	function is_core_module( $module_slug ) {

		return $this->is_module( $module_slug, 'core' );

	}

	/**
	 * Return if a module is an active core module for the given type
	 *
	 * @param string $module_slug Directory name of module
	 *
	 * @return bool
	 */
	function is_user_module( $module_slug ) {

		return $this->is_module( $module_slug, 'user' );

	}

	/**
	 * Return if a module is an active module for the given type
	 *
	 * @param string $module_slug Directory name of module
	 * @param string $module_type Type of module: 'core' or 'user' or 'any'
	 *
	 * @return bool
	 *
	 * @todo implement $module_type => 'any'
	 */
	function is_module( $module_slug, $module_type = 'any' ) {

		$modules = $this->get_modules( $module_type );

		return isset( $modules[ $module_slug ] );

	}

	/**
	 * Return the list of active modules for the given type
	 *
	 * @param string $module_type Type of module: 'core' or 'user'
	 *
	 * @return array
	 */
	function get_modules( $module_type ) {

		do {

			if ( ! preg_match( '#^(core|user)$#', $module_type ) ) {
				/**
				 * Return an empty array if not core or user modules
				 * @todo Add plugin and theme modules later
				 */
				$this->_modules[ $module_type ] = array();
				break;

			}

			if ( isset( $this->_modules[ $module_type ] ) ) {

				break;

			}

			$cache_key = "wp_{$module_type}_modules";

			if ( $this->_modules[ $module_type ] = wp_cache_get( $cache_key ) ) {

				break;

			}

			switch ( $module_type ) {
				case 'core':
					$this->_modules[ 'core' ] = $this->_scan_modules( WP_MODULES_DIR );
					break;

				case 'user':
					$this->_modules[ 'user' ] = $this->_scan_modules( WP_CONTENT_DIR . '/modules' );
					break;

			}

			/**
			 * Allow modules to be removed.
			 */
			$this->_modules[ $module_type ] = apply_filters(
				'active_modules',
				$this->_modules[ $module_type ],
				$module_type
			);

			/**
			 * Save it for improved performance with persistent caches
			 */
			wp_cache_set( $cache_key, $this->_modules[ $module_type ] );

		} while ( false );

		return $this->_modules[ $module_type ];

	}

	/**
	 * Loads the active modules
	 *
	 * @param string $module_type Type of module: 'core' or 'user'
	 */
	function load_modules( $module_type ) {

		foreach ( $this->get_modules( $module_type ) as $module_file ) {

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
	function _scan_modules( $dir ) {

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
