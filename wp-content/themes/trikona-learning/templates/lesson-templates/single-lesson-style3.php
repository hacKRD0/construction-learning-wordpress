<?php /**
 * TEMPLATE 4: single-lesson-interactive.php
 * Interactive lesson template with tabs
 */
defined( 'ABSPATH' ) || exit;
get_header(); ?>

<div class="llms-lesson-interactive">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="lesson-header">
                    <h1><?php the_title(); ?></h1>
                    <?php llms_print_notices(); ?>
                </header>

                <div class="lesson-tabs">
                    <nav class="tab-nav">
                        <button class="tab-btn active" data-tab="content"><?php _e( 'Content', 'textdomain' ); ?></button>
                        <?php
                        $lesson = llms_get_post( get_the_ID() );
                        if ( $lesson && $lesson->has_quiz() ) :
                        ?>
                            <button class="tab-btn" data-tab="quiz"><?php _e( 'Quiz', 'textdomain' ); ?></button>
                        <?php endif; ?>
                        <button class="tab-btn" data-tab="resources"><?php _e( 'Resources', 'textdomain' ); ?></button>
                        <button class="tab-btn" data-tab="notes"><?php _e( 'Notes', 'textdomain' ); ?></button>
                    </nav>

                    <div class="tab-content">
                        <div id="content-tab" class="tab-panel active">
                            <?php the_content(); ?>
                        </div>

                        <?php if ( $lesson && $lesson->has_quiz() ) : ?>
                            <div id="quiz-tab" class="tab-panel">
                                <h3><?php _e( 'Lesson Quiz', 'textdomain' ); ?></h3>
                                <?php echo $lesson->get_quiz_link(); ?>
                            </div>
                        <?php endif; ?>

                        <div id="resources-tab" class="tab-panel">
                            <h3><?php _e( 'Additional Resources', 'textdomain' ); ?></h3>
                            <?php
                            $resources = get_post_meta( get_the_ID(), '_lesson_resources', true );
                            if ( $resources ) {
                                echo wpautop( $resources );
                            } else {
                                echo '<p>' . __( 'No additional resources available for this lesson.', 'textdomain' ) . '</p>';
                            }
                            ?>
                        </div>

                        <div id="notes-tab" class="tab-panel">
                            <h3><?php _e( 'My Notes', 'textdomain' ); ?></h3>
                            <textarea id="lesson-notes" placeholder="<?php _e( 'Take notes here...', 'textdomain' ); ?>"></textarea>
                            <button id="save-notes"><?php _e( 'Save Notes', 'textdomain' ); ?></button>
                        </div>
                    </div>
                </div>

                <div class="lesson-navigation">
                    <?php llms_get_template( 'course/lesson-navigation.php' ); ?>
                </div>

            </article>
        <?php endwhile; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all buttons and panels
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Add active class to clicked button and corresponding panel
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });
});
</script>

<style>
.llms-lesson-interactive .tab-nav {
    display: flex;
    border-bottom: 2px solid #eee;
    margin-bottom: 2rem;
}
.llms-lesson-interactive .tab-btn {
    padding: 1rem 2rem;
    border: none;
    background: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}
.llms-lesson-interactive .tab-btn.active {
    border-bottom-color: #007cba;
    color: #007cba;
}
.llms-lesson-interactive .tab-panel {
    display: none;
}
.llms-lesson-interactive .tab-panel.active {
    display: block;
}
.llms-lesson-interactive #lesson-notes {
    width: 100%;
    min-height: 200px;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>

<?php get_footer(); ?>