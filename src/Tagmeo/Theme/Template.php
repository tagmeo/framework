<?php

namespace Tagmeo\Theme;

class Template
{
    public function __construct()
    {
        add_filter('body_class', function ($classes) {
            $classes = [];

            if (is_single() || is_page() && !is_front_page()) {
                if (!in_array(basename(get_permalink()), $classes)) {
                    $classes[] = basename(get_permalink());
                }
            }

            if (Sidebar::displaySidebar()) {
                $classes[] = 'sidebar-display';
            }

            return $classes;
        }, 10, 1);
    }
}
