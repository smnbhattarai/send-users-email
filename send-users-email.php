<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://sumanbhattarai.com.np
 * @since             1.0.0
 * @package           Send_Users_Email
 *
 * @wordpress-plugin
 * Plugin Name:       Send Users Email
 * Plugin URI:        https://sumanbhattarai.com.np
 * Description:       Easily send emails to your users. Select individual users or role to send the email.
 * Version:           1.0.0
 * Author:            Suman Bhattarai
 * Author URI:        http://sumanbhattarai.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       send-users-email
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SEND_USERS_EMAIL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-send-users-email-activator.php
 */
function activate_send_users_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-send-users-email-activator.php';
	Send_Users_Email_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-send-users-email-deactivator.php
 */
function deactivate_send_users_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-send-users-email-deactivator.php';
	Send_Users_Email_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_send_users_email' );
register_deactivation_hook( __FILE__, 'deactivate_send_users_email' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-send-users-email.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_send_users_email() {

	$plugin = new Send_Users_Email();
	$plugin->run();

}
run_send_users_email();
