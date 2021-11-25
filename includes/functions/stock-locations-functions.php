<?php

function stock_locations_slug()
{
    return "location";
}

/**
 * Get user selected location
 *
 * @return string
 */
function stock_locations_get_location()
{

    //saved to cookie if not logged in
    $location = filter_input(INPUT_COOKIE, stock_locations_slug(), FILTER_VALIDATE_INT) ?? false;
    if (function_exists("WC") && !is_admin() && is_user_logged_in()) {

        $location = get_transient(STOCK_LOCATIONS_SLUG . '-' . get_current_user_id() . '-location');
        //clean up
        stock_locations_set_location($location);
    }
    return $location;
}

/**
 * set user selected location
 *
 * @return string
 */
function stock_locations_set_location($location)
{

    //saved to transient if not logged in
    if (function_exists("WC") && !is_admin() && is_user_logged_in()) {

        //setting location to transient with key plugin name and logged in user id, which expires in a week
        set_transient(STOCK_LOCATIONS_SLUG . '-' . get_current_user_id() . '-location', $location, WEEK_IN_SECONDS);

        //clean up
        if (!headers_sent() && filter_input(INPUT_COOKIE, stock_locations_slug(), FILTER_VALIDATE_INT)) {
            setcookie(stock_locations_slug(), "", time() - DAY_IN_SECONDS);
        }
    } else {
        setcookie(stock_locations_slug(), $location, time() + DAY_IN_SECONDS);
    }
}

/**
 * Get user selected location title
 *
 * @return string
 */
function stock_locations_location_name()
{
    return stock_locations_get_location() != false ? get_post_field("post_title", stock_locations_get_location()) : "";
}

function stock_locations_order_shipping_methods($order)
{

    $order = wc_get_order($order);

    if ($order) {

        return array_map(function ($method) {
            return $method->get_method_id();
        }, $order->get_shipping_methods());
    }

    return ["local_pickup"];
}

/**
 * Check if order is local pickup
 *
 * @param WC_Order|int $order
 * @return boolean
 */
function stock_locations_order_is_local_pickup($order)
{
    return in_array("local_pickup", stock_locations_order_shipping_methods($order));
}

/**
 * Check if cart is local pickup
 *
 * @return boolean
 */
function stock_locations_cart_is_local_pickup()
{
    return in_array("local_pickup", wc_get_chosen_shipping_method_ids());
}

/**
 * Get all main locations
 *
 * @param string $fields
 * @return array
 */
function stock_locations_locations($fields = "all")
{
    return get_posts(array(
        "post_type" => stock_locations_slug(),
        'no_found_rows' => true,
        "fields" => $fields,
        'post_status' => 'publish',
        'numberposts' => -1,
    ));
}

/**
 * Get all locations as ids
 *
 * @return array
 */
function stock_locations_location_ids()
{
    return stock_locations_locations("ids");
}

/**
 * Check if is front page (not admin page)
 *
 * @return boolean
 */
function stock_locations_is_front_page()
{
    return !is_admin();
}

function stock_locations_meta_key($suffix = "")
{
    return "_" . STOCK_LOCATIONS_SLUG . "-stock-" . $suffix;
}

/**
 * Get currently selected tab
 *
 * @return string
 */
function stock_locations_options_get_tab()
{
    return filter_input(INPUT_GET, "tab", FILTER_SANITIZE_STRING) ?: filter_input(INPUT_POST, "tab", FILTER_SANITIZE_STRING) ?: array_key_first(stock_locations_options_get_tabs());
}

/**
 * Get options tab names
 *
 * @return array
 */
function stock_locations_options_get_tabs()
{
    return apply_filters(STOCK_LOCATIONS_SLUG . "-option-tabs", array(
        "tab-prompt" => __("Prompt", STOCK_LOCATIONS_SLUG),
    ));
}

/**
 * Inserts any number of scalars or arrays at the point
 * in the haystack immediately after the search key ($needle) was found,
 * or at the end if the needle is not found or not supplied.
 * Modifies $haystack in place.
 * 
 * @link https://stackoverflow.com/a/40305210/5956589
 * @param array $haystack 
 * @param string
 * @param mixed 
 * @return array
 */
function stock_locations_array_insert_after($haystack, $needle = '', $stuff = array())
{
    $pos = array_search($needle, array_keys($haystack)) ?: count($haystack);

    $second_array = array_splice($haystack, $pos + 1);
    $haystack = array_merge($haystack, $stuff, $second_array);

    return $haystack;
}