<?php

/**
 * Class Test_Modular_Mu_Plugins
 */
class Test_Modular_Mu_Plugins {

	static function on_load() {

		add_action( 'admin_notices', array( __CLASS__, '_admin_notices' ) );

	}

	static function _admin_notices() {
		echo <<<HTML
<style type="text/css">
p { font-size:3em; color:red; font-weight: bold; }
</style>
<div class="updated">
<p>Modular MU Plugins Loaded!</p>
</div>
HTML;
	}

}
Test_Modular_Mu_Plugins::on_load();
