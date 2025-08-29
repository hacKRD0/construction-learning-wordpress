<?php if($_GET['collegeData']=="true" && $_GET['tab']=='college-logo' || (isset($_GET['groupId']) && $_GET['tab']=='college-logo')){
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

  if($_POST['group_banner_id']!=""){
    if (!function_exists('wp_generate_attachment_metadata')) {
      require_once(ABSPATH . "wp-admin" . '/includes/image.php');
      require_once(ABSPATH . "wp-admin" . '/includes/file.php');
      require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    }

    $files = $_FILES['input-b7'];
    if(!empty($files)){
      $errors = $obj->ValidateFiles($files);

      if (empty($errors)) {
        $attach_ids = array();
        $file = array(
          'name'     => $files['name'],
          'type'     => $files['type'],
          'tmp_name' => $files['tmp_name'],
          'error'    => $files['error'],
          'size'     => $files['size']
        );
        $_FILES = array("upload_attachment" => $file);

        foreach ($_FILES as $file => $array) {
          $attach_id = media_handle_upload($file,$post_id);
          array_push($attach_ids , $attach_id);
        }

        if (!empty($attach_ids))
          groups_update_groupmeta( $group_id, 'bp_group_banner_img' ,$attach_ids );    
      }
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
$bp_group_banner_img  = groups_get_groupmeta( $group_id, 'bp_group_banner_img' ,true );
$bp_group_banner = wp_get_attachment_image_src( $bp_group_banner_img[0] , 'full');

 ?>

<br/>
 <br/>
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
  <input   <?=  $disable;?> id="input-b7" name="input-b7"  type="file" class="file" data-allowed-file-extensions='["jpeg", "png","jpg","webp"]'>
 </div>	
 <small><?= $alimg;?></small>
</form>
</div>

<hr/>
<?php if($bp_group_banner!=""){ ?>
<div class="row" style="margin-top:7rem;">
 <div class="col-sm-6">
<div class="card">
<img class="card-img-top" src="<?= $bp_group_banner[0];?>" alt="Compay logo Image">
<div class="card-body">
<a href="javascript:void(0)" attach_ids="<?= $bp_group_banner_img[0];?>"  class="btn btn-primary removeBanner">Remove</a>
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
   }, 700);

  jQuery('body').on('click', '.removeBanner', function(e) {
    var attach_ids=  jQuery(this).attr('attach_ids');
     jQuery.ajax({
           type: "POST",
           url : '<?= admin_url( 'admin-ajax.php' );?>',
           data : {
            action: "remove_group_banner",
            group_id : '<?= $group_id?>',
            attach_ids : attach_ids,
           },
         success: function(data)
         {

          window.location.reload();
         }
     }); 

  });
 </script>
<?php } ?>