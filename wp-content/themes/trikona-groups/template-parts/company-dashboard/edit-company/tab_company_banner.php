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
    }
 groups_update_groupmeta( $group_id, 'bp_group_banner_img' ,$attach_ids );
       
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
<div  class="alert alert-success"  role="alert">Banner Images Uploaded Successfully.</div>
<?php } 

$bp_group_banner_img  = groups_get_groupmeta( $group_id, 'bp_group_banner_img' ,true );
$bp_group_banner = wp_get_attachment_image_src( $bp_group_banner_img[0] , 'full');

 ?>

<br/>
 
<?php  echo get_template_part( 'template-parts/usersprofiles/company_profile_menu' ); 
  $current_user = wp_get_current_user();
$term_obj_list = get_the_terms($current_user->ID, 'bp_member_type' );
$term_list = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'slugs' ) );

    $termArr = array();
        if(!empty($term_obj_list)){
       foreach ($term_obj_list as $key => $term_obj_value) {
          $termArr[$term_obj_value->name]= $term_obj_value->name;
            }
          
            unset($termArr['student']);
            unset($termArr['professional']);
             //$memberType = implode(', ', $termArr);
          } 
   ?>
<br/>
<?php if($bp_group_banner[0]!=""){ 
    $disable= 'disabled';
    $alimg = "Please remove banner and upload again";
  }else{
  	$disable= '';
  	$alimg = "";
  } 
  ?>	
<div class="row">
<form class="form"  name="upload_group_banner" method="POST" enctype="multipart/form-data">
<input name="group_banner_id" type="hidden" value="<?=  $group_id; ?>">
<div class="file-loading">
  <input   <?=  $disable;?> id="input-b7" name="input-b7[]"  type="file" class="file" data-allowed-file-extensions='["jpeg", "png","jpg","webp"]'>
 </div>	
 <small><?= $alimg;?></small>
</form>
</div>

<hr/>
<?php if($bp_group_banner[0]!=""){ ?>
<div class="row" style="margin-top:7rem;">
 <div class="col-sm-6">
<div class="card">
<img class="card-img-top" src="<?= $bp_group_banner[0];?>" alt="Compay banner Image">
<div class="card-body">
<a href="javascript:void(0)" attach_ids="<?= $bp_group_banner_img[0];?>"  class="btn btn-primary removeBanner">Remove</a>
</div>
</div>
</div>
</div>
<!-- Card -->
<?php } ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/buffer.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/filetype.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/piexif.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/sortable.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>
<script>
    var banner_text = 'Drag & drop files here … <br>Recommended Size: 1920px x 1080px';
    setTimeout(function() {
        jQuery('.file-drop-zone-title').html(banner_text);
    }, 500);
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
        success: function(data) {
            window.location.reload();
        }
    }); 

 });

</script>
<?php }else{ 

	echo do_shortcode('[elementor-template id="33289"]');
} 
//}
?>