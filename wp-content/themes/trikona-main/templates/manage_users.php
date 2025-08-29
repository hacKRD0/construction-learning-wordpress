<?php 
	/**
	 * Template Name: Manage Users
	 */
	get_header();

	global $wpdb, $status_messages, $trikona_obj, $wp_roles;

	$current_user = wp_get_current_user();

	$current_user_roles = $current_user->roles;

	if (is_super_admin($current_user->ID)) {
		$current_user_roles = ['administrator'];
	}
	$allowed_roles = array_intersect($trikona_obj->manage_groups_allowed_roles, $current_user_roles);

	if (empty($current_user) || empty($allowed_roles)){
?>
	<div class="card user-card-full mt-4 text-center">
	    <div class="row m-l-0 m-r-0">
	        <div class="col-sm-12">
	            <div class="card-block">
	                <?= $status_messages->error[5] ?>
	            </div>
	        </div>
	    </div>
	</div>
<?php } else {
	$states = WC()->countries->get_states( 'IN' );
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$users_per_page = 20;
	$exclude_roles = ['administrator'];
	$args = [
	  	'role__not_in' => $exclude_roles,
	  	'order'=> 'ASC',
	  	'orderby'=> 'ID',
	  	'number' => $users_per_page,
	  	'paged'=> $paged,
	  	'count_total' => true,
	  	'is_custom_user_query' => true
	];

	if (isset($_GET['filter_role']) && !empty($_GET['filter_role'])) {
		unset($args['role__not_in']);
		$args['role__in'] = [sanitize_text_field($_GET['filter_role'])];
	}
	if (isset($_GET['membership_level']) && !empty($_GET['membership_level'])) {
		$level_id = intval($_GET['membership_level']);
		$member_ids = $wpdb->get_col( $wpdb->prepare(
	        "SELECT user_id FROM {$wpdb->prefix}pmpro_memberships_users
	         WHERE membership_id = %d AND status = 'active'", $level_id
	    ));

	    if (!empty($member_ids)) {
	        $args['include'] = $member_ids;
	    } else {
	        $args['include'] = [0]; // No match, return no users
	    }
	}
	// Initialize meta_query with AND relation if not exists
	if (!isset($args['meta_query'])) {
	    $args['meta_query'] = ['relation' => 'AND'];
	}
	if (isset($_GET['filter_state']) && !empty($_GET['filter_state'])) {
		$filter_state = sanitize_text_field($_GET['filter_state']);

	    // Initialize meta_query if not already set
	    if (!isset($args['meta_query'])) {
	        $args['meta_query'] = ['relation' => 'AND'];
	    }

	    $args['meta_query'][] = [
	        'key'     => 'billing_state',
	        'value'   => $filter_state,
	        'compare' => '='
	    ];
	}
	if (isset($_GET['filter_city']) && !empty($_GET['filter_city'])) {
	    $filter_city = sanitize_text_field($_GET['filter_city']);
	    if (!isset($args['meta_query'])) {
	        $args['meta_query'] = ['relation' => 'AND'];
	    }

	    $args['meta_query'][] = [
	        'key'     => 'billing_city',
	        'value'   => $filter_city,
	        'compare' => '='
	    ];
	}
	if (isset($_GET['search_user_title']) && !empty($_GET['search_user_title'])) {
		$search_term = sanitize_text_field($_GET['search_user_title']);

		// Add an OR relation meta_query inside the main AND relation
	    $args['meta_query'][] = [
	        'relation' => 'OR',
	        [
	            'key'     => 'first_name',
	            'value'   => $search_term,
	            'compare' => 'LIKE',
	        ],
	        [
	            'key'     => 'last_name',
	            'value'   => $search_term,
	            'compare' => 'LIKE',
	        ],
	    ];
	}
	if (isset($_GET['filter_group_manager']) && !empty($_GET['filter_group_manager'])) {
		$filter_group_manager = $_GET['filter_group_manager'];

	    // Initialize meta_query if not already set
	    if (!isset($args['meta_query'])) {
	        $args['meta_query'] = ['relation' => 'AND'];
	    }

	    $args['meta_query'][] = [
	        'key'     => 'group_manager',
	        'value'   => $filter_group_manager,
	        'compare' => '='
	    ];
	}
	// Get users
	$user_query = new WP_User_Query($args);

	// Total users
	$total_users = $user_query->get_total();

	// Total pages
	$total_pages = ceil($total_users / $users_per_page);

	$users = $user_query->get_results();

	$roles = wp_list_pluck(array_diff_key( $wp_roles->roles, array( 'administrator' => '' ) ), 'name');

	$memberships = [];
	$levels = pmpro_getAllLevels( true );

	if (!empty($levels)) {
		$memberships = array_map( function( $level ) {
		    return array(
		        'id'          => $level->id,
		        'name'        => $level->name,
		    );
		}, $levels );
	}

	$group_manager_args = [
	    'role__in'    => ['group_manager']
	];
	$group_managers = get_users( $group_manager_args );
?>
	<style type="text/css">
		.table input[type="checkbox"] {
			box-shadow: none !important;
			height: 16px;
		}
		.loader{
		  	position: fixed;
		  	/*left: 0px;
		  	top: 0px;
		  	width: 100%;
		  	height: 100%;*/
		  	left: 50%;
		  	top: 50%;
		  	width: 10%;
		  	height: 10%;
		  	z-index: 9999;
		  	background: url('//upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Phi_fenomeni.gif/50px-Phi_fenomeni.gif') 50% 50% no-repeat rgb(249,249,249);
		}
		.filter-col {
		    background-color: #f2f2f2;
		    padding: 20px;
		}
		.user-roles-list {
			list-style: disc;
		}
		.row.cs-pagination {
		  background-color: #f6f6f6;
		  padding: 6px;
		  margin: 0px 10px;
		  border-radius: 5px;
		  box-shadow: 0 0 2px #8080804d;
		  align-items: center;
		}
		.row.cs-pagination div#pagination {
		  text-align: right;
		  float: right;
		}
		.hint-text {
		  font-size: 13px;
		}
		div#pagination a {
		  background-color: #afafaf;
		  margin: 3px;
		  padding: 0px 10px;
		  color: #fff;
		}
		div#pagination .page-numbers.current {
		  background-color: #2b5971;
		  margin: 3px;
		  padding: 0px 10px;
		  color: #fff;
		}
		.pagination {
		  margin: 0 0 5px;
		}
		div#pagination a:hover {
			text-decoration: none;
		}
		.custom_filter {
		    margin-bottom: 25px;
		    padding: 0px 10px !important;
		}
		.btn-color-2 {
		    background: #1b3b4c !important;
		    color: #fff !important;
		}
		.cstm-btn {
		    width: 100%;
		    font-size: 16px;
		    font-weight: 600;
		}
	</style>
	<div class="loader d-none"></div>

	<div class="container-fluid" style="padding: 50px 0px;">
	    <div class="row w-100">
	    	<!-- Begin Filter Section -->
	    	<div class="custom-wraper col-md-3 filter-col">
	    	    <form method="GET" name="filter_users">
	    	    	<div class="custom_filters">
	    	    		<!-- Search Filter -->
	    	    		<div class="custom_filter">
	    	    		    <span>Search by keywords</span>
	    	    		    <div class="custom_filter_values">
	    	    		        <div class="custom_filter_values">
	    	    		            <input type="text" class="form-control" name="search_user_title" id="search_user_title" placeholder="Search" style="color: #666;height: 40px;padding-left: 8px;" value="<?php if(isset($_GET['search_user_title']) && !empty($_GET['search_user_title'])) { echo $_GET['search_user_title']; } ?>">
	    	    		        </div>
	    	    		    </div>
	    	    		</div>

	    	    		<div class="custom_filter">
	    	    		    <span>Roles</span>
		    	    		<div class="custom_filter_values">
		    	    		    <div class="custom_filter_values">
		    	    		        <div class="">
		    	    		            <select style="width:100%;" class="form-control" id="filter_role" name="filter_role">
		    	    		                <option value="">Select role</option>
		    	    		                <?php  foreach ($roles as $role_id => $role) { ?>
		    	    		                	<option value="<?= $role_id ?>" <?php if(isset($_GET['filter_role']) && $_GET['filter_role'] == $role_id) { echo "selected=selected"; } ?>><?= $role ?></option>
		    	    		                <?php } ?>
		    	    		            </select>
		    	    		        </div>
		    	    		    </div>
		    	    		</div>
		    	    	</div>

		    	    	<div class="custom_filter">
	    	    		    <span>Membership Level</span>
		    	    		<div class="custom_filter_values">
		    	    		    <div class="custom_filter_values">
		    	    		        <div class="">
		    	    		            <select style="width:100%;" class="form-control" id="membership_level" name="membership_level">
		    	    		                <option value="">Select membership Level</option>
		    	    		                <?php  foreach ($memberships as $membership) { ?>
		    	    		                	<option value="<?= $membership['id'] ?>" <?php if(isset($_GET['membership_level']) && $_GET['membership_level'] == $membership['id']) { echo "selected=selected"; } ?>><?= $membership['name'] ?></option>
		    	    		                <?php } ?>
		    	    		            </select>
		    	    		        </div>
		    	    		    </div>
		    	    		</div>
		    	    	</div>

	    	    		<?php if (in_array('administrator', $allowed_roles)) { ?>
	    	    		<div class="custom_filter">
	    	    		    <span>Group Manager</span>
		    	    		<div class="custom_filter_values">
		    	    		    <div class="custom_filter_values">
		    	    		        <div class="">
		    	    		            <select style="width:100%;" class="form-control" id="filter_group_manager" name="filter_group_manager">
		    	    		                <option value="">Select Group Manager</option>
		    	    		                <?php  foreach ($group_managers as $group_manager) { ?>
		    	    		                	<option value="<?= $group_manager->ID ?>" <?php if(isset($_GET['filter_group_manager']) && $_GET['filter_group_manager'] == $group_manager->ID) { echo "selected=selected"; } ?>><?= ucfirst($group_manager->data->display_name) ?></option>
		    	    		                <?php } ?>
		    	    		            </select>
		    	    		        </div>
		    	    		    </div>
		    	    		</div>
		    	    	</div>
			    	    <?php } ?>

	    	    		<div class="custom_filter">
	    	    		    <span>Locations</span>
	    	    		    <div class="custom_filter_values">
	    	    		        <div class="custom_filter_values">
	    	    		            <div class="">
	    	    		                <select style="width:100%;" class="form-control" id="state-dropdown" name="filter_state">
	    	    		                    <option value="">Select State</option>
	    	    		                    <?php  foreach ($states as $key => $state) { ?>
	    	    		                    <option value="<?= $key ?>" <?php if(isset($_GET['filter_state']) && $_GET['filter_state'] == $key) echo 'selected = "selected"'; ?>><?= $state ?></option>
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
	    	    		                <select style="width:100%" class="form-control" id="city-dropdown" name="filter_city">
	    	    		                    <option value="">Select City</option>
	    	    		                </select>
	    	    		            </div>
	    	    		        </div>
	    	    		    </div>
	    	    		</div>

	    	    		<div class="custom_filter">
	    	    		    <button type="submit" class="btn cstm-btn btn-color-1 mb-3" name="group_filter_btn">Filter</button>
	    	    		    <a href="<?= home_url() ?>/manage-users/"class="btn cstm-btn btn-color-2">Clear</a>
	    	    		</div>
	    	    	</div>
	    	    </form>
	    	</div>

	    	<div class="col-md-9">
	    		<form method="POST" name="assign_manager">
	    			<input type="hidden" name="action" value="assign_user_manager">

		    		<div class="table-responsive">
	    		        <div class="table-wrapper">
	    		        	<div class="table-title">
				                <div class="d-flex">
				                	<div class="col-md-3">
				                		<div class="custom_filter_values">
				                		    <div class="custom_filter_values">
				                		        <div class="">
				                		            <select style="width:100%;" class="form-control" id="group_manager" name="group_manager">
				                		                <option value="">Select Group Manager</option>
				                		                <?php  foreach ($group_managers as $group_manager) { ?>
				                		                	<option value="<?= $group_manager->ID ?>" <?php if(isset($_GET['group_manager']) && $_GET['group_manager'] == $group_manager->ID) { echo "selected=selected"; } ?>><?= ucfirst($group_manager->data->display_name) ?></option>
				                		                <?php } ?>
				                		            </select>
				                		        </div>
				                		    </div>
				                		</div>
				                	</div>
				                	<div class="col-md-2">
				                		<button type="submit" class="btn cstm-btn btn-color-1 mb-3 mt-0" name="btn_asign_group_manager">Assign</button>
				                	</div>
				                </div>
				            </div>
	    		        </div>
	    		    </div>

	    		    <div class="col-md-12 alert alert-danger assign-manger-error d-none"></div>
	    		    <div class="col-md-12 alert alert-success assign-manger-success d-none"></div>
		    		<table class="table table-striped table-hover table-responsive">
		    			<thead>
		    			    <tr>
		    			    	<th width="5%"><input type="checkbox" name="checkAll" class="checkAll"></th>
		    			    	<th width="5%">User ID</th>
		    			    	<th width="20%">User Name</th>
		    			    	<th width="10%">User Role</th>
		    			    	<th width="15%">State</th>
		    			    	<th width="15%">City</th>
		    			    	<th width="10%">Membership Level</th>
		    			    	<th width="15%">Group Manager</th>
		    			    	<th width="10%">Edit/View</th>
		    			    </tr>
		    			</thead>
		    			<tbody>
		    				<?php if(!empty($users)){ ?>
		    					<?php
		    						foreach ($users as $user) {
		    							$first_name = get_user_meta($user->ID, 'first_name', true);
		    							$last_name  = get_user_meta($user->ID, 'last_name', true);
		    							$user_name = [];
		    							if (!empty($first_name))
		    								$user_name[] = $first_name;
		    							if (!empty($last_name))
		    								$user_name[] = $last_name;

		    							$level_names = [];
		    							// Get membership levels for this user
		    							$levels = pmpro_getMembershipLevelsForUser($user->ID);

		    							if (!empty($levels)) {
			    							$level_names = array_map(function($level) {
			    							    return $level->name;
			    							}, $levels);
		    							}

		    							$default_profile = get_user_meta( $user->ID, 'default_profile', true );
		    							$edit_url = home_url().'/user-dashboard/?id='.base64_encode($user->ID);
		    							$view_url = '#';

		    							if (!empty($default_profile)) {
		    								$view_url = home_url().'/candidates/member/?user='.base64_encode($user->ID);
		    							} else {
		    								$view_url = esc_url(bp_core_get_user_domain($user->ID));
		    							}

		    							$state_name = '';
		    							$city = get_user_meta($user->ID, 'billing_city', true);
		    							$state_code = get_user_meta($user->ID, 'billing_state', true);
		    							$country_code = get_user_meta($user->ID, 'billing_country', true);
		    							$country_code = $country_code ? $country_code : 'IN';

		    							if (!empty($state_code)) {
		    								$states = WC()->countries->get_states( $country_code );
		    								$state_name = isset( $states[ $state_code ] ) ? $states[ $state_code ] : $state_code;
		    							}

		    							$group_manager_name = '';
		    							$group_manger_id = get_user_meta($user->ID, 'group_manager', true);

		    							if (!empty($group_manger_id)) {
		    								$group_manager_info = get_user_by( 'ID', $group_manger_id );
		    								if (!empty($group_manager_info)) {
		    									$group_manager_name = ucfirst($group_manager_info->data->display_name);
		    								}
		    							}
		    					?>
			    					<tr>
			    						<td>
			    							<input type="checkbox" name="user_ids[]" class="user-checkbox" value="<?= $user->ID; ?>">
			    						</td>
			    						<td><?= $user->ID ?></td>
			    						<td><?= !empty($user_name) ? implode(" ", $user_name) : "-" ?></td>
			    						<td>
			    							<ul class="pl-0 user-roles-list">
			    								<?= "<li>".implode("</li><li>", array_map('ucfirst', $user->roles))."</li>" ?>
			    							</ul>
			    						</td>
			    						<td><?= $state_name ?></td>
			    						<td><?= $city ?></td>
			    						<td>
			    							<?php if(!empty($level_names)){ ?>
			    								<ul class="pl-0 user-roles-list">
			    									<?= "<li>".implode("</li><li>", array_map('ucfirst', $level_names))."</li>" ?>
			    								</ul>
			    							<?php } else { ?>
			    								No Membership
			    							<?php } ?>
			    						</td>
			    						<td><?= !empty($group_manager_name) ? $group_manager_name : '-' ?></td>
			    						<td class="text-center">
			                	    		<a class="btn1 pr-3" target="_blank" href="<?= $edit_url ?>"> <i class="fa-solid fa-pencil-alt"></i></a>
	                                        <a class="btn1" target="_blank" href="<?= $view_url ?>"> <i class="fa-solid fa-eye"></i></a>
			    						</td>
			    					</tr>
		    					<?php } ?>
		    				<?php } else { ?>
		    					<tr>
		    						<th colspan="9" class="text-center">No user data found.</th>
		    					</tr>
		    				<?php } ?>
		    			</tbody>
		    		</table>
		    	</form>
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
	    		              $total_pages = $total_pages;
	    		              $current_page = max(1, get_query_var('paged'));
	    		              $big = 999999999; // need an unlikely integer
	    		              echo paginate_links(
	    		                array(  
	    		                  'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	    		                  'format' => '?paged=%#%',  
	    		                  'current' => $current_page,  
	    		                  'total' => $total_pages,  
	    		                  'prev_text' => __('&laquo; Previous'), // text for previous page
	    		                  'next_text' => __('Next &raquo;'), // text for next page
	    		                )
	    		              );
	    		          ?>
	    		        </div>
	    		    </div>
	    		</div>
	    	</div>
	   	</div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			let state_code = jQuery('#state-dropdown option:selected').val();
			if (state_code != undefined && state_code.length > 0) {
				setTimeout(function(){
					jQuery('#state-dropdown').val(state_code).trigger('change');
				}, 1000)
			}
		  	jQuery('#state-dropdown').on('change', function() {
			    var state_id = this.value;
			    jQuery.ajax({
			        url : '<?= admin_url( 'admin-ajax.php' ) ?>',
			        type: "POST",
			        data: {action: "filter_city_by_state",state_id: state_id },
			        cache: false,
			        success: function(result){
			          jQuery("#city-dropdown").html(result);
			        }
			    });
		  	});

		  	jQuery('.checkAll').click(function(){
		  		jQuery('.user-checkbox').prop('checked', jQuery(this).prop("checked"));
		  	});

	  	  	jQuery('form[name=assign_manager]').submit(function(e){
	  	  		e.preventDefault();
	  	  		var data = jQuery('form[name=assign_manager]').serialize();
	  	  		jQuery('.loader').removeClass('d-none');

	  	  		jQuery.ajax({
	  	  		    url : '<?= admin_url( 'admin-ajax.php' ) ?>',
	  	  		    type: "POST",
	  	  		    data: data,
	  	  		    dataType: "json",
	  	  		    cache: false,
	  	  		    success: function(response){
	  	  		    	if (response.success) {
	  	  		    		jQuery('.assign-manger-success').html(response.message);
	  	  		    		jQuery('.assign-manger-success').removeClass('d-none');
	  	  		    		jQuery('.loader').addClass('d-none');
	  	  		    		if (response.errors) {
	  	  		    			jQuery('.assign-manger-error').html(response.errors);
	  		  		    		jQuery('.assign-manger-error').removeClass('d-none');
	  	  		    		}
	  	  		    		setTimeout(function() {
	  	  		    		    window.location.reload();
	  	                    }, 3000);
	  	  		    	} else {
	  	  		    		jQuery('.assign-manger-error').html(response.message);
	  	  		    		jQuery('.assign-manger-error').removeClass('d-none');
	  	  		    		jQuery('.loader').addClass('d-none');
	  	  		    		setTimeout(function() {
	  	  		    		    jQuery('.assign-manger-error').addClass('d-none');
	  	                    }, 2500);
	  	  		    	}
	  	  		    }
	  	  		});
	  	  	});
		});
	</script>
<?php } ?>
<?php get_footer(); ?>