<?php
$url_prefix = '?';
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $current_user = get_user_by( 'ID', base64_decode($_GET['id']) );
    if (empty($current_user)){
        $current_user = wp_get_current_user();
    } else {
        $url_prefix .= 'id='.$_GET['id']."&";
    }
} else {
    $current_user = wp_get_current_user();
}
$term_list = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'slugs' ) );
 global $wpdb, $trikona_obj;
 global $product;
$membershipOBJ = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."pmpro_memberships_users WHERE user_id = $current_user->ID AND status='active'" ) );
$userRolesChk = ( array ) $current_user->roles;
if($_GET['tabs']=="cv" && $_GET['career']=='true'){

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
if($_GET['tabs']=="cv"){
  $activeMenu1 = "btn-primary";
}else{
	$activeMenu1 = "btn-default";
}

if($_GET['tabs']=="jobApp"){
$activeMenu2 = "btn-primary";	
}else{
	$activeMenu2 = "btn-default";
}

if($_GET['tabs']=="createCV"){
$activeMenu3 = "btn-primary";	
}else{
	$activeMenu3 = "btn-default";
}

 ?>
 <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">

 	 <div class="btn-group" role="group">
            <a type="button" id="stars" class="btn <?php echo $activeMenu3;?>" href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>career=true&tabs=ChooseCv"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                <div class="hidden-xs">Create Resume</div>
            </a>
        </div>

        <div class="btn-group" role="group">
            <a type="button" id="stars" class="btn <?php echo $activeMenu1;?>" href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>career=true&tabs=cv"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                <div class="hidden-xs">Upload Resume</div>
            </a>
        </div>
        

       

        <div class="btn-group" role="group">
            <a target="_blank" type="button" id="favorites" class="btn <?php echo $activeMenu2;?>" href="<?php echo home_url()?>/jobs/" ><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                <div class="hidden-xs">Job Applications </div>
            </a>
        </div>
       
       </div>

    <br/><br/><br/><br/>   


 <?php
   if($membershipOBJ->membership_id == $trikona_obj->student_rookie_mem_id || $membershipOBJ->membership_id == $trikona_obj->student_champ_mem_id || $membershipOBJ->membership_id == $trikona_obj->student_pro_mem_id || $membershipOBJ->membership_id == $trikona_obj->professional_silver_mem_id || $membershipOBJ->membership_id == $trikona_obj->professional_gold_mem_id || $membershipOBJ->membership_id == $trikona_obj->professional_platinum_mem_id){
  ?>  



<div class="parent-div">
<h6 class="cs-label">Please Choose And Upload Resume.</h6>
 <?php $member_resume_stype = get_user_meta( $current_user->ID, 'member_resume_stype', true );

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
$member_uploaded_resume = get_user_meta( $current_user->ID, 'member_uploaded_resume', true );
$uploaded_resume = wp_get_attachment_url( $member_uploaded_resume[0] );

?>

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
        <input id="input-b7" name="input-b7[]"  type="file" class="file">
    </div>

   <div class="upload_cv_btn">
      <button type="submit" class="btn  btn-lg btn-info" style="">Upload</button>
    </div> 
</form>
 </div>
  </div>
</div>
</div>
 <br/>
<br/>
<?php }else{ ?>
 <div class="alert alert-warning" role="alert">
  By upgrading your membership, you'll unlock a host of exclusive features.
</div>
<?php } ?>
 <?php if($uploaded_resume!=""){ ?>
 	<div class="row">
 <div class="card">
  <div class="card-body">
   <a class="btn" target="_blank" href="<?php echo $uploaded_resume;?>"><i class="fa fa-download"></i> Download</a>

  </div>
</div>
</div>
<?php } } ?>

<?php 
if($_GET['tabs']=="ChooseCv" && $_GET['career']=='true'){ 


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
if($_GET['tabs']=="cv"){
  $activeMenu1 = "btn-primary";
}else{
	$activeMenu1 = "btn-default";
}

if($_GET['tabs']=="jobApp"){
$activeMenu2 = "btn-primary";	
}else{
	$activeMenu2 = "btn-default";
}

if($_GET['tabs']=="ChooseCv"){
$activeMenu3 = "btn-primary";	
}else{
	$activeMenu3 = "btn-default";
}


$args = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'posts_per_page'        => '12',
    'orderby'               => 'ID',
    'order'                 => 'ASC',
    'tax_query'             => array(
        array(
            'taxonomy'      => 'product_cat',
            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
            'terms'         => $trikona_obj->resume_category_id,
            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
        ),
       
    )
);
$products = new WP_Query($args);
// var_dump($products->have_posts());


$orderArg = array(
    'customer_id' => get_current_user_id(),
    'limit' => 4,
    'orderby' => 'date',
        'order' => 'DESC',
    );
$orders = wc_get_orders($orderArg);

 ?>

 <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
 	 <div class="btn-group" role="group">
            <a type="button" id="stars" class="btn <?php echo $activeMenu3;?>" href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>career=true&tabs=ChooseCv"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                <div class="hidden-xs">Create Resume</div>
            </a>
        </div>

        <div class="btn-group" role="group">
            <a type="button" id="stars" class="btn <?php echo $activeMenu1;?>" href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>career=true&tabs=cv"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                <div class="hidden-xs">Upload Resume</div>
            </a>
        </div>
        

       

        <div class="btn-group" role="group">
            <a type="button" id="favorites" class="btn <?php echo $activeMenu2;?>" href="<?php echo home_url()?>/user-dashboard/<?= $url_prefix ?>career=true&tabs=jobApp" ><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                <div class="hidden-xs">Job Applications </div>
            </a>
        </div>
       
       </div>

      <div class="notice notice-warning">
        <strong>Notice:</strong> On choose resume page, you can simply choose one or more templates that you like and the CV will be generated for you to downlooad. You can upload the resume in upload resume page. Access to upload resume will be based on your membership level".
 "On upload resume page, you can upload any CV in pdf. You can buy a CV template from the choose resume page or upload a fresh CV of your own. This CV will be available on your profile for Companies to download
    </div>  
<br/> <br/> <br/>


  <div class="parent-div">



  	<h6 class="cs-label">Please choose resume style and generate/ download resume.</h6>
  	    	<!-- <form name="chooseResume" id="chooseResume" method="POST"> -->

    <div class="row">
    	<?php while ( $products->have_posts() ) : $products->the_post(); ?>
    		<?php   $src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail', false );
    		$product = get_product(get_the_ID());


    		if($orders){
			   $product_id= array();
			    foreach ($orders as  $orderData) {
			    	$items = $orderData->get_items();
			    	    foreach ( $items as $item_id => $item_data ) {
                            $product_id[]=  $item_data["product_id"];
			    	    }
			         }
			     }

			   if(!empty( $product_id))  {
               if (in_array(get_the_ID(),  $product_id)){
                      $radioCheked =   'checked="checked"';
               }else{
               	$radioCheked =   "";
               }
           }

    		 ?>
        <div class='col-md-4 text-center'>
            <div class="card h-100">
                <input <?php echo  $radioCheked ;?> type="radio" name="chooseResumes" id="img<?php echo get_the_ID();?>" class="d-none imgbgchk" value="<?php echo get_the_ID();?>" <?php if($member_resume_stype=='1'){ echo "checked=checked";}  ?>>
                <label for="img<?php echo get_the_ID();?>" class="pt-2">
                    <img src="<?php echo $src[0];?>" alt="Image 1">
                    <div class="tick_container">
                      <div class="tick"><i class="fa fa-check"></i></div>
                    </div>
                </label>
                <?php  if(!empty( $product_id)){
                    if (in_array(get_the_ID(),  $product_id)){ 
                   	    $cl = "hideCart";
                        $_cv_layouts_url=	get_post_meta(get_the_ID(), '_cv_layouts_url', true);
                        $_template_type = get_post_meta(get_the_ID(), 'template_type', true);

                        $default_profile = get_user_meta( $current_user->ID, 'default_profile', true );
                        $product_bought = wc_customer_bought_product( '', $current_user->ID, get_the_ID() );
                    ?>
                    
                    <a class="btn" target="_blank" href="<?php echo home_url()."/download-cv/".get_the_ID()."/".$_template_type."/".get_current_user_id(); ?>">Download</a>
                        <?php if(!empty($default_profile) && $default_profile == get_the_ID()){ ?>
                            <div class="btn d-flex" style="padding-left: 3rem;">
                                <div class="tick"><i class="fa fa-check"></i></div>
                                <div class="p-2">Default Profile</div>
                                <div class="tick"><i class="fa fa-check"></i></div>
                            </div>
                        <?php } else if($product_bought){ ?>
                            <form class="form" name="setDefaultProfile" method="POST">
                                <input type="hidden" name="action" value="set_default_profile">
                                <input type="hidden" name="current_user_id" value="<?= $current_user->ID ?>">
                                <input type="hidden" name="default_profile_id" value="<?= get_the_ID() ?>">
                                <button type="submit" class="btn">Set As Default Profile</button>
                            </form>
                        <?php } ?>
                <?php   }else{
              	     $cl = "";
                } 
                   }
                ?>

                <?php if(empty($cl)){ ?>
                    <a class="btn  <?php echo $cl;?>" href="<?= wc_get_cart_url(). $product->add_to_cart_url() ;?>">Add to cart</a>
                <?php } ?>
            </div>
        </div>
    <?php endwhile; ?>
       


      </div>
      
    <!-- </form> -->
    </div>
    <br/> <br/> <br/>
    <br/> <br/> <br/>
<style>  
.hideCart{
 display:none !important;
}
</style>
<?php } ?>



<?php 
if($_GET['tabs']=="jobApp" && $_GET['career']=='true'){ 


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
if($_GET['tabs']=="cv"){
  $activeMenu1 = "btn-primary";
}else{
	$activeMenu1 = "btn-default";
}

if($_GET['tabs']=="jobApp"){
$activeMenu2 = "btn-primary";	
}else{
	$activeMenu2 = "btn-default";
}

if($_GET['tabs']=="ChooseCv"){
$activeMenu3 = "btn-primary";	
}else{
	$activeMenu3 = "btn-default";
}

 ?>
<?php if (in_array('student-basic', $term_list, true ) || in_array('student-champion', $term_list, true )  || in_array('student-pro', $term_list, true ) || in_array('administrator', $userRolesChk, true )  || in_array('prof-gold', $term_list, true ) || in_array('prof-platinum', $term_list, true ) || in_array('prof-silver', $term_list, true )) {
    echo do_shortcode('[past_applications]'); 
 }
?>


<?php } ?>

