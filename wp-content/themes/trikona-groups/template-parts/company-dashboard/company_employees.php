<?php
    $obj = new Trikona();

    $group_id = get_query_var( 'active_user_group_id' );

    $members = !empty($group_id) ? $obj->getGroupMembers(['group_id' => $group_id]) : [];
    switch_to_blog(1);
    $current_user = wp_get_current_user();
    restore_current_blog();
    $checkMembership = $obj->getLoggedInUserMembership($current_user->ID);

    $allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);
?>
<div class="col-lg-12 well">
    <div class="row">
        <div class="col-sm-12">
            <h5>Employees</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Group Role</th>
                        <th>Member Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="userData">
                    <?php if($group_id > 0 && ($checkMembership->status=='active' || !empty($allowed_roles))){ ?>
                        <?php 
                            switch_to_blog(1);
                            $corporateRole = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'names' ) );
                            restore_current_blog();
                            // Get members data from database 
                            if(!empty($members)){ 
                                foreach($members as $memberInfo){ 
                                    switch_to_blog(1);
                                    $user_info  = get_userdata($memberInfo->user_id);
                                    $roles = $user_info->roles;
                                restore_current_blog();
                               if($memberInfo->user_title==""){
                    				 $groupMember = "Member";
                    			}else{
                    				$groupMember = $memberInfo->user_title;
                    			}
                    			if($groupMember=="Group Mod"){
                    				$corporateRoles =$corporateRole[0];
                    			}if($groupMember==""){
                    				$corporateRoles ='';
                    			}


                            if(!in_array("administrator", $roles)){
                            	if($user_info->ID!=get_current_user_id()){

                            ?>
                                <tr id="<?= $user_info->ID; ?>">
                                    <td><?= $user_info->ID; ?></td>
                                    <td>
                                        <span class="editSpan first_name"><?= $user_info->first_name; ?></span>
                                        <input class="form-control editInput first_name" type="text" name="first_name" value="<?= $user_info->first_name; ?>" style="display: none;">
                                        <input class="form-control" type="text" name="group_id" value="<?= $group_id; ?>" style="display: none;">
                                    </td>
                                    <td>
                                        <span class="editSpan last_name"><?= $user_info->last_name; ?></span>
                                        <input class="form-control editInput last_name" type="text" name="last_name" value="<?= $user_info->last_name; ?>" style="display: none;">
                                    </td>
                                   
                                    <td>
                                        <span class="editSpan role"><?= $roles[0]; ?></span>
                                        <input disabled class="form-control editInput role" type="text" name="role" value="<?= $roles[0]; ?>" style="display: none;">
                                    </td>
                                   

                                    <td>
                                        <span class="editSpan grouprole" ><?= $groupMember; ?></span>
                                        <select userid="<?= $user_info->ID; ?>" class="form-control editInput grouprole" name="grouprole" style="display: none;">
                                            <option value="Member">Member</option>
                                            <option value="Group Mod">Group Mod</option>
                                        </select>
                                    </td>

                                    <td id="td<?= $user_info->ID; ?>">
                                    	<?php if($groupMember=="Group Mod"){?>
                                        <span class="editSpan statusmember"><?= $corporateRoles; ?></span>
                                        <select class="form-control editInput statusmember" name="memberships" style="display: none;">
                                            <option value="<?= $corporateRole[0];?>"><?= $corporateRoles;?></option>
                                        </select>
                                       <?php } ?>
                                    </td>
                                    <td>
                                    	<button type="button" class="btn btndefault deleteBtn" style="">Remove</button>
                                    	<?php if($roles[0]=='corporate'){?>
                                        <button type="button" class="btn btnedt editBtn">Edit</i></button>
                                    <?php } ?>
                                        
                                        
                                        <button type="button" class="btn btn-success saveBtn" style="display: none;">Save</button>
                                        <button type="button" class="btn btn-danger confirmBtn" style="display: none;">Confirm</button>
                                        <button type="button" class="btn btn-secondary cancelBtn" style="display: none;">Cancel</button>
                                    </td>
                                </tr>
                            <?php 
                                } 
                            }
                            }
                            }else{ 
                                echo '<tr><th colspan="7" align="center">No employee found.</th></tr>'; 
                            } 

                        ?>
                    <?php } else { echo "<tr><th colspan='7' align='center'>No employee found.</th></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style> 
.table-striped > tbody > tr:nth-child(2n+1) {
	background-color: #fff !important;
}
</style>
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
            data: {action: "remove_emp_groups",id: ID,'groupid':'<?= $group_id; ?>'},
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

