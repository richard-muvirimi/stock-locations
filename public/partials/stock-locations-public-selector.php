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
 * @var WP_Post $locations
 */
?>

<div id="<?php esc_attr_e($this->plugin_name) ?>-selector"
    class="<?php esc_attr_e($this->plugin_name) ?>-selector modal">

    <div class="modal-content">
        <div>
            <div class="<?php esc_attr_e($this->plugin_name) ?>-selector-header">
                <button class="<?php esc_attr_e($this->plugin_name) ?>-close">
                    <div>&times;</div>
                    <div><?php _e("Close", $this->plugin_name) ?></div>
                </button>
                <h3>
                    <?php _e("Set Collection Store", $this->plugin_name) ?>
                </h3>
                <p class="txt-center">
                    <?php esc_html_e(get_option($this->plugin_name . "-prompt")) ?>
                </p>
            </div>
            <?php if (!empty($locations)) : ?>
            <ul class="<?php esc_attr_e($this->plugin_name) ?>-selector-content">
                <?php foreach ($locations as $location) : ?>
                <li>

                    <label for="<?php esc_attr_e($location->ID) ?>">
                        <h6>
                            <span><img width="32px" height="32px" alt="<?php _e("Delivery", $this->plugin_name) ?>"
                                    src="<?php esc_attr_e(plugin_dir_url(__DIR__) . "img/map-marker-radius-outline.svg") ?>">
                            </span>
                            <span><?php esc_html_e($location->post_title) ?></span>
                            <input type="radio" id="<?php esc_attr_e($location->ID) ?>"
                                name="<?php esc_attr_e($this->plugin_name) ?>-location"
                                <?php checked($location->ID, stock_locations_get_location() ?: $locations[0]->ID) ?>
                                value="<?php esc_attr_e($location->ID) ?>">
                        </h6>
                        <div><?php echo wpautop($location->post_excerpt) ?></div>
                    </label>

                </li>
                <?php endforeach; ?>
            </ul>
            <button data-type="selector"
                class="button <?php esc_attr_e($this->plugin_name) ?>-save"><?php _e("Set Collection Store", $this->plugin_name) ?></button>
        </div>
        <?php else : ?>
        <p class="txt-center"><?php _e("No Collection Stores available", $this->plugin_name) ?></p>
    </div>
    <?php endif; ?>

</div>
</div>