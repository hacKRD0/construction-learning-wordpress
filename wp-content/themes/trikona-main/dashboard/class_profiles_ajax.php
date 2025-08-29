<?php
class TrikonaUserProfilesAjax {

	public function __construct() {

		add_action( 'wp_ajax_update_members_profile_image', array($this,'update_members_profile_image'));
		add_action( 'wp_ajax_nopriv_update_members_profile_image', array($this,'update_members_profile_image'));       


		add_action( 'wp_ajax_update_members_profile',  array($this,'update_members_profile' ));
		add_action( 'wp_ajax_nopriv_update_members_profile',  array($this,'update_members_profile' ));


		//Update user profile fileds 

		add_action( 'wp_ajax_update_billing_address',  array($this,'update_billing_address' ));
		add_action( 'wp_ajax_nopriv_update_billing_address',  array($this,'update_billing_address' ));


		//mamange user exprance data

		add_action( 'wp_ajax_edit_user_exprances', array($this,'edit_user_exprances'));
		add_action( 'wp_ajax_nopriv_edit_user_exprances', array($this,'edit_user_exprances'));


		add_action( 'wp_ajax_edit_user_education', array($this,'edit_user_education'));
		add_action( 'wp_ajax_nopriv_edit_user_education', array($this,'edit_user_education'));

		add_action( 'wp_ajax_delete_exprances', array($this,'delete_exprances'));
        add_action( 'wp_ajax_nopriv_delete_exprances', array($this,'delete_exprances'));


		add_action( 'wp_ajax_delete_education', array($this,'delete_education'));
		add_action( 'wp_ajax_nopriv_delete_education', array($this,'delete_education'));
		//update password

		add_action('wp_ajax_cvf_ngp_change_password',  array($this,'cvf_ngp_change_password'));
		add_action('wp_ajax_nopriv_cvf_ngp_change_password',  array($this,'cvf_ngp_change_password'));


		//resume style section member profile
		     add_action( 'wp_ajax_choose_resume_style', array($this,'choose_resume_style' ));
		     add_action( 'wp_ajax_nopriv_choose_resume_style', array($this,'choose_resume_style' ));


			add_action( 'wp_ajax_bb_members_directory_filter', array($this,'bb_members_directory_filter1' ));
	        add_action( 'wp_ajax_nopriv_bb_members_directory_filter', array($this,'bb_members_directory_filter1' ));


	       add_action('wp_ajax_loadMore', array($this,'loadMore'));
	       add_action('wp_ajax_nopriv_loadMore', array($this,'loadMore'));


		  add_action( 'wp_ajax_search_members_directory_filter', array($this,'search_members_directory_filter' ));
		  add_action( 'wp_ajax_nopriv_search_members_directory_filter', array($this,'search_members_directory_filter' ));



		  add_action( 'wp_ajax_courses_members_directory_filter', array($this,'courses_members_directory_filter' ));
		  add_action( 'wp_ajax_nopriv_courses_members_directory_filter', array($this,'courses_members_directory_filter' ));

		  add_action( 'wp_ajax_show_filters_fields', array($this,'show_filters_fields' ));
		  add_action( 'wp_ajax_nopriv_show_filters_fields', array($this,'show_filters_fields' ));
          

          add_action( 'wp_ajax_serach_xprofile_fileds', array($this,'serach_xprofile_fileds' ));
		  add_action( 'wp_ajax_nopriv_serach_xprofile_fileds', array($this,'serach_xprofile_fileds' ));

        // set default profile for user
	    add_action( 'wp_ajax_set_default_profile',  array($this,'set_default_profile' ));
        add_action( 'wp_ajax_nopriv_set_default_profile',  array($this,'set_default_profile' ));
	}	


/**
* Callback function for the show more search profil fileds value @student and profsnl both 
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

public function serach_xprofile_fileds(){

global $wpdb;
$fileds_id = $_POST['fieldId'];
$inputVal = $_POST['inputVal'];
$name = $_POST['name'];
$fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."bp_xprofile_fields WHERE parent_id='".$fileds_id."' AND `name` LIKE '%".$inputVal."%'");

if ($wpdb->num_rows>0) {

foreach ($fields as $key => $field) { ?>
	<div class="vibebp_members_directory_filter_values">
                    <div class="checkbox <?php echo $checkboxCls;?>  ">
	<input name="<?php echo $name;?>[<?php echo $field->name;?>][]" class="klas" type="checkbox" id="<?php echo $name;?>_<?php echo $field->name;?>" value="<?php echo $name;?>|<?php echo $field->name;?>">
  <label for="<?php echo $name;?>_<?php echo $field->name;?>"><?php echo $field->name;?></label>
</div>
</div>
<?php }
}else{
	'No Recod Found';
}
die;
}

/**
* Callback function for the show more college filters checkboxes used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

public function show_filters_fields(){
global $wpdb;
$group_type = $_POST['group_type'];
$inputVal = $_POST['inputVal'];

$member_type_objects = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."bp_groups where `name` LIKE '%".$inputVal."%'");

if ($wpdb->num_rows>0) {
     foreach($member_type_objects as $member_type=>$mt){     
            $bp_group_type = wp_get_post_terms(  $mt->id, 'bp_group_type', array( 'fields' => 'names' ) );
           // print_r($bp_group_type);
            if (in_array($group_type, $bp_group_type, true ) ) {
           ?>
           <div class="vibebp_members_directory_filter_values">
            <div class="checkbox">
              <input class="klas" type="checkbox" id="<?php echo $mt->name;?>" value="bp_group_id|<?php echo $mt->id;?>">
              <label for="<?php echo $mt->name;?>"><?php echo $mt->name;?></label>
            </div>
          </div>

      <?php } }

        } else{

      	echo "No record Found";
      } 

die;
}


/**
* Callback function for the members_register used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

public function update_members_profile_image(){
   $user_id = get_current_user_id();
   //print_r($_FILES);
    if ( isset( $_FILES['file']['name'] ) ) {
         //$base64 = $_FILES['file']['name'];
            $file_tmp= $_FILES['file']['tmp_name']; 
            $data = file_get_contents($file_tmp);
            $imgdata = 'data:image/' . $type . ';base64,' . base64_encode($data);
         
           $f = finfo_open();
          $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
          $type_file = explode('/', $mime_type);
          //print_r($type_file);die;
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


   if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path.'/'.$avatar_full)) {
   	move_uploaded_file($_FILES['file']['tmp_name'], $upload_path.'/'.$avatar_thumb);
    echo "success";
} else {
    echo "fail";
}
   switch ($type_file[1]) {
     case 'jpeg':
       # code...
       $img_full = $this->resize_imagejpg($upload_path.'/'.$avatar_full, 150, 150);
       $img_thumb = $this->resize_imagejpg($upload_path.'/'.$avatar_thumb, 50, 50);
       break;
     case 'jpg':
       # code...
       $img_full = $this->resize_imagejpg($upload_path.'/'.$avatar_full, 150, 150);
       $img_thumb = $this->resize_imagejpg($upload_path.'/'.$avatar_thumb, 50, 50);
       break;
     case 'png':
       # code...
       $img_full = $this->resize_imagepng($upload_path.'/'.$avatar_full, 150, 150);
       $img_thumb = $this->resize_imagepng($upload_path.'/'.$avatar_thumb, 50, 50);
       break;
     case 'gif':
       # code...
       $img_full = $this->resize_imagegif($upload_path.'/'.$avatar_full, 150, 150);
       $img_thumb = $this->resize_imagegif($upload_path.'/'.$avatar_thumb, 50, 50);
       break;
     default:
        return array(
               "success" => false,
               "avatar_url" => get_avatar_url($user_id));
        break;
   }
   // Override previous image
   imagejpeg($img_thumb, $$upload_path.'/'.$avatar_full, 100);
   imagejpeg($img_full, $upload_path.'/'.$avatar_thumb);

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

	die;
}


/**
* Callback function for the user profiles fields  used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/


public function update_members_profile(){
    global $trikona_obj;
	parse_str($_POST['data'], $searcharray);
$current_user = get_user_by( 'ID', $searcharray['current_user_id']);
$user_data = wp_update_user( array(
    'ID' => $current_user->ID, 
	'first_name' => $searcharray['firtName'],
	'last_name' => $searcharray['lastName'],	
	 ) );
 
if($searcharray['phoneNo']!=""){
   xprofile_set_field_data( $trikona_obj->phone_no_field_id, $current_user->ID, $searcharray['phoneNo'], $is_required = false );
}
if($searcharray['mobileNo']!=""){
   xprofile_set_field_data( $trikona_obj->mobile_no_field_id, $current_user->ID, $searcharray['mobileNo'], $is_required = false );
}
if($searcharray['address']!=""){
   xprofile_set_field_data( $trikona_obj->address_field_id, $current_user->ID, $searcharray['address'], $is_required = false );
}

if($searcharray['Total_Expereince']!=""){
   xprofile_set_field_data( $trikona_obj->total_expereince_field_id, $current_user->ID, $searcharray['Total_Expereince'], $is_required = false );
    update_user_meta( $current_user->ID, 'Total Expereince', $searcharray['Total_Expereince'] );
}

if($searcharray['Highest_Education']!=""){
   xprofile_set_field_data( $trikona_obj->highest_education_field_id, $current_user->ID, $searcharray['Highest_Education'], $is_required = false );
    update_user_meta( $current_user->ID, 'Highest Education', $searcharray['Highest_Education'] );
}

if($searcharray['Year_Of_Passout']!=""){
   xprofile_set_field_data( $trikona_obj->year_of_passout_field_id, $current_user->ID, $searcharray['Year_Of_Passout'], $is_required = false );
    update_user_meta( $current_user->ID, 'Year Of Passout', $searcharray['Year_Of_Passout'] );
}

if($searcharray['Total_year_of_study']!=""){
   xprofile_set_field_data( $trikona_obj->total_year_of_study_field_id, $current_user->ID, $searcharray['Total_year_of_study'], $is_required = false );
    update_user_meta( $current_user->ID, 'Total year of study', $searcharray['Total_year_of_study'] );
}

if($searcharray['Current_Year_Of_Study']!=""){
	
   xprofile_set_field_data( $trikona_obj->current_Year_Of_study_field_id, $current_user->ID, $searcharray['Current_Year_Of_Study'], $is_required = false );
    update_user_meta( $current_user->ID, 'Current Year Of Study', $searcharray['Current_Year_Of_Study'] );
}
if($searcharray['Graduation']!=""){
	
    update_user_meta( $current_user->ID, 'member_graduation', $searcharray['Graduation'] );
}

if(!empty($searcharray['Skills']['skill'] )){
           delete_user_meta($current_user->ID, 'Professional Skills');
		}
///if(!empty($searcharray['Skills'])){
	//print_r($searcharray['Skills']['skill']); die;
   //xprofile_set_field_data( 83, $current_user->ID, $searcharray['Skills'], $is_required = false );
	foreach ($searcharray['Skills']['skill'] as $key => $skillVal) {
		add_user_meta( $current_user->ID, 'Professional Skills', $skillVal );
	}
	
    update_user_meta( $current_user->ID, 'Skills', $searcharray['Skills'] );
//}
xprofile_set_field_data( $trikona_obj->bio_field_id, $current_user->ID, $searcharray['bio'], $is_required = false );
 update_user_meta( $current_user->ID, 'member_bio', $searcharray['bio'] );
 update_user_meta( $current_user->ID, 'memberDob', $searcharray['memberDob'] );
 update_user_meta( $current_user->ID, 'designation_current', $searcharray['designation_current'] );
 update_user_meta( $current_user->ID, 'gender', $searcharray['gender'] );
update_user_meta( $current_user->ID, 'company_current', $searcharray['company_current'] );
 update_user_meta( $current_user->ID, 'linkedinProfile', $searcharray['linkedinProfile'] );
 update_user_meta( $current_user->ID, 'skill_set', $searcharray['skillSet'] );
  update_user_meta( $current_user->ID, 'Vertical', $searcharray['skillSet'] );
   update_user_meta( $current_user->ID, 'Institute', $searcharray['Institute'] );

  if($searcharray['skillSet']!=""){
      delete_user_meta($user_id, 'Vertical');		
	}	
add_user_meta( $current_user->ID, 'Vertical', $searcharray['skillSet'] );
die;
}

/**
* Callback function for the user update_billing_address fields  used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

public function update_billing_address(){
	parse_str($_POST['data'], $searcharray);
$current_user = wp_get_current_user();

 update_user_meta( $current_user->ID, 'billing_first_name', $searcharray['billing_first_name'] );
 update_user_meta( $current_user->ID, 'billing_last_name', $searcharray['billing_last_name'] );
 update_user_meta( $current_user->ID, 'billing_company', $searcharray['billing_company'] );
 update_user_meta( $current_user->ID, 'billing_country', $searcharray['billing_country'] );
  update_user_meta( $current_user->ID, 'billing_address_1', $searcharray['billing_address_1'] );
 update_user_meta( $current_user->ID, 'billing_address_2', $searcharray['billing_address_2'] );
 update_user_meta( $current_user->ID, 'billing_city', $searcharray['billing_city'] );
 update_user_meta( $current_user->ID, 'billing_postcode', $searcharray['billing_postcode'] );
 update_user_meta( $current_user->ID, 'billing_phone', $searcharray['billing_phone'] );
 update_user_meta( $current_user->ID, 'billing_email', $searcharray['billing_email'] );
  update_user_meta( $current_user->ID, 'billing_state', $searcharray['billing_state'] );
die;
}

/**
* Callback function for the user set_default_profile fields  used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

public function set_default_profile(){
    parse_str($_POST['data'], $searcharray);

    update_user_meta( $searcharray['current_user_id'], 'default_profile', $searcharray['default_profile_id'] );
    die;
}


/**
* Callback function for the user user exprances fields  used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/


public function edit_user_exprances(){
	
global $wpdb;
if($_POST['id']=='insertRoew'){
    if($_POST['formdate']!=""){
        $current_user_id =  base64_decode($_POST['current_user_id']);
        $wpdb->insert($wpdb->prefix.'user_expriances', array(
            'fromdate' => $_POST['formdate'],
            'todate' => $_POST['todate'],
            'organization' => $_POST['email'], 
            'position' => $_POST['position'],
            'experience' => $_POST['experience'],
            'responsibility' => $_POST['responsibility'],
            'user_id' => $current_user_id,
        ));
        echo json_encode(array('status'=>2, 'fromdate'=>$_POST['formdate'],'todate' => $_POST['todate'],'organization' => $_POST['email'],'position' =>$_POST['position'],'experience' => $_POST['experience'],'responsibility' => $_POST['responsibility']));
    }
}else{
      $wpdb->update($wpdb->prefix.'user_expriances', array(
            'fromdate' =>$_POST['formdate'],
            'todate' => $_POST['todate'],
            'organization' => $_POST['email'],
            'position' =>$_POST['position'],
            'experience' => $_POST['experience'],
             'responsibility' => $_POST['responsibility']
           ),
            array('id'=>$_POST['id'])
     );	
      echo json_encode(array('status'=>1, 'fromdate'=>$_POST['formdate'],'todate' => $_POST['todate'],'organization' => $_POST['email'],'position' =>$_POST['position'],'experience' => $_POST['experience'],'responsibility' => $_POST['responsibility']));
}
die;
}


/**
* Callback function for the user delete_exprances  used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/


public function delete_exprances(){
	
global $wpdb;
$table = $wpdb->prefix.'user_expriances';
 $wpdb->delete( $table, array( 'id' => $_POST['id'] ) );
 echo json_encode(array('status'=>1, 'message'=>__('Account create Successfully!.')));
die;
}


/**
* Callback function for the user user exprances fields  used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/


public function edit_user_education(){
	
global $wpdb;
if($_POST['id']=='insertRoew'){
    if($_POST['institute_name']!=""){
        $current_user_id =  base64_decode($_POST['current_user_id']);
        $wpdb->insert($wpdb->prefix.'user_educations', array(
            'institute_name' => $_POST['institute_name'],
            'qualification' => $_POST['qualification'],
            'courseofStudy' => $_POST['courseofStudy'], 
            'duration' => $_POST['duration'],
            'startDate' => $_POST['startDate'],
            'year_passout' => $_POST['year_passout'],
            'description' => $_POST['description'],
            'user_id' => $current_user_id,
        ));
         echo json_encode(array('status'=>2, 'institute_name'=>$_POST['institute_name'],'qualification' => $_POST['qualification'],'courseofStudy' => $_POST['courseofStudy'],'duration' =>$_POST['duration'],'startDate' => $_POST['startDate'],'year_passout' => $_POST['year_passout'],'description' => $_POST['description']));
    }
}else{
      $wpdb->update($wpdb->prefix.'user_educations', array(
                'institute_name' =>$_POST['institute_name'],
                'qualification' => $_POST['qualification'],
                'courseofStudy' => $_POST['courseofStudy'],
                'duration' =>$_POST['duration'],
                'startDate' => $_POST['startDate'],
                'year_passout' => $_POST['year_passout'],
                'description' => $_POST['description'],
           ), array('id'=>$_POST['id'])
     );	
      echo json_encode(array('status'=>2, 'institute_name'=>$_POST['institute_name'],'qualification' => $_POST['qualification'],'courseofStudy' => $_POST['courseofStudy'],'duration' =>$_POST['duration'],'startDate' => $_POST['startDate'],'year_passout' => $_POST['year_passout'],'description' => $_POST['description']));
}
die;
}


/**
* Callback function for the user delete_education   used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

function delete_education(){
	
global $wpdb;
$table = $wpdb->prefix.'user_educations';
 $wpdb->delete( $table, array( 'id' => $_POST['id'] ) );
 echo json_encode(array('status'=>1, 'message'=>__('Account create Successfully!.')));
die;
}


/**
* Callback function for the user CHNAGE USER PROFILE PASSWORD   used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

public function cvf_ngp_change_password() {
       
    global $current_user;
   
    if(isset($_POST['cvf_action']) && $_POST['cvf_action'] == 'change_password') {
       
        //Sanitize received password
        $password = sanitize_text_field($_POST['new_password']);
       
        // Define arguments that will be passed to the wp_update_user()
        $userdata = array(
            'ID'        =>  $current_user->ID,
            'user_pass' =>  $password // Wordpress automatically applies the wp_hash_password() function to the user_pass field.
        ); 
        $user_id = wp_update_user($userdata);
       
        // wp_update_user() will return the user_id on success and an array of error messages on failure.
        // so bellow we are going to check if the returned string is equal to the current user ID, if yes then we proceed updating the user meta field
        if($user_id == $current_user->ID){
            update_user_meta($current_user->ID, 'ngp_changepass_status', 1);
            echo 'success';
           
        } else {
            echo 'error';
        }  
    }
    // Always exit to avoid further execution
    exit();
}


/**
* Callback function for the user SELECT RESUME STYLE   used in the form.
*
* Processses the data recieved from the form, and you can do whatever you want with it.
*
* @return    echo   response string about the completion of the ajax call.
*/

public function choose_resume_style(){
	parse_str($_POST['data'], $searcharray);
$current_user = wp_get_current_user();

 update_user_meta( $current_user->ID, 'member_resume_stype', $searcharray['chooseResumes'] );
 
die;
}





public function search_members_directory_filter(){

	global $wpdb,$bp;
	$current_user = wp_get_current_user();
	$memberShipTypeElite = array('student-rookie','student-champion','student-pro','prof-gold','prof-silver','prof-platinum');
	$memberShipTypePrime = array('student-champion','student-pro','prof-gold','prof-platinum');
	$memberShipTypeBasic = array('student-pro','prof-platinum');


 $current_user = wp_get_current_user();
 $currentUserPlan = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'names' ) );

if(!empty($currentUserPlan)){
    $corporateRole = implode(', ', $currentUserPlan);
}

  $postVal =  $_POST['data'];
	
      
      $args = array(
	'meta_query' => array(
		'relation' => 'OR',
			array(
				'key'     => 'Highest Education',
				'value'   => $postVal,
	 			 'compare' => 'LIKE'
			),
			array(
				'key'     => 'Total Expereince',
				'value'   => $postVal,				
				'compare' => '='
			),
			array(
				'key'     => 'Skills',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			),
			array(
				'key'     => 'Year Of Passout',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			),
			array(
				'key'     => 'Total year of study',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			),
			array(
				'key'     => 'Current Year Of Study',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			)
			,
			array(
				'key'     => 'first_name',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			),
			array(
				'key'     => 'designation_current',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			),
			array(
				'key'     => 'gender',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			),
			array(
				'key'     => 'company_current',
				'value'   => $postVal,				
				 'compare' => 'LIKE'
			)
			
			
	 )
 );
$user_query = new WP_User_Query( $args );

$users = $user_query->get_results();
	$usercc  = wp_get_current_user();
$userRolesChk = ( array ) $usercc->roles;

         ?>
                     <div class="vibebp_members_directory card team-boxed" id="member_data">
          <div class="row people">
                <?php 
                
                foreach ($users as $key => $bpMembers) {
                     $new_user = get_userdata(  $bpMembers->ID );
                     $first_name = $new_user->first_name;
                    $last_name = $new_user->last_name;
                    $member_type = bp_get_member_type( $bpMembers->ID );
                     $user = new BP_Core_User( $bpMembers->ID );
                     $user_avatar = $user->avatar;
         	        $user_roles = $new_user->roles;
         	if (in_array($_POST['roles'], $user_roles, true ) ) {
                	$memberships_users = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."pmpro_memberships_users WHERE user_id='".$bpMembers->ID."' AND status='active'");
                   // if($memberships_users->status=='active'){
                  
                     
                           $term_obj_list = wp_get_post_terms( $bpMembers->ID, 'bp_member_type', array( 'fields' => 'names' ) );
                          
                           if(!empty($term_obj_list)){
                                $memberType = implode(', ', $term_obj_list);

                          }
                          
                  if($corporateRole=="corporate-elite"){
                         if (in_array($memberType, $memberShipTypeElite, true ) ) {
                        include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }


                   if($corporateRole=="corporate-prime"){

                         if (in_array($memberType, $memberShipTypePrime, true ) ) {
                       include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php'; 
                        }
                   }

                   if($corporateRole=="corporate-basic"){
                         if (in_array($memberType, $memberShipTypeBasic, true ) ) {
                        include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php'; 
                        }
                   } 

                   
                    // }
              
               
              if (in_array('administrator', $userRolesChk, true ) ) {
                        include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                        }
            }
              ?>
              </div>
      </div>
         <?php 



	 die;
}




public function courses_members_directory_filter(){

  $postVal =  $_POST['data']['klasId'];
  $member_data = explode(',', $postVal);
       	if($member_data[0]!=''){
         foreach ( $member_data as $member_datas ) {
               
               global $wpdb;
                $coursesData  = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."bp_activity WHERE item_id ='".$member_datas."' AND type='course_evaluated'" );
               if($wpdb->num_rows > 0){
                foreach ( $coursesData as $bpMembers ) {
                	$memberships_users = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."pmpro_memberships_users WHERE user_id='".$bpMembers->ID."' AND status='active'");
                    //if($memberships_users->status=='active'){
                	 $user_info = get_userdata( $bpMembers->user_id );
                     $first_name = $user_info->first_name;
                      $last_name = $user_info->last_name;
                      $member_type = bp_get_member_type( $bpMembers->user_id );
                       $user = new BP_Core_User( $bpMembers->user_id );
                       $user_avatar = $user->avatar;

                       

                           $term_obj_list = wp_get_post_terms( $bpMembers->user_id, 'bp_member_type', array( 'fields' => 'names' ) );
                          
                           if(!empty($term_obj_list)){
                                $memberType = implode(', ', $term_obj_list);

                          }

                        if($corporateRole=="corporate-elite"){
                         if (in_array($memberType, $memberShipTypeElite, true ) ) {
                         	echo $memberType;
                       include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }


                   if($corporateRole=="corporate-prime"){

                         if (in_array($memberType, $memberShipTypePrime, true ) ) {
                        include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }

                   if($corporateRole=="corporate-basic"){
                         if (in_array($memberType, $memberShipTypeBasic, true ) ) {
                       include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php'; 
                        }
                   }
                   
                     if (in_array('administrator', $userRolesChk, true ) ) {
                       include WP_PLUGIN_DIR . '/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                       ?>
                    
         <?php 
          //}
         }
     }else{ ?>
<div class="alert alert-warning" role="alert">
  No Memebers Founds..
</div>

   <?php  }

     }
 }else{
        
     	$this->getMembersList();
     }



	 die;
}


public function getMembersList($roles ) { 
global $wpdb, $trikona_obj;
  $no  = 12; 
    //  $Members1 = get_users( [ 'role__in' => [ 'student', 'professional'] ,'number'=>$no] );
  $Members1 = get_users( [ 'role__in' => [$roles] ,'number'=>$no] );
  $memberShipTypeElite = $trikona_obj->elite_membership_types;
$memberShipTypePrime = $trikona_obj->prime_membership_types;
$memberShipTypeBasic = $trikona_obj->basic_membership_types;

$current_user = wp_get_current_user();

 $term_obj_list = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'names' ) );

if(!empty($term_obj_list)){
    $corporateRole = implode(', ', $term_obj_list);

}

if(!empty($Members1)) {
 $usercc  = wp_get_current_user();
 $userRolesChk = ( array ) $usercc->roles;
 ?>
<div class="row people"> 

 <?php
foreach($Members1 as $bpMembers):	
  $id = $bpMembers->ID;	
  	
 $user_info = get_userdata( $id );
 $user_roles = $user_info->roles;
  	
 if (in_array($roles, $user_roles, true ) ) {

 $memberships_users = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."pmpro_memberships_users WHERE user_id='".$bpMembers->ID."' AND status='active'");
  $first_name = $user_info->first_name;
                    $last_name = $user_info->last_name;
                    $member_type = bp_get_member_type( $id );
                    $user = new BP_Core_User( $id );
                    $user_avatar = $user->avatar; 
                   // if($memberships_users->status=='active'){	

                     $term_obj_list = wp_get_post_terms( $bpMembers->ID, 'bp_member_type', array( 'fields' => 'names' ) );

						if(!empty($term_obj_list)){
						    $memberType = implode(', ', $term_obj_list);

						}    
                  if($corporateRole == $trikona_obj->corporate_elite_role ){
                  	if (!in_array('administrator', $userRolesChk, true ) ) {
                         if (in_array($memberType, $memberShipTypeElite, true ) ) {
                      include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }
               }


                   if($corporateRole == $trikona_obj->corporate_prime_role ){
                       if (!in_array('administrator', $userRolesChk, true ) ) {
                         if (in_array($memberType, $memberShipTypePrime, true ) ) {
                       include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }
               }

                   if($corporateRole == $trikona_obj->corporate_basic_role ){
                   	if (!in_array('administrator', $userRolesChk, true ) ) {
                         if (in_array($memberType, $memberShipTypeBasic, true ) ) {
                       include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }
               }

                    
             //}

             if (in_array('administrator', $userRolesChk, true ) ) {
                       include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                    }
        endforeach; 
        ?> </div> <?php
} 
}

}

new TrikonaUserProfilesAjax();
?>