
<?php
/**
 * TEMPLATE 1: single-lesson-video-focused.php
 * Video-focused lesson template
 */
defined( 'ABSPATH' ) || exit;
get_header(); ?>

<div class="llms-lesson-video-focused">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'llms-lesson-content' ); ?>>

                <!-- Video Section -->
                <div class="lesson-video-container">
                    <?php
                    // Get lesson video
                    $video = get_post_meta( get_the_ID(), '_llms_video_embed', true );
                    if ( $video ) {
                        echo '<div class="lesson-video">' . wp_oembed_get( $video ) . '</div>';
                    }
                    ?>
                </div>

                <!-- Lesson Header -->
                <header class="lesson-header test">
                    <h1 class="lesson-title"><?php the_title(); ?></h1>
                    <?php llms_print_notices(); ?>
                </header>

                <!-- Lesson Content -->
                <div class="lesson-content">
                    <?php the_content(); ?>
                </div>
                <div class="lesson-navigation">
                    <?php llms_get_template( 'course/lesson-navigation.php' ); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<style>
.llms-lesson-video-focused .lesson-video-container {
    margin-bottom: 2rem;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}
.llms-lesson-video-focused .lesson-video iframe {
    width: 100%;
    height: 400px;
}
.llms-lesson-video-focused .lesson-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}
</style>

<?php get_footer();