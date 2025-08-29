<section class="lesson_benner_bg">
    <div class="container">
        <div class="lesson_benner_wrapper">
            <div>
                <h1 class=""><?php the_title(); ?></h1>
                <?php
                $short_desc = get_post_meta(get_the_ID(), '_llms_course_short_description', true);
                if (! empty($short_desc)) {
                    echo '<div class="lessson_short_dec">' . esc_html($short_desc) . '</div>';
                }
                ?>

                <div class="les_created">
                    <?php
                    // Get course instructor name using LifterLMS method
                    $course = llms_get_post(get_the_ID());
                    $instructor_name = '';

                    if ($course && method_exists($course, 'get_instructors')) {
                        $instructors = $course->get_instructors();
                        if ($instructors && !empty($instructors)) {
                            $instructor_names = array();
                            foreach ($instructors as $instructor) {
                                // Instructors are arrays with 'id' and 'label' keys
                                if (isset($instructor['id'])) {
                                    $user_info = get_userdata($instructor['id']);
                                    if ($user_info) {
                                        $instructor_names[] = $user_info->display_name;
                                    }
                                }
                            }
                            if (!empty($instructor_names)) {
                                $instructor_name = implode(', ', $instructor_names);
                            }
                        }
                    }

                    // Display instructor name or fallback
                    if (!empty($instructor_name)) {
                        echo 'Created by ' . esc_html($instructor_name);
                    } else {
                        echo 'Created by Course Instructor';
                    }
                    ?>
                </div>
                <div class="lesdateupdate">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" id="update" fill="#fff">
                            <defs>
                                <path id="a" d="M0 0h24v24H0z"></path>
                            </defs>
                            <path d="M11 8.75v3.68c0 .35.19.68.49.86l3.12 1.85c.36.21.82.09 1.03-.26.21-.36.1-.82-.26-1.03l-2.87-1.71v-3.4c-.01-.4-.35-.74-.76-.74-.41 0-.75.34-.75.75zm10 .75V4.21c0-.45-.54-.67-.85-.35l-1.78 1.78c-1.81-1.81-4.39-2.85-7.21-2.6-4.19.38-7.64 3.75-8.1 7.94C2.46 16.4 6.69 21 12 21c4.59 0 8.38-3.44 8.93-7.88.07-.6-.4-1.12-1-1.12-.5 0-.92.37-.98.86-.43 3.49-3.44 6.19-7.05 6.14-3.71-.05-6.84-3.18-6.9-6.9C4.94 8.2 8.11 5 12 5c1.93 0 3.68.79 4.95 2.05l-2.09 2.09c-.32.32-.1.86.35.86h5.29c.28 0 .5-.22.5-.5z"></path>
                        </svg>

                        <?php
                        // Get the last modified date of the post
                        $last_updated = get_the_modified_date('F j, Y');
                        echo 'Last updated ' . esc_html($last_updated);
                        ?> </span>
                    <div>
                    </div>
                </div>
            </div>
</section>

<section class="lesson_connet_bg">
    <div class="container">
        <div class="leasson2_main_wrapper">
            <article class="course-template-style1">
                <div class="course-content">
                    <?php the_content(); ?>
                </div>

                <!-- Course Instructors -->
                <section class="course-instructors">
                    <?php echo do_shortcode('[lifterlms_course_instructors course_id="' . get_the_ID() . '"]'); ?>
                </section>

                <!-- Course Lessons / Syllabus -->
                <section class="course-syllabus">
                    <?php echo do_shortcode('[lifterlms_course_syllabus course_id="' . get_the_ID() . '"]'); ?>
                </section>

            </article>
            <sidebar class="sidebar_lesson">
                <div class="sidebar_parant">
                    <div class="sidebar_post_thumb">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="course-featured-image">
                                <?php the_post_thumbnail('full'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="lesson_price"> <?php echo do_shortcode('[course_price]'); ?></div>
                    <!-- Course Meta -->
                    <div class="lesson_course_meta">
                        <h2><?php // esc_html_e('Course Information', 'textdomain');
                            ?></h2>
                        <?php echo do_shortcode('[lifterlms_course_meta_info course_id="' . get_the_ID() . '"]'); ?>
                    </div>
                    <?php
                        // Check if user is enrolled in the course
                        $course_id = get_the_ID();
                        $user_id = get_current_user_id();
                        $is_enrolled = false;

                        if ($user_id && function_exists('llms_is_user_enrolled')) {
                            $is_enrolled = llms_is_user_enrolled($user_id, $course_id);
                        }
                        ?>

                        <?php if (!$is_enrolled) : ?>
                            <!-- Show Enroll Button if NOT enrolled -->
                            <div class="lesson_course_enroll">
                                <?php echo do_shortcode('[course_enroll_button]'); ?>
                            </div>
                        <?php else : ?>
                            <!-- Show Progress if enrolled -->
                            <div class="lesson_prograss">
                                <?php echo do_shortcode('[lifterlms_course_continue course_id="' . get_the_ID() . '"]'); ?>
                            </div>
                        <?php endif; ?>

                </div>
            </sidebar>
        </div>
    </div>
</section>