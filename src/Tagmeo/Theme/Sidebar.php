<?php

namespace Tagmeo\Theme;

class Sidebar
{
    public function __construct()
    {
        add_action('widgets_init', function () {
            $config = [
                'before_widget' => '<section class="widget %1$s %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            ];

            register_sidebar([
                'name' => 'Primary',
                'id' => 'sidebar-primary'
            ] + $config);

            register_sidebar([
                'name' => 'Footer',
                'id' => 'sidebar-footer'
            ] + $config);
        });
    }

    public static function displaySidebar()
    {
        static $display;

        isset($display) || $display = apply_filters('tagmeo/displaySidebar', true);

        return $display;
    }
}
