<!-- Banner Section -->
<?php
/**
 *
 *  Default Blog detial page design
 *
 */
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

// fallback image if no featured image set
// if (empty($featured_img_url)) {
//     $featured_img_url = 'https://construction-world.org/wp-content/uploads/2024/03/annie-spratt-QckxruozjRg-unsplash.jpg'; // Replace this with your own fallback image URL
// }
?>


<!-- Main Content -->
<main id="primary" class="site-main blog_container">
    <div class="container">
        <div class="Post_details_row_wrapper">
            <div class="Post_details_wrapper Post_designe_seconde">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                     <h1><?php the_title(); ?></h1>
                    <div class="post_author_meta">
                        <div class="author-info">
                            <?php echo get_avatar(get_the_author_meta('ID'), 40); ?>
                            <div class="author-text">
                                <small>Writer</small>
                                <strong class="author_name"><?php the_author(); ?></strong>
                            </div>
                        </div>
                        <span class="dot-separator">•</span>
                        <div class="post-date">
                            <?php echo get_the_date('d M Y'); ?>
                        </div>
                    </div>
                    <?php if ( $featured_img_url ) : ?>
                        <img class="post_img" src="<?php echo esc_url($featured_img_url); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <footer class="entry-footer">
                        <div class="post-tags">
                            <span><?php the_tags('<b> Tags: </b> ', ', '); ?></span>
                        </div>
                    </footer>
                </article>

                <div class="post_comment_section">
                    <?php
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <div class="blog_sideabr_sticky">
                    <div class="cust_widget">
                        <h4>Categories</h4>
                        <ul>
                            <?php wp_list_categories(array(
                                'title_li' => '',
                            )); ?>
                        </ul>
                    </div>

                    <div class="cust_widget">
                        <h4>Recent Posts</h4>
                        <ul>
                            <?php
                            $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 5,
                                'post_status' => 'publish'
                            ));
                            foreach ($recent_posts as $post) : ?>
                                <li>
                                    <a href="<?php echo get_permalink($post['ID']); ?>"><?php echo $post['post_title']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>