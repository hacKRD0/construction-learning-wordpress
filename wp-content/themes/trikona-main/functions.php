<?php
/**
 * Trikona-Main is a Child theme of Trikona Edtech Parent Theme
 */


 /* Codes for the following included
 1. Creates Custom Post Type for Student Courses
 2. To allow upload of csv files

 Woo-200. Buy Credits Product with custom quantity - ensures a custom number of credits can be purchased.
 Woo-100. Restrict a specific product purchase to just one time per user. Restricts the purcahse of 500 Free Credits product
 Woo-300. Restrict product to be displayed based on product category-ID & based on the user-role. Ensures role based memberships are accessible for the specific user-roles only.
 Woo-400. Buy Credits Product with custom quantity - ensures a custom number of credits can be purchased.
 Woo-500. Disable Payment Method for Specific Category
 Woo-600 All Products add to cart check so that they are not clubbed with any Credits Category

*/


//temp add
// add_action('after_setup_theme', 'trikona_setup');
// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


//     add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_styles', 99 );
//     function dequeue_woocommerce_styles() {
//         wp_dequeue_style( 'woocommerce-general' );
//         wp_dequeue_style( 'woocommerce-layout' );
//         wp_dequeue_style( 'woocommerce-smallscreen' );
//     }

add_filter( 'woocommerce_enqueue_styles', function( $styles ) {
    return $styles; // return default array of WooCommerce styles
});


// Autoload all PHP files from includes/ and its subdirectories
function load_theme_includes($dir) {
    foreach (glob($dir . '/*.php') as $file) {
        require_once $file;
    }

    foreach (glob($dir . '/*', GLOB_ONLYDIR) as $subdir) {
        load_theme_includes($subdir); // Recursively include subdirectories
    }
}

// Load from the includes folder
load_theme_includes(get_stylesheet_directory() . '/includes');

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include_once( __DIR__ . '/dashboard/class_profiles_ajax.php');


/*1. This creates a custom post type for student-courses which are required for college students as well as professionals to identify the course they have completed in their education profile
* @author
*/

function create_posttype() {

    register_post_type( 'Student Courses',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Student Courses' ),
                'singular_name' => __( 'Student Courses' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'studentcourses'),
            'show_in_rest' => true,

        )
    );
}

//Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

/********************************/

// /* 2. To allow upload of csv files
// * @author  Shailendra
// */

// function my_theme_custom_upload_mimes( $existing_mimes ) {

//     // Add csv to the list of mime types.
//     $existing_mimes['csv'] = 'text/csv';

//     // Return the array back to the function with our added mime types.
//     return $existing_mimes;
// }

// // Hook the function to the 'upload_mimes' filter.
// add_filter( 'upload_mimes', 'my_theme_custom_upload_mimes' );




/** added by Surendra Raghuvamshi
 * Description of the function purpose to be written
 *
 */



/*10. Ensure the default group is public
 * @author        Shailendra
*/
function custom_set_default_group_status() {
    // Set the default group status to public
    return 'public';
}
add_filter('bp_groups_default_status', 'custom_set_default_group_status');

/* Included logic to handle resume downloads and manage the user dashboard.
 * @author    Mahendragiri Gauswami
*/
require_once(get_stylesheet_directory().'/resume/includes/download-resume-route.php');
require_once(get_stylesheet_directory().'/dashboard/includes/bp-users-manage.php');

//     // Return the filtered list of levels
//     return $filtered_levels;
// }

// add_action('rest_api_init', function () {
//     register_rest_route('pmpro/v1', '/memberships', [
//         'methods'  => 'GET',
//         'callback' => 'pmpro_get_membership_groups_with_levels_custom',
//         'permission_callback' => '__return_true', // Secure later
//     ]);
// });

// function pmpro_get_membership_groups_with_levels_custom($request) {
//     global $wpdb;

//     if (!function_exists('pmpro_get_level_groups_in_order') || !function_exists('pmpro_get_level_ids_for_group')) {
//         return new WP_Error('pmpro_groups_missing', 'PMPro Groups functions are not available.', ['status' => 500]);
//     }

//     // Get search term if provided
//     $s = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';

//     // Get level groups in order
//     $level_groups = pmpro_get_level_groups_in_order();

//     // Get all levels
//     $sqlQuery = "SELECT * FROM $wpdb->pmpro_membership_levels ";
//     if ($s) {
//         $sqlQuery .= "WHERE name LIKE '%" . esc_sql($s) . "%' ";
//     }
//     $sqlQuery .= "ORDER BY id ASC";

//     $levels = $wpdb->get_results($sqlQuery, OBJECT);

//     // Reorder levels if needed
//     $pmpro_level_order = pmpro_getOption('level_order');
//     if (empty($s) && !empty($pmpro_level_order)) {
//         $order = explode(',', $pmpro_level_order);
//         $level_ids = wp_list_pluck($levels, 'id');

//         // Clean and fix order
//         $order = array_values(array_filter($order, fn($id) => in_array($id, $level_ids)));
//         foreach ($level_ids as $id) {
//             if (!in_array($id, $order)) {
//                 $order[] = $id;
//             }
//         }
//         $order = array_unique($order);
//         pmpro_setOption('level_order', implode(',', $order));

//         // Apply reorder
//         $reordered_levels = [];
//         foreach ($order as $id) {
//             foreach ($levels as $level) {
//                 if ($level->id == $id) {
//                     $reordered_levels[] = $level;
//                 }
//             }
//         }
//     } else {
//         $reordered_levels = $levels;
//     }

//     // Build API response
//     $response = [];

//     foreach ($level_groups as $level_group) {
//         $group_level_ids = pmpro_get_level_ids_for_group($level_group->id);

//         $group_levels_to_show = [];
//         foreach ($reordered_levels as $level) {
//             if (in_array($level->id, $group_level_ids)) {
//                 $group_levels_to_show[] = [
//                     'level_id'        => (string) $level->id,
//                     'level_name'      => $level->name,
//                     'initial_payment' => (float) $level->initial_payment,
//                     'billing_amount'  => (float) $level->billing_amount,
//                     'cycle_number'    => (string) $level->cycle_number,
//                     'cycle_period'    => $level->cycle_period,
//                     'buy_url'         => pmpro_url("checkout", "?level=" . $level->id),
//                     'description'     => wp_kses_post($level->description),
//                 ];
//             }
//         }

//         // Only add groups that have levels
//         if (!empty($group_levels_to_show)) {
//             $response[] = [
//                 'group_id'   => (string) $level_group->id,
//                 'group_name' => $level_group->name,
//                 'packages'   => $group_levels_to_show
//             ];
//         }
//     }

//     return rest_ensure_response($response);
// }

function my_own_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'my_own_mime_types' );