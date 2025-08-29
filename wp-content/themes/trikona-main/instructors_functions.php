<?php
	/*
		Updated date: 23 May 2024
	*/
	add_action( 'wp_ajax_instructor_members_approve', 'instructor_members_approve' );
	add_action( 'wp_ajax_nopriv_instructor_members_approve', 'instructor_members_approve' );
	function instructor_members_approve(){
	    $obj = new Trikona();

	 global $wpdb;
	parse_str($_POST['data'], $searcharray);
	$current_user = wp_get_current_user();

	$usersids = explode(',', $searcharray['regId']);
	foreach ($usersids as $key => $user_id) {
		update_user_meta($user_id, $obj->instructor_status_meta, $searcharray['LinkedinStatus']);
		update_user_meta($user_id, $obj->instructor_msg_by_meta, $searcharray['msg']);
		update_user_meta($user_id, $obj->instructor_approve_by_meta, $current_user->first_name);
	}

	die;
	}


	add_action( 'wp_ajax_instructor_members_assign_membership', 'instructor_members_assign_membership' );
	add_action( 'wp_ajax_nopriv_instructor_members_assign_membership', 'instructor_members_assign_membership' );
	function instructor_members_assign_membership(){
	 global $wpdb;
	parse_str($_POST['data'], $searcharray);
	$current_user = wp_get_current_user();
	$instructorIds = explode(',', $searcharray['instructorIds']);
	$instructorMembership = $searcharray['instructorMembership'];

	if($instructorMembership!=""){
		foreach ($instructorIds as $key => $user_id) {
			wp_set_post_terms( $user_id, $instructorMembership, 'bp_member_type' );

		}
	}

	die;
	}

	add_action( 'wp_ajax_instructor_members_assign_membership_single', 'instructor_members_assign_membership_single' );
	add_action( 'wp_ajax_nopriv_instructor_members_assign_membership_single', 'instructor_members_assign_membership_single' );
	function instructor_members_assign_membership_single(){
	 global $wpdb;
	 $user_id = $_POST['user_id'];
	 $membershipLebel = $_POST['membershipLebel'];
	if($membershipLebel!=""){
			wp_set_post_terms( $user_id,$membershipLebel, 'bp_member_type' );

		}


	die;
	}

	//instructor_members_delete

	add_action( 'wp_ajax_instructor_members_delete', 'instructor_members_delete' );
	add_action( 'wp_ajax_nopriv_instructor_members_delete', 'instructor_members_delete' );
	function instructor_members_delete(){
	 global $wpdb;
	 $submissionId = $_POST['submissionId'];
	if($submissionId!=""){
	    $table = 'wpcw_e_submissions_values';
	    $table2 = 'wpcw_e_submissions';
	    $wpdb->delete( $table, array('submission_id' => $submissionId) );
	    $wpdb->delete( $table2, array('id' => $submissionId) );
		}
	die;
	}

	function events_register_custom_boxes() {
	    add_meta_box( 'instructors-link', 'instructors', 'instructors_link', 'ajde_events',"side", "high", null);
	}

	add_action( 'add_meta_boxes', 'events_register_custom_boxes' );


	function instructors_link( $post ) {
	$obj = new Trikona();
	$instructor_role = $obj->instructor_role;

	$events_instructors_arr = get_post_meta( $post->ID, 'events_instructors_arr', true);
		
	$args = array(
	    'role'    => $instructor_role,
	    'orderby' => 'user_nicename',
	    'order'   => 'ASC'
	);
	$users = get_users( $args );
	 ?>
	<div class="cs_box">
	   <select multiple="multiple" id="my-select" name="instructorArr[]" style="width:100% !important;">
	   	<?php foreach ( $users as $user ) {
	   		if(!empty($events_instructors_arr)){
	     if (in_array($user->ID, $events_instructors_arr))
			  {
			 $selected = 'selected';
			  }
			else
			  {
			  $selected = '';
			  }
			}
	   	 ?>
	   	<option value='<?php echo $user->ID;?>'  <?php echo  $selected;?>><?php echo $user->display_name;?></option>
	   <?php } ?>
	   	   </select>
	</div>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script> 
		jQuery(document).ready(function() {
	jQuery('#my-select').select2({
	  placeholder: 'Select an instructors',
	 allowClear: true
	});
	});
	</script>
	<?php
	}
	function instructors_save_custom_box( $post_id ) {
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	    //echo $post_id;
	   //print_r($_POST['instructorArr']); die;
	    update_post_meta( $post_id, 'events_instructors_arr', $_POST['instructorArr']);
	    
	}
	add_action( 'save_post', 'instructors_save_custom_box' );

	add_shortcode('course_instructors', 'course_instructors');
	function course_instructors(){
		 ob_start();
		 global $post;
	$post_id = get_queried_object_id();
	$term_list = wp_get_post_terms( $post_id, 'author', array( 'fields' => 'all' ) );
	?>
	<style>
	.cs-instructor-list .col-md-12 {
	    background-color: #1b3b4c;
	    padding: 10px;
	    text-align: center;
	    margin-bottom: 30px;
	    border-radius: 10px;
	}
	.cs-instructor-list .col-md-12 h4 {
	    color: #fff;
	    margin-bottom: 0px;
	}
	.row.cs-instructor-list {
	    width: 98%;
	    margin-bottom: 50px;
	}
	.cs-instructor-list .col-md-4 {
	    background-color: #f2f2f2;
	    margin: 10px;
	    text-align: center;
	    padding: 20px;
	    border-radius: 10px;
	}
	.cs-instructor-list .col-md-4 .img-box {
	    width: 20%;
	    margin: 0 auto 20px;
	}
	.cs-instructor-list .col-md-4 .img-box img {
	    border-radius: 50%;
	}
	.cs-instructor-list .col-md-4 .ins-course-title {
	    font-size: 18px;
	    font-weight: 600;
	    text-transform: capitalize;
	    border-bottom: 1px solid #d1d1d1;
	    padding-bottom: 10px;
	    margin-bottom: 10px;
	}
	.cs-instructor-list .col-md-4 .ins-cstm-cont {
	    font-size: 15px;
	    padding-bottom: 5px;
	}
	.cs-instructor-list .col-md-4 a {
	    color: blue;
	    position: relative;
	}
	.cs-instructor-list .ins-cstm-link {
	    margin: 10px;
	}
	@media only screen and (max-width: 700px){
		.row.cs-instructor-list {
	    width: 100%;
	    margin: 0;
	}
	}
	</style>
	<div class="container">
	<div class="row cs-instructor-list">
	<div class="col-md-12">
	<h4>Course Instructors</h4>
	</div>
	<?php
	foreach ($term_list as $key => $course_instructors) {
	    $obj = new Trikona();

	//echo $int = (int) filter_var($course_instructors->description, FILTER_SANITIZE_NUMBER_INT);
	preg_match("|\d+|", $course_instructors->description, $m);
	$userid=  $m[0];
	$user_info = get_userdata($userid);
	 $linkedinProfile = get_user_meta( $userid, $obj->linkedin_profile_meta, true );
	 $member_bio= get_user_meta($userid, $obj->member_bio_meta,true);
	 $user = new BP_Core_User( $userid );
	$user_avatar = $user->avatar;
	$userId = base64_encode($userid);

	 ?>

	<div class="col-md-4">
			<div class="img-box">
				<?php echo $user_avatar;?>
			</div>
			
	             <div class="ins-course-title">
				 <?php echo $course_instructors->name?>
				 </div>
	             <div class="ins-cstm-cont"> 
	             Email:  <?php echo $user_info->user_email;?>
	             </div>
	             <div class="ins-cstm-cont"> 
	              Bio <?php echo $member_bio;?>
	             </div> 
				 <div class="ins-cstm-link"> 
	             <a target="_blank" href="<?php echo home_url()?>/instructors/?user=<?php echo $userId; ?>" class="title">View Profile</a>
				 </div> 
	         </div> 

	<?php 
	}
	?>
	     </div>		
	 </div>
	 <?php

	$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
?>