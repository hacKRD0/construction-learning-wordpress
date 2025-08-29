<?php 
/**
* Template Name: Courses Directory
 */
get_header();
global $wpdb,$bp;
$current_user = wp_get_current_user();
?>

<link href='<?php echo get_stylesheet_directory_uri()?>/dashboard/assets/css/style-dashboard.css'  rel="stylesheet" id="main-style"/>

<!-- <section id="content"> -->
<div class="content-fluid cs-directory-page" style="padding:15px; background-color: #f7f7f7;">
<div class="row" style="padding:50px 0px">

<aside class="col-md-3">
 <form name="directory-filterpage" method="GET"> 	
   <div class="card">
   <article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_1" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Filter Membership</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_1" style="">
			<div class="card-body">
				
				
				 <select class="filter-data-select" name="filtersData[]"  multiple="multiple" data-placeholder="Membership">
				 	<?php
				 	$membership_levels = $wpdb->get_results( "SELECT * FROM wpcw_pmpro_membership_levels");
				 	?>
               
                <div class="filter-col card-header">
                    <div class="checkbox">
                    	<?php
                          $showfiled = array('Professional Silver','Professional Gold','Professional Platinum','Student Rookie','Student Champ','Student Pro');
                         // print_r($showfiled);

                    	 foreach ($membership_levels  as $key => $lebelarr) {
                          if(!empty($_GET['filtersData'])){
                       if (in_array($lebelarr->id, $_GET['filtersData']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						} 
                          if (in_array($lebelarr->name, $showfiled)){
                    	 ?>
                    	
                       <option value="<?php echo $lebelarr->id;?>"  <?php echo $selectTed;?>><?php echo $lebelarr->name;?></option>
                      <?php } }?>
                    </div>
                </div>


               
            </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	
	<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_2" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Category</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_2" style="">
			<div class="card-body">
				
				
				 <select class="filter-data-select" name="catid[]"  multiple="multiple" data-placeholder="Category">
				 	<?php
				 	$terms = get_terms( array(
							    'taxonomy'   => 'course-cat',
							    'orderby' => 'name',
			                     'order' => 'ASC',
							    'hide_empty' => false,
							    'exclude' => 217
							) );

				 	?>
               
                <div class="filter-col card-header">
                    <div class="checkbox">
                    	<?php foreach ($terms  as $key => $lebelarr) {
                          if(!empty($_GET['filtersData'])){
                       if (in_array($lebelarr->term_id, $_GET['filtersData']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						} 

                    	 ?>
                    	
                       <option value="<?php echo $lebelarr->term_id;?>"  <?php echo $selectTed;?>><?php echo $lebelarr->name;?></option>
                      <?php } ?>
                    </div>
                </div>


               
            </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_3" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Level</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_3" style="">
			<div class="card-body">
				
				
				 <select class="filter-data-select" name="level[]"  multiple="multiple" data-placeholder="Level">
				 	<?php
				 	$terms = get_terms( array(
							    'taxonomy'   => 'level',
							    'orderby' => 'name',
			                     'order' => 'ASC',
							    'hide_empty' => true,
							) );

				 	?>
               
                <div class="filter-col card-header">
                    <div class="checkbox">
                    	<?php foreach ($terms  as $key => $lebelarr) {
                          if(!empty($_GET['level'])){
                       if (in_array($lebelarr->term_id, $_GET['level']))
						  {
						 $selectTed= 'selected="selected"';
						  }
						else
						  {
						  $selectTed= '';
						  }
						} 

                    	 ?>
                    	
                       <option value="<?php echo $lebelarr->term_id;?>"  <?php echo $selectTed;?>><?php echo $lebelarr->name;?></option>
                      <?php } ?>
                    </div>
                </div>


               
            </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_4" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Instructor</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_4" style="">
			<div class="card-body">
				
				
				 <select class="filter-data-select" name="instructor[]"  multiple="multiple" data-placeholder="Instructor">
				 	<?php
				 	$args = array(
				    'role'    => 'instructor',
				    'orderby' => 'user_nicename',
				    'order'   => 'ASC'
				);
				$users = get_users( $args );

				 	?>
               
                <div class="filter-col card-header">
                    <div class="checkbox">
                    	<?php foreach ($users  as $key => $usersArr) {
                          if(!empty($_GET['instructor'])){
                       if (in_array($usersArr->ID, $_GET['instructor']))
						  {
						 $instructor= 'selected="selected"';
						  }
						else
						  {
						  $instructor= '';
						  }
						} 

                    	 ?>
                    	
                       <option value="<?php echo $usersArr->ID;?>"  <?php echo $instructor;?>><?php echo $usersArr->first_name;?></option>
                      <?php } ?>
                    </div>
                </div>


               
            </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_5" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Price</h6>
			</a>
		</header>
		<?php $pricelistArr= array('S'=>'Free','H'=>'Paid'); 

		?>
		<div class="filter-content collapse show" id="collapse_5" style="">
			<div class="card-body">
				 <select class="filter-data-select" name="price[]"  multiple="multiple" data-placeholder="Price">
                <div class="filter-col card-header">
                    <div class="checkbox">     
                    <?php foreach ($pricelistArr as $key => $pricelist) {

                    	  if(!empty($_GET['price'])){
	                       if (in_array($key, $_GET['price']))
							  {
							 $priceSel= 'selected="selected"';
							  }
							else
							  {
							  $priceSel= '';
							  }
							} 


                     ?>
                       <option value="<?php echo $key;?>" <?php echo $priceSel;?>><?php echo $pricelist;?></option>
                   <?php } ?>
                    </div>
                </div>


               
            </select>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_7" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Price Range</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_7" style="">
			<div class="card-body">
			<div class="slider-box">
			  <input type="text" name="PriceRangefilter" id="PriceRangefilter" readonly>
			  <div id="price_range" class="slider"></div>
			</div>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_6" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Total Duration of Course</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_6" style="">
			<div class="card-body">
			<div class="slider-box">
			  <input type="text" name="priceRange" id="priceRange" readonly>
			  <div id="price-range" class="slider"></div>
			</div>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->

	<article class="filter-group">
		<header class="card-header">
			<a href="#" data-toggle="collapse" data-target="#collapse_7" aria-expanded="true" class="">
				<i class="icon-control fa fa-chevron-down"></i>
				<h6 class="title">Total number of Students in Course</h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse_7" style="">
			<div class="card-body">
			<div class="slider-box">
			  <input type="text" name="studentRange" id="studentRange" readonly>
			  <div id="student-range" class="slider"></div>
			</div>

			</div> <!-- card-body.// -->
		</div>
	</article> <!-- filter-group  .// -->


	<input type=submit class="filterBtn" value="Apply"><br/>
         <a href="/all-courses/" class="btn filterBtn ">Clear Filters</a>
       

</div> <!-- card.// -->
</form>
	</aside>
<!-- End sidebar col -->
<div class="col-md-9">
<!-- Listing Grids Start -->
<div class="cs_directory_main">
   
    <?php
    $filtersData = $_GET['filtersData'];
	if(!empty($filtersData)){
    $meta = array('relation' => 'AND'); 
    foreach ($filtersData as $key => $value) { 

            array_push($meta,array(
              'key' => 'vibe_pmpro_membership',
              'value' => $value ,
              'compare' => 'like'
            ));    
      }
  }

    

    if ( isset( $_GET['catid'] ) && ! empty( $_GET['catid'] ) ) { 
        $filter[] = array(
            'taxonomy' => 'course-cat',
            'field'    => 'term_id',
            'terms'    => $_GET['catid'],
        );
    }
    if ( isset( $_GET['level'] ) && ! empty( $_GET['level'] ) ) { 
        $filter[] = array(
            'taxonomy' => 'level',
            'field'    => 'term_id',
            'terms'    => $_GET['level'],
        );
    }
    if ( isset( $_GET['instructor'] ) && ! empty( $_GET['instructor'] ) ) { 
        $filter[] = array(
            'key'       => '_sfwd-courses',
            'value'     => '',
            'compare'   => 'LIKE',
        );
    }

    if ( isset( $_GET['price'][0] ) && ! empty( $_GET['price'][0] ) ) { 
        $meta_query[] = array(
            'key'       => 'vibe_course_free',
            'value'     => $_GET['price'][0],
            'compare'   => 'LIKE',
        );
    }

    if ( isset( $_GET['price'][1] ) && ! empty( $_GET['price'][1] ) ) { 
        $meta_query[] = array(
            'key'       => 'vibe_course_free',
            'value'     => $_GET['price'][1],
            'compare'   => 'LIKE',
        );
    }

     if ( isset( $_GET['priceRange'] ) && ! empty( $_GET['priceRange'] ) ) { 
     	$priceRange = explode("-",$_GET['priceRange']);
        $meta_query[] = array(
            'key'       => 'vibe_duration',
            'value'     => $priceRange,
            'type'     => 'numeric',
           'compare'  => 'between'
        );
    }

    if ( isset( $_GET['priceRange'] ) && ! empty( $_GET['priceRange'] ) ) { 
     	$priceRange = explode("-",$_GET['priceRange']);
        $meta_query[] = array(
            'key'       => 'vibe_duration',
            'value'     => $priceRange,
            'type'     => 'numeric',
           'compare'  => 'between'
        );
    }

    if ( isset( $_GET['filtersData'] ) && ! empty( $_GET['filtersData'] ) ) { 
        $meta_query[] = array(
            'key'       => 'vibe_pmpro_membership',
            'value' => '"('. implode('|', $filtersData) .')"',
             'compare' => 'REGEXP',
        );
    }


if(!empty($_GET['instructor'])){
	if(!empty($_GET['instructor'][0])){
			$author_names = array();
			foreach($_GET['instructor'] as $author_id){
				$instructor_name = get_the_author_meta('user_nicename',$author_id);
				$author_names[] = 'cap-'.$instructor_name;
			}

		 $filter[]= array(
				'taxonomy'=>'author',
				'field'=>'slug',
				'terms' => $author_names,
			);
      }       
   }

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

if(!empty($meta_query) || !empty($filter)){  

$args = array(
  'post_type'       => 'course',
  'post_status'     => 'publish',
  'order'           => 'ASC',
  'posts_per_page' => 12,
  'paged' => $paged,
  'meta_query' => array(
                    'relation' =>'AND',
                    $meta_query,

                ),
  'tax_query' => array(
                    'relation' =>'AND',
                    $filter,

                ),
  );
}else{
$args = array(
  'post_type'       => 'course',
  'post_status'     => 'publish',
  'order'           => 'ASC',
  'posts_per_page' => 12,
  'paged' => $paged,
  
  );

}
 

$query = new WP_Query( $args );
 $total_users = $query->max_num_pages;

if( $query->have_posts() )  { ?>

    <div class="cs_directory card team-boxed" id="member_data">
       <div class="row g-4">

            <?php 
                while( $query->have_posts() ) { $query->the_post(); 
                     $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium', false );
                    $vibe_pmpro_membership =  get_post_meta($post->ID,'vibe_pmpro_membership',true);
                    $lebelName=  wp_get_post_terms($post->ID, 'level',  array("fields" => "names"));
                    $vibe_duration =  get_post_meta($post->ID,'vibe_duration',true);
                    $vibe_students =  get_post_meta($post->ID,'vibe_students',true);
                    $vibe_course_duration=   get_post_meta($post->ID,'vibe_course_duration_parameter',true);
                    if($vibe_course_duration==3600){
                      $vibe_course_duration ='Hours';
                    }else
                    if($vibe_course_duration==86400){
                    	$vibe_course_duration ='Days';
                    }else
                    if($vibe_course_duration==604800){
                    	$vibe_course_duration ='Week';
                    }else
                    if($vibe_course_duration==2592000){
                    	$vibe_course_duration ='Month';
                    }else
                    if($vibe_course_duration==31536000){
                    	$vibe_course_duration ='Year';
                    }


                    
                    
                	?>
				<div class="col-sm-6 col-lg-4 col-xl-4">
				<div class="card shadow h-100">
                <?php if(!empty($src[0])) { ?>
				 <img class="card-img-top" src="<?php echo $src[0]; ?>"/>
                <?php } 
                else { ?>
                    <img class="card-img-top" src="<?php echo site_url().'/wp-content/uploads/2022/12/Introduction-to-Construction-Learning-960x720min-460x345.webp' ?>"/>
                    <?php
                }
                ?>
                  <div class="card-body pb-0">       
				<div class="d-flex justify-content-between mb-2">
					<a href="#" class="badge bg-purple bg-opacity-10 text-purple"><?php //echo //$lebelName[0];?></a>
					<!-- <a href="#" class="h6 mb-0"><i class="far fa-heart"></i></a> -->
				</div>
                   
                   	<h5 class="card-title fw-normal"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <hr>
                    <p><?php echo wp_trim_words( get_the_content(), 10 ); // post content?></p>
                    
                    <ul class="list-inline mb-0" style="display:none">
									<li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
									<li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
									<li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
									<li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
									<li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
									<li class="list-inline-item ms-2 h6 fw-light mb-0">4.0/5.0</li>
								</ul>
				   <div class="d-flex justify-content-between">
					  <span class="h6 fw-light mb-0"><i class="far fa-clock text-danger me-2"></i><?php echo  $vibe_duration;?> <?php echo $vibe_course_duration;?></span>
					  <span class="h6 fw-light mb-0" style="display:none"><i class="fas fa-table text-orange me-2"></i><?php echo get_the_date( 'F j, Y' );?> </span>
				   </div>	
				    <hr>
				    <div class="d-flex justify-content-between">			
                    <?php 
                     foreach ($vibe_pmpro_membership as $key => $lebelMem) {
                    $membership_levels = $wpdb->get_results( "SELECT * FROM wpcw_pmpro_membership_levels WHERE id = '$lebelMem'"); 
                    //print_r($membership_levels);
                     
                     foreach ($membership_levels as $key => $lebel) {
                    
                    ?>
                        <span class="h6 fw-light mb-0">
                    <a href="/membership-account/membership-details/" class="course_price_option amount" style="padding:5px;"><strong><?php echo $lebel->name;?></strong></a></span>
                    <?php } }?>
                    </div>
                </div>
           
      
        </div>
    </div>

           
          <?php }
}else{ ?>

<!-- Warning Alert -->
<div class="alert alert-warning alert-dismissible fade show">
    <strong>Warning!</strong> No Record Found..
</div>
<?php } ?>	
        </div>
    </div>
    </div>
   
    <div class="cs_members_directory_pagination" style="display:block;">
     <div class="clearfix">
	  <div id="hint" class="hint-text">Total :  <b><?php echo $query->found_posts;;?></b> entries</div>
	<div id="pagination" class="pagination" style="float: right;">
<?php 		
         $big = 999999999;
     echo paginate_links( array(
          'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
          'format' => '?paged=%#%',
          'current' => max( 1, get_query_var('paged') ),
          'total' => $query->max_num_pages,
          'prev_text' => '&laquo;',
          'next_text' => '&raquo;'
     ) );

            ?>
	</div>             
	</div>
	</div>
	</div>

	</div>
	<!-- Listing Grids End -->
	</div>
 </div>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet" id="jquery-ui-css"> 
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ></script>  
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

$(function() {
  $("#price-range").slider({
    step: 1,
    range: true, 
    min: 0, 
    max: 100, 
    values: [0, 100], 
    slide: function(event, ui)
    {$("#priceRange").val(ui.values[0] + " - " + ui.values[1]);}
  });
  $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values", 1));
  
});

$(function() {
  $("#student-range").slider({
    step: 1,
    range: true, 
    min: 0, 
    max: 100, 
    values: [0, 100], 
    slide: function(event, ui)
    {$("#studentRange").val(ui.values[0] + " - " + ui.values[1]);}
  });
  $("#studentRange").val($("#student-range").slider("values", 0) + " - " + $("#student-range").slider("values", 1));
  
});
$(function() {
  $("#price_range").slider({
    step: 20,
    range: true, 
    min: 0, 
    max: 500, 
    values: [0, 500], 
    slide: function(event, ui)
    {$("#PriceRangefilter").val(ui.values[0] + " - " + ui.values[1]);}
  });
  $("#PriceRangefilter").val($("#price_range").slider("values", 0) + " - " + $("#price_range").slider("values", 1));
  
});
</script>


<?php
get_footer();
?>

<style>
	.d-flex {
    display: -ms-flexbox!important;
    display: flex!important;
    flex-wrap: wrap;
}
	.ui-widget-content { background: #ffc107; }
	.slider-box {width: 90%; margin: 25px auto}
label, input {border: none; display: inline-block; margin-right: -4px; vertical-align: top; width: 30%}
input {width: 70%}
.slider {margin: 25px 0}
	.text-danger{
		color:#fd7e14 !important;
	}
	.text-orange{
		color:#fd7e14 !important;
	}
	.fw-light {
	font-weight: 400 !important;
}
	.me-2 {
	margin-right: 0.5rem !important;
}
	.justify-content-between {
	-webkit-box-pack: justify !important;
	-ms-flex-pack: justify !important;
	justify-content: space-between !important;
}
	.fw-light {
	font-weight: 400 !important;
}
	.me-0 {
	margin-right: 0 !important;
}
.list-inline {
	padding-left: 0;
	list-style: none;
}
	.text-purple {
	--bs-bg-opacity: 1;
	background-color: #e6f8f3 !important;
}

.col-sm-6.col-lg-4.col-xl-4 {
	padding: 10px;
}
  .card.user-card {
    border-top: none;
    -webkit-box-shadow: 0 0 1px 2px rgba(0,0,0,0.05), 0 -2px 1px -2px rgba(0,0,0,0.04), 0 0 0 -1px rgba(0,0,0,0.05);
    box-shadow: 0 0 1px 2px rgba(0,0,0,0.05), 0 -2px 1px -2px rgba(0,0,0,0.04), 0 0 0 -1px rgba(0,0,0,0.05);
    -webkit-transition: all 150ms linear;
    transition: all 150ms linear;
}

.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: none;
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.card .card-header {
    background-color: transparent;
    border-bottom: none;
    padding: 25px;
}

.card .card-header h5 {
    margin-bottom: 0;
    color: #222;
    font-size: 14px;
    font-weight: 600;
    display: inline-block;
    margin-right: 10px;
    line-height: 1.4;
}

.card .card-header+.card-block, .card .card-header+.card-block-big {
    padding-top: 0;
}

.user-card .card-block {
    text-align: center;
}

.card .card-block {
    padding: 15px;
}

.user-card .card-block .user-image {
    position: relative;
    margin: 0 auto;
    display: inline-block;
    padding: 5px;
    width: 110px;
    height: 110px;
}

.user-card .card-block .user-image img {
  z-index: 20;
    position: absolute;
    top: 0;
    left: 0;
    width: 100px;
    height: 100px;
    background-color: #0365b3;
    border-radius: 50%;
}

.img-radius {
    border-radius: 50%;
}

.f-w-600 {
    font-weight: 600;
}

.m-b-10 {
    margin-bottom: 10px;
}

.m-t-25 {
    margin-top: 25px;
}

.m-t-15 {
    margin-top: 15px;
}

.card .card-block p {
    line-height: 1.4;
}

.text-muted {
    color: #919aa3!important;
}

.user-card .card-block .activity-leval li.active {
    background-color: #2ed8b6;
}

.user-card .card-block .activity-leval li {
    display: inline-block;
    width: 15%;
    height: 4px;
    margin: 0 3px;
    background-color: #ccc;
}

.user-card .card-block .counter-block {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff);
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5);
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80);
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a);
}

.m-t-10 {
    margin-top: 10px;
}

.p-20 {
    padding: 20px;
}

.user-card .card-block .user-social-link i {
    font-size: 30px;
}

.text-facebook {
    color: #3B5997;
}

.text-twitter {
    color: #42C0FB;
}

.text-dribbble {
    color: #EC4A89;
}

.user-card .card-block .user-image:before {
    bottom: 0;
    border-bottom-left-radius: 50px;
    border-bottom-right-radius: 50px;
}

/* .user-card .card-block .user-image:after, .user-card .card-block .user-image:before {
    content: "";
    width: 100%;
    height: 48%;
    border: 2px solid #4099ff;
    position: absolute;
    left: 0;
    z-index: 10;
} */

.user-card .card-block .user-image:after {
    top: 0;
    border-top-left-radius: 50px;
    border-top-right-radius: 50px;
}

/* .user-card .card-block .user-image:after, .user-card .card-block .user-image:before {
    content: "";
    width: 100%;
    height: 48%;
    border: 2px solid #4099ff;
    position: absolute;
    left: 0;
    z-index: 10;
} */
h6.title-name {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 8px;
}
p.title-mb-type {
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1.5px;
}
p.title-mb-type {
    background-color: #f1f5f7;
    text-align: center;
    padding: 5px;
}
p.card-content {
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1.5px;
}
.total-exp {
    color: #060606;
    font-weight: 600;
}
p.current-comp {
    color: #060606;
}
.current-comp span {
    color: #a7a7a7;
}
.total-exp span {
    color: #a7a7a7;
}
.team-boxed p {
    color: #060606;
}
.cs_directory_wrapper .cs_directory_header .cs_search input {
    border: none;
    background: none;
    padding: 15px 10px;
    width: 95%;
font-size: 16px;
}
[class^="vicon-"], [class*=" vicon-"] {
    font-size: 18px;
}
.cs_directory_wrapper .cs_directory_header>span {
    display: grid;
    align-items: center;
    width: 100%;
    background-color: #fff;
}
.card.user-card a {
    text-decoration: none;
}
.cs_directory_filter span {
    background-color: #f1f5f7;
    padding: 5px 10px !IMPORTANT;
}
.filter-col card-header {
    position: relative;
    top: 5px;
}
.cs_directory_filter {
    background-color: #f9f9f9;
    margin-bottom: 10px;
    box-shadow: 0 2px 2px #bfbfbf3d;
}
.current-comp {
    height: 35px;
}
.verStatus {
    position: absolute;
    right: -80px;
    top: 0;
}
.verStatus img {
    width: 30%;
}
.vf-user .title-mb-type {
    background-color: #dbffb2;
}
.card.user-card.vf-user {
    box-shadow: 0 0 1px 2px #dbffb2, 0 -2px 1px -2px rgba(0,0,0,0.04), 0 0 0 -1px rgba(0,0,0,0.05);
}
</style>
<style>

	.icon-control {
    margin-top: 5px;
    float: right;
    font-size: 80%;
}
.card-header {
	padding: .75rem 1.25rem !important;
	margin-bottom: 0;
	background-color: rgba(0,0,0,.03) !important;
	border-bottom: 1px solid rgba(0,0,0,.125) !important;
}


.btn-light {
    background-color: #fff;
    border-color: #e4e4e4;
}

.list-menu {
    list-style: none;
    margin: 0;
    padding-left: 0;
}
.list-menu a {
    color: #343a40;
}

.card-product-grid .info-wrap {
    overflow: hidden;
    padding: 18px 20px;
}

[class*='card-product'] a.title {
    color: #212529;
    display: block;
}

.card-product-grid:hover .btn-overlay {
    opacity: 1;
}
.card-product-grid .btn-overlay {
    -webkit-transition: .5s;
    transition: .5s;
    opacity: 0;
    left: 0;
    bottom: 0;
    color: #fff;
    width: 100%;
    padding: 5px 0;
    text-align: center;
    position: absolute;
    background: rgba(0, 0, 0, 0.5);
}
.img-wrap {
    overflow: hidden;
    position: relative;
}
.select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
	top: -5px !important;
	
}
.form-control {
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: none !important;
    margin-bottom: 15px;
}

.form-control:focus {
    border: 1px solid #34495e;
}

.select2.select2-container {
    width: 100% !important;
}

.select2.select2-container .select2-selection {
    border: 1px solid #ccc;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    height: 34px;
    margin-bottom: 15px;
    outline: none !important;
    transition: all .15s ease-in-out;
}

.select2.select2-container .select2-selection .select2-selection__rendered {
    color: #333;
    line-height: 32px;
    padding-right: 33px;
}

.select2.select2-container .select2-selection .select2-selection__arrow {
    background: #f8f8f8;
    border-left: 1px solid #ccc;
    -webkit-border-radius: 0 3px 3px 0;
    -moz-border-radius: 0 3px 3px 0;
    border-radius: 0 3px 3px 0;
    height: 32px;
    width: 33px;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--single {
    background: #f8f8f8;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--single .select2-selection__arrow {
    -webkit-border-radius: 0 3px 0 0;
    -moz-border-radius: 0 3px 0 0;
    border-radius: 0 3px 0 0;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
    border: 1px solid #34495e;
}

.select2.select2-container .select2-selection--multiple {
    height: auto;
    min-height: 34px;
}

.select2.select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
    margin-top: 0;
    height: 32px;
}

.select2.select2-container .select2-selection--multiple .select2-selection__rendered {
    display: block;
    padding: 0 4px;
    line-height: 29px;
}

.select2.select2-container .select2-selection--multiple .select2-selection__choice {
    background-color: #f8f8f8;
    border: 1px solid #ccc;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin: 4px 4px 0 0;
    padding: 0 6px 0 22px;
    height: 24px;
    line-height: 24px;
    font-size: 12px;
    position: relative;
}

.select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
    position: absolute;
    top: 0;
    left: 0;
    height: 22px;
    width: 22px;
    margin: 0;
    text-align: center;
    color: #e74c3c;
    font-weight: bold;
    font-size: 16px;
}

.select2-container .select2-dropdown {
    background: transparent;
    border: none;
    margin-top: -5px;
}

.select2-container .select2-dropdown .select2-search {
    padding: 0;
}

.select2-container .select2-dropdown .select2-search input {
    outline: none !important;
    border: 1px solid #34495e !important;
    border-bottom: none !important;
    padding: 4px 6px !important;
}

.select2-container .select2-dropdown .select2-results {
    padding: 0;
}

.select2-container .select2-dropdown .select2-results ul {
    background: #fff;
    border: 1px solid #34495e;
}

.select2-container .select2-dropdown .select2-results ul .select2-results__option--highlighted[aria-selected] {
    background-color: #3498db;
}
input#seachmembers {
    width: 94%;
}
span#searchUsers {
    position: relative;
    right: 40px;
}
section.cs-directory-page {
    margin: 0 auto;
    max-width: 1300px;
    width: 100%;
}
.cs_directory_filter span {
    background-color: #ff000000 !important;
    padding: 0px 0px !IMPORTANT;
}
.select2.select2-container .select2-selection--multiple {
    height: auto;
    min-height: auto;
    margin-bottom: 0px;
}
.filter-col card-header {
    position: relative;
    top: 5px;
    background-color: #f1f5f7;
}
.cs_directory_filter {
    background-color: #ff0a0a00!important;
    margin-bottom: 10px;
    box-shadow: none;
}
.filter-sidebar{
    background-color:#f1f5f7 !important;
    padding:20px 10px !important;
}
.select2.select2-container .select2-selection--multiple {
    height: auto;
    min-height: auto;
    border: none;
    margin-bottom: 15px;
    border-bottom: 1px solid #2196F3;
    border-radius: 10px;
    margin-top: 5px;
}
.select2.select2-container .select2-selection--multiple .select2-selection__rendered {
    display: block;
    padding: 0 4px;
    line-height: 29px;
    background-color: #fff;
    padding: 10px;
    border-radius: 10px;
}
.select2-container--classic.select2-container--open .select2-dropdown {
    border-color: #5897fb;
    position: relative;
    top: 20px;
}
.select2.select2-container .select2-selection--multiple .select2-selection__choice {
    background-color: #bce8ff;
    border: 1px solid #ccc;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin: 4px 4px 0 0;
    padding: 0 15px 0px;
    height: 30px;
    line-height: 26px;
    font-size: 14px;
    position: relative;
}
.select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
    position: absolute;
    top: -8px!important;
    right: -5px;
    height: 16px;
    width: 16px;
    margin: 0;
    text-align: center;
    color: #ffffff;
    font-weight: bold;
    font-size: 14px;
    line-height: 16px;
    background-color: red!important;
    border-radius: 50px;
    left: auto;
}
.filter-sidebar {
    background-color: #f1f5f7 !important;
    padding: 20px!important;
    border-radius: 10px;
    margin-right: 15px;
    max-width: 23%;
    box-shadow: 0 0 5px #c5c5c5;
}
input.filterBtn {
    width: 100%;
    background-color: #1b3b4c;
    height: 45px;
    border-radius: 10px;
    margin-top: 15px;
    cursor: pointer;
}
input.filterBtn:hover {
    background-color: #f5bb11;
}
input.filterBtn {
    width: 99%;
    background-color: #1b3b4c;
    height: 45px;
    border-radius: 10px;
    margin-top: 15px;
    cursor: pointer;
}
a.btn.filterBtn {
    float: right;
    position: relative;
    border-radius: 10px;
    font-weight: 600;
}

.bg-opacity-10 {
	--bs-bg-opacity: 0.1;
}
.text-danger {
	--bs-text-opacity: 1;
}
.justify-content-between {

	margin-top: 15px;
}
/* Ash css */
.cs-directory-page .card-header {
    background-color: #2b5971!important;
    padding: 10px !important;
}
.cs-directory-page .card-header .title {
    margin-bottom: 0;
}
.cs-directory-page .card-header a {
    color: #fff;
}
.cs-directory-page .filter-content input {
    display: block;
    border: 1px solid #c9c9c9;
    width: 100%;
    border-radius: 10px;
}
.cs-directory-page .slider-box {
    width: auto!important; 
    margin: 0px!important;
}
.cs-directory-page .text-purple {
    --bs-bg-opacity: 1;
    background-color: #000000 !important;
}
.cs-directory-page .card {
    background: none!important;
}
.cs-directory-page .col-sm-6.col-lg-4.col-xl-4 {
    padding: 10px;
    margin-top: 10px;
}
.cs-directory-page .card-body.pb-0 {
    background-color: #fff;
}
.cs-directory-page .card-title.fw-normal a {
    color: #000!important;
    font-weight: 600;
}
.cs-directory-page .badge {
    position: absolute;
    top: 10px;
    right: 10px;
}
.course_price_option.amount {
    color: #000;
    font-size: 13px;
}
.cs-directory-page .col-sm-6.col-lg-4.col-xl-4:hover .card-title.fw-normal a {
    color: #fec000!important;
}
.cs-directory-page .card-header .title {
    margin-bottom: 0;
    font-size: 16px;
    color:#fff;
}
img.card-img-top {
    height: 250px;
    object-fit: cover;
}
.cs-directory-page .text-purple {
    --bs-bg-opacity: 1;
    background-color: #000000 !important;
    color: #ffff;
    height: 22px;
}
.cs-directory-page .filter-content input {
    display: block;
    border: 1px solid #c9c9c9;
    width: 100%;
    border-radius: 10px;
    height: 50px;
    padding: 10px;
}
[type=button], [type=submit], button {
    color: #fff;
}
.select2-container .select2-search--inline{
    float:none;
}

</style>
<?php
