<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Stock_locations
 * @subpackage Stock_locations/includes
 * @author     Richard (Webentangled) <tygalive@gmail.com>
 */
class Stock_locations
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Stock_locations_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('STOCK_LOCATIONS_VERSION')) {
            $this->version = STOCK_LOCATIONS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = STOCK_LOCATIONS_SLUG;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_option_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Stock_locations_Loader. Orchestrates the hooks of the plugin.
     * - Stock_locations_i18n. Defines internationalization functionality.
     * - Stock_locations_Admin. Defines all hooks for the admin area.
     * - Stock_locations_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * utility functions
         */
        require_once plugin_dir_path(__FILE__) . "functions/stock-locations-functions.php";

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-stock-locations-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-stock-locations-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-stock-locations-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-stock-locations-public.php';

        /**
         * The class responsible for defining all actions that occur in the options-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'options/class-stock-locations-options.php';

        $this->loader = new Stock_locations_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Stock_locations_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Stock_locations_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Stock_locations_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_filter('woocommerce_admin_shipping_fields', $plugin_admin, 'woocommerce_admin_shipping_fields');

        $this->loader->add_action("init", $plugin_admin, "register_post_types");

        $this->loader->add_filter("woocommerce_product_is_in_stock", $plugin_admin, "woocommerce_product_is_in_stock", 100, 2);

        $this->loader->add_action("woocommerce_product_options_stock", $plugin_admin, "woocommerce_product_options_stock");
        $this->loader->add_action("woocommerce_product_quick_edit_end", $plugin_admin, "woocommerce_product_quick_edit_end");

        $this->loader->add_filter("enter_title_here", $plugin_admin, "stock_location_title", 10, 2);

        //save
        $this->loader->add_action("woocommerce_before_product_object_save", $plugin_admin, "woocommerce_product_save");

        $this->loader->add_action("woocommerce_admin_order_data_after_shipping_address", $plugin_admin, "woocommerce_admin_order_data_after_shipping_address");
        $this->loader->add_action("woocommerce_email_order_meta", $plugin_admin, "woocommerce_admin_order_data_after_shipping_address");

        $this->loader->add_action("restrict_manage_posts", $plugin_admin, "restrict_manage_posts");
        $this->loader->add_filter('request', $plugin_admin, 'request_query');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Stock_locations_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_filter("wp_loaded", $plugin_public, "wp_loaded");

        //filter products
        //$this->loader->add_filter("woocommerce_product_is_visible", $plugin_public, "woocommerce_product_is_visible", 100, 2);
        $this->loader->add_filter("woocommerce_product_is_in_stock", $plugin_public, "woocommerce_product_is_in_stock", 100, 2);

        $this->loader->add_filter("pre_get_posts", $plugin_public, "pre_get_posts", 100);

        $this->loader->add_shortcode("woo.store.selector", $plugin_public, "renderStoreSelector");
        $this->loader->add_shortcode("woo.store.display", $plugin_public, "renderStoreName");

        $this->loader->add_action("woocommerce_review_order_after_shipping", $plugin_public, "woocommerce_review_order_after_shipping");

        $this->loader->add_filter("woocommerce_checkout_fields", $plugin_public, "woocommerce_checkout_fields");

        $this->loader->add_action("woocommerce_thankyou", $plugin_public, "woocommerce_thankyou");
        $this->loader->add_filter("woocommerce_after_order_details", $plugin_public, "woocommerce_thankyou");

        $this->loader->add_filter("woocommerce_checkout_update_order_meta", $plugin_public, "woocommerce_checkout_update_order_meta");

        //stock
        $this->loader->add_filter("woocommerce_update_product_stock_query", $plugin_public, "woocommerce_updated_product_stock", 1000, 4);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_option_hooks()
    {

        $plugin_options = new Stock_locations_Options($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_options, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_options, 'enqueue_scripts');

        $this->loader->add_action("admin_menu", $plugin_options, "admin_menu");
        $this->loader->add_action('admin_init', $plugin_options, 'init');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Stock_locations_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
