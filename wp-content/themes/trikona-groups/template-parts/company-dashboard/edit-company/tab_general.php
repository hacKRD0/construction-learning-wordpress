<?php
$obj = new Trikona();

global $wpdb;

switch_to_blog(1);
$current_user = wp_get_current_user();

$group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";

// Check for group membership for current user with Group Admin title
$active_user_group_id = get_query_var( 'active_user_group_id' );

restore_current_blog();

if (empty($group_id) && !empty($active_user_group_id)) {
	$group_id = $active_user_group_id;
}

// Check for group data
$bp_groups_data = $obj->getBPGroups(['id' => $group_id]);

if($group_id > 0){
	$project_upload_attachment =  groups_get_groupmeta( $group_id, 'project_upload_attachment' ,true );
	if($_POST['group_id']!=""){
	$state_id= 	$_POST['state'];
		$stateid = $obj->getStates(['id' => $state_id]);

		groups_update_groupmeta( $_POST['group_id'], $obj->company_staff_meta ,$_POST['staff'] );
		groups_update_groupmeta( $_POST['group_id'], $obj->state_meta, $stateid->name );
		groups_update_groupmeta( $_POST['group_id'], $obj->gstates_meta, $state_id );

		groups_update_groupmeta( $_POST['group_id'], $obj->company_website_url_meta ,$_POST['website_url'] );
		groups_update_groupmeta( $_POST['group_id'], $obj->city_meta ,$_POST['city'] );
		groups_update_groupmeta( $_POST['group_id'], $obj->address_meta ,$_POST['address'] );
		groups_update_groupmeta( $_POST['group_id'], $obj->industries_type_meta ,$_POST['Industries_type'] );
		groups_update_groupmeta( $_POST['group_id'], $obj->sectors_meta ,$_POST['sectors'] );
		groups_update_groupmeta( $_POST['group_id'], $obj->email_address_meta ,$_POST['comapnyEmailAdress'] );
		groups_update_groupmeta( $_POST['group_id'], $obj->phone_number_meta ,$_POST['companyPhoneNumber'] );
		switch_to_blog(1);
        $table_name = $wpdb->prefix . "bp_groups";
        restore_current_blog();

		$wpdb->update( $table_name, array( 'description' => $_POST['company_descriptions']),array('ID'=>$_POST['group_id']));

		$bp_groups_data = $obj->getBPGroups(['id' => $group_id]);

  }
  $industriesType = get_post_meta($obj->industries_type_post_meta,'group_fileds_optionName',true);
  $sectorArr = get_post_meta($obj->sectors_post_meta,'group_fileds_optionName',true);
	$staff_collage  = groups_get_groupmeta( $group_id, $obj->company_staff_meta ,true );
	$State  = groups_get_groupmeta( $group_id, $obj->state_meta ,true );
	$grpwebsite  = groups_get_groupmeta( $group_id, $obj->company_website_url_meta ,true );
	$grpcity  = groups_get_groupmeta( $group_id, $obj->city_meta ,true );
	$groupAddress  = groups_get_groupmeta( $group_id, $obj->address_meta ,true );
	$Industries_type  = groups_get_groupmeta( $group_id, $obj->industries_type_meta ,true );
	$sectors  = groups_get_groupmeta( $group_id, $obj->sectors_meta ,true );

	$comapnyEmailAdress  = groups_get_groupmeta( $group_id, $obj->email_address_meta ,true );
	$companyPhoneNumber  = groups_get_groupmeta( $group_id, $obj->phone_number_meta ,true );
 	$gstates32470  = groups_get_groupmeta( $group_id, $obj->gstates_meta ,true );
?>
<?php if($_POST['group_id']!=""){ ?>
	<div class="alert alert-success">
	  <strong>Success!</strong> Record updated Successfully...
	</div>
<?php } ?>
<div class="col-lg-12 well">
<div class="row">
<div class="col-sm-12">
<h5>Enter Company Details</h5>
	<form class="form"  id="updatecollegeData" name="updatecollegeData" method="POST" enctype="multipart/form-data">

		<input name="group_id" type="hidden" value="<?= $group_id; ?>">
		<div class="row">
				<div class="col-sm-6 form-group">
					<label>Comapny Email</label>
					<input disabled type="text" placeholder="Comapny Email" class="form-control required inputDisabled" name="comapnyEmailAdress" value="<?= $comapnyEmailAdress; ?>">
				</div>   						
            
				<div class="col-sm-6 form-group">
					<label>Company Phone</label>
					<input name="companyPhoneNumber" type="text" placeholder="Comapny Phone" class="form-control required inputDisabled" value="<?= $companyPhoneNumber; ?>" disabled>
				</div>
				
            </div>

			<div class="row">
				<div class="col-sm-6 form-group">
					<label>Comapny Name</label>
					<input disabled type="text"  class="form-control" value="<?= $bp_groups_data->name; ?>">
				</div>   						
            
				<div class="col-sm-6 form-group">
					<label>Company Staff</label>
					<input name="staff" type="text" placeholder="Enter Staff No.." class="form-control required inputDisabled" value="<?= $staff_collage; ?>" disabled>
				</div>
				
            </div>
            <?php
            	$results = $obj->getStates();
            ?>
				<div class="row">
				<div class="col-sm-6 form-group">
					<label>State</label>
					<select  name="state" style="width:100%" class="form-control inputDisabled" id="state-dropdown" disabled>
                         <option value="">Select State</option>
                         <?php foreach ($results as $key => $statelist) {?>
                        <option value="<?=  $statelist->id; ?>" <?php if( $statelist->id == $gstates32470 ): ?> selected="selected"<?php endif; ?>><?=  $statelist->name; ?></option>
                         <?php }?>
                    </select>
				</div>
			
			    <div class="col-sm-6 form-group">
					<label>City </label>
					<select name="city" style="width:100%" class="form-control inputDisabled" id="city-dropdown" disabled>
						<option value="<?=  $grpcity;?>"><?=  $grpcity;?></option>

                    </select>
					
				</div>	
										    

			</div>

			<div class="row">
			  	<div class="col-sm-6 form-group">
					<label>Website Url</label>
					<input name="website_url" type="text" placeholder="Enter Website Url Here.." class="form-control  required inputDisabled" value="<?=  $grpwebsite;?>" disabled>
				</div>						    

			    <div class="col-sm-6 form-group">
					<label>Industries Type</label>
					<select name="Industries_type" class="form-control required inputDisabled" disabled>
				   <option  value="">Select Industries</option>
				   <?php foreach ($industriesType as $key => $industriesType_val) { ?>
						 <option value="<?=  $industriesType_val;?>" <?php if($Industries_type == $industriesType_val): ?> selected="selected"<?php endif; ?>><?=  $industriesType_val;?></option>
					  <?php  }  ?> 
			</select>
				</div>
			</div>

			<div class="row">
			    <div class="col-sm-6 form-group">
					<label>Sectors </label>
					<select name="sectors" class="form-control required inputDisabled" disabled>
				    <option value="">Select Sectors </option>
				  <?php foreach ($sectorArr as $key => $sectorVal) { ?>
					<option value="<?=  $sectorVal;?>" <?php if($sectors == $sectorVal): ?> selected="selected"<?php endif; ?>><?=  $sectorVal;?></option>
                   <?php  }  ?>
			</select>
				</div>
                <div class="col-sm-6 form-group">
			<label>Address</label>
			<textarea name="address" class="form-control inputDisabled" aria-invalid="false" disabled><?=  $groupAddress;?></textarea>
               
		</div>	

	    <div class="col-sm-12 form-group">
			<label>Company Descriptions</label>
			<textarea style="height:160px;" name="company_descriptions" class="form-control inputDisabled" aria-invalid="false" disabled><?=  $bp_groups_data->description;?></textarea>
               
		</div>		
        </div>
		<div style="display:none;" class="alert alert-success" id="updateproInfo2" role="alert">Your collage account has been Updated successfully.</div>	

		<button type="submit" class="btn btn-lg btn-info inputDisabled d-none" disabled>Update</button>		<button type="button" id="removeDisable2" class="btn btn-lg btn-info">Edit</button>			
		
	</form> 
	</div>
	</div>
</div>
<script>
jQuery(document).ready(function() {

jQuery('#state-dropdown').on('change', function() {
	var state_id = this.value;
	jQuery.ajax({
		 url : '<?=  admin_url('admin-ajax.php'); ?>',
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
<?php }else{ 

	echo get_template_part( 'template-parts/usersprofiles/company/user_permisson' );
 

} 

?>