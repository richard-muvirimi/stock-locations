<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       www.webentangled.co.zw
 * @since      1.0.0
 *
 * @package    ERP_Integration
 * @subpackage ERP_Integration/public/partials
 */


/**
 * @var WP_Post $locations
 */
if (!empty($locations)) :
?>

    <div id="<?= $this->plugin_name ?>-prompt" class="<?= $this->plugin_name ?>-prompt modal" style="display: block;">

        <div class="modal-content">
            <h3>
                <?php _e("Select store location", $this->plugin_name) ?>
            </h3>
            <p class="txt-center">
                <?php esc_html_e(get_option($this->plugin_name . "-prompt")) ?>
            </p>
            <select name="<?= $this->plugin_name ?>-prompt">
                <?php foreach ($locations as $location) : ?>
                    <option value="<?php esc_attr_e($location->ID) ?>" <?php selected(stock_locations_get_location(), $location->ID) ?>>
                        <?= $location->post_title ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button data-type="prompt" class="button <?= $this->plugin_name ?>-save"><?php _e("Set Store Location", $this->plugin_name) ?></button>
        </div>

    </div>
<?php endif;
