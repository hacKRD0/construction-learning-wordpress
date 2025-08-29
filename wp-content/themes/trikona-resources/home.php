<?php
get_header();
?>

<!-- <section class="blog-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/blog-banner.jpg');"> -->
<section class="blog-banner" style="background-image: url('https://www.constructionlearning.online/resources/wp-content/uploads/sites/5/2025/07/blog-bg.webp')">
    <div class="overlay"></div>
    <div class="container banner-content">
        <h1>Blog</h1>
        <div class="Post_breadcrumb">
            <a href="<?php echo home_url(); ?>">Home</a>
            <span class="brad_divder">/</span>
            <span>Blog</span>
        </div>
    </div>
</section>

<div class="container">
    <div class="blog-container">
        <div class="blog-content">
            <div class="blog_list_wrapper">
                <?php
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                    'paged' => $paged
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="blog-card">
                            <div class="blog-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) {
                                        the_post_thumbnail('medium');
                                    } ?>
                                </a>
                            </div>
                            <div class="blog_content">
                                <div class="blog-meta"><?php echo get_the_date(); ?></div>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <a class="read-more" href="<?php the_permalink(); ?>">Read More</a>
                            </div>
                        </div>
                    <?php endwhile;
                else :
                    echo '<p>No posts found.</p>';
                endif;

                wp_reset_postdata();
                ?>
            </div>

            <!-- Pagination -->
            <div class="blog_pagination">
                <?php
                echo paginate_links(array(
                    'total' => $query->max_num_pages,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                ));
                ?>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="blog-sidebar">
            <?php if (is_active_sidebar('blog-sidebar')) : ?>
                <?php dynamic_sidebar('blog-sidebar'); ?>
            <?php else : ?>
                <div class="cust_widget">
                    <h4>Categories</h4>
                    <ul>
                        <?php wp_list_categories(array(
                            'title_li' => '',
                        )); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>