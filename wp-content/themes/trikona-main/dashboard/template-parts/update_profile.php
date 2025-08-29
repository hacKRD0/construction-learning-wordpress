<?php if(!isset($_GET) || empty($_GET) || (isset($_GET['id']) && sizeof($_GET) == 1)){
    global $wpdb,$bp;
    $results = $wpdb->get_results("SELECT field.id as id, field.name as name FROM {$bp->profile->table_name_fields} as field INNER JOIN {$bp->profile->table_name_meta} as meta ON field.id = meta.object_id
    WHERE meta.object_type = 'field' AND meta.meta_key = 'do_autolink' AND meta.meta_value = 'on'");

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $current_user = get_user_by( 'ID', base64_decode($_GET['id']) );
        if (empty($current_user))
            $current_user = wp_get_current_user();
    } else {
        $current_user = wp_get_current_user();
    }    
    
    $results_edu = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$results[1]->id."'");
    $results_yearlist = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$results[2]->id."'");
    $professionalSkills = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$results[9]->id."'");
    $studentSkills = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$results[7]->id."'");
    ?>
<!-- <button type="button" id="removeDisable" class="btn btn-lg btn-info">Enable Edit Mode</button>	 -->
<div class="row edit-profile">
    <div class="col-md-12">
        <h5 class="cs-label-1">Profile</h5>
        <form class="form"  id="updateUserProfile" name="updateUserProfile" method="POST">
            <input type="hidden" name="current_user_id" value="<?= $current_user->ID ?>">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>First Name</label>
                    <input name="firtName" type="text" placeholder="Enter First Name Here.." class="form-control required inputDisabled" value="<?php echo  $current_user->user_firstname; ?>" disabled>
                </div>
                <div class="col-md-6 form-group">
                    <label>Last Name</label>
                    <input name="lastName" type="text" placeholder="Enter Last Name Here.." class="form-control required inputDisabled" value="<?php echo  $current_user->user_lastname;?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Email</label>
                    <input type="text" name="emailAdress" placeholder="Enter Email Address Here.." class="form-control required email  " value="<?php echo  $current_user->user_email;?>" disabled>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Phone</label>
                    <input name="phoneNo" type="text" placeholder="Enter Phone Name Here.." class="form-control required  inputDisabled" value="<?php echo preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $phone);?>" disabled>
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
                    <input name="memberDob" type="text" placeholder="Enter DOB Name Here.." class="form-control StartDate required  inputDisabled" value="<?php echo $memberDob;?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control valid inputDisabled" aria-invalid="false" disabled><?php echo $address;?></textarea>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Linkedin Profile</label>
                    <input class="form-control required inputDisabled" type="text" name="linkedinProfile"  value="<?php echo $linkedinProfile;?>" disabled>
                </div>
            </div>
            <hr class="hr hr-blurry" />
            <div class="row">
                <div class="form-group col-sm-12">
                    <label> About</label>
                    <textarea name="bio" class="form-control valid inputDisabled" aria-invalid="false" disabled><?php echo $member_bio;?></textarea>
                </div>
            </div>
            <hr class="hr hr-blurry" />
            <br/>    
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Highest Education</label>
                    <select class="form-control required inputDisabled" name="Highest Education" id="Highest Education" disabled>
                        <option value="">Select Highest Education Here..</option>
                        <?php foreach ($results_edu as $key => $eduFields) {
                            ?>
                        <option value="<?php echo $eduFields->name;?>" <?php if($hedu == $eduFields->name): ?> selected="selected"<?php endif; ?>><?php echo $eduFields->name;?></option>
                        <?php } ?>          
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Institute</label>
                    <input type="text" placeholder="Enter Institute Name Here.." class="form-control inputDisabled required"  name="Institute"  value="<?php echo $Institute;?>" disabled>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Total year of study</label>
                    <select class="form-control required  inputDisabled" name="Total year of study" id="Total year of study" disabled>
                        <option value="">Select Total year of study Here..</option>
                        <option value="4" <?php if($toatlStudy == '4'): ?> selected="selected"<?php endif; ?>>4</option>
                        <option value="3" <?php if($toatlStudy == '3'): ?> selected="selected"<?php endif; ?>>3</option>
                        <option value="2" <?php if($toatlStudy == '2'): ?> selected="selected"<?php endif; ?>>2</option>
                        <option value="1" <?php if($toatlStudy == '1'): ?> selected="selected"<?php endif; ?>>1</option>
                    </select>
                </div>
                <?php if (in_array('student', $userRolesChk) || in_array('administrator', $userRolesChk)){ ?>
                <div class="col-sm-6 form-group">
                    <label>Current Year Of Study </label>
                    <select class="form-control   inputDisabled" name="Current Year Of Study" id="Current Year Of Study" disabled>
                        <option value="">Select Current Year Of Study Here..</option>
                        <option value="4" <?php if($crstd == '4'): ?> selected="selected"<?php endif; ?>>4</option>
                        <option value="3" <?php if($crstd == '3'): ?> selected="selected"<?php endif; ?>>3</option>
                        <option value="2" <?php if($crstd == '2'): ?> selected="selected"<?php endif; ?>>2</option>
                        <option value="1" <?php if($crstd == '1'): ?> selected="selected"<?php endif; ?>>1</option>
                    </select>
                </div>
                <?php } ?>						    
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Graduation CGPA10</label>
                    <select class="form-control required inputDisabled" name="Graduation" id="Graduation" disabled>
                        <option value="">Select Graduation CGPA10..</option>
                        <option value="0-5" <?php if($member_graduation == '0-5'): ?> selected="selected"<?php endif; ?>>0-5</option>
                        <option value="5-7" <?php if($member_graduation == '5-7'): ?> selected="selected"<?php endif; ?>>5-7</option>
                        <option value="7-9" <?php if($member_graduation == '7-9'): ?> selected="selected"<?php endif; ?>>7-9</option>
                        <option value="9-10" <?php if($member_graduation == '9-10'): ?> selected="selected"<?php endif; ?>>9-10</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Year Of Passout</label>
                    <select class="form-control required inputDisabled" name="Year Of Passout" id="Year Of Passout" disabled>
                        <option value="">Select Year Of Passout Here..</option>
                        <?php foreach ($results_yearlist as $key => $value) {?>             
                        <option value="<?php echo $value->name;?>" <?php if($yearpass ==  $value->name): ?> selected="selected"<?php endif; ?>><?php echo $value->name;?></option>
                        <?php } ?>	                       
                    </select>
                </div>
            </div>
            <?php if (in_array('professional', $userRolesChk) || in_array('administrator', $userRolesChk)){ ?>		
            <hr class="hr hr-blurry" />
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Current/Last Organisaiton</label>
                    <input type="text" placeholder="Enter Organisaiton Name Here.." class="form-control inputDisabled required"  name="company_current"  value="<?php echo $company_current;?>" disabled>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Current/Last Position</label>
                    <input type="text" placeholder="Enter Position Name Here.." class="form-control inputDisabled required"  name="designation_current"  value="<?php echo $designation_current;?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Skill Set</label>
                    <select class="form-control required inputDisabled" name="skillSet" id="SkillSet"  disabled>
                        <option value="">Select Skill Set</option>
                        <option value="Bridges" <?php if($skill_set == 'Bridges'): ?> selected="selected"<?php endif; ?>>Bridges</option>
                        <option value="Buildings" <?php if($skill_set == 'Buildings'): ?> selected="selected"<?php endif; ?>>Buildings</option>
                        <option value="Highways" <?php if($skill_set == 'Highways'): ?> selected="selected"<?php endif; ?>>Highways</option>
                        <option value="Industrial" <?php if($skill_set == 'Industrial'): ?> selected="selected"<?php endif; ?>>Industrial</option>
                        <option value="Irrigation" <?php if($skill_set == 'Irrigation'): ?> selected="selected"<?php endif; ?>>Irrigation</option>
                        <option value="Marine" <?php if($skill_set == 'Marine'): ?> selected="selected"<?php endif; ?>>Marine</option>
                        <option value="Metro" <?php if($skill_set == 'Metro'): ?> selected="selected"<?php endif; ?>>Metro</option>
                        <option value="Oil & Gas" <?php if($skill_set == 'Oil & Gas'): ?> selected="selected"<?php endif; ?>>Oil & Gas</option>
                        <option value="Petrochemicals" <?php if($skill_set == 'Petrochemicals'): ?> selected="selected"<?php endif; ?>>Petrochemicals</option>
                        <option value="Power" <?php if($skill_set == 'Power'): ?> selected="selected"<?php endif; ?>>Power</option>
                        <option value="Railway" <?php if($skill_set == 'Railway'): ?> selected="selected"<?php endif; ?>>Railway</option>
                        <option value="Tunnels" <?php if($skill_set == 'Tunnels'): ?> selected="selected"<?php endif; ?>>Tunnels</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Total Expereince</label>
                    <select class="form-control required inputDisabled" name="Total Expereince" id="Total Expereince" disabled>
                        <option value="">Select Total Expereince Here..</option>
                        <option value="0-1" <?php if($exp == '0-1'): ?> selected="selected"<?php endif; ?>>0-1</option>
                        <option value="1-5" <?php if($exp == '1-5'): ?> selected="selected"<?php endif; ?>>1-5</option>
                        <option value="5-10" <?php if($exp == '5-10'): ?> selected="selected"<?php endif; ?>>5-10</option>
                        <option value="15-20" <?php if($exp == '15-20'): ?> selected="selected"<?php endif; ?>>15-20</option>
                        <option value="10-15" <?php if($exp == '10-15'): ?> selected="selected"<?php endif; ?>>10-15</option>
                        <option value="20+" <?php if($exp == '20+'): ?> selected="selected"<?php endif; ?>>20+</option>
                    </select>
                </div>
            </div>
            <?php } ?>
            <hr class="hr hr-blurry" />
            <br/>
            <table border="0" width="100%" class="table" id="employee_table">
                <tbody>
                    <tr>
                        <th>Skills</th>
                        <th>Scores</th>
                        <th style="width:96px;">Action</th>
                    </tr>
                    <?php 
                        $countrw =1;
                        if(!empty($skill['skill'])){
                        foreach ($skill['skill'] as $key => $value) { 
                          $scores =$skill['scores'][ $key];
                          if($value!=""){
                        ?>
                    <tr>
                        <?php if (in_array('professional', $userRolesChk) || in_array('administrator', $userRolesChk)){ ?>		        	
                        <td>
                            <select data-live-search="true"  class="form-control inputDisabled" name="Skills[skill][]" disabled required>
                                <option value="" disabled selected>Select Skills</option>
                                <?php foreach ($professionalSkills as $key => $skillsObj) { ?>
                                <option value="<?php echo $skillsObj->name;?>" <?php if($value == $skillsObj->name): ?> selected="selected"<?php endif; ?>><?php echo $skillsObj->name;?></option>
                                <?php } ?>          
                            </select>
                        </td>
                        <?php  }  ?>
                        <?php if (in_array('student', $userRolesChk)){ ?>		        	
                        <td>
                            <select data-live-search="true"  class="form-control inputDisabled" name="Skills[skill][]" disabled required>
                                <option value="" disabled selected>Select Skills</option>
                                <?php foreach ($studentSkills as $key => $skillsObj) { ?>
                                <option value="<?php echo $skillsObj->name;?>" <?php if($value == $skillsObj->name): ?> selected="selected"<?php endif; ?>><?php echo $skillsObj->name;?></option>
                                <?php } ?>          
                            </select>
                        </td>
                        <?php  }  
                            if($scores==""){
                            	$scores =1;
                            }
                            ?>
                        <td>
                            <select data-live-search="true"  class="form-control inputDisabled" name="Skills[scores][]" disabled required>
                                <option value="" disabled selected>Select Scores</option>
                                <option value="1" <?php if($scores == '1'): ?> selected="selected"<?php endif; ?>>1</option>
                                <option value="2" <?php if($scores == '2'): ?> selected="selected"<?php endif; ?>>2</option>
                                <option value="3" <?php if($scores == '3'): ?> selected="selected"<?php endif; ?>>3</option>
                                <option value="4" <?php if($scores == '4'): ?> selected="selected"<?php endif; ?>>4</option>
                                <option value="5" <?php if($scores == '5'): ?> selected="selected"<?php endif; ?>>5</option>
                            </select>
                        </td>
                        <td><button class="remove_employee inputDisabled" disabled>Remove</button></td>
                    </tr>
                    <?php $countrw++; } } }?>
                </tbody>
            </table>
            <a href="javascript:void(0)"  class="add_employee btn btn-color-2 inputDisabledadmore" disabled="disabled">Add More</a>
            <br><br>
            <div style="display:none;" class="alert alert-success" id="updateproInfo1" role="alert">Your account has been Updated successfully.</div>
            <button type="submit" class="btn update-btn btn-lg btn-info inputDisabled" disabled>Update Profile</button>
            <button type="button" id="removeDisable2" class="btn btn-color-1">Enable Edit Mode</button>	
        </form>
    </div>
</div>
<script> 
    jQuery(document).ready(function() {
    
        var max_rows   = 10; //Maximum allowed group of input fields 
        var wrapper    = jQuery("#employee_table"); //Group of input fields wrapper
        var add_button = jQuery(".add_employee"); //Add button class or ID
        var x = 1;
    
        jQuery(add_button).click(function(e){ //On click add employee button
            e.preventDefault();
            if(x < max_rows){ //max group of input fields allowed
                x++; //group of input fields increment
               <?php if (in_array('professional', $userRolesChk) || in_array('administrator', $userRolesChk)){ ?>	
                jQuery(wrapper).append('<tr><td><select data-live-search="true"  class="form-control" name="Skills[skill][]"><option value="">Select Skills</option><?php foreach ($professionalSkills as $key => $skillsObj) { ?> <option value="<?php echo $skillsObj->name;?>"><?php echo $skillsObj->name;?></option>  <?php } ?></select>	</td><td><select data-live-search="true"  class="form-control" name="Skills[scores][]"><option value="">Select Scores</option> <option value="1">1</option><option value="2" >2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td><td><button class="remove_employee">Remove</button></td></tr>'); 
                <?php } ?>
    				}
    				<?php if (in_array('student', $userRolesChk)){ ?>	
                jQuery(wrapper).append('<tr><td><select data-live-search="true"  class="form-control" name="Skills[skill][]"><option value="">Select Skills</option><?php foreach ($studentSkills as $key => $skillsObj) { ?> <option value="<?php echo $skillsObj->name;?>"><?php echo $skillsObj->name;?></option>  <?php } ?></select>	</td><td><select data-live-search="true"  class="form-control" name="Skills[scores][]"><option value="">Select Scores</option> <option value="1">1</option><option value="2" >2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td><td><button class="remove_employee">Remove</button></td></tr>'); 
            <?php } ?>
        }); 
    
        jQuery(wrapper).on("click",".remove_employee", function(e){ // On click to remove button
            e.preventDefault(); jQuery(this).parent().parent().remove(); x--;
            var rowCount = jQuery("#employee_table tr").length;
            if(rowCount>2){
              jQuery('#employee_table tr:last td:last-child').html('<button class="remove_employee">Remove</button>');
            }
        });
    });
</script>
<style> 
    .inputDisabledadmore {
    pointer-events: none;
    cursor: default;
    }
</style>
<?php } ?>