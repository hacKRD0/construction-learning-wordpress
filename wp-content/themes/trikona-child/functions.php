<?php
function trikona_main_child_enqueue_styles() {
    // Load parent theme stylesheet
    wp_enqueue_style(
        'trikona-main-style',
        get_template_directory_uri() . '/style.css'
    );

    // Load child theme stylesheet
    wp_enqueue_style(
        'trikona-main-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('trikona-main-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'trikona_main_child_enqueue_styles');

add_action('template_redirect', function() {
    if ( is_front_page() ) {
        wp_redirect(home_url('/docs/'), 301);
        exit;
    }
});

function modify_existing_event_cpt_for_rest() {
    global $wp_post_types;

    if (isset($wp_post_types['event'])) {
        $wp_post_types['event']->show_in_rest = true;
    }
}
add_action('init', 'modify_existing_event_cpt_for_rest', 20);