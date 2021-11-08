<?php

/**
 * The option-specific functionality of the plugin.
 *
 * @link       https://webentangled.co.zw
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/option
 */

/**
 * The option-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the option-specific stylesheet and JavaScript.
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/option
 * @author     Web Entangled <richard@webentangled.co.zw>
 */
class Stock_locations_Options
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the options-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style($this->plugin_name . "-options", plugin_dir_url(__FILE__) . 'css/stock-locations-options.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the options-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_register_script($this->plugin_name . "-options", plugin_dir_url(__FILE__) . 'js/stock-locations-options.js', array('jquery'), $this->version, false);
    }

    public function admin_menu()
    {
        add_submenu_page(stock_locations_slug(), __("Options", $this->plugin_name), __("Options", $this->plugin_name), "manage_options", stock_locations_slug() . "-options", array($this, "displaySettings"));
    }

    public function init()
    {

        $this->registerSettings();
        $this->addSections();
        $this->addSettingsFields();
    }

    public function registerSettings()
    {
        switch (stock_locations_options_get_tab()) {
            case "tab-prompt":
                register_setting(
                    stock_locations_slug() . "-options",
                    $this->plugin_name . "-prompt-intro",
                );
                break;
        }
    }

    public function addSections()
    {
        add_settings_section(
            $this->plugin_name,
            __("Stock locations Options", $this->plugin_name),
            array($this, "renderOptionsHeader"),
            stock_locations_slug() . "-options"
        );
    }

    public function addSettingsFields()
    {

        switch (stock_locations_options_get_tab()) {
            case "tab-prompt":

                add_settings_field(
                    $this->plugin_name . '-prompt',
                    __('Store selector prompt', $this->plugin_name),
                    array($this, 'renderPrompt'),
                    stock_locations_slug() . "-options",
                    $this->plugin_name,
                    array(
                        'label_for' => $this->plugin_name . '-prompt',
                        'class' => $this->plugin_name . '-row',
                        $this->plugin_name . "-value" => get_option($this->plugin_name . "-prompt"),
                    )
                );

                break;
            default;
                do_action($this->plugin_name . "-options-content", stock_locations_options_get_tab());
                break;
        }
    }

    public function renderOptionsHeader()
    {
        include plugin_dir_path(__FILE__) . "partials/stock-locations-options-tabs.php";
    }

    public function renderPrompt($args)
    {
        include plugin_dir_path(__FILE__) . "partials/field/stock-locations-field-prompt.php";
    }

    public function displaySettings()
    {
        wp_enqueue_style($this->plugin_name . "-options");
        wp_enqueue_script($this->plugin_name . "-options");
        wp_localize_script($this->plugin_name . "-options", "ccbot", array(
            "name" => $this->plugin_name,
        ));

        include plugin_dir_path(__FILE__) . "partials/stock-locations-options-display.php";
    }

    /**
     * Add settings link to plugins page
     *
     * @param array $actions
     * @return array
     */
    public function renderSettingsLink($actions)
    {

        $actions[] = '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '" >' . __("Settings", $this->plugin_name) . '</a>';

        return $actions;
    }
}
