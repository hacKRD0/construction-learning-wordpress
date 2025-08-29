<?php
/**
 * Template Name: Common Career Template
 * Description: A reusable page template with common layout.
 */

get_header(); // Load the header

// Define the two page slugs or URLs you want to check
// $allowed_pages = [
//     'manage-webinars',
//     'manage-registrations',
//     'webinars',
//     'tags',
//     'my-bookings',
//     'categories'
// ];

// Get the current page slug
global $post;
$current_slug = $post->post_name;

?>


<!-- Banner Section Start -->
<div class="banner-section">
    <h1><?php the_title(); ?></h1>
</div>
<!-- Banner Section End -->


<main id="" class="site-main container">
    <div class="event_container">
        <div class="event_main">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </div>
</main>

<?php
get_footer(); // Load the footer