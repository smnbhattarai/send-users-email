<?php

/**
 * Fired during plugin activation
 *
 * @link       http://sumanbhattarai.com.np
 * @since      1.0.0
 *
 * @package    Send_Users_Email
 * @subpackage Send_Users_Email/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Send_Users_Email
 * @subpackage Send_Users_Email/includes
 * @author     Suman Bhattarai <smnbhattarai4@gmail.com>
 */
class Send_Users_Email_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( 'sue_send_users_email', [] );
	}

}
