=== Stock Locations ===
Contributors: tygalive
Donate link: https://tyganeutronics.com
Tags: woocommerce, stock, location, store, filter, product, e-shop
Requires at least: 3.0.1
Tested up to: 5.8
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily setup stock locations and ensure no customer buys a product from a location without stock.

== Description ==

Add stock locations to your woocommerce store and allow customers to select a store first before they start shopping. This eliminates the overhead of a customer buying a product not available in their location.
This is hyperthetically the same as a customer shopping from a physical shop, 

Key features:

*   Customers only sees products available where they want to collect.
*   Orders can be grouped by location, making it easier to process Orders.
*   Order location can be changed in the order management screen.
*   Location is sent to the customer in order emails so they are reminded where to collect.
*   Works with existing woocommerce stock management system.

There is also a shortcode to display the currenlty selected location `woo.store.display` and `woo.store.selector` which has the logic to allow a customer to select a new location.

The selected location is reset after completing an order or when a woocommerce session naturally dies.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `stock-locations.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= Can a customer reselect a stock location after selecting aniother? =

Yes, you will have to include the `woo.store.selector` shortcode, where customers would be provided with a dialogue allowing them to set their preffered shopping location.

= What if a customer changes their mind on checkout? =

The plugin will re evaluate all the products in the cart/order before checkout to ensure the newly selected location has stocks and remove products without stock at the new location.

== Changelog ==

= 1.0 =
* Initial Release.

== Upgrade Notice ==

= 1.0 =
Initial Release.
