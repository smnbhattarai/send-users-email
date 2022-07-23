<?php

/**
 * The admin-specific functionality of the plugin.
 */
class Send_Users_Email_Admin {

	private $plugin_name;

	private $version;

	// want to make it so we can have a specific role for sending email so not only administrators can send email
	private $send_users_email_role_slug;
	private $send_users_email_role_display;
	private $send_users_email_role_capability;
	
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
		// set up the slug, role, and capability to permit access by user role
		$this->send_users_email_role_slug = "email_sender";
		$this->send_users_email_role_display = "Email Sender";
		$this->send_users_email_role_capability = "send_email";

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		// Add css to this plugin page only
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "";
		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_style( 'sue-bootstrap-5', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), '5.1.1',
				'all' );
			wp_enqueue_style( 'sue-bootstrap-5-datatable',
				plugin_dir_url( __FILE__ ) . 'css/dataTables.bootstrap5.min.css', array( 'sue-bootstrap-5' ), '1.11.2',
				'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/send-users-email-admin.css',
				array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		// Add JS to this plugin page only
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "";
		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js',
				array( 'jquery' ), '5.1.1', true );
			wp_enqueue_script( 'datatable-js', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js',
				array( 'jquery' ), '1.11.2', true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/send-users-email-admin.js',
				array( 'jquery' ), $this->version, true );
		}

	}


	/**
	 * Register admin menu Items
	 */
	public function admin_menu() {

		add_menu_page( __( "Send Users Email", "send-users-email" ),
			__( "Email to users", "send-users-email" ),
			// allow access to users with the right roles
			$this->send_users_email_role_capability,
			'send-users-email',
			[ $this, 'admin_dashboard' ],
			'dashicons-email-alt2',
			250 );

		add_submenu_page(
			'send-users-email',
			__( 'Dashboard', "send-users-email" ),
			__( 'Dashboard', "send-users-email" ),
			// allow access to users with the right roles
			$this->send_users_email_role_capability,
			'send-users-email',
			[ $this, 'admin_dashboard' ]
		);

		add_submenu_page(
			'send-users-email',
			__( 'Email Users', "send-users-email" ),
			__( 'Email Users', "send-users-email" ),
			// allow access to users with the right roles
			$this->send_users_email_role_capability,
			'send-users-email-users',
			[ $this, 'users_email' ]
		);

		add_submenu_page(
			'send-users-email',
			__( 'Email Roles', "send-users-email" ),
			__( 'Email Roles', "send-users-email" ),
			// allow access to users with the right roles
			$this->send_users_email_role_capability,
			'send-users-email-roles',
			[ $this, 'roles_email' ]
		);

		add_submenu_page(
			'send-users-email',
			__( 'Settings', "send-users-email" ),
			__( 'Settings', "send-users-email" ),
			// allow access to users with the right roles
			$this->send_users_email_role_capability,
			'send-users-email-settings',
			[ $this, 'settings' ]
		);

	}

	function send_users_email_activate_admin_actions()
	{
		// this method getes called when the plugin is activated
		
		// create a new role of email sender and give it the capability to send email
		add_role($this->send_users_email_role_slug, $this->send_users_email_role_display, [$this->send_users_email_role_capability => true]);
		
		// make any site administrators able to send email
		$roles = wp_roles();
		$role = $roles->role_objects['administrator'];
		$role->add_cap($this->send_users_email_role_capability);
	}
	
	function send_users_email_deactivate_admin_actions()
	{
		// delete the role of email sender
		remove_role($this->send_users_email_role_slug);
		
		// remove email capability from site administrators
		$roles = wp_roles();
		$role = $roles->role_objects['administrator'];
		$role->remove_cap($this->send_users_email_role_capability);
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
		$options              = get_option( 'sue_send_users_email' );
		$logo                 = $options['logo_url'] ?? '';
		$title                = $options['email_title'] ?? '';
		$tagline              = $options['email_tagline'] ?? '';
		$footer               = $options['email_footer'] ?? '';
		$email_from_name      = $options['email_from_name'] ?? '';
		$email_from_address   = $options['email_from_address'] ?? '';
		$reply_to_address     = $options['reply_to_address'] ?? '';
		$email_template_style = $options['email_template_style'] ?? '';
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
				$users   = array_map( 'sanitize_text_field', $users );

				// Validate inputs
				$validation_message = [];

				if ( empty( $subject ) || strlen( $subject ) < 2 || strlen( $subject ) > 200 ) {
					$validation_message['subject'] = __( 'Subject is required and should be between 2 and 200 characters.',
						"send-users-email" );
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

				// check to see if they are a user with sending email capability
				if ( current_user_can( $this->send_users_email_role_capability ) ) {

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
						'fields'  => [ 'ID', 'display_name', 'user_email', 'user_login' ]
					] );

					// Email header setup
					$headers = $this->get_email_headers();

					foreach ( $user_details as $user ) {
						$email_body   = $message;
						$username     = $user->user_login;
						$display_name = $user->display_name;
						$user_email   = sanitize_email( $user->user_email );

						$user_meta  = get_user_meta( $user->ID );
						$first_name = $user_meta['first_name'][0] ?? '';
						$last_name  = $user_meta['last_name'][0] ?? '';

						// Replace placeholder with user content
						$email_body = $this->replace_placeholder( $email_body, $username, $display_name, $first_name,
							$last_name, $user_email );

						// Send email
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

		// check to see if they are a user with sending email capability
			if ( current_user_can( $this->send_users_email_role_capability ) ) {
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
				$roles   = array_map( 'sanitize_text_field', $roles );

				// Validate inputs
				$validation_message = [];

				if ( empty( $subject ) || strlen( $subject ) < 2 || strlen( $subject ) > 200 ) {
					$validation_message['subject'] = __( 'Subject is required and should be between 2 and 200 characters.',
						"send-users-email" );
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

				// check to see if they are a user with sending email capability
				if ( current_user_can( $this->send_users_email_role_capability ) ) {

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

					// Email header setup
					$headers = $this->get_email_headers();

					foreach ( $user_details as $user ) {
						$email_body   = $message;
						$username     = $user->username;
						$display_name = $user->display_name;
						$user_email   = sanitize_email( $user->user_email );

						$user_meta  = get_user_meta( $user->ID );
						$first_name = $user_meta['first_name'][0] ?? '';
						$last_name  = $user_meta['last_name'][0] ?? '';

						// Replace placeholder with user content
						$email_body = $this->replace_placeholder( $email_body, $username, $display_name, $first_name,
							$last_name, $user_email );

						// Send email
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

		// check to see if they are a user with sending email capability
		if ( current_user_can( $this->send_users_email_role_capability ) ) {
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
		$styles  = $options['email_template_style'] ?? '';
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

				$logo                 = isset( $_REQUEST['logo'] ) ? esc_url_raw( $_REQUEST['logo'] ) : "";
				$title                = isset( $_REQUEST['title'] ) ? sanitize_text_field( $_REQUEST['title'] ) : "";
				$tagline              = isset( $_REQUEST['tagline'] ) ? sanitize_text_field( $_REQUEST['tagline'] ) : "";
				$footer               = isset( $_REQUEST['footer'] ) ? sanitize_text_field( $_REQUEST['footer'] ) : "";
				$email_from_name      = isset( $_REQUEST['email_from_name'] ) ? sanitize_text_field( $_REQUEST['email_from_name'] ) : "";
				$email_from_address   = isset( $_REQUEST['email_from_address'] ) ? sanitize_text_field( $_REQUEST['email_from_address'] ) : "";
				$reply_to_address     = isset( $_REQUEST['reply_to_address'] ) ? sanitize_text_field( $_REQUEST['reply_to_address'] ) : "";
				$email_template_style = isset( $_REQUEST['email_template_style'] ) ? sanitize_text_field( $_REQUEST['email_template_style'] ) : "";


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
					$validation_message['footer'] = __( 'Please provide a bit more footer content.',
						"send-users-email" );
				}

				if ( ! empty( $email_from_name ) && ( strlen( $email_from_name ) <= 2 ) ) {
					$validation_message['email_from_name'] = __( 'Please provide a bit more email from Name.',
						"send-users-email" );
				}

				if ( ! empty( $email_from_address ) && ! filter_var( $email_from_address, FILTER_VALIDATE_EMAIL ) ) {
					$validation_message['email_from_address'] = __( 'Please provide a valid email from address.',
						"send-users-email" );
				}

				if ( ! empty( $reply_to_address ) && ! filter_var( $reply_to_address, FILTER_VALIDATE_EMAIL ) ) {
					$validation_message['reply_to_address'] = __( 'Please provide a valid reply to address.',
						"send-users-email" );
				}

				// If validation fails send, error messages
				if ( count( $validation_message ) > 0 ) {
					wp_send_json( array( 'errors' => $validation_message, 'success' => false ), 422 );
				}

				// check to see if they are a user with sending email capability
				if ( current_user_can( $this->send_users_email_role_capability ) ) {

					$options = get_option( 'sue_send_users_email' );

					if ( ! $options ) {
						update_option( 'sue_send_users_email', [] );
					}

					$options = get_option( 'sue_send_users_email' );

					$options['logo_url']             = esc_url_raw( $logo );
					$options['email_title']          = stripslashes_deep( wp_strip_all_tags( $title ) );
					$options['email_tagline']        = stripslashes_deep( wp_strip_all_tags( $tagline ) );
					$options['email_footer']         = stripslashes_deep( wp_strip_all_tags( $footer ) );
					$options['email_from_name']      = stripslashes_deep( wp_strip_all_tags( $email_from_name ) );
					$options['email_from_address']   = stripslashes_deep( wp_strip_all_tags( $email_from_address ) );
					$options['reply_to_address']     = stripslashes_deep( wp_strip_all_tags( $reply_to_address ) );
					$options['email_template_style'] = stripslashes_deep( wp_strip_all_tags( $email_template_style ) );

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
	private function replace_placeholder(
		$email_body,
		$username,
		$display_name,
		$first_name,
		$last_name,
		$user_email
	) {
		$email_body = str_replace( '{{username}}', $username, $email_body );
		$email_body = str_replace( '{{user_display_name}}', $display_name, $email_body );
		$email_body = str_replace( '{{user_first_name}}', $first_name, $email_body );
		$email_body = str_replace( '{{user_last_name}}', $last_name, $email_body );
		$email_body = str_replace( '{{user_email}}', $user_email, $email_body );

		return nl2br( $email_body );
	}

	/**
	 * @return array
	 */
	private function get_email_headers() {
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		$options            = get_option( 'sue_send_users_email' );
		$email_from_name    = $options['email_from_name'] ?? '';
		$email_from_address = $options['email_from_address'] ?? '';
		$reply_to_address   = $options['reply_to_address'] ?? '';

		if ( ! empty( $email_from_name ) && ! empty( $email_from_address ) ) {
			$headers[] = "From: $email_from_name <$email_from_address>";
		}

		if ( ! empty( $email_from_name ) && ! empty( $reply_to_address ) ) {
			$headers[] = "Reply-To: $email_from_name <$reply_to_address>";
		}

		return $headers;
	}

}
