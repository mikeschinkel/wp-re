<?php

/**
 * Class WP_Helper - Provides "Helper" functionality for WP and maybe other classes.
 */
class WP_Helper {

	/**
	 * List of helper classes for the WP class
	 *
	 * @var array
	 */
	private static $_helpers;

	/**
	 * Registers helper methods for WP or another class.
	 *
	 * @param string $helper_class
	 * @param string $class_to_help
	 */
	static function _register_helper( $helper_class, $class_to_help  ) {

		$methods = array_fill_keys( array_filter( get_class_methods( $helper_class ),
			function( $element ) {
				return '__' !== substr( $element, 0, 2 );
			} ),
			new $helper_class()
		);

		unset( $methods[ 'on_load' ] );

		if ( ! isset( self::$_helpers[ $class_to_help ] ) ) {

			self::$_helpers[ $class_to_help ] = $methods;

		} else {

			self::$_helpers[ $class_to_help ] += $methods;

		}

	}

	/**
	 * Calls registered helper methods for the class being helped.
	 *
	 * @param string $class_helped
	 * @param string $helper_method
	 * @param array $args
	 * @return mixed
	 */
	static function _call_helper( $class_helped, $helper_method, $args ) {

		if ( isset( self::$_helpers[ $class_helped ][ $helper_method ] ) ) {

			return call_user_func_array( array( self::$_helpers[ $class_helped ][ $helper_method ], $helper_method ), $args );

		} else {

			$message = 'There is no helper method %s registered for class %s.';

			trigger_error( sprintf( $message, $helper_method, $class_helped ), E_USER_ERROR );

			return null;

		}

	}

}
