<?php
  $obj = new Trikona();

  global $wpdb,$bp;

  switch_to_blog(1);
  $current_user = wp_get_current_user();
  $active_user_group_id = get_query_var( 'active_user_group_id' );
  restore_current_blog();

  $group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";
  if (empty($group_id) && !empty($active_user_group_id)) {
    $group_id = $active_user_group_id;
  }
  
  $errors = [];

  if ( isset( $_FILES['input-b7']['name'] ) ) {
    $errors = $obj->ValidateFiles($_FILES['input-b7']);
    if (empty($errors)) {
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
      $profile_subdir = '/group-avatars/' .$group_id;
      $upload_path=  $uploaddir['path'] = $uploaddir['basedir'] . $profile_subdir;

      if (!file_exists($upload_path))  {
        mkdir($upload_path, 0755, true);
      }
      require_once(ABSPATH . '/wp-load.php');             
      require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
      require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
      // Remove files into user's folder
      $files = list_files($upload_path);
      for($i = 0; $i<count($files); $i++) {
        wp_delete_file($files[$i]);
      }

      if (move_uploaded_file($_FILES['input-b7']['tmp_name'], $upload_path.'/'.$avatar_full)) {
     	  move_uploaded_file($_FILES['input-b7']['tmp_name'], $upload_path.'/'.$avatar_thumb);
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
  	  groups_update_groupmeta($group_id,'group-avatarsImg',$attachment_id);         
    }
  }
?>
<?php if(!empty($errors)){ ?>
    <div class="alert alert-danger">
        <ul>
            <?= '<li>' . implode( '</li><li>', $errors) . '</li>'; ?>
        </ul>
    </div>
<?php } ?>
<?php  if($_POST['group_banner_id']!="" && empty($errors)){ ?>
  <div  class="alert alert-success"  role="alert">Logo Images Uploaded Successfully.</div>
<?php } 

$bp_group_banner_img  = groups_get_groupmeta( $group_id, 'group-avatarsImg' ,true );
$attachment_id = $bp_group_banner_img;
// $bp_group_banner = wp_get_attachment_image_src( $bp_group_banner_img , 'full');
 $group_id = $group_id;
$group_id = intval($group_id);
$bp_group_banner = bp_get_group_avatar_url($group_id);

$url   = wp_nonce_url(
  bp_get_group_manage_url(
    $group_id,
    bp_groups_get_path_chunks( array( 'group-avatar', 'delete' ), 'manage' )
  ),
  'bp_group_avatar_delete'
);

 ?>
<?php if($bp_group_banner!=""){ 
    $disable= 'disabled';
    $alimg = "Please remove banner and upload again";
  }else{
  	$disable= '';
  	$alimg = "";
  } 


  	if($_GET['tab']=="college-banner"){
  $activeMenu1 = "btn-primary";
}else{
	$activeMenu = "btn-default";
}

  ?>

  	
<div class="row">
<form class="form"  name="upload_group_banner" method="POST" enctype="multipart/form-data" style="width: 100%;margin-top: 5rem;">
<input name="group_banner_id" type="hidden" value="<?=  $group_id; ?>">
<div class="file-loading">
  <input    id="input-b7" name="input-b7"  type="file" class="file" data-allowed-file-extensions='["jpeg", "png","jpg","webp"]'>
 </div>	
 <small><?= $alimg;?></small>
</form>
</div>

<hr/>
<?php 

if($bp_group_banner!=""){ ?>
<div class="row" style="margin-top:7rem;">
 <div class="col-sm-6">
<div class="card w-50">
<img class="card-img-top" src="<?= $bp_group_banner;?>" alt="Compay logo Image">
<div class="card-body">
<a href="<?= $url ?>" target="_blank" attach_ids="<?= $attachment_id ?>"  class="btn btn-primary removeCompanyLogo">Remove</a>
</div>
</div>
</div>
</div>
<!-- Card -->
<?php } ?>

<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" crossorigin="anonymous">-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/buffer.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/filetype.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/piexif.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/sortable.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>

 <script type="text/javascript">
   var banner_text = 'Drag & drop files here … <br>Recommended Size: 200px x 200px';
   setTimeout(function() {
      jQuery('.file-drop-zone-title').html(banner_text);
   }, 600);
 </script>
<?php  ?>