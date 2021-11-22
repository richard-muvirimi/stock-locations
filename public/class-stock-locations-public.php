<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/public
 * @author     Richard (Webentangled) <tygalive@gmail.com>
 */
class Stock_locations_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style($this->plugin_name . "-selector", plugin_dir_url(__FILE__) . 'css/stock-locations-public.css', array(), $this->version, 'all');
        wp_register_style($this->plugin_name . "-prompt", plugin_dir_url(__FILE__) . 'css/stock-locations-prompt.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script($this->plugin_name . "-prompt", plugin_dir_url(__FILE__) . 'js/stock-locations-prompt.js', array('jquery'), $this->version, false);

        wp_register_script($this->plugin_name . "-selector", plugin_dir_url(__FILE__) . 'js/stock-locations-public.js', array('jquery', $this->plugin_name . "-prompt"), $this->version, false);
    }

    public function wp_loaded()
    {
        if (function_exists("WC")) {

            if (get_option('woocommerce_manage_stock') === 'yes') {

                if (filter_input(INPUT_GET, "action", FILTER_SANITIZE_URL) == $this->plugin_name) {
                    $location = filter_input(INPUT_GET, stock_locations_slug(), FILTER_VALIDATE_INT) ?? false;

                    stock_locations_set_location($location);

                    wp_redirect(filter_input(INPUT_GET, "redirect", FILTER_SANITIZE_URL));
                    exit;
                }
            }
        }
    }

    public function loadFooter()
    {
        if (function_exists("WC")) {

            if (get_option('woocommerce_manage_stock') === 'yes') {

                $locations = stock_locations_locations();

                wp_localize_script($this->plugin_name . "-selector", "wsl", array(
                    "name" => $this->plugin_name,
                    "default" => $locations[0]->ID ?? "",
                    "slug" => stock_locations_slug(),
                ));

                if (wp_cache_get("selector_loaded", $this->plugin_name)) {
                    include plugin_dir_path(__FILE__) . 'partials/stock-locations-public-selector.php';
                }

                if ($this->shouldShowPrompt()) {
                    wp_enqueue_style($this->plugin_name . "-prompt");
                    wp_enqueue_script($this->plugin_name . "-prompt");

                    include plugin_dir_path(__FILE__) . 'partials/stock-locations-public-prompt.php';
                }
            }
        }
    }

    public function renderStoreSelector()
    {

        wp_cache_set("selector_loaded", true, $this->plugin_name);

        ob_start();
        wp_enqueue_style($this->plugin_name . "-selector");
        wp_enqueue_script($this->plugin_name . "-selector");

        include plugin_dir_path(__FILE__) . 'partials/stock-locations-public-display.php';

        add_action("wp_footer", array($this, "loadFooter"));

        return ob_get_clean();
    }

    public function renderStoreName()
    {
        return stock_locations_location_name() ?: __("Select Location", $this->plugin_name);
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

        if (get_option('woocommerce_manage_stock') === 'yes') {

            $product = wc_get_product($product_id);
            if ($product && $product->managing_stock()) {

                if (stock_locations_is_front_page() && stock_locations_get_location() !== false) {

                    $stock = $product->get_meta(stock_locations_meta_key(stock_locations_get_location())) ?: 0;

                    $in_stock &= $stock > 0;
                }
            }
        }

        return $in_stock;
    }

    /**
     * Filter products on the front end
     *
     * @param WP_Query $query
     * @return WP_Query
     */
    public function pre_get_posts($query)
    {

        if (get_option('woocommerce_manage_stock') === 'yes') {

            $product = wc_get_product();
            if ($product && $product->managing_stock()) {

                if (stock_locations_is_front_page() && $query->get("post_type") == "product") {
                    $location = stock_locations_get_location();
                    if ($location !== false) {

                        //Get original meta query
                        $meta_query = $query->get('meta_query') ?: array();

                        //Add our meta query to the original meta queries
                        $meta_query[] = array(
                            'key' => stock_locations_meta_key($location),
                            'value' => '0',
                            'compare' => '>',
                        );

                        $query->set('meta_query', $meta_query);
                    }
                }
            }
        }

        return $query;
    }

    public function shouldShowPrompt()
    {
        return function_exists("WC") && (get_option('woocommerce_manage_stock') === 'yes') && stock_locations_get_location() == false;
    }

    public function woocommerce_review_order_after_shipping()
    {
        if (stock_locations_cart_is_local_pickup()) {
            include plugin_dir_path(__FILE__) . "partials/stock-locations-public-collection.php";
        }
    }

    public function woocommerce_thankyou($order)
    {

        $order = wc_get_order($order);

        if (stock_locations_order_is_local_pickup($order)) {

            include plugin_dir_path(__FILE__) . "partials/stock-locations-public-order-location.php";
        }
    }

    /**
     * Save order location and or route
     *
     * @param int $order
     * @return void
     */
    public function woocommerce_checkout_update_order_meta($order)
    {
        $order = wc_get_order($order);

        //save order location if local pickup
        if (stock_locations_order_is_local_pickup($order)) {
            $location =  stock_locations_get_location();

            if ($location) {

                $order->update_meta_data("_shipping_location", stock_locations_get_location());
                $order->save_meta_data();
            }
        }
    }

    /**
     * When saving product stock, also update location stock based on the changed stock amount
     *
     * @param string $sql
     * @param int $product_id_with_stock
     * @param int $new_stock
     * @param string $operation
     * @return string
     */
    public function woocommerce_updated_product_stock($sql, $product_id_with_stock, $new_stock, $operation)
    {
        /**
         * @global wpdb $wpdb
         */
        global $wpdb;

        if (get_option('woocommerce_manage_stock') === 'yes') {

            $product = wc_get_product($product_id_with_stock);
            if ($product && $product->managing_stock()) {

                if (stock_locations_get_location() != false) {

                    $key = stock_locations_meta_key(stock_locations_get_location());

                    // Ensures a row exists to update.
                    add_post_meta($product_id_with_stock, $key, 0, true);

                    //use this to get actual value instead of cached
                    $current_stock = wc_stock_amount(
                        $wpdb->get_var(
                            $wpdb->prepare(
                                "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key='_stock';",
                                $product_id_with_stock
                            )
                        )
                    );

                    //difference between main product stock
                    $stock_difference = absint($new_stock - $current_stock);

                    // Calculate new value for filter below. Set multiplier to subtract or add the meta_value.
                    switch ($operation) {
                        case "set":

                            $location_sql = $wpdb->prepare(
                                "UPDATE {$wpdb->postmeta} SET meta_value = %f WHERE post_id = %d AND meta_key='{$key}'",
                                $current_stock + $stock_difference,
                                $product_id_with_stock
                            );
                            break;
                        case 'increase':
                            $multiplier = 1;
                        default:
                            $multiplier = $multiplier ?? -1;

                            // Generate SQL.
                            $location_sql = $wpdb->prepare(
                                "UPDATE {$wpdb->postmeta} SET meta_value = meta_value %+f WHERE post_id = %d AND meta_key='{$key}'",
                                wc_stock_amount($stock_difference) * $multiplier, // This will either subtract or add depending on operation.
                                $product_id_with_stock
                            );
                    }

                    $wpdb->query($location_sql);
                }
            }
        }

        return $sql;
    }
}