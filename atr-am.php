<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.atarimtr.com/
 * @since             1.0.0
 * @package           Atr_Advanced_Menu
 *
 * @wordpress-plugin
 * Plugin Name:       ATR advanced menu
 * Plugin URI:        http://atarimtr.com
 * Description:       Create highly customizable dropdown or mega menu with icons, posts, thumbnails etc using standard Wordpress menu editing page. The plugin requires editing the header.php file, and add 'walker' => new atr_advanced_menu_walker() to wp_nav_menu. 
 * Version:           1.0.4
 * Author:            Yehuda Tiram
 * Author URI:        http://www.atarimtr.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       atr-advanced-menu
 * Domain Path:       /languages
 * WC requires at least: 2.6.4
 * WC tested up to: 6.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-atr-am-activator.php
 */
function activate_atr_advanced_menu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atr-am-activator.php';
	Atr_Advanced_Menu_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-atr-am-deactivator.php
 */
function deactivate_atr_advanced_menu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atr-am-deactivator.php';
	Atr_Advanced_Menu_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_atr_advanced_menu' );
register_deactivation_hook( __FILE__, 'deactivate_atr_advanced_menu' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-atr-am.php';

/**
 * Call the walker.
 *
 * @since    1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'includes/walkers/class-atr-am-walker.php';

/**
 * Call the css classes formating class.
 *
 * @since    1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'includes/walkers/class-atr-am-formatting.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_atr_advanced_menu() {

	$plugin = new Atr_Advanced_Menu();
	$plugin->run();

}

run_atr_advanced_menu();
