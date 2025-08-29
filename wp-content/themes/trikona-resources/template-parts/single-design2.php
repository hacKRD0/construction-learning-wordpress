<?php

/**
 *
 * Version 1 Design for blog post
 *
 */

//  banner section

$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

// fallback image if no featured image set
// if (empty($featured_img_url)) {
//     $featured_img_url = 'https://yourdomain.com/path/to/default.jpg'; // Replace this with your own fallback image URL
// }
?>
<section class="blog-banner" style="background-image: url('<?php echo esc_url($featured_img_url); ?>'); background-position: center;">
    <div class="overlay"></div>
    <div class="container">
        <div class="banner-content">
            <h1><?php the_title(); ?></h1>
            <div class="Post_breadcrumb">
                <a href="<?php echo home_url(); ?>">Home</a>
                <span class="brad_divder">/</span>
                <?php the_title(); ?>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main id="primary" class="site-main blog_container">
    <div class="container">
        <div class="Post_details_row_wrapper">
            <div class="Post_details_wrapper">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php if ( $featured_img_url ) : ?>
                        <img class="post_img" src="<?php echo esc_url($featured_img_url); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>

                    <div class="cv-post-meta cv-meta-icon-show post_meta">
                        <span class="cv-post-date cv-post-meta-item" itemprop="datePublished">
                            <span class="cv-post-author-wrapper">
                                <svg  width="23" height="23"  version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 256" enable-background="new 0 0 256 256" xml:space="preserve">
                                    <metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>
                                    <g><g><path fill="#ffc000" d="M69,69c0,32.6,26.4,59,59,59c32.6,0,59-26.4,59-59c0-32.6-26.4-59-59-59C95.4,10,69,36.4,69,69z"/><path fill="#ffc000" d="M22.3,216.3c0,0.1,0,0.1,0,0.2C22.3,216.5,22.3,216.4,22.3,216.3z"/><path fill="#ffc000" d="M22.3,215.9c0,0.1,0,0.2,0,0.3C22.3,216.1,22.3,216,22.3,215.9z"/><path fill="#ffc000" d="M22.3,215.9L22.3,215.9C22.3,215.9,22.3,215.9,22.3,215.9z"/><path fill="#ffc000" d="M233.7,216.6C233.7,216.6,233.7,216.6,233.7,216.6C233.7,216.6,233.7,216.6,233.7,216.6z"/><path fill="#ffc000" d="M233.7,216.5c0,0,0-0.1,0-0.2C233.7,216.3,233.7,216.4,233.7,216.5z"/><path fill="#ffc000" d="M22.3,216.6C22.3,216.6,22.3,216.7,22.3,216.6C22.3,216.6,22.3,216.6,22.3,216.6z"/><path fill="#ffc000" d="M181.5,140.5l-4.9,0c-1.7,0-4.3,0.6-5.8,1.4c0,0-3.9,2.2-7.4,3.8c-10.5,4.6-22.6,7.2-35.4,7.2c-12.7,0-24.7-2.6-35.2-7.1c-3.6-1.5-7.5-3.8-7.5-3.8c-1.5-0.8-4.1-1.5-5.8-1.5l-4.8,0c-19.3,2.6-39.6,19.7-45.4,38.2c0,0-6.2,12.5-7.1,37.1c0,3.2,2.5,7.3,5.8,9c0,0,32.9,21.1,99.8,21.1c66.9,0,99.8-21.1,99.8-21.1c3.3-1.8,5.9-6.3,5.8-9.1c-0.8-24.5-6.9-36.5-6.9-36.5C221.4,160.6,200.8,143.4,181.5,140.5z"/><path fill="#ffc000" d="M233.7,215.9c0-0.1,0-0.1,0-0.1C233.7,215.9,233.7,215.9,233.7,215.9z"/><path fill="#ffc000" d="M233.7,216.3c0-0.1,0-0.1,0-0.2C233.7,216.1,233.7,216.2,233.7,216.3z"/></g></g>
                                    </svg>
                            <span class="cv-post-author-name cv-post-meta-item" itemprop="author">By: <?php the_author(); ?></span>
                            </span>

                            <span class="bullet_div">&#8226;</span> 
                            <span class="cv_post_date">                                 
                                <svg width="23" height="23" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.81184 1.875C5.81184 1.72582 5.75258 1.58274 5.64709 1.47725C5.5416 1.37176 5.39853 1.3125 5.24934 1.3125C5.10016 1.3125 4.95709 1.37176 4.8516 1.47725C4.74611 1.58274 4.68684 1.72582 4.68684 1.875V3.06C3.60684 3.14625 2.89884 3.35775 2.37834 3.879C1.85709 4.3995 1.64559 5.10825 1.55859 6.1875H16.4401C16.3531 5.1075 16.1416 4.3995 15.6203 3.879C15.0998 3.35775 14.3911 3.14625 13.3118 3.05925V1.875C13.3118 1.72582 13.2526 1.58274 13.1471 1.47725C13.0416 1.37176 12.8985 1.3125 12.7493 1.3125C12.6002 1.3125 12.4571 1.37176 12.3516 1.47725C12.2461 1.58274 12.1868 1.72582 12.1868 1.875V3.00975C11.6881 3 11.1286 3 10.4993 3H7.49934C6.87009 3 6.31059 3 5.81184 3.00975V1.875Z" fill="#ffc000"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 9C1.5 8.37075 1.5 7.81125 1.50975 7.3125H16.4902C16.5 7.81125 16.5 8.37075 16.5 9V10.5C16.5 13.3282 16.5 14.7427 15.621 15.621C14.742 16.4992 13.3282 16.5 10.5 16.5H7.5C4.67175 16.5 3.25725 16.5 2.379 15.621C1.50075 14.742 1.5 13.3282 1.5 10.5V9ZM12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#ffc000"/>
                                </svg>
                              <?php echo get_the_date(); ?>
                            </span>
                        </span>
                    </div>

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