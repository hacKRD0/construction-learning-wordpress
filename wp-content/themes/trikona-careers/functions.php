<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


//Enqueue parent and child theme styles and scripts, including Elementor customizations.
function trikona_child_enqueue_assets() {

	// Load parent theme style
	wp_enqueue_style(
		'trikona-main-style',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( get_template() )->get( 'Version' )
	);

	// Load child theme style
	wp_enqueue_style(
		'trikona-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		['trikona-main-style'],
		wp_get_theme()->get('Version')
	);

	// Load Elementor Raleway Google Font
	wp_enqueue_style(
		'google-fonts-raleway',
		'https://fonts.googleapis.com/css?family=Raleway%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&display=auto',
		[],
		null
	);

	// If you want to include trikona_main.css from parent theme's assets folder
	wp_enqueue_style(
		'trikona-main-custom-style',
		get_template_directory_uri() . '/assets/css/trikona_main.css',
		[],
		'1.0.0'
	);

	// Include trikona_main.js from parent theme assets
	wp_enqueue_script(
		'trikona-main-js',
		get_template_directory_uri() . '/assets/js/trikona_main.js',
		[],
		'1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'trikona_child_enqueue_assets', 20 );

// Include any extra PHP functionality
include_once( get_stylesheet_directory() . '/functions_jobs.php' );