<?php
// THEME - TRIKONA EDTECH PARENT THEME 
// ==========================================
// THEME SETUP: Title, Thumbnails, Logo
// ==========================================
function trikona_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
}

   
// ==========================================
// LOAD MENUS, DEFINITIONS - CLASS,ASSETS,  
// ==========================================
require_once get_template_directory() . '/menu.php';
require_once get_template_directory() . '/custom-def/class-trikona.php';
require_once get_template_directory() . '/custom-def/status-messages.php';
require_once get_template_directory() . '/custom-def/assets-trikona.php';
require_once get_template_directory() . '/custom-def/class-jobs.php';
require_once get_template_directory() . '/custom-def/image-optimize.php';//added for optimising the images
require_once get_template_directory() . '/custom-ajax/common_ajax.php'; //Common ajax file for all sites


// Create instance of status messages
global $trikona_obj, $status_messages, $jobs_obj;
$status_messages    = new TrikonaStatusMessages();
$trikona_obj        = new Trikona();
$jobs_obj           = new TrikonaJobs();

// ==========================================
// ENQUEUE STYLES & SCRIPTS
// ==========================================
function trikona_scripts() {
    wp_enqueue_style('trikona-style', get_stylesheet_uri());
    wp_enqueue_style('trikona-edtech-style', get_template_directory_uri() . '/assets/css/trikona-edtech.css');
    wp_enqueue_script('trikona-custom-script', get_template_directory_uri() . '/assets/js/custom-script.js');
    wp_enqueue_script('trikona-main-script', get_template_directory_uri() . '/assets/js/trikona-main.js');
  
}
add_action('wp_enqueue_scripts', 'trikona_scripts');

function trikona_extscripts(){
 // Raleway Google Fonts
    wp_enqueue_style(
        'google-fonts-raleway',
        'https://fonts.googleapis.com/css?family=Raleway:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=auto',
        [],
        '6.4.2'
    );

    // Font Awesome
    wp_enqueue_style(
        'font-awesome-new',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css',
        [],
        '6.5.1'
    );

    wp_enqueue_script(
        'bootstrap-js',
        '//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js',
        [],
        '4.1.1',
        true
    );

}
add_action('wp_enqueue_scripts', 'trikona_extscripts');

// ==========================================
// REGISTER WIDGET AREAS
// ==========================================
function trikona_widgets() {
    register_sidebar([
        'name'          => 'Main Sidebar',
        'id'            => 'main-sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'trikona_widgets');