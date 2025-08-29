<?php global $post;

$course = new LLMS_Course( $post );
$instructors = $course->get( 'authors' );

?>

<div class="lms_course">
    <div class="container">
    <article class="course-template-style1">

        <?php if (has_post_thumbnail()) : ?>
            <div class="course-featured-image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>

        <h1 class="course-title"><?php the_title(); ?></h1>

        <div class="course-content">
            <?php the_content(); ?>
        </div>

        <!-- Course Meta -->
        <section class="course-meta">
            <h2><?php esc_html_e('Course Information', 'textdomain'); ?></h2>

            <?php if ( $course->get('length') ) : ?>
                <div class="llms-meta llms-course-length">
                    <p><?php echo wp_kses_post( sprintf( __( 'Estimated Time: <span class="length">%s</span>', 'lifterlms' ), $course->get( 'length' ) ) ); ?></p>
                </div>
            <?php endif; ?>

            <?php if ( $course->get_difficulty() ) : ?>
                <div class="llms-meta llms-difficulty">
                    <p><?php echo wp_kses_post( sprintf( __( 'Difficulty: <span class="difficulty">%s</span>', 'lifterlms' ), $course->get_difficulty() ) ); ?></p>
                </div>
            <?php endif; ?>

            <?php
            $categories = get_the_term_list( $post->ID, 'course_cat', '', ', ', '' );
            if ( $categories ) : ?>
                <div class="llms-meta llms-categories">
                    <p><?php echo __( 'Categories: ', 'lifterlms' ) . $categories; ?></p>
                </div>
            <?php endif; ?>

            <?php
            $tags = get_the_term_list( $post->ID, 'course_tag', '', ', ', '' );
            if ( $tags ) : ?>
                <div class="llms-meta llms-tags">
                    <p><?php echo __( 'Tags: ', 'lifterlms' ) . $tags; ?></p>
                </div>
            <?php endif; ?>

        </section>



        <!-- Continue Course -->
        <div class="course-continue">
            <?php echo do_shortcode('[lifterlms_course_continue course_id="' . get_the_ID() . '"]'); ?>
        </div>

        <!-- Course Instructors -->
        <section class="course-instructors">
            <?php echo do_shortcode('[lifterlms_course_instructors course_id="' . get_the_ID() . '"]'); ?>
        </section>

        <!-- Enroll Button -->
        <div class="course-enroll">
            <?php echo do_shortcode('[enroll_button]'); ?>
        </div>

        <!-- Course Lessons / Syllabus -->
        <section class="course-syllabus">
            <?php echo do_shortcode('[lifterlms_course_syllabus course_id="' . get_the_ID() . '"]'); ?>
        </section>

        <?php //echo do_shortcode('[lifterlms_course_outline course_id="' . get_the_ID() . '" collapse="true" toggles="true"]'); ?>

    </article>
    </div>
</div>