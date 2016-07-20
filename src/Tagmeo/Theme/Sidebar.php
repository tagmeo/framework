<?php

namespace Tagmeo\Theme;

class Sidebar
{
    public function __construct()
    {
        add_action('widgets_init', function () {
            register_sidebar([
                'name' => 'Primary',
                'id' => 'sidebar-primary',
                'before_widget' => '<section class="widget %1$s %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3>',
                'after_title' => '</h3>',
            ]);

            register_sidebar([
                'name' => 'Footer',
                'id' => 'sidebar-footer',
                'before_widget' => '<section class="widget %1$s %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3>',
                'after_title' => '</h3>',
            ]);
        });
    }

    public static function displaySidebar()
    {
        static $display;

        isset($display) || $display = !in_array(true, [
            is_404(),
            is_front_page()
        ]);

        return apply_filters('tagmeo/display_sidebar', $display);
    }
}
