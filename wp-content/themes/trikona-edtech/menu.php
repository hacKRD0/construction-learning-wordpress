<?php
/**
 * Menu registration and setup for Trikona theme
 */

// 1. Register menu locations
function trikona_register_menus() {
    register_nav_menus([
        'main-menu'               => __('Main Menu', 'trikona'),
        'mobile-menu'             => __('Mobile Menu', 'trikona'),
        'guest-menu'              => __('Guest Menu', 'trikona'),
        'guest-mobile'            => __('Mobile Guest Menu', 'trikona'),

        'student-main-menu'       => __('Student Main Menu', 'trikona'),
        'professional-main-menu'  => __('Professional Main Menu', 'trikona'),
        'instructor-main-menu'    => __('Instructor Main Menu', 'trikona'),
        'corporate-main-menu'     => __('Corporate Main Menu', 'trikona'),
        'college-main-menu'       => __('College Main Menu', 'trikona'),
        'subscriber-menu'         => __('Subscriber Main Menu', 'trikona'),

        'student-mobile'          => __('Mobile Student Menu', 'trikona'),
        'professional-mobile'     => __('Mobile Professional Menu', 'trikona'),
        'instructor-mobile'       => __('Mobile Instructor Menu', 'trikona'),
        'corporate-mobile'        => __('Mobile Corporate Menu', 'trikona'),
        'college-mobile'          => __('Mobile College Menu', 'trikona'),

        'user-control-menu'       => __('User Control Menu', 'trikona'),
        'group-manager-menu'      => __('Group Manager Menu', 'trikona'),
        'admin-developer-menu'    => __('Administrator Menu', 'trikona'),
    ]);
}
add_action('after_setup_theme', 'trikona_register_menus');

// 2. Create and assign default menus on theme activation
function trikona_create_default_menus() {
    $menus = [
        'student-main-menu'      => 'Student Main Menu',
        'professional-main-menu' => 'Professional Main Menu',
        'college-main-menu'      => 'College Main Menu',
        'corporate-main-menu'    => 'Corporate Main Menu',
        'instructor-main-menu'   => 'Instructor Main Menu',
        'subscriber-menu'        => 'Subscriber Main Menu',
        
        'student-mobile'         => 'Mobile Student Menu',
        'professional-mobile'    => 'Mobile Professional Menu',
        'corporate-mobile'       => 'Mobile Corporate Menu',
        'primary-mobile'         => 'Default Mobile Main Menu',
        'instructor-mobile'      => 'Mobile Instructor Menu',
        'college-mobile'         => 'Mobile College Menu',

        'user-control-menu'      => 'User Control Menu',
        'group-manager-menu'     => 'Group Manager Menu',
        'admin-developer-menu'   => 'Administrator Menu',
    ];

    $current_locations = get_theme_mod('nav_menu_locations');

    foreach ($menus as $location => $menu_name) {
        $menu_obj = wp_get_nav_menu_object($menu_name);
        if (!$menu_obj) {
            $menu_id = wp_create_nav_menu($menu_name);
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title'  => __('Home', 'trikona'),
                'menu-item-url'    => home_url('/'),
                'menu-item-status' => 'publish',
            ]);
        } else {
            $menu_id = $menu_obj->term_id;
        }

        $current_locations[$location] = $menu_id;
    }

    set_theme_mod('nav_menu_locations', $current_locations);
}
add_action('after_switch_theme', 'trikona_create_default_menus');

// Optional debug logger
function trikona_log_debug($message) {
    if (defined('TRIKONA_MENU_DEBUG') && TRIKONA_MENU_DEBUG) {
        error_log('[Trikona Menu] ' . $message);
    }
}

// 3. Role-based menu selector
function trikona_get_menu_location_by_role($type = 'desktop') {
    $valid_types = ['desktop', 'mobile'];
    $type = in_array($type, $valid_types) ? $type : 'desktop';

    $preview_role = isset($_GET['preview_role']) ? sanitize_key($_GET['preview_role']) : null;

    if (is_super_admin() || $preview_role === 'super_admin') {
        return ($type === 'mobile') ? 'admin-developer-menu' : 'admin-developer-menu';
    }

    if (!is_user_logged_in() || $preview_role === 'guest') {
        return ($type === 'mobile') ? 'guest-mobile' : 'guest-menu';
    }

    $user = wp_get_current_user();
    if (empty($user->roles)) {
        trikona_log_debug("User has no role assigned. Falling back to default menu.");
        return ($type === 'mobile') ? 'mobile-menu' : 'main-menu';
    }

    $role = $preview_role ?: ($user->roles[0] ?? '');

    $map = [
        'student'       => ['student-main-menu', 'student-mobile'],
        'professional'  => ['professional-main-menu', 'professional-mobile'],
        'corporate'     => ['corporate-main-menu', 'corporate-mobile'],
        'instructor'    => ['instructor-main-menu', 'instructor-mobile'],
        'college_admin' => ['college-main-menu', 'college-mobile'],
        'subscriber'    => ['subscriber-menu', 'mobile-menu'],
        'user_control'  => ['user-control-menu', 'mobile-menu'],
        'group_manager' => ['group-manager-menu', 'mobile-menu'],
        'administrator' => ['admin-developer-menu', 'mobile-menu'],
    ];

    if (!isset($map[$role])) {
        trikona_log_debug("Unrecognized user role '{$role}'. Using default menu.");
        return ($type === 'mobile') ? 'mobile-menu' : 'main-menu';
    }

    return ($type === 'mobile') ? $map[$role][1] : $map[$role][0];
}

// 4. Enqueue mobile toggle script
function trikona_enqueue_mobile_menu_toggle() {
    wp_enqueue_script(
        'trikona-mobile-menu-toggle',
        get_template_directory_uri() . '/assets/js/mobile-menu-toggle.js',
        [],
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'trikona_enqueue_mobile_menu_toggle');
