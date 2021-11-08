<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/admin
 * @author     Richard (Webentangled) <tygalive@gmail.com>
 */
class Stock_locations_Admin
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
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        switch (get_current_screen()->id) {
            case "edit-product":
                if (get_option('woocommerce_manage_stock') === 'yes') {

                    $product = wc_get_product();
                    if ($product && $product->managing_stock()) {
                        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/stock-locations-product.css', array(), $this->version, 'all');
                    }
                }
                break;
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        switch (get_current_screen()->id) {
            case "edit-product":
                if (get_option('woocommerce_manage_stock') === 'yes') {

                    $product = wc_get_product();
                    if ($product && $product->managing_stock()) {

                        wp_enqueue_script($this->plugin_name . "-quick-edit", plugin_dir_url(__FILE__) . 'js/stock-locations-product-quick-edit.js', array('jquery'), $this->version, false);
                        wp_localize_script($this->plugin_name . "-quick-edit", "wsl", array(
                            "name" => $this->plugin_name,
                            "field_id" => stock_locations_meta_key(),
                        ));
                    }
                }
                break;
            case "product":
                if (get_option('woocommerce_manage_stock') === 'yes') {

                    $product = wc_get_product();
                    if ($product && $product->managing_stock()) {
                        wp_enqueue_script($this->plugin_name . "-edit-product", plugin_dir_url(__FILE__) . 'js/stock-locations-product-edit.js', array('jquery'), $this->version, false);
                        wp_localize_script($this->plugin_name . "-edit-product", "wsl", array(
                            "name" => $this->plugin_name,
                            "slug" => stock_locations_slug(),
                            "field_id" => stock_locations_meta_key(),
                        ));
                    }
                }
                break;
        }
    }

    public function register_post_types()
    {
        if (function_exists("WC")) {
            include plugin_dir_path(__FILE__) . "register/stock-locations-location.php";
        }
    }

    public function woocommerce_admin_shipping_fields($fields)
    {

        $order = wc_get_order();

        if ($order) {

            if (stock_locations_order_is_local_pickup($order)) {

                $shipping_location = $order->get_meta("_shipping_location");

                $locations = stock_locations_locations();

                $options = array();
                foreach ($locations as $location) {
                    $options[$location->ID] = $location->post_title;
                }

                $fields[stock_locations_slug()] = array(
                    'label' => __('Collection Location', $this->plugin_name),
                    'show' => false,
                    'class' => 'js_field-location select short',
                    'type' => 'select',
                    'options' => $options,
                    "default" => $shipping_location,
                );
            }
        }

        return $fields;
    }

    public function woocommerce_product_options_stock()
    {
        include plugin_dir_path(__FILE__) . "partials/stock-locations-admin-product-edit.php";
    }

    public function woocommerce_product_quick_edit_end()
    {
        include plugin_dir_path(__FILE__) . "partials/stock-locations-admin-product-quick-edit.php";
    }

    public function woocommerce_product_save(WC_Product $product)
    {

        if (get_option('woocommerce_manage_stock') === 'yes') {

            if ($product->managing_stock()) {

                $locations = stock_locations_location_ids();

                foreach ($locations as $location) {
                    $name = stock_locations_meta_key($location);

                    $stock = filter_input(INPUT_POST, $name, FILTER_VALIDATE_INT);
                    if ($stock != false && is_numeric($stock)) {
                        $product->update_meta_data($name, intval($stock));
                    }
                }
            }
        }
    }

    public function stock_location_title($text, $post)
    {
        switch ($post->post_type) {
            case stock_locations_slug():
                $text = __("Stock Location Title", $this->plugin_name);
                break;
        }
        return $text;
    }

    public function woocommerce_admin_order_data_after_shipping_address(WC_Order $order)
    {

        include plugin_dir_path(__FILE__) . "partials/stock-locations-admin-order-shipping.php";
    }

    /**
     * Check if product is currently in stock
     *
     * @param boolean $in_stock
     * @param int $product_id
     * @return boolean
     */
    public function woocommerce_product_is_in_stock($in_stock, $product_id)
    {

        if (is_admin()) {

            if (get_option('woocommerce_manage_stock') === 'yes') {

                $product = wc_get_product($product_id);
                if ($product && $product->managing_stock()) {

                    $stock = wp_cache_get("stock-" . $product->get_id(), $this->plugin_name);

                    if (!$stock) {

                        $locations = stock_locations_location_ids();
                        foreach ($locations as $location) {
                            $stock += $product->get_meta(stock_locations_meta_key($location), true) ?: 0;
                        }

                        wp_cache_set("stock-" . $product->get_id(), $this->plugin_name, $stock, MINUTE_IN_SECONDS);
                    }

                    $in_stock = $stock > 0;
                }
            }
        }

        return $in_stock;
    }

    public function restrict_manage_posts()
    {

        if (get_option('woocommerce_manage_stock') === 'yes') {

            global $typenow;

            if (in_array($typenow, wc_get_order_types('order-meta-boxes'), true)) {
                include plugin_dir_path(__FILE__) . "partials/stock-locations-admin-order-filter.php";
            }
        }
    }

    /**
     * Handle any filters.
     *
     * @param array $query_vars Query vars.
     * @return array
     */
    public function request_query($query_vars)
    {

        if (get_option('woocommerce_manage_stock') === 'yes') {

            global $typenow;

            if (in_array($typenow, wc_get_order_types('order-meta-boxes'), true)) {

                $location = filter_input(INPUT_GET, $this->plugin_name . "-location", FILTER_SANITIZE_NUMBER_INT);

                // Filter the orders by the posted location.
                if ($location) {

                    //Get original meta query
                    $meta_query = $query_vars['meta_query'] ?? array();

                    //Add our meta query to the original meta queries
                    $meta_query[] = array(
                        array(
                            'key'     => '_shipping_location',
                            'value'   => $location,
                            'compare' => '=',
                        ),
                    );

                    $query_vars['meta_query'] =  $meta_query;
                }
            }
        }

        return $query_vars;
    }
}
