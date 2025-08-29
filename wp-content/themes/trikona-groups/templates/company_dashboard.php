<?php
/**
 * Template Name: Company Dashboard Templates
 * @since Trikona 1.0
 */

global $wpdb, $bp, $trikona_obj;

get_header();
$check_response = checkDashboardAccessibility($type = $trikona_obj->bp_group_type_company);

if(get_current_user_id() > 0 && $check_response['success']){
    // Switch to the main site context (ID 1) in a multisite network
    switch_to_blog(1);

    $current_user = wp_get_current_user();
    $user_meta = get_userdata($current_user->ID);
    $user_roles = $user_meta->roles;
    $user = new BP_Core_User( $current_user->ID );
    $user_avatar = $user->avatar;
    $member_bio= get_user_meta( $current_user->ID, $trikona_obj->member_bio_meta ,true);
    $memberDob = get_user_meta( $current_user->ID, $trikona_obj->memberDob_meta, true );
    $gender = get_user_meta( $current_user->ID, $trikona_obj->gender_meta, true );
    $linkedinProfile = get_user_meta( $current_user->ID, $trikona_obj->linkedin_profile_meta, true );
    $phone = bp_get_profile_field_data('field='.$trikona_obj->phone_no_field_id.'&user_id='. $current_user->ID);

    // Restore original blog context (important for multisite)
    restore_current_blog();
    $active_user_group_id = $check_response['group_id'];
?>

<?php //  Display User Name, Avatar & left menu on the left sideboard ?>
<div class="container-fluid display-table">
    <div class="row display-table-row">
    	<?php  if(get_current_user_id() > 0){ ?>	
        <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
            <h4 class="disp-name"> <?= $current_user->user_firstname ?>
                <?= $current_user->user_lastname ?></h4>
                    <div class="logo logostyle">
                        <a hef="#"><?= $user_avatar ?></a>
                    </div>
                <div class="update-img">
            <a href="<?php echo home_url(); ?>/company-dashboard/?updateavatar=true"><span
                    class="btn btn-change-img">Change avatar</span></a>
        </div>
    <?php 
        // Left sideboard menu
            echo get_template_part( 'template-parts/company-dashboard/company_left_menu' ); 
    ?>
</div>

<div class="col-md-10 col-sm-11 display-table-cell v-align">
    <div class="row">        </div>



<div class="user-dashboard">                   
    <?php 
    // top header
        echo get_template_part( 'template-parts/company-dashboard/company_top_header' );

 
    //Enquiry Tab
    
    if($_GET['enquiry']=='true'){ 
        echo get_template_part( 'template-parts/company-dashboard/company_enquiry' );
    } 	

    // Mmebrships Tab
    if($_GET['memberships']=='true'){
        echo get_template_part( 'template-parts/company-dashboard/company_memberships');
      }

    // Myaccout Tab
    
    if($_GET['myaccount']=='true'){ 
        echo get_template_part( 'template-parts/company-dashboard/company_myaccount');
      }

    // My Points
    if($_GET['mypoints']=='true'){ 
         echo do_shortcode('[mycred_history user_id='.get_current_user_id().' type=trikona_credit]');
         } 
         
    //Company Gen profile
    if($_GET['companyProfile']=='true' && $_GET['detsild']==""){ 
        set_query_var('active_user_group_id', $active_user_group_id);
    	echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
        echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_general');
     } 

    // Company Logo
    if($_GET['companyProfile']=='true' && $_GET['detsild']=="logo"){ 
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');	
        echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_company_logo');
     } 

    //Company Services
    if($_GET['companyProfile']=='true' && $_GET['detsild']=="services"){ 
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');	
        echo get_template_part( 'template-parts/company-dashboard/edit-company//tab_services');	
     } 
 
    //Company Branches
    if($_GET['detsild']=="branch" && $_GET['companyProfile']=='true'){
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
        echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_branches');	
    }

    //Company Projects
    if($_GET['detsild']=="projects" && $_GET['companyProfile']=='true'){
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
        echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_projects');	
    }

    //Company Banner
    if($_GET['detsild']=="banner" && $_GET['companyProfile']=='true'){
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
        echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_company_banner');	
    }
    
    //Company Docuemnts
    if($_GET['detsild']=="document" && $_GET['companyProfile']=='true'){
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
        echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_company_documents');	
    }

    //Manage Group Mambers
    if($_GET['detsild']=="manageGroupMambers" && $_GET['companyProfile']=='true'){
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_profile_menu');
        echo get_template_part( 'template-parts/company-dashboard/edit-company/tab_manage_group_member');   
    }
    
    //Corp User Profile
    //Manage Jobs
    if($_GET['managesJobs']=='true'){
        echo do_shortcode('[job_dashboard]');
    }
    if($_GET['managesEmp']=='true'){
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/company-dashboard/company_employees');
    }
    
    if (empty($_GET)) {
        echo get_template_part( 'template-parts/company-dashboard/company_corpuser_profile');
    }
} } else { ?>
    <div class="container-fluid cs-directory-page">
        <div class="card pt-4">
            <div class="card-block text-center">
                <h3><?= $check_response['message'] ?></h3>
            </div>
        </div>
    </div>
<?php } get_footer();
?>

<script type="text/javascript">
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/company_profile.js"></script>