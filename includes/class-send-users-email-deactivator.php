<?php

/**
 * Fired during plugin deactivation.
 */
class Send_Users_Email_Deactivator {

	public static function deactivate() {
	    // need a variable to access so we can call a method in the send_users_email_admin class			
		$plugin = new Send_Users_Email();
		$plugin_admin = new Send_Users_Email_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

		// tell the plugin admin class to do the deactivate actions
		$plugin_admin->send_users_email_deactivate_admin_actions();
	}

}
