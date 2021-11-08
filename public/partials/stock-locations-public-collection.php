<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/public/partials
 */
?>

<tr class="woocommerce-shipping-locations shipping">
    <th><?php _e("Pickup Location", $this->plugin_name) ?></th>
    <td data-title="<?php _e("Pickup Location", $this->plugin_name) ?>">
        <p class="woocommerce-shipping-locations"><?php esc_html_e(stock_locations_location_name()) ?></p>
    </td>
</tr>