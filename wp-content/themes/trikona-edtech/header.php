<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="container-fluid">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <div class="site-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php endif; ?>

            <?php if ( display_header_text() ) : ?>
                <div class="site-title-description">
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                    </h1>
                    <p class="site-description"><?php bloginfo('description'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Desktop Menu -->
        <nav class="site-navigation show" aria-label="Main Menu">
            <?php
            $current_blog_id = get_current_blog_id();
            if ($current_blog_id !== 1) {
                switch_to_blog(1);
            }

            if (function_exists('trikona_get_menu_location_by_role')) {
                $menu_location_by_role = trikona_get_menu_location_by_role('desktop');
                if (has_nav_menu($menu_location_by_role)) {
                    wp_nav_menu([
                        'theme_location' => $menu_location_by_role,
                        'menu_class'     => 'menu',
                        'container'      => false,
                    ]);
                } else {
                    echo '<!-- ⚠️ No menu assigned to: ' . esc_html($menu_location_by_role) . ' -->';
                }
            } else {
                echo '<!-- ❌ Menu function not defined -->';
            }

            if ($current_blog_id !== 1) {
                restore_current_blog();
            }
            ?>
        </nav>

        <!-- Mobile Toggle Button -->
        <div class="site-navigation-toggle-holder show">
            <button
                type="button"
                class="site-navigation-toggle"
                aria-label="Toggle mobile menu"
                aria-controls="mobile-menu"
                aria-expanded="false"
            >
                <span class="site-navigation-toggle-icon" aria-hidden="true"></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <nav id="mobile-menu" class="site-navigation-dropdown show" aria-label="Mobile Menu" hidden>
            <?php
            if ($current_blog_id !== 1) {
                switch_to_blog(1);
            }

            if (function_exists('trikona_get_menu_location_by_role')) {
                $mob_menu_location_by_role = trikona_get_menu_location_by_role('mobile');
                if (has_nav_menu($mob_menu_location_by_role)) {
                    wp_nav_menu([
                        'theme_location' => $mob_menu_location_by_role,
                        'menu_class'     => 'menu',
                        'container'      => false,
                    ]);
                } else {
                    echo '<!-- ⚠️ No mobile menu assigned to: ' . esc_html($mob_menu_location_by_role) . ' -->';
                }
            } else {
                echo '<!-- ❌ Mobile menu function not defined -->';
            }

            if ($current_blog_id !== 1) {
                restore_current_blog();
            }
            ?>
        </nav>
    </div>
</header>
