<?php
	global $wpdb, $trikona_obj;

	switch_to_blog( $trikona_obj->main_site_blog_id );
	$wp_user_avatar =  get_user_meta( $current_user->ID ,'wp_user_avatar',true);
	$imguser = wp_get_attachment_image_src($wp_user_avatar);
	$user_avatar = '<img src="'.get_avatar_url($current_user->ID).'" width="150"/>';

	if (!empty($imguser)) {
		$user_avatar = '<img src="'.$imguser[0].'" width="150" />';
	}

	$designation_current = get_user_meta( $current_user->ID, 'designation_current', true );
	$company_current = get_user_meta( $current_user->ID, 'company_current', true );
	$member_bio= get_user_meta( $current_user->ID,'member_bio',true);
	$phone_values = $wpdb->get_row("SELECT value FROM ".$wpdb->prefix."bp_xprofile_data AS pd INNER JOIN ".$wpdb->prefix."bp_xprofile_fields AS pf ON pd.field_id = pf.id WHERE pf.name = 'Phone' AND pd.user_id=".$current_user->ID);
	if (!empty($phone_values)) {
		$phone = $phone_values->value;
	}
	$mobile_values = $wpdb->get_row("SELECT value FROM ".$wpdb->prefix."bp_xprofile_data AS pd INNER JOIN ".$wpdb->prefix."bp_xprofile_fields AS pf ON pd.field_id = pf.id WHERE pf.name = 'Mobile' AND pd.user_id=".$current_user->ID);
	if (!empty($mobile_values)) {
		$mobile = $mobile_values->value;
	}

	$linkedinProfile = get_user_meta( $current_user->ID, 'linkedinProfile', true );
	$skill= get_user_meta( $current_user->ID,'Skills',true);
	$email = $current_user->user_email;

	$current_position = [];
	if(!empty($designation_current)){
		$current_position[] = $designation_current;
	}
	if(!empty($company_current)){
		$current_position[] = $company_current;
	}

	$members_experience = $wpdb->get_results("SELECT * from ".$wpdb->prefix."user_expriances where user_id='$current_user->ID' ORDER By id DESC LIMIT 5");
	$members_education = $wpdb->get_results("SELECT * from ".$wpdb->prefix."user_educations where user_id='$current_user->ID' ORDER By id DESC LIMIT 5");

	$phone_nos = [];
	if (!empty($phone)) {
		$phone_nos[] = $phone;
	}
	if (!empty($mobile)) {
		$phone_nos[] = $mobile;
	}

	function getSkillProgressClass($percentage) {
		$progress_class = '';
		if ($percentage < 30)
			$progress_class = 'bg-danger';
		if ($percentage > 30 && $percentage < 50)
			$progress_class = 'bg-info';
		if ($percentage > 49 && $percentage < 70)
			$progress_class = 'bg-warning text-dark';
		if ($percentage > 69 && $percentage < 80)
			$progress_class = 'bg-success';
		if ($percentage > 79)
			$progress_class = 'bg-primary';

		return $progress_class;
	}
?>