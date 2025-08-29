<?php get_header(); ?>

<?php
// Set a dynamic banner background image (fallback if not set)
$banner_bg = 'https://www.constructionlearning.online/resources/wp-content/uploads/sites/5/2025/07/blog-bg.webp';
?>

<section class="blog-banner" style="background-image: url('<?php echo esc_url($banner_bg); ?>');">
    <div class="overlay"></div>
    <div class="container banner-content">
        <h1>
            <?php
            if (is_category()) {
                single_cat_title();
            } elseif (is_tag()) {
                single_tag_title();
            } elseif (is_author()) {
                echo 'Posts by ' . get_the_author();
            } elseif (is_day() || is_month() || is_year()) {
                echo get_the_archive_title();
            } else {
                echo 'Blog';
            }
            ?>
        </h1>
        <div class="Post_breadcrumb">
            <a href="<?php echo esc_url(home_url()); ?>">Home</a>
            <span class="brad_divder">/</span>
            <span>
                <?php
                if (is_category()) {
                    single_cat_title();
                } elseif (is_tag()) {
                    single_tag_title();
                } elseif (is_author()) {
                    the_post();
                    echo 'Author: ' . get_the_author();
                    rewind_posts();
                } elseif (is_day()) {
                    echo 'Day: ' . get_the_date();
                } elseif (is_month()) {
                    echo 'Month: ' . get_the_date('F Y');
                } elseif (is_year()) {
                    echo 'Year: ' . get_the_date('Y');
                } else {
                    echo 'Blog';
                }
                ?>
            </span>
        </div>
    </div>
</section>

<div class="container">
    <div class="blog-container">
        <div class="blog-content">
            <div class="blog_list_wrapper">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
                <?php endwhile; else : ?>
                    <p>No posts found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="blog_pagination">
                <?php
                echo paginate_links(array(
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
