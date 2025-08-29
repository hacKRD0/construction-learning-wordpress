<?php get_header(); ?>

<!-- Banner Section Start -->
<div class="banner-section">
    <h1><?php the_title(); ?></h1>
</div>
<!-- Banner Section End -->

<div class="job-listing-content container">
  <?php
  while ( have_posts() ) : the_post();
    the_content();
  endwhile;
  ?>
</div>

<?php get_footer(); ?>
