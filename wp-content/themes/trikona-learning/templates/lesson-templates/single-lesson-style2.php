<?php
/**
 * TEMPLATE 2 : single-lesson-text-heavy.php
 * Text-heavy lesson template with sidebar showing all lessons
 */
defined( 'ABSPATH' ) || exit;
get_header(); ?>

<div class="llms-lesson-text-heavy">
    <div class="container">
        <div class="lesson-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <!-- Main Content -->
                <main class="lesson-main">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="lesson-header">
                            <h1 class="lesson-title"><?php the_title(); ?></h1>
                            <div class="lesson-meta">
                                <?php
                                $lesson = llms_get_post( get_the_ID() );
                                if ( $lesson ) {
                                    $length = $lesson->get( 'length' );
                                    if ( $length ) {
                                        echo '<span class="lesson-length"><i class="fa fa-clock"></i> ' . esc_html( $length ) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                            <?php llms_print_notices(); ?>
                        </header>

                        <div class="lesson-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Quiz if available -->
                        <?php if ( $lesson && $lesson->has_quiz() ) : ?>
                            <div class="lesson-quiz">
                                <h3><?php _e( 'Lesson Quiz', 'textdomain' ); ?></h3>
                                <?php echo $lesson->get_quiz_link(); ?>
                            </div>
                        <?php endif; ?>

                        <div class="lesson-navigation">
                            <?php llms_get_template( 'course/lesson-navigation.php' ); ?>
                        </div>
                    </article>
                </main>

                <!-- Sidebar -->
                <aside class="lesson-sidebar">
                    <!-- Lesson Progress -->
                    <div class="lesson-progress-widget">
                        <h4><?php _e( 'Your Progress', 'textdomain' ); ?></h4>
                        <?php
                        $course_id = llms_get_post_parent_course( get_the_ID() );
                        if ( $course_id ) {
                            $course = llms_get_post( $course_id );
                            $student = llms_get_student();
                            if ( $student && $course ) {
                                $progress = $student->get_progress( $course_id );
                                echo '<div class="progress-bar">';
                                echo '<div class="progress-fill" style="width: ' . esc_attr( $progress ) . '%"></div>';
                                echo '</div>';
                                echo '<p class="progress-text">' . esc_html( $progress ) . '% ' . __( 'Complete', 'textdomain' ) . '</p>';
                            }
                        }
                        ?>
                    </div>

                    <!-- All Lessons List -->
                    <div class="course-lessons-list">
                        <h4><?php _e( 'Course Lessons', 'textdomain' ); ?></h4>
                        <?php
                        $course_id = llms_get_post_parent_course( get_the_ID() );
                        if ( $course_id ) {
                            $course = llms_get_post( $course_id );
                            $student = llms_get_student();
                            $current_lesson_id = get_the_ID();

                            // Get all sections and lessons
                            $sections = $course->get_sections( 'posts' );

                            if ( $sections ) {
                                echo '<div class="lessons-accordion">';

                                foreach ( $sections as $section ) {
                                    $lessons = $section->get_lessons( 'posts' );

                                    if ( $lessons ) {
                                        // Check if current lesson is in this section
                                        $current_section = false;
                                        foreach ( $lessons as $lesson_post ) {
                                            if ( $lesson_post->ID == $current_lesson_id ) {
                                                $current_section = true;
                                                break;
                                            }
                                        }

                                        echo '<div class="section-group ' . ( $current_section ? 'current-section' : '' ) . '">';
                                        echo '<h5 class="section-title">' . esc_html( $section->get( 'title' ) ) . '</h5>';
                                        echo '<ul class="lessons-list">';

                                        foreach ( $lessons as $lesson_post ) {
                                            $lesson_obj = llms_get_post( $lesson_post->ID );
                                            $is_current = ( $lesson_post->ID == $current_lesson_id );
                                            $is_completed = false;
                                            $is_available = true;

                                            // Check if student has access and completion status
                                            if ( $student ) {
                                                $is_completed = $student->is_complete( $lesson_post->ID, 'lesson' );
                                                $is_available = $student->is_lesson_accessible( $lesson_post->ID );
                                            }

                                            $lesson_classes = array( 'lesson-item' );
                                            if ( $is_current ) $lesson_classes[] = 'current-lesson';
                                            if ( $is_completed ) $lesson_classes[] = 'completed-lesson';
                                            if ( ! $is_available ) $lesson_classes[] = 'locked-lesson';

                                            echo '<li class="' . implode( ' ', $lesson_classes ) . '">';

                                            if ( $is_available && ! $is_current ) {
                                                echo '<a href="' . get_permalink( $lesson_post->ID ) . '">';
                                            }

                                            echo '<span class="lesson-status">';
                                            if ( $is_completed ) {
                                                echo '<i class="fa fa-check-circle"></i>';
                                            } elseif ( $is_current ) {
                                                echo '<i class="fa fa-play-circle"></i>';
                                            } elseif ( ! $is_available ) {
                                                echo '<i class="fa fa-lock"></i>';
                                            } else {
                                                echo '<i class="fa fa-circle-o"></i>';
                                            }
                                            echo '</span>';

                                            echo '<span class="lesson-title">' . esc_html( $lesson_post->post_title ) . '</span>';

                                            // Add lesson length if available
                                            if ( $lesson_obj ) {
                                                $lesson_length = $lesson_obj->get( 'length' );
                                                if ( $lesson_length ) {
                                                    echo '<span class="lesson-duration">' . esc_html( $lesson_length ) . '</span>';
                                                }
                                            }

                                            if ( $is_available && ! $is_current ) {
                                                echo '</a>';
                                            }

                                            echo '</li>';
                                        }

                                        echo '</ul>';
                                        echo '</div>';
                                    }
                                }

                                echo '</div>';
                            } else {
                                // Fallback: Get lessons directly if no sections
                                $lessons = $course->get_lessons( 'posts' );
                                if ( $lessons ) {
                                    echo '<ul class="lessons-list simple-list">';

                                    foreach ( $lessons as $lesson_post ) {
                                        $lesson_obj = llms_get_post( $lesson_post->ID );
                                        $is_current = ( $lesson_post->ID == $current_lesson_id );
                                        $is_completed = false;
                                        $is_available = true;

                                        if ( $student ) {
                                            $is_completed = $student->is_complete( $lesson_post->ID, 'lesson' );
                                            $is_available = $student->is_lesson_accessible( $lesson_post->ID );
                                        }

                                        $lesson_classes = array( 'lesson-item' );
                                        if ( $is_current ) $lesson_classes[] = 'current-lesson';
                                        if ( $is_completed ) $lesson_classes[] = 'completed-lesson';
                                        if ( ! $is_available ) $lesson_classes[] = 'locked-lesson';

                                        echo '<li class="' . implode( ' ', $lesson_classes ) . '">';

                                        if ( $is_available && ! $is_current ) {
                                            echo '<a href="' . get_permalink( $lesson_post->ID ) . '">';
                                        }

                                        echo '<span class="lesson-status">';
                                        if ( $is_completed ) {
                                            echo '<i class="fa fa-check-circle"></i>';
                                        } elseif ( $is_current ) {
                                            echo '<i class="fa fa-play-circle"></i>';
                                        } elseif ( ! $is_available ) {
                                            echo '<i class="fa fa-lock"></i>';
                                        } else {
                                            echo '<i class="fa fa-circle-o"></i>';
                                        }
                                        echo '</span>';

                                        echo '<span class="lesson-title">' . esc_html( $lesson_post->post_title ) . '</span>';

                                        if ( $lesson_obj ) {
                                            $lesson_length = $lesson_obj->get( 'length' );
                                            if ( $lesson_length ) {
                                                echo '<span class="lesson-duration">' . esc_html( $lesson_length ) . '</span>';
                                            }
                                        }

                                        if ( $is_available && ! $is_current ) {
                                            echo '</a>';
                                        }

                                        echo '</li>';
                                    }

                                    echo '</ul>';
                                }
                            }
                        }
                        ?>
                    </div>

                    <!-- Course Info -->
                    <?php if ( $course_id ) : ?>
                        <div class="course-info">
                            <h4><?php _e( 'Course Info', 'textdomain' ); ?></h4>
                            <?php
                            $course = llms_get_post( $course_id );
                            if ( $course ) {
                                echo '<p class="course-title">' . esc_html( $course->get( 'title' ) ) . '</p>';

                                $total_lessons = count( $course->get_lessons( 'ids' ) );
                                if ( $total_lessons ) {
                                    echo '<p class="total-lessons">' . sprintf( _n( '%d Lesson', '%d Lessons', $total_lessons, 'textdomain' ), $total_lessons ) . '</p>';
                                }

                                // Course length
                                $course_length = $course->get( 'length' );
                                if ( $course_length ) {
                                    echo '<p class="course-length">' . esc_html( $course_length ) . '</p>';
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </aside>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<style>
.llms-lesson-text-heavy .lesson-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.llms-lesson-text-heavy .lesson-content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.llms-lesson-text-heavy .lesson-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.llms-lesson-text-heavy .lesson-sidebar > div {
    background: #f9f9f9;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.llms-lesson-text-heavy .lesson-sidebar h4 {
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
    color: #333;
    border-bottom: 2px solid #007cba;
    padding-bottom: 0.5rem;
}

/* Progress Bar */
.progress-bar {
    width: 100%;
    height: 20px;
    background: #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #007cba, #005a87);
    transition: width 0.3s ease;
}

.progress-text {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
    text-align: center;
}

/* Lessons List */
.lessons-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.section-group {
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #444;
    margin: 0 0 0.8rem 0;
    padding: 0.5rem 0;
    border-bottom: 1px solid #ddd;
}

.current-section .section-title {
    color: #007cba;
}

.lesson-item {
    display: flex;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s ease;
}

.lesson-item:last-child {
    border-bottom: none;
}

.lesson-item a {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    width: 100%;
}

.lesson-item a:hover {
    color: #007cba;
}

.lesson-status {
    margin-right: 0.8rem;
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.lesson-title {
    flex: 1;
    font-size: 0.9rem;
    line-height: 1.4;
}

.lesson-duration {
    font-size: 0.8rem;
    color: #888;
    margin-left: 0.5rem;
}

/* Lesson States */
.current-lesson {
    background-color: #e3f2fd;
    border-radius: 4px;
    padding: 0.8rem;
    margin: 0.2rem 0;
}

.current-lesson .lesson-status {
    color: #007cba;
}

.current-lesson .lesson-title {
    font-weight: 600;
    color: #007cba;
}

.completed-lesson .lesson-status {
    color: #4caf50;
}

.completed-lesson .lesson-title {
    color: #666;
}

.locked-lesson {
    opacity: 0.6;
}

.locked-lesson .lesson-status {
    color: #999;
}

.locked-lesson .lesson-title {
    color: #999;
}

/* Course Info */
.course-info .course-title {
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #333;
}

.course-info p {
    margin: 0.3rem 0;
    font-size: 0.9rem;
    color: #666;
}

/* Responsive */
@media (max-width: 768px) {
    .llms-lesson-text-heavy .lesson-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .llms-lesson-text-heavy .lesson-sidebar {
        position: static;
        order: -1;
    }
}

/* Font Awesome fallback (if not loaded) */
.fa:before {
    font-family: 'Font Awesome 5 Free', 'FontAwesome', sans-serif;
}
</style>

<?php get_footer(); ?>