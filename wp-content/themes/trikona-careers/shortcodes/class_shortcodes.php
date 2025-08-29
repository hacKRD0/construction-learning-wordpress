<?php 
// Shortcode for Member Cards

class MembersGridShortCOdes{


/**
 * Initializes an __construct service object.
 *
 * @return An authorized service object.
 */

public function __construct() {
 add_shortcode('show_members_cards', array($this,'cstm_members_cards'));
 
 }


/**
 * Initializes an shortcode function service object.
 *
 * @return An members service object.
 */


public function cstm_members_cards($atts){
wp_enqueue_style('member_grids');

  global $wpdb,$bp;
  $results = $wpdb->get_results("SELECT field.id as id, field.name as name FROM {$bp->profile->table_name_fields} as field INNER JOIN {$bp->profile->table_name_meta} as meta ON field.id = meta.object_id
    WHERE meta.object_type = 'field' AND meta.meta_key = 'do_autolink' AND meta.meta_value = 'on'");
 $current_user = wp_get_current_user();


$memberShipTypeElite = array('student-rookie','student-champion','student-pro','prof-gold','prof-silver','prof-platinum');
$memberShipTypePrime = array('student-champion','student-pro','prof-gold','prof-platinum');
$memberShipTypeBasic = array('student-pro','prof-platinum');

$current_user = wp_get_current_user();
 $term_obj_list = get_the_terms($current_user->ID, 'bp_member_type' );

$currentUserPlan = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'names' ) );

if(!empty($currentUserPlan)){
    $corporateRole = implode(', ', $currentUserPlan);
}


if($current_user->ID > 0){

	$filtersData = $_GET['filtersData'];
	if(!empty($filtersData)){    

    $meta = array('relation' => 'OR'); 

      foreach ($filtersData as $key => $value) { 
        $feildValues=array();

      	 $array = explode('|', $value);
      	   if (isset($array[0]) && !empty($array[0])) {
      	   	$feildValues[]= $array[1];
            array_push($meta,array(
              'key' => $array[0],
              'value' => $feildValues ,
              'compare' => '%like%'
            ));    
       //print_r($feildValues); echo '<br>';
      }
  }
}

?>
<!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"> -->

<div class="container-fluid cs-directory-page">
  <div class="row" style="padding:50px 0px">
<aside class="col-md-3 filter-col">
	<form name="directory-filterpage" method="GET"> 	
    <div class="card">
   <article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_1" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Search</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_1" style="">
			<div class="card-body">
				<div class="input-group">
				  <input type="text" class="form-control" name="seachmembers" placeholder="Search" value="<?php echo $_GET['seachmembers'];?>">
				  <div class="input-group-append">
				    <button class="btn btn-light" type="submit">Search</button>
				  </div>
				</div>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->
	 	
    	 <?php if(!empty($results)){
foreach($results as $result){
$results_fls = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$result->id."'");

$field_meta = $wpdb->get_results("SELECT * FROM wpcw_bp_xprofile_meta WHERE object_id='".$result->id."'");
$filedArr = array();
foreach ($field_meta as $key => $field_meta_value) {
if( $field_meta_value->meta_key=='member_type'){
$filedArr[]=  $field_meta_value->meta_value;
}
}
if (in_array($atts['types'], $filedArr) ){
$filtersData = $_GET['filtersData'];
	if(!empty($filtersData)){    

    $meta = array('relation' => 'OR'); 

      foreach ($filtersData as $key => $value) { 
        $feildValues=array();

      	 $array = explode('|', $value);
      	   if (isset($array[0]) && !empty($array[0])) {
      	   	$feildValues[]= $array[1];
            array_push($meta,array(
              'key' => $array[0],
              'value' => $feildValues ,
              'compare' => '%like%'
            ));    
       //print_r($feildValues); echo '<br>';
      }
  }
}

?>
 
	  <article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_<?php echo $result->id;?>" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title"><?php echo $result->name;?></h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_<?php echo $result->id;?>" style="">
			<div class="card-body">
				
				
				 <select class="filter-data-select" name="filtersData[]"  multiple="multiple" data-placeholder="<?php echo $result->name;?>">

                <?php $cc =0;
                  foreach ($results_fls as $key => $field) {
                  	if(!empty($_GET['filtersData'])){
                       if (in_array($result->name.'|'.$field->name, $_GET['filtersData']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						}
                  	?>
                <div class="vibebp_members_directory_filter_values">
                    <div class="checkbox <?php echo $checkboxCls;?>  ">
                        <option value="<?php echo $result->name;?>|<?php echo $field->name;?>"  <?php echo $selectTed;?>><?php echo $field->name;?></option>

                    </div>
                </div>


                <?php } ?>
            </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->
	<?php } } }?>

	 <article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_cog1" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Company</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_cog1" style="">
			<div class="card-body">
				
				
				<select class="filter-data-select" name="filtersData[]"  multiple="multiple" data-placeholder="Companies">
            <?php 
$args = array(
'group_type'=>'company',
'order'  => 'ASC',
'orderby'  => 'name',
'per_page'=>500,
);    
$member_type_objects =  groups_get_groups($args);
foreach($member_type_objects['groups'] as $member_type=>$mt){     
if(!empty($_GET['filtersData'])){
                       if (in_array('bp_group_id'.'|'.$mt->id, $_GET['filtersData']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						}
?>
            <div class="vibebp_members_directory_filter_values">
                <div class="checkbox">
                	<option value="bp_group_id|<?php echo $mt->id;?>" <?php echo $selectTed;?>><?php echo $mt->name;?></option>
                   
                </div>
            </div>

            <?php } ?>
        </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	 <article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_cell1" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Colleges</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_cell1" style="">
			<div class="card-body">
				
				
<select class="filter-data-select" name="filtersData[]"  multiple="multiple" data-placeholder="Colleges">
            <?php 
$args = array(
'group_type'=>'college',
'order'  => 'ASC',
'orderby'  => 'name',
'per_page'=>500,
// 'page' => 1,
);    
$member_type_objects =  groups_get_groups($args);
//print_r($member_type_objects['total']);
foreach($member_type_objects['groups'] as $member_type=>$mt){     
if(!empty($_GET['filtersData'])){
                       if (in_array('bp_group_id'.'|'.$mt->id, $_GET['filtersData']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						}?>
            <div class="vibebp_members_directory_filter_values">
                <div class="checkbox">
                   <option value="bp_group_id|<?php echo $mt->id;?>" <?php echo $selectTed;?>><?php echo $mt->name;?></option>
                </div>
            </div>
            <?php } ?>
        </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->


 <?php $args = array( 
'post_type' => 'course',
'posts_per_page' => 217,
'tax_query' => array(
array(
'taxonomy'  => 'course-cat',
'field'     => 'slug',
'terms'     => 'filter-category',
'operator'  => 'IN'
)

),


); 
$loop = new WP_Query( $args );
?>

 <article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_lms" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">LMS Courses</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_lms" style="">
			<div class="card-body">
				
	<select class="couresChk filter-data-select" name="filtersData[]"  multiple="multiple" data-placeholder="LMS Courses">
            <?php while ( $loop->have_posts() ) : $loop->the_post();
                 if(!empty($_GET['filtersData'])){
                       if (in_array(get_the_ID(), $_GET['filtersData']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						}

            	?>
            <div class="vibebp_members_directory_filter_values">
                <div class="checkbox">
                   <option value="<?php echo get_the_ID();?>" <?php echo $selectTed;?>><?php the_title();?></option>

                </div>
            </div>
            <?php endwhile; ?>
            </select> 

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<?php $args = array(  
'post_type' => 'studentcourses',
'post_status' => 'publish',
'posts_per_page' => -1, 
'orderby' => 'title', 
'order' => 'ASC', 
);
$loop = new WP_Query( $args );
?>
<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#studentcourses" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Academic Courses</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="studentcourses" style="">
			<div class="card-body">
				
	<select class="filter-data-select" name="filtersData[]"  multiple="multiple" data-placeholder="Academic Courses">
            <?php while ( $loop->have_posts() ) : $loop->the_post();
                if(!empty($_GET['filtersData'])){
                       if (in_array('course-id'.'|'.get_the_ID(), $_GET['filtersData']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						}
            	?>
            <div class="vibebp_members_directory_filter_values">
                <div class="checkbox">
                	<option value="course-id|<?php echo get_the_ID();?>" <?php echo $selectTed;?>><?php the_title();?></option>

                </div>
            </div>
            <?php endwhile; ?>
        </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<input type=submit class="filterBtn" value="Apply"><br/>
	<?php //echo $atts['roles'];
         if($atts['roles']=="professional"){ ?>
         <a href="/professional-directory/" class="btn filterBtn ">Clear Filters</a>
        <?php }else{ ?>
         <a href="/student-directory/" class="btn filterBtn ">Clear Filters</a>
        <?php } ?>	

</div> <!-- card.// -->
</form>
	</aside>
<!-- End sidebar col -->
<div class="col-md-9">
<!-- Listing Grids Start -->
<div class="vibebp_members_directory_main">
   
    <?php


/**
 * Parses and prints the Query Requst.
 *
 * @param An Users WP_User_Query response.
 */

$postsPerPage =12;  
$no=12;// total no of author to display
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if($paged==1){
  $offset=0;  
}else {
   $offset= ($paged-1)*$no;
}

 if(!empty($filtersData)){
 $args = array(
 	'role__in'       => explode(", ", $atts['roles']),  
    'number' => $no, 
    'offset' => $offset,
    'orderby' => 'registered', 
    'order' => 'DESC',             
	'meta_query' => array(
		'relation' => 'AND',$meta
	 )
 );


}
if(empty($filtersData)){

/**
 * When and FIlter Query Requst is empty.
 *
 * @param An Users WP_User_Query Request.
 */

$args = array(
	'role__in'       => explode(", ", $atts['roles']),               
	'number' => $no, 
	 'offset' => $offset,
    'orderby' => 'registered', 
    'order' => 'DESC',);	
}
if($_GET['seachmembers']){
$search_term = $_GET['seachmembers'];
$args = array (
   'role__in'   => explode(", ", $atts['roles']), 
    'order' => 'ASC',

    'orderby' => 'display_name',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key'     => 'first_name',
            'value'   => $search_term,
            'compare' => '%LIKE%'
        ),
        array(
            'key'     => 'last_name',
            'value'   => $search_term,
            'compare' => '%LIKE%'
        ),
        array(
            'key' => 'Highest Education',
            'value' => $search_term ,
            'compare' => '%LIKE%'
        ),
        array(
            'key' => 'Vertical',
            'value' => $search_term ,
            'compare' => '%LIKE%'
        ),
        array(
            'key' => 'Professional Skills',
            'value' => $search_term ,
            'compare' => '%LIKE%'
        ),
        array(
            'key' => 'designation_current',
            'value' => $search_term ,
            'compare' => '%LIKE%'
        ),
    )
);
}

$user_query = new WP_User_Query( $args );
$users = $user_query->get_results();

$args = array(
	'role__in'       => explode(", ", $atts['roles']),               
	'number' => $no, 
	 'offset' => $offset,
    'orderby' => 'registered', 
    'order' => 'DESC',);	

$userquery = new WP_User_Query( $args );

$total_users = $user_query->get_total();

if ( ! empty( $user_query->results ) ) { ?>

    <div class="card team-boxed" id="member_data">
        <div class="row people">
            <?php 
                $usercc  = wp_get_current_user();
                $userRolesChk = ( array ) $usercc->roles;
                $termArr = array();
                $loadmoreCount = 0;
                foreach ($users as $key => $bpMembers) {
                	
                	$new_user = get_userdata(  $bpMembers->ID );
                       $first_name = $new_user->first_name;
                       $last_name = $new_user->last_name;
                       $member_type = bp_get_member_type( $bpMembers->ID );
                       $user = new BP_Core_User( $bpMembers->ID );


                       $user_avatar = $user->avatar;
                       //echo $bpMembers->ID.'<br/>';

                	$memberships_users = $wpdb->get_row("SELECT * FROM wpcw_pmpro_memberships_users WHERE user_id='".$bpMembers->ID."' AND status='active'");

                    //if($memberships_users->status=='active'){
                       $term_obj_list = wp_get_post_terms( $bpMembers->ID, 'bp_member_type', array( 'fields' => 'names' ) );

                         if(!empty($term_obj_list)){

                          $memberType = implode(', ', $term_obj_list);
                      }
                                    	

                if(!empty( $term_obj_list)){
                  if($corporateRole=="corporate-elite"){

                  	if (!in_array('administrator', $userRolesChk, true ) ) {


                         if (in_array($memberType, $memberShipTypeElite, true ) ) {

                             include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';

                        $loadmoreCount++; 
                        }
                   }
               }


                   if($corporateRole=="corporate-prime"){

                        if (!in_array('administrator', $userRolesChk, true ) ) {

                         if (in_array($memberType, $memberShipTypePrime, true ) ) {

                        include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        
                       $loadmoreCount++;
                        }
                   }
                }
                 if (!in_array('administrator', $userRolesChk, true ) ) {

                   if($corporateRole=="corporate-basic"){

                         if (in_array($memberType, $memberShipTypeBasic, true ) ) {

                          include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';
                        }

                        $loadmoreCount++;
                   }
               }
                   
                     
                   
                   
              
              //} 
              if (in_array('administrator', $userRolesChk, true ) ) {

                        include WP_PLUGIN_DIR .'/trikona-user-profiles/Frontend/layouts/member-card.php';

                        $loadmoreCount++; 

                        }
              
              }
          }
}else{ ?>

<!-- Warning Alert -->
<div class="alert alert-warning alert-dismissible fade show">
    <strong>Warning!</strong> No Record Found..
</div>
<?php } ?>	
        </div>
    </div>
   
    <div class="row cs-pagination">
        
     <!-- <div class="clearfix"> -->
        <div class="col-md-6">
            <div id="hint" class="hint-text">
                Total :  <b><?php echo $total_users;?></b> entries
            </div>
        </div>
        <div class="col-md-6">
	<div id="pagination" class="pagination">
<?php 		
         $total_user = $user_query->total_users;  
            $total_pages=ceil($total_user/$no);
                    $current_page = max(1, get_query_var('paged'));
               $big = 999999999; // need an unlikely integer
                   echo paginate_links(array(  
	               'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	               'format' => '?paged=%#%',  
                   'current' => $current_page,  
                   'total' => $total_pages,  
                   'prev_text' => __('&laquo; Previous'), // text for previous page
                   'next_text' => __('Next &raquo;'), // text for next page

                )); ?>
	</div>             
	</div>             
	<!-- </div> -->
	</div>

    <!-- End Directory Main -->
	</div>

	</div>
	<!-- Listing Grids End -->
	</div>
            </div>
<?php
} else { ?>

<div class="cs-login-alert">
    <?php echo do_shortcode('[elementor-template id="32314"]'); ?>
</div>


<?php }
$current_user = wp_get_current_user();
 $term_obj_list = get_the_terms($current_user->ID, 'bp_member_type' );
if(!empty($term_obj_list)){
$termArr = array();
foreach ($term_obj_list as $key => $term_obj_value) {
	$termArr[]= $term_obj_value->name;
}

$List = implode(', ', $termArr);
}

  $term_obj_list = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'names' ) );

if(!empty($term_obj_list)){

$List = implode(', ', $term_obj_list);
}

 ?>
<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
 -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
 <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    $(".filter-data-select").select2({
    	 allowClear: true,
        placeholder: function() {
            $(this).data('placeholder');
        },
       
        theme: "classic",
        width: 'resolve',
    });
   });
</script>



<script type="text/javascript">
function seachmembers() {
    var roles = '<?php echo $atts['roles']?>';
    var textval = jQuery("#seachmembers").val();
    jQuery("#load-more").addClass('hide-more-btn');
    jQuery.ajax({
        type: "post",
        dataType: "html",
        url: '<?php echo admin_url( 'admin-ajax.php' );?>',
        data: {
            action: "search_members_directory_filter",
            data: textval,
            roles: roles,
            member_ship: '<?php echo $List;?>'
        },
        success: function(response) {
            jQuery("#member_data").html(response)
            if (response.type == "success") {
                jQuery("#member_data").html(response.vote_count)
            } else {
                //alert("Your vote could not be added")
            }
        }
    })
};


</script>

<style>


</style>
<?php
}


new MembersGridShortCOdes;
}