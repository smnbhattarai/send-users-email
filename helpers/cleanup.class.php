<?php
class Send_Users_Email_cleanup {

	public static function cleanupEmailProgress() {

		$options = get_option( 'sue_send_users_email' );
		$user_id = get_current_user_id();
		unset( $options[ 'email_users_email_send_start_' . $user_id ] );
		unset( $options[ 'email_users_total_email_send_' . $user_id ] );
		unset( $options[ 'email_users_total_email_to_send_' . $user_id ] );
		update_option( 'sue_send_users_email', $options );

	}

}