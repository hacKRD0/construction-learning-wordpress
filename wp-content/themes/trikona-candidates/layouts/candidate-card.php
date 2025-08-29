<?php
global $trikona_obj, $jobs_obj;

switch_to_blog( 1 );  
// layout for user card

// Get the user's data using BuddyPress's get_userdata function
$bpMembserData = get_userdata($bpMembers->ID);

// Retrieve the member type for the current user from user meta
$member_type = get_user_meta($bpMembers->ID, 'bp_member_type', true);

// Query the database to check if the user has a verified status by looking at the 'Verified' field in BuddyPress profile data
$verStatus = $wpdb->get_row("SELECT value FROM ".$wpdb->prefix."bp_xprofile_data AS pd INNER JOIN ".$wpdb->prefix."bp_xprofile_fields AS pf ON pd.field_id = pf.id WHERE pf.name = 'Verified' AND pd.user_id=".$bpMembers->ID);

// Get the user's avatar ID from user meta
$wp_user_avatar =  get_user_meta( $bpMembers->ID ,'wp_user_avatar',true);

// Retrieve the avatar image source (URL) using the image attachment ID
$imguser = wp_get_attachment_image_src($wp_user_avatar);

// Check if the user has been verified based on the 'verStatus' value. If yes, set a specific class for verified users.
if(!empty($verStatus)){
    $userVeri = 'vf-user'; // Verified user class
} else{
    $userVeri = ''; // Non-verified user (empty class)
}

// Encode the user ID in base64 for secure transmission or other purposes
$userId = base64_encode($bpMembserData->ID);

$default_profile = get_user_meta( $bpMembserData->ID, 'default_profile', true );
if ($candidate_role == $trikona_obj->student_role) { ?>
    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
        <div class="card user-card <?php echo $userVeri; ?> w-100">
            <p class="title-mb-type">Student</p>
            <div class="card-body d-flex flex-column">
                <div class="card-block">
                    <div class="user-image">
                        <?php if($imguser==""){ ?>
                            <img src="<?= get_avatar_url($bpMembers->ID ) ?>" >
                        <?php }else{ ?>
                            <img src="<?= $imguser[0] ?>" />
                        <?php } ?>
                    </div>
                    <h6 class="title-name"><?php if($first_name != '') { echo $first_name . ' ' . $last_name; } else { echo '-'; } ?></h6>
                    <hr>
                    <p class="m-t-15 total-exp text-left">
                        <span>Highest Education: </span>
                        <?php
                            // Attempt to get the user's 'Highest Education' value from user meta
                            $highest_education = get_user_meta($bpMembers->ID, 'Highest Education', true);

                            // Check if the 'Highest Education' value is empty
                            if (empty($highest_education)) {
                                // If empty, attempt to retrieve the education data from BuddyPress XProfile using a custom object method
                                $education_data = $trikona_obj->get_xprofile_data(
                                    [
                                        'field_id' => $trikona_obj->highest_education_field_id, // Specify the field ID for 'Highest Education'
                                        'user_id'  => $bpMembers->ID                    // User ID to fetch the data for
                                    ],
                                    $is_single_record = true                           // Indicate that a single record is expected
                                );

                                // If education data was found via XProfile, assign its value to the $highest_education variable
                                if (!empty($education_data)) {
                                    $highest_education = $education_data->value;
                                }
                            }
                        ?>
                        <!-- Output the user's highest education if available; otherwise, display a dash ("-") -->
                        <?= $highest_education ? $highest_education : '-' ?>
                    </p>
                    <p class="current-comp text-left">
                        <span>Course: </span>
                        <?php 
                            // Retrieve the course ID associated with the user from user meta
                            $course_id = get_user_meta($bpMembers->ID, 'course-id', true);

                            // Get the course title using the course ID
                            $course_title = get_the_title($course_id);

                            // If a course title exists, trim it to 2 words and append ellipsis (...)
                            if (!empty($course_title)) {
                                $course_title = wp_trim_words( $course_title, 2, '...' );
                            }
                        ?>

                        <!-- Display the trimmed course title if available; otherwise, display a dash -->
                        <?= $course_title ? $course_title : '-' ?>
                    </p>
                    <?php if(!empty($courses) && isset($courses['found']) && !empty($courses['found'])){
                        $enrolled_course_ids = array_column($courses['results'], 'id');
                        $completed_courses = 0;

                        if (!empty($enrolled_course_ids)) {
                            $filter = [
                                'user_id' => $bpMembers->ID,
                                'course_ids' => $enrolled_course_ids,
                            ];
                            $progress = $jobs_obj->getComletedCourses($filter);

                            $completed_courses = $progress['found'];
                        }
                    ?>
                        <p class="current-comp text-left">
                            <span>Enrolled Courses: </span> <span class="badge badge-primary text-white"><?= $courses['found'] ?></span>
                        </p>
                        <p class="current-comp text-left">
                            <span>Active Courses: </span> <span class="badge badge-warning text-white"><?= ($courses['found'] - $completed_courses) ?></span>
                        </p>
                        <p class="current-comp text-left">
                            <span>Completed Courses: </span> <span class="badge badge-success text-white"><?= $completed_courses ?></span>
                        </p>
                    <?php } ?>
                </div>
                <?php if(!empty($default_profile)){ ?>
                    <a class="user_profile" target="_blank" href="<?= home_url()?>/candidates/member/?user=<?= $userId ?>"> </a>
                <?php } else { ?>
                    <a class="user_profile" target="_blank" href="<?= home_url()?>/members/<?= str_replace(".", "-", $bpMembserData->data->user_login) ?>"> </a>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
        <div class="card user-card <?php echo $userVeri; ?> w-100">
            <p class="title-mb-type">
                <?php if ($candidate_role == $trikona_obj->professional_role) { ?>
                    Professional
                <?php } else if ($candidate_role == $trikona_obj->instructor_role) { ?>
                   Instructor
                <?php } ?>
            </p>
            <div class="card-body d-flex flex-column">
                <div class="card-block">
                    <div class="user-image">
                    	<?php if($imguser==""){ ?>
                            <img src="<?= get_avatar_url($bpMembers->ID) ?>" >
                        <?php }else{ ?>
                            <img src="<?= $imguser[0] ?>" />
                        <?php  } ?>
                    </div>
                    <h6 class="title-name"><?= $first_name . ' ' . $last_name ?></h6>
                    <?php
                        // Attempt to retrieve the user's current designation from user meta
                        $current_designation = get_user_meta($bpMembers->ID, 'designation_current', true);

                        // If no designation is found in user meta, try retrieving it from BuddyPress XProfile fields
                        if (empty($current_designation)) {
                            $designation_data = $trikona_obj->get_xprofile_data(
                                [
                                    'field_id' => $trikona_obj->current_position_field_id, // Field ID for the current position
                                    'user_id'  => $bpMembers->ID                   // ID of the user to fetch data for
                                ],
                                $is_single_record = true                           // Expecting a single record
                            );

                            // If XProfile data is found, assign its value to the current designation variable
                            if (!empty($designation_data)) {
                                $current_designation = $designation_data->value;
                            }
                        }
                    ?>

                    <!-- Output the current designation if available; otherwise, display a dash -->
                    <?= $current_designation ? $current_designation : '-' ?>
                    <hr>
                    <p class="m-t-15 total-exp">
                        <span>Total Expereince: </span>
                        <?php
                            // Retrieve the user's total work experience from user meta (note the typo in the key: 'Total Expereince')
                            $work_experience = get_user_meta($bpMembers->ID, 'Total Expereince', true);

                            // If the value is empty, try to retrieve it from BuddyPress XProfile data
                            if (empty($work_experience)) {
                                $experience_data = $trikona_obj->get_xprofile_data(
                                    [
                                        'field_id' => $trikona_obj->total_expereince_field_id, // Field ID for total experience (should match XProfile setup)
                                        'user_id'  => $bpMembers->ID                   // The user's ID
                                    ],
                                    $is_single_record = true                          // Indicates we expect a single record
                                );

                                // If experience data is found, assign the value to the work_experience variable
                                if (!empty($experience_data)) {
                                    $work_experience = $experience_data->value;
                                }
                            }
                        ?>
                        <!-- Display the total work experience if available; otherwise, show a dash -->
                        <?= $work_experience ? $work_experience : '-' ?>
                    </p>
                    <p class="current-comp">
                        <span>Current Company: </span>
                        <?php
                            // Retrieve the current company name from user meta for the given user ID
                            $current_company = get_user_meta($bpMembers->ID, 'company_current', true); 

                            // If the current company meta is empty, try to get it from BuddyPress XProfile data
                            if(empty($current_company)){
                                $company_data = $trikona_obj->get_xprofile_data(
                                    [
                                        'field_id' => $trikona_obj->current_company_field_id, // BuddyPress field ID for current company
                                        'user_id'  => $bpMembers->ID                   // User ID to fetch data for
                                    ],
                                    $is_single_record = true                           // Expect a single record
                                );

                                // If data is found, assign the value to $current_company
                                if (!empty($company_data)) {
                                    $current_company = $company_data->value;
                                }
                            }
                        ?>

                        <!-- Display the current company if available, otherwise display a dash -->
                        <?= $current_company ? $current_company : '-' ?>
                    </p>
                    <?php if(!empty($default_profile)){ ?>
                        <a class="user_profile" target="_blank" href="<?= home_url()?>/candidates/member/?user=<?= $userId ?>"> </a>
                    <?php } else { ?>
                        <a class="user_profile" target="_blank" href="<?= home_url()?>/members/<?= str_replace(".", "-", $bpMembserData->data->user_login) ?>"> </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>