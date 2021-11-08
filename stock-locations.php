<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tyganeutronics.com
 * @since             1.0.0
 * @package           Stock_locations
 *
 * @wordpress-plugin
 * Plugin Name:       Stock Locations
 * Plugin URI:        https://webentangled.co.zw
 * Description:       Easily setup stock locations and ensure no customer buys a product from a location without stock.
 * Version:           1.0.0
 * Author:            Richard (Webentangled)
 * Author URI:        https://tyganeutronics.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stock-locations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('STOCK_LOCATIONS_VERSION', '1.0.0');
define('STOCK_LOCATIONS_SLUG', 'stock-locations');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-stock-locations-activator.php
 */
function activate_stock_locations()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-stock-locations-activator.php';
	Stock_locations_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-stock-locations-deactivator.php
 */
function deactivate_stock_locations()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-stock-locations-deactivator.php';
	Stock_locations_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_stock_locations');
register_deactivation_hook(__FILE__, 'deactivate_stock_locations');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-stock-locations.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_stock_locations()
{
	$plugin = new Stock_locations();
	$plugin->run();
}
run_stock_locations();