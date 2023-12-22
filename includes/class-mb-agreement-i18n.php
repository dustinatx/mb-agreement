<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       codeable.io/developers/stacey-blaschke
 * @since      1.0.0
 *
 * @package    Mb_Agreement
 * @subpackage Mb_Agreement/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mb_Agreement
 * @subpackage Mb_Agreement/includes
 * @author     Stacey <codeable@sunlitstud.io>
 */
class Mb_Agreement_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mb-agreement',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
