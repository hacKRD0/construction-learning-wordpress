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
		//update password

		add_action('wp_ajax_cvf_ngp_change_password',  array($this,'cvf_ngp_change_password'));
		add_action('wp_ajax_nopriv_cvf_ngp_change_password',  array($this,'cvf_ngp_change_password'));
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

$current_user = wp_get_current_user();
$user_data = wp_update_user( array(
    'ID' => $current_user->ID, 
	'first_name' => $searcharray['firtName'],
	'last_name' => $searcharray['lastName'],	
	 ) );
 
if($searcharray['phoneNo']!=""){
   xprofile_set_field_data( $trikona_obj->phone_no_field_id , $current_user->ID, $searcharray['phoneNo'], $is_required = false );
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
 update_user_meta( $current_user->ID, $trikona_obj->member_bio_meta, $searcharray['bio'] );
 update_user_meta( $current_user->ID, $trikona_obj->memberDob_meta, $searcharray['memberDob'] );
 update_user_meta( $current_user->ID, $trikona_obj->designation_current_meta, $searcharray['designation_current'] );
 update_user_meta( $current_user->ID, $trikona_obj->gender_meta, $searcharray['gender'] );
update_user_meta( $current_user->ID, $trikona_obj->company_current_meta, $searcharray['company_current'] );
 update_user_meta( $current_user->ID, $trikona_obj->linkedin_profile_meta, $searcharray['linkedinProfile'] );
 update_user_meta( $current_user->ID, $trikona_obj->skill_set_meta, $searcharray['skillSet'] );
  update_user_meta( $current_user->ID, $trikona_obj->vertical_meta, $searcharray['skillSet'] );
   update_user_meta( $current_user->ID, $trikona_obj->institute_meta, $searcharray['Institute'] );

  if($searcharray['skillSet']!=""){
      delete_user_meta($user_id, $trikona_obj->vertical_meta);		
	}	
add_user_meta( $current_user->ID, $trikona_obj->vertical_meta, $searcharray['skillSet'] );
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
    global $trikona_obj;
	parse_str($_POST['data'], $searcharray);
$current_user = wp_get_current_user();

 update_user_meta( $current_user->ID, $trikona_obj->billing_first_name_meta, $searcharray['billing_first_name'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_last_name_meta, $searcharray['billing_last_name'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_company_meta, $searcharray['billing_company'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_country_meta, $searcharray['billing_country'] );
  update_user_meta( $current_user->ID, $trikona_obj->billing_address_1_meta, $searcharray['billing_address_1'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_address_2_meta, $searcharray['billing_address_2'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_city_meta, $searcharray['billing_city'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_postcode_meta, $searcharray['billing_postcode'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_phone_meta, $searcharray['billing_phone'] );
 update_user_meta( $current_user->ID, $trikona_obj->billing_email_meta, $searcharray['billing_email'] );
  update_user_meta( $current_user->ID, $trikona_obj->billing_state_meta, $searcharray['billing_state'] );
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
    global $current_user, $trikona_obj;
   
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
            update_user_meta($current_user->ID, $trikona_obj->ngp_changepass_status_meta, 1);
            echo 'success';
           
        } else {
            echo 'error';
        }  
    }
    // Always exit to avoid further execution
    exit();
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

 $memberships_users = $wpdb->get_row("SELECT * FROM wpcw_pmpro_memberships_users WHERE user_id='".$bpMembers->ID."' AND status='active'");
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
                  if($corporateRole==$trikona_obj->corporate_elite_role){
                  	if (!in_array('administrator', $userRolesChk, true ) ) {
                         if (in_array($memberType, $memberShipTypeElite, true ) ) {
                      include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }
               }


                   if($corporateRole==$trikona_obj->corporate_prime_role){
                       if (!in_array('administrator', $userRolesChk, true ) ) {
                         if (in_array($memberType, $memberShipTypePrime, true ) ) {
                       include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }
                   }
               }

                   if($corporateRole==$trikona_obj->corporate_basic_role){
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