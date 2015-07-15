<?php

/**
 * Class _WP_MU_Plugins
 */
class _WP_MU_Plugins extends WP_Module {

	/**
	 *
	 */
	static function on_load() {

		WP::register_helper( __CLASS__ );

	}

	/**
	 * Load the Must User Plugins
	 */
	static function load_mu_plugins() {

		/**
		 * Load must-use plugins.
		 */
		foreach ( self::get_mu_plugins() as $mu_plugin ) {

			include_once( $mu_plugin );

		}

		/**
		 * Load network activated plugins.
		 */
		if ( is_multisite() ) {

			foreach( wp_get_active_network_plugins() as $network_plugin ) {

				wp_register_plugin_realpath( $network_plugin );

				include_once( $network_plugin );

			}

		}

		/**
		 * Fires once all must-use and network-activated plugins have loaded.
		 *
		 * @since 2.8.0
		 */
		do_action( 'muplugins_loaded' );
    }

    static function get_mu_plugins() {
        $mu_plugins = array();
        if ( !is_dir( WPMU_PLUGIN_DIR ) )
            return $mu_plugins;
        if ( ! $dh = opendir( WPMU_PLUGIN_DIR ) )
            return $mu_plugins;
        while ( ( $plugin = readdir( $dh ) ) !== false ) {
            if ( substr( $plugin, -4 ) == '.php' )
                $mu_plugins[] = WPMU_PLUGIN_DIR . '/' . $plugin;
        }
        closedir( $dh );
        sort( $mu_plugins );

        return $mu_plugins;
    }
}

_WP_MU_Plugins::on_load();
