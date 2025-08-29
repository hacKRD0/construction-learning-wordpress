<?php
add_action( 'rest_api_init', function () {
    register_rest_route( 'custom-api/v1', '/orders-with-event', [
        'methods'  => 'GET',
        'callback' => function ( $request ) {

            $user_id = absint( $request['customer_id'] ?? 0 );
            if ( ! $user_id ) {
                return new WP_Error( 'missing_params', 'customer_id is required', [ 'status' => 400 ] );
            }

            // Step 1: Get all event product IDs
            $products = get_posts([
                'post_type'      => ['product','product_variation'],
                'post_status'    => 'publish',
                'numberposts'    => -1,
                'fields'         => 'ids',
                'meta_query'     => [
                    [
                        'key'     => '_wcem_event_id',
                        'value'   => '',
                        'compare' => '!=',
                    ],
                ],
            ]);

            if ( empty( $products ) ) {
                return []; // no event products
            }

            // flip for faster lookup
            $event_products = array_flip( $products );

            // Step 2: Get all orders for this user (IDs only)
            $customer_orders = wc_get_orders([
                'customer_id' => $user_id,
                'limit'       => -1,
                'return'      => 'ids',
            ]);

            if ( empty( $customer_orders ) ) {
                return [];
            }

            $orders = [];

            foreach ( $customer_orders as $order_id ) {
                $order = wc_get_order( $order_id );
                if ( ! $order ) {
                    continue;
                }

                $include_order = false;
                $items = [];

                foreach ( $order->get_items() as $item ) {
                    $product_id = $item->get_product_id();
                    $product    = $item->get_product();

                    if ( isset( $event_products[$product_id] ) ) {
                        $include_order = true;
                    }

                    $subtotal = (float) $item->get_subtotal();
                    $total    = (float) $item->get_total();

                    $items[] = [
                        'item_id'       => $item->get_id(),
                        'product_id'    => $product_id,
                        'name'          => $item->get_name(),
                        'quantity'      => $item->get_quantity(),
                        'subtotal'      => $subtotal,
                        'subtotal_html' => wc_price( $subtotal, [ 'currency' => $order->get_currency() ] ),
                        'total'         => $total,
                        'total_html'    => wc_price( $total, [ 'currency' => $order->get_currency() ] ),
                        'sku'           => $product ? $product->get_sku() : '',
                    ];
                }

                if ( ! $include_order ) {
                    continue; // skip orders without event products
                }

                $order_total = (float) $order->get_total();

                $orders[] = [
                    'id'         => $order->get_id(),
                    'status'     => $order->get_status(),
                    'currency'   => $order->get_currency(),
                    'total'      => $order_total,
                    'total_html' => wc_price( $order_total, [ 'currency' => $order->get_currency() ] ),
                    'date'       => $order->get_date_created() ? $order->get_date_created()->date( 'Y-m-d H:i:s' ) : '',
                    'customer'   => [
                        'id'    => $order->get_customer_id(),
                        'email' => $order->get_billing_email(),
                        'name'  => trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
                    ],
                    'billing'    => $order->get_address( 'billing' ),
                    'shipping'   => $order->get_address( 'shipping' ),
                    'line_items' => $items,
                ];
            }

            return $orders;
        },
        'permission_callback' => '__return_true'
    ]);
});