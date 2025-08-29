<?php
	require_once get_theme_root() . '/class-trikona.php';

	global $wpdb;

	$obj = new Trikona();
	switch_to_blog( $obj->main_site_blog_id );
	$main_theme_url = get_stylesheet_directory_uri();
	restore_current_blog();

	$phone_nos = [];

	$wp_user_avatar =  get_user_meta( $current_user->ID ,'wp_user_avatar',true);
	$imguser = wp_get_attachment_image_src($wp_user_avatar);
	$user_avatar = '<img src="'.get_avatar_url($current_user->ID).'" />';

	if (!empty($imguser)) {
		$user_avatar = '<img src="'.$imguser[0].'" />';
	}

	switch_to_blog( $obj->main_site_blog_id );
	$phone_values = $wpdb->get_row("SELECT value FROM ".$wpdb->prefix."bp_xprofile_data AS pd INNER JOIN ".$wpdb->prefix."bp_xprofile_fields AS pf ON pd.field_id = pf.id WHERE pf.name = 'Phone' AND pd.user_id=".$current_user->ID);
	if (!empty($phone_values)) {
		$phone_nos[] = $phone_values->value;
	}
	$mobile_values = $wpdb->get_row("SELECT value FROM ".$wpdb->prefix."bp_xprofile_data AS pd INNER JOIN ".$wpdb->prefix."bp_xprofile_fields AS pf ON pd.field_id = pf.id WHERE pf.name = 'Mobile' AND pd.user_id=".$current_user->ID);
	if (!empty($mobile_values)) {
		$phone_nos[] = $mobile_values->value;
	}
	$address = '';
	$address_values = $wpdb->get_row("SELECT value FROM ".$wpdb->prefix."bp_xprofile_data AS pd INNER JOIN ".$wpdb->prefix."bp_xprofile_fields AS pf ON pd.field_id = pf.id WHERE pf.name = 'Address' AND pd.user_id=".$current_user->ID);
	if (!empty($address_values)) {
		$address = $address_values->value;
	}

	$members_experience = $wpdb->get_results("SELECT * from ".$wpdb->prefix."user_expriances where user_id='$current_user->ID' ORDER By id DESC LIMIT 5");
	$members_education = $wpdb->get_results("SELECT * from ".$wpdb->prefix."user_educations where user_id='$current_user->ID' ORDER By id DESC LIMIT 5");
	restore_current_blog();

	$linkedinProfile = get_user_meta( $current_user->ID, 'linkedinProfile', true );
	$skill= get_user_meta( $current_user->ID,'Skills',true);
	$email = $current_user->user_email;
	$memberDob = get_user_meta( $current_user->ID, 'memberDob', true );
	$member_bio= get_user_meta( $current_user->ID,'member_bio',true);
	$gender = get_user_meta( $current_user->ID, 'gender', true );
?>