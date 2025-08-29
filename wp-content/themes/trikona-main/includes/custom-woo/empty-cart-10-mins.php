<?php
/*Woo-700. Empty Cart after 10 minutes
 * @author        Shailendra
 */

add_action('woocommerce_add_to_cart', 'set_cart_expiration_transient');
function set_cart_expiration_transient() {
    // Set or reset a transient that lasts for 60 minutes
    set_transient('wc_cart_expiration_time', 'expire', 30 * MINUTE_IN_SECONDS);
}

add_action('wp_loaded', 'check_cart_expiration_and_empty');
function check_cart_expiration_and_empty() {
    // Check if WooCommerce is active and the cart class is available
    if (function_exists('WC') && isset(WC()->cart)) {
        // Ensure we're not in the admin or doing ajax requests
        if (!is_admin() && !defined('DOING_AJAX')) {
            // Check if our cart expiration transient does not exist
            if (!get_transient('wc_cart_expiration_time')) {
                // Ensure the WC cart session is loaded to avoid errors
                WC()->cart->get_cart(); // This loads the cart contents if not already loaded

                // Empty the cart
                WC()->cart->empty_cart();

                // Optionally, set a new transient to start the 60 minute countdown again
                // set_transient('wc_cart_expiration_time', 'expire', 60 * MINUTE_IN_SECONDS);
            }
        }
    }
}

?>