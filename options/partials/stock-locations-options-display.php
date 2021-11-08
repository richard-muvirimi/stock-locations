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

// check user capabilities
if (!current_user_can('manage_options')) {
    return;
}

// add error/update messages

// check if the user have submitted the settings
// WordPress will add the "settings-updated" $_GET parameter to the url
if (isset($_GET['settings-updated'])) {
    // add settings saved message with the class of "updated"
    //add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
}

// show error/update messages
//settings_errors( 'wporg_messages' );
?>

<div class="wrap">
    <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">\
        <input type="hidden" name="tab" value="<?= stock_locations_options_get_tab() ?>">
        <?php
        // output security fields for the registered setting "wporg"
        settings_fields(stock_locations_slug() . "-options");
        // output setting sections and their fields
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections(stock_locations_slug() . "-options");
        // output save settings button
        submit_button(__('Save Settings', $this->plugin_name));
        ?>
    </form>
</div>