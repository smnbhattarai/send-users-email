<?php

/**
 * Fired during plugin activation.
 */
class Send_Users_Email_Activator {

	public static function activate() {
		update_option( 'sue_send_users_email', [] );
		// need a variable to access so we can call a method in the send_users_email_admin class			
		$plugin = new Send_Users_Email();
		$plugin_admin = new Send_Users_Email_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

		// tell the plugin admin class to do the activate actions
		$plugin_admin->send_users_email_activate_admin_actions();
	}

}
