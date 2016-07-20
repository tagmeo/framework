<?php

namespace Tagmeo\Admin;

class Customizer
{
    public function __construct()
    {
        add_action('customize_register', function ($customizer) {
            $customizer->remove_section('colors');
            $customizer->remove_section('header_image');
            $customizer->remove_section('background_image');

            $customizer->get_setting('blogname')->transport = 'postMessage';
            $customizer->get_setting('blogdescription')->transport = 'postMessage';
            $customizer->get_setting('header_textcolor')->transport = 'postMessage';
        });
    }
}
