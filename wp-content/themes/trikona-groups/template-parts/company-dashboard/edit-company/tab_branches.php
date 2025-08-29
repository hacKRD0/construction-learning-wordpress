<?php 
//if($_GET['detsild']=="branch" && $_GET['companyProfile']=='true'){
$obj = new Trikona();
global $wpdb;


switch_to_blog(1);
$current_user = wp_get_current_user();

$branches_tab_accessible = false;
$current_user_plan = $obj->getLoggedInUserMembership($current_user->ID);

if (!empty($current_user_plan)) {
    if ($current_user_plan->membership_id == $obj->corporate_basic_mem_id) {
        $branches_tab_accessible = false;
    } else if ($current_user_plan->membership_id == $obj->corporate_prime_mem_id) {
        $branches_tab_accessible = true;
    } else if ($current_user_plan->membership_id == $obj->corporate_elite_mem_id) {
        $branches_tab_accessible = true;
    }
}

$allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);

if (!$branches_tab_accessible && empty($allowed_roles)) { ?>
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
    $group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";
    $active_user_group_id = get_query_var( 'active_user_group_id' );
    restore_current_blog();

    if (empty($group_id) && !empty($active_user_group_id)) {
        $group_id = $active_user_group_id;
    }

    if($group_id > 0){
        if($_POST['group_id']!=""){
        	
          groups_update_groupmeta( $_POST['group_id'], 'comBranch_groups' ,$_POST['comBranch'] );
         }

        $comBranch_groups  = groups_get_groupmeta( $group_id, 'comBranch_groups' ,true );
        $projects_groups =  groups_get_groupmeta( $group_id, 'projects_groups' ,true );
        $project_upload_attachment =  groups_get_groupmeta( $group_id, 'project_upload_attachment' ,true );
    ?>
    <h5>Enter Company Branch Details</h5>
    <br/>
    <?php  if($_POST['group_id']!=""){ ?>
    <div  class="alert alert-success" id="updateproInfo2" role="alert">Data Inserted Successfully.</div>
    <?php } ?>
    <form class="form"  name="updatecollegeData_Branch" method="POST" enctype="multipart/form-data">
    <input name="group_id" type="hidden" value="<?=  $group_id; ?>">
     <table border="0" width="100%" class="table" id="branch_table">
        <tbody>
            <tr>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Phone</th>
                <th>Email</th>
                <th style="width:96px;">Action</th>
            </tr>
            <?php 
             if(!empty($comBranch_groups['address'])){
             foreach ($comBranch_groups['address'] as $key => $value) { 
               $city =$comBranch_groups['city'][ $key];
                $state =$comBranch_groups['state'][ $key];
                 $phone =$comBranch_groups['phone'][ $key];
                  $email =$comBranch_groups['email'][ $key];
             ?>
            <tr>
            <td>
    			 <input disabled type="text" name="comBranch[address][]" placeholder="Enter Address" class="form-control required inputDisabled" value="<?= $value;?>">	
            </td>
            <td>
              <input disabled type="text" name="comBranch[city][]" placeholder="Enter city " class="form-control required inputDisabled" value="<?= $city;?>">
            </td>
            <td>
              <input disabled type="text" name="comBranch[state][]" placeholder="Enter State " class="form-control required inputDisabled" value="<?= $state;?>">
            </td>
            <td>
              <input disabled type="text" name="comBranch[phone][]" placeholder="Enter Phone " class="form-control required inputDisabled" value="<?= $phone;?>">
            </td>
            <td>
              <input disabled type="text" name="comBranch[email][]" placeholder="Enter Email " class="form-control required inputDisabled" value="<?= $email;?>">
            </td>
            <td><button disabled class="remove_employee inputDisabled d-none">Remove</button></td>
            </tr>
        <?php  } } ?>
        </tbody>
    </table>
    <a href="javascript:void(0)"  class="add_employee btn inputDisabled d-none" style="float: right;">Add Branches</a>


    <button type="submit" class="btn btn-lg btn-info inputDisabled d-none">Save</button>		<button type="button" id="removeDisable2" class="btn btn-lg btn-info">Edit</button>
    </form>

    <?php }else{ 

    	echo do_shortcode('[elementor-template id="33289"]'); 

    } 

    ?>

    <script>  

    var max_rows   = 10; //Maximum allowed group of input fields 
        var wrapper    = jQuery("#branch_table"); //Group of input fields wrapper
        var add_button = jQuery(".add_employee"); //Add button class or ID
        var x =1;

        jQuery(add_button).click(function(e){ //On click add employee button
            e.preventDefault();

            if(x < max_rows){ //max group of input fields allowed
                x++; //group of input fields increment
                jQuery('#branch_table tr:last td:last-child').html("");
                jQuery(wrapper).append('<tr><td><input type="text" name="comBranch[address][]" placeholder="Enter Address Here.." class="form-control required" value="">	</td><td><input type="text" name="comBranch[city][]" placeholder="Enter city Here.." class="form-control required" value=""></td><td><input type="text" name="comBranch[state][]" placeholder="Enter State Here.." class="form-control required" value=""></td><td><input type="text" name="comBranch[phone][]" placeholder="Enter Phone Here.." class="form-control required" value=""></td><td><input type="text" name="comBranch[email][]" placeholder="Enter Email Here.." class="form-control required" value=""></td><td><button class="remove_employee">Remove</button></td></td></tr>'); 
    	}
        }); 

        jQuery(wrapper).on("click",".remove_employee", function(e){ // On click to remove button
            e.preventDefault(); jQuery(this).parent().parent().remove(); x--;
            var rowCount = jQuery("#branch_table tr").length;
            if(rowCount>2){
                jQuery('#branch_table tr:last td:last-child').html('<button class="remove_employee">Remove</button></td>');
            }
        });

    </script>
<?php } ?>