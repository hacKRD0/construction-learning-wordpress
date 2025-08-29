<?php
function diwp_metabox_mutiple_fields()
{

  add_meta_box(
    'diwp-metabox-multiple-fields',
    'Select Company',
    'diwp_add_multiple_fields',
    'job_listing'
  );
}
add_action('add_meta_boxes', 'diwp_metabox_mutiple_fields');
function diwp_add_multiple_fields()
{
  global $post;
  global $wpdb, $trikona_obj;
  // Get Value of Fields From Database
  $_job_groups = get_post_meta($post->ID, '_job_groups', true);
  /*$sql = "SELECT * FROM wpcw_bp_groups order by name asc";
  $results = $wpdb->get_results($sql);*/

  $args = array(
    'order' => 'ASC',
    'orderby' => 'name',
    'per_page' => -1,
    'group_type' => $trikona_obj->bp_group_type_company,
  );

  $company_groups = $trikona_obj->getBuddyPressGroups($args);
?>
  <div class="row">
    <div class="label"><b>Select Compay</b></div>
    <div class="fields">
      <select name="_job_groups" style="width:50%">
        <option value="">Select Option</option>
        <?php if (!empty($company_groups)) { ?>
          <?php foreach ($company_groups as $company_group) { ?>
            <option value="<?= $company_group->id ?>" <?php if ($company_group->id ==  $_job_groups) {
                                                        echo 'selected';
                                                      } ?>><?= $company_group->name ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <?php
}


function diwp_save_multiple_fields_metabox()
{

  global $post;
  if (isset($_POST["_job_groups"])) :
    update_post_meta($post->ID, '_job_groups', $_POST["_job_groups"]);
  endif;
}

add_action('save_post', 'diwp_save_multiple_fields_metabox');

//Create job listing custom shortcodes

function  jobs_queryshortcode($atts)
{

  $group_id = bp_get_current_group_id();
  global $wpdb;
  $groups  = $wpdb->get_row("SELECT * FROM wpcw_bp_groups WHERE id = $group_id");
  $bp_groups_members = $wpdb->get_results("SELECT user_id FROM wpcw_bp_groups_members WHERE group_id='$group_id' and user_title='Group Mod' or user_title='Group Admin' ");

  if ($wpdb->num_rows > 0) {
    ob_start();
    $groupsAdminArr = array();

    foreach ($bp_groups_members as $key => $bp_groups_members_value) {
      $groupsAdminArr[] = $bp_groups_members_value->user_id;
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

    $query = new WP_Query($args);
    if ($query->have_posts()) {
  ?>
      <ul class="job_listings">
        <!-- loop start -->
        <?php while ($query->have_posts()): $query->the_post();
          $author_id = get_post_field('post_author', get_the_ID());

          $user_info = get_userdata($author_id);
          $first_name = $user_info->first_name;
          $last_name = $user_info->last_name;
          if ($first_name != "") {
            $display_name = $first_name . '  ' . $last_name;
          } else {
            $display_name = get_the_author_meta('display_name', $author_id);
          }


          if (in_array($author_id, $groupsAdminArr, true)) {
            $term_obj_list = get_the_terms(get_the_ID(), 'job_listing_type');
            $_remote_position = get_post_meta(get_the_ID(), '_remote_position', true);
            if ($_remote_position == 1) {
              $_remote_position = '(Remote)';
            } else {
              $_remote_position = '';
            }

        ?>
            <li class="post-1243 job_listing type-job_listing status-publish hentry job-type-full-time job-type-part-time job-type-temporary" data-longitude="" data-latitude="">
              <a href="<?php echo get_permalink(get_the_ID()); ?>">
                <img class="company_logo" src="https://www.construction-world.in/wp-content/plugins/wp-job-manager/assets/images/company.png" alt="">
                <div class="position">
                  <h3><?php the_title(); ?></h3>
                  <div class="company"></div>
                </div>
                <div class="location">
                  <?php echo get_post_meta(get_the_ID(), '_job_location', true); ?> <small><?php echo $_remote_position; ?></small>
                </div>
                <ul class="meta">
                  <?php foreach ($term_obj_list as $key => $job_type) { ?>
                    <li class="job-type  <?php echo  $job_type->slug; ?>"><?php echo  $job_type->name; ?></li>
                  <?php } ?>
                  <li class="date"><time datetime="2023-03-09">Posted <?php echo time_ago(); ?></time></li>
                  <li class="date">Posted By <?php echo $display_name; ?></li>
                </ul>
              </a>
            </li>
      <?php }
        endwhile;
      } ?>
      <!-- loop end -->
      </ul>
  <?php

    $data = ob_get_contents();
    ob_end_clean();
    return $data;
  } else {

    echo "Job listing not found..";
  }
}
add_shortcode('group_jobs_listing', 'jobs_queryshortcode');




function time_ago($type = 'post')
{
  $d = 'comment' == $type ? 'get_comment_time' : 'get_post_modified_time';

  return human_time_diff($d('U'), current_time('timestamp')) . " " . __('ago');
}





add_filter('submit_job_form_fields', 'gma_custom_submit_job_form_fields');

function gma_custom_submit_job_form_fields($fields)
{

  unset($fields['company']['company_name']);
  unset($fields['company']['company_website']);
  unset($fields['company']['company_tagline']);
  unset($fields['company']['company_video']);
  unset($fields['company']['company_twitter']);
  unset($fields['company']['company_logo']);

  return $fields;
}


add_action('single_job_listing_meta_end', 'display_job_salary_data');

function display_job_salary_data()
{
  global $post;

  $salary = get_post_meta($post->ID, '_job_groups', true);

  if ($salary) {
    echo '<li>' . __('Company:') . ' ' . esc_html($salary) . '</li>';
  }
}


add_filter('submit_job_form_fields', 'custom_submit_job_form_fields');

// This is your function which takes the fields, modifies them, and returns them
// You can see the fields which can be changed here: https://github.com/mikejolley/WP-Job-Manager/blob/master/includes/forms/class-wp-job-manager-form-submit-job.php
function custom_submit_job_form_fields($fields)
{
  global $wpdb, $bp;
  $args = array('group_type' => 'company');
  switch_to_blog(1);
  $current_user = wp_get_current_user();
  $userRolesChk = (array) $current_user->roles;
  $table_name = $wpdb->prefix . "bp_groups_members";

  $group_id = 6;
  $query = $wpdb->prepare("
    SELECT meta_value
    FROM {$wpdb->prefix}bp_groups_groupmeta
    WHERE group_id = %d
    AND meta_key = 'group-type'
", $group_id);

  $group_type = $wpdb->get_var($query);
  print_r($group_type);
  //$results = $wpdb->get_row("SELECT * from  $table_name where user_id='$current_user->ID' and  user_title='Group Admin'");
  $results = $wpdb->get_row("SELECT * FROM `wpcw_bp_groups_members` WHERE `user_id` = '$current_user->ID' AND `user_title` IN ('Group Mod','Group Admin','trikona-admin')");
  restore_current_blog();
  //print_r($results );
  $sql = "SELECT * FROM wpcw_bp_groups order by name asc";
  $member_type_objects = $wpdb->get_results($sql);
  $member_types = array();
  if (in_array('administrator', $userRolesChk) || in_array('trikona-admin', $userRolesChk)) {
    foreach ($member_type_objects as $member_type => $mt) {

      //if($results->group_id==$mt->id){
      $member_types[$mt->name] = $mt->name;
      //}
    }

    $member_types = array_merge(array('Select Company' => __('Select Company', 'nouveau')), $member_types);
  } else {

    foreach ($member_type_objects as $member_type => $mt) {
      if ($results->group_id == $mt->id) {
        $member_types[$mt->name] = $mt->name;
      }
    }
  }

  error_log('Current User ID: ' . $current_user->ID);
  error_log('User Roles: ' . print_r($userRolesChk, true));
  error_log('Group Results: ' . print_r($results, true));
  error_log('Companies: ' . print_r($member_types, true));

  //print_r($member_types);
  $fields['job']['job_groups'] = array(
    'label'       => __('Company', 'job_manager'),
    'type'        => 'select',
    'required'    => true,
    'options'    => $member_types,
    'priority'    => 7
  );
  return $fields;
}

add_action('rest_api_init', function () {
  // Existing jobs endpoint
  register_rest_route('jobboard/v1', '/jobs', array(
    'methods' => 'GET',
    'callback' => 'get_job_listings',
  ));

  // New: Job Types endpoint
  register_rest_route('jobboard/v1', '/job-types', array(
    'methods' => 'GET',
    'callback' => 'get_job_types',
  ));

  // New: Job Tags endpoint
  register_rest_route('jobboard/v1', '/job-tags', array(
    'methods' => 'GET',
    'callback' => 'get_job_tags',
  ));
});

function get_job_types(WP_REST_Request $request)
{
  $terms = get_terms([
    'taxonomy'   => 'job_listing_type',
    'hide_empty' => false, // change to true if you only want types with jobs
  ]);

  $data = [];
  foreach ($terms as $term) {
    $data[] = [
      'id'          => $term->term_id,
      'name'        => $term->name,
      'slug'        => $term->slug,
      'count'       => $term->count, // number of jobs with this type
      'description' => $term->description,
    ];
  }
  return $data;
}

function get_job_tags(WP_REST_Request $request)
{
  $terms = get_terms([
    'taxonomy'   => 'job_listing_tag',
    'hide_empty' => false,
  ]);

  $data = [];
  foreach ($terms as $term) {
    $data[] = [
      'id'          => $term->term_id,
      'name'        => $term->name,
      'slug'        => $term->slug,
      'count'       => $term->count,
      'description' => $term->description,
    ];
  }
  return $data;
}


function get_job_listings(WP_REST_Request $request) {
    $args = [
        'post_type'      => 'job_listing',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
    ];

    $tax_query = [];

    // Filter by tags
    if ($tags = $request->get_param('tags')) {
        $terms = array_map('sanitize_title', explode(',', $tags));
        $tax_query[] = [
            'taxonomy' => 'job_listing_tag',
            'field'    => 'slug',
            'terms'    => $terms,
        ];
    }

    // Filter by job type
    if ($types = $request->get_param('type')) {
        $terms = array_map('sanitize_title', explode(',', $types));
        $tax_query[] = [
            'taxonomy' => 'job_listing_type',
            'field'    => 'slug',
            'terms'    => $terms,
        ];
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = count($tax_query) > 1
            ? ['relation' => 'AND'] + $tax_query
            : $tax_query;
    }

    $jobs = get_posts($args);
    $data = [];

    foreach ($jobs as $job) {
        $data[] = [
            'id'        => $job->ID,
            'title'     => get_the_title($job->ID),
            'slug'      => $job->post_name,
            'link'      => get_permalink($job->ID),
            'description' => apply_filters('the_content', $job->post_content),
            'location'    => get_post_meta($job->ID, '_job_location', true),
            'expires'     => get_post_meta($job->ID, '_job_expires', true),
            'deadline'    => get_post_meta($job->ID, '_application_deadline', true),
            'application' => get_post_meta($job->ID, '_application', true),
            'company' => [
                'name'    => get_post_meta($job->ID, '_company_name', true),
                'website' => get_post_meta($job->ID, '_company_website', true),
                'tagline' => get_post_meta($job->ID, '_company_tagline', true),
                'twitter' => get_post_meta($job->ID, '_company_twitter', true),
                'video'   => get_post_meta($job->ID, '_company_video', true),
            ],
            'salary' => [
                'text'     => get_post_meta($job->ID, '_job_salary', true),
                'currency' => get_post_meta($job->ID, '_job_salary_currency', true),
                'unit'     => get_post_meta($job->ID, '_job_salary_unit', true),
            ],
            'tags' => wp_get_post_terms($job->ID, 'job_listing_tag', ['fields' => 'names']),
            'types' => wp_get_post_terms($job->ID, 'job_listing_type', ['fields' => 'names']),
            'posted_at' => get_post_time('c', true, $job->ID),
            'modified'  => get_post_modified_time('c', true, $job->ID),
        ];
    }

    return $data;
}




  ?>