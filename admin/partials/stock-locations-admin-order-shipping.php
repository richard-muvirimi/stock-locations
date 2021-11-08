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

/**
 * @var WC_Order $order
 */

?>

<!--Collection location if local pickup-->
<?php

if (stock_locations_order_is_local_pickup($order)) :
    $location = $order->get_meta("_shipping_location");

    if ($location) :
?>
        <div class="address">
            <p>
                <strong>
                    <?php esc_html_e('Collection Location', $this->plugin_name) ?>:
                </strong><?php echo wp_kses_post(get_post_field("post_title", $location));
                            ?>
            </p>
        </div>
    <?php endif; ?>
<?php endif; ?>