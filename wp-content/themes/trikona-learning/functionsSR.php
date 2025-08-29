<?php
/**
 * Trikona — LifterLMS minimal helpers + Course API (uses LLMS↔Woo Bridge mappings)
 * Put this in your child theme’s functions.php or a small mu-plugin.
 */

/** ---------------- Assets ---------------- */
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'trikona-learning-style',
    get_stylesheet_directory_uri() . '/style.css',
    [], // add parent handle here only if you actually enqueue parent css
    wp_get_theme()->get('Version')
  );
});

/** ---------------- Theme supports ---------------- */
add_action('after_setup_theme', function () {
  add_theme_support('block-templates');
});

/** ---------------- LifterLMS dashboard: hide some tabs ---------------- */
add_filter('llms_get_student_dashboard_tabs', function ($tabs) {
  unset($tabs['edit-account']);
  unset($tabs['redeem-voucher']);
  unset($tabs['view-memberships']);
  unset($tabs['orders']);
  return $tabs;
}, 99);

/** ---------------- Helpers (multisite + Woo currency) ---------------- */
function trikona_get_main_site_id() {
  return is_multisite() ? get_main_site_id() : get_current_blog_id();
}

function trikona_get_woo_currency_symbol_from_main() {
  $symbol = '';
  $main = trikona_get_main_site_id();

  if ( function_exists('switch_to_blog') ) {
    switch_to_blog($main);
    if ( function_exists('get_woocommerce_currency_symbol') ) {
      $symbol = get_woocommerce_currency_symbol();
    }
    restore_current_blog();
  }

  if ( ! $symbol ) {
    // Fallback to store currency code if Woo isn’t reachable
    $code = get_option('woocommerce_currency', 'USD');
    $map  = ['USD'=>'$','EUR'=>'€','GBP'=>'£','INR'=>'₹','AUD'=>'A$','CAD'=>'C$','AED'=>'د.إ'];
    $symbol = $map[$code] ?? $code;
  }
  return $symbol;
}

/**
 * Get cheapest display plan for a course using the LLMS↔Woo Bridge mapping.
 * Bridge stores product id on the access plan as: _llms_wc_linked_product_id
 * Returns: ['plan_id'=>int,'product_id'=>int|null,'price'=>float|null]
 */
function trikona_get_course_display_plan($course_id) {
  $plans = get_children([
    'post_parent' => (int) $course_id,
    'post_type'   => 'llms_access_plan',
    'post_status' => 'publish',
    'numberposts' => -1,
  ]);
  if ( ! $plans ) return ['plan_id'=>null,'product_id'=>null,'price'=>null];

  $main = trikona_get_main_site_id();

  $best = ['plan_id'=>null,'product_id'=>null,'price'=>null];
  foreach ( $plans as $plan_post ) {
    $plan_id    = (int) $plan_post->ID;
    $product_id = (int) get_post_meta($plan_id, '_llms_wc_linked_product_id', true);

    // Prefer Woo price (authoritative for checkout), else fall back to LLMS plan price
    $price = null;
    if ( $product_id && function_exists('switch_to_blog') ) {
      switch_to_blog($main);
      if ( function_exists('wc_get_product') ) {
        $p = wc_get_product($product_id);
        if ( $p ) $price = (float) $p->get_price();
      }
      restore_current_blog();
    }
    if ( $price === null && class_exists('LLMS_Access_Plan') ) {
      $plan  = new LLMS_Access_Plan($plan_id);
      $price = (float) $plan->get_price();
    }

    if ( $best['price'] === null || $price < $best['price'] ) {
      $best = ['plan_id'=>$plan_id, 'product_id'=>$product_id ?: null, 'price'=>$price];
    }
  }
  return $best;
}

/** ---------------- REST API: /custom-llms/v1/courses ---------------- */
add_action('rest_api_init', function () {
  register_rest_route('custom-llms/v1', '/courses', [
    'methods'  => 'GET',
    'callback' => 'trikona_api_get_courses',
    'permission_callback' => '__return_true',
    'args' => [
      'catid'        => ['type'=>'string','required'=>false],  // comma-separated term_ids
      'level'        => ['type'=>'string','required'=>false],  // comma-separated term_ids
      'instructor'   => ['type'=>'string','required'=>false],  // comma-separated user_ids
      'duration_min' => ['type'=>'number','required'=>false],
      'duration_max' => ['type'=>'number','required'=>false],
      'price_min'    => ['type'=>'number','required'=>false],
      'price_max'    => ['type'=>'number','required'=>false],
      'price'        => ['type'=>'string','required'=>false],  // 'S' (free) and/or 'H' (paid), comma-separated
    ],
  ]);
});

/**
 * Build course list, selecting the cheapest access plan via the bridge.
 * Supports taxonomy filters (course_cat, course_difficulty), instructor, duration, and price filters.
 */
function trikona_api_get_courses(WP_REST_Request $req) {
  $args = [
    'post_type'      => 'course',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ];

  // Taxonomies
  $tax_query = [];
  if ( $req['catid'] ) {
    $tax_query[] = [
      'taxonomy' => 'course_cat',
      'field'    => 'term_id',
      'terms'    => array_map('intval', explode(',', $req['catid'])),
    ];
  }
  if ( $req['level'] ) {
    $tax_query[] = [
      'taxonomy' => 'course_difficulty',
      'field'    => 'term_id',
      'terms'    => array_map('intval', explode(',', $req['level'])),
    ];
  }
  if ( $tax_query ) $args['tax_query'] = $tax_query;

  // Instructor
  if ( $req['instructor'] ) {
    $args['author__in'] = array_map('intval', explode(',', $req['instructor']));
  }

  // Duration (stored as numeric meta '_course_duration')
  $meta_query = [];
  $hasDurMin = ($req['duration_min'] !== null && $req['duration_min'] !== '');
  $hasDurMax = ($req['duration_max'] !== null && $req['duration_max'] !== '');
  if ( $hasDurMin || $hasDurMax ) {
    $min = $hasDurMin ? (int) $req['duration_min'] : 0;
    $max = $hasDurMax ? (int) $req['duration_max'] : PHP_INT_MAX;
    $meta_query[] = [
      'key'     => '_course_duration',
      'value'   => [$min, $max],
      'compare' => 'BETWEEN',
      'type'    => 'NUMERIC',
    ];
  }
  if ( $meta_query ) $args['meta_query'] = $meta_query;

  $ids = (new WP_Query($args))->posts;

  // Price filters (work on chosen cheapest plan price)
  $hasPriceMin = ($req['price_min'] !== null && $req['price_min'] !== '');
  $hasPriceMax = ($req['price_max'] !== null && $req['price_max'] !== '');
  $minPrice    = $hasPriceMin ? (float) $req['price_min'] : 0;
  $maxPrice    = $hasPriceMax ? (float) $req['price_max'] : INF;
  $priceFlags  = $req['price'] ? array_map('trim', explode(',', $req['price'])) : []; // S (free) / H (paid)

  $symbol = trikona_get_woo_currency_symbol_from_main();
  $results = [];

  foreach ( $ids as $course_id ) {
    $picked = trikona_get_course_display_plan($course_id);
    $price  = $picked['price']; // may be null if no plans

    // Apply price filters if provided
    $keep = true;
    if ( $hasPriceMin || $hasPriceMax || $priceFlags ) {
      $numeric = ($price !== null) ? (float) $price : 0.0;
      $inRange = ($numeric >= $minPrice && $numeric <= $maxPrice);

      if ( $priceFlags ) {
        $isPaid = ($numeric > 0);
        $allowed = ( in_array('H', $priceFlags, true) && $isPaid ) || ( in_array('S', $priceFlags, true) && ! $isPaid );
        $keep = $allowed && $inRange;
      } else {
        $keep = $inRange;
      }
    }
    if ( ! $keep ) continue;

    $results[] = [
      'id'             => $course_id,
      'title'          => get_the_title($course_id),
      'description'    => wp_strip_all_tags(get_post_field('post_content', $course_id)),
      'link'           => get_permalink($course_id),
      'featured_image' => get_the_post_thumbnail_url($course_id, 'medium'),
      'plan'           => [
        'plan_id'    => $picked['plan_id'],
        'product_id' => $picked['product_id'],
        'price_raw'  => $price,
        'price'      => ($price !== null) ? $symbol . $price : null,
      ],
    ];
  }

  return rest_ensure_response($results);
}
