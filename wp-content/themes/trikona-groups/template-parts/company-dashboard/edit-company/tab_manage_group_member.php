<?php
$obj = new Trikona();

global $wpdb;

switch_to_blog(1);
$current_user = wp_get_current_user();

$manage_group_member_tab_accessible = false;
$current_user_plan = $obj->getLoggedInUserMembership($current_user->ID);
$active_user_group_id = get_query_var( 'active_user_group_id' );
restore_current_blog();

$group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";

if (!empty($current_user_plan)) {
    if ($current_user_plan->membership_id == $obj->corporate_basic_mem_id) {
        $manage_group_member_tab_accessible = false;
    } else if ($current_user_plan->membership_id == $obj->corporate_prime_mem_id) {
        $manage_group_member_tab_accessible = false;
    } else if ($current_user_plan->membership_id == $obj->corporate_elite_mem_id) {
        $manage_group_member_tab_accessible = true;

        if (!empty($active_user_group_id)) {
	        $group_id = $active_user_group_id;
        }
    }
}

if (!$manage_group_member_tab_accessible) { ?>
    <div class="card user-card-full mt-4 text-center">
        <div class="row m-l-0 m-r-0">
            <div class="col-sm-12">
                <div class="card-block">
                    You are not authorized to access this page.
                </div>
            </div>
        </div>
    </div>
<?php } else {
	switch_to_blog(1);
	$bp_group_Obj  = $obj->getGroupMembers(['group_id' => $group_id]);
?>
	<div class="" >
		<table class="table table-bordered table-striped">
			<thead>
			    <tr>
			        <th>Member ID</th>
			        <th>First Name</th>
			        <th>Last Name</th>
			        <th>Role</th>
			        <th>Group Role</th>
			        <th>Action</th>
			    </tr>
			</thead>
			<tbody id="userData">
				    <?php 
				    	foreach ($bp_group_Obj  as $key => $bp_group_users) {
							switch_to_blog(1);
					  		$user_info =  get_userdata($bp_group_users->user_id);
					  		$roles = ( array ) $user_info->roles;
				     		restore_current_blog();
				        ?>
				        <tr id="<?= $user_info->ID; ?>">
				            <td><?= $user_info->ID; ?></td>
				            <td>
				                <span class="editSpan first_name"><?= $user_info->first_name; ?></span>
				                <input class="form-control editInput first_name" type="text" name="first_name" value="<?= $user_info->first_name; ?>" style="display: none;">
				                <input class="form-control" type="text" name="group_id" value="<?= $results->group_id; ?>" style="display: none;">
				            </td>
				            <td>
				                <span class="editSpan last_name"><?= $user_info->last_name; ?></span>
				                <input class="form-control editInput last_name" type="text" name="last_name" value="<?= $user_info->last_name; ?>" style="display: none;">
				            </td>
				           
				            <td>
				                <span class="editSpan role"><?php  echo implode(" ",$roles);?></span>
				                <input disabled class="form-control editInput role" type="text" name="role" value="<?php   echo implode(" ",$roles); ?>" style="display: none;">
				            </td>
				           

				            <td>
				                <span class="editSpan grouprole" ><?= $bp_group_users->user_title; ?></span>
				                <select userid="<?= $user_info->ID; ?>" class="form-control editInput grouprole" name="grouprole" style="display: none;">
				                	  	<option value="administrator" <?php if(!empty($bp_group_users->user_title) && $bp_group_users->user_title == 'Group Admin'){ echo "selected=selected"; } ?>>administrator</option>
				                    	<option value="Member" <?php if(!empty($bp_group_users->user_title) && $bp_group_users->user_title == 'Member'){ echo "selected=selected"; } ?>>Member</option>
				                    	<option value="Group Mod" <?php if(!empty($bp_group_users->user_title) && $bp_group_users->user_title == 'Group Mod'){ echo "selected=selected"; } ?>>Group Mod</option>
				                </select>
				            </td>

				            
				            <td>
				            	<button type="button" class="btn btndefault deleteBtn" style="">Remove</button>
				            	
				                <button type="button" class="btn btnedt editBtn">Edit</i></button>
				          
				                
				                
				                <button type="button" class="btn btn-success saveBtn" style="display: none;">Save</button>
				                <button type="button" class="btn btn-danger confirmBtn" style="display: none;">Confirm</button>
				                <button type="button" class="btn btn-secondary cancelBtn" style="display: none;">Cancel</button>
				            </td>
				        </tr>
				    <?php } ?>
			</tbody>
		</table>
	</div>
<?php } restore_current_blog(); ?>
<script type="text/javascript"> var ajaxurl = "<?= admin_url('admin-ajax.php'); ?>"; </script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="<?= get_stylesheet_directory_uri()?>/assets/js/company_profile.js"></script>

<script>
	jQuery(document).ready(function() {
	 	jQuery('.updateMembersInfo').on('click', function() {
		 	var title = jQuery(this).attr('title'); 
		   	var userrole = jQuery(this).attr('userrole'); 
		  	jQuery("#membersEmail").val(title); 
		    jQuery('#groups_update_grouproles').modal('show');
	  	});

		jQuery('#state-dropdown').on('change', function() {
			var state_id = this.value;
			jQuery.ajax({
				url : '<?= admin_url( 'admin-ajax.php' );?>',
				type: "POST",
				data: {action: "search_cities_filter",state_id: state_id},
				cache: false,
				success: function(result){
				    jQuery("#city-dropdown").html(result);
				}
			});
		});
	});
</script>

 <script>
	jQuery(document).ready(function(){

		jQuery('select').on('change', function() {
		  	var userid= jQuery(this).attr('userid');
		  	if(this.value=='Group Mod'){
			 	jQuery('#td'+userid).html('<span class="editSpan statusmember"></span><select class="form-control editInput statusmember" name="status"><option value="<?= $corporateRole[0];?>"><?= $corporateRole[0];?></option></select>');
			}else{
				jQuery('#td'+userid).html('');
			}
		});

	    jQuery('.editBtn').on('click',function(){
	        //hide edit span
	        jQuery(this).closest("tr").find(".editSpan").hide();

	        //show edit input
	        jQuery(this).closest("tr").find(".editInput").show();

	        //hide edit button
	       jQuery(this).closest("tr").find(".editBtn").hide();

	        //hide delete button
	        jQuery(this).closest("tr").find(".deleteBtn").hide();
	        
	        //show save button
	        jQuery(this).closest("tr").find(".saveBtn").show();

	        //show cancel button
	        jQuery(this).closest("tr").find(".cancelBtn").show();
	        
	    });
	    
	    jQuery('.saveBtn').on('click',function(){
	        jQuery('#userData').css('opacity', '.5');

	        var trObj = jQuery(this).closest("tr");
	        var ID = jQuery(this).closest("tr").attr('id');
	        var inputData = jQuery(this).closest("tr").find(".editInput").serialize();
	        jQuery.ajax({
	            type:'POST',
	            url : '<?= admin_url('admin-ajax.php'); ?>',
	            dataType: "json",
	            data: {action: "company_emp_list_data",id: ID,'rowData':inputData,'groupid':'<?= $group_id; ?>','author':'<?= $corporateRole[0];?>'},
	            success:function(response){
	                    trObj.find(".editSpan.first_name").text(response.first_name);
	                    trObj.find(".editSpan.last_name").text(response.last_name);
	                    trObj.find(".editSpan.role").text(response.role);
	                    trObj.find(".editSpan.grouprole").text(response.grouprole);
	                    trObj.find(".editSpan.statusmember").text(response.memberships);
	                    
	                    trObj.find(".editInput.first_name").val(response.first_name);
	                    trObj.find(".editInput.last_name").val(response.last_name);
	                    trObj.find(".editInput.role").val(response.role);
	                    trObj.find(".editInput.grouprole").val(response.grouprole);
	                    trObj.find(".editInput.statusmember").val(response.memberships);
	                    
	                    trObj.find(".editInput").hide();
	                    trObj.find(".editSpan").show();
	                    trObj.find(".saveBtn").hide();
	                    trObj.find(".cancelBtn").hide();
	                    trObj.find(".editBtn").show();
	                    trObj.find(".deleteBtn").show();
	                
	                jQuery('#userData').css('opacity', '');
	            }
	        });
	    });

	    jQuery('.cancelBtn').on('click',function(){
	        //hide & show buttons
	        jQuery(this).closest("tr").find(".saveBtn").hide();
	        jQuery(this).closest("tr").find(".cancelBtn").hide();
	        jQuery(this).closest("tr").find(".confirmBtn").hide();
	        jQuery(this).closest("tr").find(".editBtn").show();
	        jQuery(this).closest("tr").find(".deleteBtn").show();

	        //hide input and show values
	        jQuery(this).closest("tr").find(".editInput").hide();
	        jQuery(this).closest("tr").find(".editSpan").show();
	    });
	    
	    jQuery('.deleteBtn').on('click',function(){
	        //hide edit & delete button
	        jQuery(this).closest("tr").find(".editBtn").hide();
	        jQuery(this).closest("tr").find(".deleteBtn").hide();
	        
	        //show confirm & cancel button
	        jQuery(this).closest("tr").find(".confirmBtn").show();
	        jQuery(this).closest("tr").find(".cancelBtn").show();
	    });
	    
	    jQuery('.confirmBtn').on('click',function(){
	        jQuery('#userData').css('opacity', '.5');

	        var trObj = jQuery(this).closest("tr");
	        var ID = $(this).closest("tr").attr('id');
	        alert(ID);
	        jQuery.ajax({
	            type:'POST',
	            url : '<?= admin_url('admin-ajax.php'); ?>',
	            dataType: "json",
	            data: {action: "remove_emp_groups",id: ID,'groupid':'<?= $results->group_id; ?>'},
	            success:function(response){
	                if(response.status == 1){
	                    trObj.remove();
	                }else{
	                    trObj.find(".confirmBtn").hide();
	                    trObj.find(".cancelBtn").hide();
	                    trObj.find(".editBtn").show();
	                    trObj.find(".deleteBtn").show();
	                    alert(response.msg);
	                }
	                jQuery('#userData').css('opacity', '');
	            }
	        });
	    });
	});
</script>