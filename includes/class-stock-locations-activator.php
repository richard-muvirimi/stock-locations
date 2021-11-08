<?php

/**
 * Fired during plugin activation
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Stock_locations
 * @subpackage Stock_locations/includes
 * @author     Richard (Webentangled) <tygalive@gmail.com>
 */
class Stock_locations_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{

		$options = array(
			STOCK_LOCATIONS_SLUG . "-prompt" => __('Please select your location so that we can show you products that are available in your area. You can change your selection at any time in the menu bar.', STOCK_LOCATIONS_SLUG),
		);

		foreach ($options as $key => $value) {
			add_option($key, $value);
		}
	}
}
