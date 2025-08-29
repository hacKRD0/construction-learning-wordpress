<?php  if($_GET['upload-resume']=='true'){
    $member_uploaded_resume = get_user_meta( $current_user->ID, 'member_uploaded_resume', true );
    $uploaded_resume = wp_get_attachment_url( $member_uploaded_resume[0] );
    if($_FILES['input-b7']['name']!=""){
        
        if (!function_exists('wp_generate_attachment_metadata')) {
           require_once(ABSPATH . "wp-admin" . '/includes/image.php');
           require_once(ABSPATH . "wp-admin" . '/includes/file.php');
           require_once(ABSPATH . "wp-admin" . '/includes/media.php');
       }
       $files = $_FILES['input-b7'];
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
               $member_resume_stype = update_user_meta( $current_user->ID, 'member_uploaded_resume', $attach_ids );
           
             }
    
    ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/buffer.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/filetype.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/piexif.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/sortable.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>
<?php 
    $files = $_FILES['input-b7'];
       if(!empty($files)){
    ?>
<div  class="alert alert-success" id="updateproInfo2" role="alert">Resume Uploaded Successfully.</div>
<?php } ?>  
<div class="row">
    <div class="card">
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data" id="uploadResume">
                <div class="file-loading">
                    <input id="input-b7" name="input-b7[]" multiple type="file" class="file" data-allowed-file-extensions='["pdf", "doc"]'>
                </div>
            </form>
        </div>
    </div>
</div>
<br/>
<?php }  ?> 
<?php if($uploaded_resume!=""){ ?>
<div class="row">
    <div class="card">
        <div class="card-body">
            <a class="btn" target="_blank" href="<?php echo $uploaded_resume;?>"><i class="fa fa-download"></i> Download</a>
        </div>
    </div>
</div>
<?php } ?>