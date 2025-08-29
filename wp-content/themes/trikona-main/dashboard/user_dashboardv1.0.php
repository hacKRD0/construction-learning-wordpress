<?php
    /*
        Template Name: User Dashboard1.0
    */
    
    if (!is_user_logged_in()) {
      wp_redirect(home_url('/user-login/'));
       exit;
    }
    get_header();
    // $current_user = wp_get_current_user();
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $current_user = get_user_by( 'ID', base64_decode($_GET['id']) );
        if (empty($current_user))
            $current_user = wp_get_current_user();
    } else {
        $current_user = wp_get_current_user();
    }
    
    global $wpdb;
    $linkedin = bp_get_profile_field_data('field=Linkedin Profile&user_id='. $current_user->ID);
    $phone = bp_get_profile_field_data('field=Phone&user_id='. $current_user->ID);
    $mobile = bp_get_profile_field_data('field=Mobile&user_id='. $current_user->ID);
    $address = bp_get_profile_field_data('field=Address&user_id='. $current_user->ID);
    $totalExpereince = bp_get_profile_field_data('field=Total Expereince&user_id='. $current_user->ID);
    //$skill = bp_get_profile_field_data('field=Skills&user_id='. $current_user->ID);
    $user_meta = get_userdata($current_user->ID);
    $user_roles = $user_meta->roles;
    $user = new BP_Core_User( $current_user->ID );
    $user_avatar = $user->avatar;
    
    if(is_array($skill)){
    $skill = $skill;
    }else{
    $skill = array();
    }
    global $wpdb,$bp;
    $results = $wpdb->get_results("SELECT field.id as id, field.name as name FROM {$bp->profile->table_name_fields} as field INNER JOIN {$bp->profile->table_name_meta} as meta ON field.id = meta.object_id
            WHERE meta.object_type = 'field' AND meta.meta_key = 'do_autolink' AND meta.meta_value = 'on'");
    $members_experience = get_user_meta( $current_user->ID,'members_experience_',true);
    $exp= get_user_meta( $current_user->ID,'Total Expereince',true);
    $hedu= get_user_meta( $current_user->ID,'Highest Education',true);
    $skill= get_user_meta( $current_user->ID,'Skills',true);
    $crstd= get_user_meta( $current_user->ID,'Current Year Of Study',true);
    $yearpass= get_user_meta( $current_user->ID,'Year Of Passout',true);
    $toatlStudy= get_user_meta( $current_user->ID,'Total year of study',true);
    $usersMetaArr = array($exp,$hedu,$skill,$crstd,$yearpass,$toatlStudy);
    $member_bio= get_user_meta( $current_user->ID,'member_bio',true);
    $members_education = get_user_meta( $current_user->ID,'members_education_',true);
    $skill_set = get_user_meta( $current_user->ID,'skill_set',true);
    
    $memberDob = get_user_meta( $current_user->ID, 'memberDob', true );
    $designation_current = get_user_meta( $current_user->ID, 'designation_current', true );
    $gender = get_user_meta( $current_user->ID, 'gender', true );
    $company_current = get_user_meta( $current_user->ID, 'company_current', true );
    $linkedinProfile = get_user_meta( $current_user->ID, 'linkedinProfile', true );
    $member_graduation = get_user_meta( $current_user->ID, 'member_graduation', true );
    $Institute = get_user_meta( $current_user->ID, 'Institute', true );
    $userRolesChk = ( array ) $current_user->roles;
    if($crstd==""){
    $crstd ='NA';
    }
    if($yearpass==""){
    $yearpass ='NA';
    }
    if($toatlStudy==""){
    $toatlStudy ='NA';
    }
    if($exp==""){
    $exp ='NA';
    }
    
    function RemoveSpecialChar($str) {
        $res = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $str);
        return $res;
    }

    // Get the user's data using BuddyPress's get_userdata function
    $bpMembserData = get_userdata($current_user->ID);
    $default_profile = get_user_meta( $bpMembserData->ID, 'default_profile', true );
?>
<link href='<?php echo get_stylesheet_directory_uri()?>/dashboard/assets/css/user-dashboard-addon.css' rel="stylesheet" id="Dashboard Extension"/>
<?php /*<link href='<?php echo get_stylesheet_directory_uri()?>/dashboard/assets/css/user-dashboard.css'  rel="stylesheet" id="Dashboard Style"/>*/ ?>
<style>
    i.fa.fa-money-bill-alt:after {
        content: "";
    }
</style>
<div class="container-fluid display-table">
    <div class="row display-table-row w-100">
        <?php 
            /**
            * Inclues left menus
            * @Trikona plugin Vesrion 1.0.0.
            */
            include_once( get_stylesheet_directory() .'/dashboard/template-parts/profile_left_menu.php');    
        ?>
        <div class="col-md-10 col-sm-11 display-table-cell v-align">
            <div class="user-dashboard">
                <?php 
                    /**
                    * Includes Top Head files
                    * @Trikona plugin Vesrion 1.0.0.
                    */
                    include_once( get_stylesheet_directory() .'/dashboard/template-parts/top_head.php');    
                ?>
                <?php 
                    /**
                    * Includes Dashbaord file
                    * @Trikona plugin Vesrion 1.0.0.
                    */
                    include_once( get_stylesheet_directory() .'/dashboard/template-parts/update_profile.php');
                    if (isset($_GET['upload-resume'])) {
                        // Download Resume
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/download_resume.php');
                    }
                    if (isset($_GET['memberships'])) {
                        // Memberships
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/memberships.php');
                    }
                    if (isset($_GET['myaccout'])) {
                        // My Account
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/my_account.php');
                    }
                    if (isset($_GET['mypoints'])) {
                        // My Points
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/my_points.php');
                    }
                    if (isset($_GET['updateExpereince'])) {
                        // Update Experience
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/updateExpereince.php');
                    }
                    if (isset($_GET['updateEducations'])) {
                        // Update Educations
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/updateEducations.php');
                    }
                    if (isset($_GET['updateavatar'])) {
                        // Update Avatar
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/updateavatar.php');
                    }
                    include_once( get_stylesheet_directory() .'/dashboard/template-parts/upload_resume.php');
                    
                    
                    
                    if($_GET['job-applications']){
                        echo do_shortcode('[past_applications]');
                    }
                    
                    if (isset($_GET['career'])) {
                        // Career
                        include_once(get_stylesheet_directory() . '/dashboard/template-parts/career.php');
                    }
                    // include_once( get_stylesheet_directory() .'/dashboard/template-parts/career.php');
                ?>
            </div>
        </div>
    </div>
</div>
<?php //}
    //print_r($userRolesChk);
    if (in_array('subscriber', $userRolesChk)){
         require_once( get_stylesheet_directory() . '/templates/subscriber_profile.php');
    } 
    wp_enqueue_style('styleprofiles');
    if($_GET['career']==""){ 
?>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'  rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js" ></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<?php }
    get_footer();    
?>
<script> 
    var ajaxurl= '<?php echo admin_url( 'admin-ajax.php' )?>'
</script>
<script src="<?php echo get_stylesheet_directory_uri()?>/dashboard/assets/members_profiles.js"></script>