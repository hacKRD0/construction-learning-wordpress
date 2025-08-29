<?php if($_GET['updateavatar']=='true'){
$user_id = get_current_user_id();
$user = new BP_Core_User( $user_id );
$user_avatar = $user->avatar;
   //print_r($_FILES);
    if ( isset( $_FILES['input-b7']['name'] ) ) {


            $file_tmp= $_FILES['input-b7']['tmp_name']; 
            $data = file_get_contents($file_tmp);
            $imgdata = 'data:image/' . $type . ';base64,' . base64_encode($data);         
           $f = finfo_open();
           $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
           $type_file = explode('/', $mime_type);
           $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);          
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
   	move_uploaded_file($_FILES['input-b7']['tmp_name'], $upload_path.'/'.$avatar_thumb);
   
} else {
  
}
   $filename = $myDirUrl.'/'.basename( $avatar_thumb );
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

 ?>


<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" crossorigin="anonymous">-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/buffer.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/filetype.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/piexif.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/sortable.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>

<style> 
.file-caption.icon-visible .file-caption-name {
	display: none !important;
}
</style>
<?php 
 $files = $_FILES['input-b7'];
    if(!empty($files)){
?>
<div  class="alert alert-success" id="updateproInfo2" role="alert">Profile Image Uploaded Successfully.</div>
<?php } ?>
<!-- Upload profile -->
<div class="col-xxl-4">
    <div class="bg-secondary-soft px-4 py-5 rounded">
        <div class="row g-3">
            <h4 class="mb-4 mt-0 text-center">Upload your profile photo</h4>
            <div class="text-center">
                <!-- Image upload -->
                <div class="square position-relative display-2 mb-3 about-avatar">
                    <?= $user_avatar ?>
                </div>

                <form method="post" action="" enctype="multipart/form-data" id="myform">
                    <label class="custom-file-upload">
                        <input id="imgInp" name="file" type="file" />
                        Change Image
                    </label>
                    <input type="button" class="button" value="Update" id="but_upload">
                </form>

                <!-- Content -->
                <p class="text-muted mt-3 mb-0"><span class="me-1">Note:</span>Minimum size 300px x 300px</p>
            </div>
        </div>
    </div>
</div>
<?php } ?>  