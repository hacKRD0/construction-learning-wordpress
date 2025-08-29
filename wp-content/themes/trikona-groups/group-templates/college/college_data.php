<?php
	global $trikona_obj;

	$group = groups_get_current_group();
	$group_id = $group->id;

	$bp_group_banner = '';
	$bp_group_banner_img  = groups_get_groupmeta($group_id, 'bp_group_banner_img' ,true );
	if (!empty($bp_group_banner_img)) {
	    $bp_group_banner = wp_get_attachment_image_src( $bp_group_banner_img[0] , 'full');
	}

	$state  = groups_get_groupmeta( $group_id, $trikona_obj->state_meta ,true ); 
	$grpcity  = groups_get_groupmeta( $group_id, $trikona_obj->city_meta ,true );
	$groupAddress  = groups_get_groupmeta( $group_id, $trikona_obj->address_meta ,true );
	$email  = groups_get_groupmeta( $group_id, $trikona_obj->email_address_meta ,true );
	$phone  = groups_get_groupmeta( $group_id, $trikona_obj->phone_number_meta ,true );
	$grpwebsite  = groups_get_groupmeta( $group_id, $trikona_obj->company_website_url_meta ,true );

	$group_address = [];
	if (!empty($groupAddress)) {
	    $group_address[] = $groupAddress;
	}
	if (!empty($grpcity)) {
	    $group_address[] = $grpcity;
	}
	if (!empty($state)) {
	    $group_address[] = $state;
	}
?>