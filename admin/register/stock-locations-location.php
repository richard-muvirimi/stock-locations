<?php

/**
 *
 * This file is used to register post types.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Stock_locations
 * @subpackage Stock_locations/admin/register
 */

register_post_type(stock_locations_slug(), array(
    'show_ui' => true,
    'show_in_menu' => current_user_can('manage_woocommerce') ? "woocommerce" : true,
    'description' => __("Filter products based on stock location", $this->plugin_name),
    'exclude_from_search' => true,
    'hierarchical' => false,
    'labels' => array(
        'name' => _x('Stock Locations', 'stock location type general name', $this->plugin_name),
        'singular_name' => _x('Stock Location', 'stock location type singular name', $this->plugin_name),
        'add_new' => _x('Add New', 'stock location', $this->plugin_name),
        'add_new_item' => __('Add New Stock Location', $this->plugin_name),
        'edit_item' => __('Edit Stock Location', $this->plugin_name),
        'new_item' => __('New Stock Location', $this->plugin_name),
        'view_item' => __('View Stock Location', $this->plugin_name),
        'view_items' => __('View Stock Locations', $this->plugin_name),
        'search_items' => __('Search Stock Locations', $this->plugin_name),
        'not_found' => __('No stock locations found.', $this->plugin_name),
        'not_found_in_trash' => __('No stock locations found in Trash.', $this->plugin_name),
        'parent_item_colon' => __('Parent Page:', $this->plugin_name),
        'all_items' => __('Stock Locations', $this->plugin_name),
        'archives' => __('Stock Location Archives', $this->plugin_name),
        'attributes' => __('Stock Location Attributes', $this->plugin_name),
        'insert_into_item' => __('Insert into stock location', $this->plugin_name),
        'uploaded_to_this_item' => __('Uploaded to this stock location', $this->plugin_name),
        'featured_image' => _x('Featured image', 'stock location', $this->plugin_name),
        'set_featured_image' => _x('Set featured image', 'stock location', $this->plugin_name),
        'remove_featured_image' => _x('Remove featured image', 'stock location', $this->plugin_name),
        'use_featured_image' => _x('Use as featured image', 'stock location', $this->plugin_name),
        'filter_items_list' => __('Filter stock locations list', $this->plugin_name),
        'filter_by_date' => __('Filter by date', $this->plugin_name),
        'items_list_navigation' => __('Stock Locations list navigation', $this->plugin_name),
        'items_list' => __('Stock Locations list', $this->plugin_name),
        'item_published' => __('Stock Location published.', $this->plugin_name),
        'item_published_privately' => __('Stock Location published privately.', $this->plugin_name),
        'item_reverted_to_draft' => __('Stock Location reverted to draft.', $this->plugin_name),
        'item_scheduled' => __('Stock Location scheduled.', $this->plugin_name),
        'item_updated' => __('Stock Location updated.', $this->plugin_name),
        'item_link' => _x('Stock Location Link', 'navigation link block title', $this->plugin_name),
        'item_link_description' => _x('A link to a stock location.', 'navigation link block description', $this->plugin_name),
    ),
    'rewrite' => array('slug' => stock_locations_slug()),
    'supports' => array(
        'title', "excerpt",
    ),
    'delete_with_user' => false,
));
