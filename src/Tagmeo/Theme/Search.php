<?php

namespace Tagmeo\Theme;

class Search
{
    public function __construct()
    {
        add_action('template_redirect', function () {
            global $wp_rewrite;

            if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->get_search_permastruct()) {
                return;
            }

            if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], '/'.$wp_rewrite->search_base.'/') === false && strpos($_SERVER['REQUEST_URI'], '&') === false) {
                wp_redirect(get_search_link());
                exit();
            }
        });

        add_filter('wpseo_json_ld_search_url', function () {
            return str_replace('/?s=', '/search/', $url);
        });
    }
}
