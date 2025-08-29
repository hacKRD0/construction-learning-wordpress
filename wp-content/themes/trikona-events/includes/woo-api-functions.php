<?php
// get user orders by product from main site via custom API
function get_user_orders_from_main( $user_id ) {
    $ck = 'ck_d18a55aab7830703b3bbb78633cddba3780745d6'; // consumer key
    $cs = 'cs_3956838a01a9d7e80e3e49cb80cf7f3b65ed2898'; // consumer secret

    $network_url = network_site_url();

    $api_url = add_query_arg( array(
        'customer_id'    => $user_id,
        'consumer_key'   => $ck,
        'consumer_secret'=> $cs,
    ), $network_url . '/wp-json/custom-api/v1/orders-with-event' );

    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        return [];
    }

    $body = wp_remote_retrieve_body( $response );
    return json_decode( $body, true );
}