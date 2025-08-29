<?php
function enqueue_custom_subsite_assets() {
    $blog_id = get_current_blog_id();

    $subsites = [
        1 => 'main',
        2 => 'groups',
        3 => 'candidates',
        4 => 'lms',
        5 => 'careers',
        6 => 'events',
        7 => 'resources',
        8 => 'help',
    ];

    if ( isset( $subsites[ $blog_id ] ) ) {
        $slug = $subsites[ $blog_id ];
        $base_uri = get_template_directory_uri() . '/assets/subsites/';
        $base_path = get_template_directory() . '/assets/subsites/';

        // CSS
        if ( file_exists( $base_path . $slug . '.css' ) ) {
            wp_enqueue_style( $slug . '-style', $base_uri . $slug . '.css', [], null );
        }

        // JS
        if ( file_exists( $base_path . $slug . '.js' ) ) {
            wp_enqueue_script( $slug . '-script', $base_uri . $slug . '.js', [], null, true );
        }
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_subsite_assets');
