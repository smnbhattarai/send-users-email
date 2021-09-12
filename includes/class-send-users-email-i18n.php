<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://sumanbhattarai.com.np
 * @since      1.0.0
 *
 * @package    Send_Users_Email
 * @subpackage Send_Users_Email/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Send_Users_Email
 * @subpackage Send_Users_Email/includes
 * @author     Suman Bhattarai <smnbhattarai4@gmail.com>
 */
class Send_Users_Email_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'send-users-email',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
