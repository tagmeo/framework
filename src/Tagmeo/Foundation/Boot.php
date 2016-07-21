<?php

namespace Tagmeo\Foundation;

use Tagmeo\Utils\Validator;

class Boot
{
    protected function loadClasses()
    {
        new \Tagmeo\Admin\Admin;
        new \Tagmeo\Admin\Customizer;
        new \Tagmeo\Admin\Roles;

        new \Tagmeo\Theme\Assets;
        new \Tagmeo\Theme\Cleanup;
        new \Tagmeo\Theme\Menus;
        new \Tagmeo\Theme\Navigation;
        new \Tagmeo\Theme\Search;
        new \Tagmeo\Theme\Sidebar;
        new \Tagmeo\Theme\Template;
        new \Tagmeo\Theme\Wrapper;

        new \Tagmeo\Utils\Utils;
    }

    protected function addFilters()
    {
        // Add your WordPress filters here
    }

    protected function addThemeSupport()
    {
        add_action('after_setup_theme', function () {
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);
            add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);
        });
    }

    protected function addValidatorPatterns()
    {
        $baseUrl = Validator::getPattern('protocol').Validator::getPattern('domain').Validator::getPattern('url_slug');
        $url = $baseUrl.Validator::getPattern('query_string');

        Validator::addPattern('base_url', $baseUrl);
        Validator::addPattern('url', $url);
    }
}
