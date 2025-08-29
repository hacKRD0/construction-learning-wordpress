<?php
$obj = new Trikona();

global $wpdb;

switch_to_blog(1);

$active_user_group_id = get_query_var( 'active_user_group_id' );

$bp_group = $obj->getBPGroups(['id' => $active_user_group_id]);

$job_listing_post_type = $obj->job_listing_post_type;

$args = array('post_type'       => $job_listing_post_type,'post_status' => array('expired','publish'),'posts_per_page'  => -1,
  'meta_query'      => array(
    array(
      'key'         => '_job_groups',
      'value'       => $bp_group->name,
      'compare'     => '=',
    ),
  )
);

$args1 = array('post_type'       => $job_listing_post_type,'post_status' => array('publish'),'posts_per_page'  => -1,
  'meta_query'      => array(
    array(
      'key'         => '_job_groups',
      'value'       => $bp_group->name,
      'compare'     => '=',
    ),
  )
);

// Switch to the jobs site context in a multisite network
switch_to_blog($obj->jobs_site_blog_id);
$jobs = new WP_Query( $args );
$jobs1 = new WP_Query( $args1 );
// Restore original blog context (important for multisite)
restore_current_blog();

$total_member_count  = groups_get_groupmeta( $bp_groups_members->group_id, 'total_member_count' ,true );
restore_current_blog();

 ?>

<div class="row">
    <div class="col-lg-4 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor1)"><i class="fas fa-users fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor1)">
          <div class="circle-tile-description text-faded"> Total Jobs</div>
          <div class="circle-tile-number text-faded "><?= $jobs->found_posts ?></div>
        </div>
      </div>
    </div>
     
    <div class="col-lg-4 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor2)"><i class="fas fa-graduation-cap fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor2)">
          <div class="circle-tile-description text-faded"> Active Jobs </div>
          <div class="circle-tile-number text-faded "><?= $jobs1->found_posts ?></div>
        </div>
      </div>
    </div> 
    <div class="col-lg-4 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor3)"><i class="fas fa-money-bill-alt fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor3)">
          <div class="circle-tile-description text-faded"> My Credit Balance </div>
          <div class="circle-tile-number text-faded "><?= do_shortcode('[mycred_my_balance type=trikona_credit]') ?></div>
        </div>
      </div>
    </div>
   </div>  