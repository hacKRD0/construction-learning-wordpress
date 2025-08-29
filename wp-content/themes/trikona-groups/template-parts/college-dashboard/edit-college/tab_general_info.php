<?php
    $obj = new Trikona();

    global $wpdb;

    switch_to_blog(1);
    $current_user = wp_get_current_user();
    $group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";

    $results = $obj->getGroupMembers(['user_id' => $current_user->ID], $is_single_record = true);

    if (empty($group_id)) {
        $group_id = $results->group_id;
    }

    if($_POST['group_id']!=""){
       $state_id=   $_POST['state'];
        $stateid = $obj->getStates(['id' => $state_id]);
        groups_update_groupmeta( $_POST['group_id'], $obj->company_staff_meta ,$_POST['staff'] );
        groups_update_groupmeta( $_POST['group_id'], $obj->state_meta, $stateid->name );
        groups_update_groupmeta( $_POST['group_id'], $obj->gstates_meta, $state_id );

        groups_update_groupmeta( $_POST['group_id'], $obj->company_website_url_meta ,$_POST['website_url'] );
        groups_update_groupmeta( $_POST['group_id'], $obj->city_meta ,$_POST['city'] );
        groups_update_groupmeta( $_POST['group_id'], $obj->address_meta ,$_POST['address'] );
        groups_update_groupmeta( $_POST['group_id'], $obj->email_address_meta ,$_POST['college_email'] );
        groups_update_groupmeta( $_POST['group_id'], $obj->phone_number_meta ,$_POST['collegePhone'] );
        $table_name = $wpdb->prefix . "bp_groups";

        $wpdb->update( $table_name, array('description' => $_POST['company_descriptions']),array('ID'=>$_POST['group_id']));
    }

    $groupsArr = $obj->getBPGroups(['id' => $group_id]);
    // $groupmeta = $wpdb->get_results($query);
    restore_current_blog();

    $staff_collage  = groups_get_groupmeta( $group_id, $obj->company_staff_meta ,true );
    $State  = groups_get_groupmeta( $group_id, $obj->state_meta ,true ); 
    $grpwebsite  = groups_get_groupmeta( $group_id, $obj->company_website_url_meta ,true );
    $grpcity  = groups_get_groupmeta( $group_id, $obj->city_meta ,true );
    $groupAddress  = groups_get_groupmeta( $group_id, $obj->address_meta ,true );
    $email  = groups_get_groupmeta( $group_id, $obj->email_address_meta ,true );
    $phone  = groups_get_groupmeta( $group_id, $obj->phone_number_meta ,true );
    $gstates32470  = groups_get_groupmeta( $group_id, $obj->gstates_meta ,true );
?>
<div class="col-lg-12 well">
    <div class="row">
        <div class="col-sm-12">
            <form class="form" id="updatecollegeDatas" name="updatecollegeDatas" method="POST">
                <div class="row">
                    <input name="group_id" type="hidden" value="<?=  $groupsArr->id; ?>">
                    <div class="col-sm-6 form-group">
						<label>Collage Name</label>
						<input disabled type="text"  class="form-control" value="<?=  $groupsArr->name; ?>">
					</div>
				</div>
                <?php $statelist = $obj->getStates(); ?>
    			<div class="row">
        			<div class="col-sm-6 form-group">
        				<label>State</label>
        				<select  name="state" style="width:100%" class="form-control inputDisabled" id="state-dropdown" disabled>
                             <option value="">Select State</option>
                             <?php foreach ($statelist as $key => $state) {?>
                            <option value="<?= $state->id; ?>" <?php if( $state->id == $gstates32470 ): ?> selected="selected"<?php endif; ?>><?= $state->name; ?></option>
                             <?php }?>
                        </select>
        			</div>
		
        		    <div class="col-sm-6 form-group">
        				<label>City </label>
        				<select name="city" style="width:100%" class="form-control inputDisabled" id="city-dropdown" disabled>
        					<option value="<?= $grpcity;?>"><?= $grpcity;?></option>

                        </select>
        			</div>
	            </div>

                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>College Email </label>
                        <input type="text" name="college_email" placeholder="Enter Email Address."
                            class="form-control required inputDisabled" value="<?=  $email;?>" disabled>
                    </div>

                    <div class="col-sm-6 form-group">
                        <label>College Phone</label>
                        <input name="collegePhone" type="text" placeholder="Enter Phone number Here.."
                            class="form-control  required inputDisabled" value="<?= $phone;?>" disabled>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>College Staff</label>
                        <input name="staff" type="text" placeholder="Enter Staff No.."
                            class="form-control required inputDisabled" value="<?=  $staff_collage; ?>" disabled>
                    </div>  

                    <div class="col-sm-6 form-group">
                        <label>Website Url</label>
                        <input name="website_url" type="text" placeholder="Enter Website Url Here.."
                            class="form-control  required inputDisabled" value="<?= $grpwebsite;?>" disabled>
                    </div>
                </div>


                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control valid inputDisabled"
                        aria-invalid="false" disabled><?= $groupAddress;?></textarea>
                </div>
               <div class="row">
                    <div class="col-sm-12 form-group">
						<label>College Descriptions</label>
						<textarea style="height:160px;" name="company_descriptions" class="form-control inputDisabled" aria-invalid="false" disabled><?= $groupsArr->description;?></textarea>       
					</div>	
				</div>

                <div style="display:none;" class="alert alert-success" id="updateproInfo2" role="alert">Your
                    college account has been Updated successfully.</div>

                <button type="submit" class="btn btn-lg btn-info inputDisabled d-none">Update</button>
                <button type="button" id="removeDisable2" class="btn btn-lg btn-info">Edit</button>	

            </form>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function() {

    jQuery('#state-dropdown').on('change', function() {
    	var state_id = this.value;
    	jQuery.ajax({
    		 url : '<?= admin_url('admin-ajax.php'); ?>',
    		type: "POST",
    		data: {action: "search_cities_filter",state_id: state_id
    		},
    		cache: false,
        	success: function(result){
        	    jQuery("#city-dropdown").html(result);
        	}
        });
    });
});
</script>