<?php
// File: includes/pmpro-rest-api.php

add_action('rest_api_init', function () {
    register_rest_route('pmpro/v1', '/memberships', [
        'methods'  => 'GET',
        'callback' => 'pmpro_get_membership_groups_with_levels_custom',
        'permission_callback' => '__return_true', // ✅ Public access
    ]);
});

/**
 * Get membership groups with levels in order
 */
function pmpro_get_membership_groups_with_levels_custom($request) {
    global $wpdb;

    // Ensure PMPro functions are available
    if (!function_exists('pmpro_get_level_groups_in_order') || !function_exists('pmpro_get_level_ids_for_group')) {
        return new WP_Error(
            'pmpro_groups_missing',
            'PMPro Groups functions are not available.',
            ['status' => 500]
        );
    }

    // Search param
    $s = $request->get_param('s');
    $s = $s ? sanitize_text_field($s) : '';

    // Base query
    $sql = "SELECT * FROM $wpdb->pmpro_membership_levels";
    if ($s) {
        $sql .= $wpdb->prepare(" WHERE name LIKE %s", '%' . $wpdb->esc_like($s) . '%');
    }
    $sql .= " ORDER BY id ASC";

    $levels = $wpdb->get_results($sql, OBJECT);
    $level_groups = pmpro_get_level_groups_in_order();

    // Handle custom ordering
    $pmpro_level_order = pmpro_getOption('level_order');
    if (empty($s) && !empty($pmpro_level_order)) {
        $order = array_map('intval', array_filter(explode(',', $pmpro_level_order)));
        $level_ids = wp_list_pluck($levels, 'id');

        // Append missing levels
        foreach ($level_ids as $id) {
            if (!in_array($id, $order)) {
                $order[] = $id;
            }
        }

        $order = array_unique($order);
        pmpro_setOption('level_order', implode(',', $order));

        // Reorder levels
        $reordered_levels = [];
        foreach ($order as $id) {
            foreach ($levels as $level) {
                if ($level->id == $id) {
                    $reordered_levels[] = $level;
                }
            }
        }
    } else {
        $reordered_levels = $levels;
    }

    // Build response
    $response = [];
    foreach ($level_groups as $level_group) {
        // ✅ Skip group if name is Instructor (case-insensitive)
        if (strtolower(trim($level_group->name)) === 'instructor') {
            continue;
        }

        $group_level_ids = pmpro_get_level_ids_for_group($level_group->id);
        $group_levels_to_show = [];

        foreach ($reordered_levels as $level) {
            if (in_array($level->id, $group_level_ids)) {
                $group_levels_to_show[] = [
                    'level_id'        => (string) $level->id,
                    'level_name'      => $level->name,
                    'initial_payment' => (float) $level->initial_payment,
                    'billing_amount'  => (float) $level->billing_amount,
                    'cycle_number'    => (string) $level->cycle_number,
                    'cycle_period'    => $level->cycle_period,
                    'buy_url'         => pmpro_url("checkout", "?level=" . $level->id),
                    'description'     => wp_kses_post($level->description),
                ];
            }
        }

        if (!empty($group_levels_to_show)) {
            $response[] = [
                'group_id'   => (string) $level_group->id,
                'group_name' => $level_group->name,
                'packages'   => $group_levels_to_show,
            ];
        }
    }

    return rest_ensure_response($response);
}