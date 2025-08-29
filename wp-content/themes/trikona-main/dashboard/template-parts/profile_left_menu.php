<?php
    $url_prefix = '?';
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $current_user = get_user_by( 'ID', base64_decode($_GET['id']) );
        if (empty($current_user)){
            $current_user = wp_get_current_user();
        } else {
            $url_prefix .= 'id='.$_GET['id']."&";
        }
    } else {
        $current_user = wp_get_current_user();
    }
    $userId = base64_encode($current_user->ID); 
    $default_profile_url = home_url().'/candidates/member/?user='.$userId;
    if (empty($default_profile)) {
        $default_profile_url = home_url().'/members/'.str_replace(".", "-", $bpMembserData->data->user_login);
    }
?>
<div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
    <h4 class="disp-name">Hello, <?php echo  $current_user->user_firstname; ?> <?php echo  $current_user->user_lastname ; ?></h4>
    <div class="logo logostyle">
        <a hef="#"><?php echo $user_avatar;?></a>
    </div>
    <div class="update-img">
        <a href="<?php echo home_url()?>/user-dashboard/?updateavatar=true"><span class="btn btn-change-img">Update Image</span></a>
    </div>
    <div class="navi">
        <ul style="padding-left:0">
            <?php if($_GET['mypoints']=='' && $_GET['myaccout']=='' && $_GET['memberships']=='' && $_GET['updateEducations']=='' && $_GET['updateExpereince']=='' && $_GET['updateprofile']=="" && $_GET['updateavatar']==""){
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
                if($_GET['updateEducations']=='true'){
                $updateEducations = 'active';
                }
                
                if($_GET['updateExpereince']=='true'){
                $updateExpereince = 'active';
                }
                if($_GET['updateprofile']=='true'){
                $updateprofile = 'active';
                }
                
                if($_GET['upload-resume']=='true'){
                $uploadcv = 'active';
                }
                if($_GET['resume']=='true'){
                $resume = 'active';
                }
                if($_GET['job-applications']=='true'){
                $job = 'active';
                }
                if($_GET['career']=='true'){
                $career = 'active';
                }
                ?>
            <li class="<?php echo $activeProfile;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= str_replace("&", "", $url_prefix) ?>"><i class="fa fa-bar-chart" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Update Profile</span></a>
            </li>
            <li  class="">
            	<a target="_blank" href="<?= $default_profile_url ?>"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">View Profile </span></a>
            </li>
            <li class="<?php echo $updateExpereince;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>updateExpereince=true"><i class="fa fa-history" aria-hidden="true"></i></i><span class="hidden-xs hidden-sm">Experience </span></a>
            </li>
            <li class="<?php echo $updateEducations;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>updateEducations=true"><i class="fa fa-graduation-cap" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Educations</span></a>
            </li>
            <li class="<?php echo $memberships;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>memberships=true"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Memberships </span></a>
            </li>
            <li class="<?php echo $myaccout;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>myaccout=true"><i class="fa fa-book" aria-hidden="true"></i><span class="hidden-xs hidden-sm">My Account </span></a>
            </li>
            <li class="<?php echo $mypoints;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>mypoints=true"><i class="fa fa-money" aria-hidden="true"></i><span class="hidden-xs hidden-sm">My Points </span></a>
            </li>
            <?php if (!in_array('instructor', $userRolesChk)){ ?>
            <li class="<?php echo $career;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>career=true&tabs=ChooseCv"><i class="fa fa-briefcase"></i></i><span class="hidden-xs hidden-sm">Career </span></a>
            </li>
            <?php } ?>
            <li  class="<?php echo $uploadcv;?>">
            	<a href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>upload-resume=true"><i class="fa fa-download" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Upload Resume </span></a>
            </li>
        </ul>
    </div>
</div>