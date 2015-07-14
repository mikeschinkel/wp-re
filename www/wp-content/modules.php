<?php

class _WP_Remove_Core_Modules {

	static function on_load() {

		add_action( 'active_modules', array( __CLASS__, '_active_modules' ) );

	}

	static function _active_modules( $modules ) {

		return array();

	}

}
_WP_Remove_Core_Modules::on_load();
