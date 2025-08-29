<?php
$obj = new Trikona();

global $wpdb;
switch_to_blog(1);
$current_user = wp_get_current_user();
$active_user_group_id = get_query_var( 'active_user_group_id' );
restore_current_blog();

$group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";
if (empty($group_id) && !empty($active_user_group_id)) {
    $group_id = $active_user_group_id;
}

$errors = [];

if($group_id > 0){

 	if($_POST['company_profile_id']!="" &&  $_FILES['document_company']!=""){
	    if (!function_exists('wp_generate_attachment_metadata')) {
	        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	    }
	    $files = $_FILES['document_company'];

	    $errors = $obj->ValidateFiles($files);

	    if (empty($errors)) {
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
			                if (!is_object($attach_id)) {
				                array_push($attach_ids , $attach_id);
			                }
			            }
			        }
			    }
		    }
	        
	        $attach_idss = implode(', ', $attach_ids);
	        switch_to_blog(1);
	        $table_name = $wpdb->prefix."bp_group_documents";
	        $wpdb->insert($table_name, array(
	          	    'group_id' =>$_POST['company_profile_id'],
				    'doc_name' => $_POST['doc_name'],
				    'doc_attachments' => $attach_idss,
			));
	        restore_current_blog();
	    }
    }

?>
    	<div class="row well">
    	<div class="col-sm-12">

      
<?php 
	?>
  
<?php 
$result = $obj->getDocuments(['group_id' => $group_id]);
?>
<h5>Upload Profile</h5>
<br/>
<?php if(!empty($errors)){ ?>
    <div class="alert alert-danger">
        <ul>
            <?= '<li>' . implode( '</li><li>', $errors) . '</li>'; ?>
        </ul>
    </div>
<?php } ?>
<?php  if($_POST['company_profile_id']!="" && empty($errors)){ ?>
<div  class="alert alert-success" id="updateproInfo2" role="alert">Data Inserted Successfully.</div>
<?php } 

$current_user = wp_get_current_user();
$term_obj_list = get_the_terms($current_user->ID, 'bp_member_type' );
    $termArr = array();
        if(!empty($term_obj_list)){
       foreach ($term_obj_list as $key => $term_obj_value) {
          $termArr[$term_obj_value->name]= $term_obj_value->name;
            }
          
            unset($termArr['student']);
            unset($termArr['professional']);
             //$memberType = implode(', ', $termArr);
          } 
	 $term_list = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'slugs' ) );
?>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<div class="table-responsive">
			<div class="table-wrapper">
				<div class="table-title">
					<div class="row">
						<div class="col-xs-9">
							
						</div>
						<div class="col-xs-3">
							<a href="#addDocuemntModal" class="btn btnsuccess inputDisabled d-none" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Upload Documents</span></a>
												
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th>Name</th>
							<th>Document</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							foreach ($result as $key => $projects) {
							$project_images =  explode(',',  $projects->doc_attachments);	
						?>
						<tr>
							<td><?= $i; ?></td>
							<td><?= $projects->doc_name;?></td>

							<td>  <?php foreach ($project_images as $key => $img) {
								$image_attributes = wp_get_attachment_url( $img );
                                //print_r($image_attributes);
							 ?>
								  <a href="<?= $image_attributes?>"><?= $projects->doc_name;?></a>
							<?php } ?></td>
							<td>
								
								<a post_id="<?= $projects->id;?>"  href="#" class="delete deleteDoc inputDisabled d-none hide" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>
					<?php $i++; } ?>
						
					</tbody>
				</table>
				<input type="hidden" id="documetsIds" value="">
				<div class="clearfix" style="display:none">
					<div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
					<ul class="pagination">
						<li class="page-item disabled"><a href="#">Previous</a></li>
						<li class="page-item"><a href="#" class="page-link">1</a></li>
						<li class="page-item"><a href="#" class="page-link">2</a></li>
						<li class="page-item active"><a href="#" class="page-link">3</a></li>
						<li class="page-item"><a href="#" class="page-link">4</a></li>
						<li class="page-item"><a href="#" class="page-link">5</a></li>
						<li class="page-item"><a href="#" class="page-link">Next</a></li>
					</ul>
				</div>
			</div>
			<button type="button" id="removeDisable2" class="btn btn-lg btn-info mt-2">Edit</button>
		</div>

	<!-- Delete Modal HTML -->
	<div id="deleteDocModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form>
					<div class="modal-header">						
						<h4 class="modal-title">Delete Document</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<p>Are you sure you want to delete these Records?</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="button" class="btn btn-danger" value="Delete" id="deleteDocBtn">
					</div>
				</form>
			</div>
		</div>


</div>

<div id="addDocuemntModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form"  name="upload_companies_doc" method="POST" enctype="multipart/form-data">
                  <input name="company_profile_id" type="hidden" value="<?=  $group_id; ?>">
					<div class="modal-header">						
						<h4 class="modal-title">Upload Docuemts</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="doc_name" class="form-control" required>
						</div>
						
						
						<div class="form-group">
							<label>Upload  Docuemnt</label>
							<input class="form-control" type="file" id="document_company" name="document_company[]" />
						</div>					
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>

<style> 
input[type="file"] {
	 display: block !important;
}
</style>
<script>  

//delete documets
jQuery('body').on("click",".deleteDoc", function(e){
        e.preventDefault(); 
       var project_id=   jQuery(this).attr('post_id');
        jQuery("#documetsIds").val(project_id);
      
        jQuery('#deleteDocModal').modal('show');
    });

	jQuery('body').on("click","#deleteDocBtn", function(e){ 
        e.preventDefault(); 
       	var documet_id = jQuery("#documetsIds").val();
       	jQuery('#deleteDocModal').modal('hide');
      	jQuery.ajax({
          	type: "POST",
         	url : '<?= admin_url( 'admin-ajax.php' );?>',
         	data : {action: "delete_company_doc", documet_id : documet_id},
         	dataType: "json",
        	success: function(response) {
        		if (response.success) {
        			jQuery('.company-doc-success-msg').removeClass('hide');
        			jQuery('.company-doc-success-msg').html(response.message);
        			setTimeout(function() {
	        			window.location.reload();
        			}, 500);
        		} else {
        			$('.company-doc-error-msg').removeClass('hide');
        			$('.company-doc-error-msg').html(response.message);
        		}
        		//window.location.href = "<?=  home_url()?>/company-profile/?companyProfile=true&detsild=document";
        	}
    });
       
    });

</script>

<?php }else{ 

	echo do_shortcode('[elementor-template id="33289"]');

 

//} 
}
?>