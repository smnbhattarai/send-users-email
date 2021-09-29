<?php

/**
 * The admin-specific functionality of the plugin.
 */
class Send_Users_Email_Admin {

	private $plugin_name;

	private $version;

	/**
	 * Add all admin page slugs here ...
	 */
	private $plugin_pages_slug = array(
		'send-users-email',
		'send-users-email-users',
		'send-users-email-roles',
		'send-users-email-settings',
	);

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		// Add css to this plugin page only
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "";
		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_style( 'sue-bootstrap-5', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), '5.1.1', 'all' );
			wp_enqueue_style( 'sue-bootstrap-5-datatable', plugin_dir_url( __FILE__ ) . 'css/dataTables.bootstrap5.min.css', array( 'sue-bootstrap-5' ), '1.11.2', 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/send-users-email-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		// Add JS to this plugin page only
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "";
		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array( 'jquery' ), '5.1.1', true );
			wp_enqueue_script( 'datatable-js', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), '1.11.2', true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/send-users-email-admin.js', array( 'jquery' ), $this->version, true );
		}

	}


	/**
	 * Register admin menu Items
	 */
	public function admin_menu() {

		add_menu_page( __( "Send Users Email", "send-users-email" ),
			__( "Email to users", "send-users-email" ),
			'manage_options',
			'send-users-email',
			[ $this, 'admin_dashboard' ],
			'dashicons-email-alt2',
			250 );

		add_submenu_page(
			'send-users-email',
			__( 'Dashboard', "send-users-email" ),
			__( 'Dashboard', "send-users-email" ),
			'manage_options',
			'send-users-email',
			[ $this, 'admin_dashboard' ]
		);

		add_submenu_page(
			'send-users-email',
			__( 'Email Users', "send-users-email" ),
			__( 'Email Users', "send-users-email" ),
			'manage_options',
			'send-users-email-users',
			[ $this, 'users_email' ]
		);

		add_submenu_page(
			'send-users-email',
			__( 'Email Roles', "send-users-email" ),
			__( 'Email Roles', "send-users-email" ),
			'manage_options',
			'send-users-email-roles',
			[ $this, 'roles_email' ]
		);

		add_submenu_page(
			'send-users-email',
			__( 'Settings', "send-users-email" ),
			__( 'Settings', "send-users-email" ),
			'manage_options',
			'send-users-email-settings',
			[ $this, 'settings' ]
		);

	}

	/**
	 * Admin Dashboard page
	 */
	public function admin_dashboard() {
		$users = count_users();
		require_once 'partials/admin-dashboard.php';
	}

	/**
	 * Handle Email send selecting users
	 */
	public function users_email() {
		// Get system users
		$blog_users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
		require_once 'partials/users-email.php';
	}

	/**
	 * Handle Email send selecting roles
	 */
	public function roles_email() {
		$users = count_users();
		$roles = $users['avail_roles'];
		require_once 'partials/roles-email.php';
	}

	/**
	 * Settings page
	 */
	public function settings() {
		$options = get_option( 'sue_send_users_email' );
		$logo    = $options['logo_url'] ?? '';
		$title   = $options['email_title'] ?? '';
		$tagline = $options['email_tagline'] ?? '';
		$footer  = $options['email_footer'] ?? '';
		require_once 'partials/settings.php';
	}

	/**
	 * Handles request to send user email selecting users
	 */
	public function handle_ajax_admin_user_email() {

		if ( check_admin_referer( 'sue-email-user' ) ) {

			$param  = isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "";
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "";

			if ( $param == 'send_email_user' && $action == 'sue_user_email_ajax' ) {
				$subject = isset( $_REQUEST['subject'] ) ? sanitize_text_field( $_REQUEST['subject'] ) : "";
				$message = isset( $_REQUEST['sue_user_email_message'] ) ? wp_kses_post( $_REQUEST['sue_user_email_message'] ) : "";
				$users   = $_REQUEST['users'] ?? [];
				$users = array_map( 'sanitize_text_field', $users );

				// Validate inputs
				$validation_message = [];

				if ( empty( $subject ) || strlen( $subject ) < 2 || strlen( $subject ) > 200 ) {
					$validation_message['subject'] = __( 'Subject is required and should be between 2 and 200 characters.', "send-users-email" );
				}

				if ( empty( $message ) ) {
					$validation_message['message'] = __( 'Please provide email content.', "send-users-email" );
				}

				if ( empty( $users ) ) {
					$validation_message['sue-user-email-datatable'] = __( 'Please select users.', "send-users-email" );
				}

				// If validation fails send, error messages
				if ( count( $validation_message ) > 0 ) {
					wp_send_json( array( 'errors' => $validation_message, 'success' => false ), 422 );
				}

				// Cleanup email progress record
				Send_Users_Email_cleanup::cleanupUserEmailProgress();

				// Check if current user is admin --- For now only administrator can send email
				if ( current_user_can( 'administrator' ) ) {

					$user_id             = get_current_user_id();
					$total_email_send    = 0;
					$total_email_to_send = count( $users );

					$options = get_option( 'sue_send_users_email' );

					if ( ! $options ) {
						update_option( 'sue_send_users_email', [] );
					}

					$options = get_option( 'sue_send_users_email' );

					$options[ 'email_users_total_email_send_' . $user_id ]    = $total_email_send;
					$options[ 'email_users_total_email_to_send_' . $user_id ] = $total_email_to_send;

					update_option( 'sue_send_users_email', $options );

					$user_details = get_users( [
						'include' => $users,
						'fields'  => [ 'ID', 'display_name', 'user_email' ]
					] );

					foreach ( $user_details as $user ) {
						$email_body   = $message;
						$display_name = $user->display_name;
						$user_email   = sanitize_email($user->user_email);

						$user_meta  = get_user_meta( $user->ID );
						$first_name = $user_meta['first_name'][0] ?? '';
						$last_name  = $user_meta['last_name'][0] ?? '';

						// Replace placeholder with user content
						$email_body = $this->replace_placeholder( $email_body, $display_name, $first_name, $last_name, $user_email );

						// Send email
						$headers        = [ 'Content-Type: text/html; charset=UTF-8' ];
						$email_template = $this->email_template( $email_body );
						wp_mail( $user_email, $subject, $email_template, $headers );

						$email_body     = '';
						$email_template = '';

						$total_email_send ++;
						$options[ 'email_users_total_email_send_' . $user_id ] = $total_email_send;
						update_option( 'sue_send_users_email', $options );

					}

					// Cleanup email progress record
					Send_Users_Email_cleanup::cleanupUserEmailProgress();

					wp_send_json( array( 'message' => 'success', 'success' => true ), 200 );

				}

			}

		}

		wp_send_json( array( 'message' => 'Permission Denied', 'success' => false ), 200 );

	}

	/**
	 * Handle users email progress
	 */
	public function handle_ajax_email_users_progress() {

		if ( current_user_can( 'administrator' ) ) {
			$param  = isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "";
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "";

			if ( $param == 'send_email_user_progress' && $action == 'sue_email_users_progress' ) {
				$user_id             = get_current_user_id();
				$options             = get_option( 'sue_send_users_email' );
				$total_email_send    = $options[ 'email_users_total_email_send_' . $user_id ];
				$total_email_to_send = $options[ 'email_users_total_email_to_send_' . $user_id ];
				$progress            = floor( ( $total_email_send / $total_email_to_send ) * 100 );

				wp_send_json( array( 'progress' => $progress ), 200 );
			}
		}

		wp_send_json( array( 'message' => 'Permission Denied', 'success' => false ), 200 );
	}

	/**
	 * Handles Ajax request to send user email selecting users
	 */
	public function handle_ajax_admin_role_email() {

		if ( check_admin_referer( 'sue-email-user' ) ) {

			$param  = isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "";
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "";

			if ( $param == 'send_email_role' && $action == 'sue_role_email_ajax' ) {
				$subject = isset( $_REQUEST['subject'] ) ? sanitize_text_field( $_REQUEST['subject'] ) : "";
				$message = isset( $_REQUEST['sue_user_email_message'] ) ? wp_kses_post( $_REQUEST['sue_user_email_message'] ) : "";
				$roles   = $_REQUEST['roles'] ?? [];
				$roles = array_map( 'sanitize_text_field', $roles );

				// Validate inputs
				$validation_message = [];

				if ( empty( $subject ) || strlen( $subject ) < 2 || strlen( $subject ) > 200 ) {
					$validation_message['subject'] = __( 'Subject is required and should be between 2 and 200 characters.', "send-users-email" );
				}

				if ( empty( $message ) ) {
					$validation_message['message'] = __( 'Please provide email content.', "send-users-email" );
				}

				if ( empty( $roles ) ) {
					$validation_message['sue-role-email-list'] = __( 'Please select role(s).', "send-users-email" );
				}

				// If validation fails send, error messages
				if ( count( $validation_message ) > 0 ) {
					wp_send_json( array( 'errors' => $validation_message, 'success' => false ), 422 );
				}

				// Cleanup email progress record
				Send_Users_Email_cleanup::cleanupRoleEmailProgress();

				// Check if current user is admin --- For now only administrator can send email
				if ( current_user_can( 'administrator' ) ) {

					$user_id          = get_current_user_id();
					$total_email_send = 0;

					$user_details = get_users( array(
						'fields'   => array( 'ID', 'display_name', 'user_email' ),
						'role__in' => $roles
					) );

					$total_email_to_send = count( $user_details );

					$options = get_option( 'sue_send_users_email' );

					if ( ! $options ) {
						update_option( 'sue_send_users_email', [] );
					}

					$options = get_option( 'sue_send_users_email' );

					$options[ 'email_roles_total_email_send_' . $user_id ]    = $total_email_send;
					$options[ 'email_roles_total_email_to_send_' . $user_id ] = $total_email_to_send;

					update_option( 'sue_send_users_email', $options );

					foreach ( $user_details as $user ) {
						$email_body   = $message;
						$display_name = $user->display_name;
						$user_email   = sanitize_email($user->user_email);

						$user_meta  = get_user_meta( $user->ID );
						$first_name = $user_meta['first_name'][0] ?? '';
						$last_name  = $user_meta['last_name'][0] ?? '';

						// Replace placeholder with user content
						$email_body = $this->replace_placeholder( $email_body, $display_name, $first_name, $last_name, $user_email );

						// Send email
						$headers        = [ 'Content-Type: text/html; charset=UTF-8' ];
						$email_template = $this->email_template( $email_body );
						wp_mail( $user_email, $subject, $email_template, $headers );

						$email_body     = '';
						$email_template = '';

						$total_email_send ++;
						$options[ 'email_roles_total_email_send_' . $user_id ] = $total_email_send;
						update_option( 'sue_send_users_email', $options );

					}

					// Cleanup email progress record
					Send_Users_Email_cleanup::cleanupRoleEmailProgress();

					wp_send_json( array( 'message' => 'success', 'success' => true ), 200 );

				}

			}

		}

		wp_send_json( array( 'message' => 'Permission Denied', 'success' => false ), 200 );

	}

	/**
	 * Handle users email progress
	 */
	public function handle_ajax_email_roles_progress() {

		if ( current_user_can( 'administrator' ) ) {
			$param  = isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "";
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "";

			if ( $param == 'send_email_role_progress' && $action == 'sue_email_roles_progress' ) {
				$user_id             = get_current_user_id();
				$options             = get_option( 'sue_send_users_email' );
				$total_email_send    = $options[ 'email_roles_total_email_send_' . $user_id ];
				$total_email_to_send = $options[ 'email_roles_total_email_to_send_' . $user_id ];
				$progress            = floor( ( $total_email_send / $total_email_to_send ) * 100 );

				wp_send_json( array( 'progress' => $progress ), 200 );
			}
		}

		wp_send_json( array( 'message' => 'Permission Denied', 'success' => false ), 200 );
	}

	/**
	 * Email template
	 */
	private function email_template( $email_body ) {
		ob_start();
		$options = get_option( 'sue_send_users_email' );
		$logo    = $options['logo_url'] ?? '';
		$title   = $options['email_title'] ?? '';
		$tagline = $options['email_tagline'] ?? '';
		$footer  = $options['email_footer'] ?? '';
		require 'partials/email-template.php';
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Plugin settings
	 */
	public function handle_ajax_admin_settings() {
		if ( check_admin_referer( 'sue-email-user' ) ) {

			$param  = isset( $_REQUEST['param'] ) ? sanitize_text_field( $_REQUEST['param'] ) : "";
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : "";

			if ( $param == 'sue_settings' && $action == 'sue_settings_ajax' ) {

				$logo    = isset( $_REQUEST['logo'] ) ? esc_url_raw( $_REQUEST['logo'] ) : "";
				$title   = isset( $_REQUEST['title'] ) ? sanitize_text_field( $_REQUEST['title'] ) : "";
				$tagline = isset( $_REQUEST['tagline'] ) ? sanitize_text_field( $_REQUEST['tagline'] ) : "";
				$footer  = isset( $_REQUEST['footer'] ) ? sanitize_text_field( $_REQUEST['footer'] ) : "";


				// Validate inputs
				$validation_message = [];

				if ( ! empty( $logo ) && ! wp_http_validate_url( $logo ) ) {
					$validation_message['logo'] = __( 'Please provide valid image URL..', "send-users-email" );
				}

				if ( ! empty( $title ) && ( strlen( $title ) <= 2 ) ) {
					$validation_message['title'] = __( 'Please provide a bit more title.', "send-users-email" );
				}

				if ( ! empty( $tagline ) && ( strlen( $tagline ) <= 4 ) ) {
					$validation_message['tagline'] = __( 'Please provide a bit more tagline.', "send-users-email" );
				}

				if ( ! empty( $footer ) && ( strlen( $footer ) <= 4 ) ) {
					$validation_message['footer'] = __( 'Please provide a bit more footer content.', "send-users-email" );
				}

				// If validation fails send, error messages
				if ( count( $validation_message ) > 0 ) {
					wp_send_json( array( 'errors' => $validation_message, 'success' => false ), 422 );
				}

				// Check if current user is admin --- For now only administrator can send email
				if ( current_user_can( 'administrator' ) ) {

					$options = get_option( 'sue_send_users_email' );

					if ( ! $options ) {
						update_option( 'sue_send_users_email', [] );
					}

					$options = get_option( 'sue_send_users_email' );

					$options['logo_url']      = $logo;
					$options['email_title']   = $title;
					$options['email_tagline'] = $tagline;
					$options['email_footer']  = $footer;

					update_option( 'sue_send_users_email', $options );

					wp_send_json( array( 'message' => 'success', 'success' => true ), 200 );

				}

			}

		}

		wp_send_json( array( 'message' => 'Permission Denied', 'success' => false ), 200 );
	}

	/**
	 * Replace placeholder text to content
	 */
	private function replace_placeholder( $email_body, $display_name, $first_name, $last_name, $user_email ) {
		$email_body = str_replace( '{{user_display_name}}', $display_name, $email_body );
		$email_body = str_replace( '{{user_first_name}}', $first_name, $email_body );
		$email_body = str_replace( '{{user_last_name}}', $last_name, $email_body );
		$email_body = str_replace( '{{user_email}}', $user_email, $email_body );

		return nl2br($email_body);
	}

}
