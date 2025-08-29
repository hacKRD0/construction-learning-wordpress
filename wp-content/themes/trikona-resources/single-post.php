<?php get_header(); ?>


<?php

if (have_posts()) :
    while (have_posts()) : the_post();

        $design = get_post_meta(get_the_ID(), '_blog_design', true);

        if ($design === 'design2') {
            get_template_part('template-parts/single', 'design2');
        } else {
            get_template_part('template-parts/single', 'design1');
        }

    endwhile;
endif;
?>

<?php get_footer(); ?>
