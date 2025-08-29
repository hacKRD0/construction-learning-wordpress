<?php
/********************** Woocommerce Customization Code ***************/

/* Woo-100. Restrict a specific product purchase to just one time per user. Restricts the purcahse of 500 Free Credits product with Product ID = 36129
* @author        Shailendra
*/

add_filter( 'woocommerce_is_purchasable', 'restrict_duplicate_purchase', 10, 2 );

function restrict_duplicate_purchase( $purchasable, $product ) {
    $obj = new Trikona();

    $restricted_product_id = $obj->free_500_credits_product_id;
    if ( $product->get_id() == $restricted_product_id ) {
        if ( wc_customer_bought_product( '', get_current_user_id(), $restricted_product_id ) ) {
            $purchasable = false; // Make product unpurchasable if it has been bought before

            // Display an error notice
            wc_clear_notices();
            wc_add_notice( __( 'You have already redeemed the Free Credits !!.' , 'woocommerce' ), 'error' );
            }
    }
    return $purchasable;
}
?>
