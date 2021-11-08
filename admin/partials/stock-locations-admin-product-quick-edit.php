<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/admin/partials
 */

?>
<div class="clear"></div>
<div class="location_stock_fields">
    <?php if (get_option('woocommerce_manage_stock') === 'yes') :

        $product = wc_get_product(get_post());

        if ($product->managing_stock()) :

            $locations = stock_locations_locations();

            foreach ($locations as $location) : ?>
                <label class="location_stock_qty_field">
                    <span class="title"><?php esc_html_e($location->post_title) ?></span>
                    <span class="input-text-wrap">
                        <input type="number" name="<?php esc_attr_e(stock_locations_meta_key($location->ID)) ?>" class="text stock" step="any" value="<?php esc_attr_e($product->get_meta(stock_locations_meta_key($location->ID), true) ?: 0) ?>">
                    </span>
                </label>
    <?php endforeach;
        endif;
    endif; ?>
</div>