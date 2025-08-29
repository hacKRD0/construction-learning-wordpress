<?php
/** Woo-400. Buy Credits Product with custom quantity - ensures a custom number of credits can be purchased.
  */

add_action( 'woocommerce_before_add_to_cart_button', 'trikona_product_price_input', 9 );

function trikona_product_price_input() {
  global $product;
  $obj = new Trikona();

  $terms = get_the_terms( $product->get_id() , 'product_cat' );
  if ($terms[0]->term_id == $obj->credit_category_id){
  woocommerce_form_field( 'set_price', array(
      'type' => 'text',
      'required' => true,
      'label' => 'Enter Your Credit Requirement',
     ));
    }
}

add_filter( 'woocommerce_add_cart_item_data', 'trikona_product_add_on_cart_item_data', 9999, 2 );

function trikona_product_add_on_cart_item_data( $cart_item, $product_id ) {
    $obj = new Trikona();
    $terms = get_the_terms( $product_id , 'product_cat' );

    if ($terms[0]->term_id == $obj->credit_category_id){
        $cart_item['set_price'] = sanitize_text_field( $_POST['set_price'] );
        return $cart_item;
    }
}

add_action( 'woocommerce_before_calculate_totals', 'trikona_alter_price_cart', 9999 );

function trikona_alter_price_cart( $cart ) {
    $obj = new Trikona();

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];
        $terms = get_the_terms( $product->get_id() , 'product_cat' );
        if ($terms[0]->term_id !== $obj->credit_category_id) continue;
        $cart_item['data']->set_price( $cart_item['set_price'] );
    }
}
?>