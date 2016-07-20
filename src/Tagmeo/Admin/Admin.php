<?php

namespace Tagmeo\Admin;

class Admin
{
    public function __construct()
    {
        add_filter('show_admin_bar', function () {
            return false;
        });

        add_action('admin_init', function () {
            remove_submenu_page('themes.php', 'theme-editor.php');
        }, 10, 2);
    }
}
