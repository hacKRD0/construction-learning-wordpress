<?php
$obj = new Trikona();
global $wpdb;
switch_to_blog(1);
$current_user = wp_get_current_user();
$active_user_group_id = get_query_var( 'active_user_group_id' );

$project_tab_accessible = false;
$tabmemberships = $wpdb->prefix . "pmpro_memberships_users";
$current_user_plan = $obj->getLoggedInUserMembership($current_user->ID);

if (!empty($current_user_plan)) {
    if ($current_user_plan->membership_id == $obj->corporate_basic_mem_id) {
        $project_tab_accessible = false;
    } else if ($current_user_plan->membership_id == $obj->corporate_prime_mem_id) {
        $project_tab_accessible = false;
    } else if ($current_user_plan->membership_id == $obj->corporate_elite_mem_id) {
        $project_tab_accessible = true;
    }
}

$allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);

if (!$project_tab_accessible && empty($allowed_roles)) { ?>
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
	if (empty($group_id) && !empty($active_user_group_id)) {
	    $group_id = $active_user_group_id;
	}
	$result = $obj->getProjects(['group_id' => $group_id]);
	restore_current_blog();

	//not assign any group

	if($group_id > 0){
	    if (!function_exists('wp_generate_attachment_metadata')) {
	        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	    }
	   ?>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<?php
	if (isset($_GET['post'])) {
		switch ($_GET['post']) {
			case 'successfull':
				?>
	<div  class="alert alert-success" id="updateproInfo2" role="alert">Project Added.</div>
				<?php
								break;
							case 'delete':
								?>
	<div  class="alert alert-success" id="updateproInfo2" role="alert">Porject Deleted.</div>
				<?php
								break;
						}
					}
	?>

	<?php


	 if($_GET['projectId']==""){


	 ?>


			<div class="table-responsive">
				<div class="table-wrapper">
					<div class="table-title">
						<div class="row">
							<div class="col-xs-9">
								
							</div>
							<div class="col-xs-3">
								<a href="#addProjectModal" class="btn btnsuccess" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Projects</span></a>
													
							</div>
						</div>
					</div>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Description</th>
								<th>Images</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
	                         $i=1;
							 foreach ($result as $key => $projects) {
								$project_images =  explode(',',  $projects->project_images);
								
								
								?>
							<tr>
								<td>
									<?=  $i;?>
								</td>
								<td><?= $projects->project_name;?></td>

								<td style="width: 200px !important;"><?= $projects->project_desc;?></td>

								<td>  <?php foreach ($project_images as $key => $img) {

									$image_attributes = wp_get_attachment_image_src( $img );

								 ?>
									  <img src="<?= $image_attributes[0]?>">
								<?php } ?></td>
								<td>
									<a href="javascript:void(0);" data-id="<?= $projects->id ?>" class="edit edit-project" ><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
									<a href="javascript:void(0);" post_id="<?= $projects->id; ?>" class="delete deleteProject"><i class="material-icons"  title="Delete">&#xE872;</i></a>
								</td>
							</tr>
						<?php $i++; } ?>
							
						</tbody>
					</table>
					<input type="hidden" id="projectid" value="">
					
				</div>
			</div> 

	<?php }else{ 


	switch_to_blog(1);
	$table_name = $wpdb->prefix . "bp_group_projects";
	$projectId = $_GET['projectId'];
	restore_current_blog();
	$result = $obj->getProjects(['group_id' => $group_id, 'id' => $projectId]);
	$project_images =  explode(',',  $result->project_images);



	if($_POST['edit_id']){

	   $files = $_FILES['project_images'];
	if(!empty($files)){
	$attach_ids=array();
	foreach ($files['name'] as $key => $value) {
	    if ($files['name'][$key]) {
	        $file = array(
	            'name'     => $files['name'][$key],
	            'type'     => $files['type'][$key],
	            'tmp_name' => $files['tmp_name'][$key],
	            'error'    => $files['error'][$key],
	            'size'     => $files['size'][$key]
	        );
	        $_FILES = array("upload_attachment" => $file);

	        foreach ($_FILES as $file => $array) {
	            $attach_id = media_handle_upload($file,$post_id);
	            array_push($attach_ids , $attach_id);
	        }
	    }
	}


	  }

	  //merge array
	   if(!empty($project_images) && !empty($files)){
	   	echo "efrfr";
	      $attach_ids =  array_merge($project_images,$attach_ids);
	   }

	   $attach_idss = implode(', ', $attach_ids);

	    $result = $wpdb->update($table_name, 
	    array( 
	        'project_name' => $_POST['project_name'],
		    'project_desc' => $_POST['project_desc'],
		    'project_images' => $attach_idss,  
	    ), 
	    array(
	        "id" => $_GET['projectId']
	    ) 
	);


	}
	if($_POST['edit_id']){
	 $result = $obj->getProjects(['group_id' => $group_id, 'id' => $projectId]);	
	$project_images =  explode(',',  $result->project_images);
	?>  
	<div  class="alert alert-success" id="updateproInfo2" role="alert">Project Updated.</div>
	<?php }
	?>
	<div class="col-lg-12 well">
	<div class="row">
	<div class="col-sm-8">
	<form class="form"  name="upload_images_projects" method="POST" enctype="multipart/form-data">
	<input name="edit_id" type="hidden" value="<?=  $group_id; ?>">
							
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="project_name" class="form-control" required value="<?= $result->project_name;?>">
			</div>
			
			<div class="form-group">
				<label>Description</label>
				<textarea class="form-control" name="project_desc" required> <?= $result->project_desc ?></textarea>
			</div>
			<div class="form-group">

			<div class="gallery-grid">
				<?php foreach ($project_images as $key => $img) { 
					$image_attributes = wp_get_attachment_image_src( $img );
					if($image_attributes[0]!=""){
					$image_attributes = wp_get_attachment_image_src( $img );
					?>
			    <figure class="gallery-frame" id="galler_<?= $img;?>">
			      <img class="gallery-img" src="<?= $image_attributes[0]?>">
			      <figcaption><span  style="cursor:pointer;" data-id="<?=  $img;?>" class="btn deleteImg">Remove</span></figcaption>
			    </figure>
			    <?php } } ?>

			  </div><!-- end:gallery-grid -->
			</div>
			<div class="form-group">
				<label>Upload Multiple images</label>
				<div class="upload__box">
				  <div class="upload__btn-box" style="width:60%;">
				    <label class="upload__btn">
				      <p>Choose Images</p>
				      <input type="file" name="project_images[]" multiple="" data-max_length="4" class="upload__inputfile">
				    </label>
				  </div>
				  <div class="upload__img-wrap"></div>
				</div>
			</div>					
		
		<div class="imgfooter">
			<a href="<?= home_url()?>/directories/company-dashboard/?companyProfile=true&detsild=projects" class="btn">BACK</a>
			<input type="submit" class="btn btn-success" value="Update">
		</div>
	</form>
	</div>
	</div>
	</div>
	<style> 
	.gallery-grid { display: -ms-grid;  display: grid;  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); grid-gap: 1.5rem; justify-items: center;  margin: 0;  padding: 0;}
	.gallery-frame { padding: 2.5rem; font-size: 1.2rem; text-align: center; background-color: #fafafa; color: #d9d9d9;}
	.gallery-img { max-width: 100%; height: auto; object-fit: cover; transition: opacity 0.25s ease-in-out;}
	.gallery-img:hover { opacity: .7;}
	</style>

	<script>  
	  jQuery(".deleteImg").click(function(){  
	     var attach_ids =  jQuery(this).attr('data-id');
	        jQuery.ajax({
	         	type: "POST",
	         	dataType: "json",
	         	url : '<?= admin_url( 'admin-ajax.php' );?>',
	         	data : {action: "remove_project_img", attach_ids : attach_ids,project_id:'<?= $projectId;?>'},
	       
		        success: function(response) {
		        	if (response.success) {
	        			jQuery('.projects-success-msg').removeClass('hide');
	        			jQuery('.projects-success-msg').html(response.message);
	        			setTimeout(function() {
		        			window.location.reload();
	        			}, 300);
	        		} else {
	        			$('.projects-error-msg').removeClass('hide');
	        			$('.projects-error-msg').html(response.message);
	        		}
		        }
	    	});
	    });

	</script>
			<?php } ?>
	<?php ?>
		
		<!-- Delete Modal HTML -->
		<div id="deleteEmployeeModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form>
						<div class="modal-header">						
							<h4 class="modal-title">Delete Project</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">					
							<p>Are you sure you want to delete these Records?</p>
							<p class="text-warning"><small>This action cannot be undone.</small></p>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="button" id="delProject" class="btn btn-danger" value="Delete">
						</div>
					</form>
				</div>
			</div>


	</div>

	<div id="addProjectModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form"  name="create_new_project" method="POST" enctype="multipart/form-data">
		            <input type="hidden" name="action" value="create_company_project">
		            <input name="company_id" type="hidden" value="<?=  $group_id; ?>">
					<div class="modal-header">						
						<h4 class="modal-title">Add Projects</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="col-md-12 alert alert-success company-project-success-msg d-none"><ul></ul></div>
						<div class="col-md-12 alert alert-danger company-project-error-msg d-none"><ul></ul></div>
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="project_name" class="form-control" required>
						</div>
						
						<div class="form-group">
							<label>Description</label>
							<textarea class="form-control" name="project_desc" required></textarea>
						</div>
						<div class="form-group">
							<label>Upload Multiple images</label>
							<div class="upload__box">
							  <div class="upload__btn-box">
							    <label class="upload__btn">
							      <p>Choose Images</p>
							      <input type="file" name="project_images[]" multiple="" data-max_length="4" class="upload__inputfile">
							    </label>
							  </div>
							  <div class="upload__img-wrap"></div>
							</div>
						</div>					
					</div>
					<div class="modal-footer">
						<div class="d-flex align-items-center loader d-none">
						  <strong>Loading...</strong>
						  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
						</div>

						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="updateProjectModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form"  name="update_company_project" method="POST" enctype="multipart/form-data">
		            <input type="hidden" name="action" value="update_company_project">
		            <input name="group_id" type="hidden" value="<?=  $group_id; ?>">
		            <input name="project_id" type="hidden">
					<div class="modal-header">						
						<h4 class="modal-title">Edit Project</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="col-md-12 alert alert-success company-project-success-msg d-none"><ul></ul></div>
						<div class="col-md-12 alert alert-danger company-project-error-msg d-none"><ul></ul></div>
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="project_name" class="form-control" required>
						</div>
						
						<div class="form-group">
							<label>Description</label>
							<textarea class="form-control" name="project_desc" required></textarea>
						</div>
						<div class="form-group">
							<label>Upload Multiple images</label>
							<div class="upload__box">
							  <div class="upload__btn-box">
							    <label class="upload__btn">
							      <p>Choose Images</p>
							      <input type="file" name="project_images[]" multiple="" data-max_length="4" class="upload__inputfile">
							    </label>
							  </div>
							  <div class="upload__img-wrap"></div>
							</div>
						</div>					
					</div>
					<div class="modal-footer">
						<div class="d-flex align-items-center loader d-none">
						  <strong>Loading...</strong>
						  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
						</div>

						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>  

	//delete projects
		jQuery('body').on("click",".deleteProject", function(e){
	        e.preventDefault(); 
	       var project_id=   jQuery(this).attr('post_id');
	        jQuery("#projectid").val(project_id);
	      
	        jQuery('#deleteEmployeeModal').modal('show');
	    });

		jQuery('body').on("click","#delProject", function(e){ 
	        e.preventDefault(); 
	       	var project_id=    jQuery("#projectid").val();
	       	jQuery('#deleteEmployeeModal').modal('hide');
		      jQuery.ajax({
		        type: "POST",
		        url : '<?= admin_url( 'admin-ajax.php' );?>',
		        data : {action: "delete_company_project", project_id : project_id}, 
		        dataType: "json",      
		        success: function(response) {
	        		if (response.success) {
	        			jQuery('.projects-success-msg').removeClass('d-none');
	        			jQuery('.projects-success-msg').html(response.message);
	        			setTimeout(function() {
		        			window.location.reload();
	        			}, 500);
	        		} else {
	        			$('.projects-error-msg').removeClass('d-none');
	        			$('.projects-error-msg').html(response.message);
	        		}
		        }
		    });
	       
	    });

	    jQuery('.edit-project').click(function(){
	    	var project_id = jQuery(this).data('id');

	    	if (project_id != "") {
	    		jQuery('#updateProjectModal input[name=project_id]').val(project_id);

			    jQuery.ajax({
			        type: "POST",
			        url : '<?= admin_url( 'admin-ajax.php' );?>',
			        data : {action: "get_company_project", project_id : project_id}, 
			        dataType: "json",      
			        success: function(response) {
		        		if (response.success) {
				    		jQuery('#updateProjectModal input[name=project_name]').val(response.project_data.project_name);
				    		jQuery('#updateProjectModal textarea[name=project_desc]').html(response.project_data.project_desc);
		        			setTimeout(function() {
					    		jQuery('#updateProjectModal').modal('show');
		        			}, 500);
		        		} else {
		        			$('.company-project-error-msg').removeClass('d-none');
		        			$('.company-project-error-msg').html(response.message);
				    		jQuery('#updateProjectModal').modal('show');
		        		}
			        }
			    });
	    	}
	    })

		jQuery('form[name=create_new_project], form[name=update_company_project]').submit(function(e) {
			e.preventDefault();

			var current_form = jQuery(this).attr('name');
			var formData = new FormData(this);
			jQuery('form[name='+current_form+'] input[type=submit]').attr('disabled', true);
			jQuery('.loader').removeClass('d-none');

		    jQuery.ajax({
		          	type: "POST",
		         	url : '<?= admin_url( 'admin-ajax.php' );?>',
		         	data : formData,
		         	dataType: "json",
		         	processData: false,
		         	contentType: false,
		        	success: function(response) {
		        		if (response.success) {
	        				jQuery('.loader').addClass('d-none');
		        			jQuery('.company-project-success-msg').removeClass('d-none');
		        			jQuery('.company-project-success-msg ul').html(response.message);
		        			setTimeout(function() {
			        			window.location.reload();
		        			}, 500);
		        		} else {
		        			jQuery('.loader').addClass('d-none');
		        			jQuery('.company-project-error-msg').removeClass('d-none');
		        			jQuery('.company-project-error-msg ul').html(response.message);
		        			jQuery('form[name='+current_form+'] input[type=submit]').removeAttr('disabled');
		        		}
		        	}
		    });
		});
	</script>
	<?php } ?>
<?php } ?>