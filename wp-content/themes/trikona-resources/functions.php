<?php


/**
 * Add custom Meta Box for Blog Design
 */
function add_blog_design_meta_box() {
    add_meta_box(
        'blog_design_meta_box',        // ID
        'Select Blog Design',          // Title
        'render_blog_design_meta_box', // Callback
        'post',                        // Screen (post type)
        'side',                        // Context
        'default'                      // Priority
    );
}
add_action('add_meta_boxes', 'add_blog_design_meta_box');

/**
 * Render the Meta Box content
 */
function render_blog_design_meta_box($post) {
    $selected = get_post_meta($post->ID, '_blog_design', true);
    ?>
    <label for="blog_design_select">Choose Design:</label>
    <select name="blog_design_select" id="blog_design_select" style="width:100%;">
        <option value="design1" <?php selected($selected, 'design1'); ?>>Default Version</option>
        <option value="design2" <?php selected($selected, 'design2'); ?>>Version 1</option>
    </select>
    <?php
    // Nonce field for security
    wp_nonce_field('save_blog_design_meta_box', 'blog_design_meta_box_nonce');
}

/**
 * Save Meta Box Data
 */
function save_blog_design_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['blog_design_meta_box_nonce']) || !wp_verify_nonce($_POST['blog_design_meta_box_nonce'], 'save_blog_design_meta_box')) {
        return;
    }
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    // Save the selected value
    if (isset($_POST['blog_design_select'])) {
        update_post_meta($post_id, '_blog_design', sanitize_text_field($_POST['blog_design_select']));
    }
}
add_action('save_post', 'save_blog_design_meta_box');

function custom_thumbnail_setup() {
    add_image_size( 'custom-thumbnail', 365, 245, true );
}
add_action( 'after_setup_theme', 'custom_thumbnail_setup' );

// Extend the default REST API output for posts
function extend_default_post_rest_api($response, $post, $request) {
    if ($post->post_type !== 'post') return $response;

    // Add thumbnail
    $response->data['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'custom-thumbnail');

    // Add categories
    $categories = wp_get_post_terms($post->ID, 'category', array('fields' => 'names'));
    $response->data['categories_names'] = $categories;

    // Add tags
    $tags = wp_get_post_terms($post->ID, 'post_tag', array('fields' => 'names'));
    $response->data['tags_names'] = $tags;

    // Add custom meta field: blog_design
    $response->data['blog_design'] = get_post_meta($post->ID, '_blog_design', true);

    return $response;
}
add_filter('rest_prepare_post', 'extend_default_post_rest_api', 10, 3);
