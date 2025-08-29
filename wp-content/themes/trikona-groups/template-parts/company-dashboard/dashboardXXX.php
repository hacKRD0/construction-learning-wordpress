<?php
require_once get_theme_root() . '/class-trikona.php';
$obj = new Trikona();

$current_user = wp_get_current_user();
$linkedin = bp_get_profile_field_data('field='.$obj->linkedin_profile_field_key.'&user_id='. $current_user->ID);
$phone = bp_get_profile_field_data('field='.$obj->phone_field_key.'&user_id='. $current_user->ID);
$mobile = bp_get_profile_field_data('field='.$obj->mobile_field_key.'&user_id='. $current_user->ID);
$address = bp_get_profile_field_data('field='.$obj->address_field_key.'&user_id='. $current_user->ID);
$totalExpereince = bp_get_profile_field_data('field='.$obj->total_expereince_field_key.'&user_id='. $current_user->ID);
$user_meta = get_userdata($current_user->ID);
$member_bio= get_user_meta( $current_user->ID, $obj->member_bio_meta,true);
$memberDob = get_user_meta( $current_user->ID, $obj->memberDob_meta, true );
 $designation_current = get_user_meta( $current_user->ID, $obj->designation_current_meta, true );
 $gender = get_user_meta( $current_user->ID, $obj->gender_meta, true );
 $company_current = get_user_meta( $current_user->ID, $obj->company_current_meta, true );
 $linkedinProfile = get_user_meta( $current_user->ID, $obj->linkedin_profile_meta, true );

    if( $_GET['inquiry']=='' &&  $_GET['mypoints']=='' && $_GET['myaccout']=='' && $_GET['memberships']=='' && $_GET['managesEmp']=='' && $_GET['companyProfile']=='' && $_GET['managesJobs']=='' && $_GET['managestudent']=='' && $_GET['updateprofile']=="" && $_GET['updateavatar']==""){?>
        <div class="card user-card-full">
            <div class="row m-l-0 m-r-0">
            <div class="col-sm-12">
                            <div class="card-block">
                                <!-- First Row -->
                                <div class="row profile-readfull">
                                    <!-- First col -->
                                    <div class="col-md-6">
                                        <h6 class="cs-label">About Me</h6>
                                        <p class="cs-data"><?php echo $member_bio;?></p>
                                    </div>
                                    <!--Second col  -->
                                    <div class="col-md-6">
                                        <!-- Email -->
                                        <h6 class="cs-label">Email :
                                            <span class="cs-data"><?php echo  $current_user->user_email ; ?></span>
                                        </h6>
                                        <!-- Phone -->
                                        <h6 class="cs-label">Phone :
                                            <span class="cs-data"><?php echo $phone;?></span>
                                        </h6>
                                        <!-- DOB -->
                                        <h6 class="cs-label">DOB :
                                            <span class="cs-data"><?php echo $memberDob;?></span>
                                        </h6>
                                        <!-- Gender -->
                                        <h6 class="cs-label">Gender :
                                            <span class="cs-data"><?php  echo $gender;?></span>
                                        </h6>
                                        <!-- Linkedin -->
                                        <h6 class="cs-label">Linkedin Profile :
                                            <span class="cs-data"><?php echo $linkedinProfile;?></span>
                                        </h6>
                                        <!-- Address -->
                                        <h6 class="cs-label">Address :
                                            <span class="cs-data"><?php echo $address;?></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                      
           
                    </div>  
                    </div>
                </div>
            </div>
        </div>

       <?php } ?>