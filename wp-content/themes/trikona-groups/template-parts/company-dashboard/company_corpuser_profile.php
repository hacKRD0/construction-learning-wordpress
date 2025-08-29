<?php
    $obj = new Trikona();

    // Switch to the main site context (ID 1) in a multisite network
    switch_to_blog(1);

    $current_user = wp_get_current_user();
    $user_meta = get_userdata($current_user->ID);
    $user_roles = $user_meta->roles;
    $user = new BP_Core_User( $current_user->ID );
    $user_avatar = $user->avatar;
    $member_bio= get_user_meta( $current_user->ID, $obj->member_bio_meta ,true);
    $memberDob = get_user_meta( $current_user->ID, $obj->memberDob_meta, true );
    $gender = get_user_meta( $current_user->ID, $obj->gender_meta, true );
    $linkedinProfile = get_user_meta( $current_user->ID, $obj->linkedin_profile_meta, true );
    $phone = bp_get_profile_field_data('field='.$obj->phone_no_field_id.'&user_id='. $current_user->ID);
    $address = bp_get_profile_field_data('field='.$obj->address_field_id.'&user_id='. $current_user->ID);

    // Restore original blog context (important for multisite)
    restore_current_blog();
?>
	<button type="button" id="removeDisable2" class="btn btn-lg btn-info d-none">Edit</button>        
    <div class="row edit-profile">
        <div class="col-sm-12">
            <form class="form"  id="updateUserProfile" name="updateUserProfile" method="POST">  
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>First Name</label>
                        <input name="firtName" type="text" placeholder="Enter First Name Here.." class="form-control required inputDisabled" value="<?= $current_user->user_firstname; ?>" disabled>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Last Name</label>
                        <input name="lastName" type="text" placeholder="Enter Last Name Here.." class="form-control required inputDisabled" value="<?= $current_user->user_lastname; ?>" disabled>
                    </div>
                </div>
                                        
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>Email Address</label>
                        <input type="text" name="emailAdress" placeholder="Enter Email Address Here.." class="form-control required  email" value="<?= $current_user->user_email; ?>" disabled>
                    </div>  
                    <div class="col-sm-6 form-group">
                        <label>Phone</label>
                        <input name="phoneNo" type="text" placeholder="Enter Phone Name Here.." class="form-control required inputDisabled" value="<?= preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $phone); ?>" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control required inputDisabled" disabled>
                            <option value="Male"  <?php if($gender == 'Male'): ?> selected="selected"<?php endif; ?>>Male</option>
                            <option value="Female"  <?php if($gender == 'Female'): ?> selected="selected"<?php endif; ?>>Female</option>
                        </select>
                    </div>  
                    <div class="col-sm-6 form-group">
                        <label>DOB</label>
                        <input name="memberDob" type="text" placeholder="Enter DOB Name Here.." class="form-control StartDate required  inputDisabled" value="<?= $memberDob; ?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Linkedin Profile</label>
                    <input class="form-control inputDisabled" type="text" name="linkedinProfile"  value="<?= $linkedinProfile; ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control valid inputDisabled" aria-invalid="false" disabled><?= $address; ?></textarea>
                </div>  

                <div class="form-group">
                    <label> Your Bio</label>
                    <textarea name="bio" class="form-control valid inputDisabled" aria-invalid="false" disabled><?= $member_bio; ?></textarea>
                </div>

                <div style="display:none;" class="alert alert-success" id="updateproInfo1" role="alert">Your account has been Updated successfully.</div>   

                <button type="submit" class="btn btn-lg btn-info inputDisabled d-none" disabled>Update Profile</button>
                <button type="button" id="removeDisable" class="btn btn-lg btn-info">Edit</button>
            </form> 
        </div>
    </div>
    <script>
        jQuery(document).ready(function(){
            jQuery("#updateUserProfile").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                var form = jQuery(this);
                jQuery.ajax({
                    type: "POST",
                    url : '<?= admin_url( 'admin-ajax.php' ); ?>',
                    data : {action: "update_members_profile", data : form.serialize()},
                   
                    success: function(data) {
                        location.reload(true);
                    }
                });
            });
        });
    </script>