<?php
/**
 * Template Name: Candidate Directory 
 * This template is used to display the list of student/professional/instructor candidates. The display is dependent on the user role. Corporate role has main access baded on the corporate membership of the user.  
 *
 * @package Trikona
 * @subpackage Trikona
 * @since Trikona 1.0 - 17-APR-2024
 */
get_header(); 
global $wpdb,$bp, $trikona_obj, $jobs_obj;
// Custom field defined on page. This will pass 'student'/'professional'/'instructor'
$candidate_role = get_post_meta(get_the_ID(), 'candidate_role', TRUE) ? get_post_meta(get_the_ID(), 'candidate_role', TRUE) : "";
switch_to_blog( $trikona_obj->main_site_blog_id );

//  The PMPro membership ids of various user roles
$corporate_membership = $trikona_obj->corporate_membership;
$student_membership = $trikona_obj->student_membership;
$professional_membership = $trikona_obj->professional_membership;
$instructor_membership = $trikona_obj->instructor_membership;

// Check if user is super admin and pass the administrator role manually. (WP is not defining a defined role for super admin)
$current_user = wp_get_current_user();
if (is_super_admin($current_user->ID)) {
	$userRolesChk = ['administrator'];
} else {
	$userRolesChk =  $current_user->roles;
}

// The roles allowed access to the candidate_directory
$allowed_roles = $trikona_obj->candidate_directory_allowed_roles;

// The table in which the memberships are stored
$tabmemberships = $wpdb->prefix . "pmpro_memberships_users";

$common_roles = array_intersect($allowed_roles, $userRolesChk);
$current_user_plans = [];
// Getting active membership plan of the current user if the current user is corporate
if (in_array($trikona_obj->corporate_role, $userRolesChk)) {
    $current_user_plans = $trikona_obj->getMembershipPlans(['user_id' => $current_user->ID, 'status' => 'active'], $is_single_record = true);
}


// Check if the current user has an allowed role and if the allowed role is 'corporate' check for the specific membership ids. Error message if False.  
if (empty($common_roles) || (in_array($trikona_obj->corporate_role, $userRolesChk) && !in_array($current_user_plans->membership_id, $trikona_obj->corporate_membership))) { ?>
	<div class="container-fluid cs-directory-page">
		<div class="card">
			<div class="card-block">
				<h3>You are not authorized to access this page.</h3>
			</div>
		</div>
	</div>
<?php } else {
	$user_ids = [];

	// declaring the visibility conditions based on current user membership and the candidate memberships 
	if (in_array($trikona_obj->corporate_role, $userRolesChk)) {
		if ($current_user_plans->membership_id == $trikona_obj->corporate_basic_mem_id) {
			$student_membership = $trikona_obj->coporate_basic_stud_mem_ids;
			$professional_membership = $trikona_obj->coporate_basic_prof_mem_ids;
			$instructor_membership = $trikona_obj->coporate_basic_instructor_mem_ids;
		} else if ($current_user_plans->membership_id == $trikona_obj->corporate_prime_mem_id) {
			$student_membership = $trikona_obj->coporate_prime_stud_mem_ids;
			$professional_membership = $trikona_obj->coporate_prime_prof_mem_ids;
			$instructor_membership = $trikona_obj->coporate_prime_instructor_mem_ids;
		} else if ($current_user_plans->membership_id == $trikona_obj->corporate_elite_mem_id) {
			$student_membership = $trikona_obj->coporate_elite_stud_mem_ids;
			$professional_membership = $trikona_obj->coporate_elite_prof_mem_ids;
			$instructor_membership = $trikona_obj->coporate_elite_instructor_mem_ids;
		}
		// Based on above visibility criteria, get the candidate ids. 
		$user_membership_plans = [];
		if ($candidate_role == $trikona_obj->student_role) {
			$user_membership_plans = $trikona_obj->getMembershipPlans(['membership_ids' => implode(",", $student_membership)]);
		} else if ($candidate_role == $trikona_obj->professional_role) {
			$user_membership_plans = $trikona_obj->getMembershipPlans(['membership_ids' => implode(",", $professional_membership)]);
		} else if ($candidate_role == $trikona_obj->instructor_role) {
			$user_membership_plans = $trikona_obj->getMembershipPlans(['membership_ids' => implode(",", $instructor_membership)]);
		}
		// pass the candidate ids to an array
		if (!empty($user_membership_plans)) {
			$user_ids = array_column($user_membership_plans, 'user_id');
		}
	}

	// Check if the user id is not empty and Set the filter in a union mode. Data will be shown for any of the filter criteria as a union.  
	if(!empty($current_user->ID)){
		
?>
		<div class="container-fluid cs-directory-page">
		    <div class="row w-100" style="padding:50px 0px">
		        <aside class="col-md-3 filter-col">
		            <form name="directory-filterpage" method="GET">
		                <div class="card">
		                	<article class="filter-group">
		                	    <header class="card-header">
		                	        <a href="#" data-toggle="collapse" data-target="#collapse_1" aria-expanded="true" class="">
		                	            <i class="icon-control fa fa-chevron-down"></i>
		                	            <h6 class="title">Search</h6>
		                	        </a>
		                	    </header>
		                	    <div class="filter-content collapse show" id="collapse_1" style="">
		                	        <div class="card-body">
		                	            <div class="input-group">
		                	                <input type="text" class="form-control" name="seachmembers" placeholder="Search" value="<?= $_GET['seachmembers']; ?>">
		                	                <div class="input-group-append">
		                	                    <button class="btn btn-light" type="submit">Search</button>
		                	                </div>
		                	            </div>
		                	        </div>
		                	    </div>
		                	</article>
                            
		                	<?php
		                		if ($candidate_role == $trikona_obj->professional_role || $candidate_role == $trikona_obj->instructor_role) { // hide student fields
		                			$hide_fields = [$trikona_obj->student_skills_field_id, $trikona_obj->total_year_of_study_field_id, $trikona_obj->current_Year_Of_study_field_id];
		                			// 72-Student skills, 49-Total year of study, 54-Current year of study
		                		} else { // hide professional fields
		                			$hide_fields = [$trikona_obj->professional_skills_field_id, $trikona_obj->vertical_field_id]; // 91-Professional skills, 78-Vertical
		                		}

		                		// Retrieve the list of filter fields from the $trikona_obj object,
		                		// optionally excluding any fields specified in the $hide_fields array.
		                		$filters = $trikona_obj->get_filter_fields($hide_fields);

		                		if(!empty($filters)){
		                			// Loop through each filter field retrieved earlier
		                			foreach($filters as $filter){

		                				// Get sub-profile fields (children) associated with the current filter field
		                				$results_fls = $trikona_obj->get_bp_xprofile_fields(['parent_id' => $filter->id]);

		                				// Retrieve 'filtersData' from the GET request, or set to empty string if not present
		                				$filtersData = isset($_GET['filtersData']) ? $_GET['filtersData'] : "";

		                				// Proceed only if 'filtersData' is not empty
		                				if(!empty($filtersData)){
		                					// Initialize the meta query array with a logical 'AND' relation
		                				    $meta = ['relation' => 'AND'];

		                				    // Loop through each filter key/value pair from the request
		                				    foreach ($filtersData as $key => $value) { 
		                				        $fieldValues = [];

		                				        // Split the filter string into key and value using '|' as delimiter
		                				      	$array = explode('|', $value);

		                				      	// If the first element (field name) is present
		                				      	if (isset($array[0]) && !empty($array[0])) {

		                				      		// Normalize certain field names for consistency
		                				      		if ($array[0] == 'Key Qualification'){
		                				      			$array[0] = 'Highest Education';
		                				      		}

		                				      		// Special case: convert course title to course ID
		                				      		if ($array[0] == 'College Course') {
		                				      			$array[0] = 'course-id';
		                				      			$array[1] = $trikona_obj->get_student_course_id_by_title($array[1]);
		                				      		}

		                				      		// Add the field value to the list
		                				      	   	$fieldValues[]= $array[1];

		                				      	   	// Append the condition to the meta query array
		                				            array_push($meta, [
		                				              	'key' => $array[0],
		                				              	'value' => $fieldValues ,
		                				              	'compare' => '%like%'
		                				            ]);
		                				      	}
		                				  	}
		                				} ?>
	            				  	<article class="filter-group">
		            					<header class="card-header">
		            						<a href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_<?= $filter->id ?>" aria-expanded="true" class=""><i class="icon-control fa fa-chevron-down"></i><h6 class="title"><?= $filter->name ?></h6></a>
		            					</header>
		            					<div class="filter-content collapse show" id="collapse_<?= $filter->id ?>" style="">
		            						<div class="card-body">
			            							<select class="filter-data-select" name="filtersData[]"  multiple="multiple" data-placeholder="<?= $filter->name ?>">
			            			                <?php

			            			                // Loop through each child field of the current filter
			            			                foreach ($results_fls as $key => $field) {
			            			                	// Initialize $selected variable
	            									  	$selected= '';

	            									  	// Check if 'filtersData' is present in the GET request
			            			                  	if(!empty($_GET['filtersData'])){

			            			                  		// Construct the value in the same format as used in the filter processing
			            			                  		// and check if it's selected by the user
			            			                       	if (in_array($filter->name.'|'.$field->name, $_GET['filtersData'])) {
			            										$selected= 'selected="selected"'; // Mark the option as selected
			            									}
			            								}
			            			                ?>
			            			                	<!-- Render the <option> element for the <select> input -->
			            			                    <option value="<?= $filter->name ?>|<?= $field->name ?>" <?= $selected ?>><?= $field->name ?></option>
			            			                <?php } ?>
			            			            </select>
		            						</div>
		            					</div>
	            					</article>	
		                		<?php
		                			}
		                		}
		                		restore_current_blog();
		                	?>
		                </div>

		                <div class="custom_filter">
		                	<button type="submit"  class="btn cstm-btn btn-color-1">Filter</button>
	                		<?php if(isset($candidate_role) && $candidate_role == $trikona_obj->professional_role ){ ?>
	            	        	<a href="<?= home_url();?>/candidates/professional-directory/" class="btn cstm-btn btn-color-2">Clear Filters</a>
	            	        <?php } else if(isset($candidate_role) && $candidate_role == $trikona_obj->instructor_role ){ ?>
	            	        	<a href="<?= home_url();?>/candidates/instructor-directory/" class="btn cstm-btn btn-color-2">Clear Filters</a>
	            	        <?php }else{ ?>
	            	         	<a href="<?= home_url();?>/candidates/students-directory/" class="btn cstm-btn btn-color-2">Clear Filters</a>
	            	        <?php } ?>
		                </div>
		            </form>
		       	</aside>

		       	<div class="col-md-9">
		       		<div class="candidate_directory_main">
		       			<?php
		       				// Set how many candidates to display per page
		       				$posts_per_page = 24;

		       				// Get current pagination page number, default to 1
		       				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

		       				// Calculate the offset based on current page
		       				if($paged == 1){
		       				  	$offset = 0;  
		       				} else {
		       				   $offset = ($paged-1) * $posts_per_page;
		       				}

		       				// If filter data is submitted, build the query with meta filters
		       				if(!empty($filtersData)){
		       				 	$args = [
       				 	            'role__in'     => [$candidate_role],   // Filter by user role
       				 	            'number'       => $posts_per_page,     // Limit number of users per page
       				 	            'offset'       => $offset,             // Skip users based on current page
       				 	            'orderby'      => 'registered',        // Order by registration date
       				 	            'order'        => 'DESC',              // Descending order
       				 	            'meta_query'   => ['relation' => 'AND', $meta] // Apply meta filters from earlier
       				 	        ];
		       				} else {
		       					// If no filters, build a basic user query
       					        $args = [
       					            'role__in'  => [$candidate_role],
       					            'number'    => $posts_per_page,
       					            'offset'    => $offset,
       					            'orderby'   => 'registered',
       					            'order'     => 'DESC'
       					        ];
		       				}

		       				// If specific user IDs are already known (perhaps from some logic earlier), include only them
		       				if (isset($user_ids) && !empty($user_ids)) {
		       					$args['include'] = $user_ids;

		       					// When including specific users, ordering by registration may not be needed
		       					unset($args['orderby']);
		       					unset($args['order']);
		       				}

		       				// If a search term is provided via GET, override all above and do a keyword-based search
		       				if(isset($_GET['seachmembers']) && !empty($_GET['seachmembers'])){
		       					$search_term = $_GET['seachmembers'];
			       				$args = array (
			       				   	'role__in'  => [$candidate_role], 
			       				    'order' 	=> 'ASC',

			       				    'orderby' => 'display_name',
			       				    'meta_query' => [
			       				        'relation' => 'OR', // Match any of the below fields
			       				        [
			       				            'key'     => 'first_name',
			       				            'value'   => $search_term,
			       				            'compare' => '%LIKE%'
			       				        ],
			       				        [
			       				            'key'     => 'last_name',
			       				            'value'   => $search_term,
			       				            'compare' => '%LIKE%'
			       				        ],
			       				        [
			       				            'key' => 'Highest Education',
			       				            'value' => $search_term ,
			       				            'compare' => '%LIKE%'
			       				        ],
			       				        [
			       				            'key' => 'Vertical',
			       				            'value' => $search_term ,
			       				            'compare' => '%LIKE%'
			       				        ],
			       				        [
			       				            'key' => 'Professional Skills',
			       				            'value' => $search_term ,
			       				            'compare' => '%LIKE%'
			       				        ],
			       				        [
			       				            'key' => 'designation_current',
			       				            'value' => $search_term ,
			       				            'compare' => '%LIKE%'
			       				        ],
			       				    ]
			       				);
		       				}

		       				// Switch to the main blog (useful in multisite setups)
		       				switch_to_blog( $trikona_obj->main_site_blog_id );

		       				// Run the user query with the constructed arguments
		       				$user_query = new WP_User_Query( $args );

		       				// Get the resulting user objects
		       				$users = $user_query->get_results();

		       				// Restore back to the current blog if switched earlier
		       				restore_current_blog();

		       				// Get total number of users found (for pagination or display)
		       				$total_users = $user_query->get_total();

		       				// Check if there are users to display
		       				if ( ! empty( $user_query->results ) ) {
		       			?>
		       				<div class="card team-boxed" id="member_data">
		       				    <div class="row people">
		       				    	<?php
		       				    		// Loop through each user returned by WP_User_Query
		       				    		foreach ($users as $key => $bpMembers) {
		       				    			// Restore to the original blog in case we're still switched (though this should only need to be done once outside the loop)
		       				    		    restore_current_blog();

		       				    		    // Get full user data by user ID
		       				    		    $new_user 	= get_userdata(  $bpMembers->ID );

		       				    		    // Extract user details
		       				    		    $first_name = $new_user->first_name;
		       				    		    $last_name 	= $new_user->last_name;

		       				    		     // Attempt to get user avatar (assuming 'avatar' is a custom property or added via plugin)
		       				    		    $user_avatar = $new_user->avatar;

		       				    		    $courses = $jobs_obj->getStudentCourses(['user_id' => $bpMembers->ID]);

		       				    		    // Include a template file to render the candidate card UI
	   				    		            include get_stylesheet_directory().'/layouts/candidate-card.php';
		       				    		}
		       				    	?>
		       				    </div>
		       				</div>

		       				<div class="row cs-pagination">
		   				        <div class="col-md-6">
		   				            <div id="hint" class="hint-text">
		   				                Total :  <b><?php echo $total_users; ?></b> entries
		   				            </div>
		   				        </div>
		       				    <div class="col-md-6">
			       					<div id="pagination" class="pagination">
			       						<?php
			       							// Total number of users found by the query
			       				         	$total_user = $total_users;

			       				         	// Calculate the total number of pages based on posts per page
			       				            $total_pages = ceil($total_user / $posts_per_page);

			       				            // Get the current page number from the query var 'paged'; default to 1 if not set or invalid
			       				            $current_page = max(1, get_query_var('paged'));

			       				            // Define a large number placeholder used in the base URL for pagination links
		       				               	$big = 999999999;

		       				               	// Output pagination links
		       				                echo paginate_links(array(
	       				                        // Base URL for pagination. Replace $big with '%#%' which is replaced by page number
	       				                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),

	       				                        // Format for the page number query variable
	       				                        'format' => '?paged=%#%',

	       				                        // Current page number
	       				                        'current' => $current_page,

	       				                        // Total number of pages available
	       				                        'total' => $total_pages,

	       				                        // Text for the "previous page" link
	       				                        'prev_text' => __('&laquo; Previous'),

	       				                        // Text for the "next page" link
	       				                        'next_text' => __('Next &raquo;'),
	       				                    ));
			       				        ?>
			       					</div>             
		       					</div>
		       				</div>
		       			<?php } else { ?>
		       				<div class="alert alert-warning alert-dismissible fade show">
		       				    <strong>Warning!</strong> <?= $status_messages->error[301] ?>
		       				</div>
		       			<?php } ?>
		       		</div>
		       	</div>
		    </div>
		</div>
	<?php } else { ?>
		<div class="container-fluid cs-directory-page">
		    <div class="card pt-4">
		        <div class="card-block text-center">
		            <h3><?= $status_messages->error[104] ?></h3>
		        </div>
		    </div>
		</div>
	<?php } ?>
<?php } ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style type="text/css">
	.select2-container {
		width: 100% !important;
	}
	.custom_filter .cstm-btn {
	    margin-bottom: 10px;
	}
	body {
		overflow-x: hidden;
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function() {
	    jQuery(".filter-data-select").select2({
	    	allowClear: true,
	        placeholder: function() {
	            jQuery(this).data('placeholder');
	        },
	       
	        theme: "classic",
	        width: 'resolve',
	    });
   	});

	function seachmembers() {
	    var roles = '<?php echo $atts['roles']?>';
	    var textval = jQuery("#seachmembers").val();
	    jQuery("#load-more").addClass('hide-more-btn');
	    jQuery.ajax({
	        type: "post",
	        dataType: "html",
	        url: '<?php echo admin_url( 'admin-ajax.php' );?>',
	        data: {
	            action: "search_members_directory_filter",
	            data: textval,
	            roles: roles,
	            member_ship: '<?php echo $List;?>'
	        },
	        success: function(response) {
	            jQuery("#member_data").html(response)
	            if (response.type == "success") {
	                jQuery("#member_data").html(response.vote_count)
	            } else {
	                //alert("Your vote could not be added")
	            }
	        }
	    })
	};
</script>
<?php get_footer(); ?>