<?php

/**
 * Template Name: Courses Directory
 */
get_header();
global $wpdb, $bp;
$current_user = wp_get_current_user();
?>

<link href='<?php echo get_stylesheet_directory_uri() ?>/dashboard/assets/css/style-dashboard.css' rel="stylesheet" id="main-style" />

<!-- <section id="content"> -->
<div class="banner-section">
    <h1><?php the_title(); ?></h1>
</div>

<div class="content-fluid cs-directory-page" style="padding:15px; background-color: #f7f7f7;">
    <div class="row" style="padding:50px 0px">

        <aside class="col-md-3">
            <form name="directory-filterpage" method="GET">
                <div class="card">
                    <article class="filter-group">
                        <article class="filter-group">
                            <header class="card-header">
                                <a href="#" data-toggle="collapse" data-target="#collapse_2" aria-expanded="true">
                                    <i class="icon-control fa fa-chevron-down"></i>
                                    <h6 class="title">Category</h6>
                                </a>
                            </header>
                            <div class="filter-content collapse show" id="collapse_2">
                                <div class="card-body">
                                    <select class="filter-data-select" name="catid[]" multiple="multiple" data-placeholder="Category">
                                        <?php
                                        $terms = get_terms(array(
                                            'taxonomy'   => 'course_cat',
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false,
                                            'exclude'    => 217
                                        ));

                                        if (!is_wp_error($terms) && !empty($terms)) {
                                            foreach ($terms as $term) {
                                                $cselected = '';
                                                if (!empty($_GET['catid']) && in_array($term->term_id, $_GET['catid'])) {
                                                    $cselected = 'selected="selected"';
                                                }
                                                echo '<option value="' . esc_attr($term->term_id) . '" ' . $cselected . '>' . esc_html($term->name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div> <!-- card-body -->
                            </div> <!-- filter-content -->
                        </article> <!-- filter-group -->
                        <article class="filter-group">
                            <header class="card-header">
                                <a href="#" data-toggle="collapse" data-target="#collapse_3" aria-expanded="true">
                                    <i class="icon-control fa fa-chevron-down"></i>
                                    <h6 class="title">Level</h6>
                                </a>
                            </header>
                            <div class="filter-content collapse show" id="collapse_3">
                                <div class="card-body">
                                    <select class="filter-data-select" name="level[]" multiple="multiple" data-placeholder="Level">
                                        <?php
                                        $terms = get_terms(array(
                                            'taxonomy'   => 'course_difficulty',
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false,
                                        ));

                                        if (!is_wp_error($terms) && !empty($terms)) {
                                            foreach ($terms as $term) {
                                                $lselected = '';
                                                if (!empty($_GET['level']) && in_array($term->term_id, $_GET['level'])) {
                                                    $lselected = 'selected="selected"';
                                                }
                                                echo '<option value="' . esc_attr($term->term_id) . '" ' . $lselected . '>' . esc_html($term->name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div> <!-- card-body -->
                            </div> <!-- filter-content -->
                        </article> <!-- filter-group -->
                        <article class="filter-group">
                            <header class="card-header">
                                <a href="#" data-toggle="collapse" data-target="#collapse_4" aria-expanded="true">
                                    <i class="icon-control fa fa-chevron-down"></i>
                                    <h6 class="title">Instructor</h6>
                                </a>
                            </header>
                            <div class="filter-content collapse show" id="collapse_4">
                                <div class="card-body">
                                    <select class="filter-data-select" name="instructor[]" multiple="multiple" data-placeholder="Instructor">
                                        <?php
                                        $args = array(
                                            'role'    => 'instructor',
                                            'orderby' => 'user_nicename',
                                            'order'   => 'ASC'
                                        );
                                        $users = get_users($args);

                                        if (!empty($users)) {
                                            foreach ($users as $user) {
                                                $selected = '';
                                                if (!empty($_GET['instructor']) && in_array($user->ID, $_GET['instructor'])) {
                                                    $selected = 'selected="selected"';
                                                }

                                                // Fetch first name or fallback to display name
                                                $first_name = get_user_meta($user->ID, 'first_name', true);
                                                $name = !empty($first_name) ? $first_name : $user->display_name;

                                                echo '<option value="' . esc_attr($user->ID) . '" ' . $selected . '>' . esc_html($name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </article>
                        <article class="filter-group">
                            <header class="card-header">
                                <a href="#" data-toggle="collapse" data-target="#collapse_5" aria-expanded="true">
                                    <i class="icon-control fa fa-chevron-down"></i>
                                    <h6 class="title">Price</h6>
                                </a>
                            </header>
                            <?php $pricelistArr = array('S' => 'Free', 'H' => 'Paid'); ?>
                            <div class="filter-content collapse show" id="collapse_5">
                                <div class="card-body">
                                    <select class="filter-data-select" name="price[]" multiple="multiple" data-placeholder="Price">
                                        <?php
                                        foreach ($pricelistArr as $key => $pricelist) {
                                            $priceSel = '';
                                            if (!empty($_GET['price']) && in_array($key, $_GET['price'])) {
                                                $priceSel = 'selected="selected"';
                                            }
                                            echo '<option value="' . esc_attr($key) . '" ' . $priceSel . '>' . esc_html($pricelist) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div> <!-- card-body -->
                            </div> <!-- filter-content -->
                        </article> <!-- filter-group -->
                        <article class="filter-group">
                            <header class="card-header">
                                <a href="#" data-toggle="collapse" data-target="#collapse_7" aria-expanded="true" class="">
                                    <i class="icon-control fa fa-chevron-down"></i>
                                    <h6 class="title">Price Range</h6>
                                </a>
                            </header>
                            <div class="filter-content collapse show" id="collapse_7" style="">
                                <div class="card-body">
                                    <div class="slider-box">
                                        <?php
                                        // Get min and max prices from WooCommerce products linked to courses
                                        $min_price = 0;
                                        $max_price = 500;

                                        // // Get all courses to find linked products
                                        $all_courses = get_posts([
                                            'post_type' => 'course',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'fields' => 'ids',
                                        ]);



                                        $prices = [];
                                        foreach ($all_courses as $course_id) {
                                            $product_id = get_post_meta($course_id, '_linked_product_id', true);
                                            if ($product_id) {
                                                switch_to_blog(1);
                                                $product = wc_get_product($product_id);
                                                if ($product) {
                                                    $prices[] = floatval($product->get_price());
                                                }
                                                restore_current_blog();
                                            }
                                        }

                                        if (!empty($prices)) {
                                            $min_price = min($prices);
                                            $max_price = max($prices);
                                        }
                                         switch_to_blog(1);
                                         $currency= get_option('woocommerce_currency');
                                          restore_current_blog();
                                        // // Get current values from URL parameters
                                        $current_min = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
                                        $current_max = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;

                                        if ($current_min < $min_price) $current_min = $min_price;
                                        if ($current_max > $max_price) $current_max = $max_price;
                                       
                                        ?>

                                        <input type="hidden" name="min_price" id="min_price" value="<?php echo $current_min; ?>" data-mincurrency="<?php echo ( $currency === 'INR' ) ? '₹' : '$'; ?>">
                                        <input type="hidden" name="max_price" id="max_price" value="<?php echo $current_max; ?>" data-maxcurrency="<?php echo ( $currency === 'INR' ) ? '₹' : '$'; ?>">
                                        <input type="text" name="PriceRangefilter" id="PriceRangefilter" readonly value="<?php echo urldecode($current_min).'-'.$current_max; ?>">
                                        <div id="price_range" class="slider"></div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <!--<article class="filter-group">
                            <header class="card-header">
                                <a href="#" data-toggle="collapse" data-target="#collapse_6" aria-expanded="true" class="">
                                    <i class="icon-control fa fa-chevron-down"></i>
                                    <h6 class="title">Total Duration of Course</h6>
                                </a>
                            </header>
                            <div class="filter-content collapse show" id="collapse_6" style="">
                                <div class="card-body">
                                    <div class="slider-box">
                                        <input type="text" name="durationRange" id="priceRange" readonly>
                                        <div id="price-range" class="slider"></div>
                                    </div>

                                </div>--> <!-- card-body.// -->
                            <!--</div>
                        </article>-->  <!-- filter-group  .// -->

                        <input type="submit" class="filterBtn" value="Apply"><br />
                        <a href="/learning/all-courses/" class="btn filterBtn ">Clear Filters</a>
                </div> <!-- card.// -->
            </form>
        </aside>
        <!-- End sidebar col -->
        <!-- End sidebar col -->
        <div class="col-md-9">
            <!-- Listing Grids Start -->
            <div class="cs_directory_main">

                <?php
                $meta_query = [];
                $filter = [];
                $paged = get_query_var('paged') ?: 1;

                // Membership filter
                // if (!empty($_GET['filtersData'])) {
                //     $filtersData = $_GET['filtersData'];
                //     $meta_query[] = [
                //         'key'     => 'vibe_pmpro_membership',
                //         'value'   => implode('|', $filtersData),
                //         'compare' => 'REGEXP'
                //     ];
                // }

                // Taxonomy filters
                if (!empty($_GET['catid'])) {
                    $filter[] = [
                        'taxonomy' => 'course_cat',
                        'field'    => 'term_id',
                        'terms'    => $_GET['catid'],
                    ];
                }

                if (!empty($_GET['level'])) {
                    $filter[] = [
                        'taxonomy' => 'course_difficulty',
                        'field'    => 'term_id',
                        'terms'    =>  $_GET['level'],
                    ];
                }

                // Duration range
                if (!empty($_GET['durationRange'])) {
                    $raw_range = explode('-', $_GET['durationRange']);
                    $range = array_map('intval', $raw_range);

                    $meta_query[] = [
                        'key'     => 'vibe_duration',
                        'value'   => $range,
                        'type'    => 'numeric',
                        'compare' => 'BETWEEN',
                    ];
                }

                //Price Range
                if (!empty($_GET['min_price']) || !empty($_GET['max_price'])) {
                    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
                    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_INT_MAX;

                    $filtered_course_ids = [];

                    // Get all courses
                    $all_courses = get_posts([
                        'post_type' => 'course',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    ]);

                    foreach ($all_courses as $course_id) {
                        $product_id = get_post_meta($course_id, '_linked_product_id', true);
                        $price = 0;

                        if ($product_id) {
                            switch_to_blog(1);
                            $product = wc_get_product($product_id);
                            if ($product) {
                                $price = floatval($product->get_price());
                            }
                            restore_current_blog();
                        }

                        // Check price range
                        if ($price >= $min_price && $price <= $max_price) {
                            $filtered_course_ids[] = $course_id;
                        } elseif (!$product_id && $min_price <= 0) {
                            // If no product (free course), allow it if min_price <= 0
                            $filtered_course_ids[] = $course_id;
                        }
                    }

                    if (empty($filtered_course_ids)) {
                        $filtered_course_ids = [0]; // No match
                    }
                }


                // Course price (free or not)
                if (!empty($_GET['price'])) {
                    $priceFilter = $_GET['price'];
                    $price_filtered_courses = [];

                    $all_courses = get_posts([
                        'post_type' => 'course',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    ]);

                    foreach ($all_courses as $course_id) {
                        $product_id = get_post_meta($course_id, '_linked_product_id', true);
                        $price = 0;

                        if ($product_id) {
                            switch_to_blog(1); // if product is on main site
                            $product = wc_get_product($product_id);
                            if ($product) {
                                $price = floatval($product->get_price());
                            }
                            restore_current_blog();
                        }

                        if (in_array('H', $priceFilter) && $price > 0) {
                            $price_filtered_courses[] = $course_id;
                        }

                        if (in_array('S', $priceFilter) && $price == 0) {
                            $price_filtered_courses[] = $course_id;
                        }
                    }

                    if (!empty($price_filtered_courses)) {
                        if (!empty($filtered_course_ids)) {
                            if(in_array('S', $_GET['price'])) {
                                 $filtered_course_ids = $price_filtered_courses;
                            }else {
                                // Intersect with existing price range filtered results
                                $filtered_course_ids = array_intersect($filtered_course_ids, $price_filtered_courses);
                            }
                        } else {
                            $filtered_course_ids = $price_filtered_courses;
                        }
                    }
                }




                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                $args = [
                    'post_type'      => 'course',
                    'post_status'    => 'publish',
                    'order'          => 'DESC',
                    'posts_per_page' => 6,
                    'paged'          => $paged,
                ];

                // Instructor (use author__in instead of taxonomy/meta)
                if (!empty($_GET['instructor'])) {
                    $instuctor = $_GET['instructor'];
                    $meta_query[] = array(
                        'key'     => '_llms_instructors',
                        'value'   => 'i:' . intval($instuctor[0]),
                        'compare' => 'LIKE',
                    );

                }

                // if (!empty($meta_query)) {
                //     $args['meta_query'] = array_merge(['relation' => 'AND'], $meta_query);
                // }

                if (!empty($filter)) {
                    $args['tax_query'] = array_merge(['relation' => 'AND'], $filter);
                }


                if (!empty($filtered_course_ids)) {
                    $args['post__in'] = $filtered_course_ids;
                } elseif (!empty($_GET['price']) || (!empty($_GET['min_price']) && !empty($_GET['max_price']))) {
                    // If filters are set but result is empty, force no results
                    $args['post__in'] = [0];
                }

                $query = new WP_Query($args);
                $total_users = $query->max_num_pages;


                if ($query->have_posts()) { ?>

                    <div class="cs_directory card team-boxed" id="member_data">
                        <div class="row g-4">

                            <?php
                            while ($query->have_posts()) {
                                $query->the_post();
                                //echo '<pre>'; print_r(get_post_meta(get_the_ID())); echo '</pre>';
                                //echo '<pre>'; print_r($query); echo '</pre>';

                                $cproduct_id = get_post_meta(get_the_ID(), '_linked_product_id', true);
                                if ($cproduct_id) {
                                     switch_to_blog(1);
                                     $cproduct = wc_get_product($cproduct_id);
                                     $cproduct_price = $cproduct->get_price_html();
                                     restore_current_blog();
                                 }


                                $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium', false);
                                $vibe_pmpro_membership =  get_post_meta($post->ID, 'vibe_pmpro_membership', true);
                                $lebelName =  wp_get_post_terms($post->ID, 'level',  array("fields" => "names"));
                                $vibe_duration =  get_post_meta($post->ID, 'vibe_duration', true);
                                $vibe_students =  get_post_meta($post->ID, 'vibe_students', true);
                                $vibe_course_duration =   get_post_meta($post->ID, 'vibe_course_duration_parameter', true);
                                if ($vibe_course_duration == 3600) {
                                    $vibe_course_duration = 'Hours';
                                } else
                    if ($vibe_course_duration == 86400) {
                                    $vibe_course_duration = 'Days';
                                } else
                    if ($vibe_course_duration == 604800) {
                                    $vibe_course_duration = 'Week';
                                } else
                    if ($vibe_course_duration == 2592000) {
                                    $vibe_course_duration = 'Month';
                                } else
                    if ($vibe_course_duration == 31536000) {
                                    $vibe_course_duration = 'Year';
                                }




                            ?>
                                <div class="col-sm-6 col-lg-4 col-xl-4">
                                    <div class="card shadow h-100">
                                        <?php if (!empty($src[0])) { ?>
                                            <img class="card-img-top" src="<?php echo $src[0]; ?>" />
                                        <?php } else { ?>
                                            <img class="card-img-top" src="<?php echo site_url() . '/wp-content/uploads/sites/6/woocommerce-placeholder.png' ?>" />
                                        <?php
                                        }
                                        ?>
                                        <div class="card-body pb-0">
                                            <div class="d-flex justify-content-between mb-2">
                                                <a href="#" class="badge bg-purple bg-opacity-10 text-purple"><?php //echo //$lebelName[0];
                                                                                                                ?></a>
                                                <!-- <a href="#" class="h6 mb-0"><i class="far fa-heart"></i></a> -->
                                            </div>

                                            <h5 class="card-title fw-normal"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h5>
                                            <hr>
                                            <p><?php echo wp_trim_words(get_the_content(), 10); // post content
                                                ?></p>

                                            <ul class="list-inline mb-0" style="display:none">
                                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                                                <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                                                <li class="list-inline-item ms-2 h6 fw-light mb-0">4.0/5.0</li>
                                            </ul>
                                            <div class="d-flex justify-content-between">
                                                <!-- <span class="h6 fw-light mb-0"><i class="far fa-clock text-danger me-2"></i><?php //echo  $vibe_duration; ?> <?php // $vibe_course_duration; ?></span> -->
                                                <?php  if ($cproduct_id) { ?>
                                                <span class="h6 fw-light mb-0"><?php echo $cproduct_price; ?></span>
                                                <?php } ?>
                                                <span class="h6 fw-light mb-0" style="display:none"><i class="fas fa-table text-orange me-2"></i><?php echo get_the_date('F j, Y'); ?> </span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <?php
                                                foreach ($vibe_pmpro_membership as $key => $lebelMem) {
                                                    $membership_levels = $wpdb->get_results("SELECT * FROM wpcw_pmpro_membership_levels WHERE id = '$lebelMem'");
                                                    //print_r($membership_levels);

                                                    foreach ($membership_levels as $key => $lebel) {

                                                ?>
                                                        <span class="h6 fw-light mb-0">
                                                            <a href="/membership-account/membership-details/" class="course_price_option amount" style="padding:5px;"><strong><?php echo $lebel->name; ?></strong></a></span>
                                                <?php }
                                                } ?>
                                            </div>
                                        </div>


                                    </div>
                                </div>


                            <?php }
                        } else { ?>

                            <!-- Warning Alert -->
                            <div class="alert alert-warning alert-dismissible fade show">
                                <strong>Warning!</strong> No Record Found..
                            </div>
                        <?php } ?>
                        </div>
                    </div>
            </div>

            <div class="cs_members_directory_pagination" style="display:block;">
                <div class="clearfix">
                    <div id="hint" class="hint-text">Total : <b><?php echo $query->found_posts;; ?></b> entries</div>
                    <div id="pagination" class="pagination" style="float: right;">
                        <?php
                        $big = 999999999;
                        echo paginate_links(array(
                            'base' => str_replace($big, '%#%', get_pagenum_link($big)),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $query->max_num_pages,
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;'
                        ));

                        ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- Listing Grids End -->
</div>
</div>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet" id="jquery-ui-css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $(".filter-data-select").select2({
            allowClear: true,
            placeholder: function() {
                $(this).data('placeholder');
            },

            theme: "classic",
            width: 'resolve',
        });
    });

    $(function() {
        $("#price-range").slider({
            step: 1,
            range: true,
            min: 0,
            max: 100,
            values: [0, 100],
            slide: function(event, ui) {
                $("#priceRange").val(ui.values[0] + " - " + ui.values[1]);
            }
        });
        $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values", 1));

    });

    $(function() {
        $("#student-range").slider({
            step: 1,
            range: true,
            min: 0,
            max: 100,
            values: [0, 100],
            slide: function(event, ui) {
                $("#studentRange").val(ui.values[0] + " - " + ui.values[1]);
            }
        });
        $("#studentRange").val($("#student-range").slider("values", 0) + " - " + $("#student-range").slider("values", 1));

    });
    jQuery(function($) {
        // Get min and max values from PHP (you'll need to output these as JS variables)
        var minPrice = <?php echo json_encode($min_price); ?>;
        var maxPrice = <?php echo json_encode($max_price); ?>;
        var currentMin = <?php echo json_encode($current_min); ?>;
        var currentMax = <?php echo json_encode($current_max); ?>;
        var minCurrency = $('#min_price').data('mincurrency');
        var maxCurrency = $('#max_price').data('maxcurrency');

        $("#price_range").slider({
            step: 10,
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [currentMin, currentMax],
            slide: function(event, ui) {
                console.log("Slider moved:", ui.values);
                $("#PriceRangefilter").val(minCurrency + ui.values[0] + " - "+maxCurrency + ui.values[1]);
                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);
            }
        });

        // Initialize display
        $("#PriceRangefilter").val(minCurrency + currentMin + " - "+maxCurrency + currentMax);

        // Debug: Check form submission
        $('form[name="directory-filterpage"]').on('submit', function() {
            console.log("Form submitted with values:", {
                min_price: $("#min_price").val(),
                max_price: $("#max_price").val()
            });
        });
    });
</script>


<?php
get_footer();
?>

<style>
    .d-flex {
        display: -ms-flexbox !important;
        display: flex !important;
        flex-wrap: wrap;
    }

    .ui-widget-content {
        background: #ffc107;
    }

    .slider-box {
        width: 90%;
        margin: 25px auto
    }

    label,
    input {
        border: none;
        display: inline-block;
        margin-right: -4px;
        vertical-align: top;
        width: 30%
    }

    input {
        width: 70%
    }

    .slider {
        margin: 25px 0
    }

    .text-danger {
        color: #fd7e14 !important;
    }

    .text-orange {
        color: #fd7e14 !important;
    }

    .fw-light {
        font-weight: 400 !important;
    }

    .me-2 {
        margin-right: 0.5rem !important;
    }

    .justify-content-between {
        -webkit-box-pack: justify !important;
        -ms-flex-pack: justify !important;
        justify-content: space-between !important;
    }

    .fw-light {
        font-weight: 400 !important;
    }

    .me-0 {
        margin-right: 0 !important;
    }

    .list-inline {
        padding-left: 0;
        list-style: none;
    }

    .text-purple {
        --bs-bg-opacity: 1;
        background-color: #e6f8f3 !important;
    }

    .col-sm-6.col-lg-4.col-xl-4 {
        padding: 10px;
    }

    .card.user-card {
        border-top: none;
        -webkit-box-shadow: 0 0 1px 2px rgba(0, 0, 0, 0.05), 0 -2px 1px -2px rgba(0, 0, 0, 0.04), 0 0 0 -1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 0 1px 2px rgba(0, 0, 0, 0.05), 0 -2px 1px -2px rgba(0, 0, 0, 0.04), 0 0 0 -1px rgba(0, 0, 0, 0.05);
        -webkit-transition: all 150ms linear;
        transition: all 150ms linear;
    }

    .card {
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
        box-shadow: none;
        border: none;
        margin-bottom: 30px;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    .card .card-header {
        background-color: transparent;
        border-bottom: none;
        padding: 25px;
    }

    .card .card-header h5 {
        margin-bottom: 0;
        color: #222;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        margin-right: 10px;
        line-height: 1.4;
    }

    .card .card-header+.card-block,
    .card .card-header+.card-block-big {
        padding-top: 0;
    }

    .user-card .card-block {
        text-align: center;
    }

    .card .card-block {
        padding: 15px;
    }

    .user-card .card-block .user-image {
        position: relative;
        margin: 0 auto;
        display: inline-block;
        padding: 5px;
        width: 110px;
        height: 110px;
    }

    .user-card .card-block .user-image img {
        z-index: 20;
        position: absolute;
        top: 0;
        left: 0;
        width: 100px;
        height: 100px;
        background-color: #0365b3;
        border-radius: 50%;
    }

    .img-radius {
        border-radius: 50%;
    }

    .f-w-600 {
        font-weight: 600;
    }

    .m-b-10 {
        margin-bottom: 10px;
    }

    .m-t-25 {
        margin-top: 25px;
    }

    .m-t-15 {
        margin-top: 15px;
    }

    .card .card-block p {
        line-height: 1.4;
    }

    .text-muted {
        color: #919aa3 !important;
    }

    .user-card .card-block .activity-leval li.active {
        background-color: #2ed8b6;
    }

    .user-card .card-block .activity-leval li {
        display: inline-block;
        width: 15%;
        height: 4px;
        margin: 0 3px;
        background-color: #ccc;
    }

    .user-card .card-block .counter-block {
        color: #fff;
    }

    .bg-c-blue {
        background: linear-gradient(45deg, #4099ff, #73b4ff);
    }

    .bg-c-green {
        background: linear-gradient(45deg, #2ed8b6, #59e0c5);
    }

    .bg-c-yellow {
        background: linear-gradient(45deg, #FFB64D, #ffcb80);
    }

    .bg-c-pink {
        background: linear-gradient(45deg, #FF5370, #ff869a);
    }

    .m-t-10 {
        margin-top: 10px;
    }

    .p-20 {
        padding: 20px;
    }

    .user-card .card-block .user-social-link i {
        font-size: 30px;
    }

    .text-facebook {
        color: #3B5997;
    }

    .text-twitter {
        color: #42C0FB;
    }

    .text-dribbble {
        color: #EC4A89;
    }

    .user-card .card-block .user-image:before {
        bottom: 0;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
    }

    /* .user-card .card-block .user-image:after, .user-card .card-block .user-image:before {
    content: "";
    width: 100%;
    height: 48%;
    border: 2px solid #4099ff;
    position: absolute;
    left: 0;
    z-index: 10;
} */

    .user-card .card-block .user-image:after {
        top: 0;
        border-top-left-radius: 50px;
        border-top-right-radius: 50px;
    }

    /* .user-card .card-block .user-image:after, .user-card .card-block .user-image:before {
    content: "";
    width: 100%;
    height: 48%;
    border: 2px solid #4099ff;
    position: absolute;
    left: 0;
    z-index: 10;
} */
    h6.title-name {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    p.title-mb-type {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.5px;
    }

    p.title-mb-type {
        background-color: #f1f5f7;
        text-align: center;
        padding: 5px;
    }

    p.card-content {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.5px;
    }

    .total-exp {
        color: #060606;
        font-weight: 600;
    }

    p.current-comp {
        color: #060606;
    }

    .current-comp span {
        color: #a7a7a7;
    }

    .total-exp span {
        color: #a7a7a7;
    }

    .team-boxed p {
        color: #060606;
    }

    .cs_directory_wrapper .cs_directory_header .cs_search input {
        border: none;
        background: none;
        padding: 15px 10px;
        width: 95%;
        font-size: 16px;
    }

    [class^="vicon-"],
    [class*=" vicon-"] {
        font-size: 18px;
    }

    .cs_directory_wrapper .cs_directory_header>span {
        display: grid;
        align-items: center;
        width: 100%;
        background-color: #fff;
    }

    .card.user-card a {
        text-decoration: none;
    }

    .cs_directory_filter span {
        background-color: #f1f5f7;
        padding: 5px 10px !IMPORTANT;
    }

    .filter-col card-header {
        position: relative;
        top: 5px;
    }

    .cs_directory_filter {
        background-color: #f9f9f9;
        margin-bottom: 10px;
        box-shadow: 0 2px 2px #bfbfbf3d;
    }

    .current-comp {
        height: 35px;
    }

    .verStatus {
        position: absolute;
        right: -80px;
        top: 0;
    }

    .verStatus img {
        width: 30%;
    }

    .vf-user .title-mb-type {
        background-color: #dbffb2;
    }

    .card.user-card.vf-user {
        box-shadow: 0 0 1px 2px #dbffb2, 0 -2px 1px -2px rgba(0, 0, 0, 0.04), 0 0 0 -1px rgba(0, 0, 0, 0.05);
    }
</style>
<style>
    .icon-control {
        margin-top: 5px;
        float: right;
        font-size: 80%;
    }

    .card-header {
        padding: .75rem 1.25rem !important;
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, .03) !important;
        border-bottom: 1px solid rgba(0, 0, 0, .125) !important;
    }


    .btn-light {
        background-color: #fff;
        border-color: #e4e4e4;
    }

    .list-menu {
        list-style: none;
        margin: 0;
        padding-left: 0;
    }

    .list-menu a {
        color: #343a40;
    }

    .card-product-grid .info-wrap {
        overflow: hidden;
        padding: 18px 20px;
    }

    [class*='card-product'] a.title {
        color: #212529;
        display: block;
    }

    .card-product-grid:hover .btn-overlay {
        opacity: 1;
    }

    .card-product-grid .btn-overlay {
        -webkit-transition: .5s;
        transition: .5s;
        opacity: 0;
        left: 0;
        bottom: 0;
        color: #fff;
        width: 100%;
        padding: 5px 0;
        text-align: center;
        position: absolute;
        background: rgba(0, 0, 0, 0.5);
    }

    .img-wrap {
        overflow: hidden;
        position: relative;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
        top: -5px !important;

    }

    .form-control {
        border: 1px solid #ccc;
        border-radius: 3px;
        box-shadow: none !important;
        margin-bottom: 15px;
    }

    .form-control:focus {
        border: 1px solid #34495e;
    }

    .select2.select2-container {
        width: 100% !important;
    }

    .select2.select2-container .select2-selection {
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        height: 34px;
        margin-bottom: 15px;
        outline: none !important;
        transition: all .15s ease-in-out;
    }

    .select2.select2-container .select2-selection .select2-selection__rendered {
        color: #333;
        line-height: 32px;
        padding-right: 33px;
    }

    .select2.select2-container .select2-selection .select2-selection__arrow {
        background: #f8f8f8;
        border-left: 1px solid #ccc;
        -webkit-border-radius: 0 3px 3px 0;
        -moz-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;
        height: 32px;
        width: 33px;
    }

    .select2.select2-container.select2-container--open .select2-selection.select2-selection--single {
        background: #f8f8f8;
    }

    .select2.select2-container.select2-container--open .select2-selection.select2-selection--single .select2-selection__arrow {
        -webkit-border-radius: 0 3px 0 0;
        -moz-border-radius: 0 3px 0 0;
        border-radius: 0 3px 0 0;
    }

    .select2.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
        border: 1px solid #34495e;
    }

    .select2.select2-container .select2-selection--multiple {
        height: auto;
        min-height: 34px;
    }

    .select2.select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
        margin-top: 0;
        height: 32px;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__rendered {
        display: block;
        padding: 0 4px;
        line-height: 29px;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #f8f8f8;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin: 4px 4px 0 0;
        padding: 0 6px 0 22px;
        height: 24px;
        line-height: 24px;
        font-size: 12px;
        position: relative;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
        position: absolute;
        top: 0;
        left: 0;
        height: 22px;
        width: 22px;
        margin: 0;
        text-align: center;
        color: #e74c3c;
        font-weight: bold;
        font-size: 16px;
    }

    .select2-container .select2-dropdown {
        background: transparent;
        border: none;
        margin-top: -5px;
    }

    .select2-container .select2-dropdown .select2-search {
        padding: 0;
    }

    .select2-container .select2-dropdown .select2-search input {
        outline: none !important;
        border: 1px solid #34495e !important;
        border-bottom: none !important;
        padding: 4px 6px !important;
    }

    .select2-container .select2-dropdown .select2-results {
        padding: 0;
    }

    .select2-container .select2-dropdown .select2-results ul {
        background: #fff;
        border: 1px solid #34495e;
    }

    .select2-container .select2-dropdown .select2-results ul .select2-results__option--highlighted[aria-selected] {
        background-color: #3498db;
    }

    input#seachmembers {
        width: 94%;
    }

    span#searchUsers {
        position: relative;
        right: 40px;
    }

    section.cs-directory-page {
        margin: 0 auto;
        max-width: 1300px;
        width: 100%;
    }

    .cs_directory_filter span {
        background-color: #ff000000 !important;
        padding: 0px 0px !IMPORTANT;
    }

    .select2.select2-container .select2-selection--multiple {
        height: auto;
        min-height: auto;
        margin-bottom: 0px;
    }

    .filter-col card-header {
        position: relative;
        top: 5px;
        background-color: #f1f5f7;
    }

    .cs_directory_filter {
        background-color: #ff0a0a00 !important;
        margin-bottom: 10px;
        box-shadow: none;
    }

    .filter-sidebar {
        background-color: #f1f5f7 !important;
        padding: 20px 10px !important;
    }

    .select2.select2-container .select2-selection--multiple {
        height: auto;
        min-height: auto;
        border: none;
        margin-bottom: 15px;
        border-bottom: 1px solid #2196F3;
        border-radius: 10px;
        margin-top: 5px;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__rendered {
        display: block;
        padding: 0 4px;
        line-height: 29px;
        background-color: #fff;
        padding: 10px;
        border-radius: 10px;
    }

    .select2-container--classic.select2-container--open .select2-dropdown {
        border-color: #5897fb;
        position: relative;
        top: 20px;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #bce8ff;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin: 4px 4px 0 0;
        padding: 0 15px 0px;
        height: 30px;
        line-height: 26px;
        font-size: 14px;
        position: relative;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
        position: absolute;
        top: -8px !important;
        right: -5px;
        height: 16px;
        width: 16px;
        margin: 0;
        text-align: center;
        color: #ffffff;
        font-weight: bold;
        font-size: 14px;
        line-height: 16px;
        background-color: red !important;
        border-radius: 50px;
        left: auto;
    }

    .filter-sidebar {
        background-color: #f1f5f7 !important;
        padding: 20px !important;
        border-radius: 10px;
        margin-right: 15px;
        max-width: 23%;
        box-shadow: 0 0 5px #c5c5c5;
    }

    input.filterBtn {
        width: 100%;
        background-color: #1b3b4c;
        height: 45px;
        border-radius: 10px;
        margin-top: 15px;
        cursor: pointer;
    }

    input.filterBtn:hover {
        background-color: #f5bb11;
    }

    input.filterBtn {
        width: 99%;
        background-color: #1b3b4c;
        height: 45px;
        border-radius: 10px;
        margin-top: 15px;
        cursor: pointer;
    }

    a.btn.filterBtn {
        float: right;
        position: relative;
        border-radius: 10px;
        font-weight: 600;
    }

    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }

    .text-danger {
        --bs-text-opacity: 1;
    }

    .justify-content-between {

        margin-top: 15px;
    }

    /* Ash css */
    .cs-directory-page .card-header {
        background-color: #2b5971 !important;
        padding: 10px !important;
    }

    .cs-directory-page .card-header .title {
        margin-bottom: 0;
    }

    .cs-directory-page .card-header a {
        color: #fff;
    }

    .cs-directory-page .filter-content input {
        display: block;
        border: 1px solid #c9c9c9;
        width: 100%;
        border-radius: 10px;
    }

    .cs-directory-page .slider-box {
        width: auto !important;
        margin: 0px !important;
    }

    .cs-directory-page .text-purple {
        --bs-bg-opacity: 1;
        background-color: #000000 !important;
    }

    .cs-directory-page .card {
        background: none !important;
    }

    .cs-directory-page .col-sm-6.col-lg-4.col-xl-4 {
        padding: 10px;
        margin-top: 10px;
    }

    .cs-directory-page .card-body.pb-0 {
        background-color: #fff;
    }

    .cs-directory-page .card-title.fw-normal a {
        color: #000 !important;
        font-weight: 600;
    }

    .cs-directory-page .badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .course_price_option.amount {
        color: #000;
        font-size: 13px;
    }

    .cs-directory-page .col-sm-6.col-lg-4.col-xl-4:hover .card-title.fw-normal a {
        color: #fec000 !important;
    }

    .cs-directory-page .card-header .title {
        margin-bottom: 0;
        font-size: 16px;
        color: #fff;
    }

    img.card-img-top {
        height: 250px;
        object-fit: cover;
    }

    .cs-directory-page .text-purple {
        --bs-bg-opacity: 1;
        background-color: #000000 !important;
        color: #ffff;
        height: 22px;
    }

    .cs-directory-page .filter-content input {
        display: block;
        border: 1px solid #c9c9c9;
        width: 100%;
        border-radius: 10px;
        height: 50px;
        padding: 10px;
    }

    [type=button],
    [type=submit],
    button {
        color: #fff;
    }

    .select2-container .select2-search--inline {
        float: none;
    }
</style>
<?php
