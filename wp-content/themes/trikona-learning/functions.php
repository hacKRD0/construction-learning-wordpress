<?php
function trikona_learning_enqueue_styles() {

   // Load child theme stylesheet
    wp_enqueue_style(
        'trikona-learning-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('trikona-main-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'trikona_learning_enqueue_styles');


function enable_gutenberg_for_lifterlms_courses( $can_edit, $post_type ) {
    if ( $post_type === 'course' || $post_type === 'lesson' ) {
        return true;
    }
    return $can_edit;
}

function enable_gutenberg_for_custom_post_types( $use_block_editor, $post_type ) {
    $allowed_post_types = array( 'course', 'post', 'page' );

    if ( in_array( $post_type, $allowed_post_types ) ) {
        return true;
    }

    return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'enable_gutenberg_for_custom_post_types', 999, 2 );

function trikona_learning_theme_setup() {
    add_theme_support( 'block-templates' );
}
add_action( 'after_setup_theme', 'trikona_learning_theme_setup' );


function my_remove_dashboard_tabs( $tabs ) {
	// unset( $tabs['view-courses'] );
	// unset( $tabs['view-achievements'] );
	// unset( $tabs['notifications'] );
	unset( $tabs['edit-account'] );
	unset( $tabs['redeem-voucher'] );
    unset( $tabs['view-memberships'] );
	unset( $tabs['orders'] );
	// unset( $tabs['signout'] );
	return $tabs;
}
add_filter( 'llms_get_student_dashboard_tabs', 'my_remove_dashboard_tabs', 99 );


add_shortcode('enroll_button', 'learning_enroll_button_shortcode');

function learning_enroll_button_shortcode() {

    // Get linked product ID from post meta
    $product_id = get_post_meta(get_the_ID(), '_linked_product_id', true);

    if (!$product_id) return ''; // Nothing to show

    // Switch to main site to get product info
    switch_to_blog(1);
    $product = wc_get_product($product_id);

    if (!$product) {
        restore_current_blog();
        return '';
    }

    $price = $product->get_price();
    $currency = get_woocommerce_currency_symbol();
    // Get main site URL to build add-to-cart link
    $main_site_url = get_site_url(1);
    $add_to_cart_link = $main_site_url . '/cart/?add-to-cart=' . $product_id;
    restore_current_blog();

    ob_start();
    ?>
    <section class="llms-access-plans cols-1">
        <div class="llms-access-plan llms-access-plan-<?php echo esc_attr($product_id); ?>">
            <div class="llms-access-plan-featured">&nbsp;</div>
            <div class="llms-access-plan-content">
                <h4 class="llms-access-plan-title">One Time</h4>
                <div class="llms-access-plan-pricing regular">
                    <div class="llms-access-plan-price">
                        <span class="price-regular">
                            <span class="lifterlms-price">
                                <span class="llms-price-currency-symbol"><?php echo esc_html($currency); ?></span>
                                <?php echo esc_html($price); ?>
                            </span>
                        </span>
                    </div>
                    <div class="llms-access-plan-expiration">1 year of access</div>
                </div>
            </div>
            <div class="llms-access-plan-footer">
                <a class="llms-button-action button" href="<?php echo esc_url($add_to_cart_link); ?>">
                    Enroll Now
                </a>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_shortcode('course_price', 'learning_course_price_shortcode');

function learning_course_price_shortcode() {
    // Get linked product ID
    $product_id = get_post_meta(get_the_ID(), '_linked_product_id', true);
    if (!$product_id) return '';

    // Switch to main site
    switch_to_blog(1);
    $product = wc_get_product($product_id);
    if (!$product) {
        restore_current_blog();
        return '';
    }

    $price = $product->get_price();
    $currency = get_woocommerce_currency_symbol();
    restore_current_blog();

    // Return formatted price
    return '<span class="course-price">' . esc_html($currency . $price) . '</span>';
}

add_shortcode('course_enroll_button', 'learning_course_enroll_button_shortcode');

function learning_course_enroll_button_shortcode() {

    // Get linked WooCommerce product ID from course meta
    $product_id = get_post_meta(get_the_ID(), '_linked_product_id', true);
    if (!$product_id) return '';

    // Switch to main site to get product info
    switch_to_blog(1);
    $product = wc_get_product($product_id);

    if (!$product) {
        restore_current_blog();
        return '';
    }

    // Build Add to Cart link for main site
    $main_site_url   = get_site_url(1);
    $add_to_cart_url = $main_site_url . '/cart/?add-to-cart=' . $product_id;

    restore_current_blog();

    // Return styled enroll button
    return '<a class="llms-button-action button enroll-button" href="' . esc_url($add_to_cart_url) . '">'
         . esc_html__('Enroll Now', 'textdomain')
         . '</a>';
}

// Add Short Description Meta Box
function my_llms_add_short_description_meta_box() {
    add_meta_box(
        'llms_course_short_description', // ID
        'Course Short Description', // Title
        'my_llms_short_description_callback', // Callback
        'course', // Post type (LifterLMS courses)
        'normal', // Context
        'high' // Priority
    );
}
add_action( 'add_meta_boxes', 'my_llms_add_short_description_meta_box' );

// Field display
function my_llms_short_description_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'llms_short_desc_nonce' );
    $stored_value = get_post_meta( $post->ID, '_llms_course_short_description', true );
    echo '<textarea style="width:100%;height:80px;" name="llms_course_short_description">' . esc_textarea( $stored_value ) . '</textarea>';
}

function my_llms_save_short_description_meta( $post_id ) {
    // Verify nonce
    if ( ! isset( $_POST['llms_short_desc_nonce'] ) || ! wp_verify_nonce( $_POST['llms_short_desc_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }

    // Check for autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check user permissions
    if ( 'course' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    // Save the value
    if ( isset( $_POST['llms_course_short_description'] ) ) {
        update_post_meta( $post_id, '_llms_course_short_description', sanitize_text_field( $_POST['llms_course_short_description'] ) );
    }
}
add_action( 'save_post', 'my_llms_save_short_description_meta' );


add_action('woocommerce_order_status_completed', 'cw_enroll_user_in_lms_courses', 10, 1);

add_filter( 'llms_get_student_dashboard_tabs', 'my_remove_dashboard_sections' );
function my_remove_dashboard_sections( $sections ) {
	unset( $sections['memberships'] ); // Removes the content block
	return $sections;
}


add_action( 'rest_api_init', function () {
    register_rest_route( 'custom-lifterlms/v1', '/enroll/', [
        'methods'  => 'POST',
        'callback' => 'custom_lifterlms_enroll_user',
        'permission_callback' => '__return_true',
    ] );
});

function custom_lifterlms_enroll_user( $request ) {
    $user_id   = intval( $request['user_id'] );
    $course_id = intval( $request['course_id'] );

    if ( get_user_by( 'id', $user_id ) && class_exists( 'LLMS_Student' ) ) {
        $student = new LLMS_Student( $user_id );
        if ( ! $student->is_enrolled( $course_id ) ) {
            $student->enroll( $course_id, 'manual' );
            return rest_ensure_response([ 'status' => 'enrolled' ]);
        }
        return rest_ensure_response([ 'status' => 'already_enrolled' ]);
    }

    return new WP_Error( 'invalid_user_or_class', 'User not found or LifterLMS not loaded', [ 'status' => 400 ] );
}

add_action('rest_api_init', function () {
    register_rest_route('custom-llms/v1', '/courses', array(
        'methods' => 'GET',
        'callback' => 'get_filtered_llms_courses',
        'permission_callback' => '__return_true'
    ));
});

//Course API's function

function get_filtered_llms_courses($request) {
    $args = array(
        'post_type'      => 'course',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    );

    // Taxonomy filters
    $tax_query = [];

    if (!empty($request['catid'])) {
        $tax_query[] = array(
            'taxonomy' => 'course_cat',
            'field'    => 'term_id',
            'terms'    => explode(',', $request['catid']),
        );
    }

    if (!empty($request['level'])) {
        $tax_query[] = array(
            'taxonomy' => 'course_difficulty',
            'field'    => 'term_id',
            'terms'    => explode(',', $request['level']),
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    if (!empty($request['instructor'])) {
        $args['author__in'] = explode(',', $request['instructor']);
    }

    // Duration range
    $meta_query = [];

    if (!empty($request['duration_min']) || !empty($request['duration_max'])) {
        $meta_query[] = array(
            'key'     => '_course_duration',
            'value'   => array((int)$request['duration_min'], (int)$request['duration_max']),
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC'
        );
    }

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    // Initial query to get course IDs
    $initial_query = new WP_Query($args);
    $course_ids = $initial_query->posts;

    $has_min = isset($request['price_min']) && $request['price_min'] !== '';
    $has_max = isset($request['price_max']) && $request['price_max'] !== '';

    $min_price = $has_min ? floatval($request['price_min']) : 0;
    $max_price = $has_max ? floatval($request['price_max']) : PHP_INT_MAX;

    $price_filter = !empty($request['price']) ? explode(',', $request['price']) : [];
    $apply_price_filter = $has_min || $has_max || !empty($price_filter);

    if ($apply_price_filter) {
        $filtered_course_ids = [];

        foreach ($course_ids as $course_id) {
            $product_id = get_post_meta($course_id, '_linked_product_id', true);
            $is_valid = false;

            if ($product_id) {
                switch_to_blog(1);
                $product = wc_get_product($product_id);
                $price = $product ? floatval($product->get_price()) : null;
                restore_current_blog();

                if ($price !== null) {
                    $in_range = $price >= $min_price && $price <= $max_price;

                    if (!empty($price_filter)) {
                        if (in_array('H', $price_filter) && $price > 0 && $in_range) {
                            $is_valid = true;
                        }
                        if (in_array('S', $price_filter) && $price == 0 && $in_range) {
                            $is_valid = true;
                        }
                    } elseif ($in_range) {
                        $is_valid = true;
                    }
                }
            } else {
                // Free course with no linked product
                if (in_array('S', $price_filter) && $min_price <= 0 && 0 <= $max_price) {
                    $is_valid = true;
                } elseif (empty($price_filter) && $min_price <= 0 && 0 <= $max_price) {
                    $is_valid = true;
                }
            }

            if ($is_valid) {
                $filtered_course_ids[] = $course_id;
            }
        }

        $course_ids = $filtered_course_ids;
    }


    // Final query to fetch full course data
    $final_args = array(
        'post_type'      => 'course',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__in'       => !empty($course_ids) ? $course_ids : [0],
        'orderby'        => 'post__in',
    );

    $query = new WP_Query($final_args);

    $results = [];
    $currency = get_woocommerce_currency_symbol();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $course_id = get_the_ID();
            $linked_product_id = get_post_meta($course_id, '_linked_product_id', true);
            $wc_price = null;

            if ($linked_product_id) {
                switch_to_blog(1);
                $product = wc_get_product($linked_product_id);
                if ($product) {
                    $wc_price = $product->get_price();
                }
                restore_current_blog();
            }

            $results[] = array(
                'id'              => $course_id,
                'title'           => get_the_title(),
                'description'     => wp_strip_all_tags(get_the_content()),
                'link'            => get_permalink(),
                'featured_image'  => get_the_post_thumbnail_url($course_id, 'medium'),
                'wc_price'        => $wc_price !== null ? $currency . $wc_price : null,
            );
        }
        wp_reset_postdata();
    }

    return rest_ensure_response($results);
}


/**
 *
 *  API For Course Category
 *
 */

 add_action('rest_api_init', function () {
    register_rest_route('custom-llms/v1', '/course-categories', array(
        'methods'             => 'GET',
        'callback'            => 'get_llms_course_categories',
        'permission_callback' => '__return_true',
    ));
});

function get_llms_course_categories($request) {
    $args = array(
        'taxonomy'   => 'course_cat',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
    );

    $terms = get_terms($args);

    if (is_wp_error($terms)) {
        return new WP_Error('term_fetch_failed', 'Failed to fetch course categories', array('status' => 500));
    }

    $categories = array();

    foreach ($terms as $term) {
        $categories[] = array(
            'id'          => $term->term_id,
            'name'        => $term->name,
            'slug'        => $term->slug,
            'description' => $term->description,
            'count'       => $term->count,
            'parent'      => $term->parent,
        );
    }

    return rest_ensure_response($categories);
}

/**
 *
 *  Couse Difficulty API
 *
 */

 add_action('rest_api_init', function () {
    register_rest_route('custom-llms/v1', '/course-difficulties', array(
        'methods'             => 'GET',
        'callback'            => 'get_llms_course_difficulties',
        'permission_callback' => '__return_true',
    ));
});

function get_llms_course_difficulties($request) {
    $args = array(
        'taxonomy'   => 'course_difficulty',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
    );

    $terms = get_terms($args);

    if (is_wp_error($terms)) {
        return new WP_Error('term_fetch_failed', 'Failed to fetch course difficulty terms', array('status' => 500));
    }

    $difficulties = array();

    foreach ($terms as $term) {
        $difficulties[] = array(
            'id'          => $term->term_id,
            'name'        => $term->name,
            'slug'        => $term->slug,
            'description' => $term->description,
            'count'       => $term->count,
            'parent'      => $term->parent,
        );
    }

    return rest_ensure_response($difficulties);
}

add_action('rest_api_init', function () {
    register_rest_route('custom-llms/v1', '/instructors', array(
        'methods'             => 'GET',
        'callback'            => 'get_llms_assigned_instructors',
        'permission_callback' => '__return_true',
    ));
});

function get_llms_assigned_instructors($request) {
    global $wpdb;

    // Step 1: Find all posts with _llms_instructors meta key
    $results = $wpdb->get_results("
        SELECT DISTINCT meta_value
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_llms_instructors'
    ");

    if (empty($results)) {
        return rest_ensure_response([]);
    }

    $instructor_ids = [];

    // Step 2: Extract instructor IDs from serialized arrays
    foreach ($results as $row) {
        $ids = maybe_unserialize($row->meta_value);
        if (is_array($ids)) {
            foreach ($ids as $id) {
                if (!in_array($id, $instructor_ids)) {
                    $instructor_ids[] = $id;
                }
            }
        }
    }

    if (empty($instructor_ids)) {
        return rest_ensure_response([]);
    }

    // Step 3: Get user data
    $users = get_users(array(
        'include' => $instructor_ids,
        'orderby' => 'display_name',
        'order'   => 'ASC',
    ));

    $instructors = [];

    foreach ($users as $user) {
        $first_name    = get_user_meta($user->ID, 'first_name', true);
        $display_name  = !empty($first_name) ? $first_name : $user->display_name;

        $instructors[] = array(
            'id'       => $user->ID,
            'name'     => $display_name,
            'email'    => $user->user_email,
            'username' => $user->user_login,
            'avatar'   => get_avatar_url($user->ID),
        );
    }

    return rest_ensure_response($instructors);
}
// Add meta box to select course template
add_action('add_meta_boxes', function () {
    add_meta_box(
        'course_template_selector',
        __('Course Template', 'textdomain'),
        'render_course_template_meta_box',
        'course', // LifterLMS course CPT slug
        'side',
        'default'
    );
});

// Render meta box HTML
function render_course_template_meta_box($post) {
    $selected_template = get_post_meta($post->ID, '_course_template', true);
    wp_nonce_field('save_course_template_meta_box', 'course_template_meta_box_nonce');
    ?>
    <p>
        <label for="course_template"><?php _e('Select Template:', 'textdomain'); ?></label>
        <select name="course_template" id="course_template" style="width:100%;">
            <option value="style1" <?php echo ($selected_template === 'style1' || empty($selected_template)) ? 'selected' : ''; ?>>
                <?php _e('Default Style', 'textdomain'); ?>
            </option>
            <option value="style2" <?php selected($selected_template, 'style2'); ?>>
                <?php _e('Style 2', 'textdomain'); ?>
            </option>
            <!-- <option value="style3" <?php selected($selected_template, 'style3'); ?>>
                <?php _e('Style 3', 'textdomain'); ?>
            </option> -->
        </select>
    </p>
    <?php
}

// Save template choice
add_action('save_post', function ($post_id) {
    if (!isset($_POST['course_template_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['course_template_meta_box_nonce'], 'save_course_template_meta_box') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['course_template'])) {
        update_post_meta($post_id, '_course_template', sanitize_text_field($_POST['course_template']));
    }
});



// Add meta box
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'lesson_style_selector',        // ID
        'Lesson Style',                 // Title
        'render_lesson_style_selector', // Callback
        'lesson',                       // Post type
        'side',                         // Context
        'default'                       // Priority
    );
});

// Render dropdown
function render_lesson_style_selector( $post ) {
    $value = get_post_meta( $post->ID, '_lesson_style', true );
    wp_nonce_field( 'save_lesson_style', 'lesson_style_nonce' );
    ?>
    <label for="lesson_style">Select Lesson Style:</label>
    <select name="lesson_style" id="lesson_style" style="width:100%;">
        <option value="">Default</option>
        <option value="style1" <?php selected( $value, 'style1' ); ?>>Style 1</option>
        <!-- <option value="style2" <?php selected( $value, 'style2' ); ?>>Style 2</option> -->
        <!-- <option value="style3" <?php selected( $value, 'style3' ); ?>>Style 3</option> -->
    </select>
    <?php
}

// Save selection
add_action( 'save_post_lesson', function( $post_id ) {
    if ( ! isset( $_POST['lesson_style_nonce'] ) ||
         ! wp_verify_nonce( $_POST['lesson_style_nonce'], 'save_lesson_style' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( isset( $_POST['lesson_style'] ) ) {
        update_post_meta( $post_id, '_lesson_style', sanitize_text_field( $_POST['lesson_style'] ) );
    }
});

add_filter( 'template_include', function( $template ) {
    if ( is_singular( 'lesson' ) ) {
        $style = get_post_meta( get_the_ID(), '_lesson_style', true );
        if ( $style && locate_template( "templates/lesson-templates/single-lesson-{$style}.php" ) ) {
            return locate_template( "templates/lesson-templates/single-lesson-{$style}.php" );
        }
    }
    return $template;
});
