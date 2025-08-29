<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function trikona_candidates_child_scripts_styles() {
    wp_enqueue_style( 'student-dashboard-style', get_stylesheet_directory_uri() . '/assets/css/style-candidate-directory.css', [], '1.0.0' );

    // Enqueue Select2 CSS
    wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', [], '4.1.0' );

    // Enqueue Select2 JS
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', [], '4.1.0', true );

}
add_action( 'wp_enqueue_scripts', 'trikona_candidates_child_scripts_styles', 20 );

//include_once( get_stylesheet_directory() .'/shortcodes/class_shortcodes.php');

function custom_redirects() {

	if ( !is_user_logged_in() ) {
		wp_redirect('https://construction-world.org/user-login/');
		die;
	}
}
add_action( 'template_redirect', 'custom_redirects' );