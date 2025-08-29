<?php
$obj = new Trikona();
if($_GET['enquiry']=='true'){ 

    global $wpdb;
    // Switch to the main site context (ID 1) in a multisite network
    switch_to_blog(1);

    $current_user = wp_get_current_user();
    // $bp_groups_members = $obj->getGroupMembers(['user_id' => $current_user->ID, 'user_title' => 'Group Mod'], $is_single_record = true);
    // $wpcw_emp_inquiry  = $obj->getInquiries(['group_id' => $bp_groups_members->group_id]);
    $active_user_group_id = get_query_var( 'active_user_group_id' );
    $wpcw_emp_inquiry  = $obj->getInquiries(['group_id' => $active_user_group_id]);
    $enquiries_menu_accessible = false;
    $current_user_plan = $obj->getLoggedInUserMembership($current_user->ID);

    if (!empty($current_user_plan)) {
        if ($current_user_plan->membership_id == $obj->corporate_basic_mem_id) {
            $enquiries_menu_accessible = false;
        } else if ($current_user_plan->membership_id == $obj->corporate_prime_mem_id) {
            $enquiries_menu_accessible = false;
        } else if ($current_user_plan->membership_id == $obj->corporate_elite_mem_id) {
            $enquiries_menu_accessible = true;
        }
    }
    // Restore original blog context (important for multisite)
    restore_current_blog();

    $allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);

    if (!$enquiries_menu_accessible && empty($allowed_roles)) { ?>
        <div class="card user-card-full mt-4 text-center">
            <div class="row m-l-0 m-r-0">
                <div class="col-sm-12">
                    <div class="card-block">
                        You are not authorized to access this page.
                    </div>
                </div>
            </div>
        </div>
    <?php }else { ?>
        <div class="col-lg-12 well">
            <div class="row">
                <div class="col-sm-12">
                    <h5>Enquiries List</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wpcw_emp_inquiry as $key => $emp_inquiry) { ?>
                            <tr> 
                                <td><?= $emp_inquiry->Username ?></td>
                                <td><?= $emp_inquiry->userEmap ?></td>
                                <td><?= $emp_inquiry->userPhone ?></td>
                                <td><?= $emp_inquiry->userSubject ?></td>
                                <td><?= $emp_inquiry->userMsg ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>