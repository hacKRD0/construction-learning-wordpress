<?php 
    /**
     * Template Name: Company Directory 
     * 
     *
     * @package Trikona
     * @subpackage Trikona
     * @since Trikona 1.0 - 30-Apr-2024
     */
global $wpdb,$bp,$status_messages,$trikona_obj;

get_header(); 
if(get_current_user_id() > 0){
    // Retrieve the 'industries type' custom field value from the post meta using the key from the object
    $industriesType = get_post_meta($trikona_obj->industries_type_post_meta, 'group_fileds_optionName',true);

    // Retrieve the 'sectors' custom field value from the post meta using the key from the object
    $sectorArr = get_post_meta($trikona_obj->sectors_post_meta, 'group_fileds_optionName',true);

    // Retrieve the 'services type' custom field value from the post meta using the key from the object
    $services = get_post_meta($trikona_obj->services_type_post_meta, 'group_fileds_optionName',true);

    // Get the list of states by calling the getStates() method from the object
    $statelist = $trikona_obj->getStates();
?>
<link rel="stylesheet" href="<?= get_stylesheet_directory_uri().'/layouts/group-card-style.css' ?>">
<div class="container-fluid" style="padding: 50px 0px;">
    <div class="row w-100">
        <!-- Begin Filter Section -->
        <div class="custom-wraper col-md-3 filter-col">
            <form method="GET" name="filterCompany" action="">
                <div class="custom_filters">
                    <!-- Search Filter -->
                    <div class="custom_filter">
                        <span>Search by keywords</span>
                        <div class="custom_filter_values">
                            <div class="custom_filter_values search-div">
                                <input class="form-control" name="searchCompay" type="search" placeholder="Search" aria-label="Search" value="<?= $_GET['searchCompay'] ?>" >
                                <button class="btn" type="submit">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="custom_filter">
                        <span>Locations</span>
                        <div class="custom_filter_values">
                            <div class="custom_filter_values">
                                <div class="">
                                    <select style="width:100%; text-tra" class="form-control" id="state-dropdown" name="companyState">
                                        <option value="">Select State</option>
                                        <?php  foreach ($statelist as $key => $state) { ?>
                                        <option value="<?= $state->id ?>" <?php if(isset($_GET['companyState']) && $_GET['companyState'] == $state->id) echo 'selected = "selected"'; ?>><?= $state->name ?></option>
                                        <?php } ?>
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
                                    <select style="width:100%" class="form-control" id="city-dropdown" name="companyCity">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="custom_filter">
                        <span>Industries Type</span>
                        <div class="custom_filter_values">
                            <div class="custom_filter_values">
                                <div class="">
                                    <select style="width:100%"  name="Industries_type" id="Industries_type" class="select filtergroups">
                                        <option value="">Select Industries Type</option>
                                        <?php foreach ($industriesType as $key => $industriesType_val) { ?>
                                        <option value="<?= $industriesType_val ?>" <?php if(isset($_GET['Industries_type']) && $_GET['Industries_type'] == $industriesType_val) echo 'selected = "selected"'; ?>><?= $industriesType_val ?></option>
                                        <?php  }  ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="custom_filter">
                        <span>Sectors</span>
                        <div class="custom_filter_values">
                            <div class="custom_filter_values">
                                <div class="">
                                    <select style="width:100%"  name="sectors" id="sectors" class="select filtergroups">
                                        <option value="">Select Sectors </option>
                                        <?php foreach ($sectorArr as $key => $sectorVal) { ?>
                                        <option value="<?= $sectorVal ?>" <?php if(isset($_GET['sectors']) && $_GET['sectors'] == $sectorVal) echo 'selected = "selected"'; ?>><?= $sectorVal ?></option>
                                        <?php  }  ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="custom_filter">
                        <span>Services</span>
                        <div class="custom_filter_values">
                            <div class="custom_filter_values">
                                <div class="">
                                    <select style="width:100%"  name="services" id="filterservices" class="select filterservices">
                                        <option value="">Select Services </option>
                                        <?php foreach ($services as $key => $service) { if(empty($service)) { continue; }?>
                                        <option value="<?= $service ?>" <?php if(isset($_GET['services']) && $_GET['services'] == $service) echo 'selected = "selected"'; ?>><?=  $service ?></option>
                                        <?php  }  ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="custom_filter">
                        <input type="hidden" name="filtercom" value="true">
                        <button type="submit"  class="btn cstm-btn btn-color-1">Filter</button>
                        <a href="<?= home_url() ?>/company-directory/"class="btn cstm-btn btn-color-2">Clear</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- End filter Section -->

        <!-- Begin company card Section -->
        <div class="col-md-9">
            <?php
                // Get the city-related metadata from the object
                $city_meta = $trikona_obj->city_meta;

                // Get the services-related metadata from the object
                $services_meta = $trikona_obj->services_meta;

                // Get the sectors-related metadata from the object
                $sectors_meta = $trikona_obj->sectors_meta;

                // Get the industries type-related metadata from the object
                $industries_type_meta = $trikona_obj->industries_type_meta;

                // Get the verification status metadata from the object
                $verified = $trikona_obj->verified_meta;

                // Get the state-related metadata from the object
                $state_meta = $trikona_obj->state_meta;

                // Get the company group type from the object
                $group_type = $trikona_obj->bp_group_type_company;

                // Check if the filter is enabled via GET parameter (?filtercom=true)
                if(isset($_GET['filtercom']) && $_GET['filtercom']=='true'){
                    // Initialize the meta_query array with an OR relation for filtering
                    $bpgmq_querystring['meta_query'] = [
                        [ 
                          'relation' => 'OR', 
                        ]
                    ];

                    // If 'companyState' is set, get the state's name and add it to the meta query
                    if (!empty($_GET['companyState'])) {
                        $state_info = $trikona_obj->getStates(['id' => $_GET['companyState']]);
                        $bpgmq_querystring['meta_query'][] = [
                            'key'      => $state_meta,          // Meta key for state
                            'value'    => $state_info->name,    // State name to match
                            'compare'  => 'LIKE'                // Partial match using LIKE
                        ];
                    }

                    // If 'companyCity' is set, add it to the meta query
                    if (!empty($_GET['companyCity'])) {
                        $bpgmq_querystring['meta_query'][] = [
                            'key'     => $city_meta,              // Meta key for city
                            'value'   => $_GET['companyCity'],    // City name to match
                            'compare' => 'LIKE'                   // Partial match using LIKE
                        ];
                    }

                    // If 'services' is set, add it to the meta query
                    if (!empty($_GET['services'])) {
                        $bpgmq_querystring['meta_query'][] = [
                            'key'     => $services_meta,          // Meta key for services
                            'value'   => $_GET['services'],       // Service to match
                            'compare' => 'LIKE'                   // Partial match using LIKE
                        ];
                    }

                    // If 'Industries_type' is set, add it to the meta query
                    if (!empty($_GET['Industries_type'])) {
                        $bpgmq_querystring['meta_query'][] = [
                            'key'     => $industries_type_meta,        // Meta key for industry type
                            'value'   => $_GET['Industries_type'],     // Industry type to match
                            'compare' => 'LIKE'                        // Partial match using LIKE
                        ];
                    }

                    // If 'sectors' is set, add it to the meta query
                    if (!empty($_GET['sectors'])) {
                        $bpgmq_querystring['meta_query'][] = [
                            'key'     => $sectors_meta,          // Meta key for sectors
                            'value'   => $_GET['sectors'],       // Sector to match
                            'compare' => 'LIKE'                  // Partial match using LIKE
                        ];
                    }
                }

                // Get the current paged value from the query string (used for pagination), default to 1 if not set
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                // Number of groups to display per page
                $number = 24;

                // Check if filtering is enabled and there's no company name search
                if($_GET['filtercom']=='true' && $_GET['searchCompay']==''){
                    $args = [
                        'group_type'    => $group_type,                    // Group type to filter by (e.g. 'company')
                        'order'         => 'ASC',                          // Ascending order
                        'orderby'       => 'name',                         // Order by group name
                        'meta_query'    => $bpgmq_querystring['meta_query'] // Filter groups based on meta query filters
                    ]; 
                }

                // Check if filtering is enabled and a company name search is also provided
                if($_GET['filtercom']=='true' && $_GET['searchCompay']!=''){
                    $args = [
                        'group_type'     => $group_type,              // Group type to filter by
                        'order'          => 'ASC',                    // Ascending order
                        'orderby'        => 'name',                   // Order by group name
                        'search_terms'   => $_GET['searchCompay'],    // Search for groups by this term (company name)
                        'search_columns' => ['name'],                 // Search only within group names
                    ];      
                }
                
                // If no filter is applied, load default group list (with pagination)
                if($_GET['filtercom']==''){
                    $args = [
                        'group_type' => $group_type,  // Group type
                        'order'      => 'ASC',        // Ascending order
                        'orderby'    => 'name',       // Order by group name
                        'per_page'   => 24,           // Number of groups per page
                        'page'       => $paged,       // Current page number
                    ];
                }

                // Fetch the list of groups using the prepared arguments
                $groupsArr =  groups_get_groups($args);

                // Get the total number of matched groups (used for pagination or display)
                $total_users = $groupsArr['total'];
            ?>
            <div class="company-dir">
                <div class="applied_filters">
                    <span></span>
                </div>
                <div class="names" >
                    <div class="row text-center" id="member_data">
                        <?php
                            // Check if there are any groups returned from the query
                            if (!empty($groupsArr['groups'])) {
                                // Loop through each company group in the results
                                foreach($groupsArr['groups'] as $member_type=>$mt){
                                    // Get the group's avatar image URL
                                    $img = bp_get_group_avatar_url($mt->id);

                                    // Get the city metadata for the group
                                    $grpcity = groups_get_groupmeta($mt->id, $city_meta, true);

                                    // Get the verification status of the group
                                    $grpCons = groups_get_groupmeta($mt->id, $verified, true);

                                    // Get the list of group admins
                                    $group_admins = groups_get_group_admins($mt->id);

                                    // Get the permalink (URL) to the group
                                    switch_to_blog($trikona_obj->main_site_blog_id);
                                    $group_slug = $mt->slug;
                                    $href = esc_url( home_url( '/groups/' . $group_slug . '/' ) );
                                    restore_current_blog();

                                    $count = 0; // Initialize a counter (if needed later)

                                    // Prepare a query to count the number of job listings for this company group
                                    $args = [
                                        'post_type'      => 'job_listing',     // Custom post type for jobs
                                        'post_status'    => 'publish',         // Only published jobs
                                        'posts_per_page' => -1,                // Retrieve all posts
                                        'order'          => 'DESC',            // Order by most recent
                                        'meta_query'     => [                  // Filter jobs by group name
                                            [
                                                'key'     => '_job_groups',    // Meta key storing group info
                                                'value'   => $mt->id,        // Group name to match
                                                'compare' => '=',              // Exact match
                                            ],
                                        ]
                                    ];
                                    
                                    // Switch to the jobs site (in a multisite WordPress setup)
                                    switch_to_blog($trikona_obj->jobs_site_blog_id);

                                    // Run the job query
                                    $jobs = new WP_Query($args);

                                    // Restore the original blog context
                                    restore_current_blog();

                                    // Include the template that renders the company card
                                    include get_stylesheet_directory() . '/layouts/company-card.php';
                                }
                            } else {
                                // Display an error message if no companies were found
                                echo "<div class='col-md-12 '><h3>".$status_messages->error[301]."</h3></div>";
                            }
                        ?>
                    </div>
                </div>
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
                            // Block of code to print pagination

                            // Total number of users (companies) returned from the query
                            $total_user = $total_users;

                            // Calculate the total number of pages based on items per page
                            $total_pages = ceil($total_user / $number);

                            // Get the current page from the query variable, default to 1
                            $current_page = max(1, get_query_var('paged'));

                            // A large unlikely integer used for pagination base replacement
                            $big = 999999999;

                            // Output the pagination links using WordPress paginate_links function
                            echo paginate_links(array(
                                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))), // Pagination URL structure
                                'format'    => '?paged=%#%',            // Format of the pagination link
                                'current'   => $current_page,           // Current page number
                                'total'     => $total_pages,            // Total number of pages
                                'prev_text' => __('&laquo; Previous'),  // Text for the previous page link
                                'next_text' => __('Next &raquo;'),      // Text for the next page link
                            ));
                      ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End company card Section -->
    </div>
    <!-- End Row -->
</div>
<!-- End Container -->
<style> 
    .searchLoctions{ width:100%; }
    .vibebp_groups_directory_main{margin-left:20px !important;}
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function() {
  jQuery('#state-dropdown').on('change', function() {
    var state_id = this.value;
    jQuery.ajax({
        url : '<?= admin_url( 'admin-ajax.php' ) ?>',
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