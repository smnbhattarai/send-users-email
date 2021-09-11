<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://sumanbhattarai.com.np
 * @since      1.0.0
 *
 * @package    Send_Users_Email
 * @subpackage Send_Users_Email/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Send_Users_Email
 * @subpackage Send_Users_Email/admin
 * @author     Suman Bhattarai <smnbhattarai4@gmail.com>
 */
class Send_Users_Email_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
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
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
// Add css to this plugin page only
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "";
		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_style( 'bootstrap-5', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), '5.1.1', 'all' );
			wp_enqueue_style( 'bootstrap-5-datatable', plugin_dir_url( __FILE__ ) . 'css/dataTables.bootstrap5.min.css', array(), '1.11.1', 'all' );
			wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/send-users-email-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
// Add JS to this plugin page only
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : "";
		if ( in_array( $page, $this->plugin_pages_slug ) ) {
			wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array( 'jquery' ), '5.1.1', true );
			wp_enqueue_script( 'datatable-js', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), '1.11.1', true );
			wp_enqueue_script( 'select2-js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), '1.11.1', true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/send-users-email-admin.js', array( 'jquery' ), $this->version, true );
		}

	}


	/**
	 * Register admin menu Items
	 */
	public function admin_menu() {

		add_menu_page( __( "Send Users Email", "send-users-email" ),
			__( "Send Users Email", "send-users-email" ),
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
		$blog_users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
		require_once 'partials/users-email.php';
	}

	/**
	 * Handle Email send selecting roles
	 */
	public function roles_email() {
		require_once 'partials/roles-email.php';
	}

	/**
	 * Settings page
	 */
	public function settings() {
		require_once 'partials/settings.php';
	}

}
