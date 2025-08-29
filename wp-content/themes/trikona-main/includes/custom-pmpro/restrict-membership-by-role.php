<?php
/**
 * Filters PMPro membership levels based on the current user's role.
 *
 * @param array $levels All available membership levels.
 * @return array $filtered_levels Levels filtered by the user's role.
 */
function pmpro_levels_by_logged_in_user_role($levels) {
    // Get the current logged-in user object
    $user = wp_get_current_user();

    // Get an array of roles assigned to the user
    $roles = $user->roles;

    // If user is a super admin (multisite), treat as administrator
    if (is_super_admin($user->ID)) {
        $roles = ['administrator'];
    }

    // Define which membership level IDs are allowed for each role
    $role_levels = [
        'student'       => [1, 2, 3],
        'professional'  => [4, 5, 6],
        'corporate'     => [7, 8, 9],
        'college-admin' => [10, 11, 12],
        'instructor'    => [13, 14, 15],
        'administrator' => range(1, 15), // Admin can access all levels
    ];

    // Initialize array to collect allowed level IDs for the user
    $allowed_ids = [];

    // Loop through each user role and collect matching allowed level IDs
    foreach ($roles as $role) {
        if (isset($role_levels[$role])) {
            $allowed_ids = array_merge($allowed_ids, $role_levels[$role]);
        }
    }

    // Remove duplicates
    $allowed_ids = array_unique($allowed_ids);

    // Filter levels based on allowed IDs
    $filtered_levels = [];
    foreach ($levels as $level) {
        if (in_array($level->id, $allowed_ids, true)) {
            $filtered_levels[] = $level;
        }
    }

    return $filtered_levels;
}

// Hook into PMPro to filter levels before display
add_filter('pmpro_levels_array', 'pmpro_levels_by_logged_in_user_role');
?>