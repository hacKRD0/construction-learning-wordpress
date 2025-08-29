<?php 
	/**
	 * Template Name: Manage Groups
	 */
	get_header();
	global $wpdb, $bp, $status_messages, $trikona_obj;
	switch_to_blog( 1 );
	$current_user = wp_get_current_user();

	$current_user_roles = $current_user->roles;

	if (is_super_admin($current_user->ID)) {
		$current_user_roles = ['administrator'];
	}
	$allowed_roles = array_intersect($trikona_obj->manage_groups_allowed_roles, $current_user_roles);
	restore_current_blog();

	if (empty($current_user) || empty($allowed_roles)){
?>
		<div class="card user-card-full mt-4 text-center">
		    <div class="row m-l-0 m-r-0">
		        <div class="col-sm-12">
		            <div class="card-block">
		                <?= $status_messages->error[104] ?>
		            </div>
		        </div>
		    </div>
		</div>
<?php } else{
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$number = 48;

	$args = array(
	  'order'=> 'ASC',
	  'orderby'=> 'name',
	  'per_page' => 48,
	  'page'=> $paged,
	);

	if (!in_array('administrator', $allowed_roles)) {
		$args['user_id'] = $current_user->ID;
	}

	if (isset($_GET['group_filter_btn'])) {
		if (isset($_GET['filter_group_type']) && !empty($_GET['filter_group_type'])) {
			$args['group_type'] = $_GET['filter_group_type'];
		}

		if (isset($_GET['search_group_title']) && !empty($_GET['search_group_title'])) {
			$args['search_terms'] = $_GET['search_group_title'];
		}

		if (isset($_GET['filter_group_manager']) && !empty($_GET['filter_group_manager'])) {
			$args['user_id'] = $_GET['filter_group_manager'];
		}

		$meta_query = [];

		$city_32469 = $trikona_obj->city_meta;
		$state_32470 = $trikona_obj->state_meta;
		$services_32461 = $trikona_obj->services_meta;
		$sectors_33927 = $trikona_obj->sectors_meta;
		$industries_type_33926 = $trikona_obj->industries_type_meta;

		if (isset($_GET['group_state']) && !empty($_GET['group_state'])) {
			$state = $trikona_obj->getStates(["id" => $_GET['group_state']]);

			if (!empty($state)) {
				$meta_query[] = [
					'key'      => $state_32470,
					'value'    => $state->name,
					'compare'  => 'LIKE'
				];
			}
		}
		if (isset($_GET['group_city']) && !empty($_GET['group_city'])) {
			$meta_query[] = [
				'key'      => $city_32469,
				'value'    => $_GET['group_city'],
				'compare'  => 'LIKE'
			];
		}
		if (isset($_GET['industries_type']) && !empty($_GET['industries_type'])) {
			$meta_query[] = [
				'key'      => $industries_type_33926,
				'value'    => $_GET['industries_type'],
				'compare'  => 'LIKE'
			];
		}
		if (isset($_GET['sectors']) && !empty($_GET['sectors'])) {
			$meta_query[] = [
				'key'      => $sectors_33927,
				'value'    => $_GET['sectors'],
				'compare'  => 'LIKE'
			];
		}
		if (isset($_GET['services']) && !empty($_GET['services'])) {
			$meta_query[] = [
				'key'      => $services_32461,
				'value'    => $_GET['services'],
				'compare'  => 'LIKE'
			];
		}

		if (!empty($meta_query)) {
			$args['meta_query'] = array_merge(['relation' => 'OR'], $meta_query);
		}
	}

	$groups =  groups_get_groups($args);
	$total_groups = $groups['total'];

	$group_types = bp_groups_get_group_types();
	switch_to_blog(1);
	$group_manager_args = [
	    'role__in'    => ['group_manager']
	];
	$group_managers = get_users( $group_manager_args );
	restore_current_blog();

	$statelist = $trikona_obj->getStates();

	$industriesType = get_post_meta($trikona_obj->industries_type_post_meta, 'group_fileds_optionName',true); // To get list of industries type
	$sectorArr = get_post_meta($trikona_obj->sectors_post_meta, 'group_fileds_optionName',true); // To get list of Sectors
	$services = get_post_meta($trikona_obj->services_type_post_meta, 'group_fileds_optionName',true); // To get list of Services
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
	</style>
	<div class="loader d-none"></div>
	<div class="container-fluid" style="padding: 50px 0px;">
	    <div class="row w-100">
	    	<!-- Begin Filter Section -->
	    	<div class="custom-wraper col-md-3 filter-col">
	    	    <form method="GET" name="filter_groups">
	    	    	<div class="custom_filters">
	    	    		<!-- Search Filter -->
	    	    		<div class="custom_filter mb-3">
	    	    		    <span>Search by keywords</span>
	    	    		    <div class="custom_filter_values">
	    	    		        <div class="custom_filter_values">
	    	    		            <input type="text" class="form-control" name="search_group_title" id="search_group_title" placeholder="Search" style="color: #666;height: 40px;padding-left: 8px;" value="<?php if(isset($_GET['search_group_title']) && !empty($_GET['search_group_title'])) { echo $_GET['search_group_title']; } ?>">
	    	    		        </div>
	    	    		    </div>
	    	    		</div>

	    	    		<div class="custom_filter">
	    	    		    <span>Group Type</span>
	    	    		    <div class="custom_filter_values">
	    	    		        <div class="custom_filter_values">
	    	    		            <div class="">
	    	    		                <select style="width:100%;" class="form-control" id="filter_group_type" name="filter_group_type">
	    	    		                    <option value="">All</option>
	    	    		                    <?php  foreach ($group_types as $key => $group_type) { ?>
	    	    		                    	<option value="<?= $key ?>" <?php if(isset($_GET['filter_group_type']) && $_GET['filter_group_type'] == $key) { echo "selected=selected"; } ?>><?= ucfirst($group_type) ?></option>
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
	    	    		                <select style="width:100%;" class="form-control" id="state-dropdown" name="group_state">
	    	    		                    <option value="">Select State</option>
	    	    		                    <?php  foreach ($statelist as $key => $state) { ?>
	    	    		                    <option value="<?= $state->id ?>" <?php if(isset($_GET['group_state']) && $_GET['group_state'] == $state->id) echo 'selected = "selected"'; ?>><?= $state->name ?></option>
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
	    	    		                <select style="width:100%" class="form-control" id="city-dropdown" name="group_city">
	    	    		                    <option value="">Select City</option>
	    	    		                </select>
	    	    		            </div>
	    	    		        </div>
	    	    		    </div>
	    	    		</div>

	    	    		<div class="custom_filter mb-3 company-group-filter d-none">
	    	    		    <span>Industries Type</span>
	    	    		    <div class="custom_filter_values">
	    	    		        <div class="custom_filter_values">
	    	    		            <div class="">
	    	    		                <select style="width:100%"  name="industries_type" id="industries_type" class="select filtergroups">
	    	    		                    <option value="">Select Industries Type</option>
	    	    		                    <?php foreach ($industriesType as $key => $industriesType_val) { ?>
	    	    		                    <option value="<?= $industriesType_val ?>" <?php if(isset($_GET['industries_type']) && $_GET['industries_type'] == $industriesType_val) echo 'selected = "selected"'; ?>><?= $industriesType_val ?></option>
	    	    		                    <?php  }  ?>
	    	    		                </select>
	    	    		            </div>
	    	    		        </div>
	    	    		    </div>
	    	    		</div>

	    	    		<div class="custom_filter mb-3 company-group-filter d-none">
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

	    	    		<div class="custom_filter mb-3 company-group-filter d-none">
	    	    		    <span>Services</span>
	    	    		    <div class="custom_filter_values">
	    	    		        <div class="custom_filter_values">
	    	    		            <div class="">
	    	    		                <select style="width:100%"  name="services" id="filterservices" class="select filterservices">
	    	    		                    <option value="">Select Services </option>
	    	    		                    <?php foreach ($services as $key => $service) { if(empty($service)) { continue; }?>
	    	    		                    <option value="<?= $service ?>" <?php if(isset($_GET['services']) && $_GET['services'] == $service) echo 'selected = "selected"'; ?>><?= $service ?></option>
	    	    		                    <?php  }  ?>
	    	    		                </select>
	    	    		            </div>
	    	    		        </div>
	    	    		    </div>
	    	    		</div>

	    	    		<div class="custom_filter">
	    	    		    <button type="submit" class="btn cstm-btn btn-color-1 mb-3" name="group_filter_btn">Filter</button>
	    	    		    <a href="<?= home_url() ?>/manage-groups/"class="btn cstm-btn btn-color-2">Clear</a>
	    	    		</div>
	    	    	</div>
	    	    </form>
	    	</div>

	    	<div class="col-md-9">
	    		<form method="POST" name="assign_manager">
	    			<input type="hidden" name="action" value="assign_group_manager">

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

<?php // Show the group data ?>

		    		<table class="table table-striped table-hover table-responsive">
		                <thead>
		                    <tr>
		                        <th><input type="checkbox" name="checkAll" class="checkAll"></th>
		                        <th width= "5%"> GrpID</th>
		                        <th style="20%">Group Name</th>	
		                        <th style="10%">Group Type</th>	
		                        <th style="10%">State</th>	
		                        <th style="10%">City</th>	 
		                        <th style="10%">Members</th>
		                        <th style="5%">Mods</th>
		                        <th style="10%">Admin</th>
		                        <th style="15%;">Group Manager(s)</th>
		                        <th style="10%">Edit/View</th>
		                    </tr>
		                </thead>
		                <tbody>
		                	<?php if (!empty($groups)){ ?>
		                		<?php foreach ($groups['groups'] as $group) {
		                			$group_type = bp_groups_get_group_type( $group->id, $single = true );
		                			$state = groups_get_groupmeta( $group->id, $meta_key = $trikona_obj->state_meta);
		                			$city = groups_get_groupmeta( $group->id, $meta_key = $trikona_obj->city_meta);
		                			$group_id =  $group->id;
		                			$group_managers_list = $trikona_obj->getGroupMembers(['is_admin' => 1, 'group_id' => $group_id]);
		                			$group_manager_names = '-';
                                    $group_slug = $group->slug;
                                    $edit_url = esc_url( home_url( '/manage-group-dashboard/?groupId=' . base64_encode($group_id) . '&groupType=' . $group_type ) );

                                    switch_to_blog($trikona_obj->main_site_blog_id);
                                    $view_url = esc_url( home_url( '/groups/' . $group_slug . '/' ) );
                                    restore_current_blog();
		                		   
		                		   // Calculate the total group members in each role
		                			$total_members = bp_get_group_member_count($group_id);
		                		    $members = groups_get_group_members(array(
                                            'group_id' => $group_id,
                                            'exclude_admins_mods' => false, // Include admins and mods in the count
                                        ));
                                    // Initialize counts
                                        $moderator_count = 0;
                                        $admin_count = 0;
                                        $mem_count = 0;
                                        // Loop through the members and count roles
                                        if (!empty($members['members'])) {
                                            foreach ($members['members'] as $member) {
                                                if (groups_is_user_admin($member->ID, $group_id)) {
                                                    $admin_count++;
                                                } elseif (groups_is_user_mod($member->ID, $group_id)) {
                                                    $moderator_count++;
                                                }
                                            }
                                        }
		                		        $mem_count = $total_members -  $admin_count - $moderator_coun;
		                		   if (!empty($group_managers_list)) {
		                				$group_manager_names = '<ul>';
		                				foreach ($group_managers_list as $group_manager_info) {
		                					$group_mng_info = get_user_by('ID', $group_manager_info->user_id);
		                					$group_manager_names .= '<li>'.ucfirst($group_mng_info->data->display_name)."</li>";
		                				}
		                				$group_manager_names .= '</ul>';
		                			}
		                			?>
			                		<tr>
			                			<td>
			                				<input type="checkbox" name="group_ids[]" class="group-checkbox" value="<?= $group->id; ?>">
			                		    </td>
			                		    <td><?= $group_id; ?></td>
			                			<td><?= $group->name; ?></td>
			                			<td><?= ucfirst($group_type); ?></td>
			                			<td><?= $state; ?></td>
			                			<td><?= $city; ?></td>
			                		    <td><?= $mem_count; ?></td>
                                	    <td><?= $moderator_count; ?></td>
			                	        <td><?= $admin_count; ?></td>
			                		    <td><?= $group_manager_names; ?></td>
			                			<td>
			                	    		<a class="btn1" target="_blank" href="<?= $edit_url ?>"style="display: inline-flex; align-items: center; margin: 1px;"> <i class="fa-solid fa-pencil-alt" style="font-size: 10px;"></i></a>
                                            <a class="btn1" target="_blank" href="<?= $view_url ?>" style="display: inline-flex; align-items: center; margin: 1px;"> <i class="fa-solid fa-eye" style="font-size: 10px;"></i></a>
			                			</td>
			                		</tr>
			                	<?php } ?>
		                	<?php } else{ ?>
		                		<tr>
		                			<td colspan="6">No group found.</td>
		                		</tr>
		                	<?php } ?>
		                </tbody>
		            </table>
		        </form>

	            <div class="row cs-pagination">
	                <div class="col-md-6">
	                    <div id="hint" class="hint-text">
	                        Total :  <b><?= $total_groups ?></b> entries
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <div id="pagination" class="pagination">
	                        <?php
	                          // Block of code to print pagination  
	                          $total_pages = ceil($total_groups / $number);
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
	  	})

	  	jQuery('#filter_group_type').on('change', function() {
	  		var group_type = this.value;

	  		if (group_type == 'company') {
	  			jQuery('.company-group-filter').removeClass('d-none');
	  		} else {
	  			jQuery('.company-group-filter').addClass('d-none');
	  		}
	  	});

	  	jQuery('.checkAll').click(function(){
	  		jQuery('.group-checkbox').prop('checked', jQuery(this).prop("checked"));
	  	});
	});
	</script>
<?php } ?>
<?php get_footer(); ?>