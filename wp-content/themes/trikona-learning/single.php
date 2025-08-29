<?php get_header(); ?>
<main>
    <div class="lms_course">
        <div class="container">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="course-featured-image">
                            <?php the_post_thumbnail('full'); ?>
                        </div>
                    <?php endif; ?>

                    <h1><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                </article>
            <?php endwhile; endif; ?>
        </div>
    </div>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
