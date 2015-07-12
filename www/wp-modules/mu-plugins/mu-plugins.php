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
	function load_mu_plugins() {

		/**
		 * Load must-use plugins.
		 */
		foreach ( wp_get_mu_plugins() as $mu_plugin ) {

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

}
_WP_MU_Plugins::on_load();

