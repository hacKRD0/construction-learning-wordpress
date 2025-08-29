<?php
// Load Woo API functions
require_once get_stylesheet_directory() . '/includes/woo-api-functions.php';

function trikona_main_child_enqueue_styles() {
    // // Load parent theme stylesheet
    // wp_enqueue_style(
    //     'trikona-main-style',
    //     get_template_directory_uri() . '/style.css'
    // );

    // Load child theme stylesheet
    wp_enqueue_style(
        'trikona-main-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('trikona-main-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'trikona_main_child_enqueue_styles');

function bsos_gutenberg_categories_fix($args) {
	$args['show_in_rest'] = true;
	return $args;
}
add_filter('em_ct_categories','bsos_gutenberg_categories_fix');

// add_action('template_redirect', function() {
//     if ( is_front_page() ) {
//         wp_redirect(home_url('/docs/'), 301);
//         exit;
//     }
// });

function modify_existing_event_cpt_for_rest() {
    global $wp_post_types;

    if (isset($wp_post_types['event'])) {
        $wp_post_types['event']->show_in_rest = true;
    }
}
add_action('init', 'modify_existing_event_cpt_for_rest', 20);

add_filter( 'use_block_editor_for_post', '__return_true', 10 );

// Enable Block Editor for all post types
add_filter( 'use_block_editor_for_post_type', '__return_true', 10 );

// Example CPT registration with show_in_rest => true
function my_custom_post_type() {
    $args = array(
        'label' => 'Events',
        'public' => true,
        'show_in_rest' => true, // IMPORTANT: For Gutenberg
        'supports' => array( 'title', 'editor', 'thumbnail' ),
    );
    register_post_type( 'event', $args );
}
add_action( 'init', 'my_custom_post_type' );


function display_event_categories_with_pagination() {
    // Get the current page from query var or default to 1
    $paged = max( 1, get_query_var( 'paged' ) ?: get_query_var( 'page' ) ?: 1 );
    $per_page = 6; // Categories per page

    // Get all terms
    $terms = get_terms( array(
        'taxonomy'   => 'event-categories',
        'hide_empty' => false,
    ) );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return '<p>No event categories found.</p>';
    }

    // Pagination logic
    $total_terms = count( $terms );
    $total_pages = ceil( $total_terms / $per_page );
    $offset = ( $paged - 1 ) * $per_page;
    $paged_terms = array_slice( $terms, $offset, $per_page );

    ob_start();
    // Begin wrapper
    echo '<div class="em pixelbones em-list em-categories-list size-large">';

    foreach ( $paged_terms as $term ) {
        $term_link = get_term_link( $term );
        $term_name = esc_html( $term->name );

        // Get image URL - Enhanced with debugging and multiple fallbacks
        $img_url = get_category_image( $term->term_id, $term_name );

        // Count events - try multiple approaches
        $event_count = count_category_events( $term->term_id );

        ?>
        <div class="em-item em-taxonomy em-category">
            <div class="em-item-image test">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $term_name ); ?>">
            </div>
            <div class="em-item-info">
                <h3 class="em-item-title">
                    <a href="<?php echo esc_url( $term_link ); ?>"><?php echo $term_name; ?></a>
                </h3>
                <div class="em-event-meta em-item-meta">
                    <div class="em-item-meta-line em-taxonomy-events em-category-events">
                        <span class="em-icon-calendar em-icon"></span>
                        <div>
                            <?php echo $event_count > 0 ? "$event_count upcoming event" . ($event_count > 1 ? "s" : "") : "No upcoming events"; ?>
                        </div>
                    </div>
                </div>
                <div class="em-item-desc"></div>
                <div class="em-item-actions input">
                    <a class="em-item-read-more button" href="<?php echo esc_url( $term_link ); ?>">More Info</a>
                </div>
            </div>
        </div>
        <?php
    }

    echo '</div>';

    // Pagination links
    if ( $total_pages > 1 ) {
        echo '<div class="event-category-pagination">';
        echo paginate_links( array(
            'base'      => get_pagenum_link( 1 ) . '%_%',
            'format'    => 'page/%#%/',
            'current'   => $paged,
            'total'     => $total_pages,
            'prev_text' => __('« Prev'),
            'next_text' => __('Next »'),
        ) );
        echo '</div>';
    }

    return ob_get_clean();
}

// Function to get category image with multiple fallback methods
function get_category_image( $term_id, $term_name = '' ) {
    $img_url = '';

    // Method 1: Try original approach - term_image meta
    $img_url = get_term_meta( $term_id, 'term_image', true );

    if ( empty( $img_url ) ) {
        // Method 2: Try term_image_id meta
        $image_id = get_term_meta( $term_id, 'term_image_id', true );
        if ( $image_id ) {
            $img_url = wp_get_attachment_url( $image_id );
        }
    }

    if ( empty( $img_url ) ) {
        // Method 3: Try other common meta keys
        $meta_keys = array(
            'thumbnail_id',
            'category_image',
            'term_thumbnail',
            'wpcf-category-image',
            '_thumbnail_id',
            'category_thumbnail_id',
            'featured_image',
            'category_featured_image'
        );

        foreach ( $meta_keys as $meta_key ) {
            $value = get_term_meta( $term_id, $meta_key, true );
            if ( ! empty( $value ) ) {
                if ( is_numeric( $value ) ) {
                    // It's an attachment ID
                    $img_url = wp_get_attachment_url( $value );
                    if ( $img_url ) {
                        break;
                    }
                } else {
                    // It might be a direct URL
                    $img_url = $value;
                    break;
                }
            }
        }
    }

    if ( empty( $img_url ) ) {
        // Method 4: Check if using Advanced Custom Fields (ACF)
        if ( function_exists( 'get_field' ) ) {
            $acf_image = get_field( 'category_image', 'term_' . $term_id );
            if ( $acf_image ) {
                if ( is_array( $acf_image ) && isset( $acf_image['url'] ) ) {
                    $img_url = $acf_image['url'];
                } elseif ( is_string( $acf_image ) ) {
                    $img_url = $acf_image;
                }
            }
        }
    }

    if ( empty( $img_url ) ) {
        // Method 5: Check Categories Images plugin
        if ( function_exists( 'z_taxonomy_image_url' ) ) {
            $img_url = z_taxonomy_image_url( $term_id );
        }
    }

    if ( empty( $img_url ) ) {
        // Method 6: Check WP Category Image plugin
        if ( function_exists( 'wp_get_attachment_thumb_url' ) ) {
            $image_id = get_option( 'z_taxonomy_image' . $term_id );
            if ( $image_id ) {
                $img_url = wp_get_attachment_url( $image_id );
            }
        }
    }

    // Debug: Log what we found (only for admin users)
    if ( current_user_can( 'manage_options' ) && isset( $_GET['debug_images'] ) ) {
        error_log( "Category $term_id ($term_name): Found image URL: " . ( $img_url ?: 'NONE' ) );

        // Show all term meta for debugging
        $all_meta = get_term_meta( $term_id );
        error_log( "All meta for term $term_id: " . print_r( $all_meta, true ) );
    }

    // Fallback placeholder
    if ( empty( $img_url ) ) {
        $img_url = 'https://via.placeholder.com/300x200?text=' . urlencode( $term_name ?: 'No Image' );
    }

    return $img_url;
}
function count_category_events( $term_id ) {
    $today = current_time('Y-m-d');

    // Method 1: Try your original approach first
    $events_query = new WP_Query( array(
        'post_type'      => 'event',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'event-categories',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => '_event_start_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ),
    ) );

    $count = $events_query->found_posts;
    wp_reset_postdata();

    // If no results, try alternative meta keys
    if ( $count == 0 ) {
        // Try different common meta keys used by event plugins
        $meta_keys = array( '_EventStartDate', 'event_start_date', '_start_date', '_event_end_date' );

        foreach ( $meta_keys as $meta_key ) {
            $events_query = new WP_Query( array(
                'post_type'      => 'event',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'event-categories',
                        'field'    => 'term_id',
                        'terms'    => $term_id,
                    ),
                ),
                'meta_query'     => array(
                    array(
                        'key'     => $meta_key,
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE',
                    ),
                ),
            ) );

            $count = $events_query->found_posts;
            wp_reset_postdata();

            if ( $count > 0 ) {
                break; // Found the right meta key
            }
        }
    }

    // If still no results, try without date filter to see total events in category
    if ( $count == 0 ) {
        $all_events_query = new WP_Query( array(
            'post_type'      => 'event',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'event-categories',
                    'field'    => 'term_id',
                    'terms'    => $term_id,
                ),
            ),
        ) );

        // This will show total events (not just upcoming) - useful for debugging
        $total_events = $all_events_query->found_posts;
        wp_reset_postdata();

        // You can uncomment this line for debugging to see if there are any events at all
        // error_log("Category $term_id has $total_events total events but 0 upcoming events");
    }

    return $count;
}

// Debug function - add this temporarily to see what meta keys your events actually use
function debug_event_meta_keys() {
    if ( current_user_can( 'manage_options' ) && isset( $_GET['debug_events'] ) ) {
        $events = get_posts( array(
            'post_type'      => 'event',
            'posts_per_page' => 5,
            'post_status'    => 'publish',
        ) );

        echo '<div style="background: white; padding: 20px; margin: 20px; border: 1px solid #ccc;">';
        echo '<h3>Debug: Event Meta Keys</h3>';

        foreach ( $events as $event ) {
            echo '<h4>Event: ' . $event->post_title . '</h4>';
            $meta = get_post_meta( $event->ID );
            foreach ( $meta as $key => $value ) {
                if ( strpos( $key, 'date' ) !== false || strpos( $key, 'event' ) !== false ) {
                    echo '<p><strong>' . $key . '</strong>: ' . print_r( $value, true ) . '</p>';
                }
            }
            echo '<hr>';
        }
        echo '</div>';
    }
}
add_action( 'wp_footer', 'debug_event_meta_keys' );

add_shortcode( 'event_categories', 'display_event_categories_with_pagination' );



/**
 * Add INR to Events Manager (Pixelite) currencies – v7.x compatible.
 * Puts INR into the dropdown at Events → Settings → Booking Options. Shailendra on 11-Aug-25
 */
add_filter('em_get_currencies', function ($curr) {
    // EM passes an object with ->names, ->symbols, ->true_symbols
    if (!is_object($curr)) return $curr;

    // Add/overwrite INR entries safely
    if (!isset($curr->names['INR'])) {
        $curr->names['INR'] = 'INR - Indian Rupee';
    }
    $curr->symbols['INR']      = '₹'; // displays near prices
    $curr->true_symbols['INR'] = '₹'; // used in some outputs/emails

    return $curr;
}, 10, 1);


// Hook into REST API
add_action('rest_api_init', function () {
    register_rest_route('eventsmanager/v1', '/events', array(
        'methods'  => 'GET',
        'callback' => 'get_all_events',
        'permission_callback' => '__return_true' // open API
    ));
});

function get_all_events(WP_REST_Request $request) {
    // Query params
    $start_date = $request->get_param('start_date'); // YYYY-MM-DD
    $end_date   = $request->get_param('end_date');   // YYYY-MM-DD
    $filter     = $request->get_param('filter');     // upcoming | past
    $status     = $request->get_param('status');     // custom status field
    $search     = $request->get_param('search');     // keyword search
    $page       = (int) $request->get_param('page') ?: 1;
    $per_page   = (int) $request->get_param('per_page') ?: 10;

    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
        'meta_query'     => array(),
        'orderby'        => 'meta_value',
        'meta_key'       => '_event_start_date',
        'order'          => 'ASC',
    );

    // Search in title/content
    if ($search) {
        $args['s'] = $search;
    }

    // Meta query
    $meta_query = array();
    $today = date('Y-m-d');

    // Start date filter
    if ($start_date) {
        $meta_query[] = array(
            'key'     => '_event_start_date',
            'value'   => $start_date,
            'compare' => '>=',
            'type'    => 'DATE'
        );
    }

    // End date filter
    if ($end_date) {
        $meta_query[] = array(
            'key'     => '_event_end_date',
            'value'   => $end_date,
            'compare' => '<=',
            'type'    => 'DATE'
        );
    }

    // Upcoming events (start date >= today)
    if ($filter === 'upcoming') {
        $meta_query[] = array(
            'key'     => '_event_start_date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE'
        );
    }

    // Past events (end date < today)
    if ($filter === 'past') {
        $meta_query[] = array(
            'key'     => '_event_end_date',
            'value'   => $today,
            'compare' => '<',
            'type'    => 'DATE'
        );
    }

    // Status filter (if you use _event_active_status meta key)
    if ($status) {
        $meta_query[] = array(
            'key'   => '_event_active_status',
            'value' => $status,
        );
    }

    // Add meta query if not empty
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);
    $events = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $event_id = get_the_ID();

            $event_data = [
                'id'          => $event_id,
                'title'       => get_the_title(),
                'description' => apply_filters('the_content', get_the_content()),
                'permalink'   => get_permalink(),
                'timezone'    => get_post_meta($event_id, '_event_timezone', true),
                'start'       => get_post_meta($event_id, '_event_start', true),
                'end'         => get_post_meta($event_id, '_event_end', true),
                'start_date'  => get_post_meta($event_id, '_event_start_date', true),
                'end_date'    => get_post_meta($event_id, '_event_end_date', true),
                'start_time'  => get_post_meta($event_id, '_event_start_time', true),
                'end_time'    => get_post_meta($event_id, '_event_end_time', true),
                'status'      => get_post_meta($event_id, '_event_active_status', true),
            ];

            // Location
            $location_type = get_post_meta($event_id, '_event_location_type', true);
            if ($location_type === 'url') {
                $event_data['location'] = [
                    'type' => 'url',
                    'url'  => get_post_meta($event_id, '_event_location_url', true),
                    'text' => get_post_meta($event_id, '_event_location_url_text', true),
                ];
            } else {
                $event_data['location'] = [
                    'type'    => 'physical',
                    'name'    => get_post_meta($event_id, '_location_name', true),
                    'address' => get_post_meta($event_id, '_location_address', true),
                    'town'    => get_post_meta($event_id, '_location_town', true),
                ];
            }

            $events[] = $event_data;
        }
        wp_reset_postdata();
    }

    // Return with pagination info
    return [
        'events'       => $events,
        'total'        => (int) $query->found_posts,
        'total_pages'  => (int) $query->max_num_pages,
        'current_page' => $page,
        'per_page'     => $per_page,
    ];
}

