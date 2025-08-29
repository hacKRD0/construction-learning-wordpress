<?php /* Template Name: Events Orders */ 
$current_user_id = get_current_user_id();
$orders = get_user_orders_from_main( $current_user_id );

echo '<pre>';
print_r( $orders );
echo '</pre>';	die();