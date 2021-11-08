<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.webentangled.co.zw
 * @since      1.0.0
 *
 * @package    Schweppes_store_selector
 * @subpackage Schweppes_store_selector/admin/partials
 */

/**
 * @var WP_Post $post
 */
?>

<?php $locations = stock_locations_locations() ?>

<?php if (!empty($locations)) : ?>
    <select name="<?php esc_attr_e($this->plugin_name . "-location") ?>" class="" data-placeholder="<?php esc_attr_e('Filter by store', $this->plugin_name); ?>" id="<?php esc_attr_e($this->plugin_name . "-location") ?>">

        <option value="">
            <?php _e("All locations", $this->plugin_name) ?>
        </option>
        <?php

        $current = filter_input(INPUT_GET, $this->plugin_name . "-location", FILTER_SANITIZE_NUMBER_INT);

        /**
         * @var WP_Post $location
         */
        foreach ($locations as $location) : ?>
            <option value="<?php esc_attr_e($location->ID) ?>" <?php selected($location->ID, $current) ?>>
                <?php esc_html_e($location->post_title) ?>
            </option>
        <?php endforeach; ?>


    </select>
<?php endif; ?>