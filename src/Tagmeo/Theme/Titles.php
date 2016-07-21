<?php

namespace Tagmeo\Theme;

class Titles
{
    public static function pageTitle()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }

            return 'Latest Posts';
        }

        if (is_archive()) {
            return get_the_archive_title();
        }

        if (is_search()) {
            return sprintf('Search Results for %s', get_search_query());
        }

        if (is_404()) {
            return 'Not Found';
        }

        return get_the_title();
    }
}
