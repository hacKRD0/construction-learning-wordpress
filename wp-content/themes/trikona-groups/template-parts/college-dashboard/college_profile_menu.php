<?php
    
    global $trikona_obj;

    $group_id = isset($_GET['groupId']) ? $_GET['groupId'] : "";
    $group_type = isset($_GET['groupType']) ? $_GET['groupType'] : "";

    switch_to_blog($trikona_obj->main_site_blog_id);
    $current_user = wp_get_current_user();
    restore_current_blog();

    // To obtain the membership details of the logged-in user.
    $current_user_plan = $trikona_obj->getLoggedInUserMembership($current_user->ID);
    $college_membership = $trikona_obj->college_membership;

    $college_membership_ids = $college_membership ? array_slice($college_membership, 1) : '';
    $current_membership_id = $current_user_plan ? $current_user_plan->membership_id : '';
    $allowed_roles = $trikona_obj->checkIsAccessibleForCurrentUser($current_user);

    if($_GET['collegeData'] == true){
        $activeMenu1 = "btn-primary";
    }else{
        $activeMenu1 = "btn-default";
    }

    if($_GET['collegeData'] == true && $_GET['tab'] == 'college-banner'){
        $activeMenu2 = "btn-primary";
    }else{
        $activeMenu2 = "btn-default";
    }

    if($_GET['collegeData'] == true && $_GET['tab'] == 'college-logo'){
        $activeMenu3 = "btn-primary";
    }else{
        $activeMenu3 = "btn-default";
    }

    if($_GET['collegeData'] == true && $_GET['tab'] == 'courses'){
        $activeMenu4 = "btn-primary";
    }else{
        $activeMenu4 = "btn-default";
    }

    if($_GET['collegeData'] == true && $_GET['tab'] == 'staff'){
        $activeMenu5 = "btn-primary";
    }else{
        $activeMenu5 = "btn-default";
    }
    $base_url = home_url();
    if (!empty($group_id) && !empty($group_type)) {
        switch_to_blog($trikona_obj->directories_site_blog_id);
        $base_url = home_url();
        restore_current_blog();
    }
?>
<div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="stars" class="btn <?= $activeMenu1 ?>" href="<?= $base_url ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
               <div class="hidden-xs">General Info</div>
            </a>
        <?php } else { ?>
            <a type="button" id="stars" class="btn <?= $activeMenu1 ?>" href="<?= $base_url ?>/college-dashboard/?collegeData=true"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
               <div class="hidden-xs">General Info</div>
            </a>
        <?php } ?>
    </div>

    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="favorites" class="btn <?= $activeMenu2 ?>" href="<?= $base_url ?>/manage-group-dashboard/?groupId=<?= $group_id ?>&groupType=<?= $group_type ?>&tab=college-banner" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">Banner </div>
            </a>
        <?php } else { ?>
            <a type="button" id="favorites" class="btn <?= $activeMenu2 ?>" href="<?= $base_url ?>/college-dashboard/?collegeData=true&tab=college-banner" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">Banner </div>
            </a>
        <?php } ?>
    </div>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="following" class="btn <?= $activeMenu3 ?>" href="<?= $base_url ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&tab=college-logo"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">College Logo</div>
            </a>
        <?php } else { ?>
            <a type="button" id="following" class="btn <?= $activeMenu3 ?>" href="<?= $base_url ?>/college-dashboard/?collegeData=true&tab=college-logo"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">College Logo</div>
            </a>
        <?php } ?>
    </div>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="following" class="btn <?= $activeMenu4 ?>" href="<?= $base_url ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&tab=courses"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">Courses</div>
            </a>
        <?php } else { ?>
            <a type="button" id="following" class="btn <?= $activeMenu4 ?>" href="<?= $base_url ?>/college-dashboard/?collegeData=true&tab=courses"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">Courses</div>
            </a>
        <?php } ?>
    </div>
    
    <?php 
        // Display the staff tab exclusively for users who possess either College Prime or College Elite membership.
        if((!empty($college_membership_ids) && !empty($current_membership_id) && in_array($current_membership_id, $college_membership_ids)) || !empty($allowed_roles)) {
    ?>
    <div class="btn-group" role="group">
        <?php if(!empty($group_id)) { ?>
            <a type="button" id="following" class="btn <?= $activeMenu5 ?>" href="<?= $base_url ?>/manage-group-dashboard/?groupId=<?= $group_id; ?>&groupType=<?= $group_type ?>&tab=staff"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">Staff</div>
            </a>
        <?php } else { ?>
            <a type="button" id="following" class="btn <?= $activeMenu5 ?>" href="<?= $base_url ?>/college-dashboard/?collegeData=true&tab=staff"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
               <div class="hidden-xs">Staff</div>
            </a>
        <?php } ?>
    </div>
    <?php } ?>
      
</div>