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

/**
 * @var WC_Order $order
 */
?>

<section class="woocommerce-order-details">
    <h2><?php _e("Collection Details", $this->plugin_name) ?></h2>
    <ul>

        <?php if ($order->get_meta("_shipping_location")) : ?>
            <li class="woocommerce-order-overview__collection method">
                <?php esc_html_e('Collection Location:', $this->plugin_name); ?>
                <strong><?php esc_html_e(get_post_field("post_title", $order->get_meta("_shipping_location"))) ?></strong>
            </li>
        <?php endif; ?>

        <?php if ($order->get_meta("_order_secret_id")) : ?>
            <li class="woocommerce-order-overview__collection method">
                <?php esc_html_e('Secret ID:', $this->plugin_name); ?>
                <strong><?php esc_html_e($order->get_meta("_order_secret_id")) ?></strong>
            </li>
        <?php endif; ?>
    </ul>
</section>