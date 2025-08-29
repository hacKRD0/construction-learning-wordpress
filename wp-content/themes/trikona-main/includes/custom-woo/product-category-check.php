<?php



/* Woo-600 All Products add to cart check so that they are not clubbed with any Credits Category
 * @author        Shailendra
 */

add_filter( 'woocommerce_add_to_cart_validation', 'disallow_credit_category_combinations_in_cart', 15, 3 );

function disallow_credit_category_combinations_in_cart( $passed, $product_id, $quantity ) {
    $obj = new Trikona();

    $cart_items = WC()->cart->get_cart();
    $credit_category = $obj->credit_category; // Change this to your 'credit' category slug or ID
    $is_credit_category_product = has_term( $credit_category, 'product_cat', $product_id );
    $other_product_in_cart = false;
    $credit_product_in_cart = false;

    // Check each product in the cart
    foreach ( $cart_items as $cart_item ) {
        $product_in_cart_id = $cart_item['product_id'];
        if ( has_term( $credit_category, 'product_cat', $product_in_cart_id ) ) {
            $credit_product_in_cart = true;
        } else {
            $other_product_in_cart = true;
        }
    }

    // Conditions to check and show notice
    if ( $is_credit_category_product && $other_product_in_cart ) {
        wc_clear_notices();
        wc_add_notice( 'Sorry, Your cart has other products. Credit category products cannot be purchased with other category products. Please complete your current purchase first or buy credit category products separately.', 'error' );
        $passed = false;
    } elseif ( !$is_credit_category_product && $credit_product_in_cart ) {
        wc_clear_notices();
        wc_add_notice( 'Sorry, Your cart has other products. Products from other categories cannot be purchased with credit category products. Please complete your purchase of credit category products first or remove them from your cart.', 'error' );
        $passed = false;
    }

    return $passed;
}


?>
