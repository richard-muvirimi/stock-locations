<?php

/**
 * Provide a option area view for the plugin
 *
 * This file is used to markup the option-facing aspects of the plugin.
 *
 * @link       https://webentangled.co.zw
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/option/partials
 */

// Get the value of the setting we've registered with register_setting()

wp_editor($args[$this->plugin_name . '-value'], $args['label_for'], array(
    "textarea_rows" => 5
));

?>

<div>
    <small class="description">
        <?php esc_html_e('Store selection prompt', $this->plugin_name); ?>
    </small>
</div>