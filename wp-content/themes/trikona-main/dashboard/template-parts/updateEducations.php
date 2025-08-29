<?php if($_GET['updateEducations']=='true'){
    $active_user_id = get_current_user_id();

    if (isset($_GET['id'])) {
        $active_user_id = base64_decode($_GET['id']);
    }

    $args = array(  
        'post_type' => 'studentcourses',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => 'title', 
        'order' => 'ASC', 
    );

    $loop = new WP_Query( $args ); 
    global $wpdb,$bp, $trikona_obj;
    $results = $wpdb->get_results("SELECT field.id as id, field.name as name FROM {$bp->profile->table_name_fields} as field INNER JOIN {$bp->profile->table_name_meta} as meta ON field.id = meta.object_id
    WHERE meta.object_type = 'field' AND meta.meta_key = 'do_autolink' AND meta.meta_value = 'on'");

    $results_fls = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$results[1]->id."'");
    $results_yrs = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$results[2]->id."'");

    $institutes = $trikona_obj->getBuddyPressGroups($trikona_obj->bp_group_type_college);
?>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<div class="table-wrapper">
    <div class="table-title">
        <div class="row">
            <div class="col-sm-8 mr-0">
            <h5 class="cs-label-1">Educations Details</h5>
            </div>
            <div class="col-sm-4 cs-label-1">
                <button type="button" class="btn btn-info add-newedu "><i class="fa fa-plus"></i> Add New</button>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                 <th>Institute</th>						
                <th>Qualification</th>
                <th>Course</th>
                <th>Duration</th>
                <th>Start Date</th>
                <th>Year of Passout</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="userData">
            <?php 
                // Load and initialize database class 
                $members_education = $trikona_obj->getUserEducations(['user_id' => $active_user_id]);

                if($wpdb->num_rows > 0){ 
                	$rowid= 1;
        			foreach ($members_education  as $key => $education) {   
                ?>
                    <tr id="<?php echo $education->id; ?>">
                        
                        <td>
                            <span class="editSpan institute_name"><?php echo $education->institute_name; ?></span>
                            <?php /*<input class="form-control editInput institute_name" type="text" name="institute_name" value="<?php echo $education->institute_name; ?>" style="display: none;">*/ ?>

                            <select  class="form-control editInput institute_name" name="institute_name" style="display: none;">
                                <option value="">Select</option>
                                <?php foreach ($institutes as $institute) { ?>
                                <option value="<?= $institute->name ?>" <?php if($education->institute_name==$institute->name){ echo 'selected="selected"'; } ?>><?= $institute->name ?></option>
                                <?php } ?>  
                            </select>
                        </td>
                        <td>
                            <span class="editSpan qualification"><?php echo $education->qualification ?></span>
                               <select  class="form-control editInput qualification" name="qualification" style="display: none;">
        						<option value="">Select</option>
        						<?php foreach ($results_fls as $key => $field) { ?>
        						<option value="<?php echo $field->name;?>" <?php if($education->qualification==$field->name) echo 'selected="selected"'; ?>><?php echo $field->name;?></option>
        						<?php } ?>	
        					</select>
                        </td>
                        <td>
                            <span class="editSpan courseofStudy"><?php echo $education->courseofStudy; ?></span>
                            <select  class="form-control editInput courseofStudy" name="courseofStudy" style="display: none;">
        			                  <option value="">Select</option>
        			                  <?php     while ( $loop->have_posts() ) : $loop->the_post(); $title= get_the_title(get_the_ID());  ?>
                                                <option value="<?php the_title();?>" <?php if($title==$education->courseofStudy) echo 'selected="selected"'; ?>><?php the_title();?></option>

        			                  <?php endwhile; ?>

        					            		
        					          </select>
                        </td>
                        <td>
                            <span class="editSpan duration"><?php echo $education->duration; ?></span>
                            <input class="form-control editInput duration" type="text" name="duration" value="<?php echo $education->duration; ?>" style="display: none;">
                        </td>
                        
                        <td>
                            <span class="editSpan startDate StartDate"><?php echo $education->startDate; ?></span>
                            <input class="form-control editInput StartDate" type="text" name="startDate" value="<?php echo $education->startDate; ?>" style="display: none;">
                        </td>

                        <td>
                           <span class="editSpan year_passout"><?php echo $education->year_passout; ?></span>
                           	<select class="form-control editInput year_passout" name="year_passout" style="display: none;">
                           		<option>Select</option>
                           <?php foreach ($results_yrs as $key => $value) { ?>
                           
                               <option <?php if($value->name == $education->year_passout){ echo ' selected="selected"'; } ?> value="<?php echo $value->name?>" ><?php echo $value->name;?></option>
        					          
                            
                           <?php } ?>
                           </select>
                           
                        </td>

                         <td>
                           <span class="editSpan description"><?php echo $education->description; ?></span>
                            <input class="form-control editInput description" type="text" name="description" value="<?php echo $education->description; ?>" style="display: none;">
                        </td>

                        <td>
                            <a href="javascript:void(0);" class="editBtn1 edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="javascript:void(0);" class="deleteBtn1 delete"><i class="fa-solid fa-circle-xmark"></i></a>
                            
                            <button type="button" class="btn btn-success saveBtn1" style="display: none;"><i class="fa-solid fa-circle-check"></i></button>
                            <button type="button" class="btn btn-danger confirmBtn1" style="display: none;">Confirm</button>
                            <button type="button" class="btn btn-secondary cancelBtn1" style="display: none;"><i class="fa-solid fa-circle-xmark"></i></button>
                        </td>
                    </tr>
                <?php 
                    $rowid ++; } 
                }else{ 
                   echo '<tr><td colspan="7">No record(s) found...</td></tr>'; 
                } 
            ?>
        </tbody>
    </table>
</div>
<script> 
    jQuery(".add-newedu").click(function(){
    	jQuery(this).attr("disabled", "disabled");
    	var index = jQuery("table tbody tr:last-child").index();
        var row = '<tr id="insertRoew">' +
            '<td><input class="editInput" type="hidden" name="current_user_id" value="<?= base64_encode($active_user_id) ?>"><select  class="form-control editInput institute_name" name="institute_name"><option value="">Select</option><?php foreach ($institutes as $institute) { ?><option value="<?= $institute->name ?>" <?php if($education->institute_name==$institute->name){ echo 'selected="selected"'; } ?>><?= $institute->name ?></option><?php } ?></select></td>' +
            '<td> <select  class="form-control editInput qualification" name="qualification"><option value="">Select</option><?php foreach ($results_fls as $key => $field) { ?><option value="<?php echo $field->name;?>"><?php echo $field->name;?></option><?php } ?></select></td>' +'<td><select  class="form-control editInput courseofStudy" name="courseofStudy"><option value="">Select</option><?php  while ( $loop->have_posts() ) : $loop->the_post(); ?> <option value="<?php the_title();?>"><?php the_title();?></option><?php endwhile;?></select></td>' +'<td><input class="form-control editInput duration" type="text" name="duration" value="" ></td>' +'<td><input class="form-control editInput startDate StartDate" type="text" name="startDate" value=""></td>' +'<td><select class="form-control editInput year_passout" name="year_passout"> <?php foreach ($results_yrs as $key => $value) { ?> <option value="<?php echo $value->name?>"><?php echo $value->name;?></option> <?php }?></select></td>' +'<td><input class="form-control editInput description" type="text" name="description" value="" ></td>' +'<td class="d-flex">' + '<button type="button" class="btn btn-success saveBtn1" ><i class="fa-solid fa-circle-check"></i></button><button type="button" class="deleteBtn1 deleteone btn btn-secondary"><i class="fa-solid fa-circle-xmark"></i></button>' + '</td>' +
        '</tr>';
    	jQuery("table").append(row);

        jQuery('#insertRoew select.institute_name').select2({
            tags: true
        });
    });
    jQuery(document).on("click", ".deleteBtn1", function(){
        jQuery(".add-newedu").removeAttr('disabled');
    });
</script>
<style> 
</style>
<?php } ?>
