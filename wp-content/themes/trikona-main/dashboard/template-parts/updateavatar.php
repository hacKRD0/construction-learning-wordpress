<?php if($_GET['updateavatar']=='true'){

$user_id = get_current_user_id();
   //print_r($_FILES);
    if ( isset( $_FILES['input-b7']['name'] )  && $_FILES['input-b7']['name']!="") {


            $file_tmp= $_FILES['input-b7']['tmp_name']; 
            $data = file_get_contents($file_tmp);
            $imgdata = 'data:image/' . $type . ';base64,' . base64_encode($data);         
           $f = finfo_open();
           $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
           $type_file = explode('/', $mime_type);
           $ext = pathinfo($_FILES['input-b7']['name'], PATHINFO_EXTENSION); 
                 
           $time = time();
           $avatar_thumb = $time . '-bpthumb.' . $ext;
           $avatar_full = $time . '-bpfull.' . $ext; 
           $uploaddir = wp_upload_dir(); 
           $myDirPath = $uploaddir["path"]; 
           $myDirUrl = $uploaddir["url"];
           $profile_subdir = '/avatars/' .get_current_user_id();
           $upload_path=  $uploaddir['path'] = $uploaddir['basedir'] . $profile_subdir;

    if (!file_exists($upload_path))  {
      mkdir($upload_path, 0755, true);
    }
    require_once(ABSPATH . '/wp-load.php');             
    require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
    require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
    // Remove files into user's folder
   $files = list_files($upload_path);
   for($i = 0; $i<count($files); $i++)
   {
     wp_delete_file($files[$i]);
   }

   //file_put_contents($upload_path.'/'.$avatar_thumb,$_FILES['file']);
   //file_put_contents($upload_path.'/'.$avatar_full,$_FILES['file']);

   if (move_uploaded_file($_FILES['input-b7']['tmp_name'], $upload_path.'/'.$avatar_full)) {
   	move_uploaded_file($_FILES['input-b7']['tmp_name'], $upload_path.'/'.$avatar_full);


   
} else {
  
}
   

   $filename = $myDirUrl.'/'.basename( $avatar_full );
   $wp_filetype = wp_check_filetype(basename($filename), null );
   $uploadfile = $upload_path.'/'. basename( $filename );           
   $attachment = array(
      "post_mime_type" => $wp_filetype["type"],
      "post_title" => preg_replace("/\.[^.]+$/", "" , basename( $filename )),
      "post_content" => "",
      "post_status" => "inherit",
      'guid' => $uploadfile,
   );              


   $attachment_id = wp_insert_attachment( $attachment, $uploadfile );

   update_post_meta($attachment_id,'_wp_attachment_wp_user_avatar',$user_id);
   update_user_meta($user_id, 'wp_user_avatar', $attachment_id);     
	            
 }
wp_enqueue_style('fileinputMin');



wp_enqueue_script('buffer');
wp_enqueue_script('filetype');
wp_enqueue_script('piexif');
wp_enqueue_script('sortable');
wp_enqueue_script('bootstrap5');
wp_enqueue_script('fileinput');



 ?>



<?php 
$user_id = get_current_user_id();

$user = new BP_Core_User( $user_id );
$user_avatar = $user->avatar;

 $files = $_FILES['input-b7'];
    if(!empty($files)  && $_FILES['input-b7']['name']!=""){
?>
<div  class="alert alert-success" id="updateproInfo2" role="alert">Profile Image Uploaded Successfully.</div>
<?php } ?> 	


<form method="post" action="" enctype="multipart/form-data" id="myform">

<div class="mb-3">
  <label for="formFile" class="form-label">Profile Image</label>
  <input class="form-control" type="file" id="formFile" name="input-b7">
  <input type="hidden" name="updateavatar" value="yes">
</div>

<button type="submit"  class="btn btn-color-1">Update</button>
</form>
<!-- <div class="card">
<div class="card-body">

<div class="col-xxl-4">
<div class="bg-secondary-soft px-4 py-5 rounded">
<div class="row">


</div></div>
</div>

</div> 
</div> -->
<?php } ?>  