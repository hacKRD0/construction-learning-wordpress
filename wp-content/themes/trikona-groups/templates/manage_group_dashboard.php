<?php 
	/**
	 * Template Name: Manage Group Dashboard
	 */
	get_header();
	global $wpdb,$bp,$trikona_obj,$status_messages;

	$group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";
	$group_type = isset($_GET['groupType']) ? $_GET['groupType'] : "";

	$base_url = home_url();
	if (!empty($group_id) && !empty($group_type)) {
	    switch_to_blog($trikona_obj->directories_site_blog_id);
	    $base_url = get_stylesheet_directory_uri();
	    restore_current_blog();
	}

	if (empty($group_id) || empty($group_type)) {
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
<?php } else { ?>
	<style>
		.tag {
		  position: relative;
		  display: inline-block;
		  border-radius: 6px;
		  clip-path: polygon(20px 0px, 100% 0px, 100% 100%, 0% 100%, 0% 20px);
		  background: var(--global-color-yellow);
		  padding: 10px 25px;
		  margin: 0 8px;
		  font-weight: 600;
		  font-size: 18px;
		  color: var(--color-text);
		  transition: clip-path 500ms;
		}
		.tag:after {
		  content: '';
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 20px;
		  height: 20px;
		  background: var(--color-back);
		  box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1); 
		  border-radius: 0 0 6px 0;
		  transition: transform 500ms;
		}
		.tag:hover {
		  clip-path: polygon(0px 0px, 100% 0px, 100% 100%, 0% 100%, 0% 0px);
		}
		.tag:hover:after {
		  transform: translate(-100%, -100%);
		}
	</style>
	<?php
		$bp_groups_data =  $trikona_obj->getBPGroups(['id' => $group_id]);

		if($group_type == 'company'){
			$user_meta = get_userdata($group_id);
			$user_roles = $user_meta->roles;
			$user = new BP_Core_User( $group_id );
			$user_avatar = $user->avatar;
			$member_bio= get_user_meta( $group_id, $trikona_obj->member_bio_meta ,true);
			$memberDob = get_user_meta( $group_id, $trikona_obj->memberDob_meta, true );
			$gender = get_user_meta( $group_id, $trikona_obj->gender_meta, true );
			$linkedinProfile = get_user_meta( $group_id, $trikona_obj->linkedin_profile_meta, true );
			$phone = bp_get_profile_field_data('field='.$trikona_obj->phone_no_field_id.'&user_id='. $group_id);

			$is_gn_tab_active = $is_brn_tab_active = $is_bnr_tab_active = $is_logo_tab_active = $is_prf_tab_active = $is_prg_tab_active = $is_srv_tab_active = $is_mem_tab_active = '';

			if($_GET['detsild']==""){ 
				$is_gn_tab_active = ' show active';
			}
			if($_GET['detsild']=="logo"){
				$is_logo_tab_active = ' show active';
			}
			if($_GET['detsild']=="services"){
				$is_srv_tab_active = ' show active';
			}
			if($_GET['detsild']=="branch"){
				$is_brn_tab_active = ' show active';
			}
			if($_GET['detsild']=="projects"){
				$is_prg_tab_active = ' show active';
			}
			if($_GET['detsild']=="banner"){
				$is_bnr_tab_active = ' show active';
			}
			if($_GET['detsild']=="document"){
				$is_prf_tab_active = ' show active';
			}
			if($_GET['detsild']=="manageGroupMambers"){
				$is_mem_tab_active = ' show active';
			}

			$bp_group_banner_img  = groups_get_groupmeta( $group_id, 'bp_group_banner_img' ,true );
			if (!empty($bp_group_banner_img)){
				$company_logo = wp_get_attachment_image_src( $bp_group_banner_img[0]);
				$user_avatar = '<img class="card-img-top" src="'.$company_logo[0].'" alt="Compay logo Image">';
			}
		?>
		<div class="container-fluid display-table">
		    <div class="row display-table-row">
		        <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
		        	<h4 class="disp-name"><?= $bp_groups_data->name ?></h4>
		        	<div class="logo logostyle">
		        	    <a hef="#"><?= $user_avatar ?></a>
		        	</div>
		        	<?= set_query_var('active_user_group_id', $group_id); ?>
		        	<?= get_template_part( 'template-parts/company-dashboard/company_left_menu' ) ?>
		        </div>
		        <div class="col-md-10 col-sm-11 display-table-cell v-align">
					<div class="user-dashboard">
						<?php /*<div class="row">
							<div class="col-sm-10">
								<h1><span class="group-type tag"><?= $bp_groups_data->name ?></span></h1>
							</div>
							<div class="col-sm-2 text-end pt-4">
								<a class="btn" href="<?= home_url() ?>/manage-groups">Back</a>
							</div>
						</div>*/ ?>
						<?php
							set_query_var('active_user_group_id', $group_id);
						?>
						<?= get_template_part( 'template-parts/company-dashboard/company_top_header' ) ?>
						<?php 			     
							//Company Gen profile
							if($_GET['detsild']=="" && !isset($_GET['managesEmp']) && !isset($_GET['enquiry']) && !isset($_GET['memberships']) && !isset($_GET['mypoints'])){ 
								set_query_var('active_user_group_id', $group_id);
								echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_general');
						 	}

							// Company Logo
							if($_GET['detsild']=="logo"){ 
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');	
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_company_logo');
						 	}

							//Company Services
							if($_GET['detsild']=="services"){ 
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');	
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company//tab_services');	
						 	}
						
							//Company Branches
							if($_GET['detsild']=="branch"){
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_branches');	
							}

							//Company Projects
							if($_GET['detsild']=="projects"){
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_projects');	
							}

							//Company Banner
							if($_GET['detsild']=="banner"){
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_company_banner');	
							}
						
							//Company Docuemnts
							if($_GET['detsild']=="document"){
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_company_documents');	
							}

							//Manage Group Mambers
							if($_GET['detsild']=="manageGroupMambers"){
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
						    	echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_manage_group_member');	
							}
							if($_GET['managesEmp']=='true'){
								set_query_var('active_user_group_id', $group_id);
								echo get_template_part( 'template-parts/company-dashboard/company_employees');
							}
							if($_GET['enquiry']=='true'){ 
								set_query_var('active_user_group_id', $group_id);
							    echo get_template_part( 'template-parts/company-dashboard/company_enquiry' );
							} 
							// Mmebrships Tab
							if($_GET['memberships']=='true'){
								set_query_var('active_user_group_id', $group_id);
						    	echo get_template_part( 'template-parts/company-dashboard/company_memberships');
						  	}
						  	// My Points
						  	if($_GET['mypoints']=='true'){ 
						  	     echo do_shortcode('[mycred_history user_id='.get_current_user_id().' type=trikona_credit]');
						  	}
						?>
					</div>
				</div>
			</div>
		</div>
	<?php } else if($group_type == 'college'){
		$bp_group_banner_img  = groups_get_groupmeta( $group_id, 'bp_group_banner_img' ,true );
		if (!empty($bp_group_banner_img)){
			$college_logo = wp_get_attachment_image_src( $bp_group_banner_img[0]);
			$user_avatar = '<img class="card-img-top" src="'.$college_logo[0].'" alt="Compay logo Image">';
		}
	?>
		<div class="container-fluid display-table">
		    <div class="row display-table-row">
		        <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
		        	<h4 class="disp-name"><?= $bp_groups_data->name ?></h4>
		        	<div class="logo logostyle">
		        	    <a hef="#"><?= $user_avatar ?></a>
		        	</div>
		        	<?= set_query_var('active_user_group_id', $group_id); ?>
		        	<?= get_template_part( 'template-parts/college-dashboard/college_left_menu' ) ?>
		        </div>
		        <div class="col-md-10 col-sm-11 display-table-cell v-align">
					<div class="user-dashboard">
						<?php /*<div class="col-sm-12">
							<h1><span class="group-type tag"><?= $bp_groups_data->name ?></span></h1>
						</div>*/ ?>
						<?php
							set_query_var('active_user_group_id', $group_id);
						?>
						<?= get_template_part( 'template-parts/college-dashboard/college_top_header' ) ?>
						<?php
						    // echo get_template_part( 'template-parts/college-dashboard/college_top_header' );
						    if(!isset($_GET['tab']) && !isset($_GET['managestudent']) && !isset($_GET['mypoints']) && !isset($_GET['memberships'])){ 
						    	set_query_var('active_user_group_id', $group_id);
						        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
						        echo get_template_part( 'template-parts/college-dashboard/edit-college/tab_general_info' );
						    }
						    if($_GET['tab']=="college-banner"){ 
						    	set_query_var('active_user_group_id', $group_id);
						        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
						        echo get_template_part( 'template-parts/college-dashboard/banner' );
						    }
						    if($_GET['tab']=="college-logo"){ 
						    	set_query_var('active_user_group_id', $group_id);
						        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
						        echo get_template_part( 'template-parts/college-dashboard/college_logo' );
						    }
						    if($_GET['tab']=="courses"){ 
						    	set_query_var('active_user_group_id', $group_id);
						        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
						        echo get_template_part( 'template-parts/college-dashboard/college_courses' );
						    }
						    if($_GET['tab']=="staff"){ 
						    	set_query_var('active_user_group_id', $group_id);
						        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
						        echo get_template_part( 'template-parts/college-dashboard/college_manage_staff' );
						    }
						    if($_GET['managestudent'] == "true"){
					    		set_query_var('active_user_group_id', $group_id);
					    	    // echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
					    	    echo get_template_part( 'template-parts/college-dashboard/college_manage_students' );
						    }
						    if($_GET['mypoints'] == "true"){
						    	echo do_shortcode('[mycred_history user_id='.get_current_user_id().' type=trikona_credit]');
						    }
						    if($_GET['memberships']=='true'){
						        echo get_template_part( 'template-parts/college-dashboard/college_memberships' );
						    }
						?>
					</div>
					<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
					<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
					<script type="text/javascript">
					    var ajaxurl = "<?= admin_url('admin-ajax.php') ?>";
					</script>
					<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
					<script src="<?= $base_url ?>/assets/js/college_profile.js"></script>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
<?php get_footer(); ?>	
<script type="text/javascript">
    var ajaxurl = "<?= admin_url('admin-ajax.php') ?>";
</script>
<script src="<?= $base_url ?>/assets/js/company_profile.js"></script>