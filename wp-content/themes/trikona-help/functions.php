<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	// Raleway Google Fonts
	wp_enqueue_style(
		'google-fonts-raleway',
		'https://fonts.googleapis.com/css?family=Raleway%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&display=auto',
		[],
		'6.4.2'
	);
 
	  // Trikona Main Css
	  wp_enqueue_style(
		'triokna-main-style',
		get_stylesheet_directory_uri() . '/../trikona-main/assets/css/trikona_main.css',
		[],
		'1.0.0'  // Change the version number accordingly
	);
 
	// Enqueue script
	wp_enqueue_script(
		'trikona-main-js',
		get_stylesheet_directory_uri() . '/../trikona-main/assets/js/trikona_main.js',
		// Dependencies if any
		[],
		// Version number or set to false to prevent versioning
		'1.0.0',
		// Whether to load the script in the footer. Set to true if you want to load it in the footer.
		true
	);
 
 }
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

//include_once( get_stylesheet_directory() .'/functions_jobs.php');
