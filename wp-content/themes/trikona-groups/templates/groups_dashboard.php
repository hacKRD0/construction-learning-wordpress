
<?php
/**
 * Template Name: Groups Dashboard
 * 
 *
 * @package Trikona
 * @subpackage Trikona
 * @since Trikona 1.0
 */
$obj = new Trikona();

get_header(); 
if(get_current_user_id() > 0){

global $wpdb;
$current_user = wp_get_current_user();

//$skill = bp_get_profile_field_data('field=Skills&user_id='. $current_user->ID);
$user_meta = get_userdata($current_user->ID);
$user_roles = $user_meta->roles;

$bp_groups_members = $obj->getGroupMembers(['user_id' => $current_user->ID, 'user_title' => 'Group Admin'], $is_single_record = true);
$bp_group_id = !empty($bp_groups_members) ? $obj->getBPGroups(['id' => $bp_groups_members->group_id]) : NULL;
?>


 <div class="container-fluid display-table">
<div class="row display-table-row">
	<?php  if(get_current_user_id() > 0){?>	
    <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
    <h4 class="disp-name">Hello, <?=  $current_user->user_firstname  ?>
        <?= $current_user->user_lastname ?></h4>
        <div class="logo logostyle">
            <a hef="#"><?= $user_avatar ?></a>
        </div>
        <div class="update-img">
        
    </div>

<?php 
/**
 * Left menu add file @
 *
 * @return Display left header data 
 * @author Trikona 2023
 */ ?>


 <div class="navi">
    <ul>
    <?php if($_GET['mypoints']=='' && $_GET['myaccout']=='' && $_GET['memberships']=='' && $_GET['managesEmp']=='' && $_GET['companyProfile']=='' && $_GET['managesJobs']=='' && $_GET['updateprofile']=="" && $_GET['updateavatar']=="" && $_GET['inquiry']==""){
         $activeProfile = 'active';
	}
   

global $wpdb;

	 ?>
        
        <li class="<?= $activeProfile ?>"><a href="<?= home_url() ?>/groups-dashboard?managesGroups=true&type=company"><i class="fa fa-bar-chart" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Company</span></a></li>
       

         <li  class="<?= $managesJobs ?>"><a href="<?= home_url() ?>/groups-dashboard?managesGroups=true&type=college"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">College</span></a></li>

    </ul>
</div>


</div>
<div class="col-md-10 col-sm-11 display-table-cell v-align">

<div class="user-dashboard">                   

<?php 
/**
 * Header top header
 *
 * @return Display top header data 
 * @author Trikona 2023
 */
//echo get_template_part( 'template-parts/dashboard/company/top-header/top_header' );


/**
 * Dashboard data 
 *
 * @return Display top Dashboard data 
 * @author Trikona 2023
 */
//include WP_PLUGIN_DIR . '/trikona-group-profiles/front-end/templates/temp-parts/dashboard/company/dashboard.php';

?>

       
<?php 

if( $_GET['managesGroups']=='' &&  $_GET['updateMembers']=='' && $_GET['detsild']==""){?>
	
    <button type="button" id="removeDisable2" class="btn btn-lg btn-info">Enable Edit Mode</button>		
	<div class="row edit-profile">
		<div class="col-sm-12">
				<form class="form"  id="updateUserProfile" name="updateUserProfile" method="POST">

					
						
						<div class="row">
    						<div class="col-sm-6 form-group">
    							<label>First Name</label>
    							<input name="firtName" type="text" placeholder="Enter First Name Here.." class="form-control required " value="<?= $current_user->user_firstname ?>" >
    						</div>
    						<div class="col-sm-6 form-group">
    							<label>Last Name</label>
    							<input name="lastName" type="text" placeholder="Enter Last Name Here.." class="form-control required " value="<?= $current_user->user_lastname ?>" >
    						</div>
	                    </div>
    						                    
						<div class="row">
						    <div class="col-sm-6 form-group">
								<label>Email Address</label>
								<input type="text" name="emailAdress" placeholder="Enter Email Address Here.." class="form-control required  email" value="<?= $current_user->user_email ?>" >
							</div>	
							<div class="col-sm-6 form-group">
								<label>Phone</label>
								<input name="phoneNo" type="text" placeholder="Enter Phone Name Here.." class="form-control required " value="<?php echo preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $phone);?>" >
							</div>						    

						</div>
 
						
					<div style="display:none;" class="alert alert-success" id="updateproInfo1" role="alert">Your account has been Updated successfully.</div>	
	
					<button type="submit" class="btn btn-lg btn-info " >Update Profile</button>	
					<button type="button" id="removeDisable" class="btn btn-lg btn-info">Enable Edit Mode</button>			
					
				</form> 
				</div>
				</div>
	

<?php }

if($_GET['managesGroups']=='true' && $_GET['detsild']=="logo"){ 
 echo get_template_part( 'template-parts/dashboard/edit-groups/group_company_menu');
echo get_template_part( 'template-parts/dashboard/company/edit-companes/tab_company_logo');

}

 if($_GET['managesGroups']=='true' && $_GET['detsild']=="services"){ 
 echo get_template_part( 'template-parts/dashboard/edit-groups/group_company_menu');
    echo get_template_part( 'template-parts/dashboard/company/edit-companes//tab_services');	
 } 

if($_GET['detsild']=="branch" && $_GET['managesGroups']=='true'){
	 echo get_template_part( 'template-parts/dashboard/edit-groups/group_company_menu');
echo get_template_part( 'template-parts/dashboard/company/edit-companes/tab_branches');	

}
if($_GET['detsild']=="projects" && $_GET['managesGroups']=='true'){
	 echo get_template_part( 'template-parts/dashboard/edit-groups/group_company_menu');
echo get_template_part( 'template-parts/dashboard/company/edit-companes/tab_projects');	
}
if($_GET['detsild']=="banner" && $_GET['managesGroups']=='true'){
	 echo get_template_part( 'template-parts/dashboard/edit-groups/group_company_menu');
  echo get_template_part( 'template-parts/dashboard/company/edit-companes/tab_company_banner');	

}
if($_GET['detsild']=="document" && $_GET['managesGroups']=='true'){
 echo get_template_part( 'template-parts/dashboard/edit-groups/group_company_menu');
echo get_template_part( 'template-parts/dashboard/company/edit-companes/tab_company_documents');	

}
 ?>


<?php if($_GET['managesGroups']=='true' && $_GET['group_id']=="" && $_GET['detsild']==""){
 
  global $wpdb;
  $results = $obj->getGroupMembers(['user_id' => $current_user->ID, 'user_title' => 'Group Admin'], $is_single_record = true);
  $members = $obj->getBPGroups();
$args = array('order'=> 'ASC','orderby'=> 'name');
 $member_type_company = bp_groups_get_group_types();

	?>

    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-md-3">
                        <h2>Manage <b>Groups</b></h2>
                    </div>
                    <div class="col-md-3">

        <div class="form-group" style="display:none;"> 
        	<select id="filterBycompany" name="filterBycompany"   class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
               <option value="">All</option>
				  <?php  foreach($member_type_company as $member_type=>$grouptype){?>
				  <option value="<?= $grouptype ?>"><?= $grouptype ?></option>
				  
				  <?php } ?>
            </select> 

        </div> <!-- /.form-group -->
       </div> 
        <div class="col-md-3">
         <input type="text" name="searchUsers" id="searchUsers" placeholder="Search" value="" style="color: #666;height: 40px;padding-left: 8px;"></div>
                    <div class="col-md-2">
                      <input id="searchBtn" type="button" name="searchUsers"  value="Search">						
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Group Name</th>	
                        <th>Description</th>	
                        <th>Group Type</th>	
                        <th>Registered Date</th>
                        <th>Total Members</th>
                        <th>&nbsp</th>

                    </tr>
                </thead>
                <tbody id="member_data">
                	<?php 
                		$userObj =  get_userdata(get_current_user_id());
                		$roles = ( array ) $userObj->roles;
                		if (is_super_admin($current_user->ID)) {
                			$roles[0] = 'administrator';
                		}
                	global $wpdb,$bp;
                	 $paged = $_GET['pg'];
                	 if($paged==""){
                	 	$paged = 1;
                	 }else{
                	 	$paged = $paged;
                	 }
                	 $number = 10;  

                	 if($_GET['filterBycompany']==""){
                	$args = array(
                		'order'=> 'ASC',
                		'orderby'=> 'name',
                		'per_page' => 10,
                		'page'=> $paged,
                	); 
                	}  

                	if( $roles[0]!="administrator"){
                	$args = array(
                		'order'=> 'ASC',
                		'orderby'=> 'name',
                		'per_page' => 10,
                		'page'=> $paged,
                		'group_type'=>$_GET['type'],
                		'meta_query' => array(
					        'relation' => 'AND',
					        array(
					            'key'   => 'updateGroup_manager',
					            'value' => $current_user->ID,
					        ),
					       
					    ),
                	); 
                }
                if( $roles[0]!="administrator"){
                	$args = array(
                		'order'=> 'ASC',
                		'orderby'=> 'name',
                		'per_page' => 10,
                		'page'=> $paged,
                		 'user_id'            => $current_user->ID, 
                		 'group_type'=>$_GET['type'],
                		/*'meta_query' => array(
					        'relation' => 'AND',
					        array(
					            'key'   => 'updateGroup_manager',
					            'value' => $current_user->ID,
					        ),
					       
					    ),*/
                	); 
                }

                else{
                	$args = array(
                		'order'=> 'ASC',
                		'orderby'=> 'name',
                		'per_page' => 10,
                		'page'=> $paged,
                		'group_type'=>$_GET['type'],
                	
                	
                	); 
                }
                

                    $groupsList =  groups_get_groups($args);
                    $total_users = $groupsList['total'];
                   

                   
                     $totalNum = 0;
                     foreach ($groupsList['groups'] as $key => $groups) {
                     	
                       $type=  bp_groups_get_group_type($groups->id);
                       $groupAdmin=  groups_get_group_admins($groups->id );
                       $Verified=   groups_get_groupmeta( $groups->id,'verified',true);

                     	?>
                    
                    <tr>
                        <td><?= $groups->name ?></td>
                        <td><?= wp_trim_words( $groups->description, 10, '…') ?></td>
                        <td> <?= $type ?></td>
                        <td><?= $groups->date_created ?></td> 
                        <td><?= bp_get_group_member_count($groups->id) ?></td>
                         <td>
                         	<a class="btn" href="<?= home_url() ?>/groups-dashboard/?managesGroups=true&group_id=<?= $groups->id ?>&group_edit_tab=group&type=<?= $type ?>">Edit</a></td>
                        
                    </tr>
                         <?php $totalNum++ ; } ?>
                         
                    </tbody>
            </table>

            <div class="clearfix" >
                <div id="hint" class="hint-text">Showing <?= $totalNum ?> out of <b><?= $total_users ?></b> entries</div>
               <div id="pagination" class="pagination" style="float: right;">

                	   <?php 
//if($total_users > $number){

  $pl_args = array(
     'base'     => add_query_arg('paged','%#%'),
     'format'   => '',
     'total'    => ceil($total_users / $number),
     'current'  => max(1, $paged),

  );

  // for ".../page/n"
  if($GLOBALS['wp_rewrite']->using_permalinks())
    $pl_args['base'] = home_url().'/groups-dashboard/?managesGroups=true&pg=%#%';

  echo paginate_links($pl_args);
//} ?>
      </div>             
            </div>
        </div>
    </div> 
 <?php } 


  if($_GET['updateMembers']=='true' && $_GET['memberid']!="" && $_GET['detsild']==""){ 
switch_to_blog(1);
                		$userObj =  get_userdata($_GET['memberid']);
                		 $roles = ( array ) $userObj->roles;
                		$roles =  implode(', ', $userObj->roles);
                		     restore_current_blog();

  	?>

<div class="row edit-profile">
		<div class="col-sm-12">
				<form class="form" id="updateUserProfile" name="updateUserProfile" method="POST">

					
						
						<div class="row">
    						<div class="col-sm-6 form-group">
    							<label>First Name</label>
    							<input name="group_name" type="text" class="form-control required " value="<?= $userObj->first_name ?>">
    						</div>
    						<div class="col-sm-6 form-group">
    							<label>Last Name</label>
    							<input name="lastName" type="text" class="form-control required " value="<?= $userObj->last_name ?>">
    						</div>
	                    </div>
    						                    
						<div class="row">
						    <div class="col-sm-6 form-group">
								<label>Email Address</label>
								<input type="text" name="emailAdress"  class="form-control required"  value="<?= $userObj->user_email ?>">
							</div>	
												    
                              <div class="col-sm-6 form-group">
								<label>Roles</label>
								<select style="width:100%;" class="form-control" id="state-dropdown" name="companyState">
								<option value="">Change role to…</option>
								<option value="">None </option>	
								<option value="corporate" <?php if($roles=='corporate'){ echo 'selected="selected"'; } ?>>Corporate </option>
								<option value="professional" <?php if($roles=='professional'){ echo 'selected="selected"'; } ?>>professional</option>
								<option value="instructor" <?php if($roles=='instructor'){ echo 'selected="selected"'; } ?>>Instructor</option>
								<option value="student" <?php if($roles=='student'){ echo 'selected="selected"'; } ?>>Student</option>		
									</select>
                          
							</div>	
						</div>
                           
						
						<br/>
                      
               

					<div style="display:none;" class="alert alert-success" id="updateproInfo1" role="alert">Your account has been Updated successfully.</div>	
	
					<button type="submit" class="btn btn-lg btn-info">Update </button>	
					
				</form> 
				</div>
				</div>
					</div>

  <?php 
  }
} ?> 

<?php if($_GET['managesGroups']=='true' && $_GET['group_id']!="" && $_GET['detsild']==""){
      global $wpdb; 
$industriesType = get_post_meta(33926,'group_fileds_optionName',true);
$sectorArr = get_post_meta(33927,'group_fileds_optionName',true);
$services = get_post_meta(32461,'group_fileds_optionName',true);

$member_type_company = bp_groups_get_group_types();
switch_to_blog(1);
$bp_group_Obj  = $obj->getGroupMembers(['group_id' => $_GET['group_id']]);

global $wpdb;

 $current_user = wp_get_current_user();
$bp_groups_members = $obj->getGroupMembers(['user_id' => $current_user->ID, 'user_title' => 'Group Admin'], $is_single_record = true);
$bp_groups_data = $obj->getBPGroups(['id' => $_GET['group_id']]);
restore_current_blog();

	if($_POST['group_id']!=""){
	$state_id= 	$_POST['state'];
		$stateid = $obj->getStates(['id' => $state_id]);
		groups_update_groupmeta( $_POST['group_id'], 'company-staff_32473' ,$_POST['staff'] );
		groups_update_groupmeta( $_POST['group_id'], 'state_32470', $stateid->name );
		groups_update_groupmeta( $_POST['group_id'], 'gstates32470', $state_id );

		groups_update_groupmeta( $_POST['group_id'], 'company-website-url_32474' ,$_POST['website_url'] );
		groups_update_groupmeta( $_POST['group_id'], 'city_32469' ,$_POST['city'] );
		groups_update_groupmeta( $_POST['group_id'], 'address_32468' ,$_POST['address'] );
		groups_update_groupmeta( $_POST['group_id'], 'industries-type_33926' ,$_POST['Industries_type'] );
		groups_update_groupmeta( $_POST['group_id'], 'sectors_33927' ,$_POST['sectors'] );
		groups_update_groupmeta( $_POST['group_id'], 'email-address_32472' ,$_POST['comapnyEmailAdress'] );
		groups_update_groupmeta( $_POST['group_id'], 'phone-number_32471' ,$_POST['companyPhoneNumber'] );
		  //switch_to_blog(1);
           $table_name = $wpdb->prefix."bp_groups";
           $wpdb->update( $table_name, array( 'description' => $_POST['company_descriptions']),array('id'=>$_POST['group_id']));
        
  }
  $industriesType = get_post_meta(33926,'group_fileds_optionName',true);
  $sectorArr = get_post_meta(33927,'group_fileds_optionName',true);
$staff_collage  = groups_get_groupmeta( $bp_groups_data->id, 'company-staff_32473' ,true );
 $State  = groups_get_groupmeta($bp_groups_data->id, 'state_32470' ,true );
$grpwebsite  = groups_get_groupmeta( $bp_groups_data->id, 'company-website-url_32474' ,true );
$grpcity  = groups_get_groupmeta( $bp_groups_data->id, 'city_32469' ,true );
$groupAddress  = groups_get_groupmeta( $bp_groups_data->id, 'address_32468' ,true );
$Industries_type  = groups_get_groupmeta($bp_groups_data->id, 'industries-type_33926' ,true );
$sectors  = groups_get_groupmeta( $bp_groups_data->id, 'sectors_33927' ,true );
//

$comapnyEmailAdress  = groups_get_groupmeta( $bp_groups_data->id, 'email-address_32472' ,true );
$companyPhoneNumber  = groups_get_groupmeta( $bp_groups_data->id, 'phone-number_32471' ,true );
  $gstates32470  =groups_get_groupmeta($bp_groups_data->id, 'state_32470' ,true );

if($_GET['group_edit_tab']=="group"){
  $activeMenu1 = "btn-primary";
}else{
	$activeMenu1 = "btn-default";
}

if($_GET['member_edit_tab']=="members"){
$activeMenu2 = "btn-primary";	
}else{
	$activeMenu2 = "btn-default";
}
?>

 <div class="row">
              
                	 <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <a type="button" id="stars" class="btn <?= $activeMenu ?>" href="<?= home_url() ?>//groups-dashboard/?managesGroups=true&group_id=<?= $_GET['group_id'] ?>&group_edit_tab=group"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                <div class="hidden-xs">Edit Group Data</div>
            </a>
        </div>

        <div class="btn-group" role="group">
            <a type="button" id="favorites" class="btn <?= $activeMenu1 ?>" href="<?= home_url() ?>//groups-dashboard/?managesGroups=true&group_id=<?= $_GET['group_id'] ?>&member_edit_tab=members" ><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span><div class="hidden-xs">Edit Group Members</div>
            </a>
        </div>

    <?php if($_GET['type']=='company' && $_GET['detsild']==""){
    echo get_template_part( 'template-parts/dashboard/edit-groups/group_company_menu');
    } ?>
    </div>
    <?php  if($_GET['group_edit_tab']=='group'){ ?>             
                  <div class="tabcontent py-3 px-3 px-sm-0">
                    <div class="main">
                    <form class="form"  id="updatecollegeData" name="updatecollegeData" method="POST" enctype="multipart/form-data">

		<input name="group_id" type="hidden" value="<?= $_GET['group_id'] ?>">
		<div class="row">
				<div class="col-sm-6 form-group">
					<label> Email</label>
					<input  type="text" placeholder=" Email" class="form-control required " name="comapnyEmailAdress" value="<?= $comapnyEmailAdress ?>">
				</div>   						
            
				<div class="col-sm-6 form-group">
					<label> Phone</label>
					<input name="companyPhoneNumber" type="text" placeholder=" Phone" class="form-control required " value="<?= $companyPhoneNumber ?>" >
				</div>
				
            </div>

			<div class="row">
				<div class="col-sm-6 form-group">
					<label> Name</label>
					<input  type="text"  class="form-control" value="<?= $bp_groups_data->name ?>">
				</div>   						
            
				<div class="col-sm-6 form-group">
					<label> Staff</label>
					<input name="staff" type="text" placeholder="Enter Staff No.." class="form-control required " value="<?= $staff_collage ?>" >
				</div>
				
            </div>
            <?php $results = $obj->getStates(); ?>
				<div class="row">
				<div class="col-sm-6 form-group">
					<label>State</label>
					<select  name="state" style="width:100%" class="form-control " id="state-dropdown" >
                         <option value="">Select State</option>
                         <?php foreach ($results as $key => $statelist) {?>
                        <option value="<?= $statelist->id ?>" <?php if( $statelist->id == $gstates32470 ): ?> selected="selected"<?php endif; ?>><?= $statelist->name ?></option>
                         <?php }?>
                    </select>
				</div>
			
			    <div class="col-sm-6 form-group">
					<label>City </label>
					<select name="city" style="width:100%" class="form-control " id="city-dropdown" >
						<option value="<?= $grpcity ?>"><?= $grpcity ?></option>

                    </select>
					
				</div>	
										    

			</div>

			<div class="row">
			  	<div class="col-sm-6 form-group">
					<label>Website Url</label>
					<input name="website_url" type="text" placeholder="Enter Website Url Here.." class="form-control  required " value="<?php echo $grpwebsite;?>" >
				</div>						    

			    <div class="col-sm-6 form-group">
					<label>Industries Type</label>
					<select name="Industries_type" class="form-control required " >
				   <option  value="">Select Industries</option>
				   <?php foreach ($industriesType as $key => $industriesType_val) { ?>
						 <option value="<?= $industriesType_val ?>" <?php if($Industries_type == $industriesType_val): ?> selected="selected"<?php endif; ?>><?= $industriesType_val ?></option>
					  <?php  }  ?> 
			</select>
				</div>
			</div>

			<div class="row">
			    <div class="col-sm-6 form-group">
					<label>Sectors </label>
					<select name="sectors" class="form-control required " >
				    <option value="">Select Sectors </option>
				  <?php foreach ($sectorArr as $key => $sectorVal) { ?>
					<option value="<?= $sectorVal ?>" <?php if($sectors == $sectorVal): ?> selected="selected"<?php endif; ?>><?= $sectorVal ?></option>
                   <?php  }  ?>
			</select>
				</div>
                <div class="col-sm-6 form-group">
			<label>Address</label>
			<textarea name="address" class="form-control " aria-invalid="false" ><?= $groupAddress ?></textarea>
               
		</div>	

	    <div class="col-sm-12 form-group">
			<label>Descriptions</label>
			<textarea style="height:160px;" name="company_descriptions" class="form-control " aria-invalid="false" ><?= $bp_groups_data->description ?></textarea>
               
		</div>		
        </div>
		<div style="display:none;" class="alert alert-success" id="updateproInfo2" role="alert">Your collage account has been Updated successfully.</div>	

		<button type="submit" class="btn btn-lg btn-info " >Update</button>				
		
	</form>
 </div>
</div>

<?php 
 }

if($_GET['member_edit_tab']=='members' && $_GET['detsild']==""){ ?>
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
            <tr id="<?php echo $user_info->ID; ?>">
                <td><?php echo $user_info->ID; ?></td>
                <td>
                    <span class="editSpan first_name"><?= $user_info->first_name ?></span>
                    <input class="form-control editInput first_name" type="text" name="first_name" value="<?= $user_info->first_name ?>" style="display: none;">
                    <input class="form-control" type="text" name="group_id" value="<?= $results->group_id ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan last_name"><?php echo $user_info->last_name; ?></span>
                    <input class="form-control editInput last_name" type="text" name="last_name" value="<?= $user_info->last_name ?>" style="display: none;">
                </td>
               
                <td>
                    <span class="editSpan role"><?php  echo implode(" ",$roles);?></span>
                    <input disabled class="form-control editInput role" type="text" name="role" value="<?= implode(" ",$roles) ?>" style="display: none;">
                </td>
               

                <td>
                    <span class="editSpan grouprole" ><?= $bp_group_users->user_title ?></span>
                    <select userid="<?= $user_info->ID ?>" class="form-control editInput grouprole" name="grouprole" style="display: none;">
                    	
                    	  <option value="administrator">administrator</option>
                        <option value="member">Member</option>
                        <option value="Group Mod">Group Mod</option>
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
        <?php 
           
       
        }
        

    ?>
    </tbody>
</table>	

</div>
<?php } ?>
</div>

</div>
</div>

<?php } ?>


 <?php }else{ ?>
   <div class="alert alert-danger" role="alert">
  You are not able to access this page...
</div>

       <?php } ?> 
        </div>
</div>
 </div>
</div>
</div>

	<div id="groups_update_grouproles" class="modal fade">

  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Update Member Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form>
        <div class="modal-body">
          <div class="form-group">
            <label for="membersEmail">Name</label>
            <input type="text" class="form-control" id="membersEmail" name="membersEmail" value="">
          </div>
          <div class="form-group">
            <label for="password1">Role</label>
           <select name="grpState" class="form-control" id="membersrole">
			<option value="member">Group Member</option>
			<option value="mod">Moderator</option>
		</select>
          </div>
         
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php  get_footer(); ?>
<script type="text/javascript">
var ajaxurl = "<?= admin_url('admin-ajax.php') ?>";
</script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="<?= get_stylesheet_directory_uri() ?>/assets/js/company_profile.js"></script>

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
		 url : '<?= admin_url( 'admin-ajax.php' ) ?>',
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

 <script>
jQuery(document).ready(function(){

jQuery('select').on('change', function() {
  var userid= jQuery(this).attr('userid');
  if(this.value=='Group Mod'){
  jQuery('#td'+userid).html('<span class="editSpan statusmember"></span><select class="form-control editInput statusmember" name="status"><option value="<?= $corporateRole[0] ?>"><?= $corporateRole[0] ?></option></select>');
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
            url : '<?= admin_url('admin-ajax.php') ?>',
            dataType: "json",
            data: {action: "company_emp_list_data",id: ID,'rowData':inputData,'groupid':'<?= $results->group_id ?>','author':'<?= $corporateRole[0] ?>'},
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
            url : '<?= admin_url('admin-ajax.php') ?>',
            dataType: "json",
            data: {action: "remove_emp_groups",id: ID,'groupid':'<?= $results->group_id ?>'},
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

