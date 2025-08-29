<?php 
/**
 * Template Name: College Dashboard Templates
 * 
 *
 * @package Trikona
 * @subpackage Trikona
 * @since Trikona 1.0
 */
global $wpdb, $bp, $trikona_obj;

get_header(); 

$check_response = checkDashboardAccessibility($type = $trikona_obj->bp_group_type_college);

//Access to the college dashboard should be restricted exclusively to users with the 'college_admin' role
if(get_current_user_id() > 0  && $check_response['success']){
    $current_user = wp_get_current_user();
    $current_user_roles = $current_user->roles;
    $linkedin = bp_get_profile_field_data('field='.$trikona_obj->linkedin_profile_field_key.'&user_id='. $current_user->ID);
    $phone = bp_get_profile_field_data('field='.$trikona_obj->phone_field_key.'&user_id='. $current_user->ID);
    //$mobile = bp_get_profile_field_data('field='.$trikona_obj->mobile_field_key.'&user_id='. $current_user->ID);
    $address = bp_get_profile_field_data('field='.$trikona_obj->address_field_key.'&user_id='. $current_user->ID);
    //$totalExpereince = bp_get_profile_field_data('field='.$trikona_obj->total_expereince_field_key.'&user_id='. $current_user->ID);
    //$skill = bp_get_profile_field_data('field=Skills&user_id='. $current_user->ID);
    $user_meta = get_userdata($current_user->ID);
    $user_roles = $user_meta->roles;
    $user = new BP_Core_User( $current_user->ID );
    $user_avatar = $user->avatar;

    $results = $trikona_obj->getGroupMembers(['user_id' => $current_user->ID], $is_single_record = true);

    $members_experience = get_user_meta( $current_user->ID, $trikona_obj->members_experience_meta ,true);
    $exp= get_user_meta( $current_user->ID,$trikona_obj->total_expereince_meta,true);
    $hedu= get_user_meta( $current_user->ID,$trikona_obj->highest_education_meta,true);
    $skill= get_user_meta( $current_user->ID,$trikona_obj->skills_meta,true);
    $crstd= get_user_meta( $current_user->ID,$trikona_obj->current_year_of_study_meta,true);
    $yearpass= get_user_meta( $current_user->ID,$trikona_obj->year_of_passout_meta,true);
    $toatlStudy= get_user_meta( $current_user->ID,$trikona_obj->total_year_of_study_meta,true);
    $usersMetaArr = array($exp,$hedu,$skill,$crstd,$yearpass,$toatlStudy);
    $member_bio= get_user_meta( $current_user->ID,$trikona_obj->member_bio_meta,true);
    $members_education = get_user_meta( $current_user->ID,$trikona_obj->members_education_meta,true);
    $skill_set = get_user_meta( $current_user->ID,$trikona_obj->skill_set_meta,true);

    $memberDob = get_user_meta( $current_user->ID, $trikona_obj->memberDob_meta, true );
    $designation_current = get_user_meta( $current_user->ID, $trikona_obj->designation_current_meta, true );
    $gender = get_user_meta( $current_user->ID, $trikona_obj->gender_meta, true );
    $company_current = get_user_meta( $current_user->ID, $trikona_obj->company_current_meta, true );
    $linkedinProfile = get_user_meta( $current_user->ID, $trikona_obj->linkedin_profile_meta, true );
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

    // To obtain the membership details of the logged-in user.
    $current_user_plan = $trikona_obj->getLoggedInUserMembership($current_user->ID);
    $college_membership = $trikona_obj->college_membership;

    $college_elite_membership_id = $college_membership ? end($college_membership) : '';
    $current_membership_id = $current_user_plan ? $current_user_plan->membership_id : '';

    $active_user_group_id = $check_response['group_id'];
?>

<div class="container-fluid display-table">
    <div class="row display-table-row">
        <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
            <h4 class="disp-name"><?= $current_user->user_firstname ?>
                <?= $current_user->user_lastname ?></h4>
            <div class="logo logostyle">
                <a hef="#"><?= $user_avatar ?></a>
            </div>
            <div class="update-img">
                <a href="<?= home_url() ?>/college-dashboard/?updateavatar=true"><span class="btn btn-change-img">Change avatar</span></a>
            </div>
           <?php 
/**
 * Left menu add file @
 *
 * @return Display left header data 
 * @author Trikona 2023
 */

echo get_template_part( 'template-parts/college-dashboard/college_left_menu' );


?>   
        </div>
        <div class="col-md-10 col-sm-11 display-table-cell v-align">
            <!--<button type="button" class="slide-toggle">Slide Toggle</button> -->
            <div class="row">

            </div>
            <div class="user-dashboard">
<?php
    set_query_var('active_user_group_id', $active_user_group_id);
    echo get_template_part( 'template-parts/college-dashboard/college_top_header' );

    if($_GET['memberships']=='true'){
        echo get_template_part( 'template-parts/college-dashboard/college_memberships' );
    }
    if($_GET['myaccout']=='true'){
        echo get_template_part( 'template-parts/college-dashboard/college_myaccount' );
    }
    if($_GET['mypoints']=='true'){
        echo do_shortcode('[mycred_history user_id='.get_current_user_id().' type=trikona_credit]');
    }
    // Display the College Profile exclusively for users who possess either College Prime or College Elite membership.
    if($_GET['collegeData']=='true' && $_GET['tab']=="" && ((!empty($current_membership_id) && !empty($college_membership) && in_array($current_user_plan->membership_id, $college_membership)) || !empty($trikona_obj->checkIsAccessibleForCurrentUser($current_user, 'college')))){ 
        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
        echo get_template_part( 'template-parts/college-dashboard/edit-college/tab_general_info' );
    }
    if($_GET['collegeData']=='true' && $_GET['tab']=="college-banner"){ 
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
        echo get_template_part( 'template-parts/college-dashboard/banner' );
    }
    if($_GET['collegeData']=='true' && $_GET['tab']=="college-logo"){
        set_query_var('active_user_group_id', $active_user_group_id); 
        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
        echo get_template_part( 'template-parts/college-dashboard/college_logo' );
    }
    if($_GET['collegeData']=='true' && $_GET['tab']=="courses"){ 
        set_query_var('active_user_group_id', $active_user_group_id);
        echo get_template_part( 'template-parts/college-dashboard/college_profile_menu' );
        echo get_template_part( 'template-parts/college-dashboard/college_courses' );
    }
?>


 <?php if($_GET['banner']=='' && $_GET['mypoints']=='' && $_GET['myaccout']=='' &&  $_GET['memberships']=='' && $_GET['collegeData']=='' && $_GET['managestudent']=='' && $_GET['updateprofile']=="" && $_GET['updateavatar']==""){?>

        <button type="button" id="removeDisable" class="btn btn-lg btn-info">Edit</button>
        <div class="row edit-profile">
            <div class="col-md-12">
                <form class="form" id="updateUserProfile" name="updateUserProfile" method="POST">



                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>First Name</label>
                            <input name="firtName" type="text" placeholder="Enter First Name Here.."
                                class="form-control required inputDisabled"
                                value="<?= $current_user->user_firstname ?>" disabled>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Last Name</label>
                            <input name="lastName" type="text" placeholder="Enter Last Name Here.."
                                class="form-control required inputDisabled"
                                value="<?= $current_user->user_lastname ?>" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Email Address</label>
                            <input type="text" name="emailAdress" placeholder="Enter Email Address Here.."
                                class="form-control required  email" value="<?= $current_user->user_email ?>"
                                disabled>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Phone</label>
                            <input name="phoneNo" type="text" placeholder="Enter Phone Name Here.."
                                class="form-control required inputDisabled"
                                value="<?php echo preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $phone);?>" disabled>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control required  inputDisabled" disabled>
                                <option value="Male" <?php if($gender == 'Male'): ?> selected="selected"
                                    <?php endif; ?>>Male</option>
                                <option value="Female" <?php if($gender == 'Female'): ?> selected="selected"
                                    <?php endif; ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>DOB</label>
                            <input name="memberDob" type="text" placeholder="Enter DOB Name Here.."
                                class="form-control StartDate required inputDisabled" value="<?= $memberDob ?>"
                                disabled>
                        </div>

                    </div>
                    <div class="form-group">
                        <label>Linkedin Profile</label>
                        <input class="form-control inputDisabled" type="text" name="linkedinProfile"
                            value="<?= $linkedinProfile ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control valid inputDisabled" aria-invalid="false"
                            disabled><?= $address ?></textarea>

                    </div>

                    <div class="form-group">
                        <label> Your Bio</label>
                        <textarea name="bio" class="form-control valid inputDisabled" aria-invalid="false"
                            disabled><?= $member_bio ?></textarea>

                    </div>
                    <div style="display:none;" class="alert alert-success" id="updateproInfo1" role="alert">Your account
                        has been Updated successfully.</div>

                    <button type="submit" class="btn btn-lg btn-info inputDisabled" disabled>Update Profile</button>

                </form>
            </div>
        </div>


        <?php } ?>

        <?php if($_GET['updateavatar']=='true'){?>

        <!-- Upload profile -->
        <div class="col-xxl-4">
            <div class="bg-secondary-soft px-4 py-5 rounded">
                <div class="row g-3">
                    <h4 class="mb-4 mt-0 text-center">Upload your profile photo</h4>
                    <div class="text-center">
                        <!-- Image upload -->
                        <div class="square position-relative display-2 mb-3 about-avatar">
                            <?= $user_avatar ?>
                        </div>

                        <form method="post" action="" enctype="multipart/form-data" id="myform">
                            <label class="custom-file-upload">
                                <input id="imgInp" name="file" type="file" />
                                Change Image
                            </label>
                            <input type="button" class="button" value="Update" id="but_upload">
                        </form>

                        <!-- Content -->
                        <p class="text-muted mt-3 mb-0"><span class="me-1">Note:</span>Minimum size 300px x 300px</p>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Row END -->
    <?php } ?>


    <?php
        // Display the Students template exclusively for users with a Corporate Elite membership.
        if($_GET['managestudent']=='true' && ((!empty($current_membership_id) && !empty($college_elite_membership_id) && $current_membership_id == $college_elite_membership_id) || !empty($trikona_obj->checkIsAccessibleForCurrentUser($current_user, 'college')))){
            set_query_var('active_user_group_id', $active_user_group_id);
            echo get_template_part( 'template-parts/college-dashboard/college_manage_students' );
        }
    ?>
</div>
</div>
</div>

</div>


<link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
    var ajaxurl = "<?= admin_url('admin-ajax.php') ?>";
</script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="<?= get_stylesheet_directory_uri() ?>/assets/js/college_profile.js"></script>

<?php } else { ?>
    <div class="container-fluid cs-directory-page">
        <div class="card pt-4">
            <div class="card-block text-center">
                <h3><?= $check_response['message'] ?></h3>
            </div>
        </div>
    </div>
<?php } get_footer(); ?>
