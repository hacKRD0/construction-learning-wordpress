<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/error.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}

require_once get_template_directory() . '/custom-def/class-trikona.php';

$obj = new Trikona();

$is_free_credits_product = false;

$product_id = get_the_ID();

if (!empty($product_id)) {
	$product_categories = wc_get_product_terms( $product_id, 'product_cat' );

	if (!empty($product_categories)) {
		$index     =	array_search($obj->free_credits_product_category, array_column($product_categories, "name"));
		if (false !== $index) {
			$is_free_credits_product = true;

			if ( is_user_logged_in() && wc_customer_bought_product( '', get_current_user_id(), $product_id )) {
				$purchased = array_search('You have already redeemed the Free Credits !!.', array_column($notices, "notice"));

				if ($purchased === false) {
					$notices[] = [
						"notice" => "Free Credits can be redeemed only once !!.",
						"data" => []
					];
				}
			}
		}
	}
}

$credit_category = $obj->credit_category; // Change this to your 'credit' category slug or ID
$is_credit_category_product = has_term( $credit_category, 'product_cat', $product_id );

$hide_no_permission_error = false;

if (is_page('cart') || $is_free_credits_product || $is_credit_category_product) {
	$hide_no_permission_error = true;
}

foreach ( $notices as $key => $notice ){
	if ( strpos( $notice['notice'], 'you do not have permission to access this membership' ) !== false && $hide_no_permission_error ) {
		unset($notices[$key]);
	}
}
if (!empty($notices)) {
?>
<ul class="woocommerce-error" role="alert">
	<?php foreach ( $notices as $notice ) : ?>
		<li<?php echo wc_get_notice_data_attr( $notice ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php echo wc_kses_notice( $notice['notice'] ); ?>
		</li>
	<?php endforeach; ?>
</ul>
<?php } ?>