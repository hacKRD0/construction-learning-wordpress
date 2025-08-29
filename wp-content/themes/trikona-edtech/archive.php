<?php get_header(); ?>
<main>
    <h2><?php the_archive_title(); ?></h2>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php the_excerpt(); ?></p>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>