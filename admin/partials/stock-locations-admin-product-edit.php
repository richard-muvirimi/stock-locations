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
<div class="stock_fields show_if_<?php esc_attr_e(stock_locations_slug()) ?>">
    <?php if (get_option('woocommerce_manage_stock') === 'yes') : ?>
    <?php

        $product = wc_get_product(get_post());

        if ($product->managing_stock()) :

            $locations = stock_locations_locations();

            foreach ($locations as $location) :
                woocommerce_wp_text_input(
                    array(
                        'id'                => stock_locations_meta_key($location->ID),
                        'value'             => $product->get_meta(stock_locations_meta_key($location->ID), true) ?: 0,
                        'label'             => esc_html__($location->post_title),
                        'desc_tip'          => true,
                        'description'       => __('Stock quantity.', $this->plugin_name),
                        'type'              => 'number',
                        'custom_attributes' => array(
                            'step' => 'any',
                        ),
                        'data_type'         => 'stock',
                    )
                );
            endforeach;
        endif;
    endif; ?>
</div>