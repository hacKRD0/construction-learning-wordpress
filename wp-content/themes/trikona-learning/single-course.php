<?php get_header(); ?>
<main>
    <div class=" ">
        <div class=" ">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <?php
                // Load template choice or fallback to "style1"
                $template_choice = get_post_meta(get_the_ID(), '_course_template', true);
                if (empty($template_choice)) {
                    $template_choice = 'style1';
                }
                get_template_part('templates/course-templates/course', $template_choice);
                ?>

            <?php endwhile; endif; ?>
        </div>
    </div>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
