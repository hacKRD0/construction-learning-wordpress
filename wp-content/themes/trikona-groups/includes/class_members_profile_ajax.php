<?php
class TrikonaGroupsProfilesAjax {

public function __construct() {
    add_action( 'wp_ajax_fetch_empyolees_data', array($this,'fetch_empyolees_data' ));
    add_action( 'wp_ajax_nopriv_fetch_empyolees_data', array($this,'fetch_empyolees_data' ));     

    add_action( 'wp_ajax_update_emp_profile_dataTable', array($this,'update_emp_profile_dataTable' ));
    add_action( 'wp_ajax_nopriv_update_emp_profile_dataTable', array($this,'update_emp_profile_dataTable' ));     

    add_action( 'wp_ajax_fetch_members_profile', array($this,'fetch_members_profile' ));
    add_action( 'wp_ajax_nopriv_fetch_members_profile', array($this,'fetch_members_profile' ));

    add_action( 'wp_ajax_update_members_profile_dataTable', array($this,'update_members_profile_dataTable' ));
    add_action( 'wp_ajax_nopriv_update_members_profile_dataTable', array($this,'update_members_profile_dataTable' ));

    //dete data table users collage profile page
    add_action( 'wp_ajax_delete_members_profile_dataTable', array($this,'delete_members_profile_dataTable' ));
    add_action( 'wp_ajax_nopriv_delete_members_profile_dataTable', array($this,'delete_members_profile_dataTable' ));

    add_action( 'wp_ajax_update_members_profile_status', array($this,'update_members_profile_status' ));
    add_action( 'wp_ajax_nopriv_update_members_profile_status', array($this,'update_members_profile_status' ));

    //Update user profile fileds 
    add_action( 'wp_ajax_update_updatecollegeData', array($this,'update_updatecollegeData' ));
    add_action( 'wp_ajax_nopriv_update_updatecollegeData', array($this,'update_updatecollegeData' ));

    add_action( 'wp_ajax_search_cities_filter', array($this,'search_cities_filter' ));
    add_action( 'wp_ajax_nopriv_search_cities_filter', array($this,'search_cities_filter' ));

    add_action( 'wp_ajax_company_emp_list_data', array($this,'company_emp_list_data' ));
    add_action( 'wp_ajax_nopriv_company_emp_list_data', array($this,'company_emp_list_data' ));

    add_action( 'wp_ajax_remove_emp_groups', array($this,'remove_emp_groups' ));
    add_action( 'wp_ajax_nopriv_remove_emp_groups', array($this,'remove_emp_groups' ));

    add_action( 'wp_ajax_remove_group_banner', array($this,'remove_group_banner' ));
    add_action( 'wp_ajax_nopriv_remove_group_banner', array($this,'remove_group_banner' ));

    add_action( 'wp_ajax_delete_company_doc', array($this,'delete_company_doc' ));
    add_action( 'wp_ajax_nopriv_delete_company_doc', array($this,'delete_company_doc' ));

    add_action( 'wp_ajax_delete_company_project', array($this,'delete_company_project' ));
    add_action( 'wp_ajax_nopriv_delete_company_project', array($this,'delete_company_project' ));

    add_action( 'wp_ajax_remove_project_img', array($this,'remove_project_img' ));
    add_action( 'wp_ajax_nopriv_remove_project_img', array($this,'remove_project_img' ));

    add_action( 'wp_ajax_remove_group_logo', array($this,'remove_group_logo' ));
    add_action( 'wp_ajax_nopriv_remove_group_logo', array($this,'remove_group_logo' ));

    add_action( 'wp_ajax_create_company_project', array($this,'create_company_project' ));
    add_action( 'wp_ajax_nopriv_create_company_project', array($this,'create_company_project' ));

    add_action( 'wp_ajax_get_company_project', array($this,'get_company_project' ));
    add_action( 'wp_ajax_nopriv_get_company_project', array($this,'get_company_project' ));

    add_action( 'wp_ajax_update_company_project', array($this,'update_company_project' ));
    add_action( 'wp_ajax_nopriv_update_company_project', array($this,'update_company_project' ));
}

function create_company_project() {
    global $wpdb, $trikona_obj;

    $errors = [];
    $project_name = isset($_POST['project_name']) ? trim($_POST['project_name']) : "";
    $project_desc = isset($_POST['project_desc']) ? trim($_POST['project_desc']) : "";
    $files = $_FILES['project_images'];

    if (empty($project_name)) {
        $errors[] = "Project name is required.";
    }

    if (empty($project_desc)) {
        $errors[] = "Project description is required.";
    }

    if (!empty($files)) {
        $file_validations = $trikona_obj->ValidateFiles($files);
        if (!empty($file_validations)) {
            $errors = array_merge($errors, $file_validations);
        }
    }

    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = '<li>' . implode( '</li><li>', $errors) . '</li>';
        echo json_encode($response);die();
    } else {

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
        
        $attach_idss = implode(', ', $attach_ids);
        switch_to_blog(1);
        $result = $wpdb->insert($wpdb->prefix.'bp_group_projects', 
                array(
                    'project_name' => $_POST['project_name'],
                    'project_desc' => $_POST['project_desc'],
                    'project_images' => $attach_idss, 
                    'group_id' =>$_POST['company_id']
                )
            );
        restore_current_blog();

        if (!empty($result)) {
            $response['success'] = true;
            $response['message'] = '<li>Project created successfully.</li>';
        } else {
            $response['success'] = false;
            $response['message'] = '<li>Something went wrong. Please try again later.</li>';
        }
        echo json_encode($response);die();
    }
}

function update_company_project() {
    global $wpdb, $trikona_obj;

    $errors = [];
    $project_id = isset($_POST['project_id']) ? trim($_POST['project_id']) : "";
    $project_name = isset($_POST['project_name']) ? trim($_POST['project_name']) : "";
    $project_desc = isset($_POST['project_desc']) ? trim($_POST['project_desc']) : "";
    $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : "";
    $files = $_FILES['project_images'];

    switch_to_blog(1);
    $table_name = $wpdb->prefix . "bp_group_projects";
    $project = $wpdb->get_row( "SELECT * FROM  ".$table_name." WHERE id =  '$project_id'");
    restore_current_blog();

    if (empty($project_id)) {
        $errors[] = "Project not found.";
    } else {
        if (empty($project)) {
            $errors[] = "Invlid project details.";
        }
    }

    if (empty($project_name)) {
        $errors[] = "Project name is required.";
    }

    if (empty($project_desc)) {
        $errors[] = "Project description is required.";
    }

    if (!empty($files)) {
        $file_validations = $trikona_obj->ValidateFiles($files);
        if (!empty($file_validations)) {
            $errors = array_merge($errors, $file_validations);
        }
    }

    if(!empty($files)) {
        $selected_files = sizeof($files['name']);
        $existing_files = 0;

        if (!empty($project) && !empty($project->project_images)) {
            $project_images = explode(",", $project->project_images);
            $existing_files = sizeof($project_images);
        }

        $total_file_size = $selected_files + $existing_files;

        if ($total_file_size > 4) {
            $errors[] = "Maximum 4 files are allowed as project files.";
        }
    }

    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = '<li>' . implode( '</li><li>', $errors) . '</li>';
        echo json_encode($response);die();
    } else {

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

        $project_data = array(
                    'project_name' => $_POST['project_name'],
                    'project_desc' => $_POST['project_desc'],
                    'group_id' =>$group_id
                );

        if (!empty($attach_ids)) {
            $attach_ids = implode(', ', $attach_ids);
            if (!empty($project->project_images)) {
                $attach_ids = $project->project_images.", ".$attach_ids;
            }
            $project_data['project_images'] = $attach_ids;
        }

        switch_to_blog(1);
        $result = $wpdb->update($wpdb->prefix.'bp_group_projects', 
                $project_data,
                array( 'id' => $project_id )
            );
        restore_current_blog();

        if (!empty($result)) {
            $response['success'] = true;
            $response['message'] = '<li>Project updated successfully.</li>';
        } else {
            $response['success'] = false;
            $response['message'] = '<li>Something went wrong. Please try again later.</li>';
        }
        echo json_encode($response);die();
    }
}

function get_company_project() {
    global $wpdb;
    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : "";

    if (!empty($project_id)) {
        switch_to_blog(1);
        $table_name = $wpdb->prefix . "bp_group_projects";
        $project = $wpdb->get_row( "SELECT * FROM  ".$table_name." WHERE id =  '$project_id'");
        restore_current_blog();

        if (!empty($project)) {
            $response['success'] = true;
            $response['message'] = 'Project details.';
            $response['project_data'] = $project;
        } else {
            $response['success'] = false;
            $response['message'] = 'Project details not found.';    
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong. Please try again later.';
    }

    echo json_encode($response);die();
}

public function remove_project_img() {
    global $wpdb;

    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : "";
    $attachment_id = isset($_POST['attach_ids']) ? $_POST['attach_ids'] : "";

    if (!empty($project_id) && !empty($attachment_id)) {
        switch_to_blog(1);
        $table_name = $wpdb->prefix . "bp_group_projects";
        $project = $wpdb->get_row( "SELECT * FROM  ".$table_name." WHERE id =  '$project_id'");
        restore_current_blog();

        if (!empty($project)) {
            wp_delete_attachment( $attachment_id );

            $response['success'] = true;
            $response['message'] = 'Project image removed successfully.';
            echo json_encode($response);die();
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong. Please try again later.';
    }

    echo json_encode($response);die();
}

public function delete_company_project() {
    global $wpdb;

    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : "";

    if (!empty($project_id)) {
        switch_to_blog(1);
        $table_name = $wpdb->prefix . "bp_group_projects";
        $project = $wpdb->get_row( "SELECT * FROM  ".$table_name." WHERE id =  '$project_id'");
        restore_current_blog();

        if (!empty($project)) {
            $project_images =  explode(',',  $project->project_images);

            if (!empty($project_images)) {
                foreach ($project_images as $attachment_id) {
                    wp_delete_attachment( $attachment_id );
                }
            }
            $wpdb->delete( $table_name, array( 'id' => $project_id ) );

            $response['success'] = true;
            $response['message'] = 'Project removed successfully.';
            echo json_encode($response);die();
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong. Please try again later.';
    }

    echo json_encode($response);die();
}

public function delete_company_doc() {
    global $wpdb;

    $documet_id = isset($_POST['documet_id']) ? $_POST['documet_id'] : "";

    if (!empty($documet_id)) {
        switch_to_blog(1);
        $table_name = $wpdb->prefix . "bp_group_documents";
        $company_document = $wpdb->get_row( "SELECT * FROM  ".$table_name." WHERE id =  '$documet_id'");
        restore_current_blog();

        if (!empty($company_document)) {
            wp_delete_attachment( $company_document->doc_attachments );

            $wpdb->delete( $table_name, array( 'id' => $documet_id ) );

            $response['success'] = true;
            $response['message'] = 'Document removed successfully.';
            echo json_encode($response);die();
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong. Please try again later.';
    }

    echo json_encode($response);die();
}

public function remove_group_banner(){

  global $wpdb;
 $group_id =  (int) $_POST['group_id'];
 if($group_id !=""){
       wp_delete_attachment( $_POST['attach_ids'] );
        $output = array(
       "status"    => 1,
       "msg"    => "Member Removed Successfully...",
  );

echo json_encode($output);
 }

 die;
}

public function remove_group_logo(){

    global $wpdb;
    $group_id =  (int) $_POST['group_id'];
    if($group_id !=""){
        // $result = wp_delete_post($_POST['attach_ids'], true);
        switch_to_blog(1);
        groups_delete_groupmeta($_POST['attach_ids'], 'group-avatarsImg');
        restore_current_blog();

        $output = array(
            "status"    => 1,
            "msg"    => "Group logo removed...",
        );

        echo json_encode($output);
    }
    die;
}

public function remove_emp_groups(){

  global $wpdb;
 $user_id=  (int) $_POST['id'];
 $group_id =  (int) $_POST['groupid'];
 if($user_id !=""){
         groups_leave_group( $group_id, $user_id );
        $output = array(
       "status"    => 1,
       "msg"    => "Member Removed Successfully...",
  );

echo json_encode($output);
 }

 die;
}

public function company_emp_list_data(){
	 global $wpdb, $trikona_obj;
parse_str($_POST['rowData'], $searcharray);

 $current_user = wp_get_current_user();
if($_POST['id']!=""){
	$user_data = wp_update_user( array(
	  'ID' => $_POST['id'],
	  'first_name' => $searcharray['first_name'],
	  'last_name' => $searcharray['last_name'] 
	) 
);
if($searcharray['status']!=""){
$taxonomy ='bp_member_type';
wp_set_object_terms( $_POST['id'], array($searcharray['status']), $taxonomy );
}	

if($searcharray['status']==""){
$taxonomy ='bp_member_type';
    wp_remove_object_terms( $_POST['id'], array($_POST['author']), $taxonomy );
}

switch_to_blog(1);
$tableName = $wpdb->prefix .'bp_groups_members';
if($searcharray['grouprole']==$trikona_obj->group_mod_role){
    $wpdb->update($tableName, array( 'user_title' => $searcharray['grouprole']),array('user_id'=>$_POST['id']));
}

if($searcharray['grouprole']==$trikona_obj->member_role){
    $wpdb->update($tableName, array( 'user_title' => $searcharray['grouprole']),array('user_id'=>$_POST['id']));
}

}
$user_id = $_POST['id'];
  $members = $wpdb->get_row("SELECT * from ".$wpdb->prefix ."bp_groups_members where user_id='$user_id' ");
restore_current_blog();

            $user_info  = get_userdata($_POST['id']);
           $roles = $user_info->roles;
           if($members->user_title==""){
				 $groupMember = $trikona_obj->member_role;
			}else{
				$groupMember = $members->user_title;
			}
			if($groupMember==$trikona_obj->group_mod_role){
				$corporateRoles =$corporateRole[0];
			}
			if($groupMember==""){
				$corporateRoles ='';
			}
$output = array(
 "first_name"    => $user_info->first_name,
 "last_name"  =>  $user_info->last_name,
 "roles" => $roles[0],
 "grouprole"    => $groupMember,
 "memberships"    => $searcharray['status'],
);

echo json_encode($output);
die;
}


public function fetch_empyolees_data(){
global $wpdb,$bp,$trikona_obj;
$current_user = wp_get_current_user();

$results = $wpdb->get_row("SELECT * from ".$wpdb->prefix ."bp_groups_members where user_id='$current_user->ID' AND user_title='Group Admin'");

$columns = array('first_name', 'last_name','email','member_role','member_role');


if($_POST["search"]["value"]!="")
{

 $query = ' SELECT * from ".$wpdb->prefix ."usermeta
 WHERE meta_value LIKE "%'.$_POST["search"]["value"].'%"  ';

}

else{
$query = "SELECT * from ".$wpdb->prefix ."bp_groups_members where group_id='$results->group_id' AND user_title=''";	
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY user_id '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY user_id DESC ';
}

$query1 = '';



if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$query = $wpdb->get_results($query . $query1);

$query22 = $wpdb->get_results("SELECT * from ".$wpdb->prefix ."bp_groups_members where group_id='$results->group_id' ");
$number_filter_row =$wpdb->num_rows;


$data = array();
foreach ($query as $key => $memberInfo) {
	$user_info  = get_userdata($memberInfo->user_id);
     $roles = $user_info->roles;
   

	if ($memberInfo->status == 0){
 $chk= "checked='checked'";
}else{
	$chk= "";
}

 $term_obj_list = get_the_terms($memberInfo->user_id, 'bp_member_type' );
if(!empty($term_obj_list)){
$termArr = array();
foreach ($term_obj_list as $key => $term_obj_value) {
	$termArr[]= $term_obj_value->name;
}

 $corporateRole = implode(', ', $termArr);
}

$designation_current = get_user_meta( $memberInfo->user_id, $trikona_obj->designation_current_meta, true );

if($memberInfo->user_title==""){
	 $groupMember = "Member";
}else{
	$groupMember = $memberInfo->user_title;
}

 $sub_array = array();
 $sub_array[] = '<div  class="checkbox"><input  data-column="status" '.$chk.' name="memberStatus" class="member_status" type="checkbox" id="'.$user_info->user_id.'" data-email="'.$user_info->user_email.'"  data-id="'.$user_info->id.'" value="'.$memberInfo->status.'"><label for="'.$user_info->id.'"></label></div>';

 $sub_array[] = '<div  class="update"  data-email="'.$user_info->user_email.'" data-id="'.$user_info->id.'" data-column="first_name">' . $user_info->first_name . '</div>';

 $sub_array[] = '<div  class="update"  data-email="'.$user_info->user_email.'" data-id="'.$user_info->id.'" data-column="last_name">' . $user_info->last_name . '</div>';

  $sub_array[] = '<div  class="update"  data-email="'.$user_info->user_email.'" data-id="'.$user_info->id.'" data-column="member_role">' .  $roles[0] . '</div>';
   $sub_array[] = '<div  class="update"  data-email="'.$user_info->user_email.'" data-id="'.$user_info->id.'" data-column="member_type">' .  $groupMember . '</div>';
   $sub_array[] = '<div  class="update"  data-email="'.$user_info->user_email.'" data-id="'.$user_info->id.'" data-column="member_type">' .  $roles[0] . '</div>';

 //$sub_array[] = '<iput type="<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$memberInfo->email.'">Delete</button>';
 $data[] = $sub_array;
}



$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  $this->get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

die;
}






public function update_emp_profile_dataTable(){
global $wpdb,$bp, $trikona_obj;
$current_user = wp_get_current_user();
if(isset($_POST["id"]))
{
$user = get_user_by( 'email', $_POST["email"] );
    $user_id = $_POST["id"];
 if($_POST["column_name"]=="first_name"){
 	if($_POST["column_name"]=="first_name"){
      $first_name =$_POST["value"];
 	}
 	
    wp_update_user( array(
        'ID' => $user_id,
        'first_name' => $first_name,
        
   ) );
}

 if($_POST["column_name"]=="last_name"){
 	if($_POST["column_name"]=="last_name"){
      $last_name =$_POST["value"];
 	}
 	
    wp_update_user( array(
        'ID' => $user_id,
        'last_name' => $last_name,
        
   ) );
}


if($_POST["column_name"] == 'possitions' ){
update_user_meta( $user_id, $trikona_obj->designation_current_meta, $_POST["value"] );

}

echo 'Data Updated Successfully';
}else{
	echo "Data not Update";
}
die;
}

public function fetch_members_profile(){
    global $wpdb,$bp, $trikona_obj;

    $group_id = isset($_POST['group_id']) ? base64_decode($_POST['group_id']) : '';
    /*$current_user = wp_get_current_user();
    $currentUserGroup = $trikona_obj->getGroupMembers(['user_id' => $current_user->ID], $is_single_record = true);*/

    if (!empty($group_id)) {
        $filter = ['group_id' => $group_id];

        if (isset($_POST['searchByclgcourses']) && !empty($_POST['searchByclgcourses'])) {
            $filter['course_id'] = $_POST['searchByclgcourses'];
        }
        if (isset($_POST['searchByyearpss']) && !empty($_POST['searchByyearpss'])) {
            $filter['year_of_passout'] = $_POST['searchByyearpss'];
        }
        if (isset($_POST['searchByedus']) && !empty($_POST['searchByedus'])) {
            $filter['highest_education'] = $_POST['searchByedus'];
        }
        if (isset($_POST['searchBystatus']) && !empty($_POST['searchBystatus'])) {
            $filter['status'] = $_POST['searchBystatus'];
        }

        $records = $trikona_obj->get_students_in_buddypress_group($filter);
        $students = $records['data'];
        $number_filter_row = $records['recordsTotal'];;
        $total_records = $records['recordsFiltered'];;
        $data = [];
        foreach ($students as $student) {
            $first_name = get_user_meta($student->ID, 'first_name', true);
            $last_name  = get_user_meta($student->ID, 'last_name', true);
            $year_of_study = xprofile_get_field_data( $trikona_obj->total_year_of_study_field_id, $student->ID );
            $current_of_study = xprofile_get_field_data( $trikona_obj->current_Year_Of_study_field_id, $student->ID );
            $year_of_passout = xprofile_get_field_data( $trikona_obj->year_of_passout_field_id, $student->ID );
            $user = get_userdata($student->ID);
            $status_code = $user->user_status;
            if ($status_code == 1) {
                $status = 'Inactive';
                $chk = "" ;
            } else {
                $status = 'Active';
                $chk = "checked='checked'";
            }

            $data[] = [
                '<div  class="checkbox"><input  data-column="status" '.$chk.' name="memberStatus" class="member_status" type="checkbox" id="'.$student->ID.'" data-email="'.$student->user_emailuser_email.'"  data-id="'.$student->ID.'" value="'.$status.'"><label for="'.$student->ID.'"></label></div>',
                '<div  class="update"  data-email="'.$student->user_email.'" data-id="'.$student->ID.'" data-column="first_name">' . $first_name . '</div>',
                '<div  class="update"  data-email="'.$student->user_email.'" data-id="'.$student->ID.'" data-column="last_name">' . $last_name . '</div>',
                '<div  class="update"  data-email="'.$student->user_email.'" data-id="'.$student->ID.'" data-column="total_year_of_study">' . $year_of_study . '</div>',
                '<div  class="update"  data-email="'.$student->user_email.'" data-id="'.$student->ID.'" data-column="current_year_of_study">' . $current_of_study . '</div>',
                '<div  class="update"  data-email="'.$student->user_email.'" data-id="'.$student->ID.'" data-column="year_of_passout">' . $year_of_passout . '</div>'
            ];    
        }

        $output = [
            "draw"    => intval($_POST["draw"]),
            "recordsTotal"  =>  $total_records,
            "recordsFiltered" => $number_filter_row,
            "data"    => $data
        ];
    } else {
        $data = [];
        $number_filter_row = 0;
        $total_records = 0;
        $output = [
            "draw"    => intval($_POST["draw"]),
            "recordsTotal"  =>  $total_records,
            "recordsFiltered" => $number_filter_row,
            "data"    => $data
        ];
    }

    echo json_encode($output);
    die();
}




/*public function fetch_members_profile(){
global $wpdb,$bp;
$current_user = wp_get_current_user();
switch_to_blog( 1 );
//$results = $wpdb->get_row("SELECT * from ".$wpdb->prefix ."bp_groups_members where user_id='$current_user->ID' AND user_title='Group Admin'");
$results = $wpdb->get_row("SELECT * from ".$wpdb->prefix ."bp_groups_members where user_id='$current_user->ID'");

$columns = array('first_name', 'last_name','email','course','total_year_of_study','current_year_of_study','year_of_passout');



if($_POST["search"]["value"]!="")
{

 $query = ' SELECT * from ".$wpdb->prefix ."user_profile_data
 WHERE first_name LIKE "%'.$_POST["search"]["value"].'%" 
 OR last_name LIKE "%'.$_POST["search"]["value"].'%" 
 OR total_year_of_study LIKE "%'.$_POST["search"]["value"].'%"
 OR current_year_of_study LIKE "%'.$_POST["search"]["value"].'%"
 OR year_of_passout LIKE "%'.$_POST["search"]["value"].'%" AND  bp_group_id='.$results->group_id.' ';

}elseif($_POST["searchByclgcourses"]!=""){

$query = ' SELECT * from ".$wpdb->prefix ."user_profile_data
 WHERE courseid LIKE "%'.$_POST["searchByclgcourses"].'%" 
 AND  bp_group_id='.$results->group_id.' ';

}
elseif($_POST["searchBystatus"]!=""){

$query = ' SELECT * from ".$wpdb->prefix ."user_profile_data
 WHERE status LIKE "%'.$_POST["searchBystatus"].'%" 
 AND  bp_group_id='.$results->group_id.' ';

}
elseif($_POST["searchByyearpss"]!=""){

$query = ' SELECT * from '.$wpdb->prefix .'user_profile_data
 WHERE year_of_passout LIKE "%'.$_POST["searchByyearpss"].'%" 
 AND  bp_group_id='.$results->group_id.' ';

}
elseif($_POST["searchByedus"]!=""){

$query = ' SELECT * from '.$wpdb->prefix .'user_profile_data
 WHERE highest_education LIKE "%'.$_POST["searchByedus"].'%" 
 AND  bp_group_id='.$results->group_id.' ';

}
else{
$query = "SELECT * from ".$wpdb->prefix ."user_profile_data where bp_group_id='$results->group_id' AND  status='0'";	
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY id DESC ';
}

$query1 = '';



if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$query = $wpdb->get_results($query . $query1);

$query22 = $wpdb->get_results("SELECT * from ".$wpdb->prefix ."user_profile_data where bp_group_id='$results->group_id' ");
$number_filter_row =$wpdb->num_rows;

restore_current_blog();
$data = array();

foreach ($query as $key => $memberInfo) {
	if ($memberInfo->status == 0){
 $chk= "checked='checked'";
}else{
	$chk= "";
}
 $sub_array = array();
 $sub_array[] = '<div  class="checkbox"><input  data-column="status" '.$chk.' name="memberStatus" class="member_status" type="checkbox" id="'.$memberInfo->id.'" data-email="'.$memberInfo->email.'"  data-id="'.$memberInfo->id.'" value="'.$memberInfo->status.'"><label for="'.$memberInfo->id.'"></label></div>';
 $sub_array[] = '<div  class="update"  data-email="'.$memberInfo->email.'" data-id="'.$memberInfo->id.'" data-column="first_name">' . $memberInfo->first_name . '</div>';
 $sub_array[] = '<div  class="update"  data-email="'.$memberInfo->email.'" data-id="'.$memberInfo->id.'" data-column="last_name">' . $memberInfo->last_name . '</div>';

  $sub_array[] = '<div  class="update"  data-email="'.$memberInfo->email.'" data-id="'.$memberInfo->id.'" data-column="total_year_of_study">' . $memberInfo->total_year_of_study . '</div>';
  $sub_array[] = '<div  class="update"  data-email="'.$memberInfo->email.'" data-id="'.$memberInfo->id.'" data-column="current_year_of_study">' . $memberInfo->current_year_of_study . '</div>';
  $sub_array[] = '<div  class="update"  data-email="'.$memberInfo->email.'" data-id="'.$memberInfo->id.'" data-column="year_of_passout">' . $memberInfo->year_of_passout . '</div>';

 //$sub_array[] = '<iput type="<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$memberInfo->email.'">Delete</button>';
 $data[] = $sub_array;
}



$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  $this->get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

die;
}*/


public function get_all_data($connect)
{
 global $wpdb,$bp;
$current_user = wp_get_current_user();
$results = $wpdb->get_row("SELECT * from ".$wpdb->prefix ."bp_groups where creator_id='$current_user->ID'");
$query = $wpdb->get_results("SELECT * from ".$wpdb->prefix ."user_profile_data where bp_group_id='$results->id'");
$result = $query;
 return $wpdb->num_rows;
}


public function update_members_profile_dataTable(){
global $wpdb,$bp,$trikona_obj;
$current_user = wp_get_current_user();
if(isset($_POST["id"]))
{
$user = get_user_by( 'email', $_POST["email"] );
    $user_id = $user->ID;
 if($_POST["column_name"]=="first_name"){
 	if($_POST["column_name"]=="first_name"){
      $first_name =$_POST["value"];
 	}
 	
    wp_update_user( array(
        'ID' => $user_id,
        'first_name' => $first_name,
        
   ) );
}

 if($_POST["column_name"]=="last_name"){
 	if($_POST["column_name"]=="last_name"){
      $last_name =$_POST["value"];
 	}
 	
    wp_update_user( array(
        'ID' => $user_id,
        'last_name' => $last_name,
        
   ) );
}

if($_POST["column_name"] =='total_year_of_study' ){
	update_user_meta( $user_id, $trikona_obj->total_year_of_study_meta, $_POST["value"] );
}
if($_POST["column_name"] == 'current_year_of_study' ){
update_user_meta( $user_id, $trikona_obj->current_year_of_study_meta, $_POST["value"] );

}

if($_POST["column_name"] =='year_of_passout' ){
	update_user_meta( $user_id, $trikona_obj->year_of_passout_meta, $_POST["value"] );
}

$table_name = $wpdb->prefix .'user_profile_data';
$wpdb->update( $table_name, array( $_POST["column_name"] => $_POST["value"]),array('ID'=>$_POST["id"]));
echo 'Data Updated Successfully';
}else{
	echo "Data not Update";
}
die;
}




public function delete_members_profile_dataTable(){
global $wpdb,$bp;
$current_user = wp_get_current_user();
if(isset($_POST["id"]))
{

$table_name = $wpdb->prefix .'user_profile_data';
    $wpdb->delete( $table_name, array( 'id' => $_POST["id"] ) );
    echo 'Data Delete Successfully';
}else{
	echo "Data not Delete";
}
die;
}



public function update_members_profile_status(){
global $wpdb,$bp;
$current_user = wp_get_current_user();
if(isset($_POST["id"]))
{

$user = get_user_by( 'email', $_POST["user_email"] );
if($_POST["value"]==0){
   $statusVal =1;
}else{
 $statusVal =0;
}

$table_name = $wpdb->prefix .'user_profile_data';
$wpdb->update( $table_name, array( $_POST["column_name"] => $statusVal),array('ID'=>$_POST["id"]));
echo 'Data Updated Successfully';
}else{
	echo "Data not Update";
}
die;
}





public function update_updatecollegeData(){
	global $wpdb, $trikona_obj;
	parse_str($_POST['data'], $searcharray);
	
$current_user = wp_get_current_user();

if($searcharray['group_id']!=""){
groups_update_groupmeta( $searcharray['group_id'], $trikona_obj->state_meta ,$searcharray['state'] );
groups_update_groupmeta( $searcharray['group_id'], $trikona_obj->company_website_url_meta ,$searcharray['memberDob'] );
groups_update_groupmeta( $searcharray['group_id'], $trikona_obj->city_meta ,$searcharray['city'] );
groups_update_groupmeta( $searcharray['group_id'], $trikona_obj->address_meta ,$searcharray['address'] );
groups_update_groupmeta( $searcharray['group_id'], $trikona_obj->company_staff_meta ,$searcharray['staff'] );
groups_update_groupmeta( $searcharray['group_id'], $trikona_obj->email_address_meta ,$searcharray['college_email'] );
groups_update_groupmeta( $searcharray['group_id'], $trikona_obj->phone_number_meta ,$searcharray['collegePhone'] );
 $table_name = $wpdb->prefix . "bp_groups";
$wpdb->update( $table_name, array( 'description' => $searcharray['company_descriptions']),array('ID'=>$searcharray['group_id']));
}
die;
}



public function search_cities_filter(){
global $wpdb;
$state_id = $_POST["state_id"];
switch_to_blog(1);
$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cities  where state_id = $state_id");?>
		   <option value="">Select City</option>
		  <?php
		    foreach ($results as $key => $row) { 
		    ?>
		   <option value="<?php echo $row->city;?>"><?php echo $row->city;?></option>
		<?php
}
restore_current_blog();
die;
}
//end class
}

new TrikonaGroupsProfilesAjax();
?>