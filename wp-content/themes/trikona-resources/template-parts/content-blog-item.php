<article id="post-<?php the_ID(); ?>" <?php post_class('blog-item'); ?>>
    <a href="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <div class="blog-thumbnail">
                <?php the_post_thumbnail('medium'); ?>
            </div>
        <?php endif; ?>
        <div class="blog-content">
            <h2 class="blog-title"><?php the_title(); ?></h2>
            <p class="blog-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            <span class="blog-date"><?php echo get_the_date(); ?></span>
        </div>
    </a>
</article>