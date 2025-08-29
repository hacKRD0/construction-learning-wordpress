<?php 
function diwp_metabox_mutiple_fields(){
 
    add_meta_box(
            'diwp-metabox-multiple-fields',
            'Select Company',
            'diwp_add_multiple_fields',
            'job_listing'
        );
}
add_action('add_meta_boxes', 'diwp_metabox_mutiple_fields');
function diwp_add_multiple_fields(){ 
      global $post; 
     global $wpdb; 
    // Get Value of Fields From Database
    $_job_groups = get_post_meta( $post->ID, '_job_groups', true);
       $sql = "SELECT * FROM wpcw_bp_groups order by name asc";
      $results = $wpdb->get_results($sql);?> 
<div class="row">
    <div class="label"><b>Select Compay</b></div>
    <div class="fields">
        <select name="_job_groups" style="width:50%" >
            <option value="">Select Option</option>
            <?php foreach($results as $member_type=>$mt){?>
            <option value="<?php echo $mt->name;?>" <?php if($mt->name ==  $_job_groups) echo 'selected'; ?>><?php echo $mt->name;?></option>
        <?php } ?>
          
        </select>
    </div>
</div> 
<?php    
}


function diwp_save_multiple_fields_metabox(){
 
    global $post; 
    if(isset($_POST["_job_groups"])) :
        update_post_meta($post->ID, '_job_groups', $_POST["_job_groups"]);
    endif;
}
 
add_action('save_post', 'diwp_save_multiple_fields_metabox');

//Create job listing custom shortcodes

function  jobs_queryshortcode($atts) {
 
  $group_id = bp_get_current_group_id();
  global $wpdb;
  $groups  = $wpdb->get_row( "SELECT * FROM wpcw_bp_groups WHERE id = $group_id" );
$bp_groups_members = $wpdb->get_results( "SELECT user_id FROM wpcw_bp_groups_members WHERE group_id='$group_id' and user_title='Group Mod' or user_title='Group Admin' ");

if($wpdb->num_rows > 0){
	ob_start();
$groupsAdminArr = array();

foreach ($bp_groups_members as $key => $bp_groups_members_value) {
	$groupsAdminArr[] =$bp_groups_members_value->user_id;
}
$args = array(
  'post_type'       => 'job_listing',
  'post_status'     => 'publish',
  'posts_per_page'  => -1,
  'order'           => 'DESC',
  'meta_query'      => array(
    array(
      'key'         => '_job_groups',
      'value'       => $groups->name,
      'compare'     => '=',
    ),
  )
);

$query = new WP_Query( $args );
if ($query->have_posts()) {
?>
	<ul class="job_listings">
		<!-- loop start -->
		<?php while ($query->have_posts()): $query->the_post(); 
			 $author_id = get_post_field( 'post_author', get_the_ID() );
			 
			 $user_info = get_userdata( $author_id);
			 $first_name = $user_info->first_name;
            $last_name = $user_info->last_name;
            if($first_name!=""){
            	$display_name = $first_name.'  '.$last_name;
            }else{
            	$display_name = get_the_author_meta( 'display_name' , $author_id ); 
            }
			 
           
             if (in_array($author_id, $groupsAdminArr, true ) ) {
             	$term_obj_list = get_the_terms( get_the_ID(), 'job_listing_type' );
             	$_remote_position = get_post_meta(get_the_ID(),'_remote_position',true);
             	if($_remote_position==1){
                    $_remote_position = '(Remote)';
             	}else{
             		 $_remote_position = '';
             	}
             
			?>
			<li class="post-1243 job_listing type-job_listing status-publish hentry job-type-full-time job-type-part-time job-type-temporary" data-longitude="" data-latitude="">
	<a href="<?php echo get_permalink(get_the_ID());?>">
		<img class="company_logo" src="https://www.construction-world.in/wp-content/plugins/wp-job-manager/assets/images/company.png" alt="">
				<div class="position">
			<h3><?php the_title();?></h3>
			<div class="company"></div>
		</div>
		<div class="location">
			<?php echo get_post_meta(get_the_ID(),'_job_location',true);?> <small><?php echo $_remote_position;?></small>	
				</div>
		<ul class="meta">
			<?php foreach ($term_obj_list as $key => $job_type) {?>
		<li class="job-type  <?php echo  $job_type->slug;?>"><?php echo  $job_type->name;?></li>
		<?php }?>
		<li class="date"><time datetime="2023-03-09">Posted <?php echo time_ago(); ?></time></li>
		<li class="date">Posted By  <?php echo $display_name;?></li>
      </ul>
	</a>
</li>
		<?php } endwhile; }?>
		<!-- loop end -->
	</ul>
<?php

$data = ob_get_contents();
        ob_end_clean();
    return $data;
	
}
else{

			echo "Job listing not found..";
		} 
		
}
add_shortcode('group_jobs_listing', 'jobs_queryshortcode');




function time_ago( $type = 'post' ) {
    $d = 'comment' == $type ? 'get_comment_time' : 'get_post_modified_time';

    return human_time_diff($d('U'), current_time('timestamp')) . " " . __('ago');

}





add_filter( 'submit_job_form_fields', 'gma_custom_submit_job_form_fields' );

function gma_custom_submit_job_form_fields( $fields ) {

unset($fields['company']['company_name']);
unset($fields['company']['company_website']);
unset($fields['company']['company_tagline']);
unset($fields['company']['company_video']);
unset($fields['company']['company_twitter']);
unset($fields['company']['company_logo']);

return $fields;

}


add_action( 'single_job_listing_meta_end', 'display_job_salary_data' );

function display_job_salary_data() {
  global $post;

  $salary = get_post_meta( $post->ID, '_job_groups', true );

  if ( $salary ) {
    echo '<li>' . __( 'Company:' ) . ' ' . esc_html( $salary ) . '</li>';
  }
}


add_filter( 'submit_job_form_fields', 'custom_submit_job_form_fields' );

// This is your function which takes the fields, modifies them, and returns them
// You can see the fields which can be changed here: https://github.com/mikejolley/WP-Job-Manager/blob/master/includes/forms/class-wp-job-manager-form-submit-job.php
function custom_submit_job_form_fields( $fields ) {
	global $wpdb,$bp;
	$args = array('group_type'=>'company');
	switch_to_blog(1);
	$current_user = wp_get_current_user();
    $userRolesChk = ( array ) $current_user->roles;
    $table_name = $wpdb->prefix . "bp_groups_members";

    //$results = $wpdb->get_row("SELECT * from  $table_name where user_id='$current_user->ID' and  user_title='Group Admin'");
    $results = $wpdb->get_row( "SELECT * FROM `wpcw_bp_groups_members` WHERE `user_id` = '$current_user->ID' AND `user_title` IN ('Group Mod','Group Admin','trikona-admin')");
  restore_current_blog();
  //print_r($results );
 $sql = "SELECT * FROM wpcw_bp_groups order by name asc";
      $member_type_objects = $wpdb->get_results($sql);
    $member_types=array();
    if (in_array('administrator', $userRolesChk) || in_array('trikona-admin', $userRolesChk)){
			foreach($member_type_objects as $member_type=>$mt){
            
				//if($results->group_id==$mt->id){
				$member_types[$mt->name]=$mt->name;
			  //}
			}
		
	     $member_types = array_merge(array('Select Company'=>__('Select Company','vibebp')),$member_types);
       }else{

       	foreach($member_type_objects as $member_type=>$mt){
				if($results->group_id==$mt->id){
				$member_types[$mt->name]=$mt->name;
			  }
			}
       }
      //print_r($member_types);
    $fields['job']['job_groups'] = array(
    'label'       => __( 'Company', 'job_manager' ),
    'type'        => 'select',
    'required'    => true,
    'options'		=> $member_types,
    'priority'    => 7
  );
      return $fields;
}


?>