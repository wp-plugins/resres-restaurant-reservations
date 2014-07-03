<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   ResRes Light
 * @author    Dean Robinson <team@deftdev.com>
 * @license   GPL-2.0+
 * @link      http://deftdev.com
 * @copyright 2014 deftDEV
 *
 * @wordpress-plugin
 * Plugin Name:       ResRes Light
 * Plugin URI:        http://www.deftdev.com
 * Description:       A Light version of the the powerful restuarant reservation system, ResRes.
 * Version:           1.0.5.f
 * Author:            Dean Robinson
 * Author URI:        http://www.deftdev.com
 * Text Domain:       resres
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/


require_once( plugin_dir_path( __FILE__ ) . 'public/class-resres.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'ResRes', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ResRes', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'ResRes', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-resres-admin.php` with the name of the plugin's admin file
 * - replace ResRes_Admin with the name of the class defined in
 *   `class-resres-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin()) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-resres-admin.php' );
	add_action( 'plugins_loaded', array( 'ResRes_Admin', 'get_instance' ) );

}
