<?php

namespace Tagmeo\Admin;

class Customizer
{
    public function __construct()
    {
        add_action('customize_register', function (\WP_Customize_Manager $customizer) {
            $customizer->get_setting('blogname')->transport = 'postMessage';
        });
    }
}
