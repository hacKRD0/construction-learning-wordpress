<?php
/**
 * Template Name: Event Blog Template
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
<style>
.event_container {
    background: #fff;
    border-radius: 16px;
    /* box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07); */
    max-width: 1170px;
    margin: 0 auto;
    padding: 0px 15px;
    margin-top: 40px;
    margin-bottom: 30px;
}
.event_main {
    padding: 0;
    font-size: 1.1rem;
    color: #222;
    line-height: 1.7;
}
.banner-section {
    background-color: #fbe3d3;
    text-align: center;
    padding: 50px 15px;
    font-size: 35px;
    margin: 0;
    margin-bottom: 20px;
}
.banner-section h1 {
    color: #1e1e1e;
    background: transparent;
    font-style: normal;
    font-weight: 400;
    margin: 0;
    font-size: 35px;
    line-height: 1.2;
}
</style>

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