<?php


/**Woo-500. Disable Payment Method for Specific Category
 */

add_filter( 'woocommerce_available_payment_gateways', 'trikona_unset_gateway_by_category' );

function trikona_unset_gateway_by_category( $available_gateways ) {
	$obj = new Trikona();

    if ( is_admin() ) return $available_gateways;
    if ( ! is_checkout() ) return $available_gateways;
    $unset = false;
    $category_id = $obj->credit_category_id; // TARGET CATEGORY
    foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
        $terms = get_the_terms( $values['product_id'], 'product_cat' );
        foreach ( $terms as $term ) {
            if ( $term->term_id == $category_id ) {
                $unset = true; // CATEGORY IS IN THE CART
                break;
            }
        }
    }
    if ( $unset == true ){
        unset( $available_gateways[$obj->mycred_payment_method] ); // DISABLE COD IF CATEGORY IS IN THE CART
    } else {
        foreach ($available_gateways as $key => $available_gateway) {
            if ($key != $obj->mycred_payment_method) {
                unset($available_gateways[$key]);
            }
        }
    }
    return $available_gateways;
}

?>