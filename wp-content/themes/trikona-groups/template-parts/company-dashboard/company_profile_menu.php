<?php
    $obj = new Trikona();
    // Switch to the main site context (ID 1) in a multisite network
    switch_to_blog($obj->main_site_blog_id);

    $current_user = wp_get_current_user();

    // Restore original blog context (important for multisite)
    restore_current_blog();

    $branches_tab_accessible = $project_tab_accessible = $service_tab_accessible = $manage_group_member_tab_accessible = false;
    $group_id = isset($_GET['groupId']) ? $_GET['groupId'] : "";
    $group_type = isset($_GET['groupType']) ? $_GET['groupType'] : "";

    if($_GET['detsild']=="branch"){
      $activeMenu1 = "btn-primary active-tab";
    }else{
    	$activeMenu1 = "btn-default";
    }

    if($_GET['detsild']=="projects"){
        $activeMenu2 = "btn-primary active-tab";	
    }else{
    	$activeMenu2 = "btn-default";
    }

    if($_GET['detsild']=="banner"){
        $activeMenu3 = "btn-primary active-tab";	
    }else{
    	$activeMenu3= "btn-default";
    }

    if($_GET['detsild']=="document"){
        $activeMenu4 = "btn-primary active-tab";	
    }else{
    	$activeMenu4 = "btn-default";
    }

    if($_GET['detsild']==""){
        $activeMenu = "btn-primary active-tab";	
    }else{ 
        $activeMenu = "btn-default";
    }

    if($_GET['detsild']=="services"){
        $activeMenu5 = "btn-primary active-tab";	
    }else{
    	$activeMenu5 = "btn-default";
    }
    if($_GET['detsild']=="logo"){
        $activeMenu6 = "btn-primary active-tab";	
    }else{
    	$activeMenu6 = "btn-default";
    }
    if($_GET['detsild']=="manageGroupMambers"){
        $activeMenu7 = "btn-primary active-tab";    
    }else{
        $activeMenu7 = "btn-default";
    }

    global $wpdb;
    $current_user_plan = $obj->getLoggedInUserMembership($current_user->ID);

    if (!empty($current_user_plan)) {
        if ($current_user_plan->membership_id == $obj->corporate_basic_mem_id) {
            $branches_tab_accessible = $project_tab_accessible = $service_tab_accessible = false;
        } else if ($current_user_plan->membership_id == $obj->corporate_prime_mem_id) {
            $project_tab_accessible = $service_tab_accessible = false;
            $branches_tab_accessible = true;
        } else if ($current_user_plan->membership_id == $obj->corporate_elite_mem_id) {
            $branches_tab_accessible = $project_tab_accessible = $service_tab_accessible = $manage_group_member_tab_accessible = true;
        }
    }

    $allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);

    $active_user_group_id = get_query_var( 'active_user_group_id' );
    $group_info = $obj->getBPGroups(['id' => $active_user_group_id]);

    $group_profile_url = '';
    if (!empty($group_info)) {
        switch_to_blog($obj->main_site_blog_id);
        $group_profile_url = esc_url( home_url( '/groups/' . $group_info->slug . '/' ) );
        restore_current_blog();
    }
?>
<div class="btn-pref btn-group btn-group-justified btn-group-lg mb-4" role="group" aria-label="...">
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="stars" class="btn <?= $activeMenu ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                <div class="hidden-xs">General Info</div>
            </a>
        <?php } else { ?>
            <a type="button" id="stars" class="btn <?= $activeMenu; ?>" href="<?= home_url() ?>/company-dashboard/?companyProfile=true"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                <div class="hidden-xs">General Info</div>
            </a>
        <?php } ?>
    </div>
    <?php if($branches_tab_accessible || !empty( $allowed_roles)){ ?>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="favorites" class="btn <?= $activeMenu1; ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&detsild=branch" ><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                <div class="hidden-xs">Branches</div>
            </a>
        <?php } else { ?>
            <a type="button" id="favorites" class="btn <?= $activeMenu1; ?>" href="<?= home_url() ?>/company-dashboard//?companyProfile=true&&detsild=branch" ><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                <div class="hidden-xs">Branches</div>
            </a>
        <?php } ?>
    </div>
    <?php } ?>

    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" class="btn <?= $activeMenu3; ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&detsild=banner" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                <div class="hidden-xs">Company Banner</div>
            </a>
        <?php } else { ?>
            <a type="button" class="btn <?= $activeMenu3; ?>" href="<?= home_url() ?>/company-dashboard/?companyProfile=true&detsild=banner" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                <div class="hidden-xs">Company Banner</div>
            </a>
        <?php } ?>
    </div>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" class="btn <?= $activeMenu6; ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&detsild=logo" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                <div class="hidden-xs">Logo</div>
                
            </a>
        <?php } else { ?>
            <a type="button" class="btn <?= $activeMenu6; ?>" href="<?= home_url() ?>/company-dashboard/?companyProfile=true&detsild=logo" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                <div class="hidden-xs">Logo</div>
                
            </a>
        <?php } ?>
    </div>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" class="btn <?= $activeMenu4; ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&detsild=document" ><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                <div class="hidden-xs">Upload profile </div>
                
            </a>
        <?php } else { ?>
            <a type="button" class="btn <?= $activeMenu4; ?>" href="<?= home_url() ?>/company-dashboard/?companyProfile=true&detsild=document" ><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                <div class="hidden-xs">Upload profile </div>
                
            </a>
        <?php } ?>
    </div>

    <?php if($project_tab_accessible || !empty( $allowed_roles)){ ?>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="following" class="btn <?= $activeMenu2; ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&detsild=projects"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                <div class="hidden-xs">Projects</div>
            </a>
        <?php } else { ?>
            <a type="button" id="following" class="btn <?= $activeMenu2; ?>" href="<?= home_url() ?>/company-dashboard/?companyProfile=true&detsild=projects"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                <div class="hidden-xs">Projects</div>
            </a>
        <?php } ?>
    </div>
    <?php } ?>

    <?php if($service_tab_accessible || !empty( $allowed_roles)){ ?>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" class="btn <?= $activeMenu5; ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&detsild=services" >
                <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
                <div class="hidden-xs">Services</div>
            </a>
        <?php } else { ?>
            <a type="button" class="btn <?= $activeMenu5; ?>" href="<?= home_url() ?>/company-dashboard/?companyProfile=true&detsild=services" >
                <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
                <div class="hidden-xs">Services</div>
            </a>
        <?php } ?>
    </div>
    <?php } ?>

    <?php /*if($manage_group_member_tab_accessible || !empty( $allowed_roles)){ ?>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" class="btn <?= $activeMenu7; ?>" href="<?= home_url() ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&detsild=manageGroupMambers" >
                <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
                <div class="hidden-xs">Edit Group Members</div>
            </a>
        <?php } else { ?>
            <a type="button" class="btn <?= $activeMenu7; ?>" href="<?= home_url() ?>/company-dashboard/?companyProfile=true&detsild=manageGroupMambers" >
                <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
                <div class="hidden-xs">Edit Group Members</div>
            </a>
        <?php } ?>
    </div>
    <?php }*/ ?>

</div>
<?php if(!empty($group_profile_url)) { ?>
    <a class="btn float-right" href="<?= $group_profile_url ?>" target="_blank">View Profile</a>
<?php } ?>
