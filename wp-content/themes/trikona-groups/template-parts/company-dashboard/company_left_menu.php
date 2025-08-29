<?php
    $obj = new Trikona();

    $activeProfile = $mypoints = $myaccout = $memberships = $managesEmp = $companyProfile = $managesJobs = $updateprofile = $inquiry ='';

    $base_url = home_url();
    $is_manage_group_dashboard = false;

    if (isset($_GET['groupId']) && !empty($_GET['groupId']) && isset($_GET['groupType']) && !empty($_GET['groupType'])) {
        $base_url .= '/manage-group-dashboard/?groupId='.$_GET['groupId'].'&groupType='.$_GET['groupType'].'&';
        $is_manage_group_dashboard = true;
    } else {
        $base_url .= '/college-dashboard/?';
    }
?>
<div class="navi">
    <ul>
        <?php 
            if($_GET['mypoints']=='' && $_GET['myaccout']=='' && $_GET['memberships']=='' && $_GET['managesEmp']=='' && $_GET['companyProfile']=='' && $_GET['managesJobs']=='' && $_GET['updateprofile']=="" && $_GET['updateavatar']=="" && $_GET['enquiry']=="" && $_GET['myaccount']==""){
                $activeProfile = 'active';
           	}
            if($_GET['mypoints']=='true'){
                    $mypoints = 'active';
           	}
           	 if($_GET['myaccount']=='true'){
                    $myaccout = 'active';
           	}
           	if($_GET['memberships']=='true'){
                    $memberships = 'active';
           	}
           	if($_GET['managesEmp']=='true'){
                    $managesEmp = 'active';
           	}

           	if($_GET['companyProfile']=='true'){
                    $companyProfile = 'active';
           	}
           	if($_GET['managesJobs']=='true'){
                    $managesJobs = 'active';
           	}
           	if($_GET['updateprofile']=='true'){
                    $updateprofile = 'active';
           	}

           	if($_GET['enquiry']=='true'){
                    $inquiry = 'active';
           	}

            global $wpdb;

            switch_to_blog(1);

            // get current logged in user
            $current_user = wp_get_current_user();

            // Check for group membership for current user with Group Admin title
            $bp_groups_members = $obj->getGroupMembers(['user_id' => $current_user->ID, 'user_title' => 'Group Admin'], $is_single_record = true);

            // Check for group mode for current user
            $GroupMod = $obj->getGroupMembers(['user_id' => $current_user->ID, 'user_title' => 'Group Mod'], $is_single_record = true);

            // Check active membership for current user
            $checkMembership = $obj->getLoggedInUserMembership($current_user->ID);

            $userId = base64_encode($current_user->ID);

            $employees_menu_accessible = $enquiries_menu_accessible = false;

            $current_user_plan = $checkMembership;
            restore_current_blog();

            if (!empty($current_user_plan)) {
                if ($current_user_plan->membership_id == $obj->corporate_basic_mem_id) {
                    $employees_menu_accessible = $enquiries_menu_accessible = false;
                } else if ($current_user_plan->membership_id == $obj->corporate_prime_mem_id) {
                    $enquiries_menu_accessible = false;
                    $employees_menu_accessible = true;
                } else if ($current_user_plan->membership_id == $obj->corporate_elite_mem_id) {
                    $employees_menu_accessible = $enquiries_menu_accessible = true;
                }
            }

            $is_user_administrator = false;
            if (is_super_admin($current_user->ID) || in_array("administrator", $current_user->roles)) {
                $is_user_administrator = true;
            }

            $allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);
?>
        
        <?php if(!$is_manage_group_dashboard){ ?>
          <li class="<?= $activeProfile; ?>"><a href="<?= $base_url ?>"><i class="fa fa-bar-chart" aria-hidden="true"></i><span class="hidden-xs hidden-sm">My Profile</span></a></li>
        <?php } ?>
  
        <?php if(($bp_groups_members->group_id > 0 && $checkMembership->status=='active') || !empty($allowed_roles)){?>  
            
            <?php if(!$is_user_administrator  || !empty($allowed_roles)){ ?>
                <li class="<?= $companyProfile; ?>"><a href="<?= $base_url ?>companyProfile=true"><i class="fa fa-industry" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Company Profile</span></a></li>
            <?php } ?>

            <?php if(($GroupMod->group_id > 0 || $bp_groups_members->group_id > 0) || !empty($allowed_roles)){?>
                <li  class="<?= $managesJobs; ?>"><a target="_blank" href="/jobs/job-dashboard/"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Manage Jobs</span></a></li>
            <?php } ?>

            <?php if($employees_menu_accessible || !empty($allowed_roles)){ ?>
                <li class="<?= $managesEmp; ?>"><a href="<?= $base_url ?>managesEmp=true"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Employees </span></a></li>
            <?php } ?>
        <?php } ?>

        <?php if($enquiries_menu_accessible || !empty($allowed_roles)){ ?>
            <li class="<?= $inquiry; ?>"><a href="<?= $base_url ?>enquiry=true"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Enquiries </span></a></li>
        <?php } ?>
        <li  class="<?= $memberships; ?>"><a href="<?= $base_url ?>memberships=true"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Memberships</span></a></li>
        <li class="<?= $mypoints; ?>"><a href="<?= $base_url ?>mypoints=true"><i class="fa fa-money" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Points </span></a></li>
        <?php if(!$is_manage_group_dashboard){ ?>
        <li class="<?= $myaccout; ?>"><a href="<?= $base_url ?>myaccount=true"><i class="fa fa-book" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Account </span></a></li>
        <?php } ?>
    </ul>
</div>