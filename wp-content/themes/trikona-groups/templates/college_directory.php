<?php
/**
 * Template Name: College Directory 
 * 
 *
 * @package Trikona
 * @subpackage Trikona
 * @since Trikona 1.0
 */

// Make the global $wpdb object (for direct DB access), $status_messages and $trikona_obj available in this scope
global $wpdb, $status_messages, $trikona_obj;

get_header(); 

if(get_current_user_id() > 0){
// Retrieve the list of states by calling the getStates() method from the $trikona_obj instance
$statelist = $trikona_obj->getStates();
?>
<link rel="stylesheet" href="<?= get_stylesheet_directory_uri().'/layouts/group-card-style.css' ?>">
<div class="container-fluid" style="padding: 50px 0px;">
    <div class="row w-100">
        <div class="custom-wraper col-md-3 filter-col">
            <!-- Search Filter -->
            <form method="GET" name="filterCompany" action="">
                <div class="custom_filter">
                    <span>Search by keywords</span>
                    <div class="custom_filter_values">
                        <div class="custom_filter_values search-div">
                            <input class="form-control" name="searchcollage" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn" type="submit">Search</button>
                        </div>
                    </div>
                </div>
                <div class="custom_filter">
                    <span>Locations</span>
                    <div class="custom_filter_values">
                        <div class="custom_filter_values">
                            <div class="">
                                <select name="state" style="width:100%" class="form-control" id="state-dropdown">
                                    <option value="">Select State</option>
                                    <?php foreach ($statelist as $key => $state) {?>
                                    <option value="<?= $state->id ?>" <?php if($_GET['state'] == $state->id) echo 'selected = "selected"'; ?>><?= $state->name ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom_filter">
                    <span>City</span>
                    <div class="custom_filter_values">
                        <div class="custom_filter_values">
                            <div class="">
                                <select name="collegeCity" style="width:100%" class="form-control" id="city-dropdown" require>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="custom_filter_values">
                        <input type="hidden" name="filtercom" value="true">
                        <button type="submit"  class="btn cstm-btn btn-color-1">Filter</button>
                        <a href="<?= home_url() ?>/college-directory/"class="btn cstm-btn btn-color-2">Clear</a>
                    </div>
                </div>
            </form>
            <?php 
                // Get the custom post type name for student courses from the object
                $student_courses_post_type = $trikona_obj->student_courses_post_type;


                // Prepare the query arguments to fetch all student courses
                $args = [
                    'post_type'      => $student_courses_post_type, // Custom post type for student courses
                    'posts_per_page' => -1,                         // Get all posts (no pagination)
                    'order'          => 'ASC',                      // Sort in ascending order (default by date or title)
                ];

                // Run the query to fetch student course posts
                $loop = new WP_Query($args);
            ?>
            <div class="custom_filter" style="display:none;">
                <span>Courses</span>
                <div class="custom_filter_values">
                    <div class="custom_filter_values">
                        <div class="">
                            <select style="width:100%" class="form-control" id="filterCourses">
                                <option value="">Select Courses</option>
                                <?php while ($loop->have_posts()): $loop->the_post();?>
                                <option value="<?= get_the_ID() ?>"><?php the_title();?></option>
                                <?php endwhile;?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Filter col -->

        <div class="vibebp_members_directory_main col-md-9">
            <?php
                // Get the group type identifier for colleges from the object
                $college_group_type = $trikona_obj->bp_group_type_college;

                // Get the meta key used to store/retrieve city information for a group
                $city_meta = $trikona_obj->city_meta;

                // Get the meta key used to store/retrieve state information for a group
                $state_meta = $trikona_obj->state_meta;

                // Prepare meta query based on filtered options in the URL (?filtercom=true)
                if(isset($_GET['filtercom']) && $_GET['filtercom']=='true'){
                    // Initialize meta_query with an OR relation (can be changed to AND if needed)
                    $bpgmq_querystring['meta_query'] = [
                        [
                            'relation' => 'OR', // Allows matching any of the meta conditions below
                        ]
                    ];

                    // If a state is selected in the filter
                    if (!empty($_GET['state'])) {
                        // Retrieve state details based on state ID passed via GET
                        $state_info = $trikona_obj->getStates(['id' => $_GET['state']]);

                        // Add a meta query condition to filter by state name
                        $bpgmq_querystring['meta_query'][] = [
                            'key'     => $state_meta,         // Meta key for state
                            'value'   => $state_info->name,   // State name to filter
                            'compare' => 'LIKE'               // Use LIKE for partial match
                        ];
                    }

                    // If a city is selected in the filter
                    if (!empty($_GET['collegeCity'])) {
                        // Add a meta query condition to filter by city name
                        $bpgmq_querystring['meta_query'][] = [
                            'key'     => $city_meta,              // Meta key for city
                            'value'   => $_GET['collegeCity'],    // City name to filter
                            'compare' => 'LIKE'                   // Use LIKE for partial match
                        ];
                    }
                }
                
                // Get the current page number from the query variables; default to 1 if not set
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                // Set the number of items to display per page (pagination limit)
                $number = 24;

                // Prepare query arguments when filtering is enabled but no college name is searched
                if($_GET['filtercom']=='true' && $_GET['searchcollage']==''){
                    $args = [
                        'group_type' => $college_group_type,      // Filter groups by the college group type
                        'order'      => 'ASC',                     // Sort results in ascending order
                        'orderby'    => 'name',                    // Order results by group name
                        'meta_query' => $bpgmq_querystring['meta_query'],  // Apply meta query filters like state, city, etc.
                    ];
                }

                // Prepare $args when filtering is enabled and a college name search term is provided
                if ($_GET['filtercom'] == 'true' && $_GET['searchcollage'] != '') {
                    $args = array(
                        'group_type'     => $college_group_type,    // Filter by college group type
                        'order'          => 'ASC',                   // Sort ascending
                        'orderby'        => 'name',                  // Order by group name
                        'search_terms'   => $_GET['searchcollage'], // Search term for college name
                        'search_columns' => ['name'],                // Search only in group names
                    );
                }
                
                // Prepare $args when no filtering is applied (default group listing with pagination)
                if ($_GET['filtercom'] == '') {
                    $args = array(
                        'group_type' => $college_group_type, // College group type filter
                        'order'      => 'ASC',                // Sort ascending
                        'orderby'    => 'name',               // Order by name
                        'per_page'   => 24,                   // Number of items per page
                        'page'       => $paged,               // Current page number for pagination
                    );
                }

                // Fetch groups based on the prepared arguments
                $groupsArr = groups_get_groups($args);

                // Total number of groups returned (useful for pagination, etc.)
                $total_users = $groupsArr['total'];
            ?>
            <div class="row text-center" id="member_data">
                <?php
                    // Check if there are any college groups returned in the results
                    if (!empty($groupsArr['groups'])) {
                        // Loop through each college group records
                        foreach($groupsArr['groups'] as $member_type=>$mt){ 
                            // Get the group's avatar image URL
                            $img = bp_get_group_avatar_url($mt->id);

                            // Get the URL/permalink of the group
                            switch_to_blog($trikona_obj->main_site_blog_id);
                            $group_slug = $mt->slug;
                            $href = esc_url( home_url( '/groups/' . $group_slug . '/' ) );
                            restore_current_blog();

                            // Retrieve the city meta value for the group (meta key 'city_32469')
                            $grpcity = groups_get_groupmeta($mt->id, 'city_32469', true);

                            // Retrieve the verified status meta for the group
                            $grpCons = groups_get_groupmeta($mt->id, 'verified', true);

                            // Retrieve the total member count meta value for the group
                            $total_member_count = groups_get_groupmeta($mt->id, 'total_member_count', true);

                            // Get user profiles related to this group to count the number of courses offered
                            $user_profiles = $trikona_obj->getUserProfiles(['group_id' => $mt->id]);
                            $no_of_cources = count($user_profiles); // Count the number of courses

                            // Get detailed user profile data to count the number of students associated with this group
                            $user_profile_data = $trikona_obj->getUserProfilesData(['group_id' => $mt->id]);
                            $no_of_students = count($user_profile_data); // Count the number of students

                            // Include the template to display the college card with the above data
                            include get_stylesheet_directory() . '/layouts/college-card.php';
                        }
                    } else {
                        // If no groups are found, display an error message (index 301 in the error messages array)
                        echo "<div class='col-md-12 '><h3>".$status_messages->error[301]."</h3></div>";
                    }
                ?>
            </div>
            <div class="row cs-pagination">
                <div class="col-md-6">
                    <div id="hint" class="hint-text">
                        Total :  <b><?= $total_users ?></b> entries
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pagination" class="pagination">
                        <?php
                            // Total number of users (items) from the query result
                            $total_user = $total_users;

                            // Calculate the total number of pages needed based on items per page
                            $total_pages = ceil($total_user / $number);

                            // Get the current page number from query vars; defaults to 1 if not set
                            $current_page = max(1, get_query_var('paged'));

                            // A large unlikely integer placeholder for pagination base URL
                            $big = 999999999;

                            // Output the pagination links using WordPress paginate_links function
                            echo paginate_links([
                                // Base URL for pagination, with a placeholder to be replaced by page number
                                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                // Format for the page number query parameter in URLs
                                'format'    => '?paged=%#%',
                                // Current page number to highlight the active page link
                                'current'   => $current_page,
                                // Total number of pages for pagination
                                'total'     => $total_pages,
                                // Text displayed for the previous page link
                                'prev_text' => __('&laquo; Previous'),
                                // Text displayed for the next page link
                                'next_text' => __('Next &raquo;'),
                            ]);
                        ?>
                    </div>
                </div>
            </div>        
        </div>
        <!-- End list -->
    </div>
    <!-- End row -->
</div>
<!-- End container -->
<style>
    .searchLoctions{ width:100%; }
    .vibebp_groups_directory_main{margin-left:20px !important;}
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    jQuery(document).ready(function() {
         setTimeout(function () {
            <?php if($_GET['state']!=""){ ?>
                var state_id = '<?= $_GET['state'] ?>';
            jQuery.ajax({
                     url : '<?= admin_url('admin-ajax.php') ?>',
                    type: "POST",
                    data: {action: "search_cities_filter",state_id: state_id
                    },
                    cache: false,
                success: function(result){
                    jQuery("#city-dropdown").html(result);
                }
            });
            
            <?php } ?>
         }, 2500);
        jQuery('#state-dropdown').on('change', function() {
            var state_id = this.value;
            jQuery.ajax({
                url : '<?= admin_url('admin-ajax.php') ?>',
                type: "POST",
                data: {action: "search_cities_filter",state_id: state_id },
                cache: false,
                success: function(result){
                    jQuery("#city-dropdown").html(result);
                }
            });
        });
    });
</script>
<?php }else{ ?>
    <div class="container-fluid cs-directory-page">
        <div class="card pt-4">
            <div class="card-block text-center">
                <h3>You are not authorized to access this page.</h3>
            </div>
        </div>
    </div>
<?php } get_footer(); ?>