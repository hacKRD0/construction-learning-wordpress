<?php
/**
 * BuddyPress - Groups Home
 *
 * @since 3.0.0
 * @version 3.0.0
 */

if ( bp_has_groups() ) :
	while ( bp_groups() ) :
		bp_the_group();
	

global $wpdb;


echo bp_get_group_id();

$args = array(
  'post_type'       => 'group_layout',
  'post_status'     => 'publish',
  'posts_per_page'  => -1,
  'meta_query'      => array(
    array(
      'key'         => 'group_type',
      'value'       => bp_groups_get_group_type(bp_get_group_id()),
      'compare'     => 'LIKE',
    ),
  )
);

$layout = new WP_Query( $args ); 
?>
<div id="vibebp_group"> 
<div id="content" class="content-area">
	<div class="container1">
		<main id="group_<?php echo bp_get_group_id(); ?>">
		<?php
 $group_type = bp_groups_get_group_type(bp_get_group_id());
 $group_id = bp_get_group_id();
switch_to_blog(1);
$results = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."bp_groups` WHERE `id` = '$group_id'");
restore_current_blog();

		if ( $layout->have_posts() ) :
			while ( $layout->have_posts() ) :
				$layout->the_post();
				the_content();
				if(class_exists('\Elementor\Frontend')){
						
				 	$elementorFrontend = new \Elementor\Frontend();
                    $elementorFrontend->enqueue_scripts();
                    $elementorFrontend->enqueue_styles();
                }
				break;
			endwhile;
		endif;
		?>

		</main><!-- #main -->
	</div>
</div><!-- #primary -->
</div>
<?php
endwhile;
endif;?>

