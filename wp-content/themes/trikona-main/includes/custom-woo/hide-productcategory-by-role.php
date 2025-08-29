<?php
/* Woo-300. Restrict product to be displayed based on product category-ID & based on the user-role. Ensures role based memberships are accessible for the specific user-roles only.
* @author   Shailendra
*/

add_filter('woocommerce_is_purchasable', 'af_product_purchasable_by_user_role', 10, 2);

function af_product_purchasable_by_user_role($purchasable, $product)
{
    $obj = new Trikona();

    // Role → Category restriction map
    $role_restrictions = [
        "student"        => $obj->student_categories,
        "professional"   => $obj->professional_categories,
        "corporate"      => $obj->corporate_categories,
        "college-admin"  => $obj->college_admin_categories,
        "instructor"     => $obj->instructor_categories
    ];

    // Get current user role
    $current_user_roles = is_user_logged_in() ? wp_get_current_user()->roles : ['guest'];
    $current_role = is_array($current_user_roles) ? current($current_user_roles) : 'guest';

    // Set default category list
    $restricted_categories = [];

    // If the user's role has restrictions
    if (array_key_exists($current_role, $role_restrictions)) {
        $restricted_categories = (array) $role_restrictions[$current_role];
    } else {
        error_log("User role '{$current_role}' not recognized in restrictions list.");
    }

    // Get product's category IDs
    $product_cat_ids = $product->get_category_ids();
    $product_cat_ids = is_array($product_cat_ids) ? $product_cat_ids : [];

    // Default to purchasable
    $purchasable = true;

    // Check if current product category intersects with restricted categories
    if (!empty($restricted_categories) && array_intersect($product_cat_ids, $restricted_categories)) {
        $purchasable = false;

        // Show notice
        $membership_page_url = home_url('/membership-details/');
        wc_clear_notices();
        wc_add_notice(
            'Sorry, you do not have permission to access this membership. You can only buy <a href="' . esc_url($membership_page_url) . '">Student Memberships</a>.',
            'error'
        );
    }

    return $purchasable;
}
?>
