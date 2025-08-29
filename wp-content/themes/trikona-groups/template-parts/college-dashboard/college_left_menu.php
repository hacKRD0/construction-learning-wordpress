<?php
    $obj = new Trikona();

    $activeProfile = $mypoints = $myaccout = $memberships = $updateprofile = $managestudent = $collegeData = $myprofile = $collegeprofile = '';

    $base_url = home_url();
    $is_manage_group_dashboard = false;

    if (isset($_GET['groupId']) && !empty($_GET['groupId']) && isset($_GET['groupType']) && !empty($_GET['groupType'])) {
        $base_url .= '/manage-group-dashboard/?groupId='.$_GET['groupId'].'&groupType='.$_GET['groupType'].'&';
        $is_manage_group_dashboard = true;
    } else {
        $base_url .= '/college-dashboard/?';
    }

    $active_user_group_id = get_query_var( 'active_user_group_id' );
    $group_info = $obj->getBPGroups(['id' => $active_user_group_id]);
?>
<div class="navi">
    <ul>
    <?php 
        if($_GET['mypoints']=='' && $_GET['myaccout']=='' && $_GET['memberships']=='' && $_GET['managestudent']=='' && $_GET['collegeData']=='' && $_GET['updateprofile']=="" && $_GET['updateavatar']==""){
            $activeProfile = 'active';
    	}
        if($_GET['mypoints']=='true'){
            $mypoints = 'active';
    	}
    	if($_GET['myaccout']=='true'){
            $myaccout = 'active';
    	}
    	if($_GET['memberships']=='true'){
            $memberships = 'active';
    	}
        if($_GET['updateprofile']=='true'){
            $updateprofile = 'active';
        }                	
        if($_GET['managestudent']=='true'){
            $managestudent = 'active';
        }                	
        if($_GET['collegeData']=='true'){
            $collegeData = 'active';
        } 
        if($_GET['myprofile']=='true'){
            $myprofile = 'active';
        }
        if($_GET['collegeprofile']=='true'){
            $collegeprofile = 'active';
        }
        switch_to_blog(1);
        $current_user = wp_get_current_user();
        restore_current_blog();
        $userId = base64_encode($current_user->ID);

        // To obtain the membership details of the logged-in user.
        $current_user_plan = $obj->getLoggedInUserMembership($current_user->ID);

        $college_profile_accessibility = $student_menu_accessibility = false;
        if (!empty($current_user_plan)) {
            $college_membership = $obj->college_membership;

            // Display the College Profile menu exclusively for users who possess either College Prime or College Elite membership.
            if (in_array($current_user_plan->membership_id, $college_membership)) {
                $college_profile_accessibility = true;
            }

            // Display the Students menu exclusively for users with a College Elite membership.
            $college_elite_membership_id = end($college_membership);
            if ($current_user_plan->membership_id == $college_elite_membership_id) {
                $student_menu_accessibility = true;
            }
        }

        $allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);

        if (!empty($group_info)) {
            switch_to_blog($obj->main_site_blog_id);
            $college_profile_url = esc_url( home_url( '/groups/' . $group_info->slug . '/' ) );
            restore_current_blog();
        } else {
            $college_profile_url = home_url().'college-profile?user='.$userId;
        }
    ?>
        <?php if(!$is_manage_group_dashboard){ ?>
        <li class="<?= $activeProfile ?>"><a href="<?= home_url() ?>/college-dashboard/"><i class="fa fa-bar-chart" aria-hidden="true"></i><span class="hidden-xs hidden-sm">My Profile</span></a></li>
        <?php } ?>
        <?php if ($college_profile_accessibility || !empty($allowed_roles)) { ?>
        <li class="<?= $collegeData ?>"><a href="<?= $base_url ?>collegeData=true"><i class="fa fa-university"aria-hidden="true"></i><span class="hidden-xs hidden-sm">College Profile</span></a></li>
        <?php } ?>
        <li class="<?= $collegeprofile ?>"><a href="<?= $college_profile_url ?>"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">View College Profile</span></a></li>
        <?php if(!$is_manage_group_dashboard){ ?>
        <li class="<?= $myprofile ?>"><a href="<?= $base_url ?>myprofile=true"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">View My Profile</span></a></li>
        <?php } ?>
        <?php if ($student_menu_accessibility || !empty($allowed_roles)) { ?>
        <li class="<?= $managestudent ?>"><a href="<?= $base_url ?>managestudent=true"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Manage Students </span></a>  </li>
        <?php } ?>
        <li class="<?= $memberships ?>"><a href="<?= $base_url ?>memberships=true"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Memberships </span></a></li>
        <li class="<?= $mypoints ?>"><a href="<?= $base_url ?>mypoints=true"><i class="fa fa-money" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Points </span></a></li>
        <?php if(!$is_manage_group_dashboard){ ?>
        <li class="<?= $myaccout ?>"><a href="<?= $base_url ?>myaccout=true"><i class="fa fa-book" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Account </span></a></li>
        <?php } ?>
    </ul>
</div>