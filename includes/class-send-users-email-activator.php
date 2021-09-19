<?php

/**
 * Fired during plugin activation.
 */
class Send_Users_Email_Activator {

	public static function activate() {
		update_option( 'sue_send_users_email', [] );
	}

}
