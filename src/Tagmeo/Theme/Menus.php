<?php

namespace Tagmeo\Theme;

class Menus extends Navigation
{
    public function __construct()
    {
        add_filter('wp_nav_menu_args', function () {
            $args['depth'] = 2;
            $args['container'] = 'div';
            $args['fallback_cb'] = 'Tagmeo\Theme\Navigation::fallback';
            $args['menu_class'] = 'nav navbar-nav';
            $args['walker'] = new \Tagmeo\Theme\Navigation;

            return $args;
        });

        register_nav_menus([
            'header_navigation' => __('Header Navigation', 'tagmeo'),
            'footer_navigation' => __('Footer Navigation', 'tagmeo')
        ]);
    }
}
